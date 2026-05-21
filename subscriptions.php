<?php

require_once 'subscriptions_functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$jsonData  = file_get_contents('php://input');
$inputData = json_decode($jsonData, true);

if (!$inputData) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Помилка передачі даних.']);
    exit;
}

if (empty($inputData['name']) || empty($inputData['email'])) {
    echo json_encode(['success' => false, "message" => "Сервер: Ім'я та Email обов'язкові!"]);
    exit;
}

if (subscriptionExists($inputData['email'])) {
    echo json_encode(['success' => false, 'message' => 'Цей email вже зареєстровано.']);
    exit;
}

if (addSubscription($inputData)) {
    echo json_encode(['success' => true, 'message' => 'Дані успішно збережено на сервері!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Помилка збереження. Перевірте дані.']);
}