# Pemesanan Kopi

Aplikasi berbasis web untuk memudahkan proses pemesanan kopi di cafe. Sistem ini mencakup fitur pelanggan, admin, dan kasir untuk mengelola pemesanan, pembayaran, serta data menu dan laporan.

## 🚀 Fitur Utama

- 🛒 Pemesanan menu oleh pelanggan
- 💰 Sistem pembayaran (Tunai, QRIS)
- 📋 Manajemen menu oleh admin
- 📊 Laporan penjualan berbasis chart
- 🔒 Login multi-role (admin, pelanggan, kasir)
- 📱 Interface responsif & user-friendly

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP (Native, Procedural)
- **Frontend:** HTML5, CSS3, JavaScript
- **Database:** MySQL
- **Library:** 
  - Chart.js (Visualisasi data)
  - Bootstrap (Framework CSS responsif)

## 📦 Struktur Folder
    pemesanan-kopi/
    ├── 📁 admin/                    # Panel admin
    │   ├── 📁 assets/              # File CSS, JS, gambar admin
    │   ├── 📁 dashboard/           # Halaman dashboard admin
    │   ├── 📁 data/                # Data management
    │   ├── 📁 dessert/             # Management menu dessert
    │   ├── 📁 helper/              # Helper functions
    │   ├── 📁 layout/              # Layout components
    │   ├── 📁 makanan/             # Management menu makanan
    │   ├── 📁 minuman/             # Management menu minuman
    │   ├── 📁 pelanggan/           # Management data pelanggan
    │   ├── 📁 pemesanan/           # Management pemesanan
    │   ├── 📁 profile/             # Profile management
    │   ├── 📄 index.php            # Halaman utama admin
    │   └── 📄 logout.php           # Logout admin
    ├── 📁 user/                     # Panel pelanggan
    │   ├── 📁 assets/              # File CSS, JS, gambar user
    │   ├── 📁 image/               # Gambar produk
    │   ├── 📁 img/                 # Image assets
    │   ├── 📁 uploads/             # File upload user
    │   ├── 📄 index.php            # Halaman utama user
    │   ├── 📄 logout.php           # Logout user
    │   ├── 📄 order_confirmation.php # Konfirmasi pesanan
    │   └── 📄 order_details.php    # Detail pesanan
    ├── 📄 index.html               # Landing page
    ├── 📄 login.php                # Halaman login
    └── 📄 README.md                # Dokumentasi project

## 🧑‍💻 Cara Instalasi

### 1. Clone Repositori
    git clone https://github.com/sarephidayat/pemesanan-kopi.git

### 2. Pindahkan ke Folder Server Lokal
Pindahkan folder project ke direktori server lokal Anda:
  XAMPP: C:\xampp\htdocs\pemesanan-kopi
  Laragon: C:\laragon\www\pemesanan-kopi

### 3. Import Database

Buka phpMyAdmin di browser (http://localhost/phpmyadmin)
Buat database baru dengan nama: db_pemesanan_kopi
Import file SQL dari database/db_pemesanan_kopi.sql

### 4. Jalankan Aplikasi
Akses aplikasi melalui browser:
    ```bash
    http://localhost/pemesanan-kopi

## 🔑 Akun Default
### 1. Role Pelanggan
      Username : sarephidayatt_
      Password : qwert
### 2. Role Admin
      Username : 23080960032
      Password : Admin123

## 📋 Persyaratan Sistem
1. PHP 7.4 atau lebih tinggi
2. MySQL 5.7 atau lebih tinggi
3. Web server (Apache/Nginx)
4. Browser modern dengan JavaScript enabled

## 🧑‍💻 Developer
<table>
  <tr>
    <td align="center">
      <a href="https://github.com/sarephidayat">
        <img src="https://raw.githubusercontent.com/sarephidayat/Aplikasi-Peminjaman-Alat/main/src/image/MetaStudioPhoto-122%20-%20Copy.jpg?s=460&v=4" width="120px;" alt="Foto Muhammad Syarifudin Hidayat"/><br />
        <sub><b>Muhammad Syarifudin Hidayat</b></sub>
      </a><br />
      <a href="#" title="Penulis Konten">🖋</a>
    </td>
  </tr>
</table>

