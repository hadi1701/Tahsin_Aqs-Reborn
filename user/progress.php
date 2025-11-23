<?php
session_set_cookie_params([
    'lifetime' => 0, //habis saaat browser ditutup
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict' //proteksi akses tab baru + URL paste
]);

session_start();

$user_id = $_SESSION['user_id'] ?? 0;
if (!$user_id) {
    header("Location: ../public/login.php");
    exit;
}

$expected_fingerprint = md5(
    ($_SERVER['HTTP_USER_AGENT'] ?? '') .
    ($_SERVER['REMOTE_ADDR'] ?? '')
);

if (
    empty($_SESSION['browser_fingerprint']) ||
    $_SESSION['browser_fingerprint'] !== $expected_fingerprint
) {
    session_destroy();
    header('Location: ../public/login.php');
    exit;
}

require_once $_SESSION["dir_root"] . '/module/dbconnect.php';

$pdo = db();

/* ----------------------------------------------------------
|  AMBIL DATA USER
----------------------------------------------------------- */
$stmtUser = $pdo->prepare("SELECT nama FROM daftar WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);
$nama_user = $user['nama'] ?? "User";


/* ----------------------------------------------------------
|  AMBIL KELAS + HITUNG PROGRESS (DONE ONLY)
----------------------------------------------------------- */
$stmtClasses = $pdo->prepare("
    SELECT 
        c.id AS class_id, 
        c.name AS class_name, 
        COUNT(p.id) AS total_progress, 
        SUM(CASE WHEN p.status = 'done' THEN 1 ELSE 0 END) AS completed_progress
    FROM class_members cm
    JOIN classes c ON c.id = cm.class_id
    LEFT JOIN progress p 
        ON p.class_id = c.id 
        AND p.daftar_id = :user_id
    WHERE cm.daftar_id = :user_id
    GROUP BY c.id
    ORDER BY c.name ASC
");
$stmtClasses->execute(['user_id' => $user_id]);
$classes = $stmtClasses->fetchAll(PDO::FETCH_ASSOC);


/* ----------------------------------------------------------
|  PREPARE STATEMENT DETAIL SESI
----------------------------------------------------------- */
$sessionStmt = $pdo->prepare("
    SELECT 
        session_number, 
        material, 
        notes, 
        solution,
        status
    FROM progress
    WHERE daftar_id = :user_id AND class_id = :class_id
    ORDER BY session_number ASC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Progress Belajar | Tahsin Aqsyanna</title>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background-color: #f4f4f4; font-family: 'Poppins', sans-serif; }
.card-header { background-color: #0d6efd; color: #fff; font-weight: 500; }
.progress { height: 20px; }
.session-card { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 15px; }
.session-card .card-header { background-color: #e9ecef; color: #000; font-weight: 500; }
.status-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 6px; }
.done { background: #28a745; }
.pending { background: #ffc107; }
</style>
</head>

<body>

<div class="container py-4">

    <h2 class="mb-3">Halo, <?= htmlspecialchars($nama_user) ?></h2>
    <p>Berikut progress belajar Anda di kelas yang diikuti:</p>

    <div class="row">

    <?php foreach($classes as $class): ?>
        
        <?php
            $percent = $class['total_progress'] > 0 
                ? ($class['completed_progress'] / $class['total_progress']) * 100 
                : 0;

            // Ambil sesi detail
            $sessionStmt->execute([
                'user_id' => $user_id, 
                'class_id' => $class['class_id']
            ]);
            $sessions = $sessionStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="col-12 mb-4">
            <div class="card shadow-sm">

                <!-- Header Kelas -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><?= htmlspecialchars($class['class_name']) ?></span>
                    <span><?= round($percent, 1) ?>%</span>
                </div>

                <div class="card-body">

                    <!-- Progress Bar -->
                    <div class="mb-3">
                    <div class="progress" style="max-width:400px;">
                        <div 
                            class="progress-bar" 
                            role="progressbar" 
                            style="width: <?= round($percent, 1) ?>%;">
                            <?= round($percent, 1) ?>%
                        </div>
                    </div>
                    <small class="progress-info">
                        <?= $class['completed_progress'] ?> dari <?= $class['total_progress'] ?>
                    </small>
                </div>

                    <!-- Detail Session -->
                    <?php if($sessions): ?>
                        <div class="row">
                        <?php foreach($sessions as $s): ?>
                            <div class="col-md-6">
                                <div class="session-card card">
                                    <div class="card-header">
                                        Session <?= $s['session_number'] ?>
                                    </div>
                                    <div class="card-body">
                                        
                                        <!-- Status -->
                                        <p>
                                            <span class="status-dot <?= $s['status'] === 'done' ? 'done' : 'pending' ?>"></span>
                                            <strong><?= strtoupper($s['status']) ?></strong>
                                        </p>

                                        <h5 class="card-title"><?= htmlspecialchars($s['material']) ?></h5>

                                        <?php if($s['notes']): ?>
                                            <p><strong>Catatan:</strong> <?= htmlspecialchars($s['notes']) ?></p>
                                        <?php endif; ?>

                                        <?php if($s['solution']): ?>
                                            <p><strong>Solusi:</strong> <?= htmlspecialchars($s['solution']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                    <?php else: ?>
                        <p>Belum ada progress untuk kelas ini.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

    <?php if(empty($classes)): ?>
        <p>Belum ada kelas yang diikuti.</p>
    <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../module/js/setProgress.js"></script>
</body>
</html>
