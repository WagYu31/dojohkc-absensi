<?php 
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';
echo'
<div class="content-wrapper">

<section class="content-header">
  <h1>Master SPP</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Master SPP</li>
    </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Master SPP</b></h3>
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
                  <th class="text-center" width="5">No</th>
                  <th>Tahun</th>
                  <th>Nominal</th>
                  <th class="text-center">Status Aktif</th>
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
              <div class="form-group">
                  <label>Tahun Pelajaran</label>
                  <select class="form-control tahun" name="tahun" required>';
                    $query_tahun ="SELECT * FROM tahun_pelajaran";
                    $result_tahun = $connection->query($query_tahun);
                    while ($data_tahun = $result_tahun->fetch_assoc()) {
                      echo'<option value="'.$data_tahun['tahun_pelajaran_id'].'">'.$data_tahun['tahun_mulai'].' s/d '.$data_tahun['tahun_selesai'].'</option>';
                    }
                  echo'
                  </select>
              </div>

              <div class="form-group">
                  <label>Nominal</label>
                  <input type="number" class="form-control nominal" name="nominal" required>
              </div>

              <div class="form-group">
                  <label>Active</label>
                  <div>
                    <label class="custom-toggle">
                        <input type="checkbox" class="btn-active" name="status" value="Y" checked>
                          <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                    </label>
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