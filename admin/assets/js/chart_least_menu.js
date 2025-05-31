function leastMenu() {
  $.ajax({
    type: "GET",
    url: "fetchdb.php",
    data: {
      functionName: "getLeastMenu"
    },
    success: function (response) {
      let leastmenu = JSON.parse(response);

      let nama = collect(leastmenu).map(function (item) {
          return item.nama
        }).all();

        let stok = collect(leastmenu).map(function (item) {
          return item.stok
        }).all();
        console.log(nama);

      const data = {
        labels: nama,
        datasets: [{
          label: 'Stok Menu Terendah',
          data: stok,
          backgroundColor: [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#C9CBCF', '#F67019',
            '#00A5CF', '#FFD166' // kamu bisa tambah jika data > 10
          ],
          hoverOffset: 4
        }]
      };

      const config = {
        type: 'doughnut',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  const label = context.label || '';
                  const value = context.formattedValue || 0;
                  return `${label}: ${value}`;
                }
              }
            }
          }
        }
      };

      const chartLeastMenu = new Chart(
        document.getElementById('chartLeastMenu'),
        config
      );
    }
  });
}
