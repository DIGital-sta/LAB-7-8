/* ================================================================
   SCRIPT.JS — Основна логіка сайту Cheeses
   ================================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---- Burger menu ---- */
    const burger  = document.getElementById('burger');
    const navMenu = document.getElementById('nav-menu');

    if (burger && navMenu) {
        burger.addEventListener('click', () => {
            burger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        document.querySelectorAll('.js-scroll').forEach(link => {
            link.addEventListener('click', () => {
                burger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    /* ---- Sticky nav shadow ---- */
    const navWrapper = document.querySelector('.nav-wrapper');
    window.addEventListener('scroll', () => {
        if (navWrapper) {
            navWrapper.style.background = window.scrollY > 50
                ? 'rgba(17,17,17,0.97)'
                : 'rgba(17,17,17,0.85)';
        }
    });

    /* ---- Swiper ---- */
    if (document.querySelector('.cheese-swiper')) {
        new Swiper('.cheese-swiper', {
            loop: true,
            speed: 800,
            autoplay: { delay: 4500, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            effect: 'slide',
        });
    }

    /* ---- Catalog filter ---- */
    const catBtns    = document.querySelectorAll('.cat-btn');
    const allCards   = document.querySelectorAll('#productsGrid .card');

    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.cat;
            allCards.forEach(card => {
                const show = filter === 'all' || card.dataset.cat === filter;
                card.style.display = show ? '' : 'none';
                if (show) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    requestAnimationFrame(() => {
                        card.style.transition = 'opacity .35s ease, transform .35s ease';
                        card.style.opacity = '1';
                        card.style.transform = '';
                    });
                }
            });
        });
    });

    /* ---- Contact form (Mock submission for static HTML) ---- */
    const form = document.getElementById('main-form');
    if (form) {
        const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const msgEl   = document.getElementById('formMsg');

        form.addEventListener('submit', async e => {
            e.preventDefault();
            let ok = true;

            ['name','email','budget','subject','message'].forEach(id => {
                const inp = document.getElementById(id);
                if (!inp) return;
                inp.classList.remove('error');
                if (!inp.value.trim()) { inp.classList.add('error'); ok = false; }
            });

            const emailInp = document.getElementById('email');
            if (emailInp?.value && !emailRx.test(emailInp.value)) {
                emailInp.classList.add('error'); ok = false;
            }
            if (!ok) return;

            const btn = form.querySelector('.submit-btn');
            btn.textContent = 'Надсилаємо...';
            btn.disabled = true;

            // Simulate network request
            setTimeout(() => {
                msgEl.className = 'form-msg ok';
                msgEl.textContent = 'Дякуємо! Ваше повідомлення успішно надіслано.';
                form.reset();
                btn.textContent = 'Надіслати';
                btn.disabled = false;
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    msgEl.style.display = 'none';
                }, 5000);
            }, 1000);
        });
    }

    /* ---- Scroll reveal (IntersectionObserver) ---- */
    const revealEls = document.querySelectorAll('.card, .benefit-card, .gallery-img');
    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealEls.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(24px)';
            el.style.transition = 'opacity .5s ease, transform .5s ease';
            io.observe(el);
        });
    }

});