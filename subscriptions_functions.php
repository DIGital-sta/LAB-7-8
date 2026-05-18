<?php
/**
 * subscriptions_functions.php
 * Окремий файл з функціями для роботи з підписниками.
 * Підключається як на subscriptions.php, так і на admin.php —
 * без виконання зайвого коду.
 */

define('SUBSCRIPTIONS_FILE', __DIR__ . '/storage/subscriptions.ser');
define('SUBSCRIPTIONS_LOG',  __DIR__ . '/storage/log.txt');

/**
 * Повертає масив усіх підписників
 */
function getSubscriptions(): array {
    if (!file_exists(SUBSCRIPTIONS_FILE)) {
        return [];
    }
    $data = unserialize(file_get_contents(SUBSCRIPTIONS_FILE));
    return is_array($data) ? $data : [];
}

/**
 * Перевіряє, чи email вже є в базі
 */
function subscriptionExists(string $email): bool {
    foreach (getSubscriptions() as $sub) {
        if (strtolower($sub['email']) === strtolower(trim($email))) {
            return true;
        }
    }
    return false;
}

/**
 * Додає підписника та зберігає у .ser файл.
 * @return bool true — успішно, false — вже існує
 */
function addSubscription(array $data): bool {
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (subscriptionExists($data['email'])) {
        return false;
    }

    // Переконуємось, що директорія існує
    $dir = dirname(SUBSCRIPTIONS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $all   = getSubscriptions();
    $entry = [
        'id'      => count($all) + 1,
        'name'    => htmlspecialchars(trim($data['name']    ?? '')),
        'email'   => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
        'phone'   => htmlspecialchars(trim($data['phone']   ?? '')),
        'budget'  => htmlspecialchars(trim($data['budget']  ?? '')),
        'subject' => htmlspecialchars(trim($data['subject'] ?? '')),
        'message' => htmlspecialchars(trim($data['message'] ?? '')),
        'date'    => date('Y-m-d H:i:s'),
    ];
    $all[] = $entry;

    file_put_contents(SUBSCRIPTIONS_FILE, serialize($all));

    // Лог
    $log = date('Y-m-d H:i:s') . ' — нова заявка від: ' . $entry['email'] . PHP_EOL;
    file_put_contents(SUBSCRIPTIONS_LOG, $log, FILE_APPEND);

    return true;
}

/**
 * Видаляє підписника за id
 */
function removeSubscription(int $id): bool {
    $all      = getSubscriptions();
    $filtered = array_filter($all, fn($s) => (int)$s['id'] !== $id);

    if (count($filtered) === count($all)) {
        return false;
    }

    file_put_contents(SUBSCRIPTIONS_FILE, serialize(array_values($filtered)));
    return true;
}
