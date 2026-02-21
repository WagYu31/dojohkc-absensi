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
<!-- face-api.js untuk verifikasi absen -->
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>';
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

            // ================================================
            // FACE VERIFICATION SEBELUM KIRIM ABSEN
            // ================================================
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/weights';
            const storedDescriptorRaw = <?php echo !empty($row_user['face_descriptor']) ? $row_user['face_descriptor'] : 'null'; ?>;

            // Overlay canvas untuk real-time face detection
            const overlayCanvas = document.getElementById('canvas');
            let faceApiReady = false;
            let detectionInterval = null;
            let verifiedDescriptor = null;

            // Load face-api models
            Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL),
                faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
            ]).then(() => {
                faceApiReady = true;
                console.log('face-api.js ready');
                // Mulai deteksi wajah real-time overlay
                startFaceOverlay();
            }).catch(e => {
                console.error('face-api load error:', e);
            });

            function startFaceOverlay() {
                const options = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 });
                const webcamEl = document.getElementById('webcam');
                const ctx = overlayCanvas.getContext('2d');

                detectionInterval = setInterval(async () => {
                    if (!webcamEl || webcamEl.paused || webcamEl.ended || !faceApiReady) return;
                    if (overlayCanvas.classList.contains('d-none')) {
                        overlayCanvas.style.display = 'none';
                    }
                    // Resize canvas sesuai video
                    if (overlayCanvas.width !== webcamEl.videoWidth) {
                        overlayCanvas.width  = webcamEl.videoWidth  || 640;
                        overlayCanvas.height = webcamEl.videoHeight || 480;
                    }
                    const dets = await faceapi.detectAllFaces(webcamEl, options);
                    ctx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
                    if (dets.length > 0) {
                        const resized = faceapi.resizeResults(dets, { width: overlayCanvas.width, height: overlayCanvas.height });
                        resized.forEach(det => {
                            const box = det.box;
                            ctx.strokeStyle = '#28a745';
                            ctx.lineWidth   = 3;
                            ctx.strokeRect(box.x, box.y, box.width, box.height);
                            ctx.fillStyle = 'rgba(40,167,69,0.6)';
                            ctx.fillRect(box.x, box.y - 20, 110, 20);
                            ctx.fillStyle = '#fff';
                            ctx.font = '12px Arial';
                            ctx.fillText('Wajah Terdeteksi', box.x + 4, box.y - 4);
                        });
                    }
                }, 300);
            }

            // ================================================
            var faceVerified = false; // flag verifikasi

                $(".take-photo").click(function () {
                    if (!storedDescriptorRaw) {
                        swal({
                            title: 'Wajah Belum Terdaftar!',
                            text: 'Daftarkan wajah Anda terlebih dahulu sebelum absen.',
                            icon: 'warning',
                            buttons: {
                                cancel: 'Nanti',
                                confirm: { text: 'Daftar Sekarang', closeModal: true }
                            }
                        }).then(function(ok){ if(ok) location.href='./wajah'; });
                        return;
                    }

                    if (!faceApiReady) {
                        swal({ title: 'Mohon Tunggu', text: 'AI verifikasi wajah sedang dimuat...', icon: 'info', timer: 2000 });
                        return;
                    }

                    // Stop live overlay
                    if (detectionInterval) clearInterval(detectionInterval);

                    beforeTakePhoto();

                    const webcamEl = document.getElementById('webcam');
                    const options  = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.4 });

                    faceapi.detectSingleFace(webcamEl, options)
                        .withFaceLandmarks(true)
                        .withFaceDescriptor()
                        .then(function(detection) {
                            if (!detection) {
                                swal({ title: 'Wajah Tidak Terdeteksi!', text: 'Pastikan wajah Anda terlihat jelas di kamera.', icon: 'warning' });
                                removeCapture();
                                startFaceOverlay();
                                return;
                            }

                            // Bandingkan descriptor wajah
                            const liveDescriptor   = detection.descriptor;
                            const stored           = new Float32Array(storedDescriptorRaw);
                            const distance         = faceapi.euclideanDistance(liveDescriptor, stored);
                            console.log('Face distance:', distance);

                            const THRESHOLD = 0.50; // 0.50 = keseimbangan security & kenyamanan
                            if (distance > THRESHOLD) {
                                swal({
                                    title: 'Wajah Tidak Dikenali!',
                                    text: 'Wajah Anda tidak cocok dengan data terdaftar (jarak: '+distance.toFixed(3)+'). Pastikan pencahayaan baik dan wajah terlihat jelas.',
                                    icon: 'error'
                                });
                                removeCapture();
                                startFaceOverlay();
                                return;
                            }

                            // ✅ Verifikasi berhasil!
                            faceVerified = true;
                            let picture = webcam.snap(300, 300);
                            afterTakePhoto();

                            var img  = new Image();
                            img.src  = picture;

                            var dataString = 'img=' + img.src +
                                             '&latitude=' + latitude +
                                             '&radius='   + jarak +
                                             '&shift='    + shift +
                                             '&face_verified=1';

                            $.ajax({
                                type: 'POST',
                                url:  './sw-proses?action=absent',
                                data: dataString,
                                success: function(data) {
                                    var results  = data.split('/');
                                    var res0 = results[0];
                                    var res1 = results[1];
                                    if (res0 === 'success') {
                                        swal({ title: 'Berhasil!', text: res1, icon: 'success', timer: 2000 });
                                        setTimeout("location.href = './'", 2000);
                                    } else {
                                        swal({ title: 'Oops!', text: data, icon: 'error' });
                                        removeCapture();
                                        startFaceOverlay();
                                    }
                                }
                            });
                        })
                        .catch(function(e) {
                            console.error('Face detection error:', e);
                            swal({ title: 'Error', text: 'Gagal memproses wajah: ' + e.message, icon: 'error' });
                            removeCapture();
                            startFaceOverlay();
                        });
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

