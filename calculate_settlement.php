<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fund_id'])) {
    $fund_id = $_POST['fund_id'];
    $user_id = $_SESSION['id'];

    $conn->begin_transaction();

    try {
        // --- Security and Status Check ---
        $fund = get_shared_fund_details($conn, $fund_id, $user_id);
        if (!$fund || $fund['creator_id'] != $user_id) {
            throw new Exception("Azione non autorizzata.");
        }
        if ($fund['status'] !== 'active') {
            throw new Exception("Il fondo non è attivo e non può essere saldato.");
        }

        // --- Calculations ---
        $cash_balance = get_fund_cash_balance($conn, $fund_id);
        $debt_balances = get_group_balances($conn, $fund_id);

        // --- Surplus Distribution ---
        if ($cash_balance > 0) {
            $total_credit = 0;
            foreach ($debt_balances as $b) {
                if ($b['balance'] > 0) {
                    $total_credit += $b['balance'];
                }
            }

            if ($total_credit > 0) {
                foreach ($debt_balances as &$b) {
                    if ($b['balance'] > 0) {
                        $share_of_surplus = ($b['balance'] / $total_credit) * $cash_balance;
                        $b['balance'] -= $share_of_surplus; // Reduce what they are owed because they get cash
                    }
                }
                unset($b); // Unset reference
            }
        }

        // --- Simplify Debts ---
        $settlement_payments = simplify_debts($debt_balances);

        // --- Store Settlement Payments ---
        if (!empty($settlement_payments)) {
            $sql_insert_payment = "INSERT INTO settlement_payments (fund_id, from_user_id, to_user_id, amount) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_payment);
            foreach ($settlement_payments as $payment) {
                $stmt_insert->bind_param("iiid", $fund_id, $payment['from'], $payment['to'], $payment['amount']);
                $stmt_insert->execute();
            }
            $stmt_insert->close();
        }

        // --- Update Fund Status ---
        $sql_update_status = "UPDATE shared_funds SET status = 'settling' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_status);
        $stmt_update->bind_param("i", $fund_id);
        $stmt_update->execute();
        $stmt_update->close();

        $conn->commit();
        header("location: fund_details.php?id=" . $fund_id . "&message=Il processo di chiusura del fondo è iniziato. Ora i membri devono confermare i pagamenti.&type=success");

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