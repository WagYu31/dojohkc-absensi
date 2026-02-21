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

                <!-- Video Live (getUserMedia mode) -->
                <video id="face-video" autoplay playsinline muted
                    style="width:100%; height:100%; object-fit:cover; display:none; position:absolute;top:0;left:0;">
                </video>

                <!-- Preview foto (setelah capture) -->
                <canvas id="face-canvas"
                    style="display:none; width:100%; height:100%; object-fit:cover; position:absolute;top:0;left:0;">
                </canvas>

                <!-- Preview foto dari file input -->
                <img id="face-preview" alt="Preview"
                    style="display:none; width:100%; height:100%; object-fit:cover; position:absolute;top:0;left:0;">

                <!-- Overlay guide wajah — responsif via SVG -->
                <svg id="face-guide" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;">
                    <defs>
                        <mask id="oval-mask">
                            <rect width="100" height="100" fill="white"/>
                            <ellipse cx="50" cy="48" rx="30" ry="38" fill="black"/>
                        </mask>
                    </defs>
                    <rect width="100" height="100" fill="rgba(0,0,0,0.35)" mask="url(#oval-mask)"/>
                    <ellipse cx="50" cy="48" rx="30" ry="38"
                        fill="none" stroke="rgba(255,255,255,0.85)" stroke-width="0.8"
                        stroke-dasharray="4 2"/>
                    <path d="M22 20 L22 28" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M20 22 L28 22" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M78 20 L78 28" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M72 22 L80 22" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M22 80 L22 72" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M20 78 L28 78" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M78 80 L78 72" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <path d="M72 78 L80 78" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round"/>
                    <text x="50" y="95" text-anchor="middle" fill="rgba(255,255,255,0.75)" font-size="4.5"
                        font-family="system-ui,sans-serif">Posisikan wajah di sini</text>
                </svg>

                <!-- Status Badge -->
                <div id="cam-status" style="
                    position:absolute; top:10px; left:10px;
                    background:rgba(0,0,0,0.55); color:#fff; backdrop-filter:blur(4px);
                    padding:4px 12px; border-radius:20px; font-size:12px;
                    display:flex; align-items:center; gap:6px; z-index:5;">
                    <span id="cam-dot" style="width:8px;height:8px;border-radius:50%;background:#6c757d;display:inline-block;flex-shrink:0;"></span>
                    <span id="cam-text">Siap</span>
                </div>

                <!-- Tombol Flip (live camera mode) -->
                <button id="flip-camera" type="button" style="
                    display:none; position:absolute; top:10px; right:10px; z-index:5;
                    background:rgba(0,0,0,0.5); color:#fff; border:none;
                    width:36px; height:36px; border-radius:50%;
                    align-items:center; justify-content:center; font-size:18px; cursor:pointer;">
                    <ion-icon name="camera-reverse-outline"></ion-icon>
                </button>

                <!-- Tombol Ambil Foto — bekerja di SEMUA device & browser tanpa getUserMedia -->
                <label for="file-camera" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;
                    background:rgba(0,0,0,0.5); z-index:10; cursor:pointer;" id="lbl-camera">

                    <span class="btn btn-success" style="
                        padding:16px 36px;border-radius:30px;font-size:17px;font-weight:700;
                        box-shadow:0 6px 24px rgba(0,0,0,0.5);pointer-events:none;min-width:220px;
                        display:flex;align-items:center;justify-content:center;gap:10px;">
                        <ion-icon name="camera-outline" style="font-size:22px;"></ion-icon>
                        Ambil Foto Wajah
                    </span>
                    <span style="color:rgba(255,255,255,0.65);font-size:13px;">Tap untuk membuka kamera</span>
                </label>
                <input type="file" id="file-camera" accept="image/*" capture="user" style="display:none;">

                <!-- Flash -->
                <div id="flash-wajah" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    background:#fff;opacity:0;pointer-events:none;z-index:8;
                    transition:opacity 0.12s;"></div>
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="section mb-2">
        <div class="card">
            <div class="card-body">
                <!-- Panel Ambil Foto (hanya muncul saat live camera aktif) -->
                <div id="panel-ambil" style="display:none;">
                    <div class="form-button-group mt-1">
                        <button type="button" id="btn-ambil" class="btn btn-success btn-block">
                            <ion-icon name="camera-outline"></ion-icon> &nbsp;Ambil Foto
                        </button>
                    </div>
                </div>

                <!-- Panel Konfirmasi (setelah foto diambil) -->
                <div id="panel-konfirmasi" style="display:none;">
                    <p class="text-center text-success mb-2" style="font-size:13px;font-weight:600;">
                        <ion-icon name="checkmark-circle-outline"></ion-icon> Foto siap. Simpan atau ulangi.
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
(function() {
    var photoData    = null;
    var camDot       = document.getElementById('cam-dot');
    var camText      = document.getElementById('cam-text');
    var faceGuide    = document.getElementById('face-guide');
    var lblCamera    = document.getElementById('lbl-camera');
    var previewEl    = document.getElementById('face-preview');
    var panelAmbil      = document.getElementById('panel-ambil');
    var panelKonfirmasi = document.getElementById('panel-konfirmasi');

    function setStatus(text, color) {
        camText.textContent     = text;
        camDot.style.background = color || '#6c757d';
    }

    /* ─── FILE INPUT: buka kamera native (mobile) atau file picker (desktop) ─── */
    document.getElementById('file-camera').addEventListener('change', function(e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;

        setStatus('Memproses foto...', '#ffc107');

        // Resize ke max 480px supaya base64 < 50KB (aman untuk DB)
        var img    = new Image();
        var reader = new FileReader();

        reader.onload = function(ev) {
            img.onload = function() {
                var MAX  = 480;
                var w    = img.width,  h = img.height;
                if (w > MAX || h > MAX) {
                    var ratio = Math.min(MAX / w, MAX / h);
                    w = Math.round(w * ratio);
                    h = Math.round(h * ratio);
                }
                var cvs = document.createElement('canvas');
                cvs.width  = w;
                cvs.height = h;
                cvs.getContext('2d').drawImage(img, 0, 0, w, h);
                photoData = cvs.toDataURL('image/jpeg', 0.80);

                /* Tampilkan preview */
                previewEl.src = photoData;
                previewEl.style.display = 'block';
                lblCamera.style.display = 'none';
                if (faceGuide) faceGuide.style.display = 'none';

                panelAmbil.style.display      = 'none';
                panelKonfirmasi.style.display = '';
                setStatus('Foto siap ✓', '#28a745');
            };
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
        this.value = ''; // reset supaya bisa pilih ulang
    });

    /* ─── ULANG ─── */
    document.getElementById('btn-ulang').addEventListener('click', function() {
        photoData = null;
        previewEl.style.display = 'none';
        lblCamera.style.display = 'flex';
        if (faceGuide) faceGuide.style.display = '';
        panelAmbil.style.display      = 'none';
        panelKonfirmasi.style.display = 'none';
        setStatus('Siap', '#6c757d');
    });

    /* ─── SIMPAN ─── */
    document.getElementById('btn-simpan').addEventListener('click', function() {
        if (!photoData) return;
        var btn = this;
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

    setStatus('Siap', '#6c757d');
}());
</script>
<?php
}
include_once 'sw-mod/sw-footer.php';
} ?>
