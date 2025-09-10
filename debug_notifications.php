<?php
require_once 'db_connect.php';
require_once 'functions.php';
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "Errore: Devi essere loggato per eseguire il debug.";
    exit;
}

$user_id = $_SESSION['id'];
echo "<h1>Debug Notifiche per Utente ID: " . htmlspecialchars($user_id) . "</h1>";

// Recuperiamo le notifiche non lette SENZA segnarle come lette
$notifications = get_unread_notifications($conn, $user_id);

echo "<p>Trovate <strong>" . count($notifications) . "</strong> notifiche non lette.</p>";

echo "<h3>Dettagli:</h3>";
echo "<pre style='background-color: #f0f0f0; padding: 10px; border-radius: 5px; border: 1px solid #ccc;'>";
if (empty($notifications)) {
    echo "Nessuna notifica da mostrare.";
} else {
    print_r($notifications);
}
echo "</pre>";

$conn->close();
?>