<?php
session_start();
header('Content-Type: application/json');

// Authentication check
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

require_once 'db_connect.php';
require_once 'functions.php'; // <--- FIX: Added this line
require_once 'friends_functions.php';

$user_id = $_SESSION["id"];

// Get search and filter parameters from the GET request
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : null;
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;

try {
    // Use the existing function to get filtered friends
    $friends = get_friends_for_user($conn, $user_id, $search_query, $filter);

    // Also get a fresh list of blocked user IDs to return with the response
    $blocked_user_ids = get_blocked_user_ids($conn, $user_id);

    echo json_encode([
        'success' => true,
        'friends' => $friends,
        'blocked_user_ids' => $blocked_user_ids
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Errore del server: ' . $e->getMessage()]);
}

$conn->close();
?>