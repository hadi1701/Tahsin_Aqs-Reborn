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

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  
    <link rel="stylesheet" href="../css/style.css">

</head>
<body data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-root-margin="70px" data-bs-smooth-scroll="true">

<!-- Navbar -->
<?php include '../component/navbar.php' ?>


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
                    <p>Area belajar yang nyaman, suasana belajar khidmat & penuh adab.</p>
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
<?php include 'program.php' ?>

<!-- About -->
<?php include 'about.php' ?>


<!-- Testimoni — Instagram Style -->
<?php include 'testimoni.php' ?>


<!-- CTA Daftar -->
<section id="daftar">
    <div class="container">
        <h3>Siap Memulai Perjalanan Tahsinmu?</h3>
        <p>Daftar sekarang dan dapatkan banyak manfaat dengan pengajar kami.</p>
        <a href="../public/daftar.php" class="btn btn-warning btn-lg px-5">📝 Daftar Sekarang</a>
    </div>
</section>

<!-- Footer -->
<?php include '../component/footer.php' ?>


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
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>