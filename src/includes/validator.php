<?php
function verify_instance($instance) {
    $instance = preg_replace('#^https?://#', '', rtrim($instance, '/'));
    if (!preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $instance)) return false;

    $url = "https://{$instance}/api/v1/instance";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Schneller Timeout für den Laptop
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