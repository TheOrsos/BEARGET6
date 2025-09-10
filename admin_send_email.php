<?php
/*
================================================================================
File: admin_send_email.php
Descrizione: Script backend per inviare una singola email transazionale.
Utilizza la funzione centralizzata in functions.php.
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
$user_id = $data['user_id'] ?? null;
$email_type = $data['email_type'] ?? null;

if (!$user_id || !$email_type) {
    echo json_encode(['success' => false, 'message' => 'Dati mancanti per l\'invio.']);
    exit();
}

// I dati aggiuntivi come 'subject' e 'body' sono passati direttamente
$email_data = $data;

$result = send_admin_transactional_email($conn, $user_id, $email_type, $email_data);

echo json_encode($result);

$conn->close();