/* ================================================================
   CART.JS — Логіка кошика (localStorage + UI)
   ================================================================ */

const Cart = {
    items: [],
    STORAGE_KEY: 'cheeses_cart',

    init() {
        const saved = localStorage.getItem(this.STORAGE_KEY);
        this.items = saved ? JSON.parse(saved) : [];
        this.updateUI();
    },

    save() {
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(this.items));
    },

    add(product) {
        const existing = this.items.find(i => i.id === product.id);
        if (existing) {
            existing.qty = Math.min(existing.qty + 1, 99);
        } else {
            this.items.push({ ...product, qty: 1 });
        }
        this.save();
        this.updateUI();
        this.renderItems();
        showToast(product.name);
        bumpBadge();
    },

    remove(id) {
        this.items = this.items.filter(i => i.id !== id);
        this.save();
        this.updateUI();
        this.renderItems();
    },

    setQty(id, qty) {
        if (qty < 1) { this.remove(id); return; }
        const item = this.items.find(i => i.id === id);
        if (item) { item.qty = Math.min(qty, 99); }
        this.save();
        this.updateUI();
        this.renderItems();
    },

    clear() {
        this.items = [];
        this.save();
        this.updateUI();
        this.renderItems();
    },

    getTotal() {
        return this.items.reduce((sum, i) => sum + i.price * i.qty, 0);
    },

    getCount() {
        return this.items.reduce((sum, i) => sum + i.qty, 0);
    },

    updateUI() {
        const count = this.getCount();
        // Nav badge
        document.querySelectorAll('.nav-cart-count, .cart-count-badge, .cart-fab-badge')
            .forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? '' : 'none';
            });
        // FAB visibility
        const fab = document.getElementById('cartFab');
        if (fab) fab.style.display = count > 0 ? 'flex' : 'none';
    },

    renderItems() {
        const container = document.getElementById('cartItemsList');
        const empty     = document.getElementById('cartEmpty');
        const footer    = document.getElementById('cartFooter');
        if (!container) return;

        if (this.items.length === 0) {
            container.innerHTML = '';
            if (empty)  empty.style.display  = 'flex';
            if (footer) footer.style.display = 'none';
            return;
        }
        if (empty)  empty.style.display  = 'none';
        if (footer) footer.style.display = 'block';

        container.innerHTML = this.items.map(item => `
            <div class="cart-item" data-id="${item.id}">
                <img class="cart-item-img" src="${item.img}" alt="${item.name}">
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${(item.price * item.qty).toFixed(0)} грн</div>
                    <div class="cart-qty">
                        <button class="qty-btn" onclick="Cart.setQty('${item.id}', ${item.qty - 1})">−</button>
                        <span class="qty-val">${item.qty}</span>
                        <button class="qty-btn" onclick="Cart.setQty('${item.id}', ${item.qty + 1})">+</button>
                    </div>
                </div>
                <button class="cart-item-remove" onclick="Cart.remove('${item.id}')" title="Видалити">✕</button>
            </div>
        `).join('');

        // Totals
        const total = this.getTotal();
        const delivery = total >= 2000 ? 0 : 150;
        const el = id => document.getElementById(id);
        if (el('cartSubtotal'))   el('cartSubtotal').textContent   = `${total.toFixed(0)} грн`;
        if (el('cartDelivery'))   el('cartDelivery').textContent   = delivery === 0 ? 'Безкоштовно' : `${delivery} грн`;
        if (el('cartGrandTotal')) el('cartGrandTotal').textContent = `${(total + delivery).toFixed(0)} грн`;
    }
};

/* ---- Open / Close Sidebar ---- */
function openCart() {
    document.getElementById('cartSidebar').classList.add('open');
    document.getElementById('cartOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    Cart.renderItems();
}
function closeCart() {
    document.getElementById('cartSidebar').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

/* ---- Order Modal ---- */
function openOrderModal() {
    if (Cart.items.length === 0) return;
    closeCart();

    const overlay = document.getElementById('orderModalOverlay');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Render summary
    const summaryEl = document.getElementById('orderSummaryItems');
    if (summaryEl) {
        summaryEl.innerHTML = Cart.items.map(i =>
            `<div class="order-summary-item">
                <span>${i.name} × ${i.qty}</span>
                <strong>${(i.price * i.qty).toFixed(0)} грн</strong>
            </div>`
        ).join('');
    }
    const total = Cart.getTotal();
    const delivery = total >= 2000 ? 0 : 150;
    const el = id => document.getElementById(id);
    if (el('modalOrderTotal')) el('modalOrderTotal').textContent = `${(total + delivery).toFixed(0)} грн`;
}
function closeOrderModal() {
    document.getElementById('orderModalOverlay').classList.remove('open');
    document.body.style.overflow = '';
    // Reset form & success screen
    const form    = document.getElementById('orderForm');
    const success = document.getElementById('orderSuccess');
    const formWrap= document.getElementById('orderFormWrap');
    if (form)     form.reset();
    if (success)  success.classList.remove('show');
    if (formWrap) formWrap.style.display = 'block';
}

/* ---- Submit Order (Mock for static HTML) ---- */
async function submitOrder(e) {
    console.log("IM FUCKING HERE!!!!!!!!!!");
    console.log(Cart.items);
    
    e.preventDefault();
    const form = document.getElementById('orderForm');
    const btn  = document.getElementById('orderSubmitBtn');

    // Validate
    let valid = true;
    form.querySelectorAll('[required]').forEach(input => {
        input.classList.remove('error');
        if (!input.value.trim()) { input.classList.add('error'); valid = false; }
    });
    if (!valid) return;

    btn.disabled = true;
    btn.textContent = 'Оформлення...';

    const payload = {
        customer: {
            name:    form.querySelector('[name="name"]')?.value.trim()    ?? '',
            phone:   form.querySelector('[name="phone"]')?.value.trim()   ?? '',
            email:   form.querySelector('[name="email"]')?.value.trim()   ?? '',
            address: form.querySelector('[name="address"]')?.value.trim() ?? '',
            comment: form.querySelector('[name="comment"]')?.value.trim() ?? '',
        },
        items:    Cart.items,
        total:    Cart.items.reduce((sum, i) => sum + i.price * i.qty, 0),
        delivery: 0,
    };

    try {
        const res  = await fetch('/order_process.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });

        const json = await res.json();

        if (!res.ok || !json.success) {
            throw new Error(json.message || 'Помилка сервера');
        }

        // Show success screen
        document.getElementById('orderFormWrap').style.display = 'none';
        const success = document.getElementById('orderSuccess');
        success.classList.add('show');

        // Use real order ID from server
        document.getElementById('orderNum').textContent = '#' + json.order_id;

        Cart.clear();

    } catch (err) {
        alert('Не вдалося оформити замовлення: ' + err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = 'Замовити';
    }
}

/* ---- Toast notification ---- */
function showToast(name) {
    const toast = document.getElementById('cartToast');
    if (!toast) return;
    toast.querySelector('.toast-name').textContent = name;
    toast.classList.add('show');
    clearTimeout(toast._timer);
    toast._timer = setTimeout(() => toast.classList.remove('show'), 2800);
}

/* ---- Badge bump animation ---- */
function bumpBadge() {
    document.querySelectorAll('.cart-fab-badge, .nav-cart-count').forEach(el => {
        el.classList.remove('bump');
        void el.offsetWidth; // reflow
        el.classList.add('bump');
    });
}

/* ---- Init on DOM ready ---- */
document.addEventListener('DOMContentLoaded', () => {
    Cart.init();

    // Overlay click → close
    document.getElementById('cartOverlay')?.addEventListener('click', closeCart);
    document.getElementById('orderModalOverlay')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeOrderModal();
    });

    // Order form submit
    document.getElementById('orderForm')?.addEventListener('submit', submitOrder);

    // "Add to cart" buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            Cart.add({
                id:    btn.dataset.id,
                name:  btn.dataset.name,
                price: parseFloat(btn.dataset.price),
                img:   btn.dataset.img
            });
        });
    });
});
