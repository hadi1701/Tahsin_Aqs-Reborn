<?php
ob_start();
session_start();
require_once '../dbconnect.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo json_encode(['status' => 'error', 'message' => 'Username atau password kosong']);
        exit;
    }

    // reset session lama
    session_unset();
    session_regenerate_id(true); //cegah fixation

    /* ==========================
       CEK LOGIN ADMIN
    ========================== */
    $qAdmin = db()->prepare("SELECT * FROM admin WHERE username = :u LIMIT 1");
    $qAdmin->execute([':u' => $username]);
    $admin = $qAdmin->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in']    = true;
        $_SESSION['admin_id']           = $admin['id'];
        $_SESSION['username']           = $admin['username'];
        $_SESSION['is_admin']           = true;

        // fingerprint session
        $_SESSION['browser_fingerprint'] = md5(
            $_SERVER['HTTP_USER_AGENT'] . ($_SERVER['REMOTE_ADDR'] ?? '')
        );

        session_regenerate_id(true); //ID baru setelah login
        echo json_encode(['status' => 'admin']);
        exit;
    }

    /* ==========================
       CEK LOGIN USER
    ========================== */
    $qUser = db()->prepare("SELECT * FROM daftar WHERE username = :u LIMIT 1");
    $qUser->execute([':u' => $username]);
    $user = $qUser->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Username atau password salah']);
        exit;
    }

    // simpan session user
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id']        = $user['id'];
    $_SESSION['username']       = $user['username'];
    $_SESSION['is_admin']       = false;

    // fingerprint proteksi anti URL copy-paste
    $_SESSION['browser_fingerprint'] = md5(
        ($_SERVER['HTTP_USER_AGENT'] ?? '') .
        ($_SERVER['REMOTE_ADDR'] ?? '')
    );

    session_regenerate_id(true);

    /* ==========================
       CEK STATUS PEMBAYARAN
    ========================== */
    $pay = db()->prepare("SELECT is_paid, is_active FROM pembayaran WHERE user_id = :id LIMIT 1");
    $pay->execute([':id' => $user['id']]);
    $payment = $pay->fetch(PDO::FETCH_ASSOC);

    // user baru, record pembayaran tidak ada
    if (!$payment) {
        echo json_encode(['status' => 'unpaid']);
        exit;
    }

    // belum bayar
    if ((int)$payment['is_paid'] === 0) {
        echo json_encode(['status' => 'unpaid']);
        exit;
    }

    // sudah bayar tapi menunggu validasi
    if ((int)$payment['is_paid'] === 1 && (int)$payment['is_active'] === 0) {
        echo json_encode(['status' => 'waiting']);
        exit;
    }

    // sudah bayar dan aktif
    if ((int)$payment['is_paid'] === 1 && (int)$payment['is_active'] === 1) {
        echo json_encode(['status' => 'user']);
        exit;
    }

    // fallback
    echo json_encode(['status' => 'error', 'message' => 'Status pembayaran tidak diketahui']);
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
