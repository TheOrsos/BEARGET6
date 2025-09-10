<?php
header('Content-Type: application/json');
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['receiver_id']) && isset($_POST['message'])) {
    $sender_id = $_SESSION['id'];
    $sender_username = $_SESSION['username'];
    $receiver_id = (int)$_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (empty($message)) {
        $response['message'] = 'Message cannot be empty.';
        echo json_encode($response);
        exit;
    }

    // --- NUOVO CONTROLLO BLOCCO UTENTE ---
    if (check_block_status($conn, $sender_id, $receiver_id)) {
        $response['message'] = 'Azione non permessa. Uno degli utenti ha bloccato laltro.';
        echo json_encode($response);
        exit;
    }
    // --- FINE CONTROLLO ---

    $sql_check_friendship = "SELECT id FROM friendships WHERE status = 'accepted' AND ((user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?))";
    $stmt_check = $conn->prepare($sql_check_friendship);
    $stmt_check->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows == 0) {
        $response['message'] = 'You can only send messages to friends.';
        echo json_encode($response);
        exit;
    }
    $stmt_check->close();

    $sql_insert = "INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iis", $sender_id, $receiver_id, $message);

    if ($stmt_insert->execute()) {
        $notification_message = "Hai un nuovo messaggio da " . htmlspecialchars($sender_username) . ".";
        create_notification($conn, $receiver_id, 'chat_message', $notification_message, $sender_id);
        
        $response['status'] = 'success';
        $response['message'] = 'Message sent.';
    } else {
        $response['message'] = 'Failed to send message.';
    }

    $stmt_insert->close();
} else {
    $response['message'] = 'Invalid request method or missing parameters.';
}

$conn->close();
echo json_encode($response);
?>