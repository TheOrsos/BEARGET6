<?php
// Contains functions specific to the friends and chat system.

/**
 * Calculates the net loan balance between two users.
 * @return float The net balance. Positive if user2 owes user1, negative if user1 owes user2.
 */
function get_loan_balance($conn, $user_id1, $user_id2) {
    $total_lent = 0;
    $total_borrowed = 0;

    // Calculate total lent from user1 to user2
    $sql_lent = "SELECT IFNULL(SUM(amount), 0) AS total FROM loan_requests WHERE lender_id = ? AND requester_id = ? AND status = 'accepted'";
    $stmt_lent = $conn->prepare($sql_lent);
    $stmt_lent->bind_param("ii", $user_id1, $user_id2);
    $stmt_lent->execute();
    $result_lent = $stmt_lent->get_result();
    if ($row_lent = $result_lent->fetch_assoc()) {
        $total_lent = $row_lent['total'];
    }
    $stmt_lent->close();

    // Calculate total borrowed by user1 from user2
    $sql_borrowed = "SELECT IFNULL(SUM(amount), 0) AS total FROM loan_requests WHERE requester_id = ? AND lender_id = ? AND status = 'accepted'";
    $stmt_borrowed = $conn->prepare($sql_borrowed);
    $stmt_borrowed->bind_param("ii", $user_id1, $user_id2);
    $stmt_borrowed->execute();
    $result_borrowed = $stmt_borrowed->get_result();
    if ($row_borrowed = $result_borrowed->fetch_assoc()) {
        $total_borrowed = $row_borrowed['total'];
    }
    $stmt_borrowed->close();

    return $total_lent - $total_borrowed;
}


/**
 * Gets the list of friends for a user, with optional searching and filtering.
 */
function get_friends_for_user($conn, $user_id, $search_query = null, $filter = null) {
    $params = [];
    $types = "";

    $sql = "SELECT
                u.id,
                u.username,
                u.email,
                u.friend_code,
                u.profile_picture_path,
                (SELECT IFNULL(SUM(lr.amount), 0)
                 FROM loan_requests lr
                 WHERE lr.lender_id = ? AND lr.requester_id = u.id AND lr.status = 'accepted') AS total_lent_to_friend,
                (SELECT IFNULL(SUM(lr.amount), 0)
                 FROM loan_requests lr
                 WHERE lr.requester_id = ? AND lr.lender_id = u.id AND lr.status = 'accepted') AS total_borrowed_from_friend
            FROM users u
            WHERE u.id IN (
                SELECT f.user_id_2 FROM friendships f WHERE f.user_id_1 = ? AND f.status = 'accepted'
                UNION
                SELECT f.user_id_1 FROM friendships f WHERE f.user_id_2 = ? AND f.status = 'accepted'
            )";
    
    $params = [$user_id, $user_id, $user_id, $user_id];
    $types = "iiii";

    if ($search_query) {
        $sql .= " AND (u.username LIKE ? OR u.email LIKE ?)";
        $search_term = "%" . $search_query . "%";
        $params[] = $search_term;
        $params[] = $search_term;
        $types .= "ss";
    }

    $having_clauses = [];
    if ($filter) {
        switch ($filter) {
            case 'blocked':
                $sql .= " AND u.id IN (SELECT blocked_user_id FROM blocked_users WHERE user_id = ?)";
                $params[] = $user_id;
                $types .= "i";
                break;
            case 'debts': // I owe them
                $having_clauses[] = "total_borrowed_from_friend > 0";
                break;
            case 'loans': // They owe me
                $having_clauses[] = "total_lent_to_friend > 0";
                break;
        }
    }

    if (!empty($having_clauses)) {
        $sql .= " HAVING " . implode(' AND ', $having_clauses);
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Errore nella preparazione della query: " . $conn->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $friends = [];
    while ($row = $result->fetch_assoc()) {
        $friends[] = $row;
    }
    $stmt->close();
    return $friends;
}

/**
 * Gets the unread message counts for a user, grouped by sender.
 */
function get_unread_message_counts($conn, $user_id) {
    $counts = [];
    $sql = "SELECT sender_id, COUNT(id) as unread_count
            FROM chat_messages
            WHERE receiver_id = ? AND is_read = 0
            GROUP BY sender_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $counts[$row['sender_id']] = $row['unread_count'];
    }

    $stmt->close();
    return $counts;
}
?>