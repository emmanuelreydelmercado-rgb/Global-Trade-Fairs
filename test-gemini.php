<?php

// List available models
$apiKey = 'AIzaSyD4okNGo5UHRsV2lUTn2f4EVs-HQ_V8Nzg';
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "Available Models:\n";
    foreach ($data['models'] as $model) {
        echo "- " . $model['name'] . "\n";
        if (isset($model['supportedGenerationMethods'])) {
            echo "  Methods: " . implode(', ', $model['supportedGenerationMethods']) . "\n";
        }
    }
} else {
    echo "Error Response: {$response}\n";
}
