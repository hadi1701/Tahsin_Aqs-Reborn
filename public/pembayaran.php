<?php
require_once "../module/dbconnect.php";

if (!isset($_SESSION)) session_start();

$success = '';
$error = '';

// ---------------------------
// 1️⃣ Validasi login user
// ---------------------------
if (!isset($_SESSION['user_id'])) {
    $error = 'Anda belum bisa login. Silakan ke halaman pembayaran terlebih dahulu.';
}

// ---------------------------
// 2️⃣ Aksi: Admin validasi pembayaran
// URL contoh: pembayaran.php?action=update&id=5
// ---------------------------
if (isset($_GET['action']) && $_GET['action'] === 'update' && isset($_GET['id'])) {
    $pdo = db();
    $userId = $_GET['id'];

    // Cek apakah data pembayaran ada
    $stmt = $pdo->prepare("SELECT * FROM pembayaran WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Update status pembayaran
        $update = $pdo->prepare("UPDATE pembayaran SET is_paid = 1, is_active = 1 WHERE user_id = ?");
        $update->execute([$userId]);

        echo "<script>alert('Status pembayaran dan aktivasi berhasil diperbarui!'); window.location.href='../public/validasi.php';</script>";
        exit;
    } else {
        echo "<script>alert('Data pembayaran tidak ditemukan!'); window.location.href='../public/validasi.php';</script>";
        exit;
    }
}

// ---------------------------
// 3️⃣ Aksi: User upload bukti transfer
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = db();

        if (!isset($_SESSION['user_id'])) {
            $error = 'User belum login.';
        } else {
            $userId = $_SESSION['user_id'];

            // Cek file upload
            if (!isset($_FILES['bayar']) || $_FILES['bayar']['error'] !== UPLOAD_ERR_OK) {
                $error = 'File belum dipilih atau gagal diupload!';
            } else {
                $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
                $ext = strtolower(pathinfo($_FILES['bayar']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $error = 'Hanya file JPG, PNG, atau PDF yang diizinkan!';
                } else {
                    $targetDir = "../img/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                    $filename = uniqid('bayar_') . '.' . $ext;
                    $targetFile = $targetDir . $filename;

                    if (!move_uploaded_file($_FILES['bayar']['tmp_name'], $targetFile)) {
                        $error = 'Gagal menyimpan file!';
                    } else {
                        // Cek apakah user sudah pernah upload sebelumnya
                        $check = $pdo->prepare("SELECT COUNT(*) FROM pembayaran WHERE user_id = ?");
                        $check->execute([$userId]);
                        $exists = $check->fetchColumn();

                        if ($exists) {
                            $stmt = $pdo->prepare("UPDATE pembayaran SET is_paid = 1, is_active = 0, foto = ? WHERE user_id = ?");
                            $stmt->execute([$filename, $userId]);
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO pembayaran (user_id, is_paid, is_active, foto) VALUES (?, 1, 0, ?)");
                            $stmt->execute([$userId, $filename]);
                        }

                        $success = 'Bukti pembayaran berhasil diupload. Menunggu validasi Admin.';
                    }
                }
            }
        }

    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "../component/navbar.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Pembayaran</h2>

                        <!-- Alert pesan -->
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Form Upload -->
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="aksi" value="upload_bukti">

                            <div class="mb-3">
                                <p class="text-muted small">
                                    Terima kasih telah mendaftar.<br>
                                    Silakan transfer biaya pendaftaran ke:<br>
                                    <strong>No. Rekening: 72304912 a.n. Fulan bin Fulan</strong>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label for="bayar" class="form-label fw-semibold">Upload Bukti Bayar</label>
                                <input type="file" name="bayar" id="bayar" class="form-control" required>
                                <div class="form-text">File yang diperbolehkan: JPG, PNG, atau PDF</div>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted small">
                                    <i>Setelah mengirim formulir ini, silakan tunggu validasi dari admin.<br>
                                    Anda dapat login setelah pembayaran disetujui.</i>
                                </p>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "../component/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
</body>
</html>
