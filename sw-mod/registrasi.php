<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) OR !isset($_COOKIE['COOKIES_COOKIES'])){
 echo'
 <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section text-center">
            <h1>Mendaftar</h1>
        </div>
        <div class="section mb-5 p-2">
            <form id="form-registrasi">
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">No Hp</label>
                                <input type="text" class="form-control" id="employees_code" name="employees_code" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Nama</label>
                                <input type="text" class="form-control" id="name" name="employees_name" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="employees_email" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>


                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4">Tahun</label>
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
                        

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Kategori</label>
                                <select class="form-control" name="position_id" id="position_id"  required="">
                                  <option value="">- Pilih -</option>';
                                  $query="SELECT * from position order by position_name ASC";
                                  $result = $connection->query($query);
                                  while($row = $result->fetch_assoc()) { 
                                  echo'<option value="'.$row['position_id'].'">'.$row['position_name'].'</option>';
                                  }echo'
                                </select>
                            </div>
                        </div>


                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Lokasi</label>
                                <select class="form-control" name="building_id" id="building" required="">
                                  <option value="">- Pilih -</option>';
                                  $query="SELECT building_id,name,address FROM building ORDER BY name ASC";
                                  $result = $connection->query($query);
                                  while($row = $result->fetch_assoc()) { 
                                  echo'<option value="'.$row['building_id'].'">'.strip_tags($row['name']).'</option>';
                                  }echo'
                              </select>
                            </div>
                        </div>


        
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="password1">Password</label>
                                <input type="password" class="form-control" id="password" name="employees_password" placeholder="Passworb baru">
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-links mt-2">
                    <div>
                        <a href=./>Sudah punya akun?</a>
                    </div>
                    <div><a href="forgot" class="text-muted">Lupa Password?</a></div>
                </div>

                <div class="form-button-group transparent">
                   <button type="submit" class="btn btn-danger btn-block">Mendaftar</button>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->';}
  else{
  }

  include_once 'sw-mod/sw-footer.php';
} ?>