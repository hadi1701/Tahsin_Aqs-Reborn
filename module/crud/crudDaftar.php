<?php
// crudDaftar.php — versi FIX: respons status konsisten, aman, dan siap untuk SweetAlert
session_start();
require_once '../dbconnect.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

try {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'];

    // ===================== READ =====================
    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT id, username, email, nama, usia, gender, no_wa, komunitas, created_at FROM daftar WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query("SELECT id, username, email, nama, usia, gender, no_wa, komunitas, created_at FROM daftar ORDER BY id DESC");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
        exit;
    }

    // ===================== CREATE =====================
    if ($method === 'POST') {
        $id        = trim($_POST['id'] ?? '');
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $nama      = trim($_POST['nama'] ?? '');
        $usia      = trim($_POST['usia'] ?? '');
        $gender    = $_POST['gender'] ?? '';
        $no_wa     = trim($_POST['no_wa'] ?? '');
        $komunitas = trim($_POST['komunitas'] ?? '');

        // === INSERT (baru) ===
        if ($id === '') {

            $errors = [];

            // Required fields
            if ($username === '') $errors['username'] = 'Username wajib diisi';
            if ($email === '') $errors['email'] = 'Email wajib diisi';
            if ($password === '') $errors['password'] = 'Password wajib diisi';
            if ($nama === '') $errors['nama'] = 'Nama lengkap wajib diisi';
            if ($usia === '') $errors['usia'] = 'Usia wajib diisi';
            if ($gender === '') $errors['gender'] = 'Jenis kelamin wajib dipilih';
            if ($no_wa === '') $errors['no_wa'] = 'Nomor WhatsApp wajib diisi';
            if ($komunitas === '') $errors['komunitas'] = 'Komunitas wajib dipilih';

            // Validasi format
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format email tidak valid';
            }

            $cleanNoWa = preg_replace('/[^0-9]/', '', $no_wa);
            if ($no_wa !== '' && (strlen($cleanNoWa) < 10 || strlen($cleanNoWa) > 13)) {
                $errors['no_wa'] = 'Nomor WhatsApp harus 10–13 digit angka (contoh: 081234567890)';
            }
            if ($no_wa !== '' && !preg_match('/^(62|08|8)/', $cleanNoWa)) {
                $errors['no_wa'] = 'Nomor WhatsApp harus diawali 08 atau 62';
            }

            $usiaInt = (int)$usia;
            if ($usia !== '' && ($usiaInt < 5 || $usiaInt > 70)) {
                $errors['usia'] = 'Usia harus antara 5–70 tahun';
            }

            if ($nama !== '' && strlen($nama) < 3) {
                $errors['nama'] = 'Nama minimal 3 huruf';
            }

            if (strlen($password) < 6) {
                $errors['password'] = 'Password minimal 6 karakter';
            }

            // ✅ Kembalikan validation_error jika ada error
            if (!empty($errors)) {
                echo json_encode([
                    'status' => 'validation_error',
                    'message' => 'Mohon lengkapi data berikut:',
                    'errors' => $errors
                ]);
                exit;
            }

            // === Cek Duplikat ===
            $stmt = $pdo->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM daftar WHERE username = ?) AS u,
                    (SELECT COUNT(*) FROM daftar WHERE email = ?) AS e,
                    (SELECT COUNT(*) FROM daftar WHERE no_wa = ?) AS w
            ");
            $stmt->execute([$username, $email, $cleanNoWa]);
            $dup = $stmt->fetch(PDO::FETCH_ASSOC);

            $dupErrors = [];
            if ($dup['u'] > 0) $dupErrors['username'] = 'Username sudah terdaftar';
            if ($dup['e'] > 0) $dupErrors['email'] = 'Email sudah digunakan';
            if ($dup['w'] > 0) $dupErrors['no_wa'] = 'Nomor WhatsApp sudah terdaftar';

            if (!empty($dupErrors)) {
                echo json_encode([
                    'status' => 'duplicate',
                    'message' => 'Maaf, data berikut sudah digunakan oleh peserta lain:',
                    'errors' => $dupErrors
                ]);
                exit;
            }

            // === Simpan Data ===
            $pdo->beginTransaction();
            try {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO daftar (username, email, password, nama, usia, gender, no_wa, komunitas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $hashed, $nama, $usiaInt, $gender, $cleanNoWa, $komunitas]);
                $userId = $pdo->lastInsertId();

                $stmtPay = $pdo->prepare("INSERT INTO pembayaran (user_id, is_paid, is_active) VALUES (?, 0, 0)");
                $stmtPay->execute([$userId]);

                $_SESSION['user_id'] = (int)$userId;
                $pdo->commit();

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Pendaftaran berhasil! Silakan lanjut ke halaman pembayaran.',
                    'user_id' => $userId
                ]);
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                // Cek unique violation (race condition)
                $msg = $e->getMessage();
                $dup = [];
                if (strpos($msg, 'uniq_username') !== false) $dup['username'] = 'Username sudah terdaftar';
                if (strpos($msg, 'uniq_email') !== false) $dup['email'] = 'Email sudah digunakan';
                if (strpos($msg, 'uniq_no_wa') !== false) $dup['no_wa'] = 'Nomor WhatsApp sudah terdaftar';

                if (!empty($dup)) {
                    echo json_encode([
                        'status' => 'duplicate',
                        'message' => 'Maaf, data berikut sudah digunakan oleh peserta lain:',
                        'errors' => $dup
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Gagal menyimpan data. Silakan coba lagi nanti.'
                    ]);
                }
                exit;
            }
        }

        // === UPDATE (optional, bisa dihapus jika hanya daftar baru) ===
        else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Fitur perbarui data tidak tersedia untuk pendaftaran.'
            ]);
            exit;
        }
    }

    // ===================== DELETE (opsional) =====================
    if ($method === 'DELETE') {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = (int)($input['id'] ?? 0);

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        try {
            $pdo->beginTransaction();
            $pdo->prepare("DELETE FROM pembayaran WHERE user_id = ?")->execute([$id]);
            $pdo->prepare("DELETE FROM daftar WHERE id = ?")->execute([$id]);
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data. Silakan coba lagi.']);
        }
        exit;
    }

    // Metode tidak didukung
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    error_log("CRUD Daftar Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi gangguan sistem. Tim kami sedang memperbaiki. Silakan coba beberapa saat lagi.'
    ]);
    exit;
}