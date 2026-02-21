<?php if(empty($connection)){
  header('location:./404');
} else {
  ob_start("minify_html");
echo'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
<title>'.$website_name.'</title>

<!-- Favicons -->
  <link rel="shortcut icon" href="'.$base_url.'/sw-content/favicon.png">
  <link rel="apple-touch-icon" href="'.$base_url.'/sw-content/favicon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="'.$base_url.'/sw-content/favicon.png">
  <link rel="apple-touch-icon" sizes="114x114" href="'.$base_url.'/sw-content/favicon.png">
  
  <meta name="robots" content="noindex">
  <meta name="description" content="'.$meta_description.'">
  <meta name="keywords" content="'.$meta_keyword.'">
  <meta name="author" content="s-widodo.com">
  <meta http-equiv="Copyright" content="'.$website_name.'">
  <meta name="copyright" content="s-widodo.com">
  <meta itemprop="image" content="sw-content/meta-tag.jpg">

  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/style.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/sw-custom.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/magnific-popup/magnific-popup.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/webcam.css">
  
</head>';

if($mod=='home'){
echo'<body onload="loadDataCounter();">';
}elseif ($mod=='cuty') {
echo'<body onload="loadDataCuty();">';
}elseif($mod=='izin'){
echo'<body onload="loadDataIzin();">';
}elseif ($mod=='history') {
echo'<body onload="loadData();">';
}elseif ($mod=='spp') {
echo'<body onload="loadDataSpp();">';
}else{
echo'<body>';
}
echo'

<body>';
if(isset($_COOKIE['COOKIES_MEMBER'])){
  echo'
<!-- App Header -->
    <div class="appHeader bg-success text-light">
        <div class="left">';
            if($mod=='absent'){
            echo'
            <a href="./" class="headerButton">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>';
            }else{
            echo'
            <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>';
            }
        echo'
        </div>
        <div class="pageTitle">
           <a href="'.$base_url.'"><img src="'.$base_url.'sw-content/'.$site_logo.'" alt="logo" class="logo"></a>
        </div>
        <div class="right">
            <div class="headerButton" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true">';

            if(file_exists('../sw-content/karyawan/'.$row_user['photo'].'')){
                echo'<img src="'.$base_url.'sw-content/avatar.jpg" alt="image" class="imaged w32">';
            }else{
                echo'
                <img src="./sw-content/karyawan/'.$row_user['photo'].'" alt="image" class="imaged w32">';
            }
              echo'
               <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';?>
                <a class="dropdown-item" onclick="location.href='./profile';" href="./profile"><ion-icon size="small" name="person-outline"></ion-icon>Profil</a>
                <a class="dropdown-item" onclick="location.href='./logout';" href="./logout"><ion-icon size="small" name="log-out-outline"></ion-icon>Keluar</a>
              </div>
            </div>
        </div>
            <div class="progress" style="display:none;position:absolute;top:50px;z-index:4;left:0px;width: 100%">
                <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">0%</span>
                </div>
            </div>
    </div>
<?php
// === BANNER PERINGATAN WAJAH BELUM TERDAFTAR ===
if(isset($_COOKIE['COOKIES_MEMBER']) && $mod != 'wajah' && $mod != 'absent' && $mod != 'login' && $mod != 'registrasi'){
    if(empty($row_user['face_descriptor'])){
        echo'
        <div class="alert" style="
            background: linear-gradient(135deg, #ff6b35, #f7c948);
            color: #fff;
            margin: 0;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            border-radius: 0;
            position: fixed;
            top: 56px;
            left: 0;
            right: 0;
            z-index: 999;">
            <ion-icon name="warning-outline" style="font-size:20px; flex-shrink:0;"></ion-icon>
            <div style="flex:1;">
                <strong>Wajah belum terdaftar!</strong><br>
                <span style="font-size:12px;">Daftarkan wajah Anda agar bisa melakukan absen.</span>
            </div>
            <a href="./wajah" style="
                background:#fff;
                color:#e65c00;
                padding: 5px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
                text-decoration: none;">
                Daftar Sekarang
            </a>
        </div>
        <div style="height: 44px;"></div>';
    }
}

echo'<!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">';
                        if(file_exists('../sw-content/karyawan/'.$row_user['photo'].'')){
                        echo'<img src="'.$base_url.'/sw-content/avatar.jpg" alt="image" class="imaged  w36">';
                        }else{
                        echo'<img src="./sw-content/karyawan/'.$row_user['photo'].'" class="imaged  w36">';
                        }
                          echo'
                        </div>
                        <div class="in">
                            <strong>'.ucfirst($row_user['employees_name']).'</strong>
                            <div class="text-muted">'.$row_user['employees_code'].'</div>
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
              
                    <!-- menu -->
                    <div class="listview-title mt-1">Absen</div>
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="./" class="item">
                                <div class="icon-box bg-success">
                                    <ion-icon name="home-outline"></ion-icon>
                                </div> Home 
                            </a>
                        </li>
    
                        <li>
                            <a href="./izin" class="item">
                                <div class="icon-box bg-success">
                                   <ion-icon name="documents-outline"></ion-icon>
                                </div>
                                  Izin
                            </a>
                        </li>
                        
                        <li>
                            <a href="./cuty" class="item">
                                <div class="icon-box bg-success">
                                  <ion-icon name="calendar-outline"></ion-icon>
                                </div>
                                  Cuti
                            </a>
                        </li>

                        <li>
                            <a href="./spp" class="item">
                                <div class="icon-box bg-success">
                                   <ion-icon name="documents-outline"></ion-icon>
                                </div>
                                 SPP
                            </a>
                        </li>

                        <li>
                            <a href="./history" class="item">
                                <div class="icon-box bg-success">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                </div>
                                   History
                            </a>
                        </li>
                      
                        <li>
                            <a href="profile" class="item">
                                <div class="icon-box bg-success">
                                    <ion-icon name="person-outline"></ion-icon>
                                </div>
                                    Profil
                            </a>
                        </li>

                        <li>
                            <a href="./wajah" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="scan-outline"></ion-icon>
                                </div>
                                    Daftar Wajah
                            </a>
                        </li>

                        </li>
                        <li>
                            <a href="./logout" class="item">
                                <div class="icon-box bg-success">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </div>
                                    Keluar
                            </a>
                        </li>

                    </ul>
                    <!-- * menu -->
                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->';
  }
 }?>