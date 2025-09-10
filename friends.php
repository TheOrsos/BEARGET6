<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'friends_functions.php';
require_once 'auth_check.php';

$user_id = $_SESSION["id"];
$user = get_user_by_id($conn, $user_id);
$friends = get_friends_for_user($conn, $user_id);
$blocked_user_ids = get_blocked_user_ids($conn, $user_id);
$accounts = get_user_accounts($conn, $user_id); // For the transfer modal
$unread_counts = get_unread_message_counts($conn, $user_id);

$current_page = 'friends';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amici - Bearget</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: var(--color-gray-900); } 
        .modal-backdrop { transition: opacity 0.3s ease-in-out; }
.modal-content { transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out; }
    </style>
</head>
<body class="text-gray-200">

    <div class="flex h-screen">
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
        <?php include 'sidebar.php'; ?>

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
            <header class="mb-8">
                <div class="flex items-center gap-4">
                    <button id="menu-button" type="button" class="lg:hidden p-2 rounded-md text-gray-400 hover: hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-3xl font-bold  flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.004 3.004 0 013.75-2.906z" /></svg>
                            Amici
                        </h1>
                        <p class="text-gray-400 mt-1">Gestisci i tuoi amici e invia loro denaro.</p>
                    </div>
                </div>
            </header>

            <?php include 'toast_notification.php'; ?>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-1 space-y-8">
                    <!-- Your Friend Code -->
                    <div id="friend-code-panel" class="bg-gray-800 rounded-2xl p-6">
                        <h2 class="text-xl font-bold  mb-4">Il Tuo Codice Amico</h2>
                        <p class="text-gray-400 mb-2">Condividi questo codice per ricevere richieste di amicizia.</p>
                        <div class="bg-gray-900  text-center font-mono text-2xl tracking-widest py-3 rounded-lg cursor-pointer" onclick="copyToClipboard('<?php echo htmlspecialchars($user['friend_code']); ?>')">
                            <?php echo htmlspecialchars($user['friend_code']); ?>
                        </div>
                        <p id="copy-message" class="text-center text-sm text-green-400 mt-2 hidden">Copiato!</p>
                    </div>

                    <!-- Add Friend -->
                    <div id="add-friend-panel" class="bg-gray-800 rounded-2xl p-6">
                        <h2 class="text-xl font-bold  mb-4">Aggiungi un Amico</h2>
                        <form action="send_friend_request.php" method="POST">
                            <label for="friend_code" class="block text-sm font-medium text-gray-300 mb-1">Codice Amico</label>
                            <input type="text" name="friend_code" id="friend_code" required class="w-full bg-gray-700  rounded-lg px-3 py-2 mb-4" placeholder="Incolla il codice qui">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700  font-semibold py-2.5 rounded-lg">Invia Richiesta</button>
                        </form>
                    </div>
                </div>

                <div class="xl:col-span-2 bg-gray-800 rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4">Lista Amici</h2>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="friends.php" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="search_query" class="sr-only">Cerca amici</label>
                                <input type="text" name="search_query" id="search_query" placeholder="Cerca per nome o email..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>" class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div class="flex items-center gap-2">
                                <select name="filter" class="w-full bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="">Nessun filtro</option>
                                    <option value="blocked" <?php if (($filter ?? '') == 'blocked') echo 'selected'; ?>>Bloccati</option>
                                    <option value="debts" <?php if (($filter ?? '') == 'debts') echo 'selected'; ?>>Ho un debito</option>
                                    <option value="loans" <?php if (($filter ?? '') == 'loans') echo 'selected'; ?>>Ho un prestito</option>
                                </select>
                                <a href="friends.php" class="bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="py-2 px-4 w-16"></th>
                                    <th class="py-2 px-4">Username</th>
                                    <th class="py-2 px-4">Email</th>
                                    <th class="py-2 px-4">Codice Amico</th>
                                    <th class="py-2 px-4"></th>
                                </tr>
                            </thead>
                            <tbody id="friends-table-body">
                                <?php if (empty($friends)): ?>
                                    <tr><td colspan="5" class="text-center py-8 text-gray-400">Non hai ancora nessun amico.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($friends as $friend): ?>
                                        <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                            <td class="py-2 px-4">
                                                <img src="<?php echo !empty($friend['profile_picture_path']) ? htmlspecialchars($friend['profile_picture_path']) : 'assets/images/default_avatar.png'; ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                            </td>
                                            <td class="py-3 px-4"><?php echo htmlspecialchars($friend['username']); ?></td>
                                            <td class="py-3 px-4"><?php echo htmlspecialchars($friend['email']); ?></td>
                                            <td class="py-3 px-4 font-mono"><?php echo htmlspecialchars($friend['friend_code']); ?></td>
                                            <td class="py-3 px-4 text-right">
                                                <button onclick="openChatModal(<?php echo $friend['id']; ?>, '<?php echo htmlspecialchars($friend['username']); ?>')" class="chat-button relative inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg text-sm" data-friend-id="<?php echo $friend['id']; ?>">
                                                    Chat
                                                </button>
                                                <?php
                                                    $net_balance = $friend['total_lent_to_friend'] - $friend['total_borrowed_from_friend'];
                                                    $loan_button_class = 'bg-primary-600 hover:bg-primary-700'; // Default
                                                    if ($net_balance > 0) {
                                                        $loan_button_class = 'bg-green-600 hover:bg-green-700'; // They owe me
                                                    } elseif ($net_balance < 0) {
                                                        $loan_button_class = 'bg-red-600 hover:bg-red-700'; // I owe them
                                                    }
                                                ?>
                                                <button onclick="openLoansModal(<?php echo $friend['id']; ?>, '<?php echo htmlspecialchars($friend['username']); ?>', <?php echo $friend['total_lent_to_friend']; ?>, <?php echo $friend['total_borrowed_from_friend']; ?>)" class="<?php echo $loan_button_class; ?> text-white font-semibold py-1 px-3 rounded-lg text-sm ml-2">
                                                    Prestiti
                                                </button>
                                                <?php 
                                                    $is_blocked = in_array($friend['id'], $blocked_user_ids);
                                                    // NEW: Logic for disabling buttons
                                                    $has_outstanding_balance = ($net_balance != 0);
                                                    $disabled_attr = $has_outstanding_balance ? 'disabled' : '';
                                                    $disabled_class = $has_outstanding_balance ? 'opacity-50 cursor-not-allowed' : '';
                                                    $disabled_title = $has_outstanding_balance ? 'Non puoi eseguire questa azione finché c\'è un saldo in sospeso.' : '';
                                                ?>
                                                <button data-friend-id="<?php echo $friend['id']; ?>" class="toggle-block-btn py-1 px-3 rounded-lg text-sm ml-2 font-semibold transition-colors <?php echo $is_blocked ? 'bg-yellow-600 hover:bg-yellow-500' : 'bg-gray-600 hover:bg-gray-500'; ?> <?php echo $disabled_class; ?>" <?php echo $disabled_attr; ?> title="<?php echo $disabled_title; ?>">
                                                    <?php echo $is_blocked ? 'Sblocca' : 'Blocca'; ?>
                                                </button>
                                                <!-- NUOVO PULSANTE -->
                                                <button data-friend-id="<?php echo $friend['id']; ?>" data-friend-name="<?php echo htmlspecialchars($friend['username']); ?>" class="remove-friend-btn bg-red-800 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg text-sm ml-2 <?php echo $disabled_class; ?>" <?php echo $disabled_attr; ?> title="<?php echo $disabled_title; ?>">
                                                    Rimuovi
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Loans Modal (Send/Request) -->
    <div id="loansModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-white mb-4">Prestiti con <span id="loansModalFriendName"></span></h2>

                    <div id="loan-balance-display" class="text-center p-3 mb-4 rounded-lg text-white font-semibold hidden">
                </div>
            <div class="flex border-b border-gray-700 mb-4"></div>
            
            <!-- Tabs -->
            <div class="flex border-b border-gray-700 mb-4">
                <button id="tab-send" class="py-2 px-4 text-white border-b-2 border-primary-500 font-semibold">Invia Denaro</button>
                <button id="tab-request" class="py-2 px-4 text-gray-400 border-b-2 border-transparent hover:text-white">Chiedi un Prestito</button>
            </div>

            <!-- Send Money Form (previously transfer form) -->
            <form id="send-money-form" action="send_money_transfer.php" method="POST">
                <input type="hidden" name="receiver_id" id="sendReceiverId">
                <div class="mb-4">
                    <label for="send-amount" class="block text-sm font-medium text-gray-300 mb-1">Importo (€)</label>
                    <input type="number" name="amount" id="send-amount" step="0.01" min="0.01" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                </div>
                <div class="mb-6">
                    <label for="from_account_id" class="block text-sm font-medium text-gray-300 mb-1">Dal Conto</label>
                    <select name="from_account_id" id="from_account_id" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeLoansModal()" class="bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg">Invia Richiesta Denaro</button>
                </div>
            </form>

            <!-- Request Loan Form -->
            <form id="request-loan-form" class="hidden space-y-4">
                <input type="hidden" id="requestLenderId">
                <div>
                    <label for="request-amount" class="block text-sm font-medium text-gray-300 mb-1">Importo da chiedere (€)</label>
                    <input type="number" id="request-amount" step="0.01" min="0.01" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                </div>
                <!-- NUOVO MENU A TENDINA -->
                <div>
                    <label for="requester-account-id" class="block text-sm font-medium text-gray-300 mb-1">Accredita sul Conto</label>
                    <select id="requester-account-id" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        <option value="">Scegli un conto...</option>
                        <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end gap-4 pt-2">
                    <button type="button" onclick="closeLoansModal()" class="bg-gray-600 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">Invia Richiesta Prestito</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="fixed inset-0 bg-gray-900 z-50 hidden flex flex-col">
        <header class="bg-gray-800 shadow-md p-4 flex items-center gap-4 flex-shrink-0">
            <button onclick="closeChatModal()" class="p-2 rounded-full hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </button>
            <h1 id="chatModalFriendName" class="text-xl font-bold "></h1>
        </header>
        <div id="chat-box" class="flex-1 p-6 overflow-y-auto">
            <!-- Messages will be loaded here -->
        </div>
        <footer class="p-4 bg-gray-800">
            <form id="chat-form" class="flex gap-4">
                <input type="hidden" id="chatReceiverId" value="">
                <input type="text" id="message-input" placeholder="Scrivi un messaggio..." autocomplete="off" required class="w-full bg-gray-700  rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700  font-semibold py-2 px-6 rounded-lg">Invia</button>
            </form>
        </footer>
    </div>

<script>
    const currentUserId = <?php echo json_encode($user_id); ?>;

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const msg = document.getElementById('copy-message');
            if(msg) {
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            }
        });
    }

    // --- Logica Modale Prestiti (Send/Request) ---
    const loansModal = document.getElementById('loansModal');
    const loansModalFriendName = document.getElementById('loansModalFriendName');
    const loanBalanceDisplay = document.getElementById('loan-balance-display'); // Nuovo elemento
    const sendMoneyForm = document.getElementById('send-money-form');
    const sendReceiverIdInput = document.getElementById('sendReceiverId');
    const requestLoanForm = document.getElementById('request-loan-form');
    const requestLenderIdInput = document.getElementById('requestLenderId');
    const requestAmountInput = document.getElementById('request-amount');
    const tabSend = document.getElementById('tab-send');
    const tabRequest = document.getElementById('tab-request');

    function openLoansModal(friendId, friendName, totalLent, totalBorrowed) { // Aggiunti parametri
    if(loansModal) {
        loansModalFriendName.textContent = friendName;
        sendReceiverIdInput.value = friendId;
        requestLenderIdInput.value = friendId;

        // --- NUOVA LOGICA PER IL BILANCIO ---
        const netBalance = totalLent - totalBorrowed;
        loanBalanceDisplay.classList.remove('hidden', 'bg-green-600', 'bg-red-700', 'bg-gray-600'); // Pulisce le classi

        if (netBalance > 0) {
            loanBalanceDisplay.textContent = `Questo utente ti deve ${netBalance.toFixed(2)} €`;
            loanBalanceDisplay.classList.add('bg-green-600');
        } else if (netBalance < 0) {
            loanBalanceDisplay.textContent = `Devi ${Math.abs(netBalance).toFixed(2)} € a questo utente`;
            loanBalanceDisplay.classList.add('bg-red-700');
        } else {
            loanBalanceDisplay.textContent = 'Siete in pari';
            loanBalanceDisplay.classList.add('bg-gray-600');
        }
        // --- FINE NUOVA LOGICA ---

        openModal('loansModal');
    }
}

function closeLoansModal() {
    if(loansModal) {
        closeModal('loansModal');
        // Nasconde il box del bilancio alla chiusura per non mostrarlo brevemente al prossimo click
        if (loanBalanceDisplay) loanBalanceDisplay.classList.add('hidden');
    }
}

    // --- Logica Modale Chat ---
    const chatModal = document.getElementById('chatModal');
    const chatModalFriendName = document.getElementById('chatModalFriendName');
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const chatReceiverIdInput = document.getElementById('chatReceiverId');
    let chatPollingInterval = null;

    function openChatModal(friendId, friendName) {
        if(chatModal) {
            chatModalFriendName.textContent = `Chat con ${friendName}`;
            chatReceiverIdInput.value = friendId;
            chatBox.innerHTML = '<p class="text-center text-gray-500">Caricamento...</p>';
            openModal('chatModal');
            fetchNewMessages();
            clearInterval(chatPollingInterval);
            chatPollingInterval = setInterval(fetchNewMessages, 5000);
        }
    }

    function closeChatModal() {
        if(chatModal) {
            closeModal('chatModal');
            clearInterval(chatPollingInterval);
        }
    }
    
    function renderMessage(msg) {
        const messageWrapper = document.createElement('div');
        const messageBubble = document.createElement('div');
        const isSender = msg.sender_id == currentUserId;
        messageBubble.classList.add('px-4', 'py-2', 'rounded-lg', 'max-w-xs', 'lg:max-w-md', 'break-words', isSender ? 'bg-primary-600' : 'bg-gray-700');
        messageBubble.textContent = msg.message;
        messageWrapper.classList.add('flex', 'items-end', 'mb-4', 'gap-2', isSender ? 'justify-end' : 'justify-start');
        messageWrapper.appendChild(messageBubble);
        chatBox.appendChild(messageWrapper);
    }

    function fetchNewMessages() {
        const receiverId = chatReceiverIdInput.value;
        if (!receiverId) return;
        fetch(`api_fetch_messages.php?friend_id=${receiverId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const shouldScroll = (chatBox.scrollTop + chatBox.clientHeight) >= chatBox.scrollHeight - 30;
                    chatBox.innerHTML = '';
                    if (data.messages.length > 0) {
                        data.messages.forEach(renderMessage);
                    } else {
                        chatBox.innerHTML = '<p class="text-center text-gray-500">Nessun messaggio. Inizia la conversazione!</p>';
                    }
                    if(shouldScroll) chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
    }
    
    // Listener Globale per tutti gli eventi della pagina
    document.addEventListener('DOMContentLoaded', function() {

        // Gestione Tabs del Modale Prestiti
        if (tabSend && tabRequest && sendMoneyForm && requestLoanForm) {
            tabSend.addEventListener('click', () => {
                sendMoneyForm.classList.remove('hidden');
                requestLoanForm.classList.add('hidden');
                tabSend.classList.add('border-primary-500', 'text-white');
                tabSend.classList.remove('border-transparent', 'text-gray-400');
                tabRequest.classList.add('border-transparent', 'text-gray-400');
                tabRequest.classList.remove('border-primary-500', 'text-white');
            });
            tabRequest.addEventListener('click', () => {
                requestLoanForm.classList.remove('hidden');
                sendMoneyForm.classList.add('hidden');
                tabRequest.classList.add('border-primary-500', 'text-white');
                tabRequest.classList.remove('border-transparent', 'text-gray-400');
                tabSend.classList.add('border-transparent', 'text-gray-400');
                tabSend.classList.remove('border-primary-500', 'text-white');
            });
        }
        
        // Gestione Invio Richiesta Prestito
        if (requestLoanForm) {
            requestLoanForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const lenderId = requestLenderIdInput.value;
                const amount = requestAmountInput.value;
                const requesterAccountId = document.getElementById('requester-account-id').value;

                if (!requesterAccountId) {
                    showToast('Per favore, seleziona un conto dove ricevere i fondi.', 'error');
                    return;
                }

                fetch('request_loan.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        lender_id: lenderId, 
                        amount: amount,
                        requester_account_id: requesterAccountId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    showToast(data.message, data.success ? 'success' : 'error');
                    if(data.success) {
                        closeLoansModal();
                        requestLoanForm.reset();
                    }
                })
                .catch(err => showToast('Errore di rete.', 'error'));
            });
        }

        // Gestione Invio Messaggio Chat
        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                const receiverId = chatReceiverIdInput.value;
                if (!message || !receiverId) return;
                const formData = new FormData();
                formData.append('receiver_id', receiverId);
                formData.append('message', message);
                messageInput.value = '';
                fetch('ajax_send_message.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') { fetchNewMessages(); } 
                        else { console.error('Failed to send message:', data.message); }
                    });
            });
        }

        // Gestione Azioni sulla Tabella Amici (Rimuovi/Blocca)
        const friendsTableBody = document.getElementById('friends-table-body');
        if (friendsTableBody) {
            friendsTableBody.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.remove-friend-btn');
                if (removeButton) {
                    const friendId = removeButton.dataset.friendId;
                    const friendName = removeButton.dataset.friendName;
                    showConfirmationModal(
                        `Sei sicuro di voler rimuovere ${friendName}? L'azione è irreversibile e cancellerà la vostra chat.`,
                        function() { 
                            fetch('remove_friend.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ friend_id: friendId })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    showToast(data.message, 'success');
                                    const row = removeButton.closest('tr');
                                    row.style.transition = 'opacity 0.3s ease-out';
                                    row.style.opacity = '0';
                                    setTimeout(() => { row.remove(); }, 300);
                                } else {
                                    showToast(data.message, 'error');
                                }
                            })
                            .catch(err => showToast('Errore di rete.', 'error'));
                        },
                        `Rimuovi ${friendName}`
                    );
                }

                const blockButton = e.target.closest('.toggle-block-btn');
                if (blockButton) {
                    const friendId = blockButton.dataset.friendId;
                    fetch('toggle_block_user.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ friend_id: friendId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            if (data.new_status === 'blocked') {
                                blockButton.textContent = 'Sblocca';
                                blockButton.classList.remove('bg-gray-600', 'hover:bg-gray-500');
                                blockButton.classList.add('bg-yellow-600', 'hover:bg-yellow-500');
                            } else {
                                blockButton.textContent = 'Blocca';
                                blockButton.classList.remove('bg-yellow-600', 'hover:bg-yellow-500');
                                blockButton.classList.add('bg-gray-600', 'hover:bg-gray-500');
                            }
                        } else {
                            showToast(data.message, 'error');
                        }
                    })
                    .catch(err => showToast('Errore di rete.', 'error'));
                }
            });
        }
    });

</script>
<!-- Generic Confirmation Modal -->
<div id="generic-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-60 opacity-0 modal-backdrop" onclick="closeModal('generic-confirm-modal')"></div>
    <div class="bg-gray-800 rounded-2xl w-full max-w-md p-6 shadow-lg transform scale-95 opacity-0 modal-content text-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-900">
             <svg class="h-6 w-6 text-red-400" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h2 id="generic-confirm-title" class="text-xl font-bold text-white mt-4">Conferma Azione</h2>
        <p id="generic-confirm-message" class="text-gray-300 my-4">Sei sicuro di voler procedere?</p>
        <div class="flex justify-center space-x-4">
            <button type="button" onclick="closeModal('generic-confirm-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
            <button type="button" id="generic-confirm-button" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-5 rounded-lg">Conferma</button>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search_query');
    const filterSelect = document.querySelector('select[name="filter"]');
    const tableBody = document.getElementById('friends-table-body');
    const form = document.querySelector('form[method="GET"]');

    // Prevent form submission on Enter key
    form.addEventListener('submit', function(e) {
        e.preventDefault();
    });

    function renderFriends(friends, blocked_ids) {
        tableBody.innerHTML = ''; // Clear existing rows

        if (friends.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-400">Nessun amico trovato con questi criteri.</td></tr>';
            return;
        }

        friends.forEach(friend => {
            const net_balance = friend.total_lent_to_friend - friend.total_borrowed_from_friend;

            let loan_button_class = 'bg-primary-600 hover:bg-primary-700';
            if (net_balance > 0) {
                loan_button_class = 'bg-green-600 hover:bg-green-700';
            } else if (net_balance < 0) {
                loan_button_class = 'bg-red-600 hover:bg-red-700';
            }

            const is_blocked = blocked_ids.includes(friend.id);
            const has_outstanding_balance = net_balance != 0;
            const disabled_attr = has_outstanding_balance ? 'disabled' : '';
            const disabled_class = has_outstanding_balance ? 'opacity-50 cursor-not-allowed' : '';
            const disabled_title = has_outstanding_balance ? "Non puoi eseguire questa azione finché c'è un saldo in sospeso." : '';

            const blockButtonClass = is_blocked ? 'bg-yellow-600 hover:bg-yellow-500' : 'bg-gray-600 hover:bg-gray-500';
            const blockButtonText = is_blocked ? 'Sblocca' : 'Blocca';
            const profilePic = friend.profile_picture_path ? friend.profile_picture_path : 'assets/images/default_avatar.png';

            const row = `
                <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                    <td class="py-2 px-4">
                        <img src="${profilePic}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                    </td>
                    <td class="py-3 px-4">${friend.username}</td>
                    <td class="py-3 px-4">${friend.email}</td>
                    <td class="py-3 px-4 font-mono">${friend.friend_code}</td>
                    <td class="py-3 px-4 text-right">
                        <button onclick="openChatModal(${friend.id}, '${friend.username}')" class="chat-button relative inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg text-sm" data-friend-id="${friend.id}">
                            Chat
                        </button>
                        <button onclick="openLoansModal(${friend.id}, '${friend.username}', ${friend.total_lent_to_friend}, ${friend.total_borrowed_from_friend})" class="${loan_button_class} text-white font-semibold py-1 px-3 rounded-lg text-sm ml-2">
                            Prestiti
                        </button>
                        <button data-friend-id="${friend.id}" class="toggle-block-btn py-1 px-3 rounded-lg text-sm ml-2 font-semibold transition-colors ${blockButtonClass} ${disabled_class}" ${disabled_attr} title="${disabled_title}">
                            ${blockButtonText}
                        </button>
                        <button data-friend-id="${friend.id}" data-friend-name="${friend.username}" class="remove-friend-btn bg-red-800 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg text-sm ml-2 ${disabled_class}" ${disabled_attr} title="${disabled_title}">
                            Rimuovi
                        </button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    async function updateFriendsList() {
        const query = searchInput.value;
        const filter = filterSelect.value;
        const url = `api_search_friends.php?search_query=${encodeURIComponent(query)}&filter=${encodeURIComponent(filter)}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            if (data.success) {
                renderFriends(data.friends, data.blocked_user_ids);
            } else {
                console.error('API Error:', data.message);
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-red-400">Errore nel caricamento degli amici.</td></tr>`;
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-red-400">Errore di connessione.</td></tr>`;
        }
    }

    // Use a debounce function to avoid sending too many requests while typing
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateFriendsList, 300); // Wait 300ms after user stops typing
    });

    filterSelect.addEventListener('change', updateFriendsList);
});
</script>
    <?php include 'page_footer.php'; ?>
</body>
</html>