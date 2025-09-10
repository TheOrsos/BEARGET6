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
require_once 'functions.php';

$user_id = $_SESSION["id"];

// Collect filter parameters from the GET request
$filters = [
    'text_search' => isset($_GET['text_search']) ? trim($_GET['text_search']) : null,
    'id_search'   => isset($_GET['id_search']) ? trim($_GET['id_search']) : null,
    'date_search' => isset($_GET['date_search']) ? trim($_GET['date_search']) : null,
    'sort'        => isset($_GET['sort']) ? $_GET['sort'] : 'latest'
];

try {
    // Use the existing function to get filtered notes
    $notes = get_notes_for_user($conn, $user_id, $filters);

    echo json_encode([
        'success' => true,
        'notes' => $notes
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Errore del server: ' . $e->getMessage()]);
}

$conn->close();
?>