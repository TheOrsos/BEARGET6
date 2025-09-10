<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) { header("location: index.php"); exit; }
require_once 'db_connect.php';
require_once 'functions.php';
$user_id = $_SESSION["id"];

// Segna tutte le notifiche come lette
mark_notifications_as_read($conn, $user_id);

$notifications = get_all_notifications($conn, $user_id);
$user_accounts = get_user_accounts($conn, $user_id); // Aggiunta questa riga
$notification_count = 0; // Impostato a 0 perché l'utente è sulla pagina delle notifiche

$current_page = 'notifications'; 

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifiche - Bearget</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="theme.php">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 500: 'var(--color-primary-500)', 600: 'var(--color-primary-600)', 700: 'var(--color-primary-700)' },
                        gray: { 100: 'var(--color-gray-100)', 200: 'var(--color-gray-200)', 300: 'var(--color-gray-300)', 400: 'var(--color-gray-400)', 700: 'var(--color-gray-700)', 800: 'var(--color-gray-800)', 900: 'var(--color-gray-900)' },
                        success: 'var(--color-success)', danger: 'var(--color-danger)', warning: 'var(--color-warning)'
                    }
                }
            }
        }
    </script>
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: var(--color-gray-900); } </style>
</head>
<body class="text-gray-300">
    <div class="flex h-screen">
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
        <?php include 'sidebar.php'; ?>

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
            <header class="mb-8">
                <div class="flex items-center gap-4">
                    <button id="menu-button" type="button" class="lg:hidden p-2 rounded-md text-gray-400 hover: hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Apri menu principale</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-3xl font-bold  flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            Notifiche
                        </h1>
                        <p class="text-gray-400 mt-1">Qui troverai inviti e altri avvisi importanti.</p>
                    </div>
                </div>
            </header>
            <div class="space-y-4 max-w-3xl mx-auto overflow-y-auto" style="max-height: calc(100vh - 10rem);">
<?php if (empty($notifications)): ?>
    <div id="empty-state-notifications" class="bg-gray-800 rounded-lg p-6 text-center">
        <p class="text-gray-400">Nessuna nuova notifica al momento.</p>
    </div>
<?php else: foreach ($notifications as $notification): ?>
<div class="notification-item bg-gray-800 rounded-lg p-4 <?php echo $notification['is_read'] ? 'opacity-60' : ''; ?>" data-notification-id="<?php echo $notification['id']; ?>">
    <div class="flex items-start">
        <!-- Icona -->
        <div class="flex-shrink-0 mr-4">
            <?php 
            $icon = '';
            // La tua logica per le icone va qui, assicurati che sia completa
            switch ($notification['type']) {
                case 'expense_approval': $icon = '<svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'; break;
                case 'fund_invite': $icon = '<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.122-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.122-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'; break;
                case 'friend_request': case 'friend_request_accepted': $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-400" viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.004 3.004 0 013.75-2.906z" /></svg>'; break;
                case 'money_transfer_request': case 'money_transfer_accepted': case 'money_transfer_declined': $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.5 2.5 0 004 0V7.15a.5.5 0 00-.567.267C11.116 8.36 10.06 9 9 9s-2.116-.64-2.567-1.582zM9 13a1 1 0 100-2 1 1 0 000 2z" /><path fill-rule="evenodd" d="M9.878 3.878a3 3 0 00-3.756 0A3 3 0 004.5 6.622V14a2 2 0 002 2h7a2 2 0 002-2V6.622a3 3 0 00-1.622-2.744zM6 14v-1.378A5.02 5.02 0 019 9h2a5.02 5.02 0 013 3.622V14H6z" clip-rule="evenodd" /></svg>'; break;
                case 'loan_request': case 'loan_accepted': case 'loan_rejected': $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-400" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.5 2.5 0 004 0V7.15a.5.5 0 00-.567.267C11.116 8.36 10.06 9 9 9s-2.116-.64-2.567-1.582zM9 13a1 1 0 100-2 1 1 0 000 2z" /><path fill-rule="evenodd" d="M9.878 3.878a3 3 0 00-3.756 0A3 3 0 004.5 6.622V14a2 2 0 002 2h7a2 2 0 002-2V6.622a3 3 0 00-1.622-2.744zM6 14v-1.378A5.02 5.02 0 019 9h2a5.02 5.02 0 013 3.622V14H6z" clip-rule="evenodd" /></svg>'; break;
                default: $icon = '<svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            echo $icon;
            ?>
        </div>
        <div class="flex-grow">
            <?php if ($notification['type'] == 'expense_approval'):
                $data = json_decode($notification['message'], true);
                if ($data):
            ?>
                    <p class="text-white">
                        <span class="font-bold"><?= htmlspecialchars($data['creator_username']) ?></span> ha registrato una spesa di <span class="font-bold text-primary-400">€<?= number_format($data['amount'], 2, ',', '.') ?></span> a tuo nome nel fondo <a href="fund_details.php?id=<?= $data['fund_id'] ?>" class="text-blue-400 hover:underline">'<?= htmlspecialchars($data['fund_name']) ?>'</a> per <span class="italic">"<?= htmlspecialchars($data['description']) ?>"</span>.
                    </p>
                    <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
                    <form action="approve_expense.php" method="POST" class="mt-4 space-y-3">
                        <input type="hidden" name="notification_id" value="<?= $notification['id'] ?>">
                        <div>
                            <label for="account_id_<?= $notification['id'] ?>" class="block text-sm font-medium text-gray-400 mb-1">Approva usando il conto:</label>
                            <select name="account_id" id="account_id_<?= $notification['id'] ?>" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                                <?php foreach ($user_accounts as $account): ?>
                                <option value="<?= $account['id'] ?>"><?= htmlspecialchars($account['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" name="action" value="approve" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">Approva</button>
                            <button type="submit" name="action" value="decline" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg text-sm">Rifiuta</button>
                        </div>
                    </form>
                                <?php 
                endif; 
            ?> 
            <?php elseif ($notification['type'] == 'loan_request' && isset($notification['loan_status']) && $notification['loan_status'] == 'pending'): ?>
                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
                <div class="flex space-x-2 flex-shrink-0 mt-2">
                    <button data-loan-id="<?php echo $notification['related_id']; ?>" class="accept-loan-btn bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Accetta</button>
                    <button data-loan-id="<?php echo $notification['related_id']; ?>" class="reject-loan-btn bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Rifiuta</button>
                </div>
            <?php elseif ($notification['type'] == 'fund_invite' && !is_fund_member($conn, $notification['related_id'], $user_id)): ?>
                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
                <div class="flex space-x-2 flex-shrink-0 mt-2">
                    <form action="accept_invite.php" method="POST" class="inline-block">
                        <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                        <input type="hidden" name="fund_id" value="<?php echo $notification['related_id']; ?>">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Accetta</button>
                    </form>
                    <form action="decline_invite.php" method="POST" class="inline-block">
                        <input type="hidden" name="notification_id" value="<?php echo $notification['id']; ?>">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Rifiuta</button>
                    </form>
                </div>
            <?php elseif ($notification['type'] == 'friend_request' && get_friendship_status($conn, $notification['related_id']) == 'pending'): ?>
                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
                <div class="flex space-x-2 flex-shrink-0 mt-2">
                    <form action="handle_friend_request.php" method="POST" class="inline-block">
                        <input type="hidden" name="request_id" value="<?php echo $notification['related_id']; ?>">
                        <button type="submit" name="action" value="accept" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Accetta</button>
                    </form>
                    <form action="handle_friend_request.php" method="POST" class="inline-block">
                        <input type="hidden" name="request_id" value="<?php echo $notification['related_id']; ?>">
                        <button type="submit" name="action" value="decline" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Rifiuta</button>
                    </form>
                </div>
            <?php elseif ($notification['type'] == 'money_transfer_request' && get_money_transfer_status($conn, $notification['related_id']) == 'pending'): ?>
                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
                <div class="flex space-x-2 flex-shrink-0 mt-2">
                    <form action="handle_money_transfer.php" method="POST" class="inline-block">
                        <input type="hidden" name="transfer_id" value="<?php echo $notification['related_id']; ?>">
                        <button type="submit" name="action" value="accept" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Accetta</button>
                    </form>
                    <form action="handle_money_transfer.php" method="POST" class="inline-block">
                        <input type="hidden" name="transfer_id" value="<?php echo $notification['related_id']; ?>">
                        <button type="submit" name="action" value="decline" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-1 rounded-md text-sm">Rifiuta</button>
                    </form>
                </div>
            <?php else: ?>
                <?php // MESSAGGIO GENERICO PER TUTTE LE ALTRE NOTIFICHE SENZA AZIONI ?>
                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date("d/m/Y H:i", strtotime($notification['created_at'])) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>
            </div>
        </main>
    </div>
    <?php include 'page_footer.php'; ?>

    <!-- Modale per Accettare Richiesta di Prestito -->
    <div id="accept-loan-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('accept-loan-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-md p-6 shadow-lg transform scale-95 opacity-0 modal-content">
            <h2 class="text-2xl font-bold text-white mb-4">Conferma Prestito</h2>
            <form id="accept-loan-form">
                <input type="hidden" id="accept-loan-id" name="loan_id">
                
                <div class="space-y-4">
                    <div>
                        <label for="lender-account-select" class="block text-sm font-medium text-gray-300 mb-1">Seleziona il conto da cui inviare i fondi:</label>
                        <select name="lender_account_id" id="lender-account-select" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                            <?php if (empty($user_accounts)): ?>
                                <option value="">Nessun conto disponibile</option>
                            <?php else: foreach ($user_accounts as $account): ?>
                                <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['name']); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('accept-loan-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-lg">Conferma e Invia</button>
                </div>
            </form>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {


    // --- Logica per Gestire Richieste di Prestito ---
    const notificationsContainer = document.querySelector('.space-y-4.max-w-3xl');
    const acceptLoanModal = document.getElementById('accept-loan-modal');
    const acceptLoanForm = document.getElementById('accept-loan-form');
    const acceptLoanIdInput = document.getElementById('accept-loan-id');

    if (notificationsContainer) {
        notificationsContainer.addEventListener('click', function(e) {
            const target = e.target;

            // Pulsante Rifiuta
            if (target.classList.contains('reject-loan-btn')) {
                const loanId = target.dataset.loanId;
                showConfirmationModal(
                    'Sei sicuro di voler rifiutare questa richiesta di prestito?',
                    () => { handleLoanRequest(loanId, 'reject'); },
                    'Conferma Rifiuto'
                );
            }

            // Pulsante Accetta
            if (target.classList.contains('accept-loan-btn')) {
                const loanId = target.dataset.loanId;
                if(acceptLoanIdInput) acceptLoanIdInput.value = loanId;
                openModal('accept-loan-modal');
            }
        });
    }

    // Gestione del form nel modale di accettazione
    if (acceptLoanForm) {
        acceptLoanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const loanId = acceptLoanIdInput.value;
            const lenderAccountId = document.getElementById('lender-account-select').value;
            
            if (!lenderAccountId) {
                showToast('Per favore, seleziona un conto da cui inviare i fondi.', 'error');
                return;
            }
            
            handleLoanRequest(loanId, 'accept', lenderAccountId);
        });
    }

    // Funzione centrale per gestire la richiesta
    function handleLoanRequest(loanId, action, lenderAccountId = null) {
        const body = {
            loan_id: loanId,
            action: action
        };
        if (lenderAccountId) {
            body.lender_account_id = lenderAccountId;
        }

        // Mostra un feedback visivo durante l'elaborazione
        showToast('Elaborazione richiesta...', 'info');

        fetch('handle_loan_request.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                // La soluzione più semplice e robusta è ricaricare la pagina
                // per mostrare lo stato aggiornato delle notifiche.
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        })
        .catch(err => {
            showToast('Errore di rete. Riprova.', 'error');
            console.error(err);
        });
    }
});
</script>
</body>
</html>