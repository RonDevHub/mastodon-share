<?php
function get_language() {
    if (isset($_GET['lang'])) return $_GET['lang'] === 'de' ? 'de' : 'en';
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
    return ($lang === 'de') ? 'de' : 'en';
}

function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}