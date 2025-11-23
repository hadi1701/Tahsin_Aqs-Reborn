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


$stmt = db()->prepare('SELECT * FROM daftar');
$stmt->execute();
$rowDaftar = $stmt->fetchAll(db()::FETCH_ASSOC);
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

          
        <?php include '../component/nav-admin.php'?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
            <div class="content-wrapper">
            <!-- Content -->

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h2 class="mb-0">Daftar Peserta</h2>
                                    <a href="add_peserta.php" class="btn btn-success"><i class="fa fa-plus"></i>Tambah Data</a>
                                </div>
                                <div class="card-body">
                                    <div class="col-12">
                                        <table class="table table-bordered">
                                            <thead class="table-primary">
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    <th>Email</th>
                                                    <th>Nama Lengkap</th>
                                                    <th>Usia</th>
                                                    <th>No. WhatsApp</th>
                                                    <th>Asal Komunitas</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach ($rowDaftar as $key): ?>
                                                    <tr>
                                                        <td class="text-center"><?= $i ?></td>
                                                        <td><?= htmlspecialchars($key['email']) ?></td>
                                                        <td><?= htmlspecialchars($key['nama']) ?></td>
                                                        <td><?= htmlspecialchars($key['usia']) ?></td>
                                                        <td><?= htmlspecialchars($key['no_wa']) ?></td>
                                                        <td><?= htmlspecialchars($key['komunitas']) ?></td>
                                                        <td class="d-flex text-center d-inline gap-2">
                                                            <button type="button" class="btn btn-sm btn-icon btn-primary btn-update" data-id="<?= $key['id'] ?>">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-icon btn-danger btn-delete" data-id="<?= $key['id'] ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
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
    
    <!-- Modal Update -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
        
        <!-- Header -->
        <div class="modal-header" style="background-color: #000;">
            <h5 class="modal-title text-warning fw-bold d-flex align-items-center" id="modalUpdateLabel">Edit Data Registrasi</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        
        <!-- Body -->
        <div class="modal-body" style="background-color: #f8f9fa;">
            <form id="formUpdate">
                <div class="mb-3">
                    <label for="id" class="form-label fw-semibold text-dark">No</label>
                    <input type="text" id="id" name="id" class="form-control border-dark" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold text-dark">Email</label>
                    <input type="text" id="email" name="email" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold text-dark">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="usia" class="form-label fw-semibold text-dark">Usia</label>
                    <input type="number" id="usia" name="usia" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">Gender</label><br>
                    <div class="form-check form-check-inline">
                    <input type="radio" name="gender" id="updategenderL" value="Laki-laki" class="form-check-input">
                    <label class="form-check-label text-dark" for="updategenderL">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" name="gender" id="updategenderP" value="Perempuan" class="form-check-input">
                    <label class="form-check-label text-dark" for="updategenderP">Perempuan</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="no_wa" class="form-label fw-semibold text-dark">No. WhatsApp</label>
                    <input type="text" id="no_wa" name="no_wa" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="komunitas" class="form-label fw-semibold text-dark">Asal Komunitas</label>
                    <input type="text" id="komunitas" name="komunitas" class="form-control border-dark">
                </div>

                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn px-3" style="background-color: #f4c542; color: #000; font-weight: 300;">Simpan Perubahan</button>
                </div>
                </form>
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
    
    <script src="../module/js/setDaftar.js"></script>

</body>
</html>
