<?php
session_start();
require_once 'includes/functions.php';
$lang_code = get_language();
$l = include "lang/{$lang_code}.php";

// Parameter aus URL holen
$text = $_GET['text'] ?? '';
$url = $_GET['url'] ?? '';
$error_msg = $_GET['error'] ?? '';
$last_instance = $_GET['instance'] ?? '';

// Text-Logik: Priorisiere 'text' (falls vom Redirect kommt), sonst kombiniere text+url
if (!empty($_GET['text']) && empty($_GET['url'])) {
    $full_text = $_GET['text'];
} else {
    $full_text = trim($text . ' ' . $url);
}

$n1 = rand(2, 9); $n2 = rand(2, 9);
$_SESSION['captcha_result'] = $n1 + $n2;
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primäre SEO Tags -->
    <title><?= $l['title'] ?> | RonDevHub Tools</title>
    <meta name="description" content="Ein sicheres, leichtgewichtiges Tool zum Teilen von Inhalten auf Mastodon. Mit Instanz-Verifizierung und Datenschutz-Fokus.">
    <meta name="keywords" content="Mastodon, Share, Fediverse, Tool, PHP, Open Source, Privacy">
    <meta name="author" content="Ronny (RonDevHub)">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook / Mastodon (Vorschau-Viereck) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] ?><?= $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:title" content="<?= $l['title'] ?> - Sicher im Fediverse teilen">
    <meta property="og:description" content="Teile Texte und Links ganz einfach mit deiner Mastodon-Instanz. Schnell, sicher und ohne Tracking.">
    <meta property="og:image" content="https://<?= $_SERVER['HTTP_HOST'] ?>/assets/android-chrome-512x512.png">

    <!-- Twitter / X Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $l['title'] ?>">
    <meta name="twitter:description" content="Das minimalistische Mastodon Share-Tool im Glassmorphism-Look.">

    <!-- Favicon & Themes -->
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <meta name="theme-color" content="#1e1b4b">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/style.css">
    
    <!-- Verknüpfung für alternative Sprachen (SEO Best Practice) -->
    <link rel="alternate" href="?lang=de" hreflang="de">
    <link rel="alternate" href="?lang=en" hreflang="en">
</head>
<body>
    <div id="toast" class="toast"></div>

    <div class="glass">
        <h2><img src="assets/apple-touch-icon.png" height="20"> <?= $l['title'] ?></h2>
        
        <form action="redirect.php" method="POST" id="share-form">
            <label><?= $l['preview'] ?></label>
            <textarea id="share-text" name="text" rows="3"><?= sanitize($full_text) ?></textarea>
            <label><?= $l['your_instance'] ?></label>
            <input type="text" name="instance" id="instance" placeholder="<?= $l['placeholder'] ?>" required>
            <button type="button" id="forget-btn" class="hidden" style="background:rgba(220,38,38,0.3); margin-bottom:10px;"><?= $l['forget'] ?></button>
            
            <div style="margin: 15px 0;">
                <label><?= sprintf($l['captcha_q'], $n1, $n2) ?></label>
                <input type="number" name="captcha" required>
            </div>
            
            <input type="text" name="real_name" class="hidden"> <!-- Honeypot -->
            <button type="submit"><?= $l['btn_share'] ?></button>
        </form>

        <hr style="border:0; border-top:1px solid var(--border); margin: 25px 0;">

        <h3><?= $l['gen_title'] ?></h3>
        <button id="gen-btn" style="background:rgba(255,255,255,0.1)"><?= $l['gen_open'] ?></button>
        
        <div id="generator-fields" class="hidden" style="margin-top:20px;">
            <div class="copy-group">
                <input type="text" id="res-url" readonly>
                <button type="button" class="copy-btn" onclick="copyToClipboard('res-url', '<?= $l['copy_success'] ?>')">URL</button>
            </div>
            <div class="copy-group">
                <input type="text" id="res-html" readonly>
                <button type="button" class="copy-btn" onclick="copyToClipboard('res-html', '<?= $l['copy_success'] ?>')">HTML</button>
            </div>
            <div class="copy-group">
                <input type="text" id="res-md" readonly>
                <button type="button" class="copy-btn" onclick="copyToClipboard('res-md', '<?= $l['copy_success'] ?>')">MD</button>
            </div>
        </div>

        <hr style="border:0; border-top:1px solid var(--border); margin: 25px 0;">

        <div class="footer">
            <?= $l['created_by'] ?> <a href="https://commitcloud.net/RonDevHub/mastodon-share" target="_blank">RonDevHub</a>
        </div>

    </div>

    <script src="assets/script.js"></script>
    <script>
        <?php if(isset($_GET['error'])): ?>
            showToast("<?= sanitize($_GET['error']) ?>");
        <?php endif; ?>
    </script>
</body>
</html>