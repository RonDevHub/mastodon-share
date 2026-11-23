<?php
function get_language() {
    if (isset($_GET['lang'])) return $_GET['lang'] === 'de' ? 'de' : 'en';
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
    return ($lang === 'de') ? 'de' : 'en';
}

function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function get_captcha_config() {
    return [
        'type' => strtolower(trim(getenv('CAPTCHA_TYPE') ?: 'math')),
        'turnstile_site_key' => trim(getenv('TURNSTILE_SITE_KEY') ?: ''),
        'turnstile_secret_key' => trim(getenv('TURNSTILE_SECRET_KEY') ?: ''),
        'cap_url' => rtrim(trim(getenv('CAP_URL') ?: ''), '/'),
        'cap_site_key' => trim(getenv('CAP_SITE_KEY') ?: ''),
        'cap_secret_key' => trim(getenv('CAP_SECRET_KEY') ?: ''),
        'header_code' => getenv('HEADER_CODE') ?: '',
    ];
}

function captcha_config_errors($config) {
    $errors = [];

    if (!in_array($config['type'], ['math', 'turnstile', 'cap'], true)) {
        return ['CAPTCHA_TYPE must be math, turnstile, or cap'];
    }

    if ($config['type'] === 'turnstile') {
        if ($config['turnstile_site_key'] === '') $errors[] = 'TURNSTILE_SITE_KEY';
        if ($config['turnstile_secret_key'] === '') $errors[] = 'TURNSTILE_SECRET_KEY';
    }

    if ($config['type'] === 'cap') {
        if ($config['cap_url'] === '') {
            $errors[] = 'CAP_URL';
        } else {
            $url = parse_url($config['cap_url']);
            if ($url === false || !isset($url['scheme'], $url['host']) ||
                !in_array(strtolower($url['scheme']), ['http', 'https'], true) ||
                isset($url['query']) || isset($url['fragment']) || isset($url['user']) || isset($url['pass'])) {
                $errors[] = 'CAP_URL must be a base HTTP(S) URL without credentials, query, or fragment';
            }
        }
        if ($config['cap_site_key'] === '') $errors[] = 'CAP_SITE_KEY';
        if ($config['cap_secret_key'] === '') $errors[] = 'CAP_SECRET_KEY';
    }

    return $errors;
}

function ensure_captcha_config_is_valid($config) {
    $errors = captcha_config_errors($config);
    if ($errors) {
        http_response_code(500);
        exit('Invalid CAPTCHA configuration: ' . implode(', ', $errors));
    }
}