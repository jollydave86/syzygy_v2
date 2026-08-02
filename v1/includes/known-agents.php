<?php

function track_visit_in_known_agents() {
    if (!function_exists('curl_init')) {
        return;
    }

    $curl = curl_init('https://api.knownagents.com/visits');

    if ($curl === false) {
        return;
    }

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer baeb2f83-e459-4840-be67-173094dc201a',
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'request_path' => $_SERVER['REQUEST_URI'] ?? '/',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'request_headers' => function_exists('getallheaders') ? getallheaders() : [],
            'response_status_code' => http_response_code() ?: 200,
            'response_headers' => headers_list(),
        ], JSON_UNESCAPED_SLASHES),
        CURLOPT_NOSIGNAL => true,
        CURLOPT_TIMEOUT_MS => 50,
        CURLOPT_RETURNTRANSFER => true,
    ]);

    @curl_exec($curl);
    curl_close($curl);
}

register_shutdown_function('track_visit_in_known_agents');
