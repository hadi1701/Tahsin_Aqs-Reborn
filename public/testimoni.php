<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimoni | Tahsin Aqsyanna</title>
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
    <script>AOS.init();</script>
</head>

<body class="testimoni-page">
    <?php include '../component/navbar.php'; ?>

    <!-- Bagian Batch 2 -->
    <section class="section-testimoni py-5 text-center">
        <div class="container">
        <h2 class="section-title mb-3">Testimoni <span class="highlight">Batch 2</span></h2>
        <p class="section-subtitle mb-5">
            Cerita dan kesan para peserta dari Batch 2 tentang pengalaman mereka belajar membaca Al-Qur’an di Tahsin Aqsyanna.
        </p>

        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
            <div class="testi-card">
                <video class="testi-video" controls playsinline>
                <source src="../video/testi-batch2.mp4" type="video/mp4">
                </video>
                <h5 class="testi-title">Testimoni Batch 2</h5>
                <p class="testi-desc">Perjalanan menakjubkan dari peserta batch kedua yang merasakan perubahan dalam bacaan Al-Qur’an mereka.</p>
            </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Bagian Batch 5 -->
    <section class="section-testimoni py-5 text-center">
        <div class="container">
        <h2 class="section-title mb-3">Testimoni <span class="highlight">Batch 5</span></h2>
        <p class="section-subtitle mb-5">
            Pengalaman berharga dari peserta Batch 5 yang semakin dekat dengan Al-Qur’an melalui proses tahsin yang menyenangkan.
        </p>

        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
            <div class="testi-card">
                <video class="testi-video" controls playsinline>
                <source src="../video/testi-batch5-part1.mp4" type="video/mp4">
                </video>
                <h5 class="testi-title">Testimoni Batch 5</h5>
                <p class="testi-desc">Suasana kelas yang penuh semangat dan dukungan antar peserta membuat pembelajaran menjadi lebih bermakna.</p>
            </div>
            </div>
        </div>
        </div>
    </section>

    <?php include '../component/footer.php'; ?>

    <script>
        AOS.init();
    </script>

</body>
</html>
