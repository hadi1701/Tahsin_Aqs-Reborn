<?php
ob_start();
require_once '../dbconnect.php';
header('Content-Type: application/json');

try {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'];

    // ===================== READ =====================
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT id, username, nama, usia, gender, no_wa, komunitas FROM daftar WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("SELECT id, username, nama, usia, gender, no_wa, komunitas FROM daftar ORDER BY id DESC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // ===================== CREATE / UPDATE =====================
    if ($method === 'POST') {
        $id        = $_POST['id'] ?? '';
        $username  = trim($_POST['username'] ?? '');
        $password  = $_POST['password'] ?? '';
        $nama      = trim($_POST['nama'] ?? '');
        $usia      = trim($_POST['usia'] ?? '');
        $gender    = $_POST['gender'] ?? '';
        $no_wa     = trim($_POST['no_wa'] ?? '');
        $komunitas = trim($_POST['komunitas'] ?? '');

        // ===================== VALIDASI =====================
        if ($id === '') {
            // INSERT
            if (in_array('', [$username, $password, $nama, $usia, $gender, $no_wa, $komunitas], true)) {
                echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap!']);
                exit;
            }

            // Cek username sudah ada
            $cek = $pdo->prepare("SELECT COUNT(*) FROM daftar WHERE username = ?");
            $cek->execute([$username]);
            if ($cek->fetchColumn() > 0) {
                echo json_encode(['status' => 'error', 'message' => 'Username sudah terdaftar!']);
                exit;
            }

            // Hash password sebelum simpan
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            //simpan ke table daftar
            $stmt = $pdo->prepare("INSERT INTO daftar (username, password, nama, usia, gender, no_wa, komunitas) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed, $nama, $usia, $gender, $no_wa, $komunitas]);

            //ambil id user terakhir
            $userId = $pdo->lastInsertId();

            //set session untuk user baru
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $userId;

            //Tambahkan otomatis ke table roles (defaultnya user)
            $stmtRole = $pdo->prepare("INSERT INTO roles (user_id, roles) VALUES (?, 'user')");
            $stmtRole->execute([$userId]);

            //Tambahkan otomatis ke table pembayaran
            $stmtPay = $pdo->prepare("INSERT INTO pembayaran (user_id, is_paid, is_active, foto) VALUES (?, 0, 0, ?)");
            $stmtPay->execute([$userId, null]);

            echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil']);
            exit;

        } else {
            // UPDATE
            if (empty($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan untuk update']);
                exit;
            }

            if (trim($password) === '') {
                // update tanpa ubah password
                $stmt = $pdo->prepare("UPDATE daftar SET username=?, nama=?, usia=?, gender=?, no_wa=?, komunitas=? WHERE id=?");
                $stmt->execute([$username, $nama, $usia, $gender, $no_wa, $komunitas, $id]);
            } else {
                // update + hash password baru
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE daftar SET username=?, password=?, nama=?, usia=?, gender=?, no_wa=?, komunitas=? WHERE id=?");
                $stmt->execute([$username, $hashed, $nama, $usia, $gender, $no_wa, $komunitas, $id]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diperbarui']);
            exit;
        }
    }

    // ===================== DELETE (via METHOD DELETE) =====================
    if ($method === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
            exit;
        }

        //hapus relasi FK dlu di table pembayaran dan roles
        $stmt1 = $pdo->prepare("DELETE FROM pembayaran WHERE user_id = ?");
        $stmt1->execute([$id]);
        $stmt2 = $pdo->prepare("DELETE FROM roles WHERE user_id = ?");
        $stmt2->execute([$id]);

        //baru hapus dari table daftar
        $stmt3 = $pdo->prepare("DELETE FROM daftar WHERE id = ?");
        $stmt3->execute([$id]);

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
