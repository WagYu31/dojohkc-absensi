<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) OR !isset($_COOKIE['COOKIES_COOKIES'])){
    header('location:./');
    exit;
}else{

$face_terdaftar = !empty($row_user['face_descriptor']);

echo'
<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Daftar Wajah</h1>
        <h4>Foto wajah Anda untuk verifikasi absensi</h4>
    </div>

    <!-- Status Card -->
    <div class="section mb-2 mt-1">
        <div class="card" id="status-card">
            <div class="card-body text-center py-2">
                '.($face_terdaftar ? '
                <span class="badge badge-success" style="font-size:14px; padding:8px 16px;">
                    <i class="fa fa-check-circle"></i> &nbsp;Wajah Sudah Terdaftar
                </span>
                <p class="text-muted mt-2 mb-0" style="font-size:12px;">Anda dapat mendaftar ulang untuk memperbarui foto wajah</p>
                ' : '
                <span class="badge badge-danger" style="font-size:14px; padding:8px 16px;">
                    <i class="fa fa-times-circle"></i> &nbsp;Wajah Belum Terdaftar
                </span>
                <p class="text-muted mt-2 mb-0" style="font-size:12px;">Foto wajah Anda untuk bisa melakukan absen</p>
                ').'
            </div>
        </div>
    </div>

    <!-- Camera Section -->
    <div class="section mb-2">
        <div class="card" style="overflow:hidden; border-radius:14px;">
            <!-- Wrapper kamera responsif -->
            <div id="cam-wrapper" style="position:relative; width:100%; background:#111; min-height:60vw; max-height:420px; overflow:hidden;">

                <!-- Video Live -->
                <video id="face-video" autoplay playsinline muted
                    style="width:100%; height:100%; object-fit:cover; display:block; position:absolute;top:0;left:0;">
                </video>

                <!-- Captured Photo -->
                <canvas id="face-canvas"
                    style="display:none; width:100%; height:100%; object-fit:cover; position:absolute;top:0;left:0;">
                </canvas>

                <!-- Overlay guide wajah — responsif -->
                <svg id="face-guide" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;">
                    <!-- Redupkan area luar oval -->
                    <defs>
                        <mask id="oval-mask">
                            <rect width="100" height="100" fill="white"/>
                            <ellipse cx="50" cy="48" rx="30" ry="38" fill="black"/>
                        </mask>
                    </defs>
                    <rect width="100" height="100" fill="rgba(0,0,0,0.35)" mask="url(#oval-mask)"/>
                    <!-- Outline oval -->
                    <ellipse cx="50" cy="48" rx="30" ry="38"
                        fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="0.8"
                        stroke-dasharray="4 2"/>
                    <!-- Sudut pojok -->
                    <path d="M22 20 L22 28" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M20 22 L28 22" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M78 20 L78 28" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M72 22 L80 22" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M22 80 L22 72" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M20 78 L28 78" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M78 80 L78 72" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M72 78 L80 78" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <!-- Label -->
                    <text x="50" y="95" text-anchor="middle" fill="rgba(255,255,255,0.75)" font-size="4.5"
                        font-family="system-ui,sans-serif">Posisikan wajah di sini</text>
                </svg>

                <!-- Status Badge -->
                <div id="cam-status" style="
                    position:absolute; top:10px; left:10px;
                    background:rgba(0,0,0,0.55); color:#fff; backdrop-filter:blur(4px);
                    padding:4px 12px; border-radius:20px; font-size:12px;
                    display:flex; align-items:center; gap:6px; z-index:5;">
                    <span id="cam-dot" style="width:8px;height:8px;border-radius:50%;background:#ffc107;display:inline-block;flex-shrink:0;"></span>
                    <span id="cam-text">Memulai kamera...</span>
                </div>

                <!-- Flip Button -->
                <button id="flip-camera" type="button" style="
                    position:absolute; top:10px; right:10px; z-index:5;
                    background:rgba(0,0,0,0.5); color:#fff; border:none;
                    width:36px; height:36px; border-radius:50%;
                    display:flex; align-items:center; justify-content:center; font-size:18px; cursor:pointer;">
                    <ion-icon name="camera-reverse-outline"></ion-icon>
                </button>

                <!-- Tombol Aktifkan Kamera (auto-disembunyikan setelah kamera ON) -->
                <div id="cam-start-overlay" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    display:flex;align-items:center;justify-content:center;
                    background:rgba(0,0,0,0.6); z-index:10;">
                    <button id="btn-start-cam" type="button" class="btn btn-success"
                        onclick="window.wajahStartCamera && window.wajahStartCamera()" style="
                        padding:12px 28px; border-radius:30px; font-size:15px; font-weight:600;
                        box-shadow:0 4px 20px rgba(0,0,0,0.4);">
                        <ion-icon name="videocam-outline" style="vertical-align:middle;"></ion-icon>
                        &nbsp;Aktifkan Kamera
                    </button>
                </div>

                <!-- Flash -->
                <div id="flash-wajah" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    background:#fff;opacity:0;pointer-events:none;z-index:8;
                    transition:opacity 0.12s; border-radius:0;"></div>
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="section mb-2">
        <div class="card">
            <div class="card-body">
                <!-- Panel Ambil Foto -->
                <div id="panel-ambil">
                    <div class="form-button-group mt-1">
                        <button type="button" id="btn-ambil" class="btn btn-success btn-block" disabled>
                            <ion-icon name="camera-outline"></ion-icon> &nbsp;Ambil Foto
                        </button>
                    </div>
                </div>

                <!-- Panel Konfirmasi (setelah foto) -->
                <div id="panel-konfirmasi" style="display:none;">
                    <p class="text-center text-success mb-2" style="font-size:13px;font-weight:600;">
                        <ion-icon name="checkmark-circle-outline"></ion-icon> Foto berhasil! Simpan atau ulangi.
                    </p>
                    <div class="row" style="margin:0;">
                        <div class="col-6 pr-1">
                            <button type="button" id="btn-ulang" class="btn btn-outline-secondary btn-block">
                                <ion-icon name="refresh-outline"></ion-icon> Ulang
                            </button>
                        </div>
                        <div class="col-6 pl-1">
                            <button type="button" id="btn-simpan" class="btn btn-success btn-block">
                                <ion-icon name="checkmark-outline"></ion-icon> Simpan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <a href="./" class="text-muted" style="font-size:12px;">
                        <ion-icon name="arrow-back-outline"></ion-icon> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="section mb-5">
        <div class="card" style="border-left:4px solid #1e7e34;">
            <div class="card-body py-2">
                <p class="mb-0" style="font-size:12px;color:#555;">
                    <ion-icon name="shield-checkmark-outline" style="color:#1e7e34;"></ion-icon>
                    <strong>Keamanan:</strong> Foto wajah diproses di server untuk verifikasi identitas.
                </p>
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->
';
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var video        = document.getElementById('face-video');
    var canvas       = document.getElementById('face-canvas');
    var camDot       = document.getElementById('cam-dot');
    var camText      = document.getElementById('cam-text');
    var btnAmbil     = document.getElementById('btn-ambil');
    var panelAmbil   = document.getElementById('panel-ambil');
    var panelKonfirmasi = document.getElementById('panel-konfirmasi');
    var flash        = document.getElementById('flash-wajah');
    var faceGuide    = document.getElementById('face-guide');
    var startOverlay = document.getElementById('cam-start-overlay');
    var btnStartCam  = document.getElementById('btn-start-cam');

    var currentStream = null;
    var facingMode    = 'user';
    var photoData     = null;

    // === START CAMERA ===
    function startCamera() {
        camDot.style.background = '#ffc107';
        camText.textContent     = 'Memulai kamera...';

        if (currentStream) {
            currentStream.getTracks().forEach(function(t) { t.stop(); });
            currentStream = null;
        }

        var constraints = {
            video: {
                facingMode: facingMode,
                width:  { ideal: 1280 },
                height: { ideal: 720 }
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                currentStream       = stream;
                video.srcObject     = stream;
                video.style.display = 'block';
                canvas.style.display = 'none';
                faceGuide.style.display = '';
                startOverlay.style.display = 'none';

                video.play().then(function() {
                    camDot.style.background = '#28a745';
                    camText.textContent     = 'Kamera Aktif ✓';
                    btnAmbil.disabled       = false;
                    panelAmbil.style.display = '';
                    panelKonfirmasi.style.display = 'none';
                    photoData = null;
                }).catch(function(e) {
                    camDot.style.background = '#dc3545';
                    camText.textContent     = 'Error: ' + e.name;
                });
            })
            .catch(function(err) {
                camDot.style.background = '#dc3545';
                camText.textContent     = err.name === 'NotAllowedError'
                    ? 'Izin kamera ditolak'
                    : 'Error: ' + err.name;
                startOverlay.style.display = 'flex';
                btnStartCam.innerHTML = '<ion-icon name="videocam-outline" style="vertical-align:middle;"></ion-icon>&nbsp;Coba Lagi';
            });
    }

    // === Expose ke global supaya onclick HTML bisa akses ===
    window.wajahStartCamera = startCamera;

    // === TOMBOL AKTIFKAN KAMERA (user gesture) ===
    if (btnStartCam) {
        btnStartCam.addEventListener('click', startCamera);
    }

    // === AMBIL FOTO ===
    btnAmbil.addEventListener('click', function() {
        flash.style.opacity = '1';
        setTimeout(function() { flash.style.opacity = '0'; }, 150);

        var w = video.videoWidth  || 640;
        var h = video.videoHeight || 480;
        canvas.width  = w;
        canvas.height = h;

        var ctx = canvas.getContext('2d');
        // Mirror jika selfie
        if (facingMode === 'user') {
            ctx.translate(w, 0);
            ctx.scale(-1, 1);
        }
        ctx.drawImage(video, 0, 0, w, h);
        // Reset transform
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        photoData = canvas.toDataURL('image/jpeg', 0.85);

        // Tampilkan foto
        video.style.display   = 'none';
        canvas.style.display  = 'block';
        faceGuide.style.display = 'none';

        // Stop stream
        if (currentStream) {
            currentStream.getTracks().forEach(function(t) { t.stop(); });
        }

        panelAmbil.style.display       = 'none';
        panelKonfirmasi.style.display  = '';
        camDot.style.background = '#17a2b8';
        camText.textContent     = 'Foto Diambil';
    });

    // === ULANG ===
    document.getElementById('btn-ulang').addEventListener('click', function() {
        startCamera();
    });

    // === SIMPAN ===
    document.getElementById('btn-simpan').addEventListener('click', function() {
        if (!photoData) return;
        var btn = document.getElementById('btn-simpan');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...';

        $.ajax({
            type: 'POST',
            url:  './action/face-save.php',
            data: { face_photo: photoData },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    swal({ title: 'Berhasil!', text: res.message, icon: 'success', timer: 2500 })
                        .then(function() { location.reload(); });
                } else {
                    swal({ title: 'Gagal!', text: res.message, icon: 'error' });
                    btn.disabled = false;
                    btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Simpan';
                }
            },
            error: function() {
                swal({ title: 'Error!', text: 'Koneksi gagal. Coba lagi.', icon: 'error' });
                btn.disabled = false;
                btn.innerHTML = '<ion-icon name="checkmark-outline"></ion-icon> Simpan';
            }
        });
    });

    // === FLIP ===
    document.getElementById('flip-camera').addEventListener('click', function() {
        facingMode = (facingMode === 'user') ? 'environment' : 'user';
        startCamera();
    });

    // === INIT ===
    camText.textContent     = 'Klik "Aktifkan Kamera" untuk mulai';
    camDot.style.background = '#6c757d';
});
</script>
<?php
}
include_once 'sw-mod/sw-footer.php';
} ?>
