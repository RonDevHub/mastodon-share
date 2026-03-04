<?php
session_start();
require_once 'includes/functions.php';
require_once 'includes/validator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($_POST['username'])) die('No.');

if ((int)$_POST['captcha'] !== $_SESSION['captcha_result']) die('Captcha falsch.');

$instance = filter_input(INPUT_POST, 'instance', FILTER_SANITIZE_URL);
$text = urlencode($_POST['text']);

if (verify_instance($instance)) {
    // Im Browser-Gedächtnis wird via JS gespeichert, hier machen wir den Redirect
    header("Location: https://{$instance}/share?text={$text}");
    exit;
} else {
    echo "Fehler: Instanz nicht verifiziert.";
}