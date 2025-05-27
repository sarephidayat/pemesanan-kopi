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
  </div>
</section>

<?php
require_once '../layout/_bottom.php';
?>