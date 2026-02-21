<?php
/**
 * face-save.php
 * Endpoint AJAX: simpan foto wajah atau face descriptor ke database employees
 * Dipanggil via POST dari halaman wajah.php
 */

$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: application/json');

// Validasi: user harus login
if (!isset($_COOKIE['COOKIES_MEMBER']) || !isset($_COOKIE['COOKIES_COOKIES'])) {
    echo json_encode(['status' => 'error', 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan']);
    exit;
}

$cookie_member  = $connection->real_escape_string($_COOKIE['COOKIES_MEMBER']);
$cookie_cookies = $connection->real_escape_string($_COOKIE['COOKIES_COOKIES']);

$query_user = "SELECT id, employees_name FROM employees 
               WHERE employees_email = '$cookie_member' 
               AND created_cookies = '$cookie_cookies' 
               LIMIT 1";
$result_user = $connection->query($query_user);

if (!$result_user || $result_user->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid, silakan login ulang']);
    exit;
}

$row_user = $result_user->fetch_assoc();
$employees_id = (int)$row_user['id'];
$safe_descriptor = null;

// === Mode 1: Terima foto sebagai base64 (pendekatan baru - cepat) ===
if (!empty($_POST['face_photo'])) {
    $photo_base64 = $_POST['face_photo'];
    
    // Validasi format base64 image
    if (!preg_match('/^data:image\/(jpeg|png|webp);base64,/', $photo_base64)) {
        echo json_encode(['status' => 'error', 'message' => 'Format foto tidak valid']);
        exit;
    }

    // Simpan base64 langsung ke face_descriptor (kompatibel dengan kolom existing)
    // Format khusus: "photo:<base64>" agar bisa dibedakan dari descriptor angka
    $safe_descriptor = $connection->real_escape_string('photo:' . $photo_base64);

// === Mode 2: Terima face descriptor JSON (mode lama, backward compat) ===
} elseif (!empty($_POST['face_descriptor'])) {
    $raw_descriptor = trim($_POST['face_descriptor']);
    $decoded = json_decode($raw_descriptor, true);
    if (!is_array($decoded) || count($decoded) !== 128) {
        echo json_encode(['status' => 'error', 'message' => 'Format data wajah tidak valid']);
        exit;
    }
    $sanitized = array_map('floatval', $decoded);
    $safe_descriptor = $connection->real_escape_string(json_encode($sanitized));

} else {
    echo json_encode(['status' => 'error', 'message' => 'Data wajah tidak ditemukan']);
    exit;
}

// Simpan ke database
$update_query = "UPDATE employees SET face_descriptor = '$safe_descriptor' WHERE id = $employees_id";
$update_result = $connection->query($update_query);

if ($update_result) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Wajah Anda berhasil didaftarkan! Sekarang Anda bisa melakukan absen.'
    ]);
} else {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Gagal menyimpan data: ' . $connection->error
    ]);
}
