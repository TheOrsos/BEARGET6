<?php
/*
================================================================================
File: update_profile.php
================================================================================
*/
session_start();
require_once 'db_connect.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { exit("Accesso non autorizzato."); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["id"];
    $new_username = trim($_POST['username']);
    
    // Se la checkbox è spuntata, il valore è '1', altrimenti non viene inviato.
    // Lo impostiamo a 0 se non è presente nel POST.
    $receives_emails = isset($_POST['receives_emails']) ? 1 : 0;

    if (empty($new_username)) {
        header("location: settings.php?message=L'username non può essere vuoto.&type=error");
        exit();
    }

    $sql = "UPDATE users SET username = ?, receives_emails = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        // i per intero (0 o 1)
        $stmt->bind_param("sii", $new_username, $receives_emails, $user_id); 
        
        if ($stmt->execute()) {
            $_SESSION["username"] = $new_username; // Aggiorna la sessione!
            header("location: settings.php?message=Profilo aggiornato!&type=success");
        } else {
            header("location: settings.php?message=Errore durante l'aggiornamento.&type=error");
        }
        $stmt->close();
    }
    $conn->close();
    exit();
}
?>