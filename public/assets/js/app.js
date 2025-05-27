import { fetchMenu, getCart } from './api.js';
import { addToCartAPI } from './api.js';

// Render menu berdasarkan kategori
const renderMenu = async (category, elementId) => {
    const container = document.getElementById(elementId);
    container.innerHTML = '<div class="loading">Memuat menu...</div>';

    try {
        const { data: menus } = await fetchMenu(category);
        
        if (menus.length === 0) {
            container.innerHTML = '<p>Tidak ada menu tersedia</p>';
            return;
        }

        container.innerHTML = menus.map(menu => `
            <div class="menu-item" data-id="${menu.kode_kategori}">
                <div class="item-image">
                    <img src="../image/${menu.image}" alt="${menu.nama}">
                </div>
                <div class="item-info">
                    <div class="item-name">${menu.nama}</div>
                    <div class="item-price">Rp ${menu.harga.toLocaleString('id-ID')}</div>
                    <div class="item-description">${menu.deskripsi || '-'}</div>
                    <button class="add-to-cart" data-id="${menu.kode_menu}">
                        Tambah ke Pesanan
                   </button>
                </div>
            </div> 
        `).join('');

        // Tambahkan event listener untuk tombol
        document.querySelectorAll(`#${elementId} .add-to-cart`).forEach(button => {
            button.addEventListener('click', addToCart);
        });

    } catch (error) {
        container.innerHTML = '<p class="error">Gagal memuat menu</p>';
    }
};

// Fungsi untuk menambah item ke keranjang
const addToCart = async (e) => {
    // const menuId = e.target.getAttribute('data-id');
    // const menuItem = e.target.closest('.menu-item');
    
    try {
        // 1. Ambil ID produk
        const menuId = e.target.getAttribute('data-id');
        
        // 2. Ambil elemen menu terkait
        const menuItem = e.target.closest('.menu-item');
        
        // 3. Ambil data lain yang diperlukan
        const menuData = {
            name: menuItem.querySelector('.item-name').textContent,
            price: parseInt(menuItem.querySelector('.item-price').textContent.replace(/\D/g, '')),
            image: menuItem.querySelector('.item-image img').src
        };

        // Tambahkan ke cart
        addToCartAPI(menuId, menuData);
        
        // Update tampilan
        updateCartUI();
        
        // Beri feedback ke user
        e.target.textContent = '✓ Ditambahkan';
        e.target.style.backgroundColor = '#4CAF50';
        
        setTimeout(() => {
            e.target.textContent = 'Tambah ke Pesanan';
            e.target.style.backgroundColor = '';
        }, 2000);
        
    } catch (error) {
        console.error('Gagal menambahkan ke keranjang:', error);
        alert('Gagal menambahkan item ke keranjang');
    }
};

// Update tampilan keranjang
const updateCartUI = () => {
    const cart = getCart(); // Ambil data cart dari localStorage atau memory
    const cartContainer = document.getElementById('cart-container');
    const emptyCartMsg = document.getElementById('empty-cart-msg');
    const checkoutBtn = document.getElementById('checkout-btn');
    const cartTotal = document.getElementById('cart-total');
    const totalPriceEl = document.getElementById('total-price');

    if (!cartContainer || !emptyCartMsg || !checkoutBtn || !cartTotal || !totalPriceEl) {
        console.error("Elemen HTML yang dibutuhkan tidak ditemukan.");
        return;
    }

    if (cart.length === 0) {
        cartContainer.innerHTML = '';
        cartContainer.style.display = 'none';
        emptyCartMsg.style.display = 'block';
        checkoutBtn.style.display = 'none';
        cartTotal.style.display = 'none';
    } else {
        emptyCartMsg.style.display = 'none';
        cartContainer.style.display = 'block';

        // Render item ke dalam keranjang
        cartContainer.innerHTML = cart.map(item => `
            <div class="cart-item" data-id="${item.id}">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-quantity">Qty: ${item.quantity}</div>
                <div class="cart-item-price">Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</div>
            </div>
        `).join('');

        // Hitung total harga
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        totalPriceEl.textContent = `Rp ${total.toLocaleString('id-ID')}`;

        // Tampilkan elemen terkait
        cartTotal.style.display = 'flex';
        checkoutBtn.style.display = 'inline-block';

        // Tambahkan event listener jika ada (optional)
        // addCartItemEventListeners(); // aktifkan kalau kamu punya fungsi ini
    }
};


const addCartItemEventListeners = () => {
    // Tombol plus
    document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const itemId = e.target.closest('.cart-item').getAttribute('data-id');
            const cart = getCart();
            const item = cart.find(item => item.id === itemId);
            
            if (item) {
                updateCartItemQuantity(itemId, item.quantity + 1);
                updateCartUI();
            }
        });
    });
    
    // Tombol minus
    document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const itemId = e.target.closest('.cart-item').getAttribute('data-id');
            const cart = getCart();
            const item = cart.find(item => item.id === itemId);
            
            if (item && item.quantity > 1) {
                updateCartItemQuantity(itemId, item.quantity - 1);
            } else {
                removeFromCart(itemId);
            }
            updateCartUI();
        });
    });
    
    // Tombol hapus
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const itemId = e.target.closest('.cart-item').getAttribute('data-id');
            removeFromCart(itemId);
            updateCartUI();
        });
    });
};

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Load menu
    renderMenu('MKN-2156', 'food-items');
    renderMenu('MNM-3821', 'drink-items');
    renderMenu('DST-7294', 'dessert-items');

    // Load keranjang
    updateCartUI();
});

