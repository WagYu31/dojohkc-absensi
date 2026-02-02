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
  <h1>Laporan SPP</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Laporan SPP</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Laporan SPP</b></h3>
          <div class="box-tools pull-right">';
          if($level_user==1){
            echo'
            <button class="btn btn-warning btn-download" type="button"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download</button>
            <button class="btn btn-info btn-print" type="button" data-tipe="print"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';
          }
          echo'
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

            <div class="col-md-3">
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
              <button class="btn btn-info btn-filter btn-block">Filter</button>
            </div>
        </div>

        <div class="load-data"></div>

      </div>
    </div>
  </div> 
</section>';
    
break;
}?>

</div>
<?php }?>