<?php
/**
 * face-verify.php
 * Verifikasi wajah live vs foto terdaftar via Face++ /compare API.
 * Dipanggil dari halaman absent.
 */

$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: application/json');

if (!isset($_COOKIE['COOKIES_MEMBER']) || !isset($_COOKIE['COOKIES_COOKIES'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Anda harus login']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$cookie_member  = $connection->real_escape_string($_COOKIE['COOKIES_MEMBER']);
$cookie_cookies = $connection->real_escape_string($_COOKIE['COOKIES_COOKIES']);

$query = "SELECT id, face_descriptor FROM employees 
          WHERE employees_email = '$cookie_member' 
          AND created_cookies = '$cookie_cookies' LIMIT 1";
$res   = $connection->query($query);

if (!$res || $res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Sesi tidak valid']);
    exit;
}

$row       = $res->fetch_assoc();
$face_data = $row['face_descriptor'] ?? '';

if (empty($face_data)) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Wajah belum terdaftar. Daftar terlebih dahulu.']);
    exit;
}

if (empty($_POST['live_photo'])) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Foto tidak diterima']);
    exit;
}

// ─── Ekstrak foto terdaftar ────────────────────────────────────────────────
if (str_starts_with($face_data, 'facepp:')) {
    $stored_photo = substr($face_data, 7); // hapus prefix "facepp:"
} elseif (str_starts_with($face_data, 'photo:')) {
    $stored_photo = substr($face_data, 6); // format lama - tetap coba di-compare
} else {
    // Format lama (JSON encoding dari Python service) — tidak kompatibel
    echo json_encode([
        'status'  => 'error',
        'match'   => false,
        'message' => 'Data wajah lama tidak kompatibel. Silakan daftar ulang wajah Anda.'
    ]);
    exit;
}

// ─── Pastikan API Key sudah diisi ─────────────────────────────────────────
if (FACEPP_API_KEY === 'YOUR_API_KEY_HERE') {
    // Mode bypass (API belum dikonfigurasi) — izinkan absen tanpa verifikasi ketat
    // HAPUS ini setelah mengisi API key!
    echo json_encode([
        'status'     => 'success',
        'match'      => true,
        'confidence' => 90.0,
        'distance'   => 0.3,
        'message'    => 'Verifikasi dilewati (API Key belum dikonfigurasi)'
    ]);
    exit;
}

// ─── Panggil Face++ /compare ──────────────────────────────────────────────
// Hapus prefix data URL dari kedua foto
$live_b64   = preg_replace('/^data:image\/[a-z]+;base64,/', '', $_POST['live_photo']);
$stored_b64 = preg_replace('/^data:image\/[a-z]+;base64,/', '', $stored_photo);

$ch = curl_init(FACEPP_API_URL . '/compare');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_POSTFIELDS     => [
        'api_key'          => FACEPP_API_KEY,
        'api_secret'       => FACEPP_API_SECRET,
        'image_base64_1'   => $live_b64,
        'image_base64_2'   => $stored_b64,
    ]
]);
$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Gagal menghubungi Face++ API. Periksa koneksi internet.']);
    exit;
}

$result = json_decode($response, true);

// Tangani error dari Face++ (misal: wajah tidak terdeteksi)
if (isset($result['error_message'])) {
    $err = $result['error_message'];
    if (str_contains($err, 'FACE_NOT_FOUND')) {
        $msg = 'Wajah tidak terdeteksi. Pastikan wajah terlihat jelas di kamera.';
    } else {
        $msg = 'Face++ Error: ' . $err;
    }
    echo json_encode(['status' => 'error', 'match' => false, 'message' => $msg]);
    exit;
}

// Face++ /compare mengembalikan "confidence" (0-100)
// Threshold: 73 = medium security (default Face++ recommendation)
$confidence = (float)($result['confidence'] ?? 0);
$THRESHOLD  = 73.0;  // Sesuaikan: 73=standar, 80=ketat, 60=longgar
$match      = $confidence >= $THRESHOLD;

echo json_encode([
    'status'     => 'success',
    'match'      => $match,
    'confidence' => round($confidence, 1),
    'threshold'  => $THRESHOLD,
    'distance'   => round(1 - $confidence / 100, 4),
    'message'    => $match
        ? "Verifikasi berhasil ✓ (kecocokan: {$confidence}%)"
        : "Wajah tidak cocok (kecocokan: {$confidence}%, minimal: {$THRESHOLD}%)"
]);
