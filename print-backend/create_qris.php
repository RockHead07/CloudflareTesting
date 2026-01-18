<?php
include "db.php";
include "config/payment.php";

$job_id = (int)$_POST['job_id'];

$payload = [
  "payment_type" => "qris",
  "transaction_details" => [
    "order_id" => "PRINT-$job_id",
    "gross_amount" => 10000
  ]
];

$ch = curl_init(MIDTRANS_URL);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
    "Accept: application/json",
    "Authorization: Basic " . base64_encode(MIDTRANS_SERVER_KEY . ":")
  ],
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

echo json_encode([
  "qr_url" => $data['actions'][0]['url']
]);
