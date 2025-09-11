<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'auth_check.php';

$user_id = $_SESSION["id"];
$debts = get_all_user_debts($conn, $user_id);
$accounts = get_user_accounts($conn, $user_id); // Get user accounts for payment modal
$expense_categories = get_user_categories($conn, $user_id, 'expense'); // Get expense categories for automation modal

$total_debt = 0;
foreach ($debts as $debt) {
    $total_debt += $debt['current_balance'];
}

$current_page = 'debts';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I Miei Debiti - Bearget</title>
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
    <style> body { font-family: 'Inter', sans-serif; background-color: var(--color-gray-900); } </style>
</head>
<body class="text-gray-200">

    <div class="flex h-screen">
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
        <?php include 'sidebar.php'; ?>

        <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
            <header class="flex flex-wrap justify-between items-center gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <button id="menu-button" type="button" class="lg:hidden p-2 rounded-md text-gray-400 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8h6m-5 0a3 3 0 110 6H9l-2 2V8a2 2 0 012-2zM15 12a3 3 0 110-6h-2l2 2V12a2 2 0 00-2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01" />
                            </svg>
                            I Miei Debiti
                        </h1>
                        <p class="text-gray-400 mt-1">Traccia e gestisci le tue passività.</p>
                    </div>
                </div>
                <button onclick="openAddModal()" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    Aggiungi Debito
                </button>
            </header>

            <?php include 'toast_notification.php'; ?>

            <div class="mb-8 bg-gray-800 rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-400">Riepilogo Debiti</h2>
                <p class="text-4xl font-bold text-red-400 mt-2">€ <?php echo number_format($total_debt, 2, ',', '.'); ?></p>
                <p class="text-gray-500">Totale di tutte le tue passività.</p>
            </div>

            <div class="bg-gray-800 rounded-2xl p-2">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="text-sm text-gray-400 uppercase">
                            <tr>
                                <th class="p-4">Nome</th>
                                <th class="p-4">Tipo</th>
                                <th class="p-4 text-right">Saldo Attuale</th>
                                <th class="p-4 text-right">Tasso Interesse</th>
                                <th class="p-4 text-right">Pagamento Minimo</th>
                                <th class="p-4 text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            <?php if (empty($debts)): ?>
                                <tr><td colspan="6" class="text-center p-6 text-gray-400">Nessun debito trovato. Inizia aggiungendone uno!</td></tr>
                            <?php else: ?>
                                <?php foreach ($debts as $debt): ?>
                                <tr class="border-b border-gray-700 last:border-b-0">
                                    <td class="p-4">
                                        <div class="font-semibold"><?php echo htmlspecialchars($debt['name']); ?></div>
                                        <?php
                                        $paid_amount = $debt['initial_amount'] - $debt['current_balance'];
                                        $percentage = ($debt['initial_amount'] > 0) ? ($paid_amount / $debt['initial_amount']) * 100 : 0;
                                        ?>
                                        <div class="mt-2">
                                            <div class="w-full bg-gray-700 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: <?php echo min($percentage, 100); ?>%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                                <span>€<?php echo number_format($paid_amount, 2, ',', '.'); ?></span>
                                                <span>€<?php echo number_format($debt['initial_amount'], 2, ',', '.'); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($debt['type'] === 'friend_loan'): ?>
                                            <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full bg-blue-700 text-blue-100">Prestito da Amico</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full bg-purple-700 text-purple-100"><?php echo htmlspecialchars(ucfirst($debt['type'])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-right font-mono text-lg">€ <?php echo number_format($debt['current_balance'], 2, ',', '.'); ?></td>
                                    <td class="p-4 text-right font-mono"><?php echo number_format($debt['interest_rate'], 2, ',', '.'); ?> %</td>
                                    <td class="p-4 text-right font-mono">€ <?php echo number_format($debt['minimum_payment'], 2, ',', '.'); ?></td>
                                    <td class="p-4 text-center">
                                        <?php if ($debt['type'] !== 'friend_loan'): ?>
                                            <div class="flex flex-col items-center space-y-2">
                                                <button onclick='openPayModal(<?php echo json_encode($debt); ?>)' class="p-2 bg-green-600 hover:bg-green-500 rounded-full" title="Effettua Pagamento"><svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg></button>
                                                <?php if ($debt['minimum_payment'] > 0): ?>
                                                    <button onclick='openAutomateModal(<?php echo json_encode($debt); ?>)' class="p-2 bg-blue-600 hover:bg-blue-500 rounded-full" title="Automatizza Pagamento Ricorrente"><svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" /></svg></button>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex items-center justify-center mt-2">
                                                <button onclick='openEditModal(<?php echo json_encode($debt); ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Modifica Debito"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 3.732z"></path></svg></button>
                                                <button onclick='openDeleteModal(<?php echo $debt['id']; ?>)' class="p-2 hover:bg-gray-700 rounded-full" title="Elimina Debito"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-500">Gestito in Amici</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Add Liability Modal -->
    <div id="add-liability-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('add-liability-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-lg p-6 shadow-lg transform scale-95 opacity-0 modal-content">
            <h2 class="text-2xl font-bold text-white mb-6">Aggiungi Nuovo Debito</h2>
            <form action="add_liability.php" method="POST" class="space-y-4">
                <div>
                    <label for="add-name" class="block text-sm font-medium text-gray-300 mb-1">Nome Debito</label>
                    <input type="text" name="name" id="add-name" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2" placeholder="Es. Carta di Credito, Mutuo Casa">
                </div>
                <div>
                    <label for="add-type" class="block text-sm font-medium text-gray-300 mb-1">Tipo</label>
                    <select name="type" id="add-type" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        <option value="credit_card">Carta di Credito</option>
                        <option value="loan">Prestito Personale</option>
                        <option value="mortgage">Mutuo</option>
                        <option value="auto_loan">Prestito Auto</option>
                        <option value="student_loan">Prestito Studentesco</option>
                        <option value="other">Altro</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="add-initial-amount" class="block text-sm font-medium text-gray-300 mb-1">Importo Iniziale</label>
                        <input type="number" name="initial_amount" id="add-initial-amount" required step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2" placeholder="0.00">
                    </div>
                    <div>
                        <label for="add-current-balance" class="block text-sm font-medium text-gray-300 mb-1">Saldo Attuale</label>
                        <input type="number" name="current_balance" id="add-current-balance" required step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2" placeholder="0.00">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="add-interest-rate" class="block text-sm font-medium text-gray-300 mb-1">Tasso Interesse (%)</label>
                        <input type="number" name="interest_rate" id="add-interest-rate" step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2" placeholder="0.00">
                    </div>
                    <div>
                        <label for="add-minimum-payment" class="block text-sm font-medium text-gray-300 mb-1">Pagamento Minimo Mensile</label>
                        <input type="number" name="minimum_payment" id="add-minimum-payment" step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2" placeholder="0.00">
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('add-liability-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-5 rounded-lg">Aggiungi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Liability Modal -->
    <div id="edit-liability-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('edit-liability-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-lg p-6 shadow-lg transform scale-95 opacity-0 modal-content">
            <h2 class="text-2xl font-bold text-white mb-6">Modifica Debito</h2>
            <form action="edit_liability.php" method="POST" class="space-y-4">
                <input type="hidden" name="liability_id" id="edit-liability-id">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-300 mb-1">Nome Debito</label>
                    <input type="text" name="name" id="edit-name" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="edit-type" class="block text-sm font-medium text-gray-300 mb-1">Tipo</label>
                    <select name="type" id="edit-type" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        <option value="credit_card">Carta di Credito</option>
                        <option value="loan">Prestito Personale</option>
                        <option value="mortgage">Mutuo</option>
                        <option value="auto_loan">Prestito Auto</option>
                        <option value="student_loan">Prestito Studentesco</option>
                        <option value="other">Altro</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-initial-amount" class="block text-sm font-medium text-gray-300 mb-1">Importo Iniziale</label>
                        <input type="number" name="initial_amount" id="edit-initial-amount" required step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label for="edit-current-balance" class="block text-sm font-medium text-gray-300 mb-1">Saldo Attuale</label>
                        <input type="number" name="current_balance" id="edit-current-balance" required step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-interest-rate" class="block text-sm font-medium text-gray-300 mb-1">Tasso Interesse (%)</label>
                        <input type="number" name="interest_rate" id="edit-interest-rate" step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label for="edit-minimum-payment" class="block text-sm font-medium text-gray-300 mb-1">Pagamento Minimo Mensile</label>
                        <input type="number" name="minimum_payment" id="edit-minimum-payment" step="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                    </div>
                </div>
                <div class="mt-8 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('edit-liability-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-5 rounded-lg">Salva Modifiche</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Liability Modal -->
    <div id="delete-liability-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('delete-liability-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-md p-6 shadow-lg transform scale-95 opacity-0 modal-content text-center">
            <h2 class="text-2xl font-bold text-white mb-4">Conferma Eliminazione</h2>
            <p class="text-gray-300 mb-6">Sei sicuro di voler eliminare questo debito? L'azione è irreversibile.</p>
            <form action="delete_liability.php" method="POST" class="inline-block">
                <input type="hidden" name="liability_id" id="delete-liability-id">
                <button type="button" onclick="closeModal('delete-liability-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg mr-4">Annulla</button>
                <button type="submit" class="bg-danger hover:bg-red-700 text-white font-semibold py-2 px-5 rounded-lg">Elimina</button>
            </form>
        </div>
    </div>

    <script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        const backdrop = modal.querySelector('.modal-backdrop');
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop?.classList.remove('opacity-0');
            content?.classList.remove('opacity-0', 'scale-95');
        }, 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        const backdrop = modal.querySelector('.modal-backdrop');
        const content = modal.querySelector('.modal-content');
        backdrop?.classList.add('opacity-0');
        content?.classList.add('opacity-0', 'scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openAddModal() {
        openModal('add-liability-modal');
    }

    function openEditModal(debt) {
        document.getElementById('edit-liability-id').value = debt.id;
        document.getElementById('edit-name').value = debt.name;
        document.getElementById('edit-type').value = debt.type;
        document.getElementById('edit-initial-amount').value = debt.initial_amount;
        document.getElementById('edit-current-balance').value = debt.current_balance;
        document.getElementById('edit-interest-rate').value = debt.interest_rate;
        document.getElementById('edit-minimum-payment').value = debt.minimum_payment;
        openModal('edit-liability-modal');
    }

    function openDeleteModal(liabilityId) {
        document.getElementById('delete-liability-id').value = liabilityId;
        openModal('delete-liability-modal');
    }

    function openPayModal(debt) {
        document.getElementById('pay-liability-id').value = debt.id;
        document.getElementById('pay-debt-name').textContent = debt.name;
        const amountInput = document.getElementById('pay-amount');
        amountInput.value = debt.current_balance;
        amountInput.max = debt.current_balance;
        openModal('pay-liability-modal');
    }

    function openAutomateModal(debt) {
        document.getElementById('automate-liability-id').value = debt.id;
        document.getElementById('automate-debt-name').textContent = debt.name;
        document.getElementById('automate-amount').value = debt.minimum_payment;
        document.getElementById('automate-description').value = "Rata per: " + debt.name;
        openModal('automate-payment-modal');
    }
    </script>

    <!-- Automate Payment Modal -->
    <div id="automate-payment-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('automate-payment-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-lg p-6 shadow-lg transform scale-95 opacity-0 modal-content">
            <h2 class="text-2xl font-bold text-white mb-2">Automatizza Pagamento</h2>
            <p class="text-gray-400 mb-6">Crea una spesa ricorrente per: <strong id="automate-debt-name" class="text-primary-400"></strong></p>
            <form action="automate_liability_payment.php" method="POST" class="space-y-4">
                <input type="hidden" name="liability_id" id="automate-liability-id">
                <input type="hidden" name="amount" id="automate-amount">
                <input type="hidden" name="description" id="automate-description">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Importo Rata</label>
                    <p class="text-lg font-bold">€ <span id="automate-amount-display"></span></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="automate-account-id" class="block text-sm font-medium text-gray-300 mb-1">Conto di Addebito</label>
                        <select name="account_id" id="automate-account-id" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                            <?php foreach($accounts as $account): ?>
                                <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="automate-category-id" class="block text-sm font-medium text-gray-300 mb-1">Categoria Spesa</label>
                        <select name="category_id" id="automate-category-id" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                            <?php foreach($expense_categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-300 mb-1">Data del Prossimo Pagamento</label>
                    <input type="date" name="start_date" id="start_date" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                </div>

                <div class="pt-4 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('automate-payment-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-lg">Crea Spesa Ricorrente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pay Liability Modal -->
    <div id="pay-liability-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-60 modal-backdrop" onclick="closeModal('pay-liability-modal')"></div>
        <div class="bg-gray-800 rounded-2xl w-full max-w-md p-6 shadow-lg transform scale-95 opacity-0 modal-content">
            <h2 class="text-2xl font-bold text-white mb-2">Effettua Pagamento</h2>
            <p class="text-gray-400 mb-6">Paga il tuo debito: <strong id="pay-debt-name" class="text-primary-400"></strong></p>
            <form action="pay_liability.php" method="POST" class="space-y-4">
                <input type="hidden" name="liability_id" id="pay-liability-id">
                <div>
                    <label for="pay-amount" class="block text-sm font-medium text-gray-300 mb-1">Importo da Pagare (€)</label>
                    <input type="number" name="amount" id="pay-amount" required step="0.01" min="0.01" class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                </div>
                <div>
                    <label for="pay-account-id" class="block text-sm font-medium text-gray-300 mb-1">Usa fondi dal conto</label>
                    <select name="account_id" id="pay-account-id" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        <?php foreach($accounts as $account): ?>
                            <option value="<?php echo $account['id']; ?>"><?php echo htmlspecialchars($account['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-4 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal('pay-liability-modal')" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-5 rounded-lg">Annulla</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-lg">Paga Ora</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'page_footer.php'; ?>
</body>
</html>
