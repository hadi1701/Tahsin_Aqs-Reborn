<?php
session_start();

require_once  "../dbconnect.php";

$action = $_GET['action'] ?? '';

if ($action == 'read') {

    $stmt = db()->prepare("
        SELECT 
            mc.id AS mc_id,
            d.nama AS nama_murid,
            c.name AS nama_kelas
        FROM murid_class mc
        JOIN daftar d ON mc.daftar_id = d.id
        JOIN classes c ON mc.class_id = c.id
        WHERE mc.batch_id = 6
        ORDER BY d.nama ASC
    ");

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $no = 1;
    foreach ($data as $r) {
        echo "
        <tr>
            <td>{$no}</td>
            <td>{$r['nama_murid']}</td>
            <td>{$r['nama_kelas']}</td>
            <td>
                <button class='btnDelete' data-id='{$r['mc_id']}'>Hapus</button>
            </td>
        </tr>
        ";
        $no++;
    }

    exit;
}

if ($action == 'assign') {

    $daftar_id = $_POST['daftar_id'];
    $class_id  = $_POST['class_id'];

    // ambil batch dari classes
    $stmtBatch = db()->prepare("SELECT batch_id FROM classes WHERE id = :id");
    $stmtBatch->execute([':id' => $class_id]);
    $batch = $stmtBatch->fetch(PDO::FETCH_ASSOC)['batch_id'];

    // CEGAR DUPLIKAT
    $check = db()->prepare("SELECT * FROM murid_class WHERE daftar_id = :d AND batch_id = :b");
    $check->execute([':d' => $daftar_id, ':b' => $batch]);

    if ($check->rowCount() > 0) {
        echo json_encode(['status' => 'exists']);
        exit;
    }

    // INSERT
    $stmt = db()->prepare("
        INSERT INTO murid_class (daftar_id, class_id, batch_id)
        VALUES (:daftar_id, :class_id, :batch_id)
    ");

    $stmt->execute([
        ':daftar_id' => $daftar_id,
        ':class_id'  => $class_id,
        ':batch_id'  => $batch
    ]);

    echo json_encode(['status' => 'success']);
    exit;
}

if ($action == 'delete') {
    $id = $_POST['id'];

    $stmt = db()->prepare("DELETE FROM murid_class WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(['status' => 'deleted']);
    exit;
}

echo "Invalid action";
