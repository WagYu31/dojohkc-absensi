<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER'])){

}else{
  echo'<!-- App Capsule -->
    <div id="appCapsule">
        <div class="section mt-3 text-center">
            <div class="avatar-section">
                <input type="file" class="upload" name="file" id="avatar" accept=".jpg, .jpeg, ,gif, .png" capture="camera">
                <a href="#">';
                if($row_user['photo'] ==''){
                echo'<img src="'.$base_url.'sw-content/avatar.jpg" alt="image" class="imaged w100 rounded">';
                }else{
                    echo'
                    <img src="timthumb?src='.$base_url.'sw-content/karyawan/'.$row_user['photo'].'&h=100&w=105" alt="avatar" class="imaged w100 rounded">';}
                        echo'
                    <span class="button">
                        <ion-icon name="camera-outline"></ion-icon>
                    </span>
                </a>
            </div>
        </div>

        <div class="section mt-2 mb-2">
            <div class="section-title">Profil</div>
            <div class="card">
                <div class="card-body">
                    <form id="update-profile">
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="text4">NO HP</label>
                                <input type="text" class="form-control" name="employees_code" value="'.$row_user['employees_code'].'" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="email4">Nama</label>
                                <input type="text" class="form-control" id="name" name="employees_name" value="'.$row_user['employees_name'].'" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>


                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4">Tahun Ajaran</label>
                                <select class="form-control custom-select" name="tahun_pelajaran" required>';
                                $query ="SELECT * FROM tahun_pelajaran";
                                $result =  $connection->query($query);
                                while($data = $result->fetch_assoc()){
                                    echo'<option value="'.$data['tahun_pelajaran_id'].'">'.$data['tahun_mulai'].' - '.$data['tahun_selesai'].'</option>';
                                }
                                echo'
                            </select>
                            </div>
                        </div>


                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4">Kategori</label>
                                <select class="form-control custom-select" name="position_id">';
                                      $query="SELECT * from position order by position_name ASC";
                                      $result = $connection->query($query);
                                      while($rowa = $result->fetch_assoc()) { 
                                      if($rowa['position_id'] == $row_user['position_id']){
                                        echo'<option value="'.$rowa['position_id'].'" selected>'.$rowa['position_name'].'</option>';
                                      }else{
                                        echo'<option value="'.$rowa['position_id'].'">'.$rowa['position_name'].'</option>';
                                      }
                                      }echo'
                                </select>
                            </div>
                        </div>

                    

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="password4">Lokasi Penempatan</label>
                                <select class="form-control custom-select" name="building_id">';
                                $query  ="SELECT building_id,name,address from building";
                                $result = $connection->query($query);
                                while($row = $result->fetch_assoc()) {
                                    if($row['building_id'] == $row_user['building_id']){ 
                                        echo'<option value="'.$row['building_id'].'" selected>'.$row['name'].'</option>';
                                    }else{
                                        echo'<option value="'.$row['building_id'].'">'.strip_tags($row['name']).'</option>';
                                    }
                                }echo'
                                </select>
                            </div>
                        </div>

                        <hr>
                            <button type="submit" class="btn btn-success mr-1 btn-block btn-profile">Simpan</button>
                        
                    </form>

                </div>
            </div>
        </div>

      
        <div class="section mt-2 mb-2">
            <div class="section-title">Update Password</div>
            <div class="card">
                <div class="card-body">
                    <form id="update-password">
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="text4">Kode Pegawai</label>
                                <input type="email" class="form-control" name="employees_email" value="'.$row_user['employees_email'].'" style="background:#eeeeee" readonly>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="email4">Password baru</label>
                                <input type="password" class="form-control" name="employees_password" id="employees_password" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-success mr-1 btn-block">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
        
    </div>
    <!-- * App Capsule -->

    <!-- === FACE VERIFICATION STATUS === -->
    <div class="section mt-2 mb-5">
        <div class="section-title">
            <ion-icon name="scan-outline"></ion-icon> Verifikasi Wajah
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0" style="font-size:13px;"><strong>Status Wajah:</strong></p>';
                        if(!empty($row_user['face_descriptor'])){
                            echo'
                        <span class="badge badge-success mt-1" style="font-size:12px;">
                            <ion-icon name="checkmark-circle-outline"></ion-icon> Sudah Terdaftar
                        </span>';
                        }else{
                            echo'
                        <span class="badge badge-danger mt-1" style="font-size:12px;">
                            <ion-icon name="close-circle-outline"></ion-icon> Belum Terdaftar
                        </span>';
                        }
                        echo'
                        <p class="text-muted mt-1 mb-0" style="font-size:11px;">Wajah digunakan untuk verifikasi saat absen</p>
                    </div>
                    <div>
                        <a href="./wajah" class="btn btn-'.(!empty($row_user['face_descriptor']) ? 'outline-success' : 'danger').' btn-sm">
                            <ion-icon name="scan-outline"></ion-icon>
                            '.(!empty($row_user['face_descriptor']) ? 'Perbarui' : 'Daftar').'
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- === END FACE === -->
';

  }
  include_once 'sw-mod/sw-footer.php';
} ?>