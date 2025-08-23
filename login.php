<?php
session_start();
require_once 'db_connect.php';
// Includiamo functions.php per accedere alle nuove funzioni dei token
require_once 'functions.php';

// È una buona pratica pulire i token scaduti periodicamente.
// Il processo di login è un buon posto per farlo.
clear_expired_tokens($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember_me = isset($_POST['remember_me']);

    if (empty($email) || empty($password)) {
        login_redirect_with_message("Email e password sono obbligatori.", "error");
    }

    $sql = "SELECT id, username, password, is_verified, theme FROM users WHERE email = ? LIMIT 1";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password, $is_verified, $theme);
            if ($stmt->fetch()) {
                if (password_verify($password, $hashed_password)) {
                    if ($is_verified == 1) {
                        session_regenerate_id();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['theme'] = $theme;

                        // --- NUOVA LOGICA "RICORDAMI" ---
                        if ($remember_me) {
                            $token_data = remember_user_token($conn, $id);
                            if ($token_data) {
                                setcookie(
                                    'remember_me_token',
                                    $token_data['cookie_value'],
                                    [
                                        'expires' => $token_data['expires_timestamp'],
                                        'path' => '/',
                                        'domain' => '', // Dominio corrente
                                        'secure' => isset($_SERVER['HTTPS']), // True se su HTTPS
                                        'httponly' => true, // Il cookie non è accessibile da JavaScript
                                        'samesite' => 'Lax' // Protezione CSRF
                                    ]
                                );
                            }
                        }
                        // --- FINE NUOVA LOGICA ---

                        header("Location: dashboard.php");
                        exit();
                    } else {
                        login_redirect_with_message("Il tuo account non è stato ancora verificato. Controlla la tua email.", "error");
                    }
                } else {
                    login_redirect_with_message("Credenziali non valide.", "error");
                }
            }
        } else {
            login_redirect_with_message("Credenziali non valide.", "error");
        }
        $stmt->close();
    }
    $conn->close();
}

function login_redirect_with_message($message, $type) {
    // Assicurati che il percorso del redirect sia corretto
    header("Location: index.php?message=" . urlencode($message) . "&type=" . $type);
    exit();
}
?>