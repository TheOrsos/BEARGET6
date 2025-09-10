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
$requester_id = $_SESSION['id'];
$lender_id = $data['lender_id'] ?? null;
$amount = $data['amount'] ?? null;
$requester_account_id = $data['requester_account_id'] ?? null; // <-- NUOVA VARIABILE

// Validazione
if (!$lender_id || !is_numeric($lender_id) || !$amount || !is_numeric($amount) || $amount <= 0 || !$requester_account_id || !is_numeric($requester_account_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dati non validi. Assicurati di aver compilato tutti i campi.']);
    exit;
}

if ($requester_id == $lender_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Non puoi chiedere un prestito a te stesso.']);
    exit;
}

// Controlla se gli utenti sono bloccati
if (check_block_status($conn, $requester_id, $lender_id)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Azione non permessa. Uno degli utenti ha bloccato laltro.']);
    exit;
}

// Inserisci la richiesta di prestito
$sql = "INSERT INTO loan_requests (requester_id, lender_id, amount, requester_account_id, status) VALUES (?, ?, ?, ?, 'pending')";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("iidi", $requester_id, $lender_id, $amount, $requester_account_id);
    
    if ($stmt->execute()) {
        $loan_id = $stmt->insert_id;
        
        // Crea una notifica per chi deve prestare i soldi
        $requester_username = $_SESSION['username'];
        $formatted_amount = number_format($amount, 2, ',', '.');
        $notification_message = htmlspecialchars($requester_username) . " ti ha chiesto un prestito di €{$formatted_amount}.";
        create_notification($conn, $lender_id, 'loan_request', $notification_message, $loan_id);
        
        echo json_encode(['success' => true, 'message' => 'Richiesta di prestito inviata con successo!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore durante linvio della richiesta.']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nella preparazione della richiesta al database.']);
}

$conn->close();
?>