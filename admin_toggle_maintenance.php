<?php
require_once 'db_connect.php';
require_once 'functions.php';
session_start();

header('Content-Type: application/json');

// Sicurezza: Solo l'admin può eseguire questa azione
if (!isset($_SESSION["id"]) || $_SESSION["id"] != 1) {
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

// Valori di default
$status = isset($data['status']) && $data['status'] === 'on' ? 'on' : 'off';
$message = $data['message'] ?? ''; // Il messaggio personalizzato è opzionale

// --- Aggiorna lo stato della manutenzione ---
$stmt_mode = $conn->prepare("UPDATE app_settings SET setting_value = ? WHERE setting_key = 'maintenance_mode'");
if (!$stmt_mode) {
    echo json_encode(['success' => false, 'message' => 'Errore preparazione statement per status.']);
    exit();
}
$stmt_mode->bind_param("s", $status);
$success_mode = $stmt_mode->execute();
$stmt_mode->close();

// --- Aggiorna il messaggio di manutenzione ---
// Lo aggiorniamo anche se lo status è 'off', così viene salvato per la prossima volta.
$stmt_msg = $conn->prepare("UPDATE app_settings SET setting_value = ? WHERE setting_key = 'maintenance_message'");
if (!$stmt_msg) {
    echo json_encode(['success' => false, 'message' => 'Errore preparazione statement per messaggio.']);
    exit();
}
$stmt_msg->bind_param("s", $message);
$success_msg = $stmt_msg->execute();
$stmt_msg->close();

if ($success_mode && $success_msg) {
    $status_text = $status === 'on' ? 'attivata' : 'disattivata';
    echo json_encode(['success' => true, 'message' => "Modalità manutenzione {$status_text} con successo!"]);
} else {
    echo json_encode(['success' => false, 'message' => 'Errore durante laggiornamento delle impostazioni.']);
}

$conn->close();
?>