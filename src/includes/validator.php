<?php
function verify_instance($instance) {
    $instance = preg_replace('#^https?://#', '', rtrim($instance, '/'));
    if (!preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $instance)) return false;

    $url = "https://{$instance}/api/v1/instance";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MastodonShareValidator/1.0');
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $data = json_decode($response, true);
        return isset($data['uri']) || isset($data['domain']);
    }
    return false;
}

function verify_captcha($config, $token) {
    if (!is_string($token) || $token === '') {
        return false;
    }

    if ($config['type'] === 'turnstile') {
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $config['turnstile_secret_key'],
            'response' => $token,
        ];
    } elseif ($config['type'] === 'cap') {
        $url = $config['cap_url'] . '/' . rawurlencode($config['cap_site_key']) . '/siteverify';
        $data = [
            'secret' => $config['cap_secret_key'],
            'response' => $token,
        ];
    } else {
        return false;
    }

    if ($config['type'] === 'cap') {
        $post_fields = json_encode($data);
        $content_type = 'application/json';
        if ($post_fields === false) {
            error_log('CAPTCHA verification request could not be encoded as JSON.');
            return false;
        }
    } else {
        $post_fields = http_build_query($data);
        $content_type = 'application/x-www-form-urlencoded';
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => ['Content-Type: ' . $content_type],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($response === false || $curl_error !== '') {
        error_log('CAPTCHA verification request failed: ' . ($curl_error ?: 'no response'));
        return false;
    }

    $result = json_decode($response, true);
    if ($http_code !== 200 || !is_array($result) || !array_key_exists('success', $result)) {
        error_log('CAPTCHA verification returned an invalid response (HTTP ' . $http_code . ').');
        return false;
    }

    return $result['success'] === true;
}