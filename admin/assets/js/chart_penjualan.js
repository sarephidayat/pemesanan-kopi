function penjualanMingguan() {
  $.ajax({
    type: "GET",
    url: "fetchdb.php",
    data: {
      functionName: "getPenjualanMingguIni"
    },
    success: function (response) {
      console.log("RESPONSE RAW DARI PHP:", response); // Tambahkan ini

      let hasil = JSON.parse(response);
      console.log("HASIL PARSE:", hasil); // Tambahkan ini

      let tanggal = hasil.map(item => item.tanggal);
      let total = hasil.map(item => item.total);
      

      const data = {
        labels: tanggal,
        datasets: [{
          label: 'Total Penjualan (7 Hari Terakhir)',
          data: total,
          fill: true,
          borderColor: '#36A2EB',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          tension: 0.3
        }]
      };

      const config = {
        type: 'line',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Rp'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Tanggal'
              }
            }
          }
        }
      };

      const chartPenjualanMingguan = new Chart(
        document.getElementById('chartPenjualanMingguan'),
        config
      );
    }
  });
}


