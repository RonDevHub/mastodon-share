<?php
session_start();
require_once 'includes/functions.php';
$lang_code = get_language();
$l = include "lang/{$lang_code}.php";

$text = $_GET['text'] ?? '';
$url = $_GET['url'] ?? '';

if (!empty($text) && !empty($url)) {
    $full_text = trim($text . ' ' . $url);
} else {
    $full_text = $text ?: $url; 
}

$n1 = rand(2, 9); $n2 = rand(2, 9);
$_SESSION['captcha_result'] = $n1 + $n2;
?>
<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $l['title'] ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div id="toast" class="toast"></div>

    <div class="glass">
        <h2><?= $l['title'] ?></h2>
        
        <form action="redirect.php" method="POST" id="share-form">
            <label><?= $l['preview'] ?></label>
            <textarea id="share-text" name="text" rows="3"><?= sanitize($full_text) ?></textarea>
            
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
        <button id="gen-btn" style="background:rgba(255,255,255,0.1)"><?= $l['gen_title'] ?> öffnen</button>
        
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
            Erstellt von <a href="https://commitcloud.net/RonDevHub/mastodon-share" target="_blank">RonDevHub</a>
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