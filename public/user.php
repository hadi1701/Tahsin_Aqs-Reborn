<?php
    session_start();

    // contoh data login (nanti bisa diambil dari DB)
    if (!isset($_SESSION['username'])) {
        $_SESSION['username'];
    }

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri | Tahsin Aqsyanna</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
        background-color: #000000ff;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        }

        /* Hero Section */
        .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                    url('../img/userbg.jpg') center/cover no-repeat;
        height: 100vh;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5%;
        border-bottom: 2px solid #ffc107;
        }

        .hero-text h1 {
        font-size: 2.5rem;
        font-weight: 600;
        color: #f38b02ff;
        }

        .hero-text p {
        font-size: 1.2rem;
        margin-top: 10px;
        }

        /* Card Section */
        .card-section {
        margin-top: -60px;
        padding-bottom: 60px;
        }

        .info-card {
        background-color: #000000ff;
        border: 2px solid #0058aaff;
        border-radius: 15px;
        padding: 25px;
        color: #044d91ff;
        text-align: center;
        transition: 0.3s;
        }

        .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        background-color: #eeeeeeff;
        }

        .info-card i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        }

        a {
        text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
        <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            Tahsin<span class="text-warning">Aqsyanna.</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item"><a href="#" class="nav-link">Beranda</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Program</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Profil</a></li>
            <li class="nav-item"><a href="login.php" class="btn btn-warning text-dark ms-3">Logout</a></li>
            </ul>
        </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-text">
            <h1>Selamat Datang, <?= htmlspecialchars($username) ?>!</h1>
            <p>Semangat terus memperbaiki bacaan <span>Al-Qur'anmu</span> 🧡</p>
    </section>

    <!-- Card Section -->
    <section class="card-section container">
        <div class="row justify-content-center gy-4">
        <div class="col-md-4">
            <a href="progress.php">
            <div class="info-card">
                <i class="fa-solid fa-chart-line"></i>
                <h5>Progress Bacaan</h5>
                <p>Lihat area yang perlu diperbaiki dalam bacaan Qur'anmu.</p>
            </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="pencapaian.php">
            <div class="info-card">
                <i class="fa-solid fa-award"></i>
                <h5>Pencapaian Huruf</h5>
                <p>Lihat huruf hijaiyyah yang sudah kamu kuasai dengan baik.</p>
            </div>
            </a>
        </div>
        </div>
    </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
