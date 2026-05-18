<?php
/**
 * register_process.php — Серверна обробка реєстрації.
 * Отримує JSON: { username, email, password, display_name }
 * Повертає JSON: { success, message }
 */
require_once 'auth.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

if (
    !isset($data['username']) ||
    !isset($data['email'])    ||
    !isset($data['password'])
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Заповніть усі обов\'язкові поля.']);
    exit;
}

$username    = trim($data['username']);
$email       = trim($data['email']);
$password    = $data['password'];
$displayName = trim($data['display_name'] ?? '');

$result = register($username, $email, $password, $displayName);

if ($result['success']) {
    // Автоматичний вхід після реєстрації
    login($username, $password);
    echo json_encode([
        'success'  => true,
        'message'  => $result['message'],
        'redirect' => 'index.php'
    ]);
} else {
    http_response_code(422);
    echo json_encode($result);
}
?>
