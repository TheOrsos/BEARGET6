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
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $initial_amount = trim($_POST['initial_amount']);
    $current_balance = trim($_POST['current_balance']);
    $interest_rate = trim($_POST['interest_rate']);
    $minimum_payment = trim($_POST['minimum_payment']);

    // Validation
    if (empty($name) || empty($type) || !is_numeric($initial_amount) || !is_numeric($current_balance)) {
        header("location: debts.php?error=" . urlencode("Per favore, compila tutti i campi obbligatori."));
        exit;
    }

    if ($initial_amount < 0 || $current_balance < 0 || $interest_rate < 0 || $minimum_payment < 0) {
        header("location: debts.php?error=" . urlencode("Gli importi non possono essere negativi."));
        exit;
    }

    $sql = "INSERT INTO liabilities (user_id, name, type, initial_amount, current_balance, interest_rate, minimum_payment) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issdddd", $user_id, $name, $type, $initial_amount, $current_balance, $interest_rate, $minimum_payment);

        if ($stmt->execute()) {
            header("location: debts.php?success=" . urlencode("Debito aggiunto con successo!"));
            exit;
        } else {
            header("location: debts.php?error=" . urlencode("Oops! Qualcosa è andato storto. Riprova più tardi."));
            exit;
        }
        $stmt->close();
    }
}

$conn->close();
?>
