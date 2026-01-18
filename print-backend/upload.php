<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

include "db.php";

if (!isset($_FILES['file'])) {
    echo json_encode(["status"=>"error","msg"=>"File tidak ada"]);
    exit;
}

$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "status"=>"error",
        "upload_error_code"=>$file['error']
    ]);
    exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') {
    echo json_encode(["status"=>"error","msg"=>"PDF only"]);
    exit;
}

$targetDir = __DIR__ . "/uploads/";
$filename = uniqid() . ".pdf";
$targetFile = $targetDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode(["status"=>"error","msg"=>"Upload gagal"]);
    exit;
}

$stmt = $conn->prepare("
INSERT INTO print_jobs 
(printer_code, file_path, page_count, total_price)
VALUES (?, ?, ?, ?)
");

$printer = "PRINTER01";
$page = 1;
$price = 1000;

$stmt->bind_param("ssii", $printer, $filename, $page, $price);
$stmt->execute();

echo json_encode([
    "status" => "ok",
    "job_id" => $conn->insert_id
]);
