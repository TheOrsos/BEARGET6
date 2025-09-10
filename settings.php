<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
require_once 'db_connect.php';
require_once 'functions.php';
require_once 'auth_check.php';

// --- Logica Manutenzione ---
$maintenance_mode = get_maintenance_status($conn);
$is_maintenance_on = ($maintenance_mode === 'on');
// ---

$user_id = $_SESSION["id"];
$user = get_user_by_id($conn, $user_id);

$current_page = 'settings'; 
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impostazioni - Bearget</title>
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
        <?php 
        // 2. INCLUDI LA SIDEBAR
        include 'sidebar.php'; 
        ?>

        <!-- Main Content -->
<main class="flex-1 p-6 lg:p-10 overflow-y-auto">
    <header class="mb-8">
        <div class="flex items-center gap-4">
            <button id="menu-button" type="button" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Apri menu principale</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    Impostazioni
                </h1>
                <p class="text-gray-400 mt-1">Gestisci il tuo profilo.</p>
            </div>
        </div>
    </header>

    <?php if ($is_maintenance_on): ?>
    <div class="p-4 mb-6 text-sm rounded-lg bg-yellow-900 text-yellow-300 border border-yellow-700 text-center">
        <h3 class="font-bold">Sito in Modalità Manutenzione</h3>
        <p>Le impostazioni del profilo sono temporaneamente disabilitate. La gestione dell'abbonamento rimane attiva.</p>
    </div>
    <?php endif; ?>

    <div class="space-y-8">

        <!-- Riquadro Seleziona Tema -->
        <div id="theme-selection-box" class="bg-gray-800 rounded-2xl p-6 <?php if ($is_maintenance_on) echo 'opacity-50 cursor-not-allowed'; ?>">
            <fieldset <?php if ($is_maintenance_on) echo 'disabled'; ?>>
                <h2 class="text-xl font-bold text-white mb-4">Seleziona Tema</h2>
                <form action="update_theme.php" method="POST">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php
                        $pro_themes = ['dark-gold'];
                        $themes = [
                            'dark-indigo' => 'bg-gradient-to-br from-indigo-500 to-purple-600', 'forest-green' => 'bg-gradient-to-br from-green-500 to-emerald-600', 'ocean-blue' => 'bg-gradient-to-br from-blue-500 to-cyan-600',
                            'sunset-orange' => 'bg-gradient-to-br from-orange-500 to-red-600', 'royal-purple' => 'bg-gradient-to-br from-purple-500 to-fuchsia-600', 'graphite-gray' => 'bg-gradient-to-br from-gray-500 to-slate-600',
                            'dark-gold' => 'bg-gradient-to-br from-yellow-400 to-amber-600', 'modern-dark' => 'bg-gradient-to-br from-violet-500 to-purple-700', 'foggy-gray' => 'bg-gradient-to-br from-gray-400 to-slate-500',
                            'crimson-white' => 'bg-gradient-to-br from-red-500 to-rose-700', 'neon-dreams' => 'bg-gradient-to-br from-pink-500 to-fuchsia-600', 'lunar-rays' => 'bg-gradient-to-br from-gray-200 to-slate-400',
                            'autumn-breeze' => 'bg-gradient-to-br from-orange-400 to-amber-500', 'jungle-heart' => 'bg-gradient-to-br from-stone-600 to-lime-800', 'ice-kingdom' => 'bg-gradient-to-br from-sky-400 to-cyan-500',
                            'violet-night' => 'bg-purple-500',
                            'willow-tree' => 'bg-gradient-to-br from-[#728167] to-[#34533f]',
                        ];
                        foreach($themes as $theme_key => $color_class):
                            $display_name = ucwords(str_replace('-', ' ', $theme_key));
                            if (in_array($theme_key, $pro_themes) && !$is_pro) {
                        ?>
                            <div class="h-24 w-full rounded-lg flex items-center justify-center p-3 text-center font-bold text-white relative opacity-60 cursor-not-allowed <?php echo $color_class; ?>" title="Passa a Pro per sbloccare questo tema">
                                <span class="drop-shadow-lg"><?php echo $display_name; ?></span>
                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg">
                                    <svg class="w-10 h-10 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                            </div>
                        <?php } else { ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="theme" value="<?php echo $theme_key; ?>" class="sr-only" onchange="this.form.submit()" <?php if ($_SESSION['theme'] == $theme_key) echo 'checked'; ?>>
                            <div class="h-24 w-full rounded-lg flex items-center justify-center p-3 text-center font-bold text-white relative <?php echo $color_class; ?> <?php echo ($_SESSION['theme'] == $theme_key) ? 'ring-4 ring-offset-2 ring-offset-gray-800 ring-white' : 'ring-2 ring-transparent'; ?> hover:scale-105 hover:ring-white transition-all duration-200">
                                <span class="drop-shadow-lg"><?php echo $display_name; ?></span>
                                <?php if ($_SESSION['theme'] == $theme_key): ?>
                                    <div class="absolute top-2 right-2 bg-white rounded-full p-0.5 shadow">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </label>
                        <?php } endforeach; ?>
                    </div>
                </form>
            </fieldset>
        </div>

        <!-- Riquadri Profilo -->
        <div id="profile-management-grid" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-gray-800 rounded-2xl p-6 <?php if ($is_maintenance_on) echo 'opacity-50 cursor-not-allowed'; ?>">
                 <fieldset <?php if ($is_maintenance_on) echo 'disabled'; ?>>
                    <h2 class="text-xl font-bold text-white mb-4">Foto Profilo</h2>
                    <form action="update_profile_picture.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div class="flex justify-center">
                            <img src="<?php echo !empty($user['profile_picture_path']) ? htmlspecialchars($user['profile_picture_path']) : 'assets/images/default_avatar.png'; ?>" alt="Avatar" class="w-32 h-32 rounded-full object-cover border-4 border-gray-700">
                        </div>
                        <div>
                            <label for="profile_picture" class="block text-sm font-medium text-gray-300 mb-1">Carica una nuova foto</label>
                            <input type="file" name="profile_picture" id="profile_picture" required class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-600 file:text-white hover:file:bg-primary-700">
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg">Salva Foto</button>
                    </form>
                 </fieldset>
            </div>
            <div class="bg-gray-800 rounded-2xl p-6 <?php if ($is_maintenance_on) echo 'opacity-50 cursor-not-allowed'; ?>">
                <fieldset <?php if ($is_maintenance_on) echo 'disabled'; ?>>
                    <h2 class="text-xl font-bold text-white mb-4">Modifica Profilo</h2>
                    <form action="update_profile.php" method="POST" class="space-y-4">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Indirizzo Email</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="w-full bg-gray-900 text-gray-400 rounded-lg px-3 py-2 cursor-not-allowed">
                        </div>
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-sm font-medium text-gray-300">Ricevi email e notifiche</span>
                            <label for="email-toggle" class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="receives_emails" value="1" id="email-toggle" class="sr-only peer" <?php echo $user['receives_emails'] ? 'checked' : ''; ?>>
                                <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary-500/50 peer-checked:bg-primary-600"></div>
                                <div class="absolute left-1 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg !mt-6">Salva Profilo</button>
                    </form>
                </fieldset>
            </div>
            <div class="bg-gray-800 rounded-2xl p-6 <?php if ($is_maintenance_on) echo 'opacity-50 cursor-not-allowed'; ?>">
                <fieldset <?php if ($is_maintenance_on) echo 'disabled'; ?>>
                    <h2 class="text-xl font-bold text-white mb-4">Cambia Password</h2>
                    <form action="update_password.php" method="POST" class="space-y-4" id="password-form">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1">Password Attuale</label>
                            <input type="password" name="current_password" id="current_password" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-300 mb-1">Nuova Password</label>
                            <input type="password" name="new_password" id="new_password" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                            <p id="password-requirement-text" class="mt-2 text-xs text-gray-500 transition-colors">
                                La password deve contenere almeno 8 caratteri. 
                                <span id="password-char-count" class="font-medium">0/8</span>
                            </p>
                        </div>
                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-1">Conferma Nuova Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required class="w-full bg-gray-700 text-white rounded-lg px-3 py-2">
                        </div>
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg">Cambia Password</button>
                    </form>
                </fieldset>
            </div>
        </div>

        <!-- Riquadro Codice Amico -->
        <div id="friend-code-box" class="bg-gray-800 rounded-2xl p-6 <?php if ($is_maintenance_on) echo 'opacity-50'; ?>">
            <h2 class="text-xl font-bold text-white mb-4">Il Tuo Codice Amico</h2>
            <p class="text-gray-400 mb-2">Condividi questo codice per farti invitare nei fondi comuni.</p>
            <div class="bg-gray-900 text-white text-center font-mono text-2xl tracking-widest py-3 rounded-lg">
                <?php echo htmlspecialchars($user['friend_code']); ?>
            </div>
        </div>

        <!-- Gestione Abbonamento -->
        <div id="subscription-management-box" class="bg-gray-800 rounded-2xl p-6">
            <h2 class="text-xl font-bold text-white mb-4">Gestisci Abbonamento</h2>
            <?php if ($user['subscription_status'] == 'pending_cancellation'): ?>
                <?php
                    $days_remaining_text = 'pochi';
                    if (!empty($user['subscription_end_date']) && strtotime($user['subscription_end_date']) !== false) {
                        $end_date = new DateTime($user['subscription_end_date']);
                        $today = new DateTime();
                        if ($end_date > $today) {
                            $interval = $today->diff($end_date);
                            $days_remaining = $interval->days;
                            if ($days_remaining > 0) {
                                $days_text = ($days_remaining == 1 ? 'giorno' : 'giorni');
                                $message = "Potrai ancora usufruire dei vantaggi Pro per i prossimi <strong>{$days_remaining} {$days_text}</strong> (fino al " . $end_date->format('d/m/Y') . ").";
                            } else {
                                $message = "Il tuo abbonamento scadrà oggi. Potrai usufruire dei vantaggi Pro fino alle " . $end_date->format('H:i') . " del " . $end_date->format('d/m/Y') . ".";
                            }
                        } else {
                            $message = "Il tuo abbonamento Pro è scaduto il " . $end_date->format('d/m/Y') . ".";
                        }
                    }
                ?>
                <div class="p-4 text-sm rounded-lg bg-yellow-900 text-yellow-300 border border-yellow-700 mb-4">
                    <p class="font-bold">Il tuo abbonamento è impostato per essere annullato.</p>
                    <p class="mt-1"><?php echo $message; ?></p>
                </div>
                <a href="create-portal-session.php" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg">Riattiva o gestisci dal Portale Clienti</a>
            <?php elseif ($user['subscription_status'] == 'active' && !empty($user['stripe_customer_id'])): ?>
                <p class="text-gray-400 mb-4">Grazie per essere un utente Pro! Gestisci il tuo metodo di pagamento o annulla il tuo abbonamento dal portale sicuro di Stripe.</p>
                <a href="create-portal-session.php" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg">Vai al Portale Clienti</a>
            <?php elseif ($user['subscription_status'] == 'lifetime' || $user['subscription_status'] == 'active'): ?>
                <p class="text-gray-400">Il tuo accesso Pro è attivo. Grazie per il tuo supporto!</p>
            <?php else: ?>
                <?php if ($is_maintenance_on): ?>
                    <div class="p-4 text-sm rounded-lg bg-gray-700 text-gray-300 text-center">
                        <p class="font-bold">Upgrade a Pro Disabilitato</p>
                        <p class="text-xs mt-1">Gli upgrade sono temporaneamente sospesi durante la manutenzione del sito.</p>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 mb-4">Attualmente sei sul piano Free. Passa a Pro per sbloccare tutte le funzionalità.</p>
                    <a href="pricing.php" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-lg">Passa a Bearget Pro</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>
    </div>
    <script>
        document.getElementById('password-form').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('La nuova password e la conferma non coincidono.');
            }
        });

        // --- Inizio codice contatore password ---
        const newPasswordInput = document.getElementById('new_password');
        const charCountSpan = document.getElementById('password-char-count');
        const requirementText = document.getElementById('password-requirement-text');

        if (newPasswordInput && charCountSpan && requirementText) {
            newPasswordInput.addEventListener('input', () => {
                const count = newPasswordInput.value.length;
                charCountSpan.textContent = `${count}/8`;
                if (count >= 8) {
                    requirementText.classList.remove('text-gray-500');
                    requirementText.classList.add('text-green-500');
                } else {
                    requirementText.classList.remove('text-green-500');
                    requirementText.classList.add('text-gray-500');
                }
            });
        }
        // --- Fine codice contatore password ---      
        
// --- Inizio codice per mostrare notifiche TOAST da URL ---

// Logica per leggere i parametri dall'URL e mostrare il toast
const params = new URLSearchParams(window.location.search);
if (params.has('message')) {
    const message = decodeURIComponent(params.get('message'));
    const type = params.get('type') || 'error';
    
    // Mostra il toast dopo che la pagina è completamente caricata
    setTimeout(() => {
        showToast(message, type);
    }, 100);

    // Pulisce l'URL
    if (window.history.replaceState) {
        const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        window.history.replaceState({path: cleanUrl}, '', cleanUrl);
    }
}
// --- Fine codice per mostrare notifiche TOAST da URL ---
    </script>
    <?php include 'toast_notification.php'; ?>
    <?php include 'page_footer.php'; ?>
</body>
</html>