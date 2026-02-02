<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        // Login tidak ditemukan
        setcookie("COOKIES_MEMBER", "", time()-$expired_cookie);
        setcookie("COOKIES_COOKIES", "", time()-$expired_cookie);
        session_destroy();
        header("location:./"); 
}else{
    $query_building  ="SELECT latitude_longtitude,radius FROM building WHERE building_id='$row_user[building_id]'";
    $result_building = $connection->query($query_building);
    $row_building = $result_building->fetch_assoc();
  echo'
  <!-- App Capsule -->
  <div id="appCapsule">
      <!-- Wallet Card -->
      <div class="section wallet-card-section pt-1">
          <div class="wallet-card wallet-card-custom">
              <div class="balance balance-custom">
                  <div class="left mr-2">
                      <span class="title"> Selamat '.$salam.'</span>
                      <h4>'.ucfirst($row_user['employees_name']).'</h4>
                  </div>
                  <div class="right">
                      <span class="title">'.tgl_ind($date).' </span>
                      <h4><span class="clock"></span></h4>
                  </div>

              </div>
              <!-- * Balance -->
              <div class="text-center">
              <!--<h3>'.tgl_ind($date).' - <span class="clock"></span></h3>-->
              <p>Lat-Long: <span class="latitude" id="latitude"></span></p>
            </div>
              <div class="wallet-footer text-center">
                  <div class="webcam-capture-body text-center">
                        
                    <main id="webcam-app">
                        <div class="md-modal md-effect-12">
                            <div id="app-panel" class="app-panel md-content row p-0 m-0">     
                                <div id="webcam-container" class="webcam-container col-12 p-0 m-0">
                                
                                <video id="webcam" autoplay playsinline width="640" height="480"></video>
                                <canvas id="canvas" class="d-none"></canvas>
                                    <div class="flash"></div>
                                    <audio id="snapSound" src="'.$base_url.'sw-mod/sw-assets/js/webcame/audio/snap.wav" preload = "auto"></audio>
                                </div>

                            
                                <a href="#" class="cameraFlip" title="Take Photo"><ion-icon name="camera-reverse-outline"></ion-icon></a>

                                <div id="cameraControls" class="cameraControls">
                                    <a href="#" class="resume-camera d-none" title="Resume">
                                        <ion-icon name="exit-outline"></ion-icon>
                                    </a>

                                    <a href="#" class="take-photo" title="Take Photo"><div class="material-icons"><ion-icon name="camera-outline" title="Take Foto"></ion-icon></div></a>

                                </div>
                            </div>        
                        </div>
                        
                    </main>


    </div>
    </div>
    <!-- * Wallet Footer -->
</div>
</div>
<!-- Card -->
</div>
<!-- * App Capsule -->';
  }
  include_once 'sw-mod/sw-footer.php';
} ?>