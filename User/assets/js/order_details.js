        // Handler untuk metode pembayaran
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function () {
                // Hapus kelas selected dari semua metode
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                });

                // Tambahkan kelas selected ke metode yang dipilih
                this.classList.add('selected');

                // Set nilai input hidden
                document.getElementById('payment_method').value = this.dataset.method;
            });
        });

        // Validasi form sebelum submit
        document.querySelector('.checkout-form').addEventListener('submit', function (e) {
            const paymentMethod = document.getElementById('payment_method').value;
            if (!paymentMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran!');
                return false;
            }
        });