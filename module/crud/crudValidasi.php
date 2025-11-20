<?php
require_once "../dbconnect.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $id = $_POST['id'];

    $stmt = db()->prepare("UPDATE pembayaran SET is_paid = 1, is_active = 1 WHERE user_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['status' => 'success', 'message' => 'Validasi berhasil']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
