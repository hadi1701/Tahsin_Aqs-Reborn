<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahsin Aqsyanna</title>

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

  <!-- AOS Script -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  
</head>

<body class="index-page">
  <?php include '../component/navbar.php'; ?>

  <!-- HERO SECTION -->
  <section class="hero-index d-flex align-items-center justify-content-center text-center text-white">
    <div class="container">
      <h1 class="fw-bold">
        Semua yang Kamu Butuhkan untuk Belajar <br>
        Tahsin Al-Qur'an, Ada di <span class="highlight">Tahsin Aqsyanna</span>.
      </h1>
      <p class="lead mt-3">Mulai perjalanan belajar Tahsin Al-Qur'anmu dengan program kami yang <br>
        komprehensif dan terstruktur.</p>
      <div class="mt-4">
        <a href="daftar.php" class="btn btn-warning btn-sm">Daftar Sekarang</a>
        <a href="program.php" class="btn btn-outline-light btn-sm">Lihat Program</a>
      </div>
    </div>
  </section>

  <!-- FITUR UTAMA -->
  <div class="container-fitur">
    <div class="row g-0 justify-content-center p-5" data-aos="fade-up" data-aos-duration="1000">
      <div class="col-md-4 col-12 mb-4 mx-auto">
        <div class="d-flex flex-column align-items-center text-center px-3">
          <i class="fa-solid fa-chalkboard-user fa-3x mb-3 text-warning"></i>
          <h5 class="fw-bold">Instruktur Berpengalaman</h5>
          <p>Belajar dari instruktur yang ahli di bidang Tahsin Al-Qur'an dengan pengalaman yang luas.</p>
        </div>
      </div>

      <div class="col-md-4 col-12 mb-4 mx-auto">
        <div class="d-flex flex-column align-items-center text-center px-3">
          <i class="fa-solid fa-book-open fa-3x mb-3 text-warning"></i>
          <h5 class="fw-bold">Materi Komprehensif</h5>
          <p>Akses materi pembelajaran yang lengkap dan terstruktur untuk semua tingkat kemampuan.</p>
        </div>
      </div>

      <div class="col-md-4 col-12 mb-4 mx-auto">
        <div class="d-flex flex-column align-items-center text-center px-3">
          <i class="fa-solid fa-home fa-3x mb-3 text-warning"></i>
          <h5 class="fw-bold">Tempat yang Kondusif</h5>
          <p>Belajar dengan nyaman dan tenang di lingkungan yang mendukung.</p>
        </div>
      </div>
    </div>
  </div>



  <!-- TANTANGAN SECTION -->
  <section class="program-tahsin1">
    <div class="container">
      <div class="row align-items-center g-5">
        <!-- Kolom Kiri (Gambar) -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img src="../img/ngaji.jpg" alt="Membaca Al-Qur'an" class="img-fluid rounded-4 shadow-lg img-tantangan">
        </div>

        <!-- Kolom Kanan (Teks dan Poin) -->
        <div class="col-md-6 ps-md-5 p-4 ">
          <div class="p-4 rounded-4 shadow-sm" style="background-color: #102a43;">
            <h4 class="fw-bold mb-3 text-light">Tantangan Umum Belajar Tahsin Al-Qur'an</h4>
            <ul class="list-unstyled text-light">
              <li class="d-flex mb-3">
                <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                <p class="mb-0">Kesulitan dalam memahami aturan tajwid yang kompleks.</p>
              </li>
              <li class="d-flex mb-3">
                <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                <p class="mb-0">Kurangnya bimbingan dari instruktur yang berpengalaman.</p>
              </li>
              <li class="d-flex">
                <i class="fa-solid fa-circle-check text-warning me-3 fs-5 mt-1"></i>
                <p class="mb-0">Kesulitan dalam menjaga konsistensi belajar.</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PROGRAM SECTION -->
  <section class="program-tahsin">
    <div class="container-fluid text-center text-light p-5">
      <h2 class="fw-bold mb-3 mt-0" data-aos="fade-down">Lima Jenjang Pembelajaran</h2>
      <p class="lead mb-5 text-white-50" data-aos="fade-up">
        Setiap jenjang membantu santri memahami, memperbaiki, dan menghafal Al-Qur’an secara bertahap.
      </p>

      <div class="row justify-content-center g-4">
        <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <div class="program-card">
            <div class="icon-wrapper"><i class="fa-solid fa-seedling"></i></div>
            <h5>Pra-Tahsin</h5>
            <p>Belajar huruf hijaiyah dan makhraj dasar untuk mengenal tajwid sejak awal.</p>
          </div>
        </div>

        <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
          <div class="program-card">
            <div class="icon-wrapper"><i class="fa-solid fa-book-open-reader"></i></div>
            <h5>Tahsin 1</h5>
            <p>Pembenahan makhraj, hukum tajwid, dan latihan membaca Al-Qur'an dengan benar.</p>
          </div>
        </div>

        <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
          <div class="program-card">
            <div class="icon-wrapper"><i class="fa-solid fa-quran"></i></div>
            <h5>Tahsin 2</h5>
            <p>Memahami hukum tajwid lanjutan sifat huruf dan tajwid lanjutan lainnya.</p>
          </div>
        </div>

        <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
          <div class="program-card">
            <div class="icon-wrapper"><i class="fa-solid fa-star-and-crescent"></i></div>
            <h5>Takhassus</h5>
            <p>Pemantapan bacaan sesuai riwayat dan adab qira’ah bagi calon pengajar.</p>
          </div>
        </div>

        <div class="col-md-4 col-lg-2 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="500">
          <div class="program-card highlight-card">
            <div class="icon-wrapper"><i class="fa-solid fa-graduation-cap"></i></div>
            <h5>Tahfizh</h5>
            <p>Setelah bacaan tartil, santri mulai menghafal ayat dengan kaidah tajwid yang benar.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include '../component/footer.php'; ?>

  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
  integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
  crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
      AOS.init();
  </script>
</body>
</html>
