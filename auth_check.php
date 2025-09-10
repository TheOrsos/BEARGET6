<?php
// Inizia la sessione se non è già attiva.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connect.php';
require_once 'functions.php';

// --- CHECK MODALITÀ MANUTENZIONE ---
$maintenance_mode = get_maintenance_status($conn);

if ($maintenance_mode === 'on') {
    // Controlla se l'utente è l'admin (ID 1)
    $is_admin = isset($_SESSION['id']) && $_SESSION['id'] == 1;
    
    // Lista delle pagine sempre accessibili durante la manutenzione
    $allowed_pages = [
        'maintenance.php',         // La pagina di manutenzione stessa
        'index.php',               // Pagina di login/registrazione (con form disabilitati)
        'login.php',               // Lo script che processa il login
        'register.php',            // Lo script che processa la registrazione
        'settings.php',            // Per permettere l'accesso alla gestione abbonamento
        'create-portal-session.php', // Lo script che crea la sessione Stripe
        'pricing.php',             // Per permettere di vedere/scegliere i piani
        'webhook.php'              // Lo script per i webhook di Stripe deve sempre funzionare
    ];
    
    $current_page = basename($_SERVER['PHP_SELF']);

    // Se la manutenzione è attiva, l'utente NON è admin e la pagina corrente NON è nella lista delle permesse,
    // allora reindirizza alla pagina di manutenzione.
    if (!$is_admin && !in_array($current_page, $allowed_pages)) {
        header("Location: maintenance.php");
        exit;
    }
}

// Controlla se l'utente non è già loggato tramite la sessione.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {

    // Se non c'è una sessione, controlla la presenza di un cookie "Ricordami".
    if (isset($_COOKIE['remember_me_token'])) {
        $token = $_COOKIE['remember_me_token'];
        $user = validate_remember_me_token($conn, $token);

        if ($user) {
            // Token valido: l'utente viene loggato.
            log_in_user($user);

            // Best practice: Rota il token. Elimina il vecchio e impostane uno nuovo.
            list($selector, ) = explode(':', $token, 2);
            $sql_delete_old = "DELETE FROM auth_tokens WHERE selector = ?";
            $stmt_delete_old = $conn->prepare($sql_delete_old);
            $stmt_delete_old->bind_param("s", $selector);
            $stmt_delete_old->execute();

            // Crea e imposta un nuovo token.
            $new_token_data = remember_user_token($conn, $user['id']);
            if ($new_token_data) {
                 setcookie(
                    'remember_me_token',
                    $new_token_data['cookie_value'],
                    [
                        'expires' => $new_token_data['expires_timestamp'],
                        'path' => '/',
                        'domain' => '',
                        'secure' => isset($_SERVER['HTTPS']),
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
            }

        } else {
            // Se il token nel cookie non è valido, puliscilo.
            setcookie('remember_me_token', '', time() - 3600, '/');
        }
    }
}

// --- CONTROLLO STATO ACCOUNT ---
// Se l'utente è loggato, controlliamo che il suo account sia ancora attivo.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user_id = $_SESSION["id"];
    $sql_check_status = "SELECT account_status FROM users WHERE id = ? LIMIT 1";
    
    if ($stmt_check_status = $conn->prepare($sql_check_status)) {
        $stmt_check_status->bind_param("i", $user_id);
        $stmt_check_status->execute();
        $result = $stmt_check_status->get_result();
        
        if ($result->num_rows === 1) {
            $user_status = $result->fetch_assoc();
            if ($user_status['account_status'] !== 'active') {
                // L'account non è attivo (es. sospeso), quindi termina la sessione.
                if (isset($_COOKIE['remember_me_token'])) {
                    setcookie('remember_me_token', '', time() - 3600, '/');
                }
                $_SESSION = array();
                session_destroy();
                header("location: index.php?error=account_suspended");
                exit;
            }
        }
        $stmt_check_status->close();
    }
}

// Controllo finale: se dopo tutto questo l'utente non è loggato, reindirizza.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    if (basename($_SERVER['PHP_SELF']) != 'index.php') {
        header("location: index.php");
        exit;
    }
}
?>