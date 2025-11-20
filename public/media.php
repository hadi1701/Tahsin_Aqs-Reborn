<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "bootstrap.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahsin Aqsyanna — Rumah Al-Qur'an Berjenjang</title>
    <meta name="description" content="Program Tahsin Al-Qur'an berjenjang di Rumah Al-Qur'an Aqsyanna. Pra-Tahsin hingga Tahfizh, dengan pengajar berkompeten & lingkungan kondusif.">

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- GOOGLE FONTS: Poppins & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-yellow: #ffd369;
            --primary-gold: #ffc107;
            --bg-navy: #102a43;
            --bg-dark: #0f172a;
            --bg-darker: #071029;
            --text-light: #ffffff;
            --text-muted: #94a3b8;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #000;
            color: var(--text-light);
            scroll-behavior: smooth;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--primary-yellow);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1050;
            padding: 0.8rem 2rem;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.6rem 2rem;
            background: rgba(15, 23, 42, 0.95);
        }

        .navbar-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .navbar-logo .tahsin {
            color: #fff;
            font-style: normal;
        }

        .navbar-logo .aqsyanna {
            color: var(--primary-yellow);
            font-style: italic;
        }

        .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            font-size: 0.98rem;
            margin: 0 0.7rem;
            transition: color 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-yellow) !important;
        }

        /* Hero — Full background, no crop */
        .hero-index {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-size: cover;
            background-position: center;
            padding: 0 1.5rem;
            background:
                linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.85)),
                url('../img/langit.jpg') center center / cover no-repeat;
        }

        .hero-index h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            line-height: 1.4;
        }

        .hero-index .highlight {
            color: var(--primary-yellow);
        }

        .hero-index p {
            font-size: 1rem;
            max-width: 650px;
            margin: 0 auto 2rem;
            color: #e2e8f0;
        }

        .btn {
            padding: 0.65rem 1.5rem;
            font-size: 0.98rem;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning {
            background-color: var(--primary-gold);
            color: #000;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 193, 7, 0.4);
        }

        .btn-outline-light {
            border-color: var(--text-light);
            color: var(--text-light);
        }

        .btn-outline-light:hover {
            background: var(--text-light);
            color: #000;
        }

        /* Section Wrapper */
        section {
            padding: 80px 2rem;
        }

        @media (max-width: 768px) {
            section {
                padding: 60px 1rem;
            }
            .hero-index h1 { font-size: 1.8rem; }
            .hero-index p { font-size: 1rem; }
        }

        /* Fitur Utama */
        .container-fitur {
            max-width: 1200px;
            margin: 0 auto;
        }

        .fitur-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.6rem;
            text-align: center;
            backdrop-filter: blur(10px);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .fitur-card:hover {
            transform: translateY(-6px);
            background: rgba(255, 255, 255, 0.06);
        }

        .fitur-card i {
            font-size: 2.2rem;
            color: var(--primary-yellow);
            margin-bottom: 1rem;
        }

        .fitur-card h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
            color: var(--primary-yellow);
        }

        .fitur-card p {
            font-size: 0.98rem;
            color: #cbd5e1;
            line-height: 1.6;
        }

        /* Tantangan */
        .program-tahsin1 {
            background: #000;
        }

        .img-tantangan {
            max-height: 300px;
            border-radius: 16px;
            object-fit: cover;
        }

        /* Program — Cards DIPERKECIL */
        .program-tahsin {
            background: radial-gradient(circle at top, var(--bg-navy), #0b1a29);
            position: relative;
            overflow: hidden;
        }

        .program-tahsin::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
            opacity: 0.05;
            z-index: 0;
        }

        .program-tahsin h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--primary-yellow);
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .program-tahsin p.lead {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto 2.5rem;
            color: #cbd5e1;
            text-align: center;
        }

        .program-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.2rem 1rem;
            color: var(--text-light);
            position: relative;
            z-index: 1;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.35s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .program-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.15);
            background: rgba(255, 255, 255, 0.08);
        }

        .program-card .icon-wrapper {
            font-size: 1.8rem;
            color: var(--primary-yellow);
            margin-bottom: 0.8rem;
        }

        .program-card h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.15rem;
            color: var(--primary-yellow);
            margin-bottom: 0.6rem;
        }

        .program-card p {
            font-size: 0.92rem;
            color: #e2e8f0;
            line-height: 1.5;
            flex-grow: 1;
        }

        .highlight-card {
            border: 1px solid rgba(255, 193, 7, 0.5);
            background: linear-gradient(145deg, rgba(255, 193, 7, 0.08), rgba(255, 255, 255, 0.05));
        }

        /* About — PALET WARNA SESUAI PERMINTAAN */
        #about {
            background: linear-gradient(180deg, var(--bg-dark), var(--bg-darker));
            color: rgba(255, 255, 255, 0.72);
        }

        .about-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .about-header h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.1rem;
            color: var(--primary-yellow);
            margin-bottom: 0.8rem;
        }

        .about-header p {
            color: var(--text-muted);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .about-content {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2.5rem;
        }

        @media (max-width: 992px) {
            .about-content {
                grid-template-columns: 1fr;
            }
        }

        .about-text {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .about-text p.lead {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.92);
            margin-bottom: 1.8rem;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
            margin-top: 1.5rem;
        }

        .feature-item {
            padding: 1.2rem;
            border-radius: 12px;
            background: rgba(124, 58, 237, 0.08);
            border: 1px solid rgba(124, 58, 237, 0.15);
            display: flex;
            gap: 0.9rem;
        }

        .feature-icon {
            color: var(--primary-gold);
            font-size: 1.2rem;
            margin-top: 0.2rem;
        }

        .feature-item h6 {
            font-size: 1.05rem;
            color: #e2e8f0;
            margin: 0 0 0.4rem;
            font-weight: 600;
        }

        .feature-item p {
            font-size: 0.95rem;
            color: #cbd5e1;
            margin: 0;
        }

        .about-stats {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.3rem;
        }

        .stat-card {
            background: linear-gradient(180deg, rgba(124, 58, 237, 0.12), rgba(124, 58, 237, 0.06));
            padding: 1.5rem;
            border-radius: 14px;
            border: 1px solid rgba(124, 58, 237, 0.15);
            text-align: center;
        }

        .stat-card h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            color: #fff;
            margin: 0;
        }

        .stat-card p {
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.95rem;
        }

        /* Visi & Misi */
        .visi-misi {
            max-width: 1000px;
            margin: 3.5rem auto 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2.2rem;
        }

        @media (max-width: 768px) {
            .visi-misi {
                grid-template-columns: 1fr;
            }
        }

        .visi-card,
        .misi-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
        }

        .visi-card:hover,
        .misi-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
        }

        .visi-card {
            border-left: 4px solid var(--primary-yellow);
        }

        .misi-card {
            border-left: 4px solid #4ade80;
        }

        .card-icon {
            font-size: 2.4rem;
            margin-bottom: 1rem;
        }

        .visi-card .card-icon {
            color: var(--primary-yellow);
        }

        .misi-card .card-icon {
            color: #4ade80;
        }

        .card-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.35rem;
            margin-bottom: 1.1rem;
            color: #e2e8f0;
        }

        .misi-list {
            list-style: none;
            padding-left: 0;
        }

        .misi-list li {
            margin-bottom: 0.9rem;
            padding-left: 1.9rem;
            position: relative;
            color: #cbd5e1;
            font-size: 1rem;
        }

        .misi-list li::before {
            content: "✓";
            color: var(--primary-yellow);
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Testimoni — Instagram Style (3 kolom) */
        #testimoni {
            background: radial-gradient(circle at top, #0a1224, #060b14);
            position: relative;
        }

        #testimoni::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
            opacity: 0.04;
            z-index: 0;
        }

        .testimoni-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .testimoni-header h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2.1rem;
            color: var(--primary-yellow);
        }

        .testimoni-header p {
            color: var(--text-muted);
            max-width: 700px;
            margin: 0.8rem auto 0;
            font-size: 1.1rem;
        }

        .testimoni-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .testimoni-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .testimoni-grid {
                grid-template-columns: 1fr;
            }
        }

        .testi-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .testi-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(255, 211, 105, 0.2);
        }

        .testi-avatar {
            width: 100%;
            height: 210px;
            background: linear-gradient(45deg, #7c3aed, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.2rem;
        }

        .testi-content {
            padding: 1.8rem 1.5rem 1.5rem;
        }

        .testi-content p {
            font-style: italic;
            font-size: 1.05rem;
            color: #e2e8f0;
            margin-bottom: 1.2rem;
            line-height: 1.65;
        }

        .testi-author {
            display: flex;
            align-items: center;
            gap: 0.9rem;
        }

        .testi-author .name {
            font-weight: 600;
            color: var(--text-light);
            font-size: 1.05rem;
        }

        .testi-author .batch {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        /* CTA Daftar */
        #daftar {
            background: linear-gradient(135deg, var(--bg-navy), #08162a);
            text-align: center;
        }

        #daftar h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.9rem;
            margin-bottom: 1rem;
        }

        #daftar p {
            color: #cbd5e1;
            font-size: 1.1rem;
            margin-bottom: 1.8rem;
        }

        /* Footer — Sesuai Versi Anda */
        .footer {
            background: #090f1b;
            padding-top: 3.5rem;
            padding-bottom: 2rem;
        }

        .footer .text-light {
            color: #e6e6e6 !important;
        }

        .footer .text-muted,
        .footer .small {
            color: #bbb !important;
        }

        .footer .text-warning {
            color: var(--primary-yellow) !important;
        }

        .footer-link {
            color: #bbb !important;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--primary-gold) !important;
        }

        .contact-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.7rem;
            font-size: 0.9rem;
        }

        .contact-list i {
            font-size: 0.85rem;
            margin-right: 0.7rem;
            margin-top: 0.2rem;
            min-width: 16px;
            color: var(--primary-gold);
        }

        .social-link {
            font-size: 1.25rem;
            margin-right: 1.1rem;
            color: #ccc !important;
            transition: all 0.3s;
        }

        .social-link:hover {
            color: var(--primary-gold) !important;
            transform: translateY(-3px);
        }

        .whatsapp-float {
            position: fixed;
            width: 58px;
            height: 58px;
            bottom: 24px;
            right: 24px;
            background: #25d366;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.35);
            z-index: 2000;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
        }

        /* Navbar Responsive */
        @media (max-width: 991px) {
            .navbar-nav {
                margin-top: 1rem;
                text-align: center;
            }
            .nav-link {
                margin: 0.5rem 0 !important;
                font-size: 1.05rem;
            }
            .btn {
                padding: 0.55rem 1.3rem;
                font-size: 0.93rem;
            }
        }

        @media (max-width: 480px) {
            .hero-index h1 { font-size: 1.6rem; }
            .fitur-card h5 { font-size: 1.1rem; }
            .testi-avatar { height: 180px; font-size: 2.6rem; }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-root-margin="70px" data-bs-smooth-scroll="true">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
    <div class="container">
        <a class="navbar-logo" href="#hero">
            <span class="tahsin">Tahsin</span><span class="aqsyanna">Aqsyanna.</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#hero">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#programs">Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#testimoni">Testimoni</a>
                </li>
            </ul>
            <div class="d-flex ms-auto mt-2 mt-lg-0">
                <a href="../public/login.php" class="btn btn-outline-light btn-sm me-2 d-none d-md-inline">Login</a>
                <a href="#daftar" class="btn btn-warning btn-sm">Daftar</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero -->
<section id="hero" class="hero-index">
    <div class="container">
        <h1 class="fw-bold">
            Semua yang Kamu Butuhkan untuk Belajar <br>
            Tahsin Al-Qur'an, Ada di <span class="highlight">Tahsin Aqsyanna</span>.
        </h1>
        <p>
            Mulai perjalanan belajar Tahsin Al-Qur'anmu dengan program kami yang komprehensif, berjenjang, dan dibimbing pengajar yang kompeten.
        </p>
        <div class="mt-4">
            <a href="#daftar" class="btn btn-warning me-1">Daftar Sekarang</a>
            <a href="#programs" class="btn btn-outline-light">Lihat Program</a>
        </div>
    </div>
</section>

<!-- Fitur Utama -->
<section id="fitur">
    <div class="container-fitur">
        <div class="row g-4 justify-content-center">
            <div class="col-md-4" data-aos="fade-up">
                <div class="fitur-card">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <h5>Instruktur Berpengalaman</h5>
                    <p>Ustadz/ustadzah tersertifikasi, berpengalaman membimbing santri dari nol hingga tartil.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="fitur-card">
                    <i class="fa-solid fa-book-open"></i>
                    <h5>Materi Terstruktur</h5>
                    <p>Modul dari Pra-Tahsin hingga Tahfizh, disusun sistematis & sesuai kaidah ilmu tajwid.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="fitur-card">
                    <i class="fa-solid fa-users"></i>
                    <h5>Lingkungan Kondusif</h5>
                    <p>Halaqah kecil (10:2), pemisahan gender, suasana belajar khidmat & penuh adab.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tantangan -->
<section class="program-tahsin1">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-6 text-center mb-4 mb-md-0">
                <img src="../img/ngaji.jpg" alt="Santri belajar Al-Qur'an" class="img-fluid img-tantangan">
            </div>
            <div class="col-md-6">
                <div class="p-4 rounded-4" style="background: rgba(16, 42, 67, 0.7); border: 1px solid rgba(255, 193, 7, 0.2);">
                    <h4 class="fw-bold mb-3 text-light">Tantangan Umum Belajar Tahsin</h4>
                    <ul class="list-unstyled text-light">
                        <li class="d-flex mb-3">
                            <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                            <p class="mb-0">Kesulitan memahami aturan tajwid yang kompleks.</p>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                            <p class="mb-0">Kurangnya bimbingan langsung dari pengajar kompeten.</p>
                        </li>
                        <li class="d-flex">
                            <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                            <p class="mb-0">Sulit konsisten karena tidak ada sistem evaluasi rutin.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Program -->
<section id="programs" class="program-tahsin">
    <div class="container">
        <h2>Lima Jenjang Pembelajaran</h2>
        <p class="lead">
            Setiap jenjang dirancang untuk membangun fondasi kuat dalam membaca Al-Qur’an sesuai kaidah tajwid.
        </p>

        <div class="row justify-content-center align-items-center text-center g-4">
            <div class="col-md-4 col-lg-2 col-sm-6">
                <div class="program-card">
                    <div class="icon-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <h5>Pra-Tahsin</h5>
                    <p>Huruf hijaiyah, makhraj dasar, dan pengenalan tajwid pertama.</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-sm-6">
                <div class="program-card">
                    <div class="icon-wrapper"><i class="fa-solid fa-book-open-reader"></i></div>
                    <h5>Tahsin 1</h5>
                    <p>Pembenahan makhraj, sifat huruf, dan latihan bacaan tartil.</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-sm-6">
                <div class="program-card">
                    <div class="icon-wrapper"><i class="fa-solid fa-quran"></i></div>
                    <h5>Tahsin 2</h5>
                    <p>Tajwid lanjutan: mad, idgham, iqlab, dan hukum waqaf.</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-sm-6">
                <div class="program-card">
                    <div class="icon-wrapper"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h5>Takhassus</h5>
                    <p>Persiapan calon pengajar: qira’ah, adab tilawah, dan metode mengajar.</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-sm-6">
                <div class="program-card highlight-card">
                    <div class="icon-wrapper"><i class="fa-solid fa-star-and-crescent"></i></div>
                    <h5>Tahfizh</h5>
                    <p>Menghafal Al-Qur’an dengan bacaan yang telah tartil dan mutqin.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About -->
<section id="about">
    <div class="container">
        <div class="about-header">
            <h2>Tentang <span class="text-warning">Tahsin Aqsyanna</span></h2>
            <p>Rumah Al-Qur'an — Program Tahsin Berjenjang (Sejak April 2022)</p>
        </div>

        <div class="about-content">
            <div class="about-text">
                <p class="lead">
                    Tahsin Aqsyanna adalah program unggulan di Rumah Al-Qur'an Aqsyanna yang dirancang khusus untuk memperbaiki bacaan Al-Qur’an santri secara bertahap dan terukur.
                </p>
                <p class="mb-3">
                    Program berlangsung selama 6 bulan dengan sistem halaqah (10 murid : 2 pengajar), pemisahan gender, dan evaluasi rutin tiap bulan. Di akhir program, peserta mendapat sertifikat dan rapor kemajuan bacaan.
                </p>

                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fa-solid fa-clock feature-icon"></i>
                        <div>
                            <h6>Durasi Terstruktur</h6>
                            <p>6 bulan untuk progres konsisten</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-people-group feature-icon"></i>
                        <div>
                            <h6>Rasio Ideal</h6>
                            <p>10 murid : 2 pengajar</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-user-shield feature-icon"></i>
                        <div>
                            <h6>Lingkungan Aman</h6>
                            <p>Pemisahan laki-laki & perempuan</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-certificate feature-icon"></i>
                        <div>
                            <h6>Sertifikat</h6>
                            <p>Dengan nilai rapor bacaan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-stats">
                <div class="stat-card">
                    <h3>Setiap Ahad</h3>
                    <p>Jam 13.00 – 15.00 WIB</p>
                </div>
                <div class="stat-card">
                    <h3>6 Bulan</h3>
                    <p>Durasi Program</p>
                </div>
                <div class="stat-card">
                    <h3>Rp 700.000</h3>
                    <p>Iuran</p>
                </div>
                <div class="stat-card">
                    <h3>10 : 2</h3>
                    <p>Murid : Pengajar</p>
                </div>
            </div>
        </div>

        <!-- Visi & Misi -->
        <div class="visi-misi">
            <div class="visi-card">
                <i class="fa-solid fa-eye card-icon"></i>
                <h3 class="card-title">Visi</h3>
                <p>
                    Tahsin Aqsyanna sebagai Poros Penggerak Dakwah Nilai-Nilai Al-Qur'an.
                </p>
            </div>
            <div class="misi-card">
                <i class="fa-solid fa-bullseye card-icon"></i>
                <h3 class="card-title">Misi</h3>
                <ul class="misi-list">
                    <li>Menumbuhkan kecintaan terhadap Al-Qur’an melalui pembelajaran tahsin yang menyenangkan.</li>
                    <li>Mengajarkan kaidah tajwid dengan metode yang mudah dipahami.</li>
                    <li>Membina peserta agar istiqamah dalam membaca dan mengamalkan Al-Qur’an.</li>
                    <li>Mengembangkan media pembelajaran Al-Qur’an yang kreatif dan modern.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Testimoni — Instagram Style -->
<section id="testimoni">
    <div class="container">
        <div class="testimoni-header">
            <h2>Testimoni Peserta</h2>
            <p>Kesan & pesan dari santri yang telah menyelesaikan program Tahsin di Aqsyanna.</p>
        </div>

        <div class="testimoni-grid">
            <!-- Testi 1 -->
            <div class="testi-card">
                <div class="testi-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="testi-content">
                    <p>
                        “Dulu saya sering salah baca idgham dan mad. Setelah 3 bulan di Tahsin Aqsyanna, Alhamdulillah bacaan saya jauh lebih tartil.”
                    </p>
                    <div class="testi-author">
                        <div class="name">Siti Aisyah</div>
                        <div class="batch">Batch 2</div>
                    </div>
                </div>
            </div>

            <!-- Testi 2 -->
            <div class="testi-card">
                <div class="testi-avatar" style="background: linear-gradient(45deg, #10b981, #06b6d4);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="testi-content">
                    <p>
                        “Suasana halaqah sangat kondusif. Kami saling mengoreksi dan termotivasi satu sama lain.”
                    </p>
                    <div class="testi-author">
                        <div class="name">Ahmad Fauzi</div>
                        <div class="batch">Batch 5</div>
                    </div>
                </div>
            </div>

            <!-- Testi 3 -->
            <div class="testi-card">
                <div class="testi-avatar" style="background: linear-gradient(45deg, #f97316, #ea580c);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="testi-content">
                    <p>
                        “Ustadzahnya sabar dan metodenya mudah dimengerti. Evaluasi bulanan membuat saya tahu progres secara objektif.”
                    </p>
                    <div class="testi-author">
                        <div class="name">Dinda Rahma</div>
                        <div class="batch">Batch 4</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Daftar -->
<section id="daftar">
    <div class="container">
        <h3>Siap Memulai Perjalanan Tahsinmu?</h3>
        <p>Daftar sekarang dan dapatkan konsultasi gratis dengan pengajar kami.</p>
        <a href="../public/daftar.php" class="btn btn-warning btn-lg px-5">📝 Daftar Sekarang</a>
    </div>
</section>

<!-- Footer -->
<footer class="footer text-white pt-5 pb-4">
  <div class="container">
    <div class="row align-items-start gy-4">
      <!-- Kolom 1 -->
      <div class="col-md-6">
        <div class="d-flex align-items-center mb-3">
          <img src="../img/tahsin.png" alt="Logo Tahsin Aqsyanna" width="70" class="me-2">
          <h5 class="fw-bold mb-0 text-warning">Tahsin Aqsyanna</h5>
        </div>
        <p class="small text-light mb-2">
          Lembaga pembelajaran Al-Qur’an yang berfokus pada peningkatan kualitas bacaan sesuai kaidah tajwid dan tartil.
        </p>
        <p class="small text-light mb-0">
          Misi kami: membantu umat Muslim membaca Al-Qur’an dengan tartil, yakin, dan cinta.
        </p>
        <div class="d-flex align-items-center social-container mt-3">
          <a href="mailto:tahsinaqsyanna@gmail.com" class="text-light social-link"><i class="fa-solid fa-envelope"></i></a>
          <a href="https://wa.me/6283890892289" target="_blank" class="text-light social-link"><i class="fab fa-whatsapp"></i></a>
          <a href="https://instagram.com/tahsinaqs" target="_blank" class="text-light social-link"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      <!-- Kolom 2 -->
      <div class="col-md-3">
        <h6 class="fw-bold text-warning mb-3 mt-3">Hubungi Kami</h6>
        <ul class="list-unstyled small mb-0 contact-list">
          <li><i class="fa-solid fa-location-dot"></i><span>Jl. W R Supratman No.51, Cemp. Putih, Kec. Ciputat Timur, Kota Tangerang Selatan, Banten 15412</span></li>
          <li class="mt-1"><i class="fa-solid fa-phone"></i><span>+62 838-9089-2289</span></li>
          <li class="mt-1"><i class="fa-solid fa-envelope"></i><span>tahsinaqsyanna@gmail.com</span></li>
        </ul>
      </div>
      
      <!-- Kolom 3 -->
      <div class="col-md-3">
        <h6 class="fw-bold text-warning mb-3 mt-3">Navigasi</h6>
        <ul class="list-unstyled small">
          <li class="mb-2"><a href="#hero" class="footer-link">Beranda</a></li>
          <li class="mb-2"><a href="#programs" class="footer-link">Program</a></li>
          <li class="mb-2"><a href="#about" class="footer-link">Tentang Kami</a></li>
        </ul>
      </div>
    </div>

    <hr class="border-secondary mt-4 mb-3">

    <div class="text-center small text-secondary">
      © 2025 <span class="text-white fw-semibold">Tahsin Aqsyanna</span>.
      All rights reserved. Made with <span class="text-danger">❤️</span> in Indonesia.
    </div>
  </div>

  <a href="https://wa.me/6288213234303" target="_blank" class="whatsapp-float">
    <i class="fab fa-whatsapp"></i>
  </a>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    const navbar = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Active nav on scroll (Intersection Observer)
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${id}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, { threshold: 0.5 });

    sections.forEach(section => {
        if (section.id) observer.observe(section);
    });

    // Smooth scroll + close mobile menu
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (this.getAttribute('href') === '#') return;
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                const top = target.offsetTop - 80;
                window.scrollTo({
                    top: top,
                    behavior: 'smooth'
                });

                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse?.classList.contains('show')) {
                    new bootstrap.Collapse(navbarCollapse).hide();
                }
            }
        });
    });
</script>

</body>
</html>