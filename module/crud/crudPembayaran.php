<?php
ob_start();
session_start();

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

header('Content-Type: application/json');

$success = '';
$error = '';
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
// 3️⃣ Aksi: User upload bukti transfer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = db();

        // ambil userId dari pending_payment atau user_id
        if (isset($_SESSION['pending_payment'])) {
            $userId = $_SESSION['pending_payment'];
        } elseif (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        } else {
            $error = 'User belum login.';
        }

        if (!$error) {

            // Cek file upload
            if (!isset($_FILES['bayar']) || $_FILES['bayar']['error'] !== UPLOAD_ERR_OK) {
                $error = 'File belum dipilih atau gagal diupload!';
            } else {
                $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
                $ext = strtolower(pathinfo($_FILES['bayar']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowed)) {
                    $error = 'Hanya file JPG, PNG, atau PDF yang diizinkan!';
                } else {

                    $targetDir = "../../img/pembayaran/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }

                    $filename = uniqid('bayar_') . '.' . $ext;
                    $targetFile = $targetDir . $filename;

                    if (!move_uploaded_file($_FILES['bayar']['tmp_name'], $targetFile)) {
                        $error = 'Gagal menyimpan file!';
                    } else {

                        // Cek apakah user pernah upload sebelumnya
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

                        $success = 'Bukti pembayaran berhasil dikirim. Menunggu validasi Admin.';
                    }
                }
            }
        }

    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}


echo json_encode([
    "status" => $error ? "error" : "success",
    "message" => $error ? $error : $success
]);
exit;

