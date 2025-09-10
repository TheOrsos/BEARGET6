<?php
// File: update_account.php
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
    $account_id = trim($_POST['account_id']);
    $name = trim($_POST['name']);

    // --- Logica di validazione del saldo migliorata ---
    // Sostituisce la virgola con il punto per i decimali
    $initial_balance_str = str_replace(',', '.', trim($_POST['initial_balance']));

    if (empty($account_id) || empty($name) || !is_numeric($initial_balance_str)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Dati non validi. Assicurati che il saldo sia un numero.']);
        exit();
    }
    
    // Converte la stringa in un numero float
    $initial_balance_float = floatval($initial_balance_str);
    // --- Fine logica migliorata ---

    $sql = "UPDATE accounts SET name = ?, initial_balance = ? WHERE id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Usa 'd' per double (float) e passa la variabile convertita
        $stmt->bind_param("sdii", $name, $initial_balance_float, $account_id, $user_id);
        
        if ($stmt->execute()) {
            // Dopo l'aggiornamento, recupera tutti i dati aggiornati per la UI
            $updated_account_data = get_account_by_id($conn, $account_id, $user_id);
            $updated_account_data['balance'] = get_account_balance($conn, $account_id);

            echo json_encode([
                'success' => true,
                'message' => 'Conto aggiornato con successo!',
                'account' => $updated_account_data
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Errore durante laggiornamento del conto.']);
        }
        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Errore nella preparazione della richiesta al database.']);
    }
    $conn->close();
    exit();
}
?>