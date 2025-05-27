<?php
// Melakukan koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_pemesanan_kopinuri");
// cek koneksi database
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
;

//melakukan query untuk menampilkan data menu
$result = mysqli_query($koneksi, "SELECT * FROM tabel_menu");

$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
$data2 = mysqli_fetch_all($result2, MYSQLI_ASSOC);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        /* CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        header {
            background-color: #5c3d2e;
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

        }

        header .container {
            display: flex;
            justify-content: space-between;

        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            align-items: center;
        }

        /* Main Content */
        main {
            margin: 40px 0;
        }

        .menu-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            color: #5c3d2e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e6e6e6;
        }

        .menu-items {
            display: grid;
            /* flex-wrap: wrap; */
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            justify-content: space-between;
            gap: 20px;
        }

        .menu-item {
            /* border: 1px solid black; */
            width: 250px;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .menu-item:hover {
            transform: translateY(-5px);
        }

        .menu-item .item-image {
            /* border: 1px solid black; */
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;

        }

        .menu-item .item-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .item-image img {
            height: max-content;
            width: 100%;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .item-info {
            margin-top: auto;
        }

        .item-name {
            font-weight: bold;
            font-size: large;
            /* margin-bottom: 5px; */
        }

        .item-price {
            color: #b85c38;
            font-weight: bold;
            font-size: medium;
            margin-bottom: 2px
        }

        .item-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .add-to-cart {
            background-color: #b85c38;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 14px;
            text-align: center;
            display: block;
            margin-bottom: 5px;
        }

        .add-to-cart:hover {
            background-color: #5c3d2e;
        }

        .item-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            height: calc(100% - 180px);
        }

        .item-info form {
            margin-top: auto;
            width: 100%;
        }

        /* Cart Section */
        .cart-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .cart-item-name {
            flex: 1;
        }

        .cart-item-quantity {
            width: 100px;
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            background-color: #e6e6e6;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 4px;
            cursor: pointer;
        }

        .quantity-display {
            margin: 0 10px;
        }

        .cart-item-price {
            width: 100px;
            text-align: right;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 15px 0;
            border-top: 2px solid #e6e6e6;
            margin-top: 10px;
        }

        .checkout-btn {
            background-color: #5c3d2e;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }

        .checkout-btn:hover {
            background-color: #b85c38;
        }

        /* Order Form */
        .order-form {
            display: none;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-order {
            background-color: #5c3d2e;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .submit-order:hover {
            background-color: #b85c38;
        }

        /* Success Message */
        .success-message {
            display: none;
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
        }

        /* Footer */
        footer {
            background-color: #5c3d2e;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-items {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            .cart-item {
                flex-direction: column;
            }

            .cart-item-quantity,
            .cart-item-price {
                width: 100%;
                margin-top: 10px;
                text-align: left;
            }
        }
    </style>
</head>


<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Kopi Nuri</h1>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Menu Makanan -->
        <section class="menu-section ">
            <h2>Menu Makanan</h2>
            <div class="menu-items">
                <?php foreach ($data as $item): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="img/<?php echo $item['img'] ?>" alt="">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo $item['nama'] ?></div>
                            <div class="item-price">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo $item['deskripsi'] ?></div>
                            <form method="post">
                                <input type="hidden" name="item_id" value="">
                                <input type="hidden" name="item_name" value="">
                                <input type="hidden" name="item_price" value="">
                                <button type="submit" name="add_to_cart" class="add-to-cart">Tambah ke Pesanan</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Menu Minuman -->
        <section class="menu-section">
            <h2>Menu Minuman</h2>
            <div class="menu-items">
                <?php foreach ($data2 as $item): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="img/<?php echo $item['img'] ?>" alt="">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo $item['nama'] ?></div>
                            <div class="item-price">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo $item['deskripsi'] ?></div>
                            <form method="post">
                                <input type="hidden" name="item_id" value="">
                                <input type="hidden" name="item_name" value="">
                                <input type="hidden" name="item_price" value="">
                                <button type="submit" name="add_to_cart" class="add-to-cart">Tambah ke Pesanan</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Menu Dessert -->
        <section class="menu-section">
            <h2>Menu Dessert</h2>
            <div class="menu-items">
                <?php foreach ($data2 as $item): ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="img/<?php echo $item['img'] ?>" alt="">
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo $item['nama'] ?></div>
                            <div class="item-price">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                            <div class="item-description"><?php echo $item['deskripsi'] ?></div>
                            <form method="post">
                                <input type="hidden" name="item_id" value="">
                                <input type="hidden" name="item_name" value="">
                                <input type="hidden" name="item_price" value="">
                                <button type="submit" name="add_to_cart" class="add-to-cart">Tambah ke Pesanan</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Keranjang Belanja -->
        <!-- Ganti bagian tombol Checkout di file index.php -->
        <div class="cart-section">
            <h2>Pesanan Anda</h2>

            <?php if (empty($_SESSION['cart'])): ?>
                <p>Keranjang pesanan Anda kosong.</p>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-name"><?php echo $item['name']; ?></div>
                            <div class="cart-item-quantity">
                                x<?php echo $item['quantity']; ?>
                            </div>
                            <div class="cart-item-price">
                                <?php echo formatRupiah($item['price'] * $item['quantity']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-total">
                    <div>Total</div>
                    <div><?php echo formatRupiah(calculateTotal()); ?></div>
                </div>

                <!-- Perubahan di sini, mengarahkan ke halaman detail pemesanan -->
                <a href="order_details.php" class="checkout-btn">Lanjutkan ke Pembayaran</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kafe Express. Semua hak dilindungi.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
</body>

</html>