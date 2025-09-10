<?php
session_start();
header('Content-Type: application/json');

// Authentication check
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

require_once 'db_connect.php';
require_once 'functions.php';

$user_id = $_SESSION["id"];

// Collect filter parameters from the GET request
$filters = [
    'start_date' => $_GET['start_date'] ?? null,
    'end_date'   => $_GET['end_date'] ?? null,
    'description' => $_GET['description'] ?? null,
    'category_id' => isset($_GET['category_id']) ? (int)$_GET['category_id'] : null,
    'account_id'  => isset($_GET['account_id']) ? (int)$_GET['account_id'] : null,
    'tag_id'      => isset($_GET['tag_id']) ? (int)$_GET['tag_id'] : null,
];

// Remove empty filters so the function doesn't process them
foreach ($filters as $key => $value) {
    if (empty($value)) {
        unset($filters[$key]);
    }
}

try {
    // Use the existing function to get filtered transactions
    $transactions = get_all_transactions($conn, $user_id, $filters);

    echo json_encode([
        'success' => true,
        'transactions' => $transactions
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Errore del server: ' . $e->getMessage()]);
}

$conn->close();
?>
