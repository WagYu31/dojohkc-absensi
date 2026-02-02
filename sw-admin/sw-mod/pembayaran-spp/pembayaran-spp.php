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
  <h1>Pembayaaran SPP</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Pembayaran SPP</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Pembayaran SPP</b></h3>
          <div class="box-tools pull-right">';
          if($level_user==1){
            echo'
            <button class="btn btn-warning btn-cekspp"><i class="fa fa-search" aria-hidden="true"></i> Cek Pembayaran</button>
            <button class="btn btn-info btn-download" type="button" data-download="excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Download</button>
            <a href="pembayaran-spp&op=add" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>';
          }else{
            echo'<button type="button" class="btn btn-success access-failed"><i class="fa fa-plus"></i> Tambah</button>';
          }echo'
          </div>
        </div>

        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control user" required>
                  <option value="">Semua</option>';
                  $query  ="SELECT id,employees_name from employees ORDER BY id ASC";
                  $result = $connection->query($query);
                    while($row = $result->fetch_assoc()) { 
                      echo'<option value="'.$row['id'].'">'.$row['employees_name'].'</option>';}
                echo'
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control status" required>
                  <option value="">Semua</option>
                  <option value="pending">Pending</option>
                  <option value="Berhasil">Berhasil</option>
                  <option value="Gagal">Gagal</option>
                </select>
              </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                  <select class="form-control tahun-pelajaran" name="tahun_pelajaran" required>';
                    $query_tahun ="SELECT * FROM tahun_pelajaran";
                    $result_tahun = $connection->query($query_tahun);
                    while ($data_tahun = $result_tahun->fetch_assoc()) {
                      echo'<option value="'.$data_tahun['tahun_pelajaran_id'].'">'.$data_tahun['tahun_mulai'].' s.d '.$data_tahun['tahun_selesai'].'</option>';
                    }
                  echo'
                  </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control bulan" required>
                    <option value="">Semua Bulan</option>';
                    $bulan_nama = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                    for($bulan=1; $bulan<=12; $bulan++){
                      $bulan_val = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                      if($bulan<=$month ) {
                      echo'<option value="'.$bulan_val.'" selected>'.$bulan_nama[$bulan].'</option>';
                      }else { 
                      echo'<option value="'.$bulan_val.'">'.$bulan_nama[$bulan].'</option>'; 
                      }
                    }
                    echo'
                    </select>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <select class="form-control tahun" required>';
                    $mulai= date('Y') - 1;
                    for($i = $mulai;$i<$mulai + 50;$i++){
                        $sel = $i == date('Y') ? ' selected="selected"' : '';
                        echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                    }
                    echo'
                  </select>
                </div>
            </div>
            
        </div>

          <div class="table-responsive">
            <table class="table table-bordered datatable">
              <thead>
              <tr>
                <th width="4">No</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Nominal</th>
                <th>Tanggal Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
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
<div class="modal fade modal-cekspp" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Import Data Pegawai</h4>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Atlet</label>
                <select class="form-control user-pembayaran user-spp" name="user" data-toggle="select" required>
                <option>Pilih:</option>';
                  $query  ="SELECT id,employees_name FROM employees ORDER BY employees_name ASC";
                  $result = $connection->query($query);
                  while($row = $result->fetch_assoc()) { 
                    echo'<option value="'.$row['id'].'">'.$row['employees_name'].'</option>';
                  }
                echo'
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                  <label>Tahun</label>
                  <select class="form-control tahun_pelajaran" name="tahun_pelajaran" required>';
                    $query_tahun ="SELECT * FROM tahun_pelajaran";
                    $result_tahun = $connection->query($query_tahun);
                    while ($data_tahun = $result_tahun->fetch_assoc()) {
                      echo'<option value="'.$data_tahun['tahun_pelajaran_id'].'">'.$data_tahun['tahun_mulai'].' s/d '.$data_tahun['tahun_selesai'].'</option>';
                    }
                  echo'
                  </select>
              </div>
            </div>
          </div>
          
          <div class="load-data-spp"></div>
          
        </div>
    </div>
  </div>
</div>';

    
break;
case 'add':
echo'
<section class="content-header">
  <h1>Tambah Pembayaran SPP</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="./karyawan"> Data Pegawai</a></li>
      <li class="active">Tambah Karyawan</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Tambah Pembayaran SPP</b></h3>
        </div>

        <div class="box-body">
            <form class="validate form-add" role="form" method="post" action="#" autocomplete="off">
                <input type="hidden" class="d-none id" name="id" readonly required>
                <div class="row justify-content-md-center">
                  <div class="col-md-6 col-md-offset-3">
                      <div class="form-group">
                        <label>Atlet</label>
                        <select class="form-control user-pembayaran user" name="user" data-toggle="select" required>
                        <option>Pilih:</option>';
                          $query  ="SELECT id,employees_name FROM employees ORDER BY employees_name ASC";
                          $result = $connection->query($query);
                          while($row = $result->fetch_assoc()) { 
                            echo'<option value="'.$row['id'].'">'.$row['employees_name'].'</option>';
                          }
                          echo'
                        </select>
                      </div>

                      <div class="form-group">
                          <label>Tahun</label>
                          <select class="form-control tahun-pelajaran" name="tahun_pelajaran" required>';
                            $query_tahun ="SELECT * FROM tahun_pelajaran";
                            $result_tahun = $connection->query($query_tahun);
                            while ($data_tahun = $result_tahun->fetch_assoc()) {
                              echo'<option value="'.$data_tahun['tahun_pelajaran_id'].'">'.$data_tahun['tahun_mulai'].' s/d '.$data_tahun['tahun_selesai'].'</option>';
                            }
                          echo'
                          </select>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-control bulan" name="bulan" required>';
                                $bulan_nama =array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                for($bulan=1; $bulan<=12; $bulan++){
                                  if($bulan<=$month ) {
                                    echo'<option value="'.$bulan.'" selected>'.$bulan_nama[$bulan].'</option>';
                                  }else { 
                                    echo'<option value="'.$bulan.'">'.$bulan_nama[$bulan].'</option>'; 
                                  }
                                }
                                echo'
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                              <label>Tahun</label>
                              <select class="form-control tahun" name="tahun" required>';
                              $mulai= date('Y') - 1;
                              for($i = $mulai;$i<$mulai + 50;$i++){
                                  $sel = $i == date('Y') ? ' selected="selected"' : '';
                                  echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                              }
                              echo'
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Nominal</label>
                          <input type="number" min="0" class="form-control nominal" name="nominal" required>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select class="form-control" name="metode_pembayaran" required>';
                                $query_metode = "SELECT * FROM metode_pembayaran ORDER BY metode_pembayaran_id ASC";
                                $result_metode = $connection->query($query_metode);
                                while($data = $result_metode->fetch_assoc()){
                                  echo'<option value="'.strip_tags($data['nama_metode']).'">'.strip_tags($data['nama_metode']).'</option>';
                                }
                              echo'
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Status Pembayaran</label>
                            <select class="form-control" name="status_pembayaran" required>
                              <option value="pending">Pending</option>
                              <option value="Berhasil">Berhasil</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
                        <a class="btn btn-danger" href="./pembayaran-spp"><i class="fa fa-remove"></i> Batal</a>
                      </div>

                </div>
              </div>
            </form>
          </div>
      </div>

      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Pembayaran SPP</b></h3>
        </div>

        <div class="box-body load-data-pembayaran">

        </div>
      </div>

    
  </div> 
</section>';

break;
}?>

</div>
<?php }?>