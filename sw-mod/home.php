<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER'])){
 echo'
 <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section mt-2 text-center">
            <h1>ABSENSI HKC</h1>
             <h4>09AM - 22PM</h4>
            <img src="sw-admin/sw-assets/img/logo-dojo_hkcpng.png" alt="Logo" class="logo" style="max-width: 370px; height: auto; margin: px 10;">
        </div>
        <div class="section mb-5 p-2">
            <form id="form-login">
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email1">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail Anda">
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>
        
                        <div class="form-group basic">
                            <div class="input-wrapper" style="position:relative;">
                                <label class="label" for="password1">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Kata sandi Anda" style="padding-right:40px;">
                                <span id="togglePassword" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;z-index:2;font-size:22px;color:#6c757d;">
                                    <ion-icon name="eye-off-outline"></ion-icon>
                                </span>
                            </div>
                        </div>
                        <script>
                        document.getElementById("togglePassword").addEventListener("click",function(){
                            var p=document.getElementById("password");
                            var icon=this.querySelector("ion-icon");
                            if(p.type==="password"){p.type="text";icon.setAttribute("name","eye-outline");}
                            else{p.type="password";icon.setAttribute("name","eye-off-outline");}
                        });
                        </script>
                    </div>
                </div>


                <div class="form-links mt-2">
                    <div>
                        <a href="registrasi">Mendaftar</a>
                    </div>
                    <div><a href="forgot" class="text-muted">Lupa Password?</a></div>
                </div>

                <div class="form-button-group  transparent">
                   <button type="submit" class="btn btn-primary btn-block"><ion-icon name="log-in-outline"></ion-icon> Masuk</button>
                   <a href="oauth/google" class="btn btn-danger btn-block"><ion-icon name="logo-google"></ion-icon> Masuk Dengan Google</a>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->';
}else{
 // Ambil bulan dan tahun sekarang
$bulan_sekarang = date('m');
$tahun_sekarang = date('Y');
$query_spp = "SELECT order_id, tanggal, nominal
    FROM pembayaran_spp 
    WHERE MONTH(tanggal) = $bulan_sekarang 
    AND YEAR(tanggal) = $tahun_sekarang AND status='berhasil'";
$result = $connection->query($query_spp);
echo'
<!-- App Capsule -->
    <div id="appCapsule">
        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <!-- Balance -->
                <div class="balance">
                    <div class="left">
                        <span class="title"> Selamat '.$salam.'</span>
                        <h3>'.ucfirst($row_user['employees_name']).'</h3>
                    </div>';

                    if($jumlah_absen > 0){
                        echo'
                        <div class="right">
                            <h4>'.$hari_ini.'</h4> 
                            <p>'.$jam_masuk.' - '.$jam_pulang.'</p>
                        </div>';
                        
                    }
                    echo'
                </div>
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div class="item">';
                        if($jumlah_absen > 0){
                        echo'
                        <a href="./absent&shift='.epm_encode($shift_id).'">';
                        }else{
                        echo'
                        <a href="#" class="btn-absen">';
                        }
                        echo'
                            <div class="icon-wrapper bg-success">
                                <ion-icon name="camera-outline"></ion-icon>
                            </div>
                            <strong>Absen</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./izin">
                            <div class="icon-wrapper bg-warning">
                               <ion-icon name="documents-outline"></ion-icon>
                            </div>
                            <strong>Izin</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./cuty">
                            <div class="icon-wrapper bg-primary">
                               <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <strong>Cuti</strong>
                        </a>
                    </div>
                   
                    <div class="item">
                        <a href="./history">
                            <div class="icon-wrapper bg-success">
                               <ion-icon name="document-text-outline"></ion-icon>
                            </div>
                            <strong>History</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./profile">
                            <div class="icon-wrapper bg-warning">
                               <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <strong>Profil</strong>
                        </a>
                    </div>

                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>
        <!-- Wallet Card -->


       <div class="modal fade modal-absen" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Lokasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Jam Latihan</label>
                            <select class="form-control jam-kerja" name="jam_kerja" required>
                                <option value="">Pilih Jam Latihan</option>';
                                $query_jam_kerja ="SELECT * FROM shift";
                                $result_jam_kerja = $connection->query($query_jam_kerja);
                                while($data_jam_kerja = $result_jam_kerja->fetch_assoc()){
                                    echo'
                                    <option value="'.epm_encode($data_jam_kerja['shift_id']).'">'.strip_tags($data_jam_kerja['shift_name']).' | '.$data_jam_kerja['time_in'].' - '.$data_jam_kerja['time_out'].'</option>';
                                }
                            echo'
                            </select>
                        </div>
                        <input type="hidden" class="form-control tipe-absen d-none" required>

                    </div>
                </div>
            </div>
        </div>

       

    <!-- Label Absensi Hari ini -->
    <div class="section">
        <div class="row mt-2">';
            if($jumlah_absen > 0){
                echo'
                <div class="col-6">
                    <div class="stat-box bg-success">
                        <div class="title text-white">Absen Masuk</div>
                        <div class="value text-white">'.$time_in.'</div>
                    </div>
                </div>';

                if($time_out =='00:00:00'){
                echo'
                <div class="col-6">
                    <a href="./absent&shift='.epm_encode($shift_id).'">
                        <div class="stat-box bg-success">
                            <div class="title text-white">Absen Pulang</div>
                            <div class="value text-white">Belum absen</div>
                        </div>
                    </a>
                </div>';
                }else{
                echo'
                <div class="col-6">
                    <div class="stat-box bg-success">
                        <div class="title text-white">Absen Pulang</div>
                        <div class="value text-white">'.$time_out.'</div>
                    </div>
                </div>';}
            } 
            else{
                echo'
                <div class="col-6">
                    <a href="javascript:void(0);" class="btn-absen">
                        <div class="stat-box bg-success">
                            <div class="title text-white">Absen Masuk</div>
                            <div class="value text-white">Belum absen</div>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <div class="stat-box bg-secondary">
                        <div class="title text-white">Absen Pulang</div>
                        <div class="value text-white">Belum Absen</div>
                    </div>
                </div>';
            }   
        echo' 
        </div>
    </div>
    
    <div class="section mt-2">';
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        echo'<div class="alert alert-warning">
           <p>Kode Pembayaran : '.$row['order_id'].'<br>
           Tanggal Pembayaran: '.tanggal_ind($row['tanggal']).'<br>
           Jumlah : Rp '.format_angka($row['nominal']).'</p>
        </div>';
        } else {
        echo'<div class="alert alert-danger">
                <p>Lakukan Pembayaran SPP untuk bulan ini melalui link dibwah ini<br>
                <a href="./spp" class="btn btn-success btn-md">Pembayaran SPP</a></p>
            </div>';
        }
    echo'
    </div>


    <div class="section mt-2">
        <div class="section-title mb-1">Absensi Bulan
            <select class="select select-change text-primary" required>';
                if($month ==1){echo'<option value="01" selected>Januari</option>';}else{echo'<option value="01">Januari</option>';}
                if($month ==2){echo'<option value="02" selected>Februari</option>';}else{echo'<option value="02">Februari</option>';}
                if($month ==3){echo'<option value="03" selected>Maret</option>';}else{echo'<option value="03">Maret</option>';}
                if($month ==4){echo'<option value="04" selected>April</option>';}else{echo'<option value="04">April</option>';}
                if($month ==5){echo'<option value="05" selected>Mei</option>';}else{echo'<option value="05">Mei</option>';}
                if($month ==6){echo'<option value="06" selected>Juni</option>';}else{echo'<option value="06">Juni</option>';}
                if($month ==7){echo'<option value="07" selected>Juli</option>';}else{echo'<option value="07">Juli</option>';}
                if($month ==8){echo'<option value="08" selected>Agustus</option>';}else{echo'<option value="08">Agustus</option>';}
                if($month ==9){echo'<option value="09" selected>September</option>';}else{echo'<option value="09">September</option>';}
                if($month ==10){echo'<option value="10" selected>Oktober</option>';}else{echo'<option value="10">Oktober</option>';}
                if($month ==11){echo'<option value="12" selected>November</option>';}else{echo'<option value="12">November</option>';}
                if($month ==12){echo'<option value="12" selected>Desember</option>';}else{echo'<option value="12">Desember</option>';}
              echo'
            </select><span class="text-primary">'.$year.'</span>
        </div>
        <div class="transactions">
            <div class="row">
                <div class="load-home" style="display:contents"></div>   
            </div>
            </div>
        </div>';

    $query_jadwal = "SELECT * FROM pengumuman ORDER BY pengumuman_id DESC LIMIT 2";
    $result_jadwal = $connection->query($query_jadwal);
    if($result_jadwal->num_rows > 0){
        echo'
        <div class="section mb-2">
            <div class="section-heading">
                <div class="section-title">Lomba/Latihan</div>
                <a href="jadwal" class="link">View All</a>
            </div>

             <div class="transactions">';
             while($data_jadwal = $result_jadwal->fetch_assoc()){
                echo'
                <a href="./jadwal-'.$data_jadwal['pengumuman_id'].'-'.seo_title($data_jadwal['judul']).'" class="item">
                    <div class="detail">
                        <div>
                            <strong>'.strip_tags($data_jadwal['judul']??'-').'</strong>
                            <p><i class="fa fa-calendar" aria-hidden="true"></i> '.tanggal_ind($data_jadwal['tanggal']).' - '.$data_jadwal['jam'].'</p>
                        </div>
                    </div>
                    <div class="right">
                        <div class="text-info">Detail</div>
                    </div>
                </a>';
             }
             echo'
            </div>
        </div>';
    }

        $query_artikel="SELECT artikel_id,judul,domain,foto,deskripsi,date FROM artikel WHERE active='Y' ORDER BY artikel_id DESC LIMIT 5";
        $result_artikel = $connection->query($query_artikel);
        if($result_artikel->num_rows > 0){
        echo'
        <div class="section mb-2">
            <div class="section-heading">
                <div class="section-title">Informasi</div>
                <a href="blog" class="link">View All</a>
            </div>
                <div class="carousel-small owl-carousel owl-theme">';
                    while ($data_artikel = $result_artikel->fetch_assoc()){
                    $judul = strip_tags($data_artikel['judul']);
                    if(strlen($judul ) >50)$judul= substr($judul,0,50).'..';
                    echo'
                    <a href="./blog-'.strip_tags($data_artikel['artikel_id']).'-'.strip_tags($data_artikel['domain']).'">
                        <div class="blog-card">';
                            if(file_exists('./sw-content/artikel/'.$data_artikel['foto'].'')){
                                echo'<img src="./sw-content/artikel/'.$data_artikel['foto'].'" height="180" imaged w-100>';
                            }else{
                                echo'<img src="./sw-content/thumbnail.jpg" height="180" imaged w-100>';
                            }
                            echo'
                            <div class="text">
                                <h4 class="title">'.$judul.'</h4>
                            </div>
                        </div>
                    </a>';
                    }
                echo'
            </div>
        </div>';
        }
    echo'
      <div class="section mt-2 mb-2">
            <div class="section-title">1 Minggu Terakhir</div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-dark rounded bg-success">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jam Masuk</th>
                                <th scope="col">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $query_absen="SELECT presence_date,time_in,time_out FROM presence WHERE YEARWEEK(presence_date)=YEARWEEK(NOW()) AND employees_id='$row_user[id]' ORDER BY presence_id DESC LIMIT 6";
                        $result_absen = $connection->query($query_absen);
                        if($result_absen->num_rows > 0){
                            while ($row_absen= $result_absen->fetch_assoc()) {
                            echo'
                            <tr>
                                <th scope="row">'.tgl_ind($row_absen['presence_date']).'</th>
                                <td>'.$row_absen['time_in'].'</td>
                                <td>'.$row_absen['time_out'].'</td>
                            </tr>';
                        }}
                        echo'
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
    </div>';

    }
  include_once 'sw-mod/sw-footer.php';
} ?>