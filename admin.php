<?php
require_once 'auth.php';
require_once 'subscriptions_functions.php';
requireAdmin();

// Обробка видалення підписника
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    removeSubscription((int)$_POST['delete_id']);
    header('Location: admin.php?tab=subs');
    exit;
}

// Обробка зміни статусу замовлення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_status'])) {
    $ordersFile = __DIR__ . '/storage/orders.ser';
    if (file_exists($ordersFile)) {
        $orders = unserialize(file_get_contents($ordersFile));
        foreach ($orders as &$o) {
            if ((int)$o['id'] === (int)$_POST['order_id']) {
                $o['status'] = $_POST['order_status'];
                break;
            }
        }
        file_put_contents($ordersFile, serialize($orders));
    }
    header('Location: admin.php?tab=orders');
    exit;
}

$subscriptions = getSubscriptions();

// Завантаження замовлень
$ordersFile = __DIR__ . '/storage/orders.ser';
$orders     = file_exists($ordersFile) ? unserialize(file_get_contents($ordersFile)) : [];
$orders     = is_array($orders) ? array_reverse($orders) : []; // нові спочатку

$total_revenue = array_reduce($orders, fn($c, $o) => $c + ($o['status'] === 'done' ? ((float)$o['total'] + (float)$o['delivery']) : 0), 0);
$new_orders    = count(array_filter($orders, fn($o) => $o['status'] === 'new'));

$activeTab = $_GET['tab'] ?? 'orders';

$statusLabel = ['new' => 'Нове', 'confirmed' => 'Підтверджено', 'shipping' => 'Доставка', 'done' => 'Виконано', 'cancelled' => 'Скасовано'];
$statusClass = ['new' => '#f59e0b', 'confirmed' => '#3b82f6', 'shipping' => '#8b5cf6', 'done' => '#22c55e', 'cancelled' => '#ef4444'];
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмін | Cheeses</title>
    <link href="https://fonts.googleapis.com/css2?family=Andika:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Andika',sans-serif;background:#0e0e0e;color:#e8e0d8;min-height:100vh}
        a{text-decoration:none;color:inherit}

        /* Header */
        .adm-hdr{background:#141414;border-bottom:1px solid rgba(255,255,255,.07);padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:100}
        .adm-logo{font-family:'Playfair Display',serif;font-size:1.4rem;color:#fff}
        .adm-logo em{color:#8b3a2b;font-style:normal}
        .adm-nav{display:flex;align-items:center;gap:.8rem}
        .adm-nav a{color:#888;font-size:.9rem;padding:.4rem .9rem;border-radius:8px;transition:all .2s}
        .adm-nav a:hover{background:rgba(255,255,255,.06);color:#fff}
        .adm-user{color:#aaa;font-size:.88rem}
        .btn-logout{background:#8b3a2b;color:#fff!important;border-radius:8px;padding:.4rem 1rem!important;font-weight:700}
        .btn-logout:hover{background:#a64d3d!important}

        /* Layout */
        .adm-wrap{max-width:1200px;margin:2rem auto;padding:0 1.5rem}
        .adm-title{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:.3rem}
        .adm-sub{color:#666;font-size:.9rem;margin-bottom:2rem}

        /* Stats */
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem}
        .stat{background:#1a1a1a;border:1px solid rgba(255,255,255,.06);border-radius:14px;padding:1.5rem;position:relative;overflow:hidden}
        .stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--c,#8b3a2b)}
        .stat-n{font-family:'Playfair Display',serif;font-size:2.2rem;color:var(--c,#8b3a2b);margin:.3rem 0}
        .stat-l{font-size:.8rem;color:#666;text-transform:uppercase;letter-spacing:.5px}

        /* Tabs */
        .tabs{display:flex;gap:.3rem;border-bottom:1px solid rgba(255,255,255,.07);margin-bottom:2rem}
        .tab{background:none;border:none;color:#666;padding:.8rem 1.4rem;cursor:pointer;font-family:'Andika',sans-serif;font-size:.95rem;border-bottom:2px solid transparent;margin-bottom:-1px;transition:all .2s;border-radius:0}
        .tab:hover{color:#fff}
        .tab.active{color:#8b3a2b;border-bottom-color:#8b3a2b}
        .tab-content{display:none}
        .tab-content.active{display:block}

        /* Table card */
        .tcard{background:#1a1a1a;border:1px solid rgba(255,255,255,.06);border-radius:16px;overflow:hidden}
        .tcard-hdr{padding:1.1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,.06)}
        .tcard-hdr h2{font-size:1rem;color:#fff}
        .badge{background:rgba(139,58,43,.2);color:#c97a5e;padding:.2rem .7rem;border-radius:20px;font-size:.8rem;font-weight:700;border:1px solid rgba(139,58,43,.3)}

        table{width:100%;border-collapse:collapse}
        th{background:rgba(255,255,255,.03);padding:.85rem 1.2rem;text-align:left;font-size:.75rem;color:#555;text-transform:uppercase;letter-spacing:.5px;font-weight:700}
        td{padding:.85rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.04);font-size:.9rem;color:#ccc;vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:rgba(255,255,255,.02)}

        .status-pill{display:inline-block;padding:.22rem .75rem;border-radius:20px;font-size:.78rem;font-weight:700;border:1px solid}
        .email-link{color:#8b3a2b}
        .email-link:hover{text-decoration:underline}

        /* Buttons */
        .btn-del{background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.3);padding:.3rem .75rem;border-radius:6px;cursor:pointer;font-family:'Andika',sans-serif;font-size:.82rem;transition:all .2s}
        .btn-del:hover{background:#ef4444;color:#fff;border-color:#ef4444}
        .status-select{background:#252525;border:1px solid rgba(255,255,255,.1);color:#fff;padding:.3rem .6rem;border-radius:6px;font-family:'Andika',sans-serif;font-size:.82rem;cursor:pointer}

        /* Empty */
        .empty{padding:4rem 2rem;text-align:center;color:#444;font-size:1rem}

        /* Order items popover */
        .items-list{font-size:.82rem;color:#888;line-height:1.6}

        @media(max-width:700px){
            .adm-hdr{flex-direction:column;gap:.7rem}
            .stats{grid-template-columns:1fr 1fr}
            th,td{padding:.6rem .8rem;font-size:.8rem}
        }
    </style>
</head>
<body>

<header class="adm-hdr">
    <div class="adm-logo">🧀 Chees<em>ES</em> <span style="font-size:.6rem;background:#8b3a2b;color:#fff;padding:.2rem .5rem;border-radius:4px;vertical-align:middle;margin-left:.3rem">ADMIN</span></div>
    <nav class="adm-nav">
        <a href="index.php">← На сайт</a>
        <span class="adm-user">Ви: <strong><?= htmlspecialchars($_SESSION['user']) ?></strong></span>
        <a href="logout.php" class="btn-logout">Вийти</a>
    </nav>
</header>

<div class="adm-wrap">
    <h1 class="adm-title">Дашборд</h1>
    <p class="adm-sub">Управління замовленнями та підписниками</p>

    <!-- Stats -->
    <div class="stats">
        <div class="stat" style="--c:#22c55e">
            <div class="stat-l">Нові замовлення</div>
            <div class="stat-n"><?= $new_orders ?></div>
        </div>
        <div class="stat">
            <div class="stat-l">Всього замовлень</div>
            <div class="stat-n"><?= count($orders) ?></div>
        </div>
        <div class="stat" style="--c:#f59e0b">
            <div class="stat-l">Дохід (виконані)</div>
            <div class="stat-n">$<?= number_format($total_revenue, 0) ?></div>
        </div>
        <div class="stat" style="--c:#3b82f6">
            <div class="stat-l">Підписники</div>
            <div class="stat-n"><?= count($subscriptions) ?></div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab <?= $activeTab === 'orders' ? 'active' : '' ?>" data-tab="orders">📦 Замовлення</button>
        <button class="tab <?= $activeTab === 'subs' ? 'active' : '' ?>" data-tab="subs">📋 Підписники</button>
    </div>

    <!-- ORDERS -->
    <div class="tab-content <?= $activeTab === 'orders' ? 'active' : '' ?>" id="tab-orders">
        <div class="tcard">
            <div class="tcard-hdr">
                <h2>📦 Список замовлень</h2>
                <span class="badge"><?= count($orders) ?> записів</span>
            </div>
            <?php if (empty($orders)): ?>
                <div class="empty">Замовлень поки немає.</div>
            <?php else: ?>
            <div style="overflow-x:auto">
            <table>
                <thead><tr>
                    <th>#</th><th>Дата</th><th>Клієнт</th><th>Товари</th><th>Сума</th><th>Статус</th><th>Дія</th>
                </tr></thead>
                <tbody>
                <?php foreach ($orders as $o):
                    $col = $statusClass[$o['status']] ?? '#888';
                    $lbl = $statusLabel[$o['status']] ?? $o['status'];
                    $sum = (float)$o['total'] + (float)$o['delivery'];
                    $itemsStr = implode(', ', array_map(fn($i) => "{$i['name']} ×{$i['qty']}", $o['items']));
                ?>
                <tr>
                    <td><strong>#<?= $o['id'] ?></strong></td>
                    <td><?= htmlspecialchars($o['date']) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($o['customer']['name']) ?></strong><br>
                        <small style="color:#666"><?= htmlspecialchars($o['customer']['phone']) ?></small>
                    </td>
                    <td><div class="items-list"><?= htmlspecialchars($itemsStr) ?></div></td>
                    <td><strong style="color:#8b3a2b">$<?= number_format($sum, 0) ?></strong></td>
                    <td>
                        <span class="status-pill" style="color:<?= $col ?>;border-color:<?= $col ?>33;background:<?= $col ?>1a">
                            <?= $lbl ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display:flex;gap:.4rem;align-items:center">
                            <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
                            <select name="order_status" class="status-select">
                                <?php foreach ($statusLabel as $k => $v): ?>
                                <option value="<?= $k ?>" <?= $o['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn-del" style="background:rgba(139,58,43,.1);color:#c97a5e;border-color:rgba(139,58,43,.3)">OK</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SUBSCRIBERS -->
    <div class="tab-content <?= $activeTab === 'subs' ? 'active' : '' ?>" id="tab-subs">
        <div class="tcard">
            <div class="tcard-hdr">
                <h2>📋 Підписники</h2>
                <span class="badge"><?= count($subscriptions) ?> записів</span>
            </div>
            <?php if (empty($subscriptions)): ?>
                <div class="empty">Підписників поки немає.</div>
            <?php else: ?>
            <div style="overflow-x:auto">
            <table>
                <thead><tr><th>#</th><th>Ім'я</th><th>Email</th><th>Телефон</th><th>Тема</th><th>Дата</th><th>Дія</th></tr></thead>
                <tbody>
                <?php foreach ($subscriptions as $sub): ?>
                <tr>
                    <td><strong><?= (int)$sub['id'] ?></strong></td>
                    <td><?= htmlspecialchars($sub['name'] ?? '—') ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($sub['email']) ?>" class="email-link"><?= htmlspecialchars($sub['email']) ?></a></td>
                    <td><?= htmlspecialchars($sub['phone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($sub['subject'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($sub['date'] ?? '—') ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Видалити?')">
                            <input type="hidden" name="delete_id" value="<?= (int)$sub['id'] ?>">
                            <button type="submit" class="btn-del">✕ Видалити</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
document.querySelectorAll('.tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab,.tab-content').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        // Update URL without reload
        history.replaceState(null,'','?tab=' + btn.dataset.tab);
    });
});
</script>
</body>
</html>
