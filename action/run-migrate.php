<?php
/**
 * run-migrate.php — jalankan 1x untuk setup kolom face_descriptor
 * Akses di browser: https://dojohkc.com/action/run-migrate.php
 * HAPUS FILE INI setelah migration berhasil!
 */
$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: text/plain; charset=utf-8');

// Cek apakah kolom sudah ada
$check = $connection->query("
    SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'employees'
      AND COLUMN_NAME  = 'face_descriptor'
    LIMIT 1
");

if (!$check) {
    die("ERROR cek kolom: " . $connection->error);
}

$row = $check->fetch_assoc();

if (!$row) {
    // Kolom belum ada sama sekali — ADD COLUMN
    echo "Kolom face_descriptor : BELUM ADA\n";
    $sql = "ALTER TABLE `employees`
            ADD COLUMN `face_descriptor` MEDIUMTEXT COLLATE utf8mb4_unicode_ci";
    if ($connection->query($sql)) {
        echo "Status               : BERHASIL ditambahkan (MEDIUMTEXT)\n";
    } else {
        echo "ERROR ADD            : " . $connection->error . "\n";
    }
} else {
    $current = strtolower($row['COLUMN_TYPE']);
    echo "Kolom saat ini       : " . strtoupper($current) . "\n";

    if (strpos($current, 'mediumtext') !== false || strpos($current, 'longtext') !== false) {
        echo "Status               : Sudah OK (tidak perlu migrasi)\n";
    } else {
        // Ada tapi masih TEXT — MODIFY ke MEDIUMTEXT
        $sql = "ALTER TABLE `employees`
                MODIFY COLUMN `face_descriptor` MEDIUMTEXT COLLATE utf8mb4_unicode_ci";
        if ($connection->query($sql)) {
            echo "Status               : BERHASIL diubah ke MEDIUMTEXT\n";
        } else {
            echo "ERROR MODIFY         : " . $connection->error . "\n";
        }
    }
}

echo "\nSelesai. Silakan hapus: action/run-migrate.php\n";
