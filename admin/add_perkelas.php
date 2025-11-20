<?php
session_start();

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  //jika bukan admin atau belum login, lempar ke login
  header('Location: ../public/login.php');
  exit;
}

$stmtKelas = db()->prepare("SELECT id, name FROM classes WHERE batch_id = 6 AND level_id = 1");
$stmtKelas->execute();
$kelasList = $stmtKelas->fetchAll(db()::FETCH_ASSOC);

$stmt2 = db()->prepare("SELECT * FROM daftar ORDER BY nama ASC");
$stmt2->execute();
$muridList = $stmt2->fetchAll(db()::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Assign Murid ke Kelas</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<h2>Assign Murid ke Kelas</h2>

<select id="murid">
    <option value="">-- Pilih Murid --</option>
    <?php foreach ($muridList as $m): ?>
        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nama']) ?></option>
    <?php endforeach; ?>
</select>

<select id="kelas">
    <option value="">-- Pilih Kelas --</option>
    <?php foreach ($kelasList as $k): ?>
        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['name']) ?></option>
    <?php endforeach; ?>
</select>

<button id="btnAssign">Assign</button>

<hr>

<h3>Daftar Murid & Kelas</h3>

<table border="1" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Murid</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="tableAssign"></tbody>
</table>

<script src="../module/js/setPerkelas.js"></script>

</body>
</html>

