<?php
/**
 * order_process.php — обробка замовлення.
 * Зберігає замовлення у storage/orders.ser і повертає JSON.
 */
require_once 'auth.php'; // session_start() вже в auth.php

header('Content-Type: application/json; charset=utf-8');

echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed', $_SERVER['REQUEST_METHOD']]);
    exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['items'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Порожній кошик']);
    exit;
}

$customer = $data['customer'] ?? [];
if (empty($customer['name']) || empty($customer['phone'])) {
    echo json_encode(['success' => false, 'message' => "Вкажіть ім'я та телефон"]);
    exit;
}

// Забезпечуємо директорію
$dir = __DIR__ . '/storage';
if (!is_dir($dir)) mkdir($dir, 0777, true);

$ordersFile = $dir . '/orders.ser';
$orders     = file_exists($ordersFile) ? unserialize(file_get_contents($ordersFile)) : [];

$orderId  = count($orders) + 1001;
$newOrder = [
    'id'         => $orderId,
    'customer'   => [
        'name'    => htmlspecialchars($customer['name']    ?? ''),
        'phone'   => htmlspecialchars($customer['phone']   ?? ''),
        'email'   => htmlspecialchars($customer['email']   ?? ''),
        'address' => htmlspecialchars($customer['address'] ?? ''),
        'comment' => htmlspecialchars($customer['comment'] ?? ''),
    ],
    'items'    => array_map(fn($i) => [
        'id'    => htmlspecialchars($i['id']   ?? ''),
        'name'  => htmlspecialchars($i['name'] ?? ''),
        'price' => (float)($i['price'] ?? 0),
        'qty'   => (int)($i['qty']     ?? 1),
    ], $data['items']),
    'total'    => (float)($data['total']    ?? 0),
    'delivery' => (float)($data['delivery'] ?? 0),
    'status'   => 'new',
    'date'     => date('Y-m-d H:i:s'),
];

$orders[] = $newOrder;
file_put_contents($ordersFile, serialize($orders));

echo json_encode(['success' => true, 'order_id' => $orderId, 'message' => 'Замовлення прийнято!']);
