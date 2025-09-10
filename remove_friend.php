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
$user_id = $_SESSION['id'];
$friend_id = $data['friend_id'] ?? null;

if (!$friend_id || !is_numeric($friend_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID amico non valido.']);
    exit;
}

// --- NUOVO: Controlla il saldo prima di rimuovere l'amico ---
require_once 'friends_functions.php';
$balance = get_loan_balance($conn, $user_id, $friend_id);
if ($balance != 0) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Non puoi rimuovere un amico con un saldo di prestiti/debiti in sospeso.']);
    exit;
}
// --- FINE NUOVO ---

$conn->begin_transaction();

try {
    // Rimuovi l'amicizia
    $sql_delete_friendship = "DELETE FROM friendships WHERE (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)";
    $stmt = $conn->prepare($sql_delete_friendship);
    $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $stmt->execute();
    $friendship_deleted = $stmt->affected_rows > 0;
    $stmt->close();

    if (!$friendship_deleted) {
        throw new Exception('Nessuna amicizia trovata da rimuovere.');
    }

    // Rimuovi la cronologia della chat
    $sql_delete_chat = "DELETE FROM chat_messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)";
    $stmt_chat = $conn->prepare($sql_delete_chat);
    $stmt_chat->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $stmt_chat->execute();
    $stmt_chat->close();
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Amicizia rimossa con successo.']);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>