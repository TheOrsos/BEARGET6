<?php
session_start();
header("Content-type: text/css");

// Tema di default se non impostato
$theme = $_SESSION['theme'] ?? 'dark-indigo';

// Definisci le palette di colori per ogni tema
$palettes = [
    'dark-indigo' => [
        '500' => '#6366f1', '600' => '#4f46e5', '700' => '#4338ca',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#374151', 'gray-800' => '#1f2937', 'gray-900' => '#111827',
    ],
    'forest-green' => [
        '500' => '#22c55e', '600' => '#16a34a', '700' => '#15803d',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#3f3f46', 'gray-800' => '#27272a', 'gray-900' => '#18181b',
    ],
    'ocean-blue' => [
        '500' => '#3b82f6', '600' => '#2563eb', '700' => '#1d4ed8',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#374151', 'gray-800' => '#1f2937', 'gray-900' => '#111827',
    ],
    'sunset-orange' => [
        '500' => '#f97316', '600' => '#ea580c', '700' => '#c2410c',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#44403c', 'gray-800' => '#292524', 'gray-900' => '#1c1917',
    ],
    'royal-purple' => [
        '500' => '#a855f7', '600' => '#9333ea', '700' => '#7e22ce',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#3730a3', 'gray-800' => '#312e81', 'gray-900' => '#1e1b4b',
    ],
    'graphite-gray' => [
        '500' => '#6b7280', '600' => '#4b5563', '700' => '#374151',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#374151', 'gray-800' => '#1f2937', 'gray-900' => '#111827',
    ],
    'dark-gold' => [
        '500' => '#d69e2e', '600' => '#b7791f', '700' => '#975a16',
        'gray-100' => '#f7fafc', 'gray-200' => '#edf2f7', 'gray-300' => '#e2e8f0', 'gray-400' => '#cbd5e0',
        'gray-700' => '#2d3748', 'gray-800' => '#1a202c', 'gray-900' => '#12151f',
    ],
    'modern-dark' => [
        '500' => '#a78bfa', '600' => '#8b5cf6', '700' => '#7c3aed',
        'gray-100' => '#f1f5f9', 'gray-200' => '#e2e8f0', 'gray-300' => '#cbd5e0', 'gray-400' => '#94a3b8',
        'gray-700' => '#334155', 'gray-800' => '#1e293b', 'gray-900' => '#0f172a',
    ],
    'foggy-gray' => [
        '500' => '#9ca3af', '600' => '#6b7280', '700' => '#4b5563',
        'gray-100' => '#f9fafb', 'gray-200' => '#f3f4f6', 'gray-300' => '#e5e7eb', 'gray-400' => '#d1d5db',
        'gray-700' => '#374151', 'gray-800' => '#1f2937', 'gray-900' => '#111827',
    ],
    'crimson-white' => [
        '500' => '#DC2626', // Red
        '600' => '#B91C1C',
        '700' => '#991B1B',
        'gray-100' => '#111827', // Dark text
        'gray-200' => '#374151',
        'gray-300' => '#6b7280',
        'gray-400' => '#9ca3af',
        'gray-700' => '#e5e7eb', // Light panel background
        'gray-800' => '#f3f4f6', // Lighter panel background
        'gray-900' => '#ffffff', // White background
    ],
    'neon-dreams' => [
        '500' => '#e040fb', // A deeper pink/purple
        '600' => '#d020e8',
        '700' => '#b812ce',
        'gray-100' => '#f5f3f7',
        'gray-200' => '#e0dce6',
        'gray-300' => '#cbc5d4',
        'gray-400' => '#a9a1b8',
        'gray-700' => '#3a2f45', // Darker panel color
        'gray-800' => '#1f1529', // Even darker
        'gray-900' => '#0d021a', // Very dark purple background
        'neon-green' => '#39ff14',
    ],
    'lunar-rays' => [
        '500' => '#E0E0E0',
        '600' => '#F5F5F5',
        '700' => '#FFFFFF',
        'gray-100' => '#ffffffff',
        'gray-200' => '#F0F0F0',
        'gray-300' => '#E0E0E0',
        'gray-400' => '#BDBDBD',
        'gray-700' => '#313136',
        'gray-800' => '#1a1a1e',
        'gray-900' => '#0C0C0F',
    ],
    'violet-night' => [
        '500' => '#a855f7', '600' => '#9333ea', '700' => '#7e22ce',
        'gray-100' => '#f3f4f6', 'gray-200' => '#e5e7eb', 'gray-300' => '#d1d5db', 'gray-400' => '#9ca3af',
        'gray-700' => '#374151', 'gray-800' => '#1C1C1C', 'gray-900' => '#050505',
    ],
    'autumn-breeze' => [
        '500' => '#f97316', // Warm Orange
        '600' => '#ea580c',
        '700' => '#c2410c',
        'gray-100' => '#F5F5DC', // Beige
        'gray-200' => '#EAE6D5',
        'gray-300' => '#D8D2C1',
        'gray-400' => '#C5BBAA',
        'gray-700' => '#4A463D',
        'gray-800' => '#2A2823', // Dark Brown
        'gray-900' => '#1A1814', // Very Dark Brown
    ],
    'jungle-heart' => [
        '500' => '#8D6E63', // Rich Brown
        '600' => '#795548',
        '700' => '#6D4C41',
        'gray-100' => '#E0F2F1', // Light Teal/Green
        'gray-200' => '#B2DFDB',
        'gray-300' => '#80CBC4',
        'gray-400' => '#4DB6AC',
        'gray-700' => '#26A69A',
        'gray-800' => '#142B2B', // Dark Teal
        'gray-900' => '#0C1A1A', // Very Dark Teal
    ],
    'ice-kingdom' => [
        '500' => '#38BDF8', // Light Blue (sky-400 from Tailwind)
        '600' => '#0EA5E9',
        '700' => '#0284C7',
        'gray-100' => '#F0F9FF', // Almost white, cool tone
        'gray-200' => '#E0F2FE',
        'gray-300' => '#BAE6FD',
        'gray-400' => '#7DD3FC',
        'gray-700' => '#1E3A8A', // Dark Blue
        'gray-800' => '#172554', // Darker Blue
        'gray-900' => '#111827', // Almost Black
    ],
    'willow-tree' => [
        '500' => '#5A704A', // Red
        '600' => '#728167',
        '700' => '#2e991bff',
        'gray-100' => '#9db68c', // Dark text
        'gray-200' => '#34533f',
        'gray-300' => '#728167',
        'gray-400' => '#bcc5b6',
        'gray-700' => '#E6FEC7', // Light panel background
        'gray-800' => '#f3f4f6', // Lighter panel background
        'gray-900' => '#5A704A', // White background
    ],
];

$current_palette = $palettes[$theme] ?? $palettes['dark-indigo'];

// Function to generate random box-shadows for stars
// Function to generate random box-shadows for stars
if (!function_exists('create_stars')) {
    function create_stars($count, $color = '#FFF') {
        $shadows = [];
        for ($i = 0; $i < $count; $i++) {
            // MODIFICA QUI: Aumentato il range per coprire schermi più larghi
            $x = rand(0, 4000);
            $y = rand(0, 2000);
            $shadows[] = "{$x}px {$y}px {$color}";
        }
        return implode(', ', $shadows);
    }
}
?>

@media (max-width: 640px) {
  header {
    margin-top: 40px; /* gestisce notch su iOS/Android */
  }
}



:root {
    --color-primary-500: <?php echo $current_palette['500']; ?>;
    --color-primary-600: <?php echo $current_palette['600']; ?>;
    --color-primary-700: <?php echo $current_palette['700']; ?>;

    --color-gray-100: <?php echo $current_palette['gray-100']; ?>;
    --color-gray-200: <?php echo $current_palette['gray-200']; ?>;
    --color-gray-300: <?php echo $current_palette['gray-300']; ?>;
    --color-gray-400: <?php echo $current_palette['gray-400']; ?>;
    --color-gray-700: <?php echo $current_palette['gray-700']; ?>;
    --color-gray-800: <?php echo $current_palette['gray-800']; ?>;
    --color-gray-900: <?php echo $current_palette['gray-900']; ?>;

    --color-success: #22c55e; /* Green */
    --color-danger: #ef4444; /* Red */
    --color-warning: #f59e0b; /* Amber */
    --color-neon-green: <?php echo $current_palette['neon-green'] ?? '#39ff14'; ?>;
}

/* Rimosso per fixare il bug delle animazioni. Le transizioni andrebbero applicate a classi specifiche. */

/* Nasconde la scrollbar della sidebar */
#sidebar > div:first-of-type {
    scrollbar-width: none; /* Per Firefox */
    -ms-overflow-style: none;  /* Per Internet Explorer e Edge */
}
#sidebar > div:first-of-type::-webkit-scrollbar {
    display: none; /* Per Chrome, Safari e Opera */
}

<?php if ($theme === 'dark-gold'): ?>

/* --- Golden Smoke & Light Effects (Revised Animation) --- */
@keyframes gold-smoke-flow {
    0% { transform: translate(0, 20%); }
    50% { transform: translate(10%, 0%); }
    100% { transform: translate(-10%, -20%); }
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: -50%;
    width: 200%;
    height: 200%;
    background: 
        radial-gradient(ellipse at 20% 80%, rgba(214, 158, 46, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 90%, rgba(183, 121, 31, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(214, 158, 46, 0.25) 0%, transparent 60%);
    z-index: -1;
    animation: gold-smoke-flow 50s ease-in-out infinite alternate;
}

body::after {
    content: '';
    position: fixed;
    top: -150px;
    right: -150px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(214, 158, 46, 0.25) 0%, rgba(214, 158, 46, 0) 60%);
    z-index: -1;
    border-radius: 50%;
}


/* --- Original Dark Gold Styles --- */
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap');
body {
    font-family: 'Lora', serif !important;
    position: relative; 
    overflow: hidden; 
}
#sidebar {
    background-image: repeating-linear-gradient(
      45deg,
      rgba(214, 158, 46, 0.05),
      rgba(214, 158, 46, 0.05) 1px,
      transparent 1px,
      transparent 10px
    );
}
.bg-gray-800 {
    border: 1px solid var(--color-primary-600);
}
a.flex.items-center:hover, button:hover {
    background-color: var(--color-gray-700);
    border-color: var(--color-primary-500);
}
<?php endif; ?>
<?php if ($theme === 'foggy-gray'): ?>

@keyframes fogLayer1 {
  0% { transform: translate(-10%, -10%); }
  25% { transform: translate(10%, -20%); }
  50% { transform: translate(20%, 20%); }
  75% { transform: translate(-10%, 20%); }
  100% { transform: translate(-10%, -10%); }
}

@keyframes fogLayer2 {
  0% { transform: translate(10%, 10%); }
  25% { transform: translate(-10%, 20%); }
  50% { transform: translate(-20%, -20%); }
  75% { transform: translate(10%, -20%); }
  100% { transform: translate(10%, 10%); }
}

body {
    background-color: #1f2937; /* var(--color-gray-800) */
    position: relative;
    overflow: hidden;
}

body::before, body::after {
    content: '';
    position: fixed;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    z-index: -1; /* Place behind all content */
}

/* Layer 1 - Slower, larger fog banks */
body::before {
    background:
        radial-gradient(ellipse at 20% 30%, rgba(229, 231, 235, 0.25) 0%, transparent 40%),
        radial-gradient(ellipse at 80% 60%, rgba(209, 213, 219, 0.2) 0%, transparent 50%);
    animation: fogLayer1 60s ease-in-out infinite;
}

/* Layer 2 - Faster, smaller wisps */
body::after {
    background:
        radial-gradient(ellipse at 50% 50%, rgba(243, 244, 246, 0.15) 0%, transparent 30%),
        radial-gradient(ellipse at 10% 80%, rgba(229, 231, 235, 0.2) 0%, transparent 40%),
        radial-gradient(ellipse at 90% 10%, rgba(209, 213, 219, 0.1) 0%, transparent 35%);
    animation: fogLayer2 45s ease-in-out infinite alternate;
}

#sidebar, .bg-gray-800 {
    background-color: rgba(31, 41, 55, 0.6) !important; /* var(--color-gray-800) with alpha */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    opacity: 0.85;
}

<?php endif; ?>

<?php if ($theme === 'crimson-white'): ?>

/* --- Smoke & Light Effects (Revised Animation) --- */
@keyframes smoke-flow {
    0% { transform: translate(0, 20%); }
    50% { transform: translate(10%, 0%); }
    100% { transform: translate(-10%, -20%); }
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: -50%;
    width: 200%;
    height: 200%;
    background: 
        radial-gradient(ellipse at 20% 80%, rgba(214, 46, 46, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 90%, rgba(183, 31, 31, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(214, 46, 46, 0.25) 0%, transparent 60%);
    z-index: -1;
    animation: smoke-flow 50s ease-in-out infinite alternate;
}

    <?php
$shadows_small_red = create_stars(700, '#DC2626');
$shadows_medium_red = create_stars(200, '#B91C1C');
?>

@keyframes animStar {
    from {
        transform: translateY(0px);
    }
    to {
        transform: translateY(-2000px);
    }
}

html::before, html::after {
    content: " ";
    position: fixed;
    top: 0;
    left: 0;
    width: 1px;
    height: 1px;
    background: transparent;
    animation-name: animStar;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    z-index: -1; /* Corretto! */
}

html::before {
    box-shadow: <?php echo $shadows_small_red; ?>;
    animation-duration: 50s;
}

html::after {
    width: 2px;
    height: 2px;
    box-shadow: <?php echo $shadows_medium_red; ?>;
    animation-duration: 100s;
}

@keyframes crimson-blush {
  0% { transform: translate(-10%, -10%); }
  25% { transform: translate(10%, -20%); }
  50% { transform: translate(20%, 20%); }
  75% { transform: translate(-10%, 20%); }
  100% { transform: translate(-10%, -10%); }
}

body {
    background-color: #120000; /* White */
    color: var(--color-gray-200); /* Dark Gray */
    position: relative;
    overflow: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background:
        radial-gradient(ellipse at 20% 30%, rgb(255 0 0) 0%, #e9000000 50%), radial-gradient(ellipse at 80% 60%, rgb(21 4 4) 0%, #000000 50%);
    z-index: -1;
    animation: crimson-blush 60s ease-in-out infinite;
}

#sidebar, .bg-gray-800 {
    background-color: #120000 !important; /* Semi-transparent light gray */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid var(--color-primary-500);
}

h1, h2, h3, h4, h5, h6 {
    color: var(--color-gray-900); /* Darkest gray for headings */
}

.bg-primary-600, a.bg-primary-600 {
    background-color: var(--color-primary-600);
    color: #fff !important;
}

.bg-primary-600:hover, a.bg-primary-600:hover {
    background-color: var(--color-primary-700);
}

<?php endif; ?>

<?php if ($theme === 'neon-dreams'): ?>

@keyframes neon-text-glow {
    from {
        text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px var(--color-primary-500), 0 0 20px var(--color-primary-500);
    }
    to {
        text-shadow: 0 0 6px #fff, 0 0 12px #fff, 0 0 18px var(--color-primary-500), 0 0 24px var(--color-primary-500);
    }
}

@keyframes neon-border-glow {
    from {
        box-shadow: 0 0 3px -1px var(--color-neon-green), 0 0 6px var(--color-neon-green), 0 0 10px var(--color-neon-green);
    }
    to {
        box-shadow: 0 0 5px -1px var(--color-neon-green), 0 0 10px var(--color-neon-green), 0 0 15px var(--color-neon-green);
    }
}


body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-200); /* Lighter gray for better readability */
    text-shadow: 0 0 1px rgba(224, 64, 251, 0.2); /* Very subtle glow for body text */
}

#sidebar, .bg-gray-800 {
    background-color: rgba(26, 10, 36, 0.85) !important; /* Made panels more opaque */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 2px solid var(--color-neon-green);
    animation: neon-border-glow 2s ease-in-out infinite alternate;
}

h1, h2, h3, h4, h5, h6 {
    color: #fff;
    animation: neon-text-glow 2s ease-in-out infinite alternate;
}

a.bg-primary-600, .bg-primary-600 {
    background-color: transparent;
    border: 2px solid var(--color-neon-green);
    box-shadow: none;
    color: var(--color-neon-green) !important;
    text-shadow: 0 0 4px var(--color-neon-green);
}

a.bg-grey700:hover, .bg-grey-600:hover {
    background-color: red;
    color: var(--color-gray-900) !important;
}

/* Generic hover for buttons and sidebar links */
a.flex.items-center:hover, button:hover {
    background-color: var(--color-neon-green) !important;
    color: var(--color-gray-900) !important;
    text-shadow: none;
    box-shadow: 0 0 10px var(--color-neon-green);
}

/* Override for .text-white on buttons */
.bg-primary-600.text-white:hover, a.bg-primary-600.text-white:hover {
    color: var(--color-gray-900) !important;
}


<?php endif; ?>

<?php if ($theme === 'lunar-rays'): ?>

<?php
$shadows_small_white = create_stars(700, '#FFF');
$shadows_medium_white = create_stars(200, '#FFF');
?>

@keyframes animStar {
    from {
        transform: translateY(0px);
    }
    to {
        transform: translateY(-2000px);
    }
}

html::before, html::after {
    content: " ";
    position: fixed;
    top: 0;
    left: 0;
    width: 1px;
    height: 1px;
    background: transparent;
    animation-name: animStar;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    z-index: -1; /* Corretto! */
}

html::before {
    box-shadow: <?php echo $shadows_small_white; ?>;
    animation-duration: 50s;
}

html::after {
    width: 2px;
    height: 2px;
    box-shadow: <?php echo $shadows_medium_white; ?>;
    animation-duration: 100s;
}   

@keyframes fogLayer1 {
  0% { transform: translate(-10%, -10%); }
  25% { transform: translate(10%, -20%); }
  50% { transform: translate(20%, 20%); }
  75% { transform: translate(-10%, 20%); }
  100% { transform: translate(-10%, -10%); }
}

@keyframes fogLayer2 {
  0% { transform: translate(10%, 10%); }
  25% { transform: translate(-10%, 20%); }
  50% { transform: translate(-20%, -20%); }
  75% { transform: translate(10%, -20%); }
  100% { transform: translate(10%, 10%); }
}

body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-200);
    position: relative;
    overflow: hidden;
}

/* Fog Layer */
body::before {
    content: '';
    position: fixed;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background:
        radial-gradient(ellipse at 50% 50%, rgba(243, 244, 246, 0.19) 0%, transparent 40%),
        radial-gradient(ellipse at 10% 80%, rgba(229, 231, 235, 0.14) 0%, transparent 50%);
    z-index: -2;
    animation: fogLayer2 55s ease-in-out infinite alternate;
}

/* Soft Volumetric Light Layer */
body::after {
    content: '';
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background:
        radial-gradient(ellipse 120% 100% at top right,
            rgba(255, 254, 238, 1) 0%,
            rgba(209, 209, 209, 0.27) 25%,
            transparent 50%
        );
    z-index: -1;
}


#sidebar, .bg-gray-800 {
    background-color: rgba(26, 26, 30, 0.75) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

h1, h2, h3, h4, h5, h6 {
    color: var(--color-gray-100);
    text-shadow: 0 0 7px rgb(255 255 255);
}
button.bg-primary-600{
    box-shadow: 0 0 5px 1px white;
    color: black;
}

button.bg-primary-600:hover{
    box-shadow: 0 0 10px 2px white;
    transition: 0.1s ease-in-out;
}

a.bg-primary-600, .bg-primary-600 {
    background-color: transparent;
    border: 1px solid var(--color-gray-300);
    color: var(--color-gray-900);
}

a.bg-primary-600:hover, .bg-primary-600:hover {
    background-color: var(--color-gray-100);
    color: var(--color-gray-900) !important;
}

/* Override for .text-white on buttons */
.bg-primary-600.text-white:hover, a.bg-primary-600.text-white:hover {
    color: var(--color-gray-900) !important;
}


<?php endif; ?>

<?php if ($theme === 'violet-night'): ?>

<?php
// Star generation code is unchanged
$shadows_small_white = create_stars(700, '#c98bffff');
$shadows_medium_white = create_stars(200, '#9900ffff');
?>

<?php /* Animations and star styles are unchanged */ ?>
@keyframes animStar { from { transform: translateY(0px); } to { transform: translateY(-2000px); } }
@keyframes fogLayer1 {   0% { transform: translate(-10%, -10%); }  25% { transform: translate(10%, -20%); }  50% { transform: translate(20%, 20%); }  75% { transform: translate(-10%, 20%); }  100% { transform: translate(-10%, -10%); } }
@keyframes fogLayer2 {   0% { transform: translate(10%, 10%); }  25% { transform: translate(-10%, 20%); }  50% { transform: translate(-20%, -20%); }  75% { transform: translate(10%, -20%); }  100% { transform: translate(10%, 10%); } }
html::before, html::after { content: " "; position: fixed; top: 0; left: 0; width: 1px; height: 1px; background: transparent; animation-name: animStar; animation-timing-function: linear; animation-iteration-count: infinite; z-index: -1; }
html::before { box-shadow: <?php echo $shadows_small_white; ?>; animation-duration: 50s; }
html::after { width: 2px; height: 2px; box-shadow: <?php echo $shadows_medium_white; ?>; animation-duration: 100s; }

body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-300); /* Testo base reso meno brillante */
    position: relative;
    overflow: hidden;
}

/* Background effects are unchanged */
body::before { content: ''; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(ellipse at 50% 50%, rgba(243, 244, 246, 0.10) 0%, transparent 40%), radial-gradient(ellipse at 10% 80%, rgba(229, 231, 235, 0.08) 0%, transparent 50%); z-index: -2; animation: fogLayer2 55s ease-in-out infinite alternate; }
body::after { content: ''; position: fixed; top: 0; right: 0; width: 100%; height: 100%; background: radial-gradient(ellipse 120% 100% at top right, rgba(168, 85, 247, 0.25) 0%, rgba(168, 85, 247, 0.1) 25%, transparent 50%); z-index: -1; }

#sidebar, .bg-gray-800 {
    background-color: #0e0c10bf !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(168, 85, 247, 0.2);
}

h1, h2, h3, h4, h5, h6 {
    color: #e6c0ff;
    text-shadow: 0 0 9px rgba(168, 85, 247, 0.6); /* Ombra del testo leggermente più forte */
}

/* Nuovo stile per l'hover della sidebar e altri link */
a.flex.items-center:hover, button:not(.bg-primary-600):hover {
    background-color: rgba(168, 85, 247, 0.15);
    color: #FFF;
}

/* Stile per i bottoni principali (viola) */
button.bg-primary-600, a.bg-primary-600 {
    background-color: var(--color-primary-600) !important;
    border: 1px solid var(--color-primary-500) !important;
    color: var(--color-gray-100) !important;
    box-shadow: 0 0 8px 0px var(--color-primary-500);
    transition: box-shadow 0.2s ease-in-out;
}

button.bg-primary-600:hover, a.bg-primary-600:hover {
    box-shadow: 0 0 15px 3px var(--color-primary-500);
}

<?php endif; ?>

<?php if ($theme === 'autumn-breeze'): ?>

/* --- Leaf Animation --- */
@keyframes fall-1 {
    0% { transform: translate(0, -10vh) rotate(-10deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translate(10vw, 110vh) rotate(180deg); opacity: 0; }
}
@keyframes fall-2 {
    0% { transform: translate(0, -10vh) rotate(20deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translate(-15vw, 110vh) rotate(-180deg); opacity: 0; }
}
@keyframes fall-3 {
    0% { transform: translate(0, -10vh) rotate(90deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translate(-5vw, 110vh) rotate(360deg); opacity: 0; }
}
@keyframes fall-4 {
    0% { transform: translate(0, -10vh) rotate(-90deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translate(5vw, 110vh) rotate(0deg); opacity: 0; }
}

/* Base style for all leaves */
body::before, body::after, html::before, html::after {
    content: '';
    position: fixed;
    top: 0;
    font-size: 24px;
    z-index: -1;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}

/* Individual leaves */
body::before {
    content: '🍂';
    left: 15vw;
    color: #D35400;
    animation-name: fall-1;
    animation-duration: 18s;
    animation-delay: -3s;
}
body::after {
    content: '🍁';
    left: 45vw;
    color: #C0392B;
    animation-name: fall-2;
    animation-duration: 22s;
    animation-delay: -7s;
}
html::before {
    content: '🍂';
    left: 65vw;
    color: #E67E22;
    animation-name: fall-3;
    animation-duration: 16s;
    animation-delay: -13s;
}
html::after {
    content: '🍁';
    left: 85vw;
    color: #D35400;
    animation-name: fall-4;
    animation-duration: 20s;
    animation-delay: -18s;
}


/* --- General Styles --- */
body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-100);
}

#sidebar, .bg-gray-800 {
    background-color: rgba(42, 40, 35, 0.8) !important; /* gray-800 with transparency */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(249, 115, 22, 0.2); /* primary-500 with transparency */
}

h1, h2, h3, h4, h5, h6 {
    color: var(--color-gray-100);
    text-shadow: 0 0 5px rgba(249, 115, 22, 0.4);
}

a.flex.items-center:hover, button:not(.bg-primary-600):hover {
    background-color: rgba(249, 115, 22, 0.1);
    color: #FFF;
}

button.bg-primary-600, a.bg-primary-600 {
    background-color: var(--color-primary-600) !important;
    border: 1px solid var(--color-primary-700) !important;
    color: var(--color-gray-100) !important;
    text-shadow: none;
    box-shadow: 0 2px 10px -2px var(--color-primary-500);
    transition: all 0.2s ease-in-out;
}

button.bg-primary-600:hover, a.bg-primary-600:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px -2px var(--color-primary-500);
}

<?php endif; ?>

<?php if ($theme === 'jungle-heart'): ?>

/* --- Smoke & Light Effects (Revised Animation) --- */
@keyframes smoke-flow {
    0% { transform: translate(0, 20%); }
    50% { transform: translate(10%, 0%); }
    100% { transform: translate(-10%, -20%); }
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 50%;
    width: 300%;
    height: 200%;
    background: 
        radial-gradient(ellipse at 20% 80%, rgba(214, 211, 46, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 90%, rgba(158, 183, 31, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(186, 214, 46, 0.26) 0%, transparent 60%);
    z-index: -1;
    animation: smoke-flow 50s ease-in-out infinite alternate;
}


/* --- Firefly Animation --- */
@keyframes firefly-1 {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.2; }
    25% { transform: translate(30px, -20px) scale(0.8); opacity: 1; }
    50% { transform: translate(10px, 20px) scale(1.2); opacity: 0.5; }
    75% { transform: translate(-20px, 10px) scale(1); opacity: 1; }
}
@keyframes firefly-2 {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
    25% { transform: translate(-25px, 15px) scale(1.1); opacity: 1; }
    50% { transform: translate(5px, -15px) scale(0.9); opacity: 0.4; }
    75% { transform: translate(15px, 10px) scale(1); opacity: 1; }
}
@keyframes firefly-3 {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
    25% { transform: translate(20px, 20px) scale(0.9); opacity: 1; }
    50% { transform: translate(-15px, -10px) scale(1.1); opacity: 0.6; }
    75% { transform: translate(10px, -15px) scale(1); opacity: 1; }
}
@keyframes firefly-4 {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.1; }
    25% { transform: translate(-10px, -15px) scale(1.2); opacity: 1; }
    50% { transform: translate(10px, 10px) scale(0.8); opacity: 0.2; }
    75% { transform: translate(5px, 5px) scale(1); opacity: 1; }
}


/* Base style for all fireflies */
body::before, body::after, html::before, html::after {
    content: '•';
    position: fixed;
    font-size: 16px;
    color: #F0E68C; /* Khaki */
    text-shadow: 0 0 12px #F0E68C, 0 0 18px #F0E68C, 0 0 25px #F0E68C;
    z-index: -1;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}

/* Individual fireflies */
body::before { top: 30vh; left: 20vw; animation-name: firefly-1; animation-duration: 10s; }
body::after { top: 60vh; left: 80vw; animation-name: firefly-2; animation-duration: 8s; animation-delay: -3s; }
html::before { top: 80vh; left: 10vw; animation-name: firefly-3; animation-duration: 12s; animation-delay: -7s; }
html::after { top: 10vh; left: 90vw; animation-name: firefly-4; animation-duration: 9s; animation-delay: -5s; }


/* --- General Styles (Unchanged) --- */
body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-100);
    position: relative;
    overflow: hidden;
}

#sidebar, .bg-gray-800 {
    background-color: rgba(20, 43, 43, 0.75) !important;
    background-image: linear-gradient(to bottom, rgba(20, 43, 43, 0.8), rgba(42, 40, 35, 0.9)) !important;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(141, 110, 99, 0.2);
}

h1, h2, h3, h4, h5, h6 {
    color: var(--color-gray-100);
    text-shadow: 0 0 6px rgba(141, 110, 99, 0.3);
}

a.flex.items-center:hover, button:not(.bg-primary-600):hover {
    background-color: rgba(141, 110, 99, 0.1);
    color: #FFF;
}

button.bg-primary-600, a.bg-primary-600 {
    background-color: var(--color-primary-600) !important;
    border: 1px solid var(--color-primary-700) !important;
    color: var(--color-gray-100) !important;
    font-weight: 600;
    text-shadow: none;
    transition: all 0.2s ease-in-out;
}

button.bg-primary-600:hover, a.bg-primary-600:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px -2px var(--color-primary-500);
}

<?php endif; ?>

<?php if ($theme === 'ice-kingdom'): ?>

/* --- Smoke & Light Effects (Revised Animation) --- */
@keyframes smoke-flow {
    0% { transform: translate(0, 20%); }
    50% { transform: translate(10%, 0%); }
    100% { transform: translate(-10%, -20%); }
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: -50%;
    width: 200%;
    height: 200%;
    background: 
        radial-gradient(ellipse at 20% 80%, rgba(46, 130, 214, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 90%, rgba(31, 132, 183, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(46, 194, 214, 0.25) 0%, transparent 60%);
    z-index: -1;
    animation: smoke-flow 50s ease-in-out infinite alternate;
}

<?php
// We can reuse the create_stars function for snowflakes.
$snowflakes_small = create_stars(500, 'rgba(255, 255, 255, 0.8)');
$snowflakes_medium = create_stars(150, 'rgba(255, 255, 255, 0.6)');
?>

@keyframes snow-fall {
    from { transform: translateY(0px); }
    to { transform: translateY(2000px); }
}

/* --- Animated Background: Falling Snow --- */
html::before, html::after {
    content: "•";
    position: fixed;
    top: -2000px; /* Start high up */
    left: 0;
    width: 2px;
    height: 2px;
    background: transparent;
    animation-name: snow-fall;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    z-index: -1; /* Correzione: In front of the static light */
}

html::before {
    box-shadow: <?php echo $snowflakes_small; ?>;
    animation-duration: 20s;
    border-radius: 50%;
}

html::after {
    width: 4px;
    height: 4px;
    box-shadow: <?php echo $snowflakes_medium; ?>;
    animation-duration: 30s;
    animation-delay: -5s;
    border-radius: 50%;
}

/* --- Static Effect: White Light --- */
body::after {
    content: '';
    position: fixed;
    top: -850px;
    right: -800px;
    width: 1500px;
    height: 1500px;
    background: radial-gradient(circle, rgb(255 255 255 / 15%) 0%, rgba(224, 242, 254, 0) 60%);
    z-index: -1; /* Behind the snow */
    border-radius: 50%;
}


/* --- General Styles --- */
body {
    background-color: var(--color-gray-900);
    color: var(--color-gray-100);
}

#sidebar, .bg-gray-800 {
    background: linear-gradient(45deg, black, #0000000f);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(56, 189, 248, 0.2); /* primary-500 with transparency */
}

h1, h2, h3, h4, h5, h6 {
    color: var(--color-gray-100);
    text-shadow: 0 0 8px rgba(125, 211, 252, 0.5); /* primary-400 with transparency */
}

a.flex.items-center:hover, button:not(.bg-primary-600):hover {
    background-color: rgba(56, 189, 248, 0.1);
    color: #FFF;
}

button.bg-primary-600, a.bg-primary-600 {
    background-color: var(--color-primary-600) !important;
    border: 1px solid var(--color-primary-500) !important;
    color: var(--color-gray-100) !important;
    font-weight: 600;
    text-shadow: none;
    box-shadow: 0 2px 10px -2px var(--color-primary-400);
    transition: all 0.2s ease-in-out;
}

button.bg-primary-600:hover, a.bg-primary-600:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px -2px var(--color-primary-400);
}

<?php endif; ?>

<?php if ($theme === 'willow-tree'): ?>

/* --- Smoke & Light Effects (Revised Animation) --- */
@keyframes smoke-flow {
    0% { transform: translate(0, 20%); }
    50% { transform: translate(10%, 0%); }
    100% { transform: translate(-10%, -20%); }
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: -50%;
    width: 200%;
    height: 200%;
    background: 
        radial-gradient(ellipse at 20% 80%, rgba(74, 214, 46, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 70% 90%, rgba(59, 183, 31, 0.2) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 50%, rgba(66, 214, 46, 0.25) 0%, transparent 60%);
    z-index: -1;
    animation: smoke-flow 50s ease-in-out infinite alternate;
}

    <?php
$shadows_small_red = create_stars(700, '#72dc26ff');
$shadows_medium_red = create_stars(200, '#1cb93eff');
?>

@keyframes animStar {
    from {
        transform: translateY(0px);
    }
    to {
        transform: translateY(-2000px);
    }
}

html::before, html::after {
    content: " ";
    position: fixed;
    top: 0;
    left: 0;
    width: 1px;
    height: 1px;
    background: transparent;
    animation-name: animStar;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    z-index: -1; /* Corretto! */
}

html::before {
    box-shadow: <?php echo $shadows_small_red; ?>;
    animation-duration: 50s;
}

html::after {
    width: 2px;
    height: 2px;
    box-shadow: <?php echo $shadows_medium_red; ?>;
    animation-duration: 100s;
}

@keyframes crimson-blush {
  0% { transform: translate(-10%, -10%); }
  25% { transform: translate(10%, -20%); }
  50% { transform: translate(20%, 20%); }
  75% { transform: translate(-10%, 20%); }
  100% { transform: translate(-10%, -10%); }
}

body {
    background-color: #011200ff; /* White */
    color: var(--color-gray-200); /* Dark Gray */
    position: relative;
    overflow: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: radial-gradient(ellipse at 20% 30%, #161a13 0%, #6f8c50 60%), radial-gradient(ellipse at 80% 60%, rgb(21 4 4) 0%, #45583D 50%);
    animation: crimson-blush 60s ease-in-out infinite;
}

#sidebar, .bg-gray-800 {
    background-color: #011202 !important; /* Semi-transparent light gray */
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid var(--color-primary-500);
    box-shadow: 0 0 15px 2px #3549368f !important;
}

h1, h2, h3, h4, h5, h6, td {
    color: var(--color-gray-100); /* Darkest gray for headings */
}

td {
    color: #c9d6c0;
}

.bg-green-600 {
    --tw-bg-opacity: 1;
    background-color: var(--color-gray-700) !important;
}

.disabled\:bg-gray-600:disabled {
    --tw-bg-opacity: 1;
    background-color: var(--color-primary-600) !important;
}

.bg-gray-600, .bg-primary-600{
    background: #728167 !important;
    border: 1px solid #596352ff !important;
        box-shadow: 0 0 15px 2px #3549368f !important;
}

.bg-gray-600:hover, .bg-primary-600:hover{
    background: #98ac8bff !important;
    border: 1px solid #596352ff !important;
        box-shadow: 0 0 15px 2px #3549368f !important;
}

.bg-primary-600, a.bg-primary-600 {
    background-color: #E6FEC7;
    color: #fff !important;
}

a.bg-gray-600, button.bg-gray-600 {
    color: #fff !important;
}

a.bg-gray-700:hover, button.bg-gray-700:hover {
    background: #9dad87ff !important;
}

.bg-primary-600:hover, a.bg-primary-600:hover {
    background-color: #E6FEC7;
}

<?php endif; ?>