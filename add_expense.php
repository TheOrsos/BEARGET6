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
    $split_with_users = $_POST['split_with_users'] ?? [];

    // New fields
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $note_content = trim($_POST['note_content']);
    $pay_from_fund = isset($_POST['pay_from_fund']);

    // Fields for personal payment
    $paid_by_user_id = $pay_from_fund ? 0 : $_POST['paid_by_user_id']; // 0 or a user id
    $account_id = $pay_from_fund ? null : $_POST['account_id'];

    // --- VALIDATION ---
    if (empty($fund_id) || empty($description) || $amount <= 0 || empty($expense_date) || empty($split_with_users)) {
        header("location: fund_details.php?id=" . $fund_id . "&message=Descrizione, importo, data e membri della divisione sono obbligatori.&type=error");
        exit;
    }
    if (!$pay_from_fund && (empty($paid_by_user_id) || empty($account_id))) {
        header("location: fund_details.php?id=" . $fund_id . "&message=Se non paghi dal fondo, devi specificare chi ha pagato e da quale conto.&type=error");
        exit;
    }

    // --- SECURITY CHECK ---
    $members = get_fund_members($conn, $fund_id);
    $member_ids = array_column($members, 'id');
    if (!in_array($_SESSION['id'], $member_ids) || (!$pay_from_fund && !in_array($paid_by_user_id, $member_ids))) {
        header("location: shared_funds.php?message=Accesso non autorizzato o utente pagante non valido.&type=error");
        exit;
    }

    // Fetch fund details to get the name for the transaction description
    $fund = get_shared_fund_details($conn, $fund_id, $_SESSION['id']);
    if (!$fund) {
        // This should not happen if the security check above passed, but as a safeguard:
        header("location: shared_funds.php?message=Fondo non trovato.&type=error");
        exit;
    }

    // --- LOGIC ---
    $split_count = count($split_with_users);
    $split_amount = round($amount / $split_count, 2);
    $note_id = null;

    $conn->begin_transaction();

    try {
        // 1. Create and share Note if content is provided
        if (!empty($note_content)) {
            $note_id = create_and_share_note_with_fund_members($conn, $note_content, $_SESSION['id'], $fund_id);
            if (!$note_id) {
                throw new Exception("Creazione della nota fallita.");
            }
        }

        // 2. Handle payment source
        if ($pay_from_fund) {
            // Paid from the fund itself, create a negative contribution
            $sql_fund_payment = "INSERT INTO shared_fund_contributions (fund_id, user_id, amount, contribution_date) VALUES (?, ?, ?, ?)";
            $stmt_fund_payment = $conn->prepare($sql_fund_payment);
            $negative_amount = -$amount;
            // The user_id for this transaction is the user who is recording the expense
            $stmt_fund_payment->bind_param("iids", $fund_id, $_SESSION['id'], $negative_amount, $expense_date);
            $stmt_fund_payment->execute();
            $stmt_fund_payment->close();
            // The "payer" for the group expense is the fund itself, we can represent this with user_id 0 or a dedicated system user. For now, let's use the creator of the expense record.
            $paid_by_user_id_for_expense = $_SESSION['id'];

        } else {
            // Paid by a person, create a personal transaction for them
            $sql_personal_tx = "INSERT INTO transactions (user_id, account_id, category_id, amount, type, description, transaction_date) VALUES (?, ?, ?, ?, 'expense', ?, ?)";
            $stmt_personal_tx = $conn->prepare($sql_personal_tx);
            $personal_tx_amount = -$amount;
            $personal_tx_desc = "Spesa di gruppo '{$fund['name']}': {$description}";
            $stmt_personal_tx->bind_param("iiidss", $paid_by_user_id, $account_id, $category_id, $personal_tx_amount, $personal_tx_desc, $expense_date);
            $stmt_personal_tx->execute();
            $stmt_personal_tx->close();
            $paid_by_user_id_for_expense = $paid_by_user_id;
        }

        // 3. Insert into group_expenses
        $sql_expense = "INSERT INTO group_expenses (fund_id, paid_by_user_id, description, amount, expense_date, category_id, note_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_expense = $conn->prepare($sql_expense);
        $stmt_expense->bind_param("iisdsii", $fund_id, $paid_by_user_id_for_expense, $description, $amount, $expense_date, $category_id, $note_id);
        $stmt_expense->execute();
        $expense_id = $stmt_expense->insert_id;
        $stmt_expense->close();

        // 4. Insert into expense_splits for each user
        $sql_split = "INSERT INTO expense_splits (expense_id, user_id, amount_owed) VALUES (?, ?, ?)";
        $stmt_split = $conn->prepare($sql_split);
        foreach ($split_with_users as $user_to_split_with_id) {
            $stmt_split->bind_param("iid", $expense_id, $user_to_split_with_id, $split_amount);
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