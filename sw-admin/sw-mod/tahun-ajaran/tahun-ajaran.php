<?php 
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';
echo'
<div class="content-wrapper">

<section class="content-header">
  <h1>Tahun Ajaran</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Tahun Ajaran</li>
    </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Tahun Ajaran</b></h3>
          <div class="box-tools pull-right">';
          if($level_user==1){
            echo'
            <button type="button" class="btn btn-success btn-add"><i class="fa fa-plus"></i> Tambah</button>';
          }else{
            echo'
            <button type="button" class="btn btn-success access-failed"><i class="fa fa-plus"></i> Tambah</button>';
          }
          echo'
          </div>
        </div>
        
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered datatable">
              <thead>
              <tr>
                <th class="text-center" width="20">No</th>
                <th>Tahun Ajaran</th>
                <th style="width:100px" class="text-center">Aksi</th>
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

<div  class="modal fade modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
      <form class="form-add" role="form" action="#">
          <input type="hidden" class="form-control id d-none" name="id" readonly>
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h5 class="modal-title">Tambah Baru</h5>
          </div>

          <div class="modal-body">
            <div class="row align-items-center">
            
              <div class="col-md-6">
                  <label class="form-control-label">Start</label>
                  <input class="form-control tahun-mulai" name="tahun_mulai" type="text" value="'.$year.'">
              </div>

              <div class="col-md-6">
                  <label class="form-control-label">End</label>
                  <input class="form-control tahun-selesai" name="tahun_selesai" type="text" value="'.$year.'">
              </div>
            </div>

          </div>
          <div class="modal-footer">
              <button type="submit" class="btn  btn-primary btn-save">Simpan</button>
              <button type="button" class="btn  btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </form>
      </div>
  </div>
</div>

</div>';
}?>