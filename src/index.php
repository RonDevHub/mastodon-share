<?php
session_start();
require_once 'includes/functions.php';
$lang = include 'includes/language_detect.php'; // Kleine Hilfsdatei zur Erkennung

// GET Parameter sicher holen
$text = filter_input(INPUT_GET, 'text', FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL) ?: '';

include 'templates/header.php';
?>

<div class="glass-card">
    <h2><?= $lang['title'] ?></h2>
    
    <div class="preview-area">
        <textarea id="share-text" class="glass-input"><?= $text . ' ' . $url ?></textarea>
    </div>

    <form action="redirect.php" method="POST">
        <input type="hidden" name="final_text" id="final_text">
        <div id="instance-container">
            <input type="text" name="instance" id="instance-input" placeholder="<?= $lang['instance_label'] ?>" required>
            <button type="button" id="forget-btn" style="display:none;"><?= $lang['forget'] ?></button>
        </div>
        
        <?php $n1 = rand(1,10); $n2 = rand(1,10); $_SESSION['captcha'] = $n1 + $n2; ?>
        <p><?= sprintf($lang['captcha_label'], $n1, $n2) ?></p>
        <input type="number" name="captcha" required>
        
        <input type="text" name="hp_field" style="display:none;">
        
        <button type="submit"><?= $lang['share_button'] ?></button>
    </form>
    
    <hr>
    <h3><?= $lang['generator_title'] ?></h3>
    </div>

<script src="assets/script.js"></script>
<?php include 'templates/footer.php'; ?>