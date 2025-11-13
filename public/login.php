<?php
session_start();
require_once '../module/dbconnect.php';

$alert = '';
if (isset($_POST['submit'])) {
    // Ambil data dari form login
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)) {
        $alert = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Username atau password salah!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Coba Lagi'
                }).then(() => {
                window.location.href='login.php';
            });
        </script>";
        exit;
    }

    // Cek username dan password
    $query = db()->prepare('SELECT * FROM daftar WHERE username = :username');
    $query->execute([':username' => $username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    $query->closeCursor();

    //cek apakah user ditemukan dan password cocok
    if ($user && password_verify($password, $user['password'])) {
        // Simpan data ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Ambil roles berdasarkan id user
        $roleQuery = db()->prepare('SELECT roles FROM roles WHERE user_id = :id');
        $roleQuery->execute([':id' => $user['id']]);
        $role = $roleQuery->fetch(PDO::FETCH_ASSOC);
        $roleQuery->closeCursor();

        //cek role dan arahkan ke halaman role
        if ($role && $role['roles'] === 'admin') {
            $_SESSION['roles'] = 'admin';
            header('Location: admin.php');
            exit;
        } 

        //kalau bukan admin, ambil data pembayaran
        $payQuery = db()->prepare('SELECT is_paid, is_active FROM pembayaran WHERE user_id = :id');
        $payQuery->execute([':id' => $user['id']]);
        $payment = $payQuery->fetch(PDO::FETCH_ASSOC);
        $payQuery->closeCursor();

        //cek status pembayaran
        if(!$payment) {
            //belum pernah isi bukti bayar dan belum bayar
            echo "<script>
                alert('Anda belum melakukan pembayaran. Silakan upload bukti pembayaran terlebih dahulu.');
                window.location.href='pembayaran.php';
            </script>";
            exit;

        } 
        
        if ($payment['is_paid'] == 0) {
            echo "<script> 
            alert('Anda belum melakukan pembayaran. Silakan upload bukti pembayaran terlebih dahulu.');
            window.location.href='pembayaran.php';
            </script>";
            exit;

        } 
        
        //sudah bayar tapi belum divalidasi admin
        if ($payment['is_paid'] == 1 && $payment['is_active'] == 0) {
            //sudah bayar, tapi menunggu validasi admin
            echo "<script> alert('Pembayaran Anda sudah diterima. Mohon menunggu validasi dari admin.');
            window.location.href='login.php';</script>";
            exit;
            
        } 
        
        //sudah bayar dan sudah divalidasi
        if ($payment['is_paid'] == 1 && $payment['is_active'] == 1) {
            //sudah active
            $_SESSION['roles'] = 'user';
            header('Location: user.php');
            exit;
        }

    } else {
        echo "<script>
            alert('Username atau password salah!'); 
            window.location.href='login.php';
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS Bootstrap Bundle (Popper sudah termasuk) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Notif -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <?php include '../component/navbar.php'?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <form action="" method="post" class="container border p-4 rounded shadow col-3">
            <h2 align="center" class="mb-3">Login</h2>
            <?php if(isset($error)): ?>
                <p style="color: red; font-style: Italic">Username/password salah!</p>
            <?php endif; ?>
            
            <div class="form-group mb-3"> 
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password">
            </div>
            <div class="form-group text-center">
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
                <p class="mt-3">Belum punya akun? Klik <a href="daftar.php">Daftar</a></p>
            </div>
        </form>
    </div>

    <?php include '../component/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="../module/js/setDaftar.js"></script>
</body>
</html>

