<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            width: 1200px;
        }
    </style>
</head>

<body onload=dataMenu()>
    <div class="container">
        <canvas id="myChart"></canvas>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"
        integrity="sha512-aub0tRfsNTyfYpvUs0e9G/QRsIDgKmm4x59WRkHeWUc3CXbdiMwiMQ5tTSElshZu2LCq8piM/cbIsNwuuIR4gA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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
                        options: {}
                    };

                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                }
            })
        }



    </script>

</body>

</html>