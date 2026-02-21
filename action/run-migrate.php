<?php
/**
 * run-migrate.php
 * Jalankan 1x untuk memperbesar kolom face_descriptor dari TEXT ke MEDIUMTEXT.
 * Akses di browser: https://dojohkc.com/action/run-migrate.php
 * HAPUS FILE INI setelah migration berhasil!
 */
$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: text/plain; charset=utf-8');

// Cek kolom sekarang
$check = $connection->query("
    SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'employees'
      AND COLUMN_NAME  = 'face_descriptor'
");

if (!$check) {
    die("ERROR: Tidak bisa cek kolom: " . $connection->error);
}

$row = $check->fetch_assoc();
$current_type = strtolower($row['COLUMN_TYPE'] ?? '');

echo "Kolom saat ini : " . strtoupper($current_type) . "\n";

if (strpos($current_type, 'mediumtext') !== false || strpos($current_type, 'longtext') !== false) {
    echo "Status         : OK - sudah MEDIUMTEXT/LONGTEXT, tidak perlu migrasi.\n";
    exit;
}

// Jalankan ALTER
$sql = "ALTER TABLE `employees`
        MODIFY COLUMN `face_descriptor` MEDIUMTEXT COLLATE utf8mb4_unicode_ci";

if ($connection->query($sql)) {
    echo "Status         : BERHASIL - face_descriptor sekarang MEDIUMTEXT\n";
    echo "Silakan hapus file ini: action/run-migrate.php\n";
} else {
    echo "ERROR          : " . $connection->error . "\n";
}
