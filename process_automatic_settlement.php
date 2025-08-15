<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fund_id'])) {
    $fund_id = $_POST['fund_id'];
    $user_id = $_SESSION['id'];
    $payments_data = $_POST['payments'] ?? [];

    $conn->begin_transaction();

    try {
        // --- 1. Security and Fund Status Check ---
        $fund = get_shared_fund_details($conn, $fund_id, $user_id);
        if (!$fund) {
            throw new Exception("Fondo non trovato o accesso non autorizzato.");
        }
        if ($fund['status'] !== 'settling_auto') {
            throw new Exception("Questo fondo non è in modalità di saldaconto automatico.");
        }

        // --- 2. Get or Create a Category for Settlement Transactions ---
        $category_name = "Regolamento Fondo";
        $category = get_category_by_name($conn, $category_name, $user_id);
        if (!$category) {
            $sql_create_cat = "INSERT INTO categories (user_id, name, type, icon) VALUES (?, ?, 'expense', '⚖️')";
            $stmt_create_cat = $conn->prepare($sql_create_cat);
            $stmt_create_cat->bind_param("is", $user_id, $category_name);
            $stmt_create_cat->execute();
            $category_id = $stmt_create_cat->insert_id;
            $stmt_create_cat->close();
        } else {
            $category_id = $category['id'];
        }

        $income_category = get_category_by_name($conn, $category_name, $user_id);
        if (!$income_category) {
            $sql_create_cat = "INSERT INTO categories (user_id, name, type, icon) VALUES (?, ?, 'income', '⚖️')";
            $stmt_create_cat = $conn->prepare($sql_create_cat);
            $stmt_create_cat->bind_param("is", $user_id, $category_name);
            $stmt_create_cat->execute();
            $income_category_id = $stmt_create_cat->insert_id;
            $stmt_create_cat->close();
        } else {
            $income_category_id = $income_category['id'];
        }


        // --- 3. Process each payment ---
        $settlement_payments = get_settlement_payments($conn, $fund_id);

        $sql_insert_tx = "INSERT INTO transactions (user_id, account_id, category_id, amount, type, description, transaction_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_tx = $conn->prepare($sql_insert_tx);

        $today = date('Y-m-d');

        foreach ($settlement_payments as $payment) {
            if ($payment['from_user_id'] == $payment['to_user_id']) continue; // Skip withdrawals

            $payment_id = $payment['id'];
            if (!isset($payments_data[$payment_id])) {
                // This could happen if a user hasn't selected their account yet.
                // For simplicity, we'll throw an error. A more complex implementation could save partial progress.
                throw new Exception("Dati mancanti per il pagamento ID " . $payment_id . ". Assicurati che tutti abbiano selezionato il proprio conto.");
            }

            $from_user_id = $payment['from_user_id'];
            $to_user_id = $payment['to_user_id'];
            $amount = $payment['amount'];
            $from_account_id = $payments_data[$payment_id]['from_account'];
            $to_account_id = $payments_data[$payment_id]['to_account'];

            // Create Expense Transaction for Payer
            $expense_amount = -$amount;
            $expense_desc = "Pagamento a " . $payment['to_username'] . " per fondo '" . $fund['name'] . "'";
            $stmt_insert_tx->bind_param("iiidsss", $from_user_id, $from_account_id, $category_id, $expense_amount, 'expense', $expense_desc, $today);
            $stmt_insert_tx->execute();

            // Create Income Transaction for Payee
            $income_desc = "Pagamento da " . $payment['from_username'] . " per fondo '" . $fund['name'] . "'";
            $stmt_insert_tx->bind_param("iiidsss", $to_user_id, $to_account_id, $income_category_id, $amount, 'income', $income_desc, $today);
            $stmt_insert_tx->execute();
        }
        $stmt_insert_tx->close();

        // --- 4. Archive the fund ---
        $sql_archive = "UPDATE shared_funds SET status = 'archived' WHERE id = ?";
        $stmt_archive = $conn->prepare($sql_archive);
        $stmt_archive->bind_param("i", $fund_id);
        $stmt_archive->execute();
        $stmt_archive->close();

        // --- 5. (Optional) Delete settlement payment records now that they are processed ---
        // $sql_delete = "DELETE FROM settlement_payments WHERE fund_id = ?";
        // $stmt_delete = $conn->prepare($sql_delete);
        // $stmt_delete->bind_param("i", $fund_id);
        // $stmt_delete->execute();
        // $stmt_delete->close();

        $conn->commit();
        header("location: fund_details.php?id=" . $fund_id . "&message=Saldaconto completato e transazioni create con successo!&type=success");

    } catch (Exception $e) {
        $conn->rollback();
        header("location: fund_details.php?id=" . $fund_id . "&message=Errore: " . $e->getMessage() . "&type=error");
    } finally {
        $conn->close();
    }
} else {
    header("location: shared_funds.php");
    exit;
}
?>
