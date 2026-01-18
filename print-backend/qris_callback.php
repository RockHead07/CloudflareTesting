<?php
include "db.php";
include "config/payment.php";

$json = file_get_contents("php://input");
$data = json_decode($json, true);

$order_id = $data['order_id']; // PRINT-12
$job_id = (int)str_replace("PRINT-", "", $order_id);

if ($data['transaction_status'] === 'settlement') {
  $conn->query("
    UPDATE print_jobs
    SET payment_status='paid'
    WHERE id=$job_id
  ");
}

http_response_code(200);
