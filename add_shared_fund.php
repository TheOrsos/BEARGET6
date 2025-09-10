<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["id"];
    $name = trim($_POST['name']);

    // --- Logica di validazione del target migliorata ---
    $target_amount_str = str_replace(',', '.', trim($_POST['target_amount']));

    if (empty($name) || !is_numeric($target_amount_str) || $target_amount_str <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dati non validi. Assicurati che lobiettivo sia un numero positivo.']);
        exit();
    }
    
    $target_amount_float = floatval($target_amount_str);
    // --- Fine logica migliorata ---

    $conn->begin_transaction();
    try {
        $sql_fund = "INSERT INTO shared_funds (name, target_amount, creator_id) VALUES (?, ?, ?)";
        $stmt_fund = $conn->prepare($sql_fund);
        $stmt_fund->bind_param("sdi", $name, $target_amount_float, $user_id);
        $stmt_fund->execute();
        $fund_id = $conn->insert_id;
        $stmt_fund->close();

        $sql_member = "INSERT INTO shared_fund_members (fund_id, user_id) VALUES (?, ?)";
        $stmt_member = $conn->prepare($sql_member);
        $stmt_member->bind_param("ii", $fund_id, $user_id);
        $stmt_member->execute();
        $stmt_member->close();

        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Fondo creato con successo!',
            'fund' => [
                'id' => $fund_id,
                'name' => $name,
                'target_amount' => $target_amount_float,
                'total_contributed' => 0.00,
                'creator_id' => $user_id
            ]
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        // Per debug, potresti voler loggare $e->getMessage()
        echo json_encode(['success' => false, 'message' => 'Errore durante la creazione del fondo.']);
    }
    
    $conn->close();
    exit();
}
?>