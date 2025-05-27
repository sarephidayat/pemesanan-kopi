// Fungsi untuk fetch data menu dari API
export const fetchMenu = async (kategori) => {
    
    try {
        const response = await fetch (`http://localhost/web/TA/api/menu.php?category=${kategori}`);
        
        // Cek apakah response berhasil
        if (!response.ok) throw new Error('Gagal mengambil data');
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        return [];
    }
};

// Fungsi untuk fetch cart dari localStorage/session
export const getCart = () => {
    return JSON.parse(localStorage.getItem('cart')) || [];
};

export const addToCartAPI = (menuId, menuData) => {
    const cart = getCart();
    const existingItem = cart.find(item => item.id === menuId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: menuId,
            name: menuData.name,
            price: menuData.price,
            quantity: 1,
            image: menuData.image
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    return cart;
};

export const removeFromCart = (menuId) => {
    const cart = getCart().filter(item => item.id !== menuId);
    localStorage.setItem('cart', JSON.stringify(cart));
    return cart;
};

// Dalam api.js
export const updateCartItemQuantity = (menuId, newQuantity) => {
    const cart = getCart();
    const item = cart.find(item => item.id === menuId);
    
    if (item) {
        item.quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    
    return cart;
};

