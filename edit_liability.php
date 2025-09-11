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
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $initial_amount = trim($_POST['initial_amount']);
    $current_balance = trim($_POST['current_balance']);
    $interest_rate = trim($_POST['interest_rate']);
    $minimum_payment = trim($_POST['minimum_payment']);

    // Validation
    if (empty($liability_id) || empty($name) || empty($type) || !is_numeric($initial_amount) || !is_numeric($current_balance)) {
        header("location: debts.php?error=" . urlencode("Per favore, compila tutti i campi obbligatori."));
        exit;
    }

    if ($initial_amount < 0 || $current_balance < 0 || $interest_rate < 0 || $minimum_payment < 0) {
        header("location: debts.php?error=" . urlencode("Gli importi non possono essere negativi."));
        exit;
    }

    // Verify that the liability belongs to the user
    if (!user_owns_liability($conn, $user_id, $liability_id)) {
        header("location: debts.php?error=" . urlencode("Azione non autorizzata."));
        exit;
    }

    $sql = "UPDATE liabilities SET name = ?, type = ?, initial_amount = ?, current_balance = ?, interest_rate = ?, minimum_payment = ? WHERE id = ? AND user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssddddii", $name, $type, $initial_amount, $current_balance, $interest_rate, $minimum_payment, $liability_id, $user_id);

        $success = $stmt->execute();
        $stmt->close(); // Close statement right after execution

        if ($success) {
            header("location: debts.php?success=" . urlencode("Debito aggiornato con successo!"));
        } else {
            header("location: debts.php?error=" . urlencode("Oops! Qualcosa è andato storto. Riprova più tardi."));
        }
        exit;
    }
}

$conn->close();
?>
