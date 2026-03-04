<?php
session_start();
require_once 'includes/functions.php';
$lang_code = get_language();
$l = include "lang/{$lang_code}.php";

$text = $_GET['text'] ?? '';
$url = $_GET['url'] ?? '';
$full_text = trim($text . ' ' . $url);

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
    <div class="glass">
        <h2><?= $l['title'] ?></h2>
        
        <form action="redirect.php" method="POST">
            <label><?= $l['preview'] ?></label>
            <textarea id="share-text" name="text" rows="3"><?= sanitize($full_text) ?></textarea>
            
            <input type="text" name="instance" id="instance" placeholder="<?= $l['placeholder'] ?>" required>
            <button type="button" id="forget-btn" style="display:none; margin-bottom:10px; background:rgba(255,0,0,0.2)"><?= $l['forget'] ?></button>
            
            <p><?= sprintf($l['captcha_q'], $n1, $n2) ?></p>
            <input type="number" name="captcha" required>
            
            <!-- Honeypot -->
            <input type="text" name="username" style="display:none">
            
            <button type="submit"><?= $l['btn_share'] ?></button>
        </form>

        <div class="small-text">
            <h3><?= $l['gen_title'] ?></h3>
            <button id="gen-btn" style="padding:5px; font-size:12px;">Link generieren</button>
            <input type="text" id="gen-result" readonly>
        </div>
    </div>
    <script src="assets/script.js"></script>
</body>
</html>