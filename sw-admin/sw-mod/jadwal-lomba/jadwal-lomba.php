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
  <h1>Jadwal Lomba</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Jadwal Lomba/Latihan</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Jadwal Lomba/Latihan</b></h3>
          <div class="box-tools pull-right">';
          if($level_user==1){
            echo'
            <a href="'.$mod.'&op=add" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>';
          }else{
            echo'<button type="button" class="btn btn-success access-failed"><i class="fa fa-plus"></i> Tambah</button>';
          }echo'
          </div>
        </div>

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered datatable">
              <thead>
              <tr>
                <th width="4">No</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Diterbitkan</th>
                <th class="text-center">Aksi</th>
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
    
break;
case 'add':
echo'
<section class="content-header">
  <h1>Jadwal Lomba/Latihan</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./'.$mod.'">Jadwal Lomba/Latihan</a></li>
      <li class="active">Tambah Baru</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Jadwal Lomba/Latihan</b></h3>
        </div>

      <div class="box-body">
          <form class="form-add" role="form" method="post" action="#" autocomplete="off">
            <input type="hidden" class="d-none id" name="id" readonly required>
            <div class="form-group">
              <label>Judul</label>
              <input type="text" class="form-control judul" name="judul" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="text" class="form-control datepicker" name="tanggal" value="'.tanggal_ind($date).'" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Jam</label>
                  <input type="time" class="form-control" name="jam" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" name="keterangan" rows="10" required></textarea>
            </div>

            <div class="form-group">
              <hr>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
              <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
            </div>
          </form>
        </div>
      </div>
  </div> 
</section>';

/** Update */
break;
case 'update':
if(!empty($_GET['id'])){
$id     =  mysqli_real_escape_string($connection,epm_decode($_GET['id'])); 
$query  ="SELECT  * FROM pengumuman WHERE pengumuman_id='$id'";
$result = $connection->query($query);
echo'
<section class="content-header">
  <h1>Update</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./'.$mod.'">Jadwal Lomba/Latihan</a></li>
      <li class="active">Update</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Update Jadwal Lomba/Latihan</b></h3>
        </div>

      <div class="box-body">';
      if($result->num_rows > 0){
          $row  = $result->fetch_assoc();
          echo'
          <form class="form-update" role="form" method="post" action="#" autocomplete="off">
            <input type="hidden" class="d-none id" name="id" value="'.$row['pengumuman_id'].'" readonly required>
            <div class="form-group">
              <label>Judul</label>
              <input type="text" class="form-control judul" name="judul" value="'.strip_tags($row['judul']??'').'" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Tanggal</label>
                  <input type="text" class="form-control datepicker" name="tanggal" value="'.tanggal_ind($row['tanggal']??'').'" required>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Jam</label>
                  <input type="time" class="form-control" name="jam" value="'.$row['jam'].'" required>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Keterangan</label>
              <textarea class="form-control" name="keterangan" rows="10" required>'.$row['keterangan'].'</textarea>
            </div>

            <div class="form-group">
              <hr>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
              <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
            </div>
          </form>';
      }
      echo'
        </div>
      </div>
  </div> 
</section>';
  }

break;
}?>

</div>
<?php }?>