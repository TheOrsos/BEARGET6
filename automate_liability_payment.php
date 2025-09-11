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
    $account_id = trim($_POST['account_id']);
    $category_id = trim($_POST['category_id']);
    $amount = trim($_POST['amount']);
    $description = trim($_POST['description']);
    $start_date = trim($_POST['start_date']);
    $frequency = 'monthly'; // Automation is always monthly for this context
    $type = 'expense';

    // --- Validation ---
    if (empty($liability_id) || empty($account_id) || empty($category_id) || !is_numeric($amount) || $amount <= 0 || empty($start_date)) {
        header("location: debts.php?error=" . urlencode("Richiesta non valida. Dati mancanti o non corretti."));
        exit;
    }

    // --- Security Checks ---
    if (!user_owns_liability($conn, $user_id, $liability_id)) {
        header("location: debts.php?error=" . urlencode("Azione non autorizzata sul debito."));
        exit;
    }
    if (!get_account_by_id($conn, $account_id, $user_id)) {
        header("location: debts.php?error=" . urlencode("Conto non autorizzato."));
        exit;
    }
    if (!get_category_by_id($conn, $category_id, $user_id)) {
        header("location: debts.php?error=" . urlencode("Categoria non autorizzata."));
        exit;
    }

    // --- Create Recurring Transaction ---
    $sql = "INSERT INTO recurring_transactions (user_id, account_id, category_id, amount, type, frequency, description, next_due_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiidssss", $user_id, $account_id, $category_id, $amount, $type, $frequency, $description, $start_date);

        if ($stmt->execute()) {
            $stmt->close();
            header("location: recurring.php?success=" . urlencode("Pagamento ricorrente creato con successo! Lo vedrai nella lista delle spese ricorrenti."));
            exit;
        } else {
            $stmt->close();
            header("location: debts.php?error=" . urlencode("Oops! Qualcosa è andato storto nella creazione della spesa ricorrente."));
            exit;
        }
    } else {
        header("location: debts.php?error=" . urlencode("Errore di preparazione della richiesta al database."));
        exit;
    }
}

$conn->close();
?>
