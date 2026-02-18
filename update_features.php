<?php
require_once 'sw-library/sw-config.php';
$features = [
  ["icon"=>"camera-outline","color"=>"red","title"=>"Face ID","desc"=>"Absen dengan Face ID menggunakan teknologi AI dan deteksi lokasi GPS radius otomatis."],
  ["icon"=>"location-outline","color"=>"gold","title"=>"Deteksi Radius","desc"=>"Sistem memverifikasi lokasi Anda berada dalam radius dojo sebelum bisa absen."],
  ["icon"=>"document-text-outline","color"=>"green","title"=>"Izin","desc"=>"Ajukan izin langsung dari aplikasi dan pantau status persetujuannya."],
  ["icon"=>"stats-chart-outline","color"=>"blue","title"=>"Riwayat Lengkap","desc"=>"Lihat riwayat kehadiran, keterlambatan, dan rekap bulanan secara detail."]
];
$connection->query("UPDATE landing_settings SET setting_value='".json_encode($features)."' WHERE setting_key='features_json'");
echo "Done! " . $connection->affected_rows . " rows updated\n";
