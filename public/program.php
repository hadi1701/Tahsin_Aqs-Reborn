<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Tahsin | Aqsyanna</title>

    
    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a2d9d6d0f1.js" crossorigin="anonymous"></script>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS Bootstrap Bundle (Popper sudah termasuk) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
        
</head>
<body>

    <!-- Navbar -->
    <?php include '../component/navbar.php'; ?>

    <section class="hero-index d-flex align-items-center justify-content-center text-center text-white">
        <div class="container">
            <h1>Kumpulan Program di 
                <span class="highlight">Tahsin Aqsyanna.</span></h1>
            <p class="lead mt-3">Semua Program yang dilaksanakan di Tahsin Aqsyanna masih bersifat offline atau tatap muka.</p>
            <div class="mt-4">
                <a href="#" class="btn btn-warning btn-sm ">Daftar Sekarang</a>
                <a href="#" class="btn btn-outline-light btn-sm">Lihat Program</a>
            </div>
        </div>
    </section>

    <!-- Program Section -->
    <section class="program-tahsin">
        <div class="container text-center text-light">
            <h2 class="fw-bold mb-3" data-aos="fade-down">Lima Jenjang Pembelajaran</h2>
            <p class="lead mb-5 text-white-50" data-aos="fade-up">
                Setiap jenjang membantu santri memahami, memperbaiki, dan menghafal Al-Qur’an secara bertahap.
            </p>

            <div class="row justify-content-center g-4">
                <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="program-card glass-card">
                        <div class="icon-wrapper"><i class="fa-solid fa-seedling"></i></div>
                        <h5>Pra-Tahsin</h5>
                        <p>Belajar huruf hijaiyah dan makhraj dasar untuk mengenal tajwid sejak awal.</p>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="program-card glass-card">
                        <div class="icon-wrapper"><i class="fa-solid fa-book-open-reader"></i></div>
                        <h5>Tahsin 1</h5>
                        <p>Pembenahan makhraj, sifat huruf, dan latihan membaca Al-Qur'an dengan benar.</p>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="program-card glass-card">
                        <div class="icon-wrapper"><i class="fa-solid fa-quran"></i></div>
                        <h5>Tahsin 2</h5>
                        <p>Memahami hukum tajwid lanjutan seperti mad, idgham, dan hukum waqaf.</p>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="program-card glass-card">
                        <div class="icon-wrapper"><i class="fa-solid fa-graduation-cap"></i></div>
                        <h5>Takhassus</h5>
                        <p>Pemantapan bacaan sesuai riwayat dan adab qira’ah bagi calon pengajar.</p>
                    </div>
                </div>

                <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="program-card glass-card highlight-card">
                        <div class="icon-wrapper"><i class="fa-solid fa-star-and-crescent"></i></div>
                        <h5>Tahfizh</h5>
                        <p>Setelah bacaan tartil, santri mulai menghafal ayat dengan kaidah tajwid yang benar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../component/footer.php'; ?>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
