<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Makanan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #5a3e2b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-card {
            border-radius: 8px;
            overflow: hidden;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
        }

        .card-image {
            width: 100%;
            height: 180px;
            background-color: #e0e0e0;
            position: relative;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-content {
            padding: 15px;
        }

        .food-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .food-price {
            font-size: 16px;
            color: #bd5734;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .food-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .add-button {
            background-color: #bd5734;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-button:hover {
            background-color: #a04a2c;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Menu Makanan</h1>
        <div class="menu-grid">
            <!-- Nasi Goreng Spesial -->
            <div class="menu-card">
                <div class="card-image">
                    <img src="/api/placeholder/300/180" alt="Nasi Goreng Spesial">
                </div>
                <div class="card-content">
                    <div class="food-title">Nasi Goreng Spesial</div>
                    <div class="food-price">Rp 35.000</div>
                    <div class="food-description">Nasi goreng dengan telur, ayam, udang, dan sayuran segar</div>
                    <button class="add-button">Tambah ke Pesanan</button>
                </div>
            </div>

            <!-- Mie Goreng -->
            <div class="menu-card">
                <div class="card-image">
                    <img src="img/ayam goreng.jpeg" alt="Mie Goreng">
                </div>
                <div class="card-content">
                    <div class="food-title">Mie Goreng</div>
                    <div class="food-price">Rp 30.000</div>
                    <div class="food-description">Mie goreng dengan telur, ayam, dan sayuran segar</div>
                    <button class="add-button">Tambah ke Pesanan</button>
                </div>
            </div>

            <!-- Ayam Bakar -->
            <div class="menu-card">
                <div class="card-image">
                    <img src="/api/placeholder/300/180" alt="Ayam Bakar">
                </div>
                <div class="card-content">
                    <div class="food-title">Ayam Bakar</div>
                    <div class="food-price">Rp 45.000</div>
                    <div class="food-description">Ayam bakar dengan bumbu khas dan sambal</div>
                    <button class="add-button">Tambah ke Pesanan</button>
                </div>
            </div>

            <!-- Sate Ayam -->
            <div class="menu-card">
                <div class="card-image">
                    <img src="/api/placeholder/300/180" alt="Sate Ayam">
                </div>
                <div class="card-content">
                    <div class="food-title">img/sate.jpeg</div>
                    <div class="food-price">Rp 25.000</div>
                    <div class="food-description">10 tusuk sate ayam dengan bumbu kacang</div>
                    <button class="add-button">Tambah ke Pesanan</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>