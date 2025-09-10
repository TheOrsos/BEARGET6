<?php
require_once 'db_connect.php';
require_once 'functions.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$lender_id = $_SESSION['id']; // L'utente che sta compiendo l'azione è il prestatore (lender)
$loan_id = $data['loan_id'] ?? null;
$action = $data['action'] ?? null; // 'accept' o 'reject'

if (!$loan_id || !$action || !in_array($action, ['accept', 'reject'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati della richiesta non validi.']);
    exit;
}

// --- Recupera i dettagli della richiesta di prestito ---
$sql_get_loan = "SELECT * FROM loan_requests WHERE id = ? AND lender_id = ? AND status = 'pending'";
$stmt_get_loan = $conn->prepare($sql_get_loan);
$stmt_get_loan->bind_param("ii", $loan_id, $lender_id);
$stmt_get_loan->execute();
$result = $stmt_get_loan->get_result();
$loan_request = $result->fetch_assoc();
$stmt_get_loan->close();

if (!$loan_request) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Richiesta di prestito non trovata, già gestita o non sei autorizzato.']);
    exit;
}

$requester_id = $loan_request['requester_id'];
$amount = $loan_request['amount'];
$requester_account_id = $loan_request['requester_account_id'];

$lender_username = $_SESSION['username'];
$requester = get_user_by_id($conn, $requester_id);
$requester_username = $requester['username'];


if ($action === 'reject') {
    // --- Logica per Rifiutare il Prestito ---
    $sql_update = "UPDATE loan_requests SET status = 'rejected', resolved_at = NOW() WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $loan_id);
    if ($stmt_update->execute()) {
        // Notifica al richiedente che il prestito è stato rifiutato
        $notification_message = htmlspecialchars($lender_username) . " ha rifiutato la tua richiesta di prestito.";
        create_notification($conn, $requester_id, 'loan_rejected', $notification_message, $loan_id);
        echo json_encode(['success' => true, 'message' => 'Richiesta di prestito rifiutata.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore nellaggiornare la richiesta.']);
    }
    $stmt_update->close();

} elseif ($action === 'accept') {
    // --- Logica per Accettare il Prestito ---
    $lender_account_id = $data['lender_account_id'] ?? null;
    if (!$lender_account_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Devi specificare un conto da cui inviare i fondi.']);
        exit;
    }

    $conn->begin_transaction();
    try {
        // 1. Aggiorna lo stato della richiesta di prestito
        $sql_update_loan = "UPDATE loan_requests SET status = 'accepted', resolved_at = NOW() WHERE id = ?";
        $stmt_update_loan = $conn->prepare($sql_update_loan);
        $stmt_update_loan->bind_param("i", $loan_id);
        $stmt_update_loan->execute();
        $stmt_update_loan->close();

        // 2. Crea la transazione di uscita per il prestatore (lender)
        $desc_lender = "Prestito concesso a " . htmlspecialchars($requester_username);
        $sql_lender_tx = "INSERT INTO transactions (user_id, account_id, amount, type, description, transaction_date) VALUES (?, ?, ?, 'expense', ?, NOW())";
        $stmt_lender_tx = $conn->prepare($sql_lender_tx);
        $negative_amount = -abs($amount);
        $stmt_lender_tx->bind_param("iids", $lender_id, $lender_account_id, $negative_amount, $desc_lender);
        $stmt_lender_tx->execute();
        $stmt_lender_tx->close();

        // 3. Crea la transazione di entrata per il richiedente (requester)
        $desc_requester = "Prestito ricevuto da " . htmlspecialchars($lender_username);
        $sql_requester_tx = "INSERT INTO transactions (user_id, account_id, amount, type, description, transaction_date) VALUES (?, ?, ?, 'income', ?, NOW())";
        $stmt_requester_tx = $conn->prepare($sql_requester_tx);
        $positive_amount = abs($amount);
        $stmt_requester_tx->bind_param("iids", $requester_id, $requester_account_id, $positive_amount, $desc_requester);
        $stmt_requester_tx->execute();
        $stmt_requester_tx->close();
        
        // 4. Notifica al richiedente che il prestito è stato accettato
        $notification_message = htmlspecialchars($lender_username) . " ha accettato la tua richiesta di prestito di €" . number_format($amount, 2, ',', '.');
        create_notification($conn, $requester_id, 'loan_accepted', $notification_message, $loan_id);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Prestito approvato e fondi trasferiti!']);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        error_log("Errore transazione prestito: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Errore critico durante la transazione del prestito.']);
    }
}

$conn->close();
?>