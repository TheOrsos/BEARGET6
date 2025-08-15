<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_GET['fund_id'])) {
    http_response_code(403);
    exit;
}

require_once 'db_connect.php';

$fund_id = (int)$_GET['fund_id'];
$user_id = $_SESSION['id'];

// Security check: ensure the user is a member of the fund they're listening to.
$sql_check = "SELECT COUNT(*) FROM shared_fund_members WHERE fund_id = ? AND user_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $fund_id, $user_id);
$stmt_check->execute();
$member_count = 0;
$stmt_check->bind_result($member_count);
$stmt_check->fetch();
$stmt_check->close();

if ($member_count === 0) {
    http_response_code(403);
    exit;
}

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

// Send a simple "connected" message initially
echo "event: connected\n";
echo "data: Connection established\n\n";
flush();

$last_update_check = null;

function get_latest_timestamp($conn, $fund_id) {
    $sql = "SELECT MAX(latest_date) as last_update
            FROM (
                SELECT MAX(created_at) as latest_date FROM group_expenses WHERE fund_id = ?
                UNION ALL
                SELECT MAX(created_at) as latest_date FROM shared_fund_contributions WHERE fund_id = ?
            ) as updates";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $fund_id, $fund_id);
    $stmt->execute();
    $result = null;
    $stmt->bind_result($result);
    $stmt->fetch();
    $stmt->close();
    return $result;
}

$last_update_check = get_latest_timestamp($conn, $fund_id);


while (true) {
    // Check for connection abort from client
    if (connection_aborted()) {
        $conn->close();
        exit();
    }

    $current_timestamp = get_latest_timestamp($conn, $fund_id);

    // If the timestamp has changed, send an update event
    if ($last_update_check !== $current_timestamp) {
        echo "event: update\n";
        echo "data: " . json_encode(['status' => 'new_data']) . "\n\n";
        flush();
        $last_update_check = $current_timestamp;
    }

    // Sleep for a short period to avoid hammering the database
    sleep(2);
}

?>
