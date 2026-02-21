<?php
/**
 * face-verify.php
 * Endpoint AJAX: verifikasi wajah live vs encoding tersimpan via Python face-service.
 * Dipanggil dari halaman absent.
 */

$base_path = dirname(__DIR__) . '/';
include_once $base_path . 'sw-library/sw-config.php';

header('Content-Type: application/json');

// Validasi login
if (!isset($_COOKIE['COOKIES_MEMBER']) || !isset($_COOKIE['COOKIES_COOKIES'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Anda harus login terlebih dahulu']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$cookie_member  = $connection->real_escape_string($_COOKIE['COOKIES_MEMBER']);
$cookie_cookies = $connection->real_escape_string($_COOKIE['COOKIES_COOKIES']);

// Ambil data user + face encoding
$query_user = "SELECT id, employees_name, face_descriptor FROM employees 
               WHERE employees_email = '$cookie_member' 
               AND created_cookies = '$cookie_cookies' LIMIT 1";
$result_user = $connection->query($query_user);

if (!$result_user || $result_user->num_rows === 0) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Sesi tidak valid']);
    exit;
}

$row_user        = $result_user->fetch_assoc();
$face_descriptor = $row_user['face_descriptor'] ?? '';

if (empty($face_descriptor)) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Wajah belum terdaftar. Daftar terlebih dahulu.']);
    exit;
}

if (empty($_POST['live_photo'])) {
    echo json_encode(['status' => 'error', 'match' => false, 'message' => 'Foto tidak diterima']);
    exit;
}

// Cek apakah encoding tersimpan adalah JSON 128-d (dari Python), atau foto lama
// Format lama: "photo:<base64>" — perlu di-encode dulu via face-service
if (str_starts_with($face_descriptor, 'photo:')) {
    // Data lama — tidak bisa verify langsung, minta user re-register
    echo json_encode([
        'status'  => 'error',
        'match'   => false,
        'message' => 'Data wajah lama tidak kompatibel dengan AI baru. Silakan daftar ulang wajah Anda di halaman Profil.'
    ]);
    exit;
}

// Validation: harus berupa JSON array 128 float
$decoded = json_decode($face_descriptor, true);
if (!is_array($decoded) || count($decoded) !== 128) {
    echo json_encode([
        'status'  => 'error',
        'match'   => false,
        'message' => 'Format data wajah tidak valid. Silakan daftar ulang.'
    ]);
    exit;
}

// ─── Panggil Python face-service untuk verifikasi ─────────────────────────
$face_url = (getenv('FACE_SERVICE_URL') ?: 'http://face-service:8000') . '/api/face/verify';

$payload = json_encode([
    'photo_base64'    => $_POST['live_photo'],
    'stored_encoding' => $face_descriptor,
]);

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
    http_response_code(503);
    echo json_encode([
        'status'  => 'error',
        'match'   => false,
        'message' => 'Face verification service tidak tersedia. Hubungi administrator.'
    ]);
    exit;
}

// Teruskan response dari Python ke front-end
$result = json_decode($response, true);
echo json_encode($result);
