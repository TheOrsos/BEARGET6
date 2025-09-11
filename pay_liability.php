<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $liability_id = trim($_POST['liability_id']);
    $amount = trim($_POST['amount']);
    $account_id = trim($_POST['account_id']);

    // --- Validation ---
    if (empty($liability_id) || empty($amount) || empty($account_id) || !is_numeric($amount) || $amount <= 0) {
        header("location: debts.php?error=" . urlencode("Richiesta non valida. Dati mancanti o non corretti."));
        exit;
    }

    // --- Security Checks ---
    // Check if user owns the liability
    if (!user_owns_liability($conn, $user_id, $liability_id)) {
        header("location: debts.php?error=" . urlencode("Azione non autorizzata."));
        exit;
    }
    // Check if user owns the account
    $account = get_account_by_id($conn, $account_id, $user_id);
    if (!$account) {
        header("location: debts.php?error=" . urlencode("Conto non trovato o non autorizzato."));
        exit;
    }

    $conn->begin_transaction();

    try {
        // 1. Update the liability's current_balance
        $sql_update = "UPDATE liabilities SET current_balance = current_balance - ? WHERE id = ? AND user_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("dii", $amount, $liability_id, $user_id);
        $stmt_update->execute();

        if ($stmt_update->affected_rows === 0) {
            throw new Exception("Aggiornamento del debito fallito.");
        }
        $stmt_update->close();

        // 2. Create a new expense transaction
        $sql_insert_tx = "INSERT INTO transactions (user_id, account_id, amount, type, description, transaction_date) VALUES (?, ?, ?, 'expense', ?, NOW())";
        $stmt_insert_tx = $conn->prepare($sql_insert_tx);
        $negative_amount = -abs($amount);

        // Get liability name for the description
        $liability_details_sql = "SELECT name FROM liabilities WHERE id = ?";
        $stmt_details = $conn->prepare($liability_details_sql);
        $stmt_details->bind_param("i", $liability_id);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();
        $liability_name = ($result_details->fetch_assoc())['name'] ?? 'Sconosciuto';
        $stmt_details->close();

        $description = "Pagamento per: " . $liability_name;

        $stmt_insert_tx->bind_param("iids", $user_id, $account_id, $negative_amount, $description);
        $stmt_insert_tx->execute();
        $stmt_insert_tx->close();

        // 3. Commit the transaction
        $conn->commit();

        header("location: debts.php?success=" . urlencode("Pagamento effettuato con successo!"));
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Errore durante il pagamento del debito: " . $e->getMessage());
        header("location: debts.php?error=" . urlencode("Oops! Qualcosa è andato storto durante la transazione."));
        exit;
    }
}

$conn->close();
?>
