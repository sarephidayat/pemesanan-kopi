<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$pelanggan = mysqli_query($connection, "SELECT COUNT(*) FROM tabel_pelanggan");
$makanan = mysqli_query($connection, "SELECT COUNT(*) FROM tabel_menu WHERE kode_kategori = 'MKN-2156'");
$minuman = mysqli_query($connection, "SELECT COUNT(*) FROM tabel_menu WHERE kode_kategori = 'MNM-3821'");
$dessert = mysqli_query($connection, "SELECT COUNT(*) FROM tabel_menu WHERE kode_kategori = 'DST-7294'");

$total_pelanggan = mysqli_fetch_array($pelanggan)[0];
$total_makanan = mysqli_fetch_array($makanan)[0];
$total_minuman = mysqli_fetch_array($minuman)[0];
$total_dessert = mysqli_fetch_array($dessert)[0];
?>

<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <div class="column">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-user"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Pelanggan</h4>
            </div>
            <div class="card-body">
              <?= $total_pelanggan ?>
            </div>
          </div>
        </div>
      </div>


      <!-- Total Jumlah Makanan -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-danger">
            <i class="bi bi-fork-knife text-white fs-5"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Makanan</h4>
            </div>
            <div class="card-body">
              <?= $total_makanan ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Jumlah Minuman -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="bi bi-cup-straw text-white fs-5"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Minuman</h4>
            </div>
            <div class="card-body">
              <?= $total_minuman ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Jumlah Dessert -->
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="bi bi-cookie text-white fs-5"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Dessert</h4>
            </div>
            <div class="card-body">
              <?= $total_dessert ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Column Chart -->
  <div class="column">
    <div class="row ">
      <!-- chart stok menu -->
      <div class="col-lg-6 col-md-6 col-sm-18 col-12 ">
        <div class="card card-statistic-1 bg-white">
          <div class="text-center mt-3 mb-3">
            <h4>Grafik Stok Menu</h4>
          </div>
          <canvas id="myChart"></canvas>
        </div>
      </div>

      <!-- chart harga makanan -->
      <div class="col-lg-6 col-md-6 col-sm-18 col-12 ">
        <div class="card card-statistic-1 bg-white">
          <div class="text-center mt-3 mb-3">
            <h4>Grafik Menu Paling Banyak</h4>
          </div>
          <canvas id="chartMostMenu"></canvas>
        </div>
      </div>

      <!-- chart harga makanan -->
      <div class="col-lg-6 col-md-6 col-sm-18 col-12 ">
        <div class="card card-statistic-1 bg-white">
          <div class="text-center mt-3 mb-3">
            <h4>Grafik Menu Paling Sedikit</h4>
          </div>
          <canvas id="chartLeastMenu"></canvas>
        </div>
      </div>

      <!-- chart harga makanan -->
      <div class="col-lg-6 col-md-6 col-sm-18 col-12 ">
        <div class="card card-statistic-1 bg-white">
          <div class="text-center mt-3 mb-3">
            <h4>Grafik Penjualan</h4>
          </div>
          <canvas id="chartPenjualanMingguan"></canvas>
        </div>
      </div>

    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
  integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js"
  integrity="sha512-aub0tRfsNTyfYpvUs0e9G/QRsIDgKmm4x59WRkHeWUc3CXbdiMwiMQ5tTSElshZu2LCq8piM/cbIsNwuuIR4gA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../assets/js/chart_stok.js"></script>
<script src="../assets/js/chart_most_menu.js"></script>
<script src="../assets/js/chart_least_menu.js"></script>
<script src="../assets/js/chart_penjualan.js"></script>


<?php
require_once '../layout/_bottom.php';
?>