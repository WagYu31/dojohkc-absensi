<?php
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';
echo'
  <div class="content-wrapper">';
switch(@$_GET['op']){ 
    default:
echo'
<section class="content-header">
  <h1>Data<small> Atlet</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Atlet</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Atlet</b></h3>
          <div class="box-tools pull-right">';
          if($level_user==1){
            echo'
            <a href="#import" class="btn btn-warning" title="Import" data-toggle="modal"> Import</a>
            <a href="'.$mod.'&op=add" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Tambah Baru</a>';}
          else{
            echo'<button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Baru</button>';
          }echo'
          </div>
        </div>
    <div class="box-body">
      <div class="table-responsive">
          <table id="sw-datatable" class="table table-bordered">
            <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>No Hp</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Kategori</th>
              <th>Lokasi</th>
              <th style="width:150px" class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        </div>
      </div>
    </div>
  </div> 
</section>';

echo'
<div id="import" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <form id="validate" class="import" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Import Data Atlet</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Upload File</label>
                <div class="input-group">
                    <span class="input-group-btn">
                    <span class="btn btn-primary btn-file"><i class="fa fa-files"></i> Upload
                    <input type="file" class="upload" name="files" accept=".csv">
                     </span></span><input type="text" class="form-control" placeholder="Upload file Excel" readonly="">
                  </div>
              </div>
              <p><a href="../sw-content/sample-import.csv">Download Sample File</a></p>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-check"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>';

    
break;
case 'add':
echo'
<section class="content-header">
  <h1>Tambah Data<small> Atlet</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./karyawan"> Data Atlet</a></li>
      <li class="active">Tambah</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Tambah Data Atlet</b></h3>
        </div>

        <div class="box-body">
            <form class="form-horizontal validate add-karyawan">
              <div class="box-body">

                <div class="form-group">
                  <label class="col-sm-2 control-label">NIP</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_code" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_name" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_email" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-6">
                    <input type="password" class="form-control" name="employees_password" required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Tahun Ajaran</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="tahun_pelajaran" required="">
                      <option value="">- Pilih -</option>';
                      $query ="SELECT * FROM tahun_pelajaran";
                      $result =  $connection->query($query);
                      while($data = $result->fetch_assoc()){
                          echo'<option value="'.$data['tahun_pelajaran_id'].'">'.$data['tahun_mulai'].' - '.$data['tahun_selesai'].'</option>';
                      }
                      echo'
                  </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Kategori</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="position_id" required="">
                      <option value="">- Pilih -</option>';
                      $query="SELECT * from position order by position_name ASC";
                      $result = $connection->query($query);
                      while($row = $result->fetch_assoc()) { 
                      echo'<option value="'.$row['position_id'].'">'.$row['position_name'].'</option>';
                      }echo'
                  </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Penempatan</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="building_id" id="building" required="">
                      <option value="">- Pilih -</option>';
                      $query="SELECT building_id,name,address from building order by name ASC";
                      $result = $connection->query($query);
                      while($row = $result->fetch_assoc()) { 
                      echo'<option value="'.$row['building_id'].'">'.strip_tags($row['name']).'</option>';
                      }echo'
                  </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Foto</label>
                  <div class="col-sm-6">
                    <div class="upload-media">
                        <img src="sw-assets/img/media.png" id="output" class="img-responsive" width="100">
                         <input type="file" class="upload-hidden" name="photo" onchange="loadFile(event)" accept="image/jpeg, image/jpg, image/gif">
                    </div>
                    <small>Kosongan jika tidak ingin mengupload foto</small>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2"></div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
              </div>
              <!-- /.box-footer -->
            </form>
        
      </div>
    </div>
  </div> 
</section>';
break;

case 'edit':
echo'
<section class="content-header">
  <h1>Edit Data<small> Atlet</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./karyawan"> Data Atlet</a></li>
      <li class="active">Edit</li>
    </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Profil</a></li>
            <li><a href="#tab_2" data-toggle="tab">Ubah Password</a></li>
          </ul>
        </div>

      <div class="box-body">';
      if(!empty($_GET['id'])){
      $id     =  mysqli_real_escape_string($connection,epm_decode($_GET['id'])); 
      $query  ="SELECT * from employees WHERE id='$id'";
      $result = $connection->query($query);
      if($result->num_rows > 0){
      $row  = $result->fetch_assoc();
      echo'
      <div class="nav-tabs-custom">
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">

            <form class="form-horizontal validate update-karyawan">
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">NIP</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_code" value="'.$row['employees_code'].'" required>
                    <input type="hidden"  name="id" value="'.$row['id'].'" readonly required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_name" value="'.$row['employees_name'].'" required>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-2 control-label">Tahun Ajaran</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="tahun_pelajaran" required="">
                      <option value="">- Pilih -</option>';
                      $query ="SELECT * FROM tahun_pelajaran";
                      $result =  $connection->query($query);
                      while($data = $result->fetch_assoc()){
                        if($data['tahun_pelajaran_id'] == $row['tahun_pelajaran']){
                          echo'<option value="'.$data['tahun_pelajaran_id'].'" selected>'.$data['tahun_mulai'].' - '.$data['tahun_selesai'].'</option>';
                        }else{
                          echo'<option value="'.$data['tahun_pelajaran_id'].'">'.$data['tahun_mulai'].' - '.$data['tahun_selesai'].'</option>';
                        }
                      }
                      echo'
                  </select>
                  </div>
                </div>

                
                <div class="form-group">
                  <label class="col-sm-2 control-label">Kategori</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="position_id" required="">
                      <option value="">- Pilih -</option>';
                      $query="SELECT * from position order by position_name ASC";
                      $result = $connection->query($query);
                      while($rowa = $result->fetch_assoc()) { 
                      if($rowa['position_id'] == $row['position_id']){
                        echo'<option value="'.$rowa['position_id'].'" selected>'.$rowa['position_name'].'</option>';
                      }else{
                        echo'<option value="'.$rowa['position_id'].'">'.$rowa['position_name'].'</option>';
                      }
                      }echo'
                  </select>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-2 control-label">Penempatan</label>
                  <div class="col-sm-6">
                   <select class="form-control" name="building_id" id="building" required="">
                      <option value="">- Pilih -</option>';
                      $query="SELECT building_id,name,address from building order by name ASC";
                      $result = $connection->query($query);
                      while($rowa = $result->fetch_assoc()) { 
                        if($rowa['building_id'] == $row['building_id']){ 
                          echo'<option value="'.$rowa['building_id'].'" selected>'.$rowa['name'].'</option>';
                        }else{
                          echo'<option value="'.$rowa['building_id'].'">'.$rowa['name'].'</option>';
                        }
                      }
                      echo'
                  </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Foto</label>
                  <div class="col-sm-6">
                    <div class="upload-media">';
                     if($row['photo'] == NULL){
                      echo'
                        <img src="sw-assets/img/media.png" id="output" class="img-responsive" width="100">';
                      }else{
                      echo'
                      <img src="../sw-content/karyawan/'.$row['photo'].'" id="output" class="img-responsive" width="100">';
                      }
                      echo'
                         <input type="file" class="upload-hidden" name="photo" onchange="loadFile(event)" accept="image/jpeg, image/jpg, image/gif">
                    </div>
                    <small>Kosongan jika tidak ingin mengubah foto</small>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2"></div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>

          <!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">
            <form class="form-horizontal validate update-password">
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" name="employees_email" value="'.$row['employees_email'].'" readonly required>
                    <input type="hidden"  name="id" value="'.$row['id'].'" readonly required>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" name="employees_password" required>
                  </div>
                </div>

              </div>
              
              <!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2"></div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        <!-- /.tab-content -->
      </div>
      <!-- nav-tabs-custom -->';
      }else{
         echo'<section class="content">
            <div class="error-page">
              <h2 class="headline text-yellow"> 404</h2>
              <div class="error-content">
                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                <p>
                Saat ini data yang Anda cari tidak ditemukan<br>
                <a class="btn btn-primary" href="./">return to dashboard</a>
                </p>
              </div>
            </div>
          </section>';
      }}
        echo'
      </div>
    </div>
  </div> 
</section>';

break;
}?>

</div>
<?php }?>