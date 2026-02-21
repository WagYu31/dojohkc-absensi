<?php
/**
 * face-save.php
 * Endpoint AJAX: kirim foto ke Python face-service untuk encode,
 * simpan face encoding (128-d) ke database employees.
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

$row_user     = $result_user->fetch_assoc();
$employees_id = (int)$row_user['id'];

// ─── Terima foto base64 ────────────────────────────────────────────────────
if (empty($_POST['face_photo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Foto wajah tidak ditemukan']);
    exit;
}

$photo_base64 = $_POST['face_photo'];

if (!preg_match('/^data:image\/(jpeg|png|webp);base64,/', $photo_base64)) {
    echo json_encode(['status' => 'error', 'message' => 'Format foto tidak valid']);
    exit;
}

// ─── Panggil Python face-service untuk encoding ────────────────────────────
$face_url = (getenv('FACE_SERVICE_URL') ?: 'http://face-service:8000') . '/api/face/encode';

$payload = json_encode(['photo_base64' => $photo_base64]);

$opts = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\nContent-Length: " . strlen($payload) . "\r\n",
        'content' => $payload,
        'timeout' => 30,
    ]
];

$context  = stream_context_create($opts);
$response = @file_get_contents($face_url, false, $context);

if ($response === false) {
    // Fallback: simpan foto langsung (tanpa Python, backward compat)
    $safe_descriptor = $connection->real_escape_string('photo:' . $photo_base64);
    $msg = 'Wajah didaftarkan (mode foto). Pastikan face-service berjalan untuk verifikasi AI.';
} else {
    $result = json_decode($response, true);

    if (!$result || $result['status'] !== 'success') {
        $detail = $result['detail'] ?? ($result['message'] ?? 'Gagal encode wajah');
        echo json_encode(['status' => 'error', 'message' => $detail]);
        exit;
    }

    // Simpan encoding (128-d float array) sebagai JSON
    $encoding_json   = json_encode($result['encoding']);
    $safe_descriptor = $connection->real_escape_string($encoding_json);
    $msg = 'Wajah Anda berhasil didaftarkan dengan AI Python! Sekarang Anda bisa melakukan absen.';
}

// ─── Simpan ke database ────────────────────────────────────────────────────
$update = "UPDATE employees SET face_descriptor = '$safe_descriptor' WHERE id = $employees_id";
if ($connection->query($update)) {
    echo json_encode(['status' => 'success', 'message' => $msg]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $connection->error]);
}
