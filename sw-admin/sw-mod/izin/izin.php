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
  <h1>Data<small> Permohonan Izin</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Permohonan Izin</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Permohonan Izin</b></h3>
        </div>
    <div class="box-body">
    <div class="table-responsive">
          <table id="sw-datatable" class="table table-bordered">
            <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>Tgl Pengajuan</th>
              <th>Nama</th>
              <th>Mulai</th>
              <th>Selesai</th>
              <th>Jenis</th>
              <th>Keterangan</th>
              <th>Status</th>
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
break;
}?>

</div>
<?php }?>