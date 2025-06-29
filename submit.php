<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $offer_id = $_POST['offer_id'] ?? 'z1340'; // пусть будет метка потока или саб

    // Эти переменные подтягиваются из Render Environment
    $api_key = getenv('API_KEY');
    $stream_code = getenv('STREAM_CODE');

    $payload = [
        "stream_code" => $stream_code,
        "client" => [
            "name" => $name,
            "phone" => $phone
        ],
        "sub1" => $offer_id,
        "sub2" => $_SERVER['HTTP_REFERER'] ?? '',
        "sub3" => $_SERVER['HTTP_USER_AGENT'] ?? '',
        "sub4" => $_SERVER['REMOTE_ADDR'] ?? '',
        "sub5" => ''
    ];

    $ch = curl_init("https://api.terraleads.com/v2/order");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err = curl_error($ch);
    curl_close($ch);

    if ($http_code === 200 && $response) {
        $decoded = json_decode($response, true);
        if ($decoded && $decoded['status'] === 200) {
            // ✅ Успешная отправка — редирект на страницу благодарности
            header('Location: success.html');
            exit;
        } else {
            echo "Ошибка: " . json_encode($decoded);
        }
    } else {
        echo "Ошибка CURL: " . $curl_err;
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}
?>
