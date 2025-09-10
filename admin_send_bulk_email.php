<?php
/*
================================================================================
File: admin_send_bulk_email.php
Descrizione: Script backend per inviare email a utenti multipli con statistiche dettagliate.
================================================================================
*/

require_once 'db_connect.php';
require_once 'functions.php';
session_start();

// --- Sicurezza: Controlla se l'utente è l'admin (id = 1) ---
if (!isset($_SESSION["id"]) || $_SESSION["id"] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$user_ids = $data['userIds'] ?? [];
$email_type = $data['email_type'] ?? null;

if (empty($user_ids) || !$email_type) {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti: ID utente o tipo di email non forniti.']);
    exit();
}

$sent_count = 0;
$blocked_count = 0;
$failed_count = 0;
$detailed_errors = [];

foreach ($user_ids as $user_id) {
    // I dati aggiuntivi come 'subject' e 'body' sono passati direttamente
    $result = send_admin_transactional_email($conn, (int)$user_id, $email_type, $data);

    if ($result['success']) {
        // Controlliamo il messaggio per vedere se l'utente ha bloccato le email
        if (strpos($result['message'], 'ha disattivato la ricezione di email') !== false) {
            $blocked_count++;
        } else {
            $sent_count++;
        }
    } else {
        $failed_count++;
        $detailed_errors[] = "ID utente {$user_id}: " . ($result['message'] ?? 'Errore sconosciuto');
    }
}

$response = [
    'success' => $failed_count === 0,
    'message' => "Operazione completata.",
    'stats' => [
        'sent' => $sent_count,
        'blocked' => $blocked_count,
        'failed' => $failed_count,
        'total' => count($user_ids)
    ],
    'errors' => $detailed_errors
];

echo json_encode($response);

$conn->close();
?>