<?php
require_once 'db_connect.php';
require_once 'functions.php';
session_start();

header('Content-Type: application/json');

// Sicurezza: controlla se l'utente è loggato
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$fund_id = $data['fund_id'] ?? null;
$user_id = $_SESSION['id'];

if (!$fund_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID del fondo non fornito.']);
    exit;
}

// Recupera i dettagli del fondo per i controlli
$fund = get_shared_fund_details($conn, $fund_id, $user_id);

if (!$fund) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Fondo non trovato o non sei un membro.']);
    exit;
}

// --- REGOLA 1: Il creatore non può abbandonare il fondo ---
if ($fund['creator_id'] == $user_id) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Come creatore del fondo, non puoi abbandonarlo. Puoi archiviarlo o eliminarlo.']);
    exit;
}

// --- REGOLA 2: Se il fondo è attivo, il saldo deve essere zero ---
if ($fund['status'] === 'active') {
    $balances = get_group_balances($conn, $fund_id);
    $user_balance = 0;
    foreach ($balances as $balance) {
        if ($balance['user_id'] == $user_id) {
            $user_balance = $balance['balance'];
            break;
        }
    }

    // Usiamo una piccola tolleranza per problemi di arrotondamento del float
    if (abs($user_balance) > 0.01) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Non puoi lasciare un fondo attivo finché il tuo saldo non è zero. Esegui o partecipa a un saldaconto per azzerare i debiti/crediti.']);
        exit;
    }
}

// --- Se tutti i controlli passano, procedi con l'abbandono ---
$sql = "DELETE FROM shared_fund_members WHERE fund_id = ? AND user_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $fund_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Hai abbandonato il fondo con successo.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore durante labbandono del fondo.']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore nella preparazione della richiesta al database.']);
}

$conn->close();
?>