<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $offer_id = $_POST['offer_id'] ?? '';

    $apiKey = 'f16d39f7-4f1d-42be-a146-06b4ea93c5c0';
    
    $data = [
        'stream_code' => 'znqrb',
        'client' => [
            'name' => $name,
            'phone' => $phone
        ],
        'sub1' => '',
        'sub2' => '',
        'sub3' => '',
        'sub4' => '',
        'sub5' => '',
    ];

    $ch = curl_init('https://api.terraleads.com/v2/order');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'response' => json_decode($response, true)
    ]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
