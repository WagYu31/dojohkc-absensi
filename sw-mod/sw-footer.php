<?php if(empty($connection)){
	header('location:./404');
} else {

if(isset($_COOKIE['COOKIES_MEMBER'])){
if($mod=='absent'){}else{
echo'
<div class="appBottomMenu">
        <a href="./" class="item">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>

        <a href="izin" class="item">
            <div class="col">
                <ion-icon name="document-text-outline"></ion-icon>
                <strong>Izin</strong>
            </div>
        </a>

        <a href="./cuty" class="item">
            <div class="col">
               <ion-icon name="calendar-outline"></ion-icon>
                <strong>Cuty</strong>
            </div>
        </a>

        <a href="./history" class="item">
            <div class="col">
                 <ion-icon name="document-text-outline"></ion-icon>
                <strong>History</strong>
            </div>
        </a>

        
        <a href="./profile" class="item">
            <div class="col">
                <ion-icon name="person-outline"></ion-icon>
                <strong>Profil</strong>
            </div>
        </a>
    </div>
<!-- * App Bottom Menu -->';
}
}
ob_end_flush();
echo'
<footer class="text-muted text-center" style="display:none">
   <p>© 2021 - '.$year.' '.$site_name.' - Design By: <span id="credits"><a class="credits_a" href="https://s-widodo.com" target="_blank">'.$site_name.'</a></span></p>
</footer>
<!-- ///////////// Js Files ////////////////////  -->
<!-- Jquery -->
<script src="'.$base_url.'sw-mod/sw-assets/js/lib/jquery-3.4.1.min.js"></script>
<!-- Bootstrap-->
<script src="'.$base_url.'sw-mod/sw-assets/js/lib/popper.min.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/lib/bootstrap.min.js"></script>
<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" crossorigin="anonymous"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/plugins/owl-carousel/owl.carousel.min.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/base.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/sweetalert.min.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/webcame/webcam-easy.min.js"></script>';
if($mod =='absent'){
echo'
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
';
}

if($mod =='history' OR $mod=='cuty' OR $mod=='izin'){
echo'
<script src="'.$base_url.'sw-mod/sw-assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="'.$base_url.'sw-mod/sw-assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
<script>
    $(".datepicker").datepicker({
        format: "dd-mm-yyyy",
        "autoclose": true
    }); 
    
</script>';
}
echo'
<script src="'.$base_url.'/sw-mod/sw-assets/js/sw-script.js"></script>';
if ($mod =='absent'){?>
<script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
<script type="text/javascript">
    var latitude_building =L.latLng(<?php echo $row_building['latitude_longtitude'];?>);
    navigator.geolocation.getCurrentPosition(function(location) {
    var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
    var markerFrom = L.circleMarker(latitude_building, { color: "#F00", radius: 10 });
    var markerTo =  L.circleMarker(latlng);
    var from = markerFrom.getLatLng();
    var to = markerTo.getLatLng();
    var jarak = from.distanceTo(to).toFixed(0);
    var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
    $("#latitude").text(latitude);
    $("#jarak").text(jarak);
    var radius ='<?php echo $row_building['radius'];?>';
        if (<?php echo $row_building['radius'];?> > jarak){
            swal({title: 'Success!', text:'Posisi Anda saat ini dalam radius', icon: 'success', timer: 3000,});
            $(".result-radius").html('Posisi Anda saat ini dalam radius');
            console.log('radius: '+radius);
            console.log('jarak: '+jarak);
        }else{
            swal({title: 'Oops!', text:'Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!', icon: 'error', timer: 3000,});
            $(".result-radius").html('Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!');
            console.log('radius: '+radius);
            console.log('jarak: '+jarak);
        }
       
        <?php if(!empty($_GET['shift'])){?>
        var shift = '<?php echo epm_decode($_GET['shift'])?>';
        <?php }else{?>
        var shift = '0';
        <?php }?>
       
            const webcamElement = document.getElementById('webcam');
            const canvasElement = document.getElementById('canvas');
            const snapSoundElement = document.getElementById('snapSound');
            const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);

            $('.md-modal').addClass('md-show');
            cameraStarted();
            webcam.start()
            .then(result =>{
                cameraStarted();
                console.log("webcam started");
            })
            .catch(err => {
                displayError();
            });
            console.log("webcam started");
            $("#webcam-switch").change(function () {
                if(this.checked){
                    $('.md-modal').addClass('md-show');
                    webcam.start()
                        .then(result =>{
                        cameraStarted();
                        console.log("webcam started");
                        })
                        .catch(err => {
                            displayError();
                        });
                }
                else {        
                    cameraStopped();
                    webcam.stop();
                    console.log("webcam stopped");
                }        
            });

                $('.cameraFlip').click(function() {
                    webcam.flip();
                    webcam.start();  
                });


                function displayError(err = ''){
                    if(err!=''){
                        $("#errorMsg").html(err);
                    }
                    $("#errorMsg").removeClass("d-none");
                }

                function cameraStarted(){
                    $('.flash').hide();
                    $("#webcam-caption").html("on");
                    $("#webcam-control").removeClass("webcam-off");
                    $("#webcam-control").addClass("webcam-on");
                    $(".webcam-container").removeClass("d-none");
                    if( webcam.webcamList.length > 1){
                        $(".cameraFlip").removeClass('d-none');
                    }
                    $("#wpfront-scroll-top-container").addClass("d-none");
                    window.scrollTo(0, 0); 
                    //$('body').css('overflow-y','hidden');
                }

            // ================================================================
            // AUTO FACE DETECTION (Gojek Style - face-api.js TinyFaceDetector)
            // ================================================================
            const hasFaceData = <?php echo !empty($row_user['face_descriptor']) ? 'true' : 'false'; ?>;
            const MODEL_URL   = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights';

            var _faceApiReady  = false;
            var _detecting     = false;
            var _capturedDone  = false;
            var _cntTimer      = null;
            var _cntVal        = 0;

            // ─── Elemen overlay status ────────────────────────────────────
            var _statusEl = document.createElement('div');
            _statusEl.id  = 'fd-status';
            _statusEl.style.cssText = [
                'position:absolute','top:8px','left:50%','transform:translateX(-50%)',
                'background:rgba(0,0,0,0.65)','color:#fff','padding:6px 16px',
                'border-radius:20px','font-size:13px','font-weight:600',
                'z-index:20','text-align:center','white-space:nowrap',
                'backdrop-filter:blur(4px)','transition:background .3s'
            ].join(';');
            _statusEl.innerHTML = '⏳ Memuat model...';

            function _mountOverlay() {
                var container = document.querySelector('.webcam-container') ||
                                document.querySelector('.md-content')        ||
                                webcamElement.parentElement;
                if (container) {
                    container.style.position = 'relative';
                    container.appendChild(_statusEl);
                }
            }

            function _setStatus(icon, text, color) {
                _statusEl.innerHTML = icon + ' ' + text;
                _statusEl.style.background = color || 'rgba(0,0,0,0.65)';
            }

            // ─── Load face-api models ─────────────────────────────────────
            faceapi.nets.tinyFaceDetector
                .loadFromUri(MODEL_URL)
                .then(function() {
                    _faceApiReady = true;
                    console.log('[FaceAPI] model ready');
                    _setStatus('🔍', 'Posisikan wajah Anda...', 'rgba(0,0,0,0.65)');
                })
                .catch(function(e) {
                    console.warn('[FaceAPI] model load fail, fallback to manual', e);
                    _setStatus('📷', 'Tap tombol untuk absen', 'rgba(0,0,0,0.65)');
                });

            // ─── Detection loop ───────────────────────────────────────────
            function _runDetect() {
                if (_capturedDone || !_detecting) return;

                faceapi.detectAllFaces(
                    webcamElement,
                    new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.5 })
                ).then(function(detections) {
                    if (_capturedDone || !_detecting) return;

                    if (detections.length === 1) {
                        // Face OK
                        if (!_cntTimer) { _startCountdown(); }
                    } else {
                        _cancelCountdown();
                        if (detections.length === 0) {
                            _setStatus('🔍', 'Posisikan wajah Anda dalam oval...', 'rgba(0,0,0,0.65)');
                        } else {
                            _setStatus('⚠️', 'Hanya 1 orang ya!', 'rgba(180,0,0,0.75)');
                        }
                    }

                    if (!_capturedDone) setTimeout(_runDetect, 350);
                }).catch(function() {
                    if (!_capturedDone) setTimeout(_runDetect, 600);
                });
            }

            function _startCountdown() {
                _cntVal = 3;
                _setStatus('✅', 'Wajah OK! Otomatis: ' + _cntVal + 's', 'rgba(22,163,74,0.85)');
                _cntTimer = setInterval(function() {
                    _cntVal--;
                    if (_cntVal <= 0) {
                        clearInterval(_cntTimer); _cntTimer = null;
                        if (!_capturedDone) { _capturedDone = true; _doAutoCapture(); }
                    } else {
                        _setStatus('✅', 'Wajah OK! Otomatis: ' + _cntVal + 's', 'rgba(22,163,74,0.85)');
                    }
                }, 1000);
            }

            function _cancelCountdown() {
                if (_cntTimer) { clearInterval(_cntTimer); _cntTimer = null; }
            }

            // ─── Auto capture & verify ────────────────────────────────────
            function _doAutoCapture() {
                _detecting = false;
                _setStatus('📸', 'Mengambil foto...', 'rgba(37,99,235,0.85)');

                if (!hasFaceData) {
                    swal({
                        title: 'Wajah Belum Terdaftar!',
                        text:  'Daftarkan wajah Anda terlebih dahulu.',
                        icon:  'warning',
                        buttons: { cancel: 'Nanti', confirm: { text: 'Daftar Sekarang', closeModal: true } }
                    }).then(function(ok) { if (ok) location.href = './wajah'; });
                    return;
                }

                beforeTakePhoto();
                var picture = webcam.snap();
                afterTakePhoto();

                swal({ title: 'Memverifikasi Wajah...', text: 'Mencocokkan dengan data Anda...', icon: 'info', button: false });

                $.ajax({
                    type: 'POST',
                    url:  './action/face-verify.php',
                    data: { live_photo: picture },
                    dataType: 'json',
                    timeout: 30000,
                    success: function(res) {
                        if (!res.match) {
                            swal({ title: 'Wajah Tidak Cocok!', text: res.message + ' (' + res.confidence + '%)', icon: 'error' });
                            removeCapture();
                            _capturedDone = false;
                            _detecting    = true;
                            setTimeout(_runDetect, 1000);
                            return;
                        }
                        swal.close();
                        var dataString = 'img='      + picture +
                                         '&latitude=' + latitude +
                                         '&radius='   + jarak +
                                         '&shift='    + shift +
                                         '&face_verified=1' +
                                         '&face_confidence=' + res.confidence;
                        $.ajax({
                            type: 'POST',
                            url:  './sw-proses?action=absent',
                            data: dataString,
                            success: function(data) {
                                var parts = data.split('/');
                                if (parts[0] === 'success') {
                                    swal({ title: 'Berhasil!', text: parts[1], icon: 'success', timer: 2000 });
                                    setTimeout("location.href = './'", 2000);
                                } else {
                                    swal({ title: 'Oops!', text: data, icon: 'error' });
                                    removeCapture();
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal memverifikasi wajah';
                        swal({ title: 'Error!', text: msg, icon: 'error' });
                        removeCapture();
                    }
                });
            }

            // ─── Mulai deteksi saat kamera ready ─────────────────────────
            // Patch: tambahkan hook ke cameraStarted()
            var _origCameraStarted = cameraStarted;
            cameraStarted = function() {
                _origCameraStarted();
                _mountOverlay();
                _capturedDone = false;
                _detecting    = true;

                // Tunggu model siap (maks 10 detik)
                var waited = 0;
                var pollReady = setInterval(function() {
                    waited += 300;
                    if (_faceApiReady) {
                        clearInterval(pollReady);
                        _runDetect();
                    } else if (waited > 10000) {
                        clearInterval(pollReady);
                        _setStatus('📷', 'Tap tombol untuk absen (manual)', 'rgba(0,0,0,0.65)');
                    }
                }, 300);
            };

            // ─── Tombol manual (fallback) — tetap ada jika model gagal ───
            $(".take-photo").click(function () {
                if (!hasFaceData) {
                    swal({
                        title: 'Wajah Belum Terdaftar!',
                        text:  'Daftarkan wajah Anda terlebih dahulu sebelum absen.',
                        icon:  'warning',
                        buttons: { cancel: 'Nanti', confirm: { text: 'Daftar Sekarang', closeModal: true } }
                    }).then(function(ok){ if(ok) location.href='./wajah'; });
                    return;
                }
                if (!_capturedDone) { _capturedDone = true; _doAutoCapture(); }
            });


                function beforeTakePhoto(){
                    $('.flash')
                    .show() 
                    .animate({opacity: 0.3}, 500) 
                    .fadeOut(500)
                    .css({'opacity': 0.7});
                    window.scrollTo(0, 0); 
                    $('#webcam-control').addClass('d-none');
                    $('.take-photo').addClass('d-none');
                    $('.cameraFlip').addClass('d-none');
                    $('#exit-app').removeClass('d-none');
                    $('.resume-camera').removeClass('d-none');
                }

                function afterTakePhoto(){
                    webcam.stop();
                    $('#canvas').removeClass('d-none');
                }

                function removeCapture(){
                    $('#canvas').addClass('d-none');
                    $('#webcam-control').removeClass('d-none');
                    $('#cameraControls').removeClass('d-none');
                    $('.take-photo').removeClass('d-none');
                    $('#exit-app').addClass('d-none');
                    $('.resume-camera').addClass('d-none');
                    $('.cameraFlip').removeClass('d-none');
                }

                $(".resume-camera").click(function () {
                    webcam.stream()
                    .then(facingMode =>{
                        removeCapture();
                    });
                });

                $(".exit-app").click(function () {
                    removeCapture();
                    webcam.stop();
                    $('.form-hidden').show();
                    $('#webcam-app').hide();
                });
    });
</script>
<?php }?>
  <!-- </body></html> -->
  </body>
</html><?php }?>

