<?php
session_set_cookie_params([
    'lifetime' => 0, //habis saaat browser ditutup
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
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

$stmt = db()->prepare('SELECT * FROM admin');
$stmt->execute();
$rowAdmin = $stmt->fetchAll(db()::FETCH_ASSOC);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
                                <h3>Tambah Data Admin</h3>
                            </div>
                            <div class="card-body">
                                <div class="col-12 mb-3">
                                    
                                    <form id="formAdmin" class="mb-3" action="" method="POST">    
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                                        </div>
    
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                        </div>
    
                                        
                                        <div class="mb-3 form-password-toggle">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" required>
                                            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                          </div>
                                        </div>
    
                                        <div class="mb-3">
                                            <label class="form-label" for="nama">Nama Lengkap</label>
                                            <input type="text" id="nama" class="form-control" placeholder="Enter your name" name="nama" required>
                                        </div>
    
                                        
                                        <div class="mb-3">
                                            <label class="form-label" for="usia">Usia</label>
                                            <input type="text" id="usia" class="form-control" placeholder="Enter your age" name="usia" required>
                                        </div>
    
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kelamin</label><br>
                                            <input id="genderL" type="radio" name="gender" value="Laki-Laki" require> Pria
                                            <input id="genderP" type="radio" name="gender" value="Perempuan" class="ms-3" required> Wanita
                                        </div>
    
                                        <div class="mb-3">
                                            <label class="form-label" for="no_wa">No. WhatsApp</label>
                                            <input type="text" id="no_wa" class="form-control" name="no_wa" placeholder="Enter your number" required>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" id="btn-submit-admin" class="btn btn-primary d-grid w-30 mt-4">Tambah</button>
                                        </div>
                                    </form>
                                </div>
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

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="../module/js/setAdmin.js"></script>

</body>
</html>
