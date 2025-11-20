<?php
session_start();

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

header('Content-Type: application/json');

// Pastikan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$action = $_POST['action'] ?? '';

/*
|--------------------------------------------------------------------------
| 1. SEARCH MURID (belum masuk kelas)
|--------------------------------------------------------------------------
*/
if ($action === 'search') {

    $keyword  = trim($_POST['keyword'] ?? '');
    $class_id = intval($_POST['class_id'] ?? 0);

    if ($keyword === '' || $class_id <= 0) {
        echo json_encode(['status' => 'empty', 'html' => '']);
        exit;
    }

    // Query cari murid yang NAMANYA mirip dan BELUM masuk kelas ini
    $stmt = db()->prepare("
        SELECT id, nama, usia, no_wa 
        FROM daftar 
        WHERE nama LIKE ? 
        AND id NOT IN (
            SELECT daftar_id FROM class_members WHERE class_id = ?
        )
        ORDER BY nama ASC
    ");

    $stmt->execute(['%'.$keyword.'%', $class_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode([
            'status' => 'not_found',
            'html' => '<div class="alert alert-warning">Tidak ada hasil.</div>'
        ]);
        exit;
    }

    $html = '<ul class="list-group">';

    foreach ($result as $d) {
        $html .= '
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>' . htmlspecialchars($d['nama']) . '</strong><br>
                <small>Usia: ' . htmlspecialchars($d['usia']) . 
                ' • WA: ' . htmlspecialchars($d['no_wa']) . '</small>
            </div>
            <button 
                class="btn btn-sm btn-primary btnAddToClass"
                data-id="' . $d['id'] . '"
            >
                Tambah
            </button>
        </li>';
    }

    $html .= '</ul>';

    echo json_encode(['status' => 'success', 'html' => $html]);
    exit;
}



/*
|--------------------------------------------------------------------------
| 2. GET MEMBERS — ambil semua murid dalam kelas
|--------------------------------------------------------------------------
*/
if ($action === 'getMembers') {

    $class_id = intval($_POST['class_id'] ?? 0);

    if ($class_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'class_id invalid']);
        exit;
    }

    $stmt = db()->prepare("
        SELECT cm.daftar_id, d.nama, d.usia, d.no_wa
        FROM class_members cm
        JOIN daftar d ON cm.daftar_id = d.id
        WHERE cm.class_id = ?
        ORDER BY d.nama ASC
    ");

    $stmt->execute([$class_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        echo json_encode(['status' => 'empty', 'html' => '']);
        exit;
    }

    $html = '';

    foreach ($rows as $key) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($key['nama']) . '</td>
            <td>' . htmlspecialchars($key['usia']) . '</td>
            <td>' . htmlspecialchars($key['no_wa']) . '</td>
            <td>
                <button class="btn btn-primary btn-sm btnProgress" data-id="'. $key['daftar_id'] .'"
                data-class="'. $class_id.'">Progress</button>


                <button class="btn btn-info btn-sm btnDetail" data-id="'. $key['daftar_id'] .'"
                data-class="'. $class_id.'">Detail</button>

                <button 
                    class="btn btn-danger btn-sm btnRemove"
                    data-id="' . $key['daftar_id'] . '"
                >
                    Hapus
                </button>
            </td>
        </tr>';
    }

    echo json_encode(['status' => 'success', 'html' => $html]);
    exit;
}



/*
|--------------------------------------------------------------------------
| 3. ADD MEMBER — masukkan murid ke kelas
|--------------------------------------------------------------------------
*/
if ($action === 'addMember') {

    $daftar_id = intval($_POST['daftar_id'] ?? 0);
    $class_id  = intval($_POST['class_id'] ?? 0);

    if ($daftar_id <= 0 || $class_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'invalid data']);
        exit;
    }

    // Pastikan tidak double
    $check = db()->prepare("SELECT id FROM class_members WHERE daftar_id = ? AND class_id = ?");
    $check->execute([$daftar_id, $class_id]);

    if ($check->fetch()) {
        echo json_encode(['status' => 'exists']);
        exit;
    }

    // Insert
    $stmt = db()->prepare("INSERT INTO class_members (daftar_id, class_id) VALUES (?, ?)");
    $stmt->execute([$daftar_id, $class_id]);

    echo json_encode(['status' => 'success']);
    exit;
}



/*
|--------------------------------------------------------------------------
| 4. REMOVE MEMBER — hapus murid dari kelas
|--------------------------------------------------------------------------
*/
if ($action === 'removeMember') {

    $daftar_id = intval($_POST['daftar_id'] ?? 0);
    $class_id  = intval($_POST['class_id'] ?? 0);

    if ($daftar_id <= 0 || $class_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'invalid data']);
        exit;
    }

    $stmt = db()->prepare("DELETE FROM class_members WHERE daftar_id = ? AND class_id = ?");
    $stmt->execute([$daftar_id, $class_id]);

    echo json_encode(['status' => 'success']);
    exit;
}



echo json_encode(['status' => 'error', 'message' => 'Unknown action']);

