<?php
function analyze_prescription_image($imagePath) {
    $apiKey = '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';
    $model = 'meta-llama/llama-4-scout-17b-16e-instruct';
    $url = 'https://api.groq.com/openai/v1/chat/completions';

    $img = base64_encode(file_get_contents($imagePath));
    $dataUrl = "data:image/jpeg;base64,$img";

    $prompt = "You are a professional Sri Lankan pharmacist in Gampaha. Read this prescription and return ONLY valid JSON with these exact keys. No markdown, no extra text:

{
  \"doctor_name\": \"string\",
  \"patient_name\": \"string\",
  \"date\": \"2025-11-10\",
  \"age\": \"string\",
  \"gender\": \"string\",
  \"drug_names\": [\"medicine with dosage and duration\"],
  \"clinical_description\": [\"diagnosis line 1\", \"diagnosis line 2\"]
}

Return pure JSON only.";

    $payload = [
        "model" => $model,
        "messages" => [[
            "role" => "user",
            "content" => [
                ["type" => "text", "text" => $prompt],
                ["type" => "image_url", "image_url" => ["url" => $dataUrl]]
            ]
        ]],
        "temperature" => 0,
        "max_tokens" => 1000,
        "response_format" => ["type" => "json_object"]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $apiKey", "Content-Type: application/json"],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 40
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return ['error' => "HTTP $httpCode: " . json_decode($response, true)['error']['message'] ?? $response];
    }

    $json = json_decode($response, true);
    $content = $json['choices'][0]['message']['content'] ?? '';
    $content = trim($content, "` \n");
    if (strpos($content, '{') !== false) {
        $content = substr($content, strpos($content, '{'));
        $content = substr($content, 0, strrpos($content, '}') + 1);
    }

    $parsed = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON failed', 'raw' => $content];
    }

    return [
        'success' => true,
        'data' => $parsed,
        'tokens' => $json['usage']['total_tokens'] ?? 0
    ];
}
?>