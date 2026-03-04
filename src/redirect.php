<?php
session_start();
require_once 'includes/functions.php';
require_once 'includes/validator.php';

$lang_code = get_language();
$l = include "lang/{$lang_code}.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($_POST['real_name'])) {
    header("Location: index.php"); exit;
}

$current_text = $_POST['text'] ?? '';
$error_redirect = "index.php?text=" . urlencode($current_text);

if (!isset($_POST['captcha']) || (int)$_POST['captcha'] !== $_SESSION['captcha_result']) {
    header("Location: " . $error_redirect . "&error=" . urlencode($l['err_captcha']));
    exit;
}

$instance = filter_input(INPUT_POST, 'instance', FILTER_SANITIZE_URL);
$text_to_share = urlencode($current_text);

if (verify_instance($instance)) {
    header("Location: https://{$instance}/share?text={$text_to_share}");
    exit;
} else {
    header("Location: " . $error_redirect . "&error=" . urlencode($l['err_instance']));
    exit;
}