<?php
/**
 * login_process.php — Серверна обробка авторизації.
 * Отримує JSON: { username, password }
 * Повертає JSON: { success, message, redirect }
 */
require_once 'auth.php';

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Введіть логін та пароль.']);
    exit;
}

$username = trim($data['username']);
$password = $data['password'];

if (login($username, $password)) {
    // Адмін іде на admin.php, решта — на index.php
    $redirect = isAdmin() ? 'admin.php' : 'index.php';
    echo json_encode([
        'success'  => true,
        'message'  => 'Вхід успішний! Перенаправлення...',
        'redirect' => $redirect,
        'is_admin' => isAdmin(),
        'display_name' => currentUserName(),
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Невірний логін або пароль.',
    ]);
}
?>
