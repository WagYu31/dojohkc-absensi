<?php
namespace Midtrans;
if(empty($connection)){
  echo'Koneksi tidak ditemukan';
  exit();
}else{
    require_once dirname(__FILE__) . '/Midtrans/Midtrans.php';
    \Midtrans\Config::$serverKey = $payment_server;
    \Midtrans\Config::$clientKey = $payment_client;
    \Midtrans\Config::$isProduction = false; // Gunakan true jika Anda sudah di production
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;
}
?>
