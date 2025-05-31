function dataMenu() {
    $.ajax({
      type: "GET",
      url: "fetchdb.php",
      data: {
        functionName: "getDataMenu"
      },
      success: function (response) {
        let menu = JSON.parse(response);
        // console.log(menu);

        let nama = collect(menu).map(function (item) {
          return item.nama
        }).all();

        let stok = collect(menu).map(function (item) {
          return item.stok
        }).all();
        console.log(stok);

        const data = {
          labels: nama,
          datasets: [{
            label: 'Stok Menu',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: stok,
          }]
        };

        const config = {
          type: 'bar',
          data: data,
          options: {
            responsive: true,
            plugins: {
              legend: {
                position: 'top',
              },
              tooltip: {
                mode: 'index',
                intersect: false,
              }
            },
            scales: {
              x: {
                ticks: {
                  maxRotation: 0,
                  minRotation: 0,
                  autoSkip: false,
                  font: {
                    size: 7 
                  }
                }
              },
              y: {
                beginAtZero: true
              }
            }
          }

        };

        const myChart = new Chart(
          document.getElementById('myChart'),
          config
        );
      }
    })
  }