<?php
require_once '../module/dbconnect.php';
session_start();

// Ambil 10 santri pertama
$stmt = db()->prepare("SELECT id, nama FROM daftar LIMIT 10");
$stmt->execute();
$santriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    foreach ($_POST['progress'] as $user_id => $data) {
        $query = db()->prepare("INSERT INTO progress_santri (user_id, pertemuan_ke, materi, catatan)
                                VALUES (?, ?, ?, ?)");
        $query->execute([
            $user_id,
            $data['pertemuan_ke'],
            $data['materi'],
            $data['catatan']
        ]);
    }
    echo "<script>alert('Progress berhasil disimpan!');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <title>Input Progress Santri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
    <h3 class="mb-4 text-primary">Input Progress 10 Santri</h3>

    <form method="POST">
        <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama Santri</th>
                <th>Pertemuan Ke</th>
                <th>Materi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($santriList as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['nama']) ?></td>
                    <td><input type="number" name="progress[<?= $s['id'] ?>][pertemuan_ke]" class="form-control" required></td>
                    <td><input type="text" name="progress[<?= $s['id'] ?>][materi]" class="form-control" required></td>
                    <td><input type="text" name="progress[<?= $s['id'] ?>][catatan]" class="form-control"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" name="submit" class="btn btn-success">Simpan Progress</button>
    </form>
    </div>

</body>
</html>
