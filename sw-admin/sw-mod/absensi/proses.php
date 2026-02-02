<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php'); 

switch (@$_GET['action']){
/* -------  LOAD DATA ABSENSI----------*/
case 'absensi':
  $error = array();

   if (empty($_GET['id'])) {
      $error[] = 'ID tidak boleh kosong';
    } else {
      $id = mysqli_real_escape_string($connection, $_GET['id']);
  }

  if(isset($_POST['month']) OR isset($_POST['year'])){
      $bulan   = date ($_POST['month']);
      $tahun      = date($_POST['year']);
  } else{
      $bulan  = date ("m");
      $tahun      = date("Y");
  }

  $hari       = date("d");
  $jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));
  $s          = date ("w", mktime (0,0,0,$bulan,1,$tahun));
if (empty($error)) { 

$sum =0;
$libur =0;
echo'
<div class="table-responsive">
<table class="table table-bordered table-hover" id="swdatatable">
        <thead>
            <tr>
                <th class="align-middle" width="20">No</th>
                <th class="align-middle">Tanggal</th>
                <th class="align-middle">Shift</th>
                <th class="align-middle text-center"><i class="fa fa-picture-o" aria-hidden="true"></i></th>
                <th class="align-middle text-center">Absen Masuk</th>
                <th class="align-middle text-center">Terlambat</th>
                <th class="align-middle text-center"><i class="fa fa-picture-o" aria-hidden="true"></i></th>
                <th class="align-middle text-center">Absen Pulang</th>
                <th class="align-middle text-center">Pulang Cepat</th>
                <th class="align-middle">Status</th>
                <th class="align-middle text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>';
      for ($d=1;$d<=$jumlahhari;$d++) {
            $warna      = '';
            $background = '';
            $status_hadir     = 'Tidak Hadir';
            $date_month_year = ''.$tahun.'-'.$bulan.'-'.$d.'';
            $hari_libur     = date('D',strtotime($date_month_year));
      
        /** Menentukan Hari Libur Umum */
        $query_sabtu ="SELECT shift_name FROM shift WHERE shift_name='Sabtu' AND active='N'";
        $result_sabtu= $connection->query($query_sabtu);
        if($result_sabtu->num_rows >0 ){
          $sabtu = 'Sat';
        }else{
          $sabtu ='1';
        }
    
        $query_minggu ="SELECT shift_id FROM shift WHERE shift_name='Minggu' AND active='N'";
        $result_minggu = $connection->query($query_minggu);
        if($result_minggu->num_rows >0 ){
          $minggu = 'Sun';
        }else{
          $minggu ='2';
        }

      if($hari_libur == $sabtu OR $hari_libur == $minggu){
            $warna='#ffffff';
            $background ='#FF0000';
            $status_hadir ='Libur';
            $sum++;
      }
      else{
        
        $query_holiday="SELECT holiday_date,description FROM holiday WHERE holiday_date='$date_month_year'";
        $result_holiday = $connection->query($query_holiday);
          if($result_holiday->num_rows > 0){
            $data_holiday = $result_holiday->fetch_assoc();
            $warna='#ffffff';
            $background ='#FF0000';
            $libur++;
            $status_hadir = $data_holiday['description'];
          }else{
            $status_hadir ='';
          }

      }
      

      if(isset($_POST['month']) OR isset($_POST['year'])){
        $month = $_POST['month'];
        $year  = $_POST['year'];
        $filter ="employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
      } 
      else{
        $filter ="employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date) ='$month' AND year(presence_date)='$year'";
      }

      $query_absen ="SELECT presence_id,shift_id,jam_masuk,jam_pulang,presence_date,picture_in,picture_out,time_in,time_out,kehadiran,latitude_longtitude_in,latitude_longtitude_out,status_in,status_out,information FROM presence WHERE $filter ORDER BY presence_id DESC";
      $result_absen = $connection->query($query_absen);
      if($result_absen->num_rows > 0 ){
        $row_absen = $result_absen->fetch_assoc();

          $query_shift ="SELECT shift_name FROM shift WHERE shift_id='$row_absen[shift_id]'";
          $result_shift = $connection->query($query_shift);
          if($result_shift->num_rows > 0){
            $data_shift = $result_shift->fetch_assoc();
            $shift_name = $data_shift['shift_name'];
          }else{
            $shift_name = '-';
          }

          // Status Kehadiran
          if($row_absen['time_in'] == NULL){
              $status_hadir ='<span class="label label-danger">Tidak Hadir</span>';
          }
          else{
            $status_hadir ='<label class="label label-warning">'.$row_absen['kehadiran'].'</label>';
            $time_in = $row_absen['time_in']; 
          }

        /* ------------ Buat Status Terlambat / Pulang Cepat ----------------*/
        $pulang_cepat = 0;
        $terlambat = 0;
        
        if($row_absen['status_in'] == 'Telat'){
          $status_masuk ='<label class="label label-danger">Terlambat</label>';
          $terlambat++;
          $diff = (strtotime($row_absen['time_in']) - strtotime($row_absen['jam_masuk']) + strtotime('00:00:00'));
          $selisih_masuk =date('H:i:s', $diff );

        }
        elseif($row_absen['status_in'] == 'Tepat Waktu'){
          $status_masuk ='<label class="label label-info">Tepat waktu</label>';
          $selisih_masuk='-';
        }else{
          $status_masuk ='';
          $selisih_masuk='-';
        }

        if($row_absen['time_out']=='00:00:00'){
          $status_pulang ='';
          $selisih_pulang ='';
        }


        elseif($row_absen['time_out'] < $row_absen['jam_pulang']){
          $status_pulang='<label class="label label-danger">Pulang Cepat</label>';
          $pulang_cepat++;
          $diff = (strtotime($shift_time_out) - strtotime($row_absen['time_out']) + strtotime('00:00:00'));
          $selisih_pulang ='-'.date('H:i:s', $diff ).'';
        }
        else{
          $status_pulang='';
          $selisih_pulang ='-';
        }

        list($latitude,  $longitude) = explode(',', $row_absen['latitude_longtitude_in']);
        if(!$row_absen['latitude_longtitude_out'] ==''){
          list($latitude_out,  $longitude_out) = explode(',', $row_absen['latitude_longtitude_out']);
        }
        

          echo'
          <tr style="background:'.$background.';color:'.$warna.'">
            <td class="text-center">'.$d.'</td>
            <td>'.format_hari_tanggal($date_month_year).'</td>
            <td>'.$shift_name.'<br>'.$row_absen['jam_masuk'].' - '.$row_absen['jam_pulang'].'</td>
            <td class="text-center picture">';
              if($row_absen['picture_in'] ==NULL){
                echo'<img src="../sw-content/avatar.jpg" style="width:40px">';}
              else{
                echo'<a class="image-link" href="../sw-content/absent/'.$row_absen['picture_in'].'">
                <img src="../sw-content/absent/'.$row_absen['picture_in'].'" style="width:40px"></a>';
              }
            echo'
            </td>
            <td class="text-center">'.$row_absen['time_in'].' '.$status_masuk.'</td>
            <td class="text-center">'.$selisih_masuk.'</td>
            <td class="text-center picture">';
            if($row_absen['picture_out'] ==NULL){
              echo'<img src="../sw-content/avatar.jpg" style="width:40px">';}
            else{
              echo'<a class="image-link" href="../sw-content/absent/'.$row_absen['picture_out'].'">
                    <img src="../sw-content/absent/'.$row_absen['picture_out'].'" style="width:40px"></a>';}
            echo'
            </td>
            <td class="text-center">'.$row_absen['time_out'].' '.$status_pulang.'</td>
            <td class="text-center">'.$selisih_pulang.'</td>
            <td>'.$status_hadir.'<br>'.$row_absen['information'].'</td>

            <td class="text-right">';
              if(!$latitude==''){
                echo'
                <button type="button" class="btn btn-warning btn-sm btn-modal enable-tooltip" title="Lokasi" data-latitude="'.$latitude.'" data-longitude="'.$longitude.'"><i class="fa fa-map-marker"></i> IN</button>';
              }
              if(!$row_absen['latitude_longtitude_out'] ==''){
                echo'
                <button type="button" class="btn btn-warning btn-sm btn-modal enable-tooltip" title="Lokasi" data-latitude="'.$latitude_out.'" data-longitude="'.$longitude_out.'"><i class="fa fa-map-marker"></i> OUT</button>';
              }
            echo'</td>
            </tr>';
            }else{
              echo'
              <tr style="background:'.$background.';color:'.$warna.'">
              <td class="text-center">'.$d.'</td>
              <td>'.format_hari_tanggal($date_month_year).'</td>
              <td>-</td>
              <td class="text-center picture">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center picture">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td>'.$status_hadir.'</td>
              <td class="text-right">-</td>
            </tr>';
            }
          }
        echo'
        </tbody>
      </table>
  </div>';
      if(isset($_POST['month']) OR isset($_POST['year'])){
        $month = $_POST['month'];
        $year  = $_POST['year'];
        $filter ="employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
      } 
      else{
        $filter ="employees_id='$id' AND MONTH(presence_date) ='$month' AND year(presence_date)='$year'";
      }

      $query_hadir="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Hadir' ORDER BY presence_id DESC";
      $hadir= $connection->query($query_hadir);

      $query_sakit="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Sakit' ORDER BY presence_id";
      $sakit = $connection->query($query_sakit);

      $query_izin="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Izin' ORDER BY presence_id";
      $izin = $connection->query($query_izin);

      $query_cuty="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Cuti'";
      $cuty = $connection->query($query_cuty);

      $query_telat ="SELECT presence_id FROM presence WHERE $filter AND status_in='Telat'";
      $telat = $connection->query($query_telat);

      echo'<hr>
      <div class="row">
        <div class="col-md-2">
          <p>Hadir : <span class="label label-success">'.$hadir->num_rows.'</span></p>
        </div>

        <div class="col-md-2">
          <p>Terlambat : <span class="label label-danger">'.$telat->num_rows.'</span></p>
        </div>

        <div class="col-md-2">
          <p>Cuti : <span class="label label-warning">'.$cuty->num_rows.'</span></p>
        </div>

        <div class="col-md-2">
          <p>Sakit : <span class="label label-warning">'.$sakit->num_rows.'</span></p>
        </div>

        <div class="col-md-2">
          <p>Izin : <span class="label label-info">'.$izin->num_rows.'</span></p>
        </div>

      </div>';
    echo'
<script>
  $("#swdatatable").dataTable({
      "iDisplayLength":35,
      "aLengthMenu": [[35, 40, 50, -1], [35, 40, 50, "All"]]
  });
 $(".image-link").magnificPopup({type:"image"});
</script>';?>
<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>
<?php
}else{
  echo'Data tidak ditemukan';
}

break;

}

}
