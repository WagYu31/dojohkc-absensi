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
                <p class="text-muted mt-2 mb-0" style="font-size:12px;">Arahkan wajah ke dalam lingkaran, otomatis tersimpan</p>
                ').
            '</div>
        </div>
    </div>

    <!-- Camera Section -->
    <div class="section mb-2">
        <div class="card" style="overflow:hidden; border-radius:14px;">
            <div id="cam-wrapper" style="position:relative; width:100%; background:#111; min-height:65vw; max-height:440px; overflow:hidden;">

                <!-- Video Live -->
                <video id="face-video" autoplay playsinline muted
                    style="width:100%; height:100%; object-fit:cover; display:none; position:absolute;top:0;left:0;">
                </video>

                <!-- Preview setelah capture -->
                <canvas id="face-canvas"
                    style="display:none; width:100%; height:100%; object-fit:cover; position:absolute;top:0;left:0;">
                </canvas>

                <!-- Overlay SVG Guide + Progress Ring -->
                <svg id="face-guide" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid meet"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;pointer-events:none;">
                    <defs>
                        <mask id="oval-mask">
                            <rect width="100" height="100" fill="white"/>
                            <ellipse cx="50" cy="47" rx="29" ry="37" fill="black"/>
                        </mask>
                    </defs>
                    <!-- Dark overlay outside oval -->
                    <rect width="100" height="100" fill="rgba(0,0,0,0.42)" mask="url(#oval-mask)"/>
                    <!-- Dashed guide ring (white) -->
                    <ellipse cx="50" cy="47" rx="29" ry="37"
                        fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="0.7"
                        stroke-dasharray="3 2"/>
                    <!-- Progress ring (green, animated) — rotated so fills from top -->
                    <ellipse id="oval-ring" cx="50" cy="47" rx="29" ry="37"
                        fill="none" stroke="#4ade80" stroke-width="2.2" stroke-linecap="round"
                        stroke-dasharray="0 215"
                        transform="rotate(-90 50 47)"/>
                    <!-- Corner brackets -->
                    <path d="M23 18 L23 25" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M20 21 L27 21" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M77 18 L77 25" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M73 21 L80 21" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M23 76 L23 83" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M20 79 L27 79" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M77 76 L77 83" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <path d="M73 79 L80 79" stroke="#4ade80" stroke-width="1.3" stroke-linecap="round"/>
                    <!-- Countdown number (center of oval) -->
                    <text id="oval-count" x="50" y="53" text-anchor="middle"
                        fill="white" font-size="16" font-family="system-ui,sans-serif"
                        font-weight="700" opacity="0">3</text>
                    <!-- Hint text bottom -->
                    <text id="oval-hint" x="50" y="95" text-anchor="middle"
                        fill="rgba(255,255,255,0.80)" font-size="4.2"
                        font-family="system-ui,sans-serif">Arahkan wajah ke dalam oval</text>
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

                <!-- Flip Button -->
                <button id="flip-camera" type="button" style="
                    display:none; position:absolute; top:10px; right:10px; z-index:5;
                    background:rgba(0,0,0,0.5); color:#fff; border:none;
                    width:36px; height:36px; border-radius:50%;
                    align-items:center; justify-content:center; font-size:18px; cursor:pointer;">
                    <ion-icon name="camera-reverse-outline"></ion-icon>
                </button>

                <!-- Fallback overlay (jika getUserMedia gagal) -->
                <label for="file-camera" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:14px;
                    background:rgba(0,0,0,0.5); z-index:10; cursor:pointer;" id="lbl-camera">
                    <span class="btn btn-success" style="
                        padding:16px 36px;border-radius:30px;font-size:17px;font-weight:700;
                        box-shadow:0 6px 24px rgba(0,0,0,0.5);pointer-events:none;min-width:220px;
                        display:flex;align-items:center;justify-content:center;gap:10px;">
                        <ion-icon name="camera-outline" style="font-size:22px;"></ion-icon>
                        Buka Kamera
                    </span>
                    <span style="color:rgba(255,255,255,0.65);font-size:13px;">Tap untuk membuka kamera</span>
                </label>
                <input type="file" id="file-camera" accept="image/*" capture="user" style="display:none;">

                <!-- Flash effect -->
                <div id="flash-wajah" style="
                    position:absolute;top:0;left:0;width:100%;height:100%;
                    background:#fff;opacity:0;pointer-events:none;z-index:8;
                    transition:opacity 0.15s;"></div>

                <!-- Overlay: sedang menyimpan -->
                <div id="saving-overlay" style="
                    display:none; position:absolute;top:0;left:0;width:100%;height:100%;
                    background:rgba(0,0,0,0.65); z-index:12;
                    flex-direction:column; align-items:center; justify-content:center; gap:12px; color:#fff;">
                    <i class="fa fa-spinner fa-spin" style="font-size:32px; color:#4ade80;"></i>
                    <span style="font-size:16px; font-weight:600;">Menyimpan wajah...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Error/Retry Panel (tersembunyi, muncul jika gagal) -->
    <div class="section mb-2" id="retry-section" style="display:none;">
        <div class="card" style="border-left:4px solid #dc3545;">
            <div class="card-body text-center py-3">
                <p class="text-danger mb-3" id="error-msg" style="font-size:14px;">Terjadi kesalahan</p>
                <button onclick="location.reload()" class="btn btn-outline-secondary btn-sm">
                    <ion-icon name="refresh-outline"></ion-icon> &nbsp;Coba Lagi
                </button>
            </div>
        </div>
    </div>

    <!-- File input fallback: konfirmasi manual -->
    <div class="section mb-2" id="panel-file-konfirmasi" style="display:none;">
        <div class="card">
            <div class="card-body">
                <p class="text-center text-success mb-2" style="font-size:13px;font-weight:600;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon> Foto siap. Simpan?
                </p>
                <div class="row" style="margin:0;">
                    <div class="col-6 pr-1">
                        <button type="button" onclick="location.reload()" class="btn btn-outline-secondary btn-block">
                            <ion-icon name="refresh-outline"></ion-icon> Ulang
                        </button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" id="btn-simpan-file" class="btn btn-success btn-block">
                            <ion-icon name="checkmark-outline"></ion-icon> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section mb-2">
        <div class="card">
            <div class="card-body py-2 text-center">
                <a href="./" class="text-muted" style="font-size:12px;">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali ke Beranda
                </a>
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
    try { initWajah(); } catch(e) {
        var ct = document.getElementById('cam-text');
        var cd = document.getElementById('cam-dot');
        if (ct) ct.textContent = 'Error: ' + e.message;
        if (cd) cd.style.background = '#dc3545';
        console.error('[wajah] DOMContentLoaded error:', e);
    }
});

function initWajah() {
    var photoData     = null;
    var stream        = null;
    var currentFacing = 'user';
    var RING_CIRC     = 215;
    var progress      = 0;
    var scanTimer     = null;
    var fillRate      = 100 / 30;   /* 3 seconds to fill */
    var drainRate     = fillRate * 2.5;

    var videoEl      = document.getElementById('face-video');
    var canvasEl     = document.getElementById('face-canvas');
    var lblCamera    = document.getElementById('lbl-camera');
    var flipBtn      = document.getElementById('flip-camera');
    var camDot       = document.getElementById('cam-dot');
    var camText      = document.getElementById('cam-text');
    var ovalRing     = document.getElementById('oval-ring');
    var ovalCount    = document.getElementById('oval-count');
    var ovalHint     = document.getElementById('oval-hint');
    var savingOverlay= document.getElementById('saving-overlay');
    var retrySection = document.getElementById('retry-section');
    var errorMsg     = document.getElementById('error-msg');

    /* Debug: log which elements are missing */
    if (!videoEl || !camText) {
        console.error('[wajah] Missing: videoEl='+!!videoEl+' camText='+!!camText);
        if (camText) camText.textContent = 'Gagal: elemen tidak ditemukan';
        return;
    }

    /* Reusable canvas for pixel sampling */
    var sampleCvs = document.createElement('canvas');
    sampleCvs.width = 48; sampleCvs.height = 56;
    var sampleCtx = sampleCvs.getContext('2d');

    function setStatus(text, color) {
        if (camText) camText.textContent     = text;
        if (camDot)  camDot.style.background = color || '#6c757d';
    }

    /* Check face in oval via pixel brightness (no ML needed) */
    function faceInOval() {
        if (!videoEl.videoWidth) return false;
        var vw = videoEl.videoWidth, vh = videoEl.videoHeight;
        sampleCtx.drawImage(videoEl, vw*0.22, vh*0.08, vw*0.56, vh*0.76, 0, 0, 48, 56);
        var data = sampleCtx.getImageData(0, 0, 48, 56).data;
        var sum = 0, n = data.length >> 2;
        for (var i = 0; i < data.length; i += 4) sum += (data[i]+data[i+1]+data[i+2])/3;
        var avg = sum / n;
        return avg > 55 && avg < 240;
    }

    /* Animate SVG progress ring */
    function updateRing(pct) {
        if (!ovalRing) return;
        var filled = (pct / 100) * RING_CIRC;
        ovalRing.setAttribute('stroke-dasharray', filled.toFixed(1) + ' ' + RING_CIRC);
        ovalRing.setAttribute('stroke', pct < 50 ? '#facc15' : '#4ade80');
        if (ovalCount) {
            if (pct > 10) {
                var sl = Math.ceil((100 - pct) / fillRate / 10);
                ovalCount.textContent = sl > 0 ? sl : '!';
                ovalCount.setAttribute('opacity', '0.9');
            } else {
                ovalCount.setAttribute('opacity', '0');
            }
        }
    }

    /* Scanning loop: detect face and fill ring */
    function startScan() {
        if (scanTimer) clearInterval(scanTimer);
        progress = 0;
        updateRing(0);
        setStatus('Arahkan wajah ke dalam oval', '#ffc107');

        scanTimer = setInterval(function() {
            if (!stream) { clearInterval(scanTimer); return; }
            if (faceInOval()) {
                progress = Math.min(100, progress + fillRate);
                if (progress < 100) {
                    var sl = Math.ceil((100 - progress) / fillRate / 10);
                    setStatus('Tahan... ' + sl + 's', '#4ade80');
                }
            } else {
                progress = Math.max(0, progress - drainRate);
                if (progress <= 5) setStatus('Arahkan wajah ke dalam oval', '#ffc107');
            }
            updateRing(progress);
            if (progress >= 100) { clearInterval(scanTimer); doAutoCapture(); }
        }, 100);
    }

    /* Fallback when getUserMedia unavailable or denied */
    function showFallback() {
        if (stream) { try { stream.getTracks().forEach(function(t){ t.stop(); }); } catch(x){} stream = null; }
        if (videoEl)  videoEl.style.display   = 'none';
        if (flipBtn)  flipBtn.style.display   = 'none';
        if (lblCamera) lblCamera.style.display = 'flex';
        setStatus('Tap untuk foto', '#6c757d');
    }

    /* Start getUserMedia camera stream */
    function startCamera(facing) {
        if (stream) { try { stream.getTracks().forEach(function(t){ t.stop(); }); } catch(x){} stream = null; }
        currentFacing = facing || 'user';
        setStatus('Memuat kamera...', '#ffc107');

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showFallback(); return;
        }

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: currentFacing, width:{ideal:640}, height:{ideal:480} },
            audio: false
        })
        .then(function(s) {
            stream = s;
            videoEl.srcObject = s;
            videoEl.style.display    = 'block';
            if (lblCamera) lblCamera.style.display  = 'none';
            if (flipBtn)   flipBtn.style.display    = 'flex';
            if (canvasEl)  canvasEl.style.display   = 'none';
            setStatus('Kamera aktif', '#28a745');
            setTimeout(startScan, 1200);
        })
        .catch(function(err) {
            console.warn('[wajah] getUserMedia error:', err.name, err.message);
            showFallback();
        });
    }

    /* Auto-capture frame from live video */
    function doAutoCapture() {
        setStatus('Ambil foto...', '#28a745');
        var flash = document.getElementById('flash-wajah');
        if (flash) { flash.style.opacity='1'; setTimeout(function(){ flash.style.opacity='0'; },200); }

        var MAX=480, vw=videoEl.videoWidth, vh=videoEl.videoHeight;
        var ratio = Math.min(MAX/vw, MAX/vh);
        canvasEl.width  = Math.round(vw*ratio);
        canvasEl.height = Math.round(vh*ratio);
        canvasEl.getContext('2d').drawImage(videoEl, 0, 0, canvasEl.width, canvasEl.height);
        photoData = canvasEl.toDataURL('image/jpeg', 0.82);

        try { stream.getTracks().forEach(function(t){ t.stop(); }); } catch(x){}
        stream = null;
        videoEl.style.display  = 'none';
        canvasEl.style.display = 'block';
        if (flipBtn) flipBtn.style.display = 'none';
        if (ovalRing)  { ovalRing.setAttribute('stroke-dasharray', RING_CIRC+' 0'); ovalRing.setAttribute('stroke','#4ade80'); }
        if (ovalCount) ovalCount.setAttribute('opacity','0');
        if (ovalHint)  ovalHint.textContent = 'Menyimpan...';

        setTimeout(doAutoSave, 600);
    }

    /* POST photo to face-save.php */
    function doAutoSave() {
        if (!photoData) return;
        if (savingOverlay) savingOverlay.style.display = 'flex';
        setStatus('Menyimpan...', '#1a73e8');

        /* Use vanilla XHR since jQuery may not be ready yet */
        var xhr = new XMLHttpRequest();
        xhr.open('POST', './action/face-save.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;
            if (savingOverlay) savingOverlay.style.display = 'none';
            try {
                var res = JSON.parse(xhr.responseText);
                if (res.status === 'success') {
                    setStatus('Tersimpan!', '#28a745');
                    if (ovalHint) ovalHint.textContent = 'Wajah berhasil didaftarkan!';
                    alert('Berhasil! ' + res.message);
                    location.reload();
                } else {
                    setStatus('Gagal', '#dc3545');
                    if (errorMsg) errorMsg.textContent = res.message || 'Gagal menyimpan.';
                    if (retrySection) retrySection.style.display = '';
                }
            } catch(e) {
                setStatus('Error respons', '#dc3545');
                if (errorMsg) errorMsg.textContent = 'Respons server tidak valid.';
                if (retrySection) retrySection.style.display = '';
            }
        };
        xhr.onerror = function() {
            if (savingOverlay) savingOverlay.style.display = 'none';
            setStatus('Error jaringan', '#dc3545');
            if (errorMsg) errorMsg.textContent = 'Koneksi gagal.';
            if (retrySection) retrySection.style.display = '';
        };
        xhr.send('face_photo=' + encodeURIComponent(photoData));
    }

    /* Flip camera */
    if (flipBtn) {
        flipBtn.addEventListener('click', function() {
            if (scanTimer) { clearInterval(scanTimer); scanTimer = null; }
            startCamera(currentFacing === 'user' ? 'environment' : 'user');
        });
    }

    /* File input fallback */
    var fileCamera = document.getElementById('file-camera');
    if (fileCamera) {
        fileCamera.addEventListener('change', function(e) {
            var file = e.target.files && e.target.files[0];
            if (!file) return;
            setStatus('Memproses foto...', '#ffc107');
            var img = new Image(), reader = new FileReader();
            reader.onload = function(ev) {
                img.onload = function() {
                    var MAX=480, w=img.width, h=img.height;
                    if (w>MAX||h>MAX){var r=Math.min(MAX/w,MAX/h);w=Math.round(w*r);h=Math.round(h*r);}
                    var cvs=document.createElement('canvas'); cvs.width=w; cvs.height=h;
                    cvs.getContext('2d').drawImage(img,0,0,w,h);
                    photoData = cvs.toDataURL('image/jpeg', 0.82);
                    if (lblCamera) lblCamera.style.display='none';
                    if (canvasEl) { canvasEl.width=w; canvasEl.height=h; canvasEl.getContext('2d').drawImage(img,0,0,w,h); canvasEl.style.display='block'; }
                    var pk = document.getElementById('panel-file-konfirmasi');
                    if (pk) pk.style.display = '';
                    setStatus('Foto siap', '#28a745');
                };
                img.src = ev.target.result;
            };
            reader.readAsDataURL(file);
            this.value='';
        });
    }

    /* File fallback manual save */
    var btnSimpanFile = document.getElementById('btn-simpan-file');
    if (btnSimpanFile) {
        btnSimpanFile.addEventListener('click', function() {
            this.disabled = true;
            this.textContent = 'Menyimpan...';
            doAutoSave();
        });
    }

    /* AUTO START */
    startCamera('user');
}
</script>

<?php
}
include_once 'sw-mod/sw-footer.php';
} ?>
