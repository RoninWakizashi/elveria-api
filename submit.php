<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $offer_id = $_POST['offer_id'] ?? '';

    $apikey = getenv('API_KEY');
    $stream_code = getenv('STREAM_CODE');

    $data = [
        "stream_code" => $stream_code,
        "client" => [
            "name" => $name,
            "phone" => $phone
        ],
        "sub1" => $offer_id, // <-- Правильно: сюда offer_id
        "sub2" => "",
        "sub3" => "",
        "sub4" => "",
        "sub5" => ""
    ];

    $ch = curl_init('https://api.terraleads.com/v2/order');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apikey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode([
            "status" => 0,
            "error" => curl_error($ch)
        ]);
        curl_close($ch);
        exit;
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    header('Content-Type: application/json');
    echo json_encode([
        "status" => $status,
        "response" => json_decode($response, true)
    ]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}
?>
