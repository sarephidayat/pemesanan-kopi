<?php
session_start();

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "db_pemesanan_kopi");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Redirect jika cart kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// Fungsi untuk format rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Fungsi untuk menghitung total
function calculateTotal()
{
    $total = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
    }
    return $total;
}

// Fungsi untuk menghitung pajak (10%)
function calculateTax()
{
    return calculateTotal() * 0.1;
}

// Fungsi untuk menghitung grand total
function calculateGrandTotal()
{
    return calculateTotal() + calculateTax();
}

// Proses form pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $nomor_meja = intval($_POST['nomor_meja']);
    $catatan = mysqli_real_escape_string($conn, trim($_POST['catatan']));
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $total_harga = calculateGrandTotal();
    $id_pesanan = 'ORD-' . date('Ymd') . '-' . rand(1000, 9999);
    $bukti_pembayaran = "Tunai";
    $status = "pending";

    // Validasi input
    if (empty($nama) || empty($nomor_meja) || empty($metode_pembayaran)) {
        $error_message = 'Semua field wajib diisi.';
    } else {
        // Jika pembayaran QRIS, maka wajib upload bukti
        if ($metode_pembayaran === 'qris') {
            if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_NO_FILE) {
                $error_message = 'Bukti pembayaran wajib diunggah untuk metode QRIS.';
            } else {
                // Proses upload file
                $target_dir = "./uploads/bukti-pembayaran/";

                // Buat folder jika belum ada
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                $original_name = basename($_FILES["bukti_pembayaran"]["name"]);
                $unique_name = uniqid() . "_" . $original_name;
                $target_file = $target_dir . $unique_name;

                $file_tmp = $_FILES["bukti_pembayaran"]["tmp_name"];
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];

                if (!in_array($file_type, $allowed_types)) {
                    $error_message = "Format file tidak didukung. Gunakan JPG, PNG, atau PDF.";
                } else {
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        // Simpan hanya nama file (tanpa path) ke database
                        $bukti_pembayaran = $unique_name;
                    } else {
                        $error_message = "Gagal mengunggah bukti pembayaran.";
                    }
                }
            }
        }
    }




    if (empty($error_message)) {
        $insert_order = "INSERT INTO tabel_pesan (id_pesanan, nama, total_harga, nomor_meja, catatan, tanggal_pesan, metode_pembayaran, id_pelanggan, bukti_pembayaran, status) 
                         VALUES ('$id_pesanan', '$nama', $total_harga, $nomor_meja, '$catatan', NOW(), '$metode_pembayaran', $id_pelanggan, " . ($bukti_pembayaran ? "'$bukti_pembayaran'" : "Tunai") . ", '$status')";

        if (mysqli_query($conn, $insert_order)) {
            // Insert detail pemesanan
            foreach ($_SESSION['cart'] as $kode_menu => $item) {
                $jumlah = intval($item['quantity']);
                $harga = intval($item['harga']);
                $subtotal = $jumlah * $harga;

                $insert_detail = "INSERT INTO tabel_detail_pesan 
                                  (id_pesanan, kode_menu, jumlah, subtotal) 
                                  VALUES 
                                  ('$id_pesanan', '$kode_menu', $jumlah, $subtotal)";
                mysqli_query($conn, $insert_detail);
            }

            $_SESSION['order_id'] = $id_pesanan;
            $_SESSION['order_details'] = $_SESSION['cart'];
            unset($_SESSION['cart']);

            echo "<script>alert('Pesanan berhasil diproses! Kode pesanan: $id_pesanan'); window.location.href='order_confirmation.php';</script>";
            exit;
        } else {
            $error_message = "Gagal memproses pesanan: " . mysqli_error($conn);
        }
    }


    if (!empty($error_message)) {
        echo "<script>alert('$error_message'); window.history.back();</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan &mdash; Cafe Ngelak</title>
    <link rel="icon" type="image/png" href="../admin/assets/img/logo-removebg.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="assets/css/order_details.css"> -->
    <style>
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
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .logo h1 {
            color: white;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .back-link {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #b85c38;
            text-decoration: underline;
        }

        /* Main Content */
        main {
            margin: 40px 0;
        }

        .order-section {
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

        .order-items {
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .item-name {
            flex: 1;
        }

        .item-quantity {
            width: 100px;
            text-align: center;
        }

        .item-price {
            width: 150px;
            text-align: right;
        }

        .order-summary {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .summary-row.total {
            font-weight: bold;
            border-top: 1px solid #e6e6e6;
            padding-top: 10px;
            margin-top: 10px;
        }

        .checkout-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #5c3d2e;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #5c3d2e;
        }

        .payment-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .payment-option {
            flex: 1;
            min-width: 120px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }

        .payment-option:hover {
            border-color: #b85c38;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(184, 92, 56, 0.2);
        }

        .payment-option.selected {
            border-color: #b85c38;
            background-color: #f8f0ed;
        }

        .payment-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .payment-details {
            display: none;
            margin-top: 1rem;
            padding: 1.5rem;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #5c3d2e;
        }

        .payment-details.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .qr-code {
            text-align: center;
            margin: 1rem 0;
        }

        .qr-image {
            width: 200px;
            height: 200px;
            border: 3px solid #5c3d2e;
            border-radius: 8px;
            margin: 1rem auto;
            display: block;
            background: white;
            padding: 10px;
        }

        .upload-area {
            border: 2px dashed #5c3d2e;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin: 1rem 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .upload-area:hover {
            background: #f8f0ed;
        }

        .upload-area.dragover {
            background: #f8f0ed;
            border-color: #b85c38;
        }

        .file-input {
            display: none;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .submit-order {
            background-color: #5c3d2e;
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .submit-order:hover {
            background-color: #b85c38;
        }

        .submit-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-info {
            background: #E3F2FD;
            border: 1px solid #2196F3;
            color: #1976D2;
        }

        .alert-warning {
            background: #FFF3E0;
            border: 1px solid #FF9800;
            color: #F57C00;
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
            .order-item {
                flex-direction: column;
                padding: 15px 0;
            }

            .item-quantity,
            .item-price {
                width: 100%;
                margin-top: 5px;
                text-align: left;
            }

            .payment-methods {
                flex-direction: column;
            }

            .payment-option {
                min-width: 100%;
            }

            main {
                padding: 0 15px;
            }

            .order-section {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Kopi Ngelak</h1>
            </div>
            <div>
                <a href="index.php" class="back-link" style="font-size: 30px;"><i class="bi bi-box-arrow-left"></i></a>
            </div>
        </div>
    </header>

    <main class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <section class="order-section">
            <h2><i class="bi bi-receipt me-2"></i>Rincian Pesanan</h2>

            <div class="order-items">
                <?php foreach ($_SESSION['cart'] as $kode_menu => $item): ?>
                    <div class="order-item">
                        <div class="item-name"><?php echo htmlspecialchars($item['nama']); ?></div>
                        <div class="item-quantity"><?php echo $item['quantity']; ?> x</div>
                        <div class="item-price"><?php echo formatRupiah($item['harga'] * $item['quantity']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <div>Subtotal</div>
                    <div><?php echo formatRupiah(calculateTotal()); ?></div>
                </div>
                <div class="summary-row">
                    <div>Pajak (10%)</div>
                    <div><?php echo formatRupiah(calculateTax()); ?></div>
                </div>
                <div class="summary-row total">
                    <div>Total</div>
                    <div><?php echo formatRupiah(calculateGrandTotal()); ?></div>
                </div>
            </div>
        </section>

        <section class="order-section">
            <h2><i class="bi bi-person-fill me-2"></i>Informasi Pemesanan</h2>

            <form method="post" action="" class="checkout-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama"><i class="bi bi-person me-1"></i>Nama</label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama Anda">
                </div>

                <div class="form-group">
                    <label for="nomor_meja"><i class="bi bi-table me-1"></i>Nomor Meja</label>
                    <select id="nomor_meja" name="nomor_meja" required>
                        <option value="">Pilih Nomor Meja</option>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="<?php echo $i; ?>">Meja <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="catatan"><i class="bi bi-chat-text me-1"></i>Catatan Tambahan</label>
                    <textarea id="catatan" name="catatan" rows="3"
                        placeholder="Catatan khusus untuk pesanan Anda (opsional)"></textarea>
                </div>

                <div class="form-group">
                    <label><i class="bi bi-credit-card me-1"></i>Metode Pembayaran</label>
                    <div class="payment-methods">
                        <div class="payment-option" data-method="tunai">
                            <div class="payment-icon">💵</div>
                            <div style="font-weight: 500;">Tunai</div>
                        </div>
                        <div class="payment-option" data-method="qris">
                            <div class="payment-icon">📱</div>
                            <div style="font-weight: 500;">QRIS</div>
                        </div>
                    </div>

                    <!-- Detail Pembayaran Tunai -->
                    <div id="tunai-details" class="payment-details">
                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle me-2"></i>Petunjuk Pembayaran Tunai</h5>
                            <ol style="margin: 0; padding-left: 1.5rem;">
                                <li>Silakan siapkan uang tunai sebesar
                                    <strong><?php echo formatRupiah(calculateGrandTotal()); ?></strong>
                                </li>
                                <li>Serahkan pembayaran kepada kasir saat pesanan siap</li>
                                <li>Pastikan uang pas atau siapkan kembalian</li>
                                <li>Simpan struk pembayaran sebagai bukti transaksi</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Detail Pembayaran QRIS -->
                    <div id="qris-details" class="payment-details">
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-qr-code me-2"></i>Pembayaran QRIS</h5>
                            <p>Scan QR Code di bawah ini dengan aplikasi mobile banking atau e-wallet Anda:</p>
                        </div>

                        <div class="qr-code">
                            <img src="./uploads/image/qris.jpg" alt="" style="width: 350px; height: 500px;"
                                class="qr-image">
                            <p style="margin-top: 0.5rem; color: #666;">Total:
                                <strong><?php echo formatRupiah(calculateGrandTotal()); ?></strong>
                            </p>
                        </div>

                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="bi bi-cloud-upload me-1"></i>Upload Bukti Pembayaran
                            </label>
                            <div class="upload-area" onclick="document.getElementById('bukti-pembayaran').click()">
                                <i class="bi bi-cloud-upload"
                                    style="font-size: 2rem; color: #8B4513; display: block; margin-bottom: 0.5rem;"></i>
                                <p style="margin: 0; color: #666;">Klik untuk upload screenshot bukti pembayaran</p>
                                <small style="color: #999;">Format: JPG, PNG, PDF (Max 5MB)</small>
                            </div>
                            <input type="file" id="bukti-pembayaran" name="bukti_pembayaran" class="file-input"
                                accept="image/*,.pdf">
                            <div id="preview-container"></div>
                        </div>
                    </div>
                    <input type="hidden" id="metode_pembayaran" name="metode_pembayaran" value="" required>
                </div>

                <button type="submit" name="process_order" class="submit-order">
                    <i class="bi bi-check-circle me-2"></i>Proses Pesanan
                </button>
            </form>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Kopi Ngelak. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="js/order_details.js"></script>
    <script>
        // Payment method selection
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentDetails = document.querySelectorAll('.payment-details');
        const metodeInput = document.getElementById('metode_pembayaran');
        const submitBtn = document.getElementById('submit-btn');
        const buktiInput = document.getElementById('bukti-pembayaran');
        const uploadArea = document.querySelector('.upload-area');
        const previewContainer = document.getElementById('preview-container');

        paymentOptions.forEach(option => {
            option.addEventListener('click', function () {
                // Remove previous selections
                paymentOptions.forEach(opt => opt.classList.remove('selected'));
                paymentDetails.forEach(detail => detail.classList.remove('active'));

                // Add selection to clicked option
                this.classList.add('selected');
                const method = this.dataset.method;
                metodeInput.value = method;

                // Show corresponding payment details
                const detailElement = document.getElementById(method + '-details');
                if (detailElement) {
                    detailElement.classList.add('active');
                }

                // Enable submit button for cash, check file for QRIS
                if (method === 'tunai') {
                    submitBtn.disabled = false;
                } else if (method === 'qris') {
                    checkQRISRequirements();
                }
            });
        });

        // File upload handling
        buktiInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = `
                        <div style="margin-top: 1rem; text-align: center;">
                            <p style="color: #8B4513; font-weight: 500;">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                File berhasil diupload: ${file.name}
                            </p>
                            ${file.type.startsWith('image/') ?
                            `<img src="${e.target.result}" class="preview-image" alt="Preview">` :
                            `<i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: #dc3545;"></i>`
                        }
                        </div>
                    `;
                };
                reader.readAsDataURL(file);

                checkQRISRequirements();
            }
        });

        // Drag and drop functionality
        uploadArea.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                buktiInput.files = files;
                buktiInput.dispatchEvent(new Event('change'));
            }
        });

        // Check QRIS requirements
        function checkQRISRequirements() {
            if (metodeInput.value === 'qris') {
                submitBtn.disabled = !buktiInput.files.length;
            }
        }

        // Form validation
        document.querySelector('.checkout-form').addEventListener('submit', function (e) {
            if (!metodeInput.value) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return;
            }

            if (metodeInput.value === 'qris' && !buktiInput.files.length) {
                e.preventDefault();
                alert('Silakan upload bukti pembayaran untuk metode QRIS.');
                return;
            }
        });
    </script>
</body>

</html>