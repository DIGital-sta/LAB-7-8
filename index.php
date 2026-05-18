<?php
require_once 'auth.php';
$loggedIn    = isLoggedIn();
$displayName = currentUserName();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheeses — Імператор сирів</title>
    <meta name="description" content="Cheeses — магазин преміальних сирів. Доставка по всій Україні.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Andika:wght@400;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
</head>
<body>

<!-- ============================================================
     HEADER
============================================================ -->
<header class="header">
    <div class="nav-wrapper">
        <div class="container">
            <nav class="nav">
                <div class="logo">CHEES<span>ES</span></div>

                <ul class="nav-menu" id="nav-menu">
                    <li><a href="#home"     class="js-scroll">Головна</a></li>
                    <li><a href="#products" class="js-scroll">Товари</a></li>
                    <li><a href="#gallery"  class="js-scroll">Галерея</a></li>
                    <li><a href="#about"    class="js-scroll">Про нас</a></li>
                    <li><a href="#contact"  class="js-scroll">Контакт</a></li>

                    <?php if ($loggedIn): ?>
                    <li class="nav-user">
                        <span class="nav-user-name">👤 <?= htmlspecialchars($displayName) ?></span>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li><a href="admin.php" class="nav-admin-link">⚙ Адмін</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="nav-logout-link">Вийти</a></li>
                    <?php else: ?>
                    <li><a href="login.php" class="nav-auth-btn nav-auth-login">Вхід</a></li>
                    <li><a href="login.php#register" class="nav-auth-btn nav-auth-reg" onclick="sessionStorage.setItem('authTab','register')">Реєстрація</a></li>
                    <?php endif; ?>
                </ul>

                <div class="nav-right">
                    <button class="nav-cart-btn" onclick="openCart()" id="navCartBtn">
                        🛒 <span class="nav-cart-count" style="display:none">0</span>
                    </button>
                    <div class="burger" id="burger">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Hero -->
    <div class="hero container" id="home">
        <div class="hero-content">
            <p class="hero-tag">🏆 Преміальна якість</p>
            <h1>Cheeses —<br>Імператор сирів</h1>
            <p class="hero-sub">Справжній смак з найкращих куточків Європи. Доставка по всій Україні.</p>
            <div class="hero-btns">
                <a href="#products" class="btn js-scroll">Переглянути каталог</a>
                <a href="#contact"  class="btn btn-ghost js-scroll">Зв'язатися</a>
            </div>
        </div>
    </div>
</header>

<!-- ============================================================
     SWIPER SLIDER
============================================================ -->
<section class="slider-section">
    <div class="container">
        <h2 class="section-title">Топ пропозиції</h2>
        <div class="swiper cheese-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide slider-slide">
                    <img src="images/gauda.png" alt="Гауда">
                    <div class="slide-info">
                        <span class="slide-tag">Напівтвердий</span>
                        <h3>Гауда</h3>
                        <p>Класичний голландський сир із м'яким вершковим смаком. Ідеальний для сирної тарілки.</p>
                        <div class="slide-footer">
                            <span class="slide-price">1200 грн / кг</span>
                            <button class="btn btn-sm add-to-cart-btn"
                                data-id="gauda" data-name="Гауда"
                                data-price="1200" data-img="images/gauda.png">
                                + До кошика
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slider-slide">
                    <img src="images/edam.png" alt="Едам">
                    <div class="slide-info">
                        <span class="slide-tag">Легкий</span>
                        <h3>Едам</h3>
                        <p>Ніжний сир у червоній воскові оболонці — улюбленець усієї родини.</p>
                        <div class="slide-footer">
                            <span class="slide-price">1150 грн / кг</span>
                            <button class="btn btn-sm add-to-cart-btn"
                                data-id="edam" data-name="Едам"
                                data-price="1150" data-img="images/edam.png">
                                + До кошика
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slider-slide">
                    <img src="images/parmezan.png" alt="Пармезан">
                    <div class="slide-info">
                        <span class="slide-tag">Витриманий</span>
                        <h3>Пармезан</h3>
                        <p>Король твердих сирів з Італії. Витриманий 24 місяці для неперевершеного смаку.</p>
                        <div class="slide-footer">
                            <span class="slide-price">2500 грн / кг</span>
                            <button class="btn btn-sm add-to-cart-btn"
                                data-id="parmezan" data-name="Пармезан"
                                data-price="2500" data-img="images/parmezan.png">
                                + До кошика
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slider-slide">
                    <img src="images/chedder.png" alt="Чеддер">
                    <div class="slide-info">
                        <span class="slide-tag">Популярний</span>
                        <h3>Чеддер</h3>
                        <p>Насичений англійський сир із пікантним присмаком. Чудово плавиться.</p>
                        <div class="slide-footer">
                            <span class="slide-price">1300 грн / кг</span>
                            <button class="btn btn-sm add-to-cart-btn"
                                data-id="chedder" data-name="Чеддер"
                                data-price="1300" data-img="images/chedder.png">
                                + До кошика
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>

<!-- ============================================================
     BENEFITS
============================================================ -->
<section class="benefits-section">
    <div class="container">
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🚚</div>
                <h4>Швидка доставка</h4>
                <p>По всій Україні за 1-2 дні. Безкоштовно від 2000 грн.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🌿</div>
                <h4>100% натуральне</h4>
                <p>Без консервантів та штучних добавок. Тільки природні інгредієнти.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🏅</div>
                <h4>Преміум якість</h4>
                <p>Прямі поставки від провідних виробників Європи.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">❄️</div>
                <h4>Холодне зберігання</h4>
                <p>Термоізольоване пакування гарантує свіжість продукту.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     PRODUCTS CATALOG
============================================================ -->
<section class="categories section" id="products">
    <div class="container">
        <h2 class="section-title">Каталог сирів</h2>

        <div class="cat-filter">
            <button class="cat-btn active" data-cat="all">Всі</button>
            <button class="cat-btn" data-cat="Напівтвердий">Напівтверді</button>
            <button class="cat-btn" data-cat="Витриманий">Витримані</button>
            <button class="cat-btn" data-cat="Сири з цвіллю">З цвіллю</button>
            <button class="cat-btn" data-cat="Свіжий">Свіжі</button>
        </div>

        <div class="products-grid" id="productsGrid">

            <!-- Гауда -->
            <div class="card" data-cat="Напівтвердий">
                <div class="card-badge">Популярне</div>
                <img src="images/gauda.png" alt="Гауда">
                <div class="card-info">
                    <span class="card-cat">Напівтвердий</span>
                    <p class="card-name">Гауда</p>
                    <p class="card-desc">Класичний голландський сир</p>
                    <div class="card-bottom">
                        <span class="price">1200 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="gauda" data-name="Гауда"
                            data-price="1200" data-img="images/gauda.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <!-- Едам -->
            <div class="card" data-cat="Напівтвердий">
                <img src="images/edam.png" alt="Едам">
                <div class="card-info">
                    <span class="card-cat">Напівтвердий</span>
                    <p class="card-name">Едам</p>
                    <p class="card-desc">Легкий ніжний смак</p>
                    <div class="card-bottom">
                        <span class="price">1150 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="edam" data-name="Едам"
                            data-price="1150" data-img="images/edam.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <!-- Пармезан -->
            <div class="card" data-cat="Витриманий">
                <div class="card-badge card-badge--gold">Преміум</div>
                <img src="images/parmezan.png" alt="Пармезан">
                <div class="card-info">
                    <span class="card-cat">Витриманий</span>
                    <p class="card-name">Пармезан</p>
                    <p class="card-desc">Витриманий 24 місяці</p>
                    <div class="card-bottom">
                        <span class="price">2500 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="parmezan" data-name="Пармезан"
                            data-price="2500" data-img="images/parmezan.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <!-- Чеддер -->
            <div class="card" data-cat="Витриманий">
                <img src="images/chedder.png" alt="Чеддер">
                <div class="card-info">
                    <span class="card-cat">Витриманий</span>
                    <p class="card-name">Чеддер</p>
                    <p class="card-desc">Насичений пікантний смак</p>
                    <div class="card-bottom">
                        <span class="price">1300 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="chedder" data-name="Чеддер"
                            data-price="1300" data-img="images/chedder.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cheese -->
            <div class="card" data-cat="Сири з цвіллю">
                <div class="card-badge card-badge--blue">Новинка</div>
                <img src="images/cheese.png" alt="Рокфор">
                <div class="card-info">
                    <span class="card-cat">Сири з цвіллю</span>
                    <p class="card-name">Рокфор</p>
                    <p class="card-desc">Блакитна цвіль, гострий смак</p>
                    <div class="card-bottom">
                        <span class="price">1500 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="rokfor" data-name="Рокфор"
                            data-price="1500" data-img="images/cheese.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <!-- Камамбер (repeat cheese.png) -->
            <div class="card" data-cat="Свіжий">
                <img src="images/cheese.png" alt="Камамбер">
                <div class="card-info">
                    <span class="card-cat">Свіжий</span>
                    <p class="card-name">Камамбер</p>
                    <p class="card-desc">М'який із білою скоринкою</p>
                    <div class="card-bottom">
                        <span class="price">950 грн</span>
                        <button class="btn-add add-to-cart-btn"
                            data-id="camembert" data-name="Камамбер"
                            data-price="950" data-img="images/cheese.png">
                            +
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     DISCOUNT BANNER
============================================================ -->
<section class="discount">
    <div class="container">
        <div class="discount-inner">
            <div>
                <p class="discount-tag">🎉 Спеціальна пропозиція</p>
                <h2>Знижка 20%<br>на перше замовлення</h2>
                <p class="discount-sub">Введіть промокод <strong>CHEESE20</strong> при оформленні</p>
            </div>
            <button class="btn" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">
                Замовити зараз
            </button>
        </div>
    </div>
</section>

<!-- ============================================================
     GALLERY
============================================================ -->
<section class="gallery section" id="gallery" style="background: linear-gradient(rgba(17,17,17,.95), rgba(17,17,17,.97)), url('images/back3.png') center/cover;">
    <div class="container">
        <h2 class="section-title">Галерея</h2>
        <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            <img src="images/gauda.png"    alt="Гауда"    class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
            <img src="images/edam.png"     alt="Едам"     class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
            <img src="images/parmezan.png" alt="Пармезан" class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
            <img src="images/chedder.png"  alt="Чеддер"   class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
            <img src="images/cheese.png"   alt="Сир"      class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
            <img src="images/gauda.png"    alt="Гауда 2"  class="gallery-img" style="width: 100%; height: 260px; border-radius: 14px; object-fit: cover; transition: transform .4s, opacity .4s; cursor: pointer;" onmouseover="this.style.transform='scale(1.03)'; this.style.opacity='0.85'" onmouseout="this.style.transform='scale(1)'; this.style.opacity='1'">
        </div>
    </div>
</section>

<!-- ============================================================
     ABOUT
============================================================ -->
<section class="about" id="about">
    <div class="container">
        <div class="about-inner">
            <div class="about-text">
                <h2>Про нас</h2>
                <p>Ми — команда справжніх поціновувачів сиру, що об'єдналась з однією метою: зробити якісні європейські сири доступними для кожного українця.</p>
                <p>Прямі поставки від фермерів Нідерландів, Франції та Італії гарантують автентичний смак без посередників.</p>
                <div class="about-stats">
                    <div class="about-stat"><strong>50+</strong><span>сортів сиру</span></div>
                    <div class="about-stat"><strong>5 000+</strong><span>задоволених клієнтів</span></div>
                    <div class="about-stat"><strong>3 роки</strong><span>на ринку</span></div>
                </div>
            </div>
            <div class="about-img-wrap">
                <img src="images/cheese.png" alt="Про нас" class="about-img">
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTACT FORM
============================================================ -->
<section class="form-section section" id="contact">
    <div class="container">
        <h2 class="section-title">Зв'яжіться з нами</h2>
        <p class="section-sub">Є питання? Напишіть нам — відповімо протягом 2 годин.</p>
        <div class="form-container">
            <form class="main-form" id="main-form">
                <div class="form-row">
                    <div>
                        <label for="name">Ім'я *</label>
                        <input type="text" id="name" name="name" placeholder="Ваше ім'я">
                    </div>
                    <div>
                        <label for="email">Email *</label>
                        <input type="text" id="email" name="email" placeholder="your@email.com">
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="phone">Телефон</label>
                        <input type="text" id="phone" name="phone" placeholder="+380...">
                    </div>
                    <div>
                        <label for="budget">Бюджет *</label>
                        <input type="text" id="budget" name="budget" placeholder="2000 грн">
                    </div>
                </div>
                <label for="subject">Тема *</label>
                <input type="text" id="subject" name="subject" placeholder="Замовлення сиру">
                <label for="message">Повідомлення *</label>
                <textarea id="message" name="message" placeholder="Ваше повідомлення..."></textarea>
                <button type="submit" class="btn submit-btn">Надіслати</button>
                <div id="formMsg" class="form-msg"></div>
            </form>
        </div>
    </div>
</section>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="footer">
    <div class="container footer-inner" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: space-between; padding: 40px 0;">
        <div class="footer-brand" style="flex: 1; min-width: 250px;">
            <div class="logo">CHEES<span>ES</span></div>
            <p style="margin-top: 15px; color: #a1a1aa;">Преміальні сири з Європи. Смак, що надихає.</p>
        </div>
        <div class="footer-col" style="flex: 1; min-width: 200px;">
            <h4 style="margin-bottom: 15px;">Навігація</h4>
            <a href="#home"     class="js-scroll" style="display: block; color: #a1a1aa; margin-bottom: 8px; text-decoration: none;">Головна</a>
            <a href="#products" class="js-scroll" style="display: block; color: #a1a1aa; margin-bottom: 8px; text-decoration: none;">Товари</a>
            <a href="#gallery"  class="js-scroll" style="display: block; color: #a1a1aa; margin-bottom: 8px; text-decoration: none;">Галерея</a>
            <a href="#contact"  class="js-scroll" style="display: block; color: #a1a1aa; margin-bottom: 8px; text-decoration: none;">Контакт</a>
        </div>
        <div class="footer-col" style="flex: 1; min-width: 200px;">
            <h4 style="margin-bottom: 15px;">Контакти</h4>
            <p style="color: #a1a1aa; margin-bottom: 8px;">📞 +380 98 389 1122</p>
            <p style="color: #a1a1aa; margin-bottom: 8px;">✉️ sales@cheeses.ua</p>
            <p style="color: #a1a1aa; margin-bottom: 8px;">📍 Київ, Україна</p>
        </div>
        <div class="footer-col" style="flex: 1; min-width: 200px;">
            <h4 style="margin-bottom: 15px;">Ми у соцмережах</h4>
            <div class="social-links" style="display: flex; gap: 15px;">
                <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: none; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#f39c12'" onmouseout="this.style.color='#fff'">Instagram</a>
                <a href="https://t.me/" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: none; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#f39c12'" onmouseout="this.style.color='#fff'">Telegram</a>
                <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer" style="color: #fff; text-decoration: none; font-size: 1.5rem; transition: color 0.3s;" onmouseover="this.style.color='#f39c12'" onmouseout="this.style.color='#fff'">Facebook</a>
            </div>
        </div>
    </div>
    <div class="footer-copy" style="border-top: 1px solid rgba(255,255,255,0.1); padding: 20px 0; text-align: center; color: #a1a1aa; font-size: 0.9rem;">
        <div class="container">© <?= date('Y') ?> Cheeses. Всі права захищені.</div>
    </div>
</footer>

<!-- ============================================================
     CART SIDEBAR
============================================================ -->
<div class="cart-overlay" id="cartOverlay"></div>
<aside class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <div class="cart-title">
            🛒 Кошик
            <span class="cart-count-badge" style="display:none">0</span>
        </div>
        <button class="cart-close-btn" onclick="closeCart()">✕</button>
    </div>

    <div class="cart-items" id="cartItemsList">
        <div class="cart-empty" id="cartEmpty">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <p>Кошик порожній</p>
            <small>Додайте товари зі сторінки каталогу</small>
        </div>
    </div>

    <div class="cart-footer" id="cartFooter" style="display:none">
        <div class="cart-totals">
            <div class="cart-total-row">
                <span>Підсумок</span>
                <span id="cartSubtotal">$0</span>
            </div>
            <div class="cart-total-row">
                <span>Доставка</span>
                <span id="cartDelivery">$5</span>
            </div>
            <div class="cart-total-row grand">
                <span>Разом</span>
                <span id="cartGrandTotal">$0</span>
            </div>
        </div>
        <button class="cart-checkout-btn" onclick="openOrderModal()">
            Оформити замовлення →
        </button>
        <button class="cart-continue-btn" onclick="closeCart()">
            Продовжити покупки
        </button>
    </div>
</aside>

<!-- FAB cart button -->
<button class="cart-fab" id="cartFab" onclick="openCart()" style="display:none" title="Кошик">
    🛒
    <span class="cart-fab-badge" style="display:none">0</span>
</button>

<!-- Toast -->
<div class="toast" id="cartToast">
    <span class="toast-icon">✅</span>
    <div class="toast-text">
        <strong>Додано до кошика</strong>
        <span class="toast-name"></span>
    </div>
</div>

<!-- ============================================================
     ORDER MODAL
============================================================ -->
<div class="modal-overlay" id="orderModalOverlay">
    <div class="order-modal">
        <div class="modal-header">
            <div class="modal-title">📦 Оформлення замовлення</div>
            <button class="modal-close" onclick="closeOrderModal()">✕</button>
        </div>
        <div class="modal-body">

            <!-- Form wrap -->
            <div id="orderFormWrap">
                <!-- Summary -->
                <div class="order-summary">
                    <div class="order-summary-title">Ваше замовлення</div>
                    <div id="orderSummaryItems"></div>
                    <div class="order-total-line">
                        <span>До сплати</span>
                        <span id="modalOrderTotal">$0</span>
                    </div>
                </div>

                <!-- Contact form -->
                <form id="orderForm">
                    <div class="modal-form-row">
                        <div class="modal-form-group">
                            <label>Ім'я *</label>
                            <input type="text" name="orderName" placeholder="Іван Іваненко" required>
                        </div>
                        <div class="modal-form-group">
                            <label>Телефон *</label>
                            <input type="tel" name="orderPhone" placeholder="+380 XX XXX XX XX" required>
                        </div>
                    </div>
                    <div class="modal-form-group">
                        <label>Email</label>
                        <input type="email" name="orderEmail" placeholder="your@email.com">
                    </div>
                    <div class="modal-form-group">
                        <label>Адреса доставки *</label>
                        <input type="text" name="orderAddress" placeholder="Місто, вулиця, будинок, квартира" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Коментар</label>
                        <textarea name="orderComment" placeholder="Побажання до замовлення..." style="height:70px; resize:none;"></textarea>
                    </div>
                    <button type="submit" class="modal-submit-btn" id="orderSubmitBtn">
                        Замовити
                    </button>
                </form>
            </div>

            <!-- Success screen -->
            <div class="order-success" id="orderSuccess">
                <div class="success-icon">✓</div>
                <h2 class="success-title">Замовлення прийнято!</h2>
                <p class="success-text">Дякуємо за покупку! Наш менеджер зв'яжеться з вами найближчим часом для підтвердження.</p>
                <div class="success-order-num">
                    Номер замовлення: <span id="orderNum">#1001</span>
                </div>
                <button class="btn" onclick="closeOrderModal()">Продовжити покупки</button>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="assets/js/cart.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
