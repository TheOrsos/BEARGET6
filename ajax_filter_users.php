<?php
session_start();
// Sicurezza: solo l'utente con ID 1 può accedere a questa logica.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["id"] != 1) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once 'db_connect.php';
require_once 'admin_functions.php'; // Usa il nuovo file con la funzione aggiornata

// Imposta l'header per la risposta JSON
header('Content-Type: application/json');

// Raccogli i filtri dalla richiesta GET
$filters = [
    'page' => isset($_GET['page']) ? (int)$_GET['page'] : 1,
    'id' => $_GET['id'] ?? null,
    'search_term' => $_GET['search'] ?? null,
    'subscription_status' => $_GET['subscription_status'] ?? null,
    'account_status' => $_GET['account_status'] ?? null,
    'receives_emails' => $_GET['receives_emails'] ?? null,
    'limit' => 20 // Valore fisso per pagina
];

// Recupera i dati degli utenti usando la nuova funzione
$user_data = get_users_paginated_and_searched_ajax($conn, $filters);
$users_list = $user_data['users'];
$total_pages = $user_data['total_pages'];
$current_page = $user_data['current_page'];

// --- Genera l'HTML per il corpo della tabella ---
ob_start();
if (empty($users_list)) {
    echo '<tr><td colspan="8" class="text-center p-6 text-gray-400">Nessun utente trovato con i filtri attuali.</td></tr>';
} else {
    foreach ($users_list as $user) {
        $user_json = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
?>
        <tr class="border-b border-gray-700 last:border-b-0">
            <td class="p-4"><input type="checkbox" name="user_ids[]" value="<?php echo $user['id']; ?>" class="user-checkbox h-4 w-4 rounded bg-gray-900 border-gray-600 text-primary-600 focus:ring-primary-500"></td>
            <td class="p-4"><?php echo $user['id']; ?></td>
            <td class="p-4 font-semibold"><?php echo htmlspecialchars($user['username']); ?></td>
            <td class="p-4"><?php echo htmlspecialchars($user['email']); ?></td>
            <td class="p-4">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full <?php echo $user['account_status'] === 'active' ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                    <span><?php echo ucfirst($user['account_status']); ?></span>
                </div>
            </td>
            <td class="p-4">
                <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full <?php
                    switch($user['subscription_status']) {
                        case 'active': echo 'bg-green-700 text-green-100'; break;
                        case 'lifetime': echo 'bg-indigo-700 text-indigo-100'; break;
                        case 'pending_cancellation': echo 'bg-yellow-700 text-yellow-100'; break;
                        default: echo 'bg-gray-700 text-gray-100';
                    }
                ?>">
                    <?php echo ucfirst($user['subscription_status']); ?>
                </span>
            </td>
            <td class="p-4 text-center">
                <?php if ($user['receives_emails']): ?>
                    <span title="Email attive"><svg class="w-5 h-5 text-green-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></span>
                <?php else: ?>
                    <span title="Email disattivate"><svg class="w-5 h-5 text-yellow-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6" /></svg></span>
                <?php endif; ?>
            </td>
            <td class="p-4">
                <div class="flex items-center space-x-1">
                    <button onclick='openUserInfoModal(<?php echo $user_json; ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Mostra dettagli utente"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                    <button onclick='openSubscriptionModal(<?php echo $user_json; ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Gestisci Abbonamento"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01M12 18v-2m0-2v-2m0-2V8m0 0h.01M12 18h.01M12 20h.01M12 4h.01M4 12h-2m14 0h2m-7-7v2m0-2V3m2 7h-2m-2 0h-2m7-2v-2m0 2v2m0 0v2m0-2h2m-2-2h-2m-2-2v2m-2-2v-2m2 7h2m-2-2h-2m-2 2v-2m2-2v2"></path></svg></button>
                    <?php if ($user['id'] != 1): ?>
                        <button onclick='openSendEmailModal([<?php echo $user['id']; ?>])' class="p-2 hover:bg-gray-700 rounded-full" title="Invia Email"><svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></button>
                        <button onclick='toggleEmailStatus(<?php echo $user['id']; ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Attiva/Disattiva Email"><svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6" /></svg></button>
                        <a href="impersonate.php?id=<?php echo $user['id']; ?>" class="p-2 hover:bg-gray-700 rounded-full" title="Accedi come <?php echo htmlspecialchars($user['username']); ?>"><svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></a>
                        <button onclick='openSuspendModal(<?php echo $user_json; ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Sospendi/Riattiva Utente"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg></button>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
<?php
    }
}
$table_body_html = ob_get_clean();

// --- Genera l'HTML per la paginazione ---
ob_start();
?>
<span class="text-sm text-gray-400">Pagina <?php echo $current_page; ?> di <?php echo $total_pages > 0 ? $total_pages : 1; ?></span>
<div class="flex gap-2">
    <?php if ($current_page > 1): ?>
        <a href="#" data-page="<?php echo $current_page - 1; ?>" class="pagination-link bg-gray-700 hover:bg-gray-600 font-semibold py-2 px-4 rounded-lg">&laquo; Precedente</a>
    <?php endif; ?>
    <?php if ($current_page < $total_pages): ?>
        <a href="#" data-page="<?php echo $current_page + 1; ?>" class="pagination-link bg-gray-700 hover:bg-gray-600 font-semibold py-2 px-4 rounded-lg">Successivo &raquo;</a>
    <?php endif; ?>
</div>
<?php
$pagination_html = ob_get_clean();

// Restituisci il JSON
echo json_encode([
    'table_body_html' => $table_body_html,
    'pagination_html' => $pagination_html
]);
?>
