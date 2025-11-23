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

if (!isset($_GET['daftar_id']) || !isset($_GET['class_id'])) {
    die("Parameter tidak lengkap.");
}


$daftar_id = intval($_GET['daftar_id']);
$class_id  = intval($_GET['class_id']);

// ambil data murid
$stmt = db()->prepare("SELECT * FROM daftar WHERE id = ?");
$stmt->execute([$daftar_id]);
$murid = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$murid) {
    die("Murid tidak ditemukan.");
}

// ambil progress murid
$stmt = db()->prepare("
    SELECT * 
    FROM progress 
    WHERE daftar_id = ? AND class_id = ?
");
$stmt->execute([$daftar_id, $class_id]);
$progress = $stmt->fetchAll(PDO::FETCH_ASSOC);

// hitung progress done
$totalHurufDiperiksa = count($progress);
$jumlahSudahBagus = 0;

foreach ($progress as $p) {
    if ($p['status'] === 'done') $jumlahSudahBagus++;
}

$percent = $totalHurufDiperiksa > 0 
    ? round(($jumlahSudahBagus / $totalHurufDiperiksa) * 100) 
    : 0;
?>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-light"
  data-assets-path="../sneat/assets/"
  data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard Admin</title>

    <meta name="description" content="" />

    <link rel="icon" type="image/x-icon" href="../sneat/assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../sneat/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../sneat/assets/css/demo.css" />

    <link rel="stylesheet" href="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/libs/apex-charts/apex-charts.css" />

    <script src="../sneat/assets/vendor/js/helpers.js"></script>
    <script src="../sneat/assets/js/config.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
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
                                    <h3>Detail Murid</h3>
                                    <hr>

                                    <h5>Profil</h5>
                                    <table class="table table-bordered" style="max-width:400px;">
                                        <tr><th>Nama</th><td><?= htmlspecialchars($murid['nama']) ?></td></tr>
                                        <tr><th>Usia</th><td><?= htmlspecialchars($murid['usia']) ?></td></tr>
                                        <tr><th>No WA</th><td><?= htmlspecialchars($murid['no_wa']) ?></td></tr>
                                    </table>
                                </div>
                                <div class="card-body">
                                    <hr>
                                    <h5>Progress Bacaan</h5>

                                    <div class="mb-3">
                                        <div class="progress" style="max-width:400px;">
                                            <div 
                                                class="progress-bar" 
                                                role="progressbar" 
                                                style="width: <?= $percent ?>%;">
                                                <?= $percent ?>%
                                            </div>
                                        </div>
                                        <small class="progress-info">
                                            <?= $jumlahSudahBagus ?> done dari <?= $totalHurufDiperiksa ?>
                                        </small>
                                    </div>

                                    <?php if ($totalHurufDiperiksa == 0): ?>
                                        <p>Belum ada progress.</p>
                                    <?php else: ?>

                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th>Materi</th>
                                                <th>Catatan Pengajar</th>
                                                <th>Solusi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($progress as $p): ?>
                                            <tr>

                                                <td><?= htmlspecialchars($p['material']) ?></td>
                                                <td><?= htmlspecialchars($p['notes']) ?></td>
                                                <td><?= htmlspecialchars($p['solution']) ?></td>

                                                <td>
                                                    <span id="badge-<?= $p['id'] ?>"
                                                        class="badge <?= $p['status'] === 'done' ? 'bg-success' : 'bg-danger' ?>">
                                                        <?= $p['status'] ?>
                                                    </span>
                                                </td>

                                                <td class="d-flex d-inline gap-2">
                                                    
                                                    <button class="btn btn-sm btn-primary toggle-progress"
                                                            data-id="<?= $p['id'] ?>">
                                                        Update
                                                    </button>

                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                            data-id="<?= $p['id'] ?>">
                                                        Hapus
                                                    </button>
                                                </td>

                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>

                                    <?php endif; ?>

                                    <br>
                                    <a class="btn btn-secondary" href="kelas.php?id=<?= $class_id ?>">Kembali</a>
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

<!-- SNEAT JS -->
<script src="../sneat/assets/vendor/libs/jquery/jquery.js"></script>
<script src="../sneat/assets/vendor/libs/popper/popper.js"></script>
<script src="../sneat/assets/vendor/js/bootstrap.js"></script>
<script src="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../sneat/assets/vendor/js/menu.js"></script>
<script src="../sneat/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="../sneat/assets/js/main.js"></script>
<script src="../sneat/assets/js/dashboards-analytics.js"></script>

<script src="../module/js/setDetail.js"></script>

</body>
</html>
