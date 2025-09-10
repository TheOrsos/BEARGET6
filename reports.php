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

// CONTROLLO ACCESSO PRO
$user = get_user_by_id($conn, $user_id);
if ($user['subscription_status'] !== 'active' && $user['subscription_status'] !== 'lifetime') {
    header("location: pricing.php?message=Accedi ai report avanzati con un piano Premium!");
    exit;
}

// GESTIONE FILTRI per caricamento iniziale
$today = date('Y-m-d');
$six_months_ago = date('Y-m-d', strtotime('-5 months'));
$selected_account_id = $_GET['account_id'] ?? ''; 
$selected_tag_id = $_GET['tag_id'] ?? '';

$initial_filters = [
    'start_date' => $_GET['start_date'] ?? $six_months_ago,
    'end_date' => $_GET['end_date'] ?? $today,
    'tag_id' => $selected_tag_id,
    'account_ids' => !empty($selected_account_id) ? [$selected_account_id] : []
];

// Dati iniziali per i grafici
$expensesByCategory = get_expenses_by_category($conn, $user_id, $initial_filters);
$incomeExpenseTrend = get_income_expense_trend($conn, $user_id, $initial_filters);
$netWorthTrend = get_net_worth_trend($conn, $user_id, $initial_filters);

// Dati per i menu a tendina
$userAccounts = get_user_accounts($conn, $user_id);
$userTags = get_user_tags($conn, $user_id);
$current_page = 'reports';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - Bearget</title>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: var(--color-gray-900); }
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
                        <span class="sr-only">Apri menu principale</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h1 class="text-3xl font-bold  flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Report finanziari
                        </h1>
                        <p class="text-gray-400 mt-1">Analizza le tue finanze con i grafici</p>
                    </div>
                </div>
            </header>

            <div class="bg-gray-800 rounded-2xl p-4 mb-8">
                <form id="reports-filter-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="start_date" class="text-sm font-medium text-gray-400">Da</label>
                        <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($initial_filters['start_date']); ?>" class="w-full bg-gray-700 rounded-lg px-3 py-2 mt-1 text-sm">
                    </div>
                    <div>
                        <label for="end_date" class="text-sm font-medium text-gray-400">A</label>
                        <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($initial_filters['end_date']); ?>" class="w-full bg-gray-700 rounded-lg px-3 py-2 mt-1 text-sm">
                    </div>
                    <div>
                        <label for="account_id" class="text-sm font-medium text-gray-400">Conto</label>
                        <select name="account_ids[]" id="account_id" class="w-full bg-gray-700 rounded-lg px-3 py-2 mt-1 text-sm">
                            <option value="">Tutti i conti</option>
                            <?php foreach($userAccounts as $account): ?>
                                <option value="<?php echo $account['id']; ?>" <?php echo ($account['id'] == $selected_account_id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($account['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="tag_id" class="text-sm font-medium text-gray-400">Etichetta</label>
                        <select name="tag_id" id="tag_id" class="w-full bg-gray-700 rounded-lg px-3 py-2 mt-1 text-sm">
                            <option value="">Tutte le etichette</option>
                            <option value="_any_" <?php echo ($selected_tag_id == '_any_') ? 'selected' : ''; ?>>Con etichette</option>
                            <option value="_none_" <?php echo ($selected_tag_id == '_none_') ? 'selected' : ''; ?>>Senza etichette</option>
                            <option disabled>──────────</option>
                            <?php foreach($userTags as $tag): ?>
                                <option value="<?php echo $tag['id']; ?>" <?php echo ($tag['id'] == $selected_tag_id) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <div class="space-y-8">
                <div class="bg-gray-800 rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4">Spese per Categoria</h2>
                    <div class="h-80"><canvas id="expensesChart"></canvas></div>
                </div>
                <div class="bg-gray-800 rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4">Entrate vs. Uscite</h2>
                    <div class="h-80"><canvas id="incomeExpenseChart"></canvas></div>
                </div>
                <div class="bg-gray-800 rounded-2xl p-6">
                    <h2 class="text-xl font-bold mb-4">Andamento Patrimonio Netto</h2>
                    <div class="h-80"><canvas id="netWorthChart"></canvas></div>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartColors = {
            purple: '#8b5cf6', blue: '#3b82f6', green: '#10b981', red: '#ef4444',
            grid: 'rgba(255, 255, 255, 0.1)', text: '#d1d5db'
        };

        let expensesChart, incomeExpenseChart, netWorthChart;

        const initialData = {
            expensesByCategory: <?php echo json_encode($expensesByCategory); ?>,
            incomeExpenseTrend: <?php echo json_encode($incomeExpenseTrend); ?>,
            netWorthTrend: <?php echo json_encode($netWorthTrend); ?>
        };

        function setupCharts(data) {
            // Expenses Chart
            if (expensesChart) {
                expensesChart.data.labels = data.expensesByCategory.labels;
                expensesChart.data.datasets[0].data = data.expensesByCategory.values;
                expensesChart.update();
            } else {
                const expCtx = document.getElementById('expensesChart').getContext('2d');
                expensesChart = new Chart(expCtx, {
                    type: 'doughnut',
                    data: {
                        labels: data.expensesByCategory.labels,
                        datasets: [{
                            data: data.expensesByCategory.values,
                            backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7'],
                            borderColor: 'var(--color-gray-800)',
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { color: chartColors.text } } } }
                });
            }

            // Income vs Expense Chart
            if (incomeExpenseChart) {
                incomeExpenseChart.data.labels = data.incomeExpenseTrend.labels;
                incomeExpenseChart.data.datasets[0].data = data.incomeExpenseTrend.income;
                incomeExpenseChart.data.datasets[1].data = data.incomeExpenseTrend.expenses;
                incomeExpenseChart.update();
            } else {
                const ieCtx = document.getElementById('incomeExpenseChart').getContext('2d');
                incomeExpenseChart = new Chart(ieCtx, {
                    type: 'line',
                    data: {
                        labels: data.incomeExpenseTrend.labels,
                        datasets: [{
                            label: 'Entrate', data: data.incomeExpenseTrend.income, borderColor: chartColors.green, backgroundColor: 'rgba(16, 185, 129, 0.1)', fill: true, tension: 0.4
                        }, {
                            label: 'Uscite', data: data.incomeExpenseTrend.expenses, borderColor: chartColors.red, backgroundColor: 'rgba(239, 68, 68, 0.1)', fill: true, tension: 0.4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { color: chartColors.text }, grid: { color: chartColors.grid } }, x: { ticks: { color: chartColors.text }, grid: { color: chartColors.grid } } }, plugins: { legend: { labels: { color: chartColors.text } } } }
                });
            }

            // Net Worth Chart
            if (netWorthChart) {
                netWorthChart.data.labels = data.netWorthTrend.labels;
                netWorthChart.data.datasets[0].data = data.netWorthTrend.values;
                netWorthChart.update();
            } else {
                const nwCtx = document.getElementById('netWorthChart').getContext('2d');
                netWorthChart = new Chart(nwCtx, {
                    type: 'bar',
                    data: {
                        labels: data.netWorthTrend.labels,
                        datasets: [{
                            label: 'Patrimonio Netto', data: data.netWorthTrend.values, backgroundColor: chartColors.blue, borderColor: chartColors.blue, borderWidth: 1
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: false, ticks: { color: chartColors.text }, grid: { color: chartColors.grid } }, x: { ticks: { color: chartColors.text }, grid: { display: false } } }, plugins: { legend: { display: false } } }
                });
            }
        }
        
        const filterForm = document.getElementById('reports-filter-form');
        const filterInputs = filterForm.querySelectorAll('input, select');
        let debounceTimer;

        async function updateCharts() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            // Handle select which can have multiple values in the future, even if not used now
            for (const [key, value] of formData.entries()) {
                if (key.endsWith('[]')) {
                    params.append(key, value);
                } else {
                    params.set(key, value);
                }
            }
            
            const url = `api_get_report_data.php?${params.toString()}`;

            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok.');
                const data = await response.json();
                if (data.success) {
                    setupCharts(data);
                } else {
                    console.error('API Error:', data.message);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
            }
        }
        
        filterInputs.forEach(input => {
            input.addEventListener('change', () => { // 'change' è meglio per date e select
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(updateCharts, 300);
            });
        });

        // Setup iniziale dei grafici
        setupCharts(initialData);
    });
    </script>
    <?php include 'page_footer.php'; ?>
</body>
</html>