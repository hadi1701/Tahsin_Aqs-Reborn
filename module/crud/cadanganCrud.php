<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../../module/dbconnect.php';

// ambil method request
$method = $_SERVER['REQUEST_METHOD'];

// ================== CREATE (REGISTER) ==================
if ($method === 'POST' && !isset($_POST['id'])) {
    try {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // password aman
        $nama = $_POST['nama'];
        $usia = $_POST['usia'];
        $gender = $_POST['gender'];
        $no_wa = $_POST['no_wa'];
        $komunitas = $_POST['komunitas'];

        // Simpan ke tabel daftar
        $stmt = db()->prepare("INSERT INTO daftar (username, password, nama, usia, gender, no_wa, komunitas) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $nama, $usia, $gender, $no_wa, $komunitas]);

        $userId = db()->lastInsertId(); // ambil id terakhir

        // Buat juga data di tabel roles dan pembayaran
        $stmtRole = db()->prepare("INSERT INTO roles (user_id, roles) VALUES (?, 'user')");
        $stmtRole->execute([$userId]);

        $stmtPay = db()->prepare("INSERT INTO pembayaran (user_id, is_paid, is_active, foto) VALUES (?, 0, 0, NULL)");
        $stmtPay->execute([$userId]);

        echo json_encode(["status" => "success", "message" => "Registrasi berhasil!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// ================== READ (GET DATA UNTUK UPDATE) ==================
if ($method === 'GET' && isset($_GET['id'])) {
    try {
        $stmt = db()->prepare("SELECT * FROM daftar WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// ================== UPDATE ==================
if ($method === 'POST' && isset($_POST['id'])) {
    try {
        $stmt = db()->prepare("UPDATE daftar SET username=?, nama=?, usia=?, gender=?, no_wa=?, komunitas=? WHERE id=?");
        $stmt->execute([
            $_POST['username'], 
            $_POST['nama'], 
            $_POST['usia'], 
            $_POST['gender'], 
            $_POST['no_wa'], 
            $_POST['komunitas'],
            $_POST['id']
        ]);

        echo json_encode(["status" => "success", "message" => "Data berhasil diperbarui!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// ================== DELETE ==================
if ($method === 'DELETE') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        // hapus data dari tabel yang punya FK juga
        $stmt1 = db()->prepare("DELETE FROM pembayaran WHERE user_id = ?");
        $stmt1->execute([$id]);

        $stmt2 = db()->prepare("DELETE FROM roles WHERE user_id = ?");
        $stmt2->execute([$id]);

        $stmt3 = db()->prepare("DELETE FROM daftar WHERE id = ?");
        $stmt3->execute([$id]);

        echo json_encode(["status" => "success", "message" => "Data berhasil dihapus!"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Request tidak dikenal."]);
