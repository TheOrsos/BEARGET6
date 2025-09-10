<?php
// admin_user_actions.php
require_once 'db_connect.php';
require_once 'functions.php'; // Assicura che get_user_by_id sia disponibile
session_start();

// Usa le classi di Brevo
use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Exception;


// Security check: ensure the user is an admin
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["id"] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the raw POST data
$input = json_decode(file_get_contents('php://input'), true);

$action = $input['action'] ?? null;
$userIds = $input['userIds'] ?? [];

// Validate input
if (!$action || empty($userIds) || !is_array($userIds)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// Ensure user IDs are integers to prevent SQL injection
$sanitizedUserIds = array_map('intval', $userIds);
// Prevent the admin from performing actions on their own account (ID 1), except for sending emails
if ($action !== 'send_email') {
    $sanitizedUserIds = array_filter($sanitizedUserIds, function($id) {
        return $id !== 1;
    });
}


if (empty($sanitizedUserIds)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No valid users selected.']);
    exit;
}

$placeholders = implode(',', array_fill(0, count($sanitizedUserIds), '?'));
$types = str_repeat('i', count($sanitizedUserIds));

// Non iniziare una transazione per l'invio di email
if ($action !== 'send_email') {
    $conn->begin_transaction();
}

try {
    $stmt = null;
    switch ($action) {
        case 'suspend':
            $stmt = $conn->prepare("UPDATE users SET account_status = 'suspended' WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$sanitizedUserIds);
            break;
        case 'reactivate':
            $stmt = $conn->prepare("UPDATE users SET account_status = 'active', suspended_until = NULL WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$sanitizedUserIds);
            break;
        case 'delete':
            $stmt = $conn->prepare("DELETE FROM users WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$sanitizedUserIds);
            break;
        case 'disable_emails':
            $stmt = $conn->prepare("UPDATE users SET receives_emails = 0 WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$sanitizedUserIds);
            break;
        case 'enable_emails':
            $stmt = $conn->prepare("UPDATE users SET receives_emails = 1 WHERE id IN ($placeholders)");
            $stmt->bind_param($types, ...$sanitizedUserIds);
            break;
        case 'send_email':
            // Logica di invio email qui
            $subject = $input['subject'] ?? 'Messaggio dall\'amministratore';
            $message_body = $input['message'] ?? 'Nessun messaggio fornito.';
            
            if (empty(trim($subject)) || empty(trim($message_body))) {
                 echo json_encode(['success' => false, 'message' => 'Oggetto e messaggio non possono essere vuoti.']);
                 exit;
            }

            $brevo_config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['BREVO_API_KEY']);
            $apiInstance = new TransactionalEmailsApi(new Client(), $brevo_config);
            
            $sent_count = 0;
            $error_count = 0;

            foreach($sanitizedUserIds as $user_id) {
                $user = get_user_by_id($conn, $user_id);
                if ($user && !empty($user['email'])) {
                    $sendSmtpEmail = new SendSmtpEmail([
                         'templateId' => 11, // Template per messaggi personalizzati
                         'to' => [['name' => $user['username'], 'email' => $user['email']]],
                         'params' => (object)[
                            'username' => $user['username'],
                            'subject_variable' => $subject,
                            'body_variable' => nl2br(htmlspecialchars($message_body))
                         ]
                    ]);

                    try {
                        $apiInstance->sendTransacEmail($sendSmtpEmail);
                        $sent_count++;
                    } catch (Exception $e) {
                        error_log("Errore Brevo in admin_user_actions.php per user ID {$user_id}: " . $e->getMessage());
                        $error_count++;
                    }
                } else {
                    $error_count++;
                }
            }
            
            $message = "Email inviate a {$sent_count} utenti.";
            if ($error_count > 0) {
                $message .= " Impossibile inviare a {$error_count} utenti.";
            }
            echo json_encode(['success' => true, 'message' => $message]);
            exit; // Esce dallo script dopo aver gestito l'invio

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            if ($action !== 'send_email') $conn->rollback();
            exit;
    }

    if ($stmt) {
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        $conn->commit();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => "Azione '{$action}' eseguita con successo su {$affected_rows} utenti."]);
    }

} catch (Exception $e) {
    if ($action !== 'send_email') {
        $conn->rollback();
    }
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'operazione: ' . $e->getMessage()]);
}

$conn->close();
?>