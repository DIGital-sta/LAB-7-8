<?php
/**
 * login.php — Сторінка входу та реєстрації.
 * Якщо вже авторизований — перекидає на index.php.
 */
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'admin.php' : 'index.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід / Реєстрація | Cheeses</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Andika:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: radial-gradient(ellipse at 60% 40%, #f5ede0 0%, #e8d5be 100%);
            padding: 2rem;
            font-family: 'Andika', sans-serif;
        }

        /* ── Card ── */
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2.5rem 2rem;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 12px 50px rgba(100,50,20,.15);
            animation: cardIn .35s ease;
        }
        @keyframes cardIn {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0);    }
        }

        /* ── Logo ── */
        .auth-logo {
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .auth-logo span {
            font-size: 2rem;
            font-weight: 700;
            color: #3d2b1f;
            letter-spacing: 2px;
        }
        .auth-logo span em {
            color: #b5651d;
            font-style: normal;
        }
        .auth-logo p {
            font-size: .85rem;
            color: #999;
            margin-top: .3rem;
        }

        /* ── Tabs ── */
        .auth-tabs {
            display: flex;
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px solid #e8d9cc;
            margin-bottom: 1.8rem;
        }
        .auth-tab {
            flex: 1;
            padding: .7rem;
            text-align: center;
            cursor: pointer;
            font-weight: 700;
            font-size: .95rem;
            color: #999;
            background: #faf7f4;
            border: none;
            transition: background .25s, color .25s;
            font-family: 'Andika', sans-serif;
        }
        .auth-tab.active {
            background: #b5651d;
            color: #fff;
        }

        /* ── Panels ── */
        .auth-panel { display: none; }
        .auth-panel.active { display: block; }

        /* ── Groups ── */
        .auth-group {
            margin-bottom: 1.1rem;
        }
        .auth-group label {
            display: block;
            font-size: .82rem;
            font-weight: 700;
            color: #3d2b1f;
            margin-bottom: .35rem;
        }
        .auth-group input {
            width: 100%;
            padding: .78rem 1rem;
            border: 1.5px solid #ddd;
            border-radius: 9px;
            font-size: .97rem;
            font-family: 'Andika', sans-serif;
            transition: border-color .25s, box-shadow .25s;
            outline: none;
            background: #fefcfa;
        }
        .auth-group input:focus {
            border-color: #b5651d;
            box-shadow: 0 0 0 3px rgba(181,101,29,.12);
        }
        .auth-group input.invalid {
            border-color: #e74c3c;
        }

        /* ── Row ── */
        .auth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .8rem;
        }

        /* ── Submit btn ── */
        .auth-btn {
            width: 100%;
            padding: .9rem;
            margin-top: .6rem;
            background: #b5651d;
            color: #fff;
            border: none;
            border-radius: 9px;
            font-size: 1rem;
            font-family: 'Andika', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: background .25s, transform .1s, box-shadow .25s;
            letter-spacing: .3px;
        }
        .auth-btn:hover   { background: #8B4513; box-shadow: 0 4px 14px rgba(139,69,19,.3); }
        .auth-btn:active  { transform: scale(.98); }
        .auth-btn:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; }

        /* ── Messages ── */
        .auth-msg {
            margin-top: 1rem;
            padding: .7rem 1rem;
            border-radius: 9px;
            font-size: .88rem;
            display: none;
            text-align: center;
        }
        .auth-msg.error {
            background: #fde8e8; color: #c0392b;
            border: 1px solid #f5c6c6; display: block;
        }
        .auth-msg.success {
            background: #e8f8ef; color: #27ae60;
            border: 1px solid #a8e6bf; display: block;
        }

        /* ── Footer ── */
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
        }
        .auth-footer a {
            color: #b5651d;
            font-size: .88rem;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }

        /* ── Hint ── */
        .auth-hint {
            margin-top: 1rem;
            text-align: center;
            font-size: .78rem;
            color: #bbb;
        }

        /* ── Password toggle ── */
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 2.8rem; }
        .pw-eye {
            position: absolute;
            right: .85rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 1.1rem;
            line-height: 1;
            background: none;
            border: none;
            padding: 0;
        }
        .pw-eye:hover { color: #b5651d; }
    </style>
</head>

<body>

    <div class="auth-card">

        <!-- Logo -->
        <div class="auth-logo">
            <span>CHEES<em>ES</em></span>
            <p>Преміальні сири з Європи</p>
        </div>

        <!-- Tabs -->
        <div class="auth-tabs">
            <button class="auth-tab active" id="tabLogin"    onclick="switchTab('login')">🔑 Вхід</button>
            <button class="auth-tab"        id="tabRegister" onclick="switchTab('register')">✨ Реєстрація</button>
        </div>

        <!-- ══════════════════════════
             LOGIN PANEL
        ══════════════════════════ -->
        <div class="auth-panel active" id="panelLogin">
            <form id="loginForm" autocomplete="off" novalidate>

                <div class="auth-group">
                    <label for="loginUsername">Логін або Email</label>
                    <input type="text" id="loginUsername" name="username"
                           placeholder="admin або your@email.com" required autocomplete="username">
                </div>

                <div class="auth-group">
                    <label for="loginPassword">Пароль</label>
                    <div class="pw-wrap">
                        <input type="password" id="loginPassword" name="password"
                               placeholder="••••••" required autocomplete="current-password">
                        <button type="button" class="pw-eye" onclick="togglePw('loginPassword', this)">👁</button>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="loginBtn">Увійти</button>
                <div class="auth-msg" id="loginMsg"></div>
            </form>
            <p class="auth-hint">Адмін: <strong>admin</strong> / <strong>admin</strong></p>
        </div>

        <!-- ══════════════════════════
             REGISTER PANEL
        ══════════════════════════ -->
        <div class="auth-panel" id="panelRegister">
            <form id="registerForm" autocomplete="off" novalidate>

                <div class="auth-row">
                    <div class="auth-group">
                        <label for="regUsername">Логін *</label>
                        <input type="text" id="regUsername" name="username"
                               placeholder="name" required minlength="3" autocomplete="username">
                    </div>
                    <div class="auth-group">
                        <label for="regDisplayName">Ім'я</label>
                        <input type="text" id="regDisplayName" name="display_name"
                               placeholder="surname">
                    </div>
                </div>

                <div class="auth-group">
                    <label for="regEmail">Email *</label>
                    <input type="email" id="regEmail" name="email"
                           placeholder="your@email.com" required autocomplete="email">
                </div>

                <div class="auth-row">
                    <div class="auth-group">
                        <label for="regPassword">Пароль *</label>
                        <div class="pw-wrap">
                            <input type="password" id="regPassword" name="password"
                                   placeholder="мін. 6 символів" required minlength="6" autocomplete="new-password">
                            <button type="button" class="pw-eye" onclick="togglePw('regPassword', this)">👁</button>
                        </div>
                    </div>
                    <div class="auth-group">
                        <label for="regPassword2">Повторіть *</label>
                        <div class="pw-wrap">
                            <input type="password" id="regPassword2"
                                   placeholder="••••••" required autocomplete="new-password">
                            <button type="button" class="pw-eye" onclick="togglePw('regPassword2', this)">👁</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="registerBtn">Зареєструватися</button>
                <div class="auth-msg" id="registerMsg"></div>
            </form>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <a href="index.php">← Повернутися на сайт</a>
        </div>

    </div><!-- /auth-card -->

    <script>
        /* ── Tab switch ── */
        function switchTab(tab) {
            ['login','register'].forEach(t => {
                document.getElementById('panel' + cap(t)).classList.toggle('active', t === tab);
                document.getElementById('tab'   + cap(t)).classList.toggle('active', t === tab);
            });
        }
        function cap(s){ return s.charAt(0).toUpperCase() + s.slice(1); }

        /* ── Password visibility ── */
        function togglePw(inputId, btn) {
            const inp = document.getElementById(inputId);
            if (inp.type === 'password') { inp.type = 'text';     btn.textContent = '🙈'; }
            else                          { inp.type = 'password'; btn.textContent = '👁';  }
        }

        /* ── Show message ── */
        function showMsg(msgId, text, type) {
            const el = document.getElementById(msgId);
            el.textContent = text;
            el.className   = 'auth-msg ' + type;
        }

        /* ── LOGIN ── */
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('loginBtn');
            btn.textContent = 'Зачекайте...';
            btn.disabled    = true;

            const username = document.getElementById('loginUsername').value.trim();
            const password = document.getElementById('loginPassword').value;

            try {
                const res  = await fetch('login_process.php', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ username, password })
                });
                const data = await res.json();
                showMsg('loginMsg', data.message, data.success ? 'success' : 'error');

                if (data.success) {
                    setTimeout(() => { window.location.href = data.redirect || 'index.php'; }, 800);
                } else {
                    btn.textContent = 'Увійти';
                    btn.disabled    = false;
                }
            } catch(err) {
                showMsg('loginMsg', 'Помилка з\'єднання з сервером.', 'error');
                btn.textContent = 'Увійти';
                btn.disabled    = false;
            }
        });

        /* ── REGISTER ── */
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('registerBtn');

            const username     = document.getElementById('regUsername').value.trim();
            const displayName  = document.getElementById('regDisplayName').value.trim();
            const email        = document.getElementById('regEmail').value.trim();
            const password     = document.getElementById('regPassword').value;
            const password2    = document.getElementById('regPassword2').value;

            // Клієнтська валідація
            if (password !== password2) {
                showMsg('registerMsg', 'Паролі не співпадають.', 'error');
                return;
            }
            if (password.length < 6) {
                showMsg('registerMsg', 'Пароль має містити щонайменше 6 символів.', 'error');
                return;
            }

            btn.textContent = 'Реєстрація...';
            btn.disabled    = true;

            try {
                const res  = await fetch('register_process.php', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ username, email, password, display_name: displayName })
                });
                const data = await res.json();
                showMsg('registerMsg', data.message, data.success ? 'success' : 'error');

                if (data.success) {
                    setTimeout(() => { window.location.href = data.redirect || 'index.php'; }, 1000);
                } else {
                    btn.textContent = 'Зареєструватися';
                    btn.disabled    = false;
                }
            } catch(err) {
                showMsg('registerMsg', 'Помилка з\'єднання з сервером.', 'error');
                btn.textContent = 'Зареєструватися';
                btn.disabled    = false;
            }
        });

        /* ── Auto-switch tab via sessionStorage ── */
        (function() {
            const tab = sessionStorage.getItem('authTab');
            if (tab === 'register') {
                switchTab('register');
                sessionStorage.removeItem('authTab');
            }
        })();
    </script>

</body>
</html>

