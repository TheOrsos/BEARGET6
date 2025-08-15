<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
require_once 'db_connect.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- DATA RETRIEVAL ---
    $fund_id = $_POST['fund_id'];
    $description = trim($_POST['description']);
    $amount = (float)$_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $split_method = $_POST['split_method'];

    // --- VALIDATION (BASIC) ---
    if (empty($fund_id) || empty($description) || $amount <= 0 || empty($expense_date) || empty($split_method)) {
        header("location: fund_details.php?id=" . $fund_id . "&message=Tutti i campi principali sono obbligatori.&type=error");
        exit;
    }

    $conn->begin_transaction();

    try {
        // --- SECURITY CHECK ---
        $members = get_fund_members($conn, $fund_id);
        $member_ids = array_column($members, 'id');
        if (!in_array($_SESSION['id'], $member_ids)) {
            throw new Exception("Accesso non autorizzato.");
        }

        $paid_by_user_id = $_POST['paid_by_user_id'];
        if (!in_array($paid_by_user_id, $member_ids)) {
            throw new Exception("Utente pagante non valido.");
        }

        // --- SPLIT LOGIC ---
        $splits = [];
        switch ($split_method) {
            case 'equal':
                $split_with_users = $_POST['split_with_users'] ?? [];
                if (empty($split_with_users)) throw new Exception("Seleziona almeno un membro per la divisione equa.");
                $split_count = count($split_with_users);
                $split_amount = round($amount / $split_count, 2);
                foreach ($split_with_users as $uid) {
                    $splits[$uid] = $split_amount;
                }
                break;

            case 'fixed':
                $fixed_amounts = $_POST['fixed'] ?? [];
                $total_fixed = 0;
                foreach ($fixed_amounts as $uid => $fixed_amount) {
                    if ($fixed_amount > 0) {
                        $splits[$uid] = (float)$fixed_amount;
                        $total_fixed += (float)$fixed_amount;
                    }
                }
                if (abs($total_fixed - $amount) > 0.01) { // Epsilon for float comparison
                    throw new Exception("La somma degli importi fissi (€" . $total_fixed . ") non corrisponde all'importo totale della spesa (€" . $amount . ").");
                }
                break;

            case 'percentage':
                $percentages = $_POST['percentage'] ?? [];
                $total_percentage = 0;
                foreach ($percentages as $uid => $perc) {
                    if ($perc > 0) {
                        $splits[$uid] = round(($amount * (float)$perc) / 100, 2);
                        $total_percentage += (float)$perc;
                    }
                }
                if (abs($total_percentage - 100) > 0.1) { // Epsilon for percentage
                    throw new Exception("La somma delle percentuali (" . $total_percentage . "%) non è 100%.");
                }
                break;

            default:
                throw new Exception("Metodo di divisione non valido.");
        }

        // --- PAYMENT SOURCE & PERSONAL TRANSACTION ---
        $fund = get_shared_fund_details($conn, $fund_id, $_SESSION['id']);
        $sql_personal_tx = "INSERT INTO transactions (user_id, account_id, category_id, amount, type, description, transaction_date) VALUES (?, ?, ?, ?, 'expense', ?, ?)";
        $stmt_personal_tx = $conn->prepare($sql_personal_tx);
        $personal_tx_amount = -$amount;
        $personal_tx_desc = "Spesa di gruppo '{$fund['name']}': {$description}";
        $stmt_personal_tx->bind_param("iiidss", $paid_by_user_id, $_POST['account_id'], $_POST['category_id'], $personal_tx_amount, $personal_tx_desc, $expense_date);
        $stmt_personal_tx->execute();
        $stmt_personal_tx->close();

        // --- GROUP EXPENSE ---
        $sql_expense = "INSERT INTO group_expenses (fund_id, paid_by_user_id, description, amount, expense_date, category_id, note_id) VALUES (?, ?, ?, ?, ?, ?, NULL)";
        $stmt_expense = $conn->prepare($sql_expense);
        $stmt_expense->bind_param("iisdsi", $fund_id, $paid_by_user_id, $description, $amount, $expense_date, $_POST['category_id']);
        $stmt_expense->execute();
        $expense_id = $stmt_expense->insert_id;
        $stmt_expense->close();

        // --- EXPENSE SPLITS ---
        $sql_split = "INSERT INTO expense_splits (expense_id, user_id, amount_owed) VALUES (?, ?, ?)";
        $stmt_split = $conn->prepare($sql_split);
        foreach ($splits as $user_id => $amount_owed) {
            $stmt_split->bind_param("iid", $expense_id, $user_id, $amount_owed);
            $stmt_split->execute();
        }
        $stmt_split->close();

        $conn->commit();
        header("location: fund_details.php?id=" . $fund_id . "&message=Spesa aggiunta con successo!&type=success");

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
