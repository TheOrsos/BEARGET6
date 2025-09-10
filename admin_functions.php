<?php

function get_users_paginated_and_searched_ajax($conn, $filters) {
    $page = $filters['page'] ?? 1;
    $users_per_page = $filters['limit'] ?? 20;
    $offset = ($page - 1) * $users_per_page;

    $base_sql = "FROM users";
    $where_conditions = [];
    $params = [];
    $param_types = "";

    // Filtro per ID
    if (!empty($filters['id'])) {
        $where_conditions[] = "id = ?";
        $params[] = $filters['id'];
        $param_types .= "i";
    }

    // Filtro per termine di ricerca (username o email)
    if (!empty($filters['search_term'])) {
        $search_query = "%" . $filters['search_term'] . "%";
        $where_conditions[] = "(username LIKE ? OR email LIKE ?)";
        $params[] = $search_query;
        $params[] = $search_query;
        $param_types .= "ss";
    }

    // Filtro per stato abbonamento
    if (!empty($filters['subscription_status'])) {
        $where_conditions[] = "subscription_status = ?";
        $params[] = $filters['subscription_status'];
        $param_types .= "s";
    }

    // Filtro per stato account
    if (!empty($filters['account_status'])) {
        $where_conditions[] = "account_status = ?";
        $params[] = $filters['account_status'];
        $param_types .= "s";
    }

    // Filtro per stato email
    if (isset($filters['receives_emails']) && $filters['receives_emails'] !== '') {
        $where_conditions[] = "receives_emails = ?";
        $params[] = $filters['receives_emails'];
        $param_types .= "i";
    }

    $where_clause = "";
    if (!empty($where_conditions)) {
        $where_clause = " WHERE " . implode(" AND ", $where_conditions);
    }

    // Query per contare il totale degli utenti (filtrati)
    $sql_count = "SELECT COUNT(id) as total " . $base_sql . $where_clause;
    $stmt_count = $conn->prepare($sql_count);
    if (!empty($params)) {
        $stmt_count->bind_param($param_types, ...$params);
    }
    $stmt_count->execute();
    $total_users = $stmt_count->get_result()->fetch_assoc()['total'];
    $total_pages = ceil($total_users / $users_per_page);
    $stmt_count->close();

    // Query per recuperare gli utenti della pagina corrente
    $sql_users = "SELECT id, username, email, subscription_status, account_status, suspended_until, last_login_at, stripe_customer_id, stripe_subscription_id, subscription_end_date, created_at, friend_code, receives_emails " . $base_sql . $where_clause . " ORDER BY id ASC LIMIT ? OFFSET ?";

    $params_with_pagination = $params;
    $params_with_pagination[] = $users_per_page;
    $params_with_pagination[] = $offset;
    $param_types_with_pagination = $param_types . "ii";

    $stmt = $conn->prepare($sql_users);
    if (!empty($param_types_with_pagination)) {
        $stmt->bind_param($param_types_with_pagination, ...$params_with_pagination);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();

    return [
        'users' => $users,
        'total_pages' => $total_pages,
        'current_page' => $page
    ];
}

?>
