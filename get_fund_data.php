<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_GET['fund_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized']);
    exit;
}

require_once 'db_connect.php';
require_once 'functions.php';

$fund_id = (int)$_GET['fund_id'];
$user_id = $_SESSION['id'];

// Security Check: User must be a member of the fund
$members = get_fund_members($conn, $fund_id);
$member_ids = array_column($members, 'id');
if (!in_array($user_id, $member_ids)) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied to this fund.']);
    exit;
}

// Fetch fresh data
$group_expenses = get_group_expenses($conn, $fund_id);
$contributions = get_fund_contributions($conn, $fund_id);
$balances = get_group_balances($conn, $fund_id);
$fund = get_shared_fund_details($conn, $fund_id, $user_id);


// --- RENDER HTML FOR EXPENSES ---
ob_start();
if(empty($group_expenses)): ?>
    <div class="text-center py-8 text-gray-500">
        <p>Nessuna spesa registrata in questo gruppo.</p>
    </div>
<?php else: foreach($group_expenses as $expense): ?>
<div class="flex items-center justify-between p-2 rounded-lg transition-colors hover:bg-gray-700/50">
    <div class="flex items-center gap-3">
        <span class="text-2xl"><?php echo htmlspecialchars($expense['category_icon'] ?? '💰'); ?></span>
        <div>
            <p class="font-semibold text-white"><?php echo htmlspecialchars($expense['description']); ?></p>
            <p class="text-sm text-gray-400">
                Pagato da <?php echo htmlspecialchars($expense['paid_by_username']); ?> il <?php echo date("d/m/Y", strtotime($expense['expense_date'])); ?>
                <?php if($expense['category_name']): ?>
                <span class="font-bold"> · </span> <?php echo htmlspecialchars($expense['category_name']); ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <?php if($expense['note_id']): ?>
        <a href="note_details.php?id=<?php echo $expense['note_id']; ?>" class="text-gray-400 hover:text-white" title="Visualizza nota">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </a>
        <?php endif; ?>
        <p class="font-bold text-danger text-lg">-€<?php echo number_format($expense['amount'], 2, ',', '.'); ?></p>
    </div>
</div>
<?php endforeach; endif;
$expenses_html = ob_get_clean();


// --- RENDER HTML FOR CONTRIBUTIONS ---
ob_start();
if(empty($contributions)): ?>
    <div class="text-center py-8 text-gray-500">
        <p>Nessun contributo ancora versato.</p>
    </div>
<?php else: foreach($contributions as $c): ?>
<div class="flex items-center justify-between p-2 rounded-lg transition-colors hover:bg-gray-700/50">
    <div>
        <p class="font-semibold text-white"><?php echo htmlspecialchars($c['username']); ?></p>
        <p class="text-sm text-gray-400"><?php echo date("d/m/Y", strtotime($c['contribution_date'])); ?></p>
    </div>
    <p class="font-bold text-success">+€<?php echo number_format($c['amount'], 2, ',', '.'); ?></p>
</div>
<?php endforeach; endif;
$contributions_html = ob_get_clean();

// --- RENDER HTML FOR BALANCES ---
ob_start();
$users_who_owe = array_filter($balances, function($b) { return $b['balance'] < 0; });
$users_who_are_owed = array_filter($balances, function($b) { return $b['balance'] > 0; });
?>
<h2 class="text-xl font-bold text-white mb-4">Bilanci</h2>
<div class="space-y-4">
    <div>
        <h3 class="text-md font-semibold text-gray-400 mb-2 border-b border-gray-700 pb-1">Chi deve dare</h3>
        <div class="space-y-2 pt-2">
        <?php if(empty($users_who_owe)): ?>
            <p class="text-sm text-gray-500">Nessuno deve soldi al gruppo.</p>
        <?php else: foreach($users_who_owe as $balance): ?>
        <div class="flex items-center justify-between p-1">
            <span class="text-white"><?php echo htmlspecialchars($balance['username']); ?></span>
            <span class="font-bold text-danger">€<?php echo number_format(abs($balance['balance']), 2, ',', '.'); ?></span>
        </div>
        <?php endforeach; endif; ?>
        </div>
    </div>
    <div>
        <h3 class="text-md font-semibold text-gray-400 mb-2 border-b border-gray-700 pb-1">Chi deve ricevere</h3>
        <div class="space-y-2 pt-2">
        <?php if(empty($users_who_are_owed)): ?>
            <p class="text-sm text-gray-500">Nessuno deve ricevere soldi dal gruppo.</p>
        <?php else: foreach($users_who_are_owed as $balance): ?>
        <div class="flex items-center justify-between p-1">
            <span class="text-white"><?php echo htmlspecialchars($balance['username']); ?></span>
            <span class="font-bold text-success">+€<?php echo number_format($balance['balance'], 2, ',', '.'); ?></span>
        </div>
        <?php endforeach; endif; ?>
        </div>
    </div>
</div>
<?php
$balances_html = ob_get_clean();


// --- RENDER HTML FOR FUND SUMMARY ---
ob_start();
$percentage = ($fund['target_amount'] > 0) ? ($fund['total_contributed'] / $fund['target_amount']) * 100 : 0;
?>
<h2 class="text-xl font-bold text-white mb-4">Riepilogo</h2>
<div class="w-full bg-gray-700 rounded-full h-4 mb-2">
    <div class="bg-green-500 h-4 rounded-full text-center text-white text-xs font-bold" style="width: <?php echo min($percentage, 100); ?>%"><?php echo round($percentage); ?>%</div>
</div>
<div class="flex justify-between text-lg text-gray-300">
    <span class="font-bold text-white">€<?php echo number_format($fund['total_contributed'], 2, ',', '.'); ?></span>
    <span class="text-gray-400">di €<?php echo number_format($fund['target_amount'], 2, ',', '.'); ?></span>
</div>
<?php
$summary_html = ob_get_clean();


// --- COMPILE AND SEND RESPONSE ---
echo json_encode([
    'expenses_html' => $expenses_html,
    'contributions_html' => $contributions_html,
    'balances_html' => $balances_html,
    'summary_html' => $summary_html
]);

$conn->close();
?>
