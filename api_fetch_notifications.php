<?php
require_once 'db_connect.php';
require_once 'functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(['success' => false, 'notifications' => []]);
    exit;
}

$user_id = $_SESSION['id'];

$notifications = get_unread_notifications($conn, $user_id);

if (!empty($notifications)) {
    mark_notifications_as_read($conn, $user_id);
}

echo json_encode(['success' => true, 'notifications' => $notifications]);

$conn->close();
?>