<?php
session_start();
 
require_once $_SESSION["dir_root"] . "/module/dbconnect.php";

header("Content-Type: application/json");

$action = $_POST["action"] ?? "";

if ($action === "toggle") {

    $id = intval($_POST["id"] ?? 0);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID invalid"]);
        exit;
    }

    // cek status lama
    $stmt = db()->prepare("SELECT status FROM progress WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
        exit;
    }

    // ===== STATUS BARU (pending <-> done) =====
    $new = ($row["status"] === "done") ? "pending" : "done";

    // update
    $upd = db()->prepare("UPDATE progress SET status = ? WHERE id = ?");
    $upd->execute([$new, $id]);

    echo json_encode([
        "status" => "success",
        "newStatus" => $new
    ]);
    exit;
}

if ($action === "delete") {

    $id = intval($_POST["id"] ?? 0);

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID invalid"]);
        exit;
    }

    $stmt = db()->prepare("DELETE FROM progress WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["status" => "success"]);
    exit;
}

// jika tidak ada action cocok
echo json_encode(["status" => "error", "message" => "Action tidak dikenal"]);
exit;
