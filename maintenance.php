<?php
// Connessione al database e recupero impostazioni
require_once 'db_connect.php';

$maintenance_message = '';
$message_query = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key = 'maintenance_message' LIMIT 1");
if ($message_query && $message_query->num_rows > 0) {
    $maintenance_message = $message_query->fetch_assoc()['setting_value'];
}

// Messaggio di default se quello personalizzato è vuoto
$default_message = "<p class='mt-2 text-base text-gray-400'>Stiamo effettuando alcuni aggiornamenti per migliorare la tua esperienza.</p><p class='text-gray-400'>Torneremo online a breve. Ci scusiamo per il disagio!</p>";

// Se c'è un messaggio personalizzato, lo usiamo. Altrimenti, usiamo quello di default.
// nl2br() permette di usare gli "a capo" nel messaggio.
$display_message = !empty(trim($maintenance_message)) ? nl2br(htmlspecialchars($maintenance_message)) : $default_message;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenzione - Bearget</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Corrisponde a gray-900 */
            color: #E5E7EB; /* Corrisponde a gray-200 */
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen p-4">
    <div class="text-center max-w-2xl mx-auto">
        <svg class="mx-auto h-16 w-16 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <h1 class="mt-4 text-3xl font-extrabold text-white tracking-tight sm:text-4xl">Sito in Manutenzione</h1>
        <div class="mt-4 text-lg text-gray-300">
            <?php echo $display_message; ?>
        </div>
    </div>
</body>
</html>