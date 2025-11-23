<?php
session_set_cookie_params([
    'lifetime' => 0, //habis saaat browser ditutup
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict' //proteksi akses tab baru + URL paste
]);

session_start();

//cek login admin
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
 
// Ambil semua kelas untuk dropdown
$stmt = db()->prepare("SELECT id, name FROM classes ORDER BY name ASC");
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title><?= htmlspecialchars($nama_kelas) ?></title>
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
                                    <h3>Manajemen Kelas</h3>

                                    <!-- Dropdown pilih kelas -->
                                    <div class="mb-3">
                                        <label>Pilih Kelas</label>
                                        <select id="selectKelas" class="form-control" style="max-width:300px;">
                                            <option value="">-- Pilih kelas --</option>
                                            <?php foreach($classes as $key): ?>
                                                <option value="<?= $key['id'] ?>"><?= htmlspecialchars($key['name']) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <!-- Search murid -->
                                    <div class="mb-3">
                                        <label>Cari Murid (belum masuk kelas):</label>
                                        <input type="text" id="searchMurid" class="form-control" placeholder="Ketik nama murid">
                                        <div id="searchResult" class="mt-2"></div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h4>Daftar Murid Dalam Kelas</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Usia</th>
                                                <th>No WA</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableMurid">
                                            <tr><td colspan="4" class="text-center">Silakan pilih kelas</td></tr>
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
    
    <!-- Modal Update -->
    <div class="modal fade" id="modalProgress" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Progress Murid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formProgress">

                    <input type="hidden" id="progress_daftar_id">
                    <input type="hidden" id="progress_class_id">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Session Number</label>
                            <input type="number" class="form-control" id="progress_session">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">Materi</label>
                            <input type="text" class="form-control" id="progress_material">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="progress_notes" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Solusi</label>
                        <textarea class="form-control" id="progress_solution" rows="2"></textarea>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSaveProgress">Simpan</button>
            </div>

            </div>
        </div>
    </div>

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
    
    <script src="../module/js/setKelas.js"></script>
    <script src="../module/js/setProgress.js"></script>
</body>
</html>