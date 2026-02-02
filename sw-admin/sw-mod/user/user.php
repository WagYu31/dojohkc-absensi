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
  <h1>Akun Pengurus Dojo</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Akun Pengurus Dojo</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Akun Pengurus Dojo</b></h3>
          <div class="box-tools pull-right">';
          if($level_user ==1){
            echo'
            <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i> Tambah Baru</button>';}else{
            echo' <button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Baru</button>';
            }
          echo'
          </div>
        </div>
<div class="box-body">
          <div class="table-responsive">
          <table id="swdatatable" class="table table-bordered">
            <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Email</th>
              <th>Registrasi</th>
              <th>Level</th>
              <th style="width:120px" class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody>';
            $query="SELECT user.user_id,user.username,user.fullname,user.email,user.registered,user.level,user.level,user_level.level_id,user_level.level_name FROM user,user_level WHERE user.level=user_level.level_id order by user.user_id DESC";
            $result = $connection->query($query);
            if($result->num_rows > 0){
            $no=0;
           while ($row_a= $result->fetch_assoc()) {
              $no++;
              echo'
              <tr>
                <td class="text-center">'.$no.'</td>
                <td>'.$row_a['fullname'].'</td>
                <td>'.$row_a['username'].'</td>
                <td>'.$row_a['email'].'</td>
                <td>'.tgl_indo($row_a['registered']).' - '.jam_indo($row_a['registered']).'</td>
                <td>'.$row_a['level_name'].'</td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm">';
                  if($level_user==1){
                    // Level Admin
                    echo'
                      <button class="btn btn-warning btn-md btn-update" data-id="'.$row_a['user_id'].'" data-name="'.strip_tags($row_a['fullname']).'" data-user="'.strip_tags($row_a['username']).'" data-email="'.strip_tags($row_a['email']).'" data-level="'.$row_a['level'].'" title="Edit"><i class="fa fa-pencil-square-o"></i></button>';
                    if($row_a['user_id']==$SESSION_ID){
                      echo'
                      <button class="btn btn-sm btn-danger access-failed disabled" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                    }else{
                      echo'<button data-id="'.epm_encode($row_a['user_id']).'" class="btn btn-md btn-danger delete" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                    }

                  }else{
                    //level operator
                    if($row_a['user_id']==$SESSION_ID){
                    echo'
                      <button class="btn btn-warning btn-md btn-update" data-id="'.$row_a['user_id'].'" data-name="'.strip_tags($row_a['fullname']).'" data-user="'.strip_tags($row_a['username']).'" data-email="'.strip_tags($row_a['email']).'" data-level="'.$row_a['level'].'" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
                      <button class="btn btn-sm btn-danger access-failed disabled" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                    }else{
                      echo'
                      <button class="btn btn-warning btn-md access-failed disabled" title="Edit"><i class="fa fa-pencil-square-o"></i></button>
                      
                      <button class="btn btn-sm btn-danger access-failed disabled" title="Hapus"><i class="fa fa-trash-o"></i></button>';
                    }
                    
                  }
                  
                  
                  echo'
                  </div>

                </td>
              </tr>';}}
            echo'
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div> 
</section>


<!-- Add -->
<div class="modal fade" id="modalAdd" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tambah Baru</h4>
      </div>
      <form id="validate" class="form add-user">
      <div class="modal-body">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" name="fullname" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group">
          <label>Level</label>
           <select class="form-control" name="level" required="">
            <option value="">- Pilih -</option>';
            $query="SELECT * from user_level order by level_name ASC";
            $result = $connection->query($query);
            while($row = $result->fetch_assoc()) { 
              echo'<option value="'.$row['level_id'].'">'.$row['level_name'].'</option>';
            }echo'
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
      </div>
    </form>
    </div>
  </div>
</div>



<!-- Edit -->
<div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit User</h4>
      </div>
      <form id="validate2" class="form update-user">
      <input type="hidden" name="id" class="id" required" value="" readonly>
      <div class="modal-body">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control nama_"  name="fullname" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control user_"  name="username" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control email_"  name="email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
            <small class="text-danger">Kosongan jika tidak ingin diubah passwordnya</small>
        </div>

        <div class="form-group">';
        if($level_user ==1){
          echo'
          <label>Level</label>
           <select class="form-control level_" name="level" required="">
            <option value="">- Pilih -</option>';
            $query="SELECT * from user_level order by level_name ASC";
            $result = $connection->query($query);
            while($row = $result->fetch_assoc()) { 
              echo'<option value="'.$row['level_id'].'">'.$row['level_name'].'</option>';
            }echo'
          </select>';}else{
            echo'<input type="hidden" name="level"  required readonly>';
          }
        echo'
        </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
      </div>
    </form>
    </div>
  </div>
</div>';
break;
}?>

</div>
<?php }?>