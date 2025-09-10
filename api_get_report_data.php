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

// Get search and filter parameters from the GET request
$raw_account_ids = isset($_GET['account_ids']) ? (is_array($_GET['account_ids']) ? $_GET['account_ids'] : [$_GET['account_ids']]) : [];

$filters = [
    'start_date' => $_GET['start_date'] ?? null,
    'end_date'   => $_GET['end_date'] ?? null,
    // FIX: Filter out empty values from the account_ids array
    'account_ids' => array_filter(array_map('intval', $raw_account_ids)),
    'tag_id'     => isset($_GET['tag_id']) ? $_GET['tag_id'] : null,
];

try {
    // Fetch data for all three charts using existing functions
    $expenses_by_category_data = get_expenses_by_category($conn, $user_id, $filters);
    $income_expense_trend_data = get_income_expense_trend($conn, $user_id, $filters);
    $net_worth_trend_data = get_net_worth_trend($conn, $user_id, $filters);

    echo json_encode([
        'success' => true,
        'expensesByCategory' => $expenses_by_category_data,
        'incomeExpenseTrend' => $income_expense_trend_data,
        'netWorthTrend' => $net_worth_trend_data
    ]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Errore del server: ' . $e->getMessage()]);
}

$conn->close();
?>