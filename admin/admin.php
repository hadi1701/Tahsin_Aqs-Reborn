<?php

// set cookie parameter
session_set_cookie_params([
    'lifetime' => 0, //habis saaat browser ditutup
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict' //proteksi akses tab baru + URL paste
]);

session_start();

// Cek session admin
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../public/login.php');
    exit;
}

// Proteksi session hijacking
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

require_once "../public/bootstrap.php";
require_once $_SESSION["dir_root"] . '/module/dbconnect.php';
$site_root = $_SESSION["site_root"];

$pdo = db();



// Ambil data admin
$stmt = $pdo->prepare("SELECT username FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $admin['username'] ?? 'Admin';
$role = $admin['role'] ?? 'admin';

//ambil statistik  real
//total pendaftar
$stmtUser = $pdo->prepare("SELECT COUNT(*) FROM daftar");
$stmtUser->execute();
$total_user = (int) $stmtUser->fetchColumn(); 

//santri aktif ngambil dari is_active di table pembayaran
$stmtActive = $pdo->prepare("SELECT COUNT(*) FROM daftar d JOIN pembayaran p ON d.id = p.user_id WHERE p.is_active = 1");
$stmtActive->execute(); // 🔥 WAJIB!
$user_aktif = (int) $stmtActive->fetchColumn();

//menunggu validasi
$stmtValidasi = $pdo->prepare("SELECT COUNT(*) FROM daftar d JOIN pembayaran p ON d.id = p.user_id WHERE p.is_paid = 1 AND p.is_active = 0");
$stmtValidasi->execute();
$user_pending = (int) $stmtValidasi->fetchColumn();

//jumlah murid mica dan umum
$stmtKomunitas = $pdo->prepare("SELECT SUM(CASE WHEN komunitas = 'MICA' THEN 1 ELSE 0 END) AS MICA,
SUM(CASE WHEN komunitas = 'UMUM' THEN 1 ELSE 0 END) AS UMUM FROM daftar");
$stmtKomunitas->execute();
$program = $stmtKomunitas->fetch(PDO::FETCH_ASSOC);
$program_mica = (int) $program['MICA'] ?? 0;
$program_umum = (int) $program['UMUM'] ?? 0;
?>

<!DOCTYPE html>
<html
  lang="id"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-light"
  data-assets-path="../sneat/assets/"
  data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard Admin • Tahsin Aqsyanna</title>
    <meta name="description" content="Panel admin program Tahsin Aqsyanna" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../sneat/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../sneat/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../sneat/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../sneat/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../sneat/assets/vendor/libs/apex-charts/apex-charts.css" />

    <script src="../sneat/assets/vendor/js/helpers.js"></script>
    <script src="../sneat/assets/js/config.js"></script>

</head>

<body data-core="menu">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            <?php include '../component/sidebar.php'; ?>
            <!-- /Sidebar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include "../component/nav-admin.php"; ?>
                <!-- /Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Header -->
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="fw-bold py-3 mb-4">
                                    <span class="text-muted fw-light">Dashboard /</span> Admin
                                </h4>
                                <div class="alert alert-primary alert-dismissible" role="alert">
                                    <i class="bx bx-info-circle me-2"></i>
                                    Assalamu'alaikum, <strong><?= htmlspecialchars($username) ?></strong> 👋
                                    <br>
                                    Selamat datang di dashboard admin Tahsin Aqsyanna. Hari ini ada <strong><?= $user_pending ?></strong> pendaftar menunggu validasi.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="me-3">
                                                <p class="text-muted mb-1">Total Santri</p>
                                                <h4 class="mb-1"><?= number_format($total_user) ?></h4>
                                                <small class="text-success fw-medium">
                                                    <i class='bx bx-trending-up'></i> <?= round($total_user > 0 ? ($user_aktif / $total_user) * 100 : 0) ?>% aktif
                                                </small>
                                            </div>
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- santri aktif -->
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="me-3">
                                                <p class="text-muted mb-1">Santri Aktif</p>
                                                <h4 class="mb-1"><?= number_format($user_aktif) ?></h4>
                                                <small class="text-success fw-medium">
                                                    <i class='bx bx-check-circle'></i> Sedang menjalani program
                                                </small>
                                            </div>
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-user-check"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Menunggu validasi -->
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="me-3">
                                                <p class="text-muted mb-1">Menunggu Validasi</p>
                                                <h4 class="mb-1"><?= number_format($user_pending) ?></h4>
                                                <small class="text-warning fw-medium">
                                                    <i class='bx bx-time-five'></i> Sudah bayar, belum divalidasi
                                                </small>
                                            </div>
                                            <div class="avatar flex-shrink-0">
                                                <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-hourglass"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Komunitas — versi single-value -->
                            <div class="col-lg-3 col-md-6">
                              <div class="card h-100">
                                <div class="card-body">
                                  <div class="d-flex justify-content-between">
                                    <div class="me-3">
                                      <p class="text-muted mb-1">Komunitas</p>
                                      <div class="mb-1">
                                        <small class="fw-medium text-primary"><i class='bx bxs-circle'></i> MICA:</small>
                                        <strong><?= number_format($program_mica) ?></strong>
                                      </div>
                                      <div>
                                        <small class="fw-medium text-success"><i class='bx bxs-circle'></i> UMUM:</small>
                                        <strong><?= number_format($program_umum) ?></strong>
                                      </div>
                                      <small class="text-muted mt-1 d-block">
                                        Total: <?= number_format($program_mica + $program_umum) ?>
                                      </small>
                                    </div>
                                    <div class="avatar flex-shrink-0">
                                      <span class="avatar-initial rounded bg-label-info"><i class="bx bx-group"></i></span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>

                        <!-- Charts & Actions -->
                        <div class="row">
                            <!-- Chart -->
                            <div class="col-md-8">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Progres Santri per Jenjang</h5>
                                        <small class="text-muted">Bulan ini</small>
                                    </div>
                                    <div class="card-body">
                                        <div id="progressChart"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">Aksi Cepat</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="validasi.php" class="btn btn-primary text-white">
                                                <i class="bx bx-user-check me-2"></i>Validasi Pendaftar
                                                <span class="badge bg-light text-primary ms-2"><?= $user_pending ?></span>
                                            </a>
                                            <a href="daftar_peserta.php" class="btn btn-outline-secondary">
                                                <i class="bx bx-list-ul me-2"></i>Daftar Santri
                                            </a>
                                            <a href="kelas.php" class="btn btn-outline-info">
                                                <i class="bx bx-edit-alt me-2"></i>Update Progres
                                            </a>
                                            <a href="daftar_admin.php" class="btn btn-outline-success">
                                                <i class="bx bx-file me-2"></i>Daftar Admin
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                                        <a href="activity.php" class="text-muted">Lihat Semua</a>
                                    </div>
                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Ahmad Fauzi</strong> mendaftar program Tahsin
                                                    <br><small class="text-muted">2 menit lalu</small>
                                                </div>
                                                <span class="badge bg-label-warning">Baru</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Siti Nurhaliza</strong> menyelesaikan jenjang 2
                                                    <br><small class="text-muted">15 menit lalu</small>
                                                </div>
                                                <span class="badge bg-label-success">Selesai</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Admin</strong> memvalidasi 5 pendaftar
                                                    <br><small class="text-muted">1 jam lalu</small>
                                                </div>
                                                <span class="badge bg-label-primary">Validasi</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2">
                            <div>
                                © <script>document.write(new Date().getFullYear())</script>,
                                <a href="https://aqsyanna.id" target="_blank" class="fw-bold">Tahsin Aqsyanna</a>
                            </div>
                            <div>
                                Built with ❤️ using <a href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/" target="_blank">Sneat Admin</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../sneat/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../sneat/assets/vendor/libs/popper/popper.js"></script>
    <script src="../sneat/assets/vendor/js/bootstrap.js"></script>
    <script src="../sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../sneat/assets/vendor/js/menu.js"></script>

    <!-- Vendors JS -->
    <script src="../sneat/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../sneat/assets/js/main.js"></script>

    <!-- Page JS -->
    <script>
        // Chart Progres per Jenjang
        document.addEventListener("DOMContentLoaded", function () {
            const progressChart = new ApexCharts(document.querySelector("#progressChart"), {
                chart: { type: 'bar', height: 350, toolbar: { show: false } },
                plotOptions: { bar: { horizontal: false, columnWidth: '45%', borderRadius: 4 } },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                series: [{
                    name: 'Rata-rata (%)',
                    data: [78, 65, 52, 38, 22] // Sesuaikan dengan data real
                }],
                xaxis: {
                    categories: ['Jenjang 1', 'Jenjang 2', 'Jenjang 3', 'Jenjang 4', 'Jenjang 5'],
                    labels: { style: { colors: '#8A8D93' } }
                },
                yaxis: {
                    title: { text: '%' },
                    max: 100,
                    labels: { formatter: val => val + '%' }
                },
                fill: { opacity: 1 },
                colors: ['#7367F0'],
                tooltip: {
                    y: { formatter: val => val + '%' }
                },
                grid: { borderColor: '#e0e0e0', strokeDashArray: 3 }
            });
            progressChart.render();
        });
    </script>

</body>
</html>