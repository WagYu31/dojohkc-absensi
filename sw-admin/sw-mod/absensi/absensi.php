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
  <h1>Laporan Data<small> Absensi</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Absensi</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Absensi</b></h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-laporan">Ekspor</button>
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
              <th class="text-center">Jumlah Absen</th>
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
</section>

        <div class="modal fade" id="modal-laporan">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Explore Laporan Absensi</h4>
              </div>
              <div class="modal-body">

              <div class="form-group">
                  <label>Pegawai</label>
                  <select class="form-control pegawai" required>';
                    $query_pegawai = "SELECT id,employees_name FROM employees ORDER BY employees_name";
                    $result_pegawai = $connection->query($query_pegawai);
                    while ($row_pegawai = $result_pegawai->fetch_assoc()) {
                      echo'<option value="'.$row_pegawai['id'].'">'.$row_pegawai['employees_name'].'</option>';
                    }
                    echo'
                  </select>
                </div>


                <div class="form-group">
                  <label>Bulan</label>
                  <select class="form-control month" required>';
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
                    if($month ==11){echo'<option value="11" selected>November</option>';}else{echo'<option value="11">November</option>';}
                    if($month ==12){echo'<option value="12" selected>Desember</option>';}else{echo'<option value="12">Desember</option>';}
                  echo'
                  </select>
                </div>

                <div class="form-group">
                  <label>Tahun</label>
                  <select class="form-control year" required>';
                    $mulai= date('Y') - 0;
                    for($i = $mulai;$i<$mulai + 50;$i++){
                        $sel = $i == date('Y') ? ' selected="selected"' : '';
                        echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                    }
                    echo'
                  </select>
                </div>

                <div class="form-group">
                  <label>Tipe</label>
                  <select class="form-control type" required>
                    <option value="excel">EXCEL</option>
                    <option value="print">PRINT</option>
                  </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left btn-print-all">Ekspor Semua</button>
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->';



break;
case 'views':
echo'
<section class="content-header">
  <h1>Detail<small> Absensi</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li><a href="#" onclick="history.back()">Data Abseni</a></li>
      <li class="active">Detail Absen</li>
    </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Detail Absensi</b></h3>
          <div class="box-tools pull-right">
            <button type="button" onclick="history.back()" class="btn btn-default btn-flat">Kembali</button>
          </div>
        </div>

      <div class="box-body">';

      if(!empty($_GET['id'])){
      $id   = mysqli_real_escape_string($connection,epm_decode($_GET['id'])); 
      $query ="SELECT employees.id,employees.employees_name,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$id'";
      $result = $connection->query($query);
      if($result->num_rows > 0){
          $row = $result->fetch_assoc();
        echo'
        <h4>Nama   : <span class="employees_name">'.$row['employees_name'].'</span></h4>
        <h4>Kategori : '.$row['position_name'].'</h4>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <input type="hidden" class="id" value="'.$id.'" readonly">
            <div class="form-group">
              <select class="form-control month" required>';
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
                if($month ==11){echo'<option value="11" selected>November</option>';}else{echo'<option value="11">November</option>';}
                if($month ==12){echo'<option value="12" selected>Desember</option>';}else{echo'<option value="12">Desember</option>';}
              echo'
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <select class="form-control year" required>';
                $mulai= date('Y') - 2;
                for($i = $mulai;$i<$mulai + 50;$i++){
                    $sel = $i == date('Y') ? ' selected="selected"' : '';
                    echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                }
                echo'
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="btn-group pull-right">
            <button type="button" class="btn btn-primary btn-sortir">Tampilkan</button>
            <button type="button" class="btn btn-warning">Ekspor/Cetak</button>
            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#" class="btn-print" data-id="pdf">PDF</a></li>
                    <li><a href="#" class="btn-print" data-id="excel">EXCEL</a></li>
                    <li><a href="#" class="btn-print" data-id="print">PRINT</a></li>
                  </ul>
            </div>
          </div>

        </div>
      <h3>Absensi Bulan : <span class="result-month">'.$month_en.'</span></h3>';
      echo'
        <div class="loaddata"></div>
      
      <div class="modal fade" id="modal-location">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lokasi Absen <span class="modal-title-name"></span></h4>
              </div>
              <div class="modal-body">
                <div id="iframe-map"></div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        ';}
      else{
        echo'<div class="alert alert-warning">Data tidak ditemukan</div>';
      }}
      echo'
      </div>
      </div>
    </div>
  </div>                
</section>';

break;
}?>

</div>
<?php }?>