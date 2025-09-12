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

    // Validation
    if (empty($liability_id)) {
        header("location: debts.php?error=" . urlencode("Richiesta non valida."));
        exit;
    }

    // Verify that the liability belongs to the user
    if (!user_owns_liability($conn, $user_id, $liability_id)) {
        header("location: debts.php?error=" . urlencode("Azione non autorizzata."));
        exit;
    }

    $sql = "DELETE FROM liabilities WHERE id = ? AND user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $liability_id, $user_id);

        $success = $stmt->execute();
        $stmt->close(); // Close statement right after execution

        if ($success) {
            header("location: debts.php?success=" . urlencode("Debito eliminato con successo!"));
        } else {
            header("location: debts.php?error=" . urlencode("Oops! Qualcosa è andato storto. Riprova più tardi."));
        }
        exit;
    }
}

$conn->close();
?>
