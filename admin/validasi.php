<?php
session_set_cookie_params([
    'lifetime' => 0, //habis saaat browser ditutup
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict' //proteksi akses tab baru + URL paste
]);

session_start();

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../public/login.php');
    exit;
}

$expected_fingerprint = md5(
    ($_SERVER['HTTP_USER_AGENT'] ?? '') .
    ($_SERVER['REMOTE_ADDR'] ?? '')
);

if (
    empty($_SESSION['browser_fingerprint']) ||
    $_SESSION['browser_fingerprint'] !== $expected_fingerprint
) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

// Ambil data pembayaran + nama user dari tabel daftar
$stmt = db()->prepare('
    SELECT p.id, p.user_id, p.is_paid, p.is_active, p.foto, d.nama
    FROM pembayaran p
    JOIN daftar d ON p.user_id = d.id
');
$stmt->execute();
$pembayaranData = $stmt->fetchAll(db()::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html 
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../sneat/assets/"
    data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>Validasi Pembayaran</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../sneat/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../sneat/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../sneat/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../sneat/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../sneat/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../sneat/assets/vendor/js/helpers.js"></script>

    <script src="../sneat/assets/js/config.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript" src="../module/js/darkmode.js" defer></script>


    <style>
        .table thead.table-dark th {
        color: #fff !important;
        }
    </style>
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <?php include '../component/sidebar.php'?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          
        <?php include "../component/nav-admin.php" ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
            <div class="content-wrapper">
            <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="">Validasi Pembayaran</h3>
                                </div>
                            
                                <div class="card-body">
                                    <?php if (isset($_GET['msg'])): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
                                    <?php endif; ?>
                                    
                                    <table class="table table-bordered">
                                        <thead class="table-primary ">
                                            <tr class="text-center text-white">
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>Foto</th>
                                                <th>Status Paid</th>
                                                <th>Status Active</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php if (empty($pembayaranData)): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Belum ada data pembayaran.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach($pembayaranData as $row): ?>
                                                <tr class="text-center">

                                                    <td class="text-center"><?=  $i ?> </td>
                                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                                    <td>
                                                        <?php if($row['foto']): ?>
                                                            <a href="../img/pembayaran/<?= htmlspecialchars($row['foto']) ?>" target="_blank">
                                                                <img src="../img/pembayaran/<?= htmlspecialchars($row['foto']) ?>" alt="Bukti" width="60" class="rounded shadow-sm">
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['is_paid']): ?>
                                                            <span class="badge bg-primary">Sudah</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Belum</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['is_active']): ?>
                                                            <span class="badge bg-primary">Aktif</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Nonaktif</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <!-- Tombol Update -->
                                                        <button class="btn btn-sm btn-success btn-validasi" data-id="<?= $row['user_id'] ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>">
                                                        Update
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php $i++; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                </div>
                <div>
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                  <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                  <a
                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    >Documentation</a
                  >

                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link me-4"
                    >Support</a
                  >
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../sneat/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../sneat/assets/vendor/libs/popper/popper.js"></script>
    <script src="../sneat/assets/vendor/js/bootstrap.js"></script>
    <script src="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../sneat/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../sneat/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../sneat/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../sneat/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="../module/js/setDaftar.js"></script>

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</body>
</html>
