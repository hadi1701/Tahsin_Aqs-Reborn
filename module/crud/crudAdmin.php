<?php
session_start();
require_once '../dbconnect.php';
header('Content-Type: application/json');

try {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'];

    // ===================== READ =====================
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT id, username, email, nama, usia, gender, no_wa FROM admin WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("SELECT id, username, email, nama, usia, gender, no_wa FROM admin ORDER BY id DESC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // ===================== CREATE / UPDATE =====================
    if ($method === 'POST') {
        $id        = $_POST['id'] ?? '';
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $nama      = trim($_POST['nama'] ?? '');
        $usia      = trim($_POST['usia'] ?? '');
        $gender    = $_POST['gender'] ?? '';
        $no_wa     = trim($_POST['no_wa'] ?? '');

        // ===================== VALIDASI =====================
        if ($id === '') {
            // INSERT
            if (in_array('', [$username, $email, $password, $nama, $usia, $gender, $no_wa], true)) {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
                exit;
            }

            // Cek username sudah ada
            $cek = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
            $cek->execute([$username]);
            if ($cek->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Username sudah terdaftar!']);
                exit;
            }

            // Hash password sebelum simpan
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            //simpan ke table daftar
            $stmt = $pdo->prepare("INSERT INTO admin (username, email, password, nama, usia, gender, no_wa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed, $nama, $usia, $gender, $no_wa]);

            //ambil id user terakhir
            $userId = $pdo->lastInsertId();

            //set session untuk user baru
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $userId;

            echo json_encode(['status' => 'success', 'message' => 'Pendaftaran Berhasil!']);
            exit;

        } else {
            // UPDATE

            //mantiin id ada
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan untuk update']);
                exit;
            }

            if (trim($password) === '') {
                // update tanpa ubah password
                $stmt = $pdo->prepare("UPDATE admin SET email=?, nama=?, usia=?, gender=?, no_wa=? WHERE id=?");
                $stmt->execute([$email, $nama, $usia, $gender, $no_wa, $id]);
            } else {
                // update + hash password baru
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE admin SET email=?, password=?, nama=?, usia=?, gender=?, no_wa=? WHERE id=?");
                $stmt->execute([$email, $hashed, $nama, $usia, $gender, $no_wa, $id]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Data Berhasil Diperbarui!']);
            exit;
        }
    }

    // ===================== DELETE (via METHOD DELETE) =====================
    if ($method === 'DELETE') {
        $input= json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
            exit;
        }

        // hapus dari table admin
        $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        exit;
    }

    // ===================== JIKA METHOD TIDAK DIKENAL =====================
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak dikenali']);

} catch (PDOException $e) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
