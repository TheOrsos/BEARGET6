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
$blocker_id = $_SESSION['id'];
$blocked_id = $data['friend_id'] ?? null;

if (!$blocked_id || !is_numeric($blocked_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID amico non valido.']);
    exit;
}

if ($blocker_id == $blocked_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Non puoi bloccare te stesso.']);
    exit;
}

// Controlla se il blocco esiste già
$sql_check = "SELECT id FROM user_blocks WHERE blocker_id = ? AND blocked_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $blocker_id, $blocked_id);
$stmt_check->execute();
$stmt_check->store_result();
$is_already_blocked = $stmt_check->num_rows > 0;
$stmt_check->close();

if ($is_already_blocked) {
    // L'utente è già bloccato, quindi lo sblocchiamo (DELETE)
    $sql = "DELETE FROM user_blocks WHERE blocker_id = ? AND blocked_id = ?";
    $action_message = "Utente sbloccato.";
    $new_status = 'unblocked';
} else {
    // L'utente non è bloccato, quindi lo blocchiamo (INSERT)
    $sql = "INSERT INTO user_blocks (blocker_id, blocked_id) VALUES (?, ?)";
    $action_message = "Utente bloccato.";
    $new_status = 'blocked';
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $blocker_id, $blocked_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => $action_message, 'new_status' => $new_status]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Si è verificato un errore.']);
}

$stmt->close();
$conn->close();
?>