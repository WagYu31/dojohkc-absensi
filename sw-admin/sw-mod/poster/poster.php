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
  <h1>Poster Rekrutmen</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Poster Rekrutmen</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Poster Rekrutmen</b></h3>
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
                  <th>Poster</th>
                  <th>Judul</th>
                  <th>Tanggal</th>
                  <th>Status</th>
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
  <h1>Poster Rekrutmen</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./'.$mod.'">Poster Rekrutmen</a></li>
      <li class="active">Tambah Baru</li>
    </ol>
</section>

<section class="content">
  <div class="box box-solid">
    <div class="box-body">
      <form class="form-add" role="form" method="post" action="#" autocomplete="off">
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Judul Poster</label>
                  <input type="text" class="form-control" name="judul" placeholder="Contoh: Rekrutmen Anggota Baru 2026" required>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="active">
                    <option value="Y">Aktif (Tampil)</option>
                    <option value="N">Nonaktif</option>
                  </select>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Upload Poster</label>
                  <div class="file-upload">
                      <div class="image-upload-wrap">
                        <input class="file-upload-input fileInput" type="file" name="foto" onchange="readURL(this);" accept="image/*">
                          <div class="drag-text">
                            <i class="lni lni-cloud-upload"></i>
                            <h3>Drag and drop poster di sini</h3>
                          </div>
                      </div>
                        <div class="file-upload-content">
                          <img class="file-upload-image" src="sw-assets/img/media.png" alt="Upload" height="200">
                            <div class="image-title-wrap">
                              <button type="button" onclick="removeUpload()" class="btn btn-danger btn-sm">Ubah<span class="image-title"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
          <hr>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
          <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
        </div>
      </form>
    </div>
  </div>
</section>';

break;
case 'update':
echo'
<section class="content-header">
  <h1>Poster Rekrutmen</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./'.$mod.'">Poster Rekrutmen</a></li>
      <li class="active">Update</li>
    </ol>
</section>';

if(!empty($_GET['id'])){
$id = anti_injection(epm_decode($_GET['id']));
$query = "SELECT * from poster WHERE poster_id='$id'";
$result = $connection->query($query);
echo'
<section class="content">
  <div class="box box-solid">
    <div class="box-body">';
    if($result->num_rows > 0){
        $data = $result->fetch_assoc();
        if(strip_tags($data['file']) == NULL){
          $imageuploadwrap = 'display:block';
          $display_none = 'display:none';
        }else{
          $imageuploadwrap = 'display:none';
          $display_none = 'display:block';
        }
      echo'
      <form class="form-update" role="form" method="post" action="#" autocomplete="off">
      <input type="hidden" class="d-none" name="id" value="'.epm_encode($data['poster_id']).'" required>
        <div class="row">
            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Judul Poster</label>
                  <input type="text" class="form-control" name="judul" value="'.strip_tags($data['judul']).'" required>
                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" name="active">
                    <option value="Y" '.($data['active']=='Y'?'selected':'').'>Aktif (Tampil)</option>
                    <option value="N" '.($data['active']=='N'?'selected':'').'>Nonaktif</option>
                  </select>
                </div>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="form-group">
                  <label>Upload Poster</label>
                  <div class="file-upload">
                      <div class="image-upload-wrap" style="'.$imageuploadwrap.'">
                        <input class="file-upload-input fileInput" type="file" name="foto" onchange="readURL(this);" accept="image/*">
                          <div class="drag-text">
                            <i class="lni lni-cloud-upload"></i>
                            <h3>Drag and drop poster di sini</h3>
                          </div>
                      </div>
                        <div class="file-upload-content" style="'.$display_none.'">';
                        if($data['file'] != NULL && file_exists('../../../sw-content/poster/'.$data['file'])){
                          echo'<img src="../sw-content/poster/'.strip_tags($data['file']).'" class="file-upload-image" height="200">';
                        }else{
                          echo'<img class="file-upload-image" src="sw-assets/img/media.png" alt="Upload" height="70">';
                        }
                        echo'
                            <div class="image-title-wrap">
                              <button type="button" onclick="removeUpload()" class="btn btn-danger btn-sm"><i class="fas fa-undo"></i> Ubah<span class="image-title"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
</section>';
}

break;
}?>

</div>
<?php }?>
