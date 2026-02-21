<?php
/**
 * face-save.php
 * Daftar wajah: validasi via Face++ /detect, simpan foto ke DB.
 */

$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: application/json');

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

$query_user = "SELECT id FROM employees 
               WHERE employees_email = '$cookie_member' 
               AND created_cookies = '$cookie_cookies' LIMIT 1";
$result_user = $connection->query($query_user);

if (!$result_user || $result_user->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid, silakan login ulang']);
    exit;
}

$row_user     = $result_user->fetch_assoc();
$employees_id = (int)$row_user['id'];

if (empty($_POST['face_photo'])) {
    echo json_encode(['status' => 'error', 'message' => 'Foto wajah tidak ditemukan']);
    exit;
}

$photo_base64 = $_POST['face_photo'];

if (!preg_match('/^data:image\/(jpeg|png|webp);base64,/', $photo_base64)) {
    echo json_encode(['status' => 'error', 'message' => 'Format foto tidak valid']);
    exit;
}

// ─── Validasi wajah via Face++ /detect ────────────────────────────────────
if (FACEPP_API_KEY !== 'YOUR_API_KEY_HERE') {

    // Hapus prefix data:image/...;base64, untuk dikirim ke Face++
    $base64_only = preg_replace('/^data:image\/[a-z]+;base64,/', '', $photo_base64);

    $ch = curl_init(FACEPP_API_URL . '/detect');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_POSTFIELDS     => [
            'api_key'      => FACEPP_API_KEY,
            'api_secret'   => FACEPP_API_SECRET,
            'image_base64' => $base64_only,
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $http_code !== 200) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghubungi Face++ API. Coba lagi.']);
        exit;
    }

    $result = json_decode($response, true);

    if (empty($result['faces'])) {
        echo json_encode(['status' => 'error', 'message' => 'Wajah tidak terdeteksi dalam foto. Pastikan wajah terlihat jelas dan pencahayaan cukup.']);
        exit;
    }

    if (count($result['faces']) > 1) {
        echo json_encode(['status' => 'error', 'message' => 'Terdeteksi lebih dari 1 wajah. Pastikan hanya ada 1 orang dalam foto.']);
        exit;
    }
}

// ─── Simpan foto ke database ───────────────────────────────────────────────
// Format: "facepp:<base64>" agar bisa dibedakan dari format lama
$safe_descriptor = $connection->real_escape_string('facepp:' . $photo_base64);

$update = "UPDATE employees SET face_descriptor = '$safe_descriptor' WHERE id = $employees_id";
if ($connection->query($update)) {
    echo json_encode([
        'status'  => 'success',
        'message' => 'Wajah Anda berhasil didaftarkan! Sekarang Anda bisa melakukan absen.'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $connection->error]);
}
