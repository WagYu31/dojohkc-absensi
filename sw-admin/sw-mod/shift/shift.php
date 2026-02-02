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
  <h1>Data<small>Jam Latihan</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Jam Latihan</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Jam Latihan</b></h3>
        </div>
          <div class="box-body">
          <form class="form-update" role="form" method="post" action="#" autocomplete="off">
          <div class="box-body">
          <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">Hari</th>
                <th scope="col">Masuk</th>
                <th scope="col">Pulang</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody class="list">';
            
            $nama_hari = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
            $i = 0;
            foreach($nama_hari as $values){$i++;
              $nama_hari = anti_injection($values);
              $query_jam_kerja  ="SELECT * from shift WHERE shift_name='$nama_hari'";
              $result_jam_kerja = $connection->query($query_jam_kerja);
              $data_jam_kerja  = $result_jam_kerja->fetch_assoc();

              echo'
                  <tr>
                    <td scope="row">
                    <input class="d-none" type="hidden" name="id[]" value="'.htmlentities($data_jam_kerja['shift_id']).'" readonly required>
                    <input class="d-none" type="input" style="display:none" name="item[]" value="'.anti_injection($values).'" required readonly>
                      '.$values.'
                    </td>
                    <td class="budget">
                      <input class="form-control timepicker" type="text" name="jam_masuk[]" value="'.$data_jam_kerja['time_in'].'" placeholder="Masuk">
                    </td>
                    <td>
                      <input class="form-control timepicker" type="text" name="jam_pulang[]" placeholder="Pulang" value="'.$data_jam_kerja['time_out'].'">
                    </td>
                    <td>
                        <div class="selectdiv">
                        <label>
                        <select class="form-control" name="active[]">';
                            if($data_jam_kerja['active'] == 'Y'){
                              echo'<option value="Y" selected>Masuk Latihan</option>';
                            }else{
                              echo'<option value="Y">Masuk Latihan</option>';
                            }
                            if($data_jam_kerja['active'] == 'N'){
                              echo'<option value="N" selected>Libur</option>';
                            }else{
                              echo'<option value="N">Libur</option>';
                            }
                            echo'
                        </select>
                        </label>
                      </div>
                    </td>
                  </tr>';
                }
            
            echo' 
            </tbody>
          </table>
          </div>
              
          <div class="box-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
            <a class="btn btn-danger" href="./'.$mod.'"><i class="fa fa-remove"></i> Batal</a>
          </div>
        </form>

       </div>
    </div>
  </div> 
</section>';
break;
}?>

</div>
<?php }?>