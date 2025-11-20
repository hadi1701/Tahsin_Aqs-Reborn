<?php
session_start();

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

/*
|-------------------------------------------------------------
| HANDLE GET UNTUK TOGGLE STATUS
|-------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] === 'toggle') {

    $id        = intval($_GET['id'] ?? 0);
    $daftar_id = intval($_GET['return'] ?? 0);
    $class_id  = intval($_GET['class_id'] ?? 0);

    if (!$id || !$daftar_id || !$class_id) {
        die("Parameter tidak lengkap.");
    }

    // Ambil status saat ini
    $stmt = db()->prepare("SELECT status FROM progress WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Data tidak ditemukan.");
    }

    // Toggle: pending ↔ done
    $new_status = ($row['status'] === 'done') ? 'pending' : 'done';

    $up = db()->prepare("UPDATE progress SET status = ? WHERE id = ?");
    $up->execute([$new_status, $id]);

    header("Location: ../../admin/detailPeserta.php?daftar_id={$daftar_id}&class_id={$class_id}");
    exit;
}

/*
|-------------------------------------------------------------
| HANDLE GET UNTUK DELETE
|-------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] === 'delete') {

    $id        = intval($_GET['id'] ?? 0);
    $daftar_id = intval($_GET['return'] ?? 0);
    $class_id  = intval($_GET['class_id'] ?? 0);

    if (!$id || !$daftar_id || !$class_id) {
        die("Parameter tidak lengkap.");
    }

    $del = db()->prepare("DELETE FROM progress WHERE id = ?");
    $del->execute([$id]);

    header("Location: ../../admin/detailPeserta.php?daftar_id={$daftar_id}&class_id={$class_id}");
    exit;
}

/*
|-------------------------------------------------------------
| HANDLE POST: GET LAST PROGRESS
|-------------------------------------------------------------
*/
header("Content-Type: application/json");
$action = $_POST['action'] ?? '';

if ($action === 'get') {

    $daftar_id = intval($_POST['daftar_id'] ?? 0);
    $class_id  = intval($_POST['class_id'] ?? 0);

    $stmt = db()->prepare("
        SELECT session_number, material, notes, solution 
        FROM progress
        WHERE daftar_id = ? AND class_id = ?
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->execute([$daftar_id, $class_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row ? ['status' => 'success'] + $row : ['status' => 'empty']);
    exit;
}

/*
|-------------------------------------------------------------
| HANDLE POST: INSERT PROGRESS
|-------------------------------------------------------------
*/
if ($action === 'update') {

    $daftar_id      = intval($_POST['daftar_id'] ?? 0);
    $class_id       = intval($_POST['class_id'] ?? 0);
    $session_number = intval($_POST['session_number'] ?? 0);
    $material       = trim($_POST['material'] ?? '');
    $notes          = trim($_POST['notes'] ?? '');
    $solution       = trim($_POST['solution'] ?? '');

    // Insert baru, default status = pending
    $stmt = db()->prepare("
        INSERT INTO progress 
        (daftar_id, class_id, session_number, material, notes, solution, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->execute([
        $daftar_id,
        $class_id,
        $session_number,
        $material,
        $notes,
        $solution
    ]);

    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
exit;
