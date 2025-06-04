<?php
// Di bagian atas file PHP (sebelum HTML)
session_start();

// Fungsi untuk handle upload file
function handleFileUpload($file)
{
    $uploadDir = 'uploads/bukti_pembayaran/';
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Buat direktori jika belum ada
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Validasi file
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error saat upload file.'];
    }

    if ($file['size'] > $maxFileSize) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB.'];
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Format file tidak didukung. Hanya JPG, PNG, dan PDF yang diperbolehkan.'];
    }

    // Generate nama file unik
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'bukti_' . date('YmdHis') . '_' . uniqid() . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;

    // Pindahkan file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'message' => 'File berhasil diupload.', 'file_path' => $filePath, 'file_name' => $fileName];
    } else {
        return ['success' => false, 'message' => 'Gagal menyimpan file.'];
    }
}

// Handle form submission
$upload_result = null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_order'])) {
    // Validasi input
    $nama = trim($_POST['nama']);
    $nomor_meja = $_POST['nomor_meja'];
    $catatan = trim($_POST['catatan']);
    $metode_pembayaran = $_POST['metode_pembayaran'];

    if (empty($nama) || empty($nomor_meja) || empty($metode_pembayaran)) {
        $error_message = 'Semua field wajib harus diisi.';
    } else {
        // Handle upload jika metode pembayaran QRIS
        if ($metode_pembayaran === 'qris') {
            if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_NO_FILE) {
                $error_message = 'Bukti pembayaran harus diupload untuk metode QRIS.';
            } else {
                $upload_result = handleFileUpload($_FILES['bukti_pembayaran']);
                if (!$upload_result['success']) {
                    $error_message = $upload_result['message'];
                }
            }
        }

        // Jika tidak ada error, proses pesanan
        if (empty($error_message)) {
            // Simpan data pesanan ke database atau session
            $order_data = [
                'nama' => $nama,
                'nomor_meja' => $nomor_meja,
                'catatan' => $catatan,
                'metode_pembayaran' => $metode_pembayaran,
                'items' => $_SESSION['cart'],
                'total' => calculateGrandTotal(),
                'waktu_pesan' => date('Y-m-d H:i:s'),
                'bukti_pembayaran' => ($upload_result && $upload_result['success']) ? $upload_result['file_path'] : null
            ];

            // Simpan ke database (contoh)
            // saveOrderToDatabase($order_data);

            // Redirect ke halaman konfirmasi
            $_SESSION['order_success'] = $order_data;
            header('Location: order_confirmation.php');
            exit();
        }
    }
}

// Handle AJAX upload untuk preview file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_upload'])) {
    header('Content-Type: application/json');

    if (isset($_FILES['bukti_pembayaran'])) {
        $result = handleFileUpload($_FILES['bukti_pembayaran']);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'message' => 'Tidak ada file yang diupload.']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kopi Ngelak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Bagian rincian pesanan tetap sama -->
        <section class="order-section">
            <h2><i class="bi bi-receipt me-2"></i>Rincian Pesanan</h2>
            <!-- ... kode rincian pesanan ... -->
        </section>

        <section class="order-section">
            <h2><i class="bi bi-person-fill me-2"></i>Informasi Pemesanan</h2>

            <form method="post" action="" class="checkout-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama"><i class="bi bi-person me-1"></i>Nama</label>
                    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama Anda"
                        value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="nomor_meja"><i class="bi bi-table me-1"></i>Nomor Meja</label>
                    <select id="nomor_meja" name="nomor_meja" required>
                        <option value="">Pilih Nomor Meja</option>
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($_POST['nomor_meja']) && $_POST['nomor_meja'] == $i) ? 'selected' : ''; ?>>
                                Meja <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="catatan"><i class="bi bi-chat-text me-1"></i>Catatan Tambahan</label>
                    <textarea id="catatan" name="catatan" rows="3"
                        placeholder="Catatan khusus untuk pesanan Anda (opsional)"><?php echo isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label><i class="bi bi-credit-card me-1"></i>Metode Pembayaran</label>
                    <div class="payment-methods">
                        <div class="payment-option <?php echo (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'tunai') ? 'selected' : ''; ?>"
                            data-method="tunai">
                            <div class="payment-icon">💵</div>
                            <div style="font-weight: 500;">Tunai</div>
                        </div>
                        <div class="payment-option <?php echo (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'qris') ? 'selected' : ''; ?>"
                            data-method="qris">
                            <div class="payment-icon">📱</div>
                            <div style="font-weight: 500;">QRIS</div>
                        </div>
                    </div>

                    <!-- Detail Pembayaran Tunai -->
                    <div id="tunai-details"
                        class="payment-details <?php echo (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'tunai') ? 'active' : ''; ?>">
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
                    <div id="qris-details"
                        class="payment-details <?php echo (isset($_POST['metode_pembayaran']) && $_POST['metode_pembayaran'] === 'qris') ? 'active' : ''; ?>">
                        <div class="alert alert-warning">
                            <h5><i class="bi bi-qr-code me-2"></i>Pembayaran QRIS</h5>
                            <p>Scan QR Code di bawah ini dengan aplikasi mobile banking atau e-wallet Anda:</p>
                        </div>

                        <!-- QR Code tetap sama -->
                        <div class="qr-code">
                            <!-- ... kode QR Code ... -->
                        </div>

                        <div style="margin-top: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                <i class="bi bi-cloud-upload me-1"></i>Upload Bukti Pembayaran <span
                                    style="color: red;">*</span>
                            </label>

                            <!-- Area upload dengan handling PHP -->
                            <div class="upload-area" onclick="document.getElementById('bukti-pembayaran').click()">
                                <i class="bi bi-cloud-upload"
                                    style="font-size: 2rem; color: #8B4513; display: block; margin-bottom: 0.5rem;"></i>
                                <p style="margin: 0; color: #666;">Klik untuk upload screenshot bukti pembayaran</p>
                                <small style="color: #999;">Format: JPG, PNG, PDF (Max 5MB)</small>
                            </div>

                            <input type="file" id="bukti-pembayaran" name="bukti_pembayaran" class="file-input"
                                accept="image/*,.pdf" onchange="previewFile(this)">

                            <!-- Container untuk preview -->
                            <div id="preview-container">
                                <?php if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK): ?>
                                    <div style="margin-top: 1rem; text-align: center;">
                                        <p style="color: #8B4513; font-weight: 500;">
                                            <i class="bi bi-check-circle text-success me-1"></i>
                                            File: <?php echo htmlspecialchars($_FILES['bukti_pembayaran']['name']); ?>
                                        </p>
                                        <?php if (strpos($_FILES['bukti_pembayaran']['type'], 'image/') === 0): ?>
                                            <img src="data:<?php echo $_FILES['bukti_pembayaran']['type']; ?>;base64,<?php echo base64_encode(file_get_contents($_FILES['bukti_pembayaran']['tmp_name'])); ?>"
                                                class="preview-image" alt="Preview"
                                                style="max-width: 200px; max-height: 200px;">
                                        <?php else: ?>
                                            <i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: #dc3545;"></i>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="metode_pembayaran" name="metode_pembayaran"
                        value="<?php echo isset($_POST['metode_pembayaran']) ? htmlspecialchars($_POST['metode_pembayaran']) : ''; ?>"
                        required>
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

    <script>
        // Simplified JavaScript - hanya untuk UI handling
        const paymentOptions = document.querySelectorAll('.payment-option');
        const paymentDetails = document.querySelectorAll('.payment-details');
        const metodeInput = document.getElementById('metode_pembayaran');

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
            });
        });

        // Preview file function
        function previewFile(input) {
            const file = input.files[0];
            const previewContainer = document.getElementById('preview-container');

            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    input.value = '';
                    previewContainer.innerHTML = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = `
                        <div style="margin-top: 1rem; text-align: center;">
                            <p style="color: #8B4513; font-weight: 500;">
                                <i class="bi bi-check-circle text-success me-1"></i>
                                File: ${file.name}
                            </p>
                            ${file.type.startsWith('image/') ?
                            `<img src="${e.target.result}" class="preview-image" alt="Preview" style="max-width: 200px; max-height: 200px;">` :
                            `<i class="bi bi-file-earmark-pdf" style="font-size: 2rem; color: #dc3545;"></i>`
                        }
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
            }
        }

        // Form validation
        document.querySelector('.checkout-form').addEventListener('submit', function (e) {
            if (!metodeInput.value) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return;
            }

            if (metodeInput.value === 'qris') {
                const buktiInput = document.getElementById('bukti-pembayaran');
                if (!buktiInput.files.length) {
                    e.preventDefault();
                    alert('Silakan upload bukti pembayaran untuk metode QRIS.');
                    return;
                }
            }
        });
    </script>
</body>

</html>