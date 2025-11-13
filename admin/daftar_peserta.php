<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['roles'] != 'admin') {
    session_destroy(); // hapus semua session
    header("Location: login.php");
    exit; // sangat penting agar kode di bawahnya tidak tetap dijalankan
}

require_once '../module/dbconnect.php';

$stmt = db()->prepare('SELECT * FROM daftar');
$stmt->execute();
$rowDaftar = $stmt->fetchAll(db()::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Daftar Peserta</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include '../component/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2>Daftar Peserta</h2>
            </div>

            <div class="col-12 text-start mb-3">
                <a href="admin.php" class="btn btn-danger"><i class="fa fa-sign-in-alt"></i> Kembali</a>
            </div>

            <div class="col-12 text-end mb-3">
                <a href="../add.php" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</a>
                <a href="validasi.php" class="btn btn-warning"><i class="fa fa-sign-out-alt"></i> Validasi</a>
            </div>

            <div class="col-12">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-primary">
                        <tr class="text-center">
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Usia</th>
                            <th>No. WhatsApp</th>
                            <th>Asal Komunitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($rowDaftar as $key): ?>
                            <tr>
                                <td class="text-center"><?= $i ?></td>
                                <td><?= htmlspecialchars($key['username']) ?></td>
                                <td><?= htmlspecialchars($key['nama']) ?></td>
                                <td><?= htmlspecialchars($key['usia']) ?></td>
                                <td><?= htmlspecialchars($key['no_wa']) ?></td>
                                <td><?= htmlspecialchars($key['komunitas']) ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary btn-update" data-id="<?= $key['id'] ?>">
                                        <i class="fa fa-edit"></i>Update
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= $key['id'] ?>">
                                        <i class="fa fa-trash"></i>Delete
                                    </button>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        
        <!-- Header -->
        <div class="modal-header" style="background-color: #000;">
            <h5 class="modal-title text-warning fw-bold" id="modalUpdateLabel">Edit Data Registrasi</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        
        <!-- Body -->
        <div class="modal-body" style="background-color: #f8f9fa;">
            <form id="formUpdate">
                <div class="mb-3">
                    <label for="id" class="form-label fw-semibold text-dark">No</label>
                    <input type="text" id="id" name="id" class="form-control border-dark" readonly>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold text-dark">Username</label>
                    <input type="text" id="username" name="username" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold text-dark">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="usia" class="form-label fw-semibold text-dark">Usia</label>
                    <input type="number" id="usia" name="usia" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-dark">Gender</label><br>
                    <div class="form-check form-check-inline">
                    <input type="radio" name="gender" id="updategenderL" value="Laki-laki" class="form-check-input">
                    <label class="form-check-label text-dark" for="updategenderL">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" name="gender" id="updategenderP" value="Perempuan" class="form-check-input">
                    <label class="form-check-label text-dark" for="updategenderP">Perempuan</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="no_wa" class="form-label fw-semibold text-dark">No. WhatsApp</label>
                    <input type="text" id="no_wa" name="no_wa" class="form-control border-dark">
                </div>

                <div class="mb-3">
                    <label for="komunitas" class="form-label fw-semibold text-dark">Asal Komunitas</label>
                    <input type="text" id="komunitas" name="komunitas" class="form-control border-dark">
                </div>

                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn px-3" style="background-color: #f4c542; color: #000; font-weight: 300;">Simpan Perubahan</button>
                </div>
                </form>
            </div>
        
        </div>
    </div>
    </div>

    <?php include '../component/footer.php'; ?>

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../module/js/setDaftar.js"></script>

    <script>
        AOS.init();
    </script>
</body>
</html>
