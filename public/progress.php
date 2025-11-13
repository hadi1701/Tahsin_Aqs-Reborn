<?php
require_once '../module/dbconnect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = db()->prepare("SELECT pertemuan_ke, materi, catatan, tanggal_input
                       FROM progress_santri
                       WHERE user_id = ?
                       ORDER BY pertemuan_ke ASC");
$stmt->execute([$user_id]);
$progressList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Progress Bacaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">

    <div class="container mt-5">
    <h3 class="mb-4 text-warning">Progress Bacaan Qur'an Kamu</h3>

    <table class="table table-dark table-striped text-center">
        <thead>
            <tr>
                <th>Pertemuan Ke</th>
                <th>Materi</th>
                <th>Catatan</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($progressList as $key): ?>
            <tr>
                <td><?= $key['pertemuan_ke'] ?></td>
                <td><?= htmlspecialchars($key['materi']) ?></td>
                <td><?= htmlspecialchars($key['catatan']) ?></td>
                <td><?= $key['tanggal_input'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </div>
</body>
</html>
