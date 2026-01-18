<?php
header("Content-Type: application/json");
include "db.php";

$id = $_GET['job_id'] ?? null;
if (!$id) {
  echo json_encode(["status"=>"error","message"=>"Job ID kosong"]);
  exit;
}

$stmt = $conn->prepare(
  "SELECT status FROM print_jobs WHERE id=?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
  echo json_encode(["status"=>"error","message"=>"Job tidak ditemukan"]);
  exit;
}

echo json_encode([
  "status"=>"ok",
  "data"=>$res
]);
