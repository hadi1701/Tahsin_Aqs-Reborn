<?php
if(!isset($_SESSION)){
    session_start();
}

require_once '../module/dbconnect.php';

$stmt = db()->prepare('SELECT * FROM daftar');
$stmt -> execute();
$rowDaftar = $stmt->fetchAll(db()::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    <div class="d-flex justify-content-center align-items-center vh-100 mb-2">
        <form id="formDaftar" class="container border p-4 rounded shadow col-3" >
            <h2 align="center" class="mb-3">Registrasi</h2>
            <div class="form-group mb-3" >
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Fulan">
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password Akun Ini">
            </div>
            <div class="form-group mb-3">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" id="nama">
            </div>
            <div class="form-group mb-3">
                <label for="usia">Usia</label>
                <input type="text" class="form-control" name="usia" id="usia">
            </div>
            <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label> <br>
                    <input id="genderL" type="radio" name="gender" value="Laki-Laki" required> Laki-Laki
                    <input id="genderP" type="radio" name="gender" value="Perempuan" required> Perempuan
                </div>
            <div class="form-group mb-3">
                <label for="no_wa">No. WhatsApp</label>
                <input type="text" class="form-control" id="no_wa" name="no_wa">
            </div>
            <div class="form-group mb-3">
                <label for="komunitas">Asal Komunitas</label>
                <input type="text" class="form-control" id="komunitas" >
            </div>
            
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary w-100" id="btn-submit">Daftar</button>
            </div>
        </form>
    </div>

    

    <?php include '../component/footer.php'; ?>

    <script src="../module/js/setDaftar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>

