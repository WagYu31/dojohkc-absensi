<?php session_start(); 
    require_once'../../../sw-library/sw-config.php'; 
    require_once'../../../sw-library/sw-function.php';
    include_once'../../../sw-library/vendor/autoload.php';
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    //Kondisi tidak login
   header('location:../login/');
}

else{
  //kondisi login
switch (@$_GET['action']){
/* -------  CETAK PDF-----------------------------------------------*/
case 'print':
  if (empty($_GET['id'])) {
      $error[] = 'ID tidak boleh kosong';
    } else {
      $id = mysqli_real_escape_string($connection, $_GET['id']);
  }

if (empty($error)) {
  $query ="SELECT employees.id,employees.employees_name,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$id'";
  $result = $connection->query($query);

  if($result->num_rows > 0){
      $row            = $result->fetch_assoc();
      $employees_name = $row['employees_name'];

      if(isset($_GET['from']) OR isset($_GET['to'])){
          $bulan   = date ($_GET['from']);
          $tahun      = date($_GET['to']);
      } 
      else{
          $bulan  = date ("m");
          $tahun      = date("Y");
      }
        $hari       = date("d");
        $jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));
        $s          = date ("w", mktime (0,0,0,$bulan,1,$tahun));

  if(empty(htmlentities($_GET['tipe'] !=='pdf'))){
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Legal-L']);
        ob_start();
  }

  if(empty(htmlentities($_GET['tipe'] !=='excel'))){
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Data-Absensi-".$date.".xls");
  }

echo'<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi '.$employees_name.'</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{line-height:25px;font-size:20px;margin:0px 0 10px;text-transform:uppercase}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{font-size:30px}table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}
    .label {display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap; vertical-align: baseline;border-radius: .25em;}
    .label-success{background-color: #00a65a !important;}.label-warning {background-color: #f0ad4e;}.label-info {background-color: #5bc0de;}.label-danger{background-color: #dd4b39 !important;}
    p{line-height:20px;padding:0px;margin: 5px;}.pull-right{float:right}
    </style>';
    if(empty(htmlentities($_GET['tipe'] !=='print'))){
      echo'<script>
        window.onafterprint = window.close;
        window.print();
      </script>';
    }

    echo'
</head>
<body>';
echo'
    <section class="container_box">
      <div class="row">';
      if(isset($_GET['from']) OR isset($_GET['to'])){
        echo'<h3 class="text-center">LAPORAN ABSENSI<br>BULAN'.ambilbulan($_GET['from']).' - '.$_GET['to'].'</h3>';}
        else{
        echo'<h3 class="text-center">LAPORAN ABSENSI<br>BULAN '.ambilbulan($month).' - '.$year.'</h3>';
       }
        echo'
        <p>Nama   : '.$row['employees_name'].'</p>
        <p>Kategori : '.$row['position_name'].'</p><br>
      <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th>Shift</td>
              <th class="text-center">Absen Masuk</th>
              <th>Terlambat</th>
              <th class="text-center">Absen Pulang</th>
              <th class="text-center">Pulang Cepat</th>
              <th>Durasi</th>
              <th>Lembur</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';
      $sum =0;
      $libur =0;
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
              $date_month_year = ''.$year.'-'.$bulan.'-'.$d.'';
              $query_holiday="SELECT holiday_date,description FROM holiday WHERE holiday_date='$date_month_year'";
              $result_holiday = $connection->query($query_holiday);
                if($result_holiday->num_rows > 0){
                  $data_holiday = $result_holiday->fetch_assoc();
                  $warna='#ffffff';
                  $background ='#FF0000';
                  $libur++;
                  $status_hadir = $data_holiday['description'];
                }else{
                  $status_hadir ='-';
                }

            }

      if(isset($_GET['from']) OR isset($_GET['to'])){
        $month = $_GET['from'];
        $year  = $_GET['to'];
        $filter ="employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
      } 
      else{
        $filter ="employees_id='$id' AND  presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
      }


      $query_absen ="SELECT presence_id,presence_date,shift_id,jam_masuk,jam_pulang,picture_in,picture_out,time_in,time_out,kehadiran,latitude_longtitude_in,latitude_longtitude_out,status_in,status_out,information FROM presence WHERE $filter ";
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
          $diff = (strtotime($row_absen['jam_pulang']) - strtotime($row_absen['time_out']) + strtotime('00:00:00'));
          $selisih_pulang ='-'.date('H:i:s', $diff ).'';
        }
        else{
          $status_pulang='';
          $selisih_pulang ='-';
        }

          // DURASI KERJA  =========================================
          $durasi_kerja_start = strtotime(''.$date_month_year.' '.$row_absen['time_in'].'');
          $durasi_kerja_end   = strtotime(''.$date_month_year.' '.$row_absen['time_out'].'');
          $diff  				= $durasi_kerja_end - $durasi_kerja_start;
          $durasi_jam   		= floor($diff / (60 * 60));
          $durasi_menit 		= $diff - ($durasi_jam * (60 * 60) );
          $durasi_detik 		= $diff % 60;
          $durasi_kerja 		= ''.$durasi_jam.' jam, '.floor($durasi_menit/60 ).' menit';

          // JAM LEMBUR =========================================
          if($row_absen['time_out'] > $row_absen['jam_pulang']){
            $lembur_kerja_start = strtotime(''.$date_month_year.' '.$row_absen['jam_pulang'].'');
            $lembur_kerja_end   = strtotime(''.$date_month_year.' '.$row_absen['time_out'].'');
            $diff  				      = $lembur_kerja_end - $lembur_kerja_start;
            $lembur_jam   		  = floor($diff / (60 * 60));
            $lembur_menit 		  = $diff - ($lembur_jam * (60 * 60) );
            $lembur 			      = ''.$lembur_jam.' jam, '.floor($lembur_menit/60 ).' menit';

          }else{
            $lembur = '';
          }
          echo'
          <tr style="background:'.$background.';color:'.$warna.'">
              <td class="text-center">'.$d.'</td>
              <td>'.tanggal_ind($date_month_year).'</td>
              <td>'.$shift_name.'<br>'.$row_absen['jam_masuk'].' - '.$row_absen['jam_pulang'].'</td>
              <td class="text-center">'.$row_absen['time_in'].' '.$status_masuk.'</td>
              <td class="text-center">'.$selisih_masuk.'</td>
              <td class="text-center">'.$row_absen['time_out'].' '.$status_pulang.'</td>
              <td class="text-center">'.$selisih_pulang.'</td>
              <td>'.$durasi_kerja.'</td>
              <td>'.$lembur.'</td>
              <td>'.$status_hadir.'</td>
              <td>'.strip_tags($row_absen['information']).'</td>
            </tr>';
            }else{
              echo'
              <tr style="background:'.$background.';color:'.$warna.'">
              <td class="text-center">'.$d.'</td>
              <td>'.tanggal_ind($date_month_year).'</td>
              <td>-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td>'.$status_hadir.'</td>
              <td class="text-right">-</td>
            </tr>';
            }
        }
  
      echo'<tbody>
      </table>';
      if(isset($_GET['from']) OR isset($_GET['to'])){
        $month = $_GET['from'];
        $year  = $_GET['to'];
        $filter ="employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
      } 
      else{
        $filter ="employees_id='$id' AND MONTH(presence_date) ='$month'  AND year(presence_date)='$year'";
      }

      $query_hadir="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Hadir'";
      $hadir= $connection->query($query_hadir);

      $query_sakit="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Sakit'";
      $sakit = $connection->query($query_sakit);

      $query_izin="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Izin'";
      $izin = $connection->query($query_izin);

      $query_cuty="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Cuti'";
      $cuty = $connection->query($query_cuty);

      $query_telat ="SELECT presence_id FROM presence WHERE $filter AND status_in='Telat'";
      $telat = $connection->query($query_telat);

      echo'
      <table class="customTable">
        <tr>
          <td><p>Hadir : <span class="label label-success">'.$hadir->num_rows.'</span></p></td>
          <td><p>Telat : <span class="label label-danger">'.$telat->num_rows.'</span></p></td>
          
          <td><p>Sakit : <span class="label label-warning">'.$sakit->num_rows.'</span></p></td>
          <td><p>Izin : <span class="label label-info">'.$izin->num_rows.'</span></p></td>
          <td><p>Cuti: <span class="label label-warning">'.$cuty->num_rows.'</span></p></td>
          <tr>
      </table>

      <table class="table" style="padding:10px;margin-top:50px;">
          <tr>
              <td width="300">'.tgl_indo($date).'<br>Mengetahui<br><br><br><br><br><br><b>'.$site_director.'</b></td>
          </tr>
      </table>
      </div>
    </div>
  </section>
</body>
</html>';
  if(empty(htmlentities($_GET['tipe'] !=='pdf'))){
    $html = ob_get_contents(); 
    ob_end_clean();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output("Absensi-$employees_name-$date.pdf" ,'I');
  }

}else{
  echo'<center><h3>Data Tidak Ditemukan</h3></center>';
}
}else{
  echo'Data tidak boleh ada yang kosong!';
}






/* -------  CETAK ALL EXCEL-----------------------------------------------*/
break;
case 'all-print':
  if(isset($_GET['pegawai'])){
      $pegawai = strip_tags($_GET['pegawai']);
  } 
  $query ="SELECT employees.id,employees.employees_name,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$pegawai' ORDER BY employees.id DESC";
  $result = $connection->query($query);
  if($result->num_rows > 0){
      $row            = $result->fetch_assoc();
      $employees_name = $row['employees_name'];
      $id             = $row['id'];
      
  if (empty($_GET['print'])) {
      header("Content-type: application/vnd-ms-excel");
      header("Content-Disposition: attachment; filename=Data-Absensi-$date.xls");
    } else {
      echo'<script>
          window.print();
          </script>';
    }

    if(isset($_GET['from']) OR isset($_GET['to'])){
      $bulan   = date ($_GET['from']);
      $tahun      = date($_GET['to']);
  } 
  else{
      $bulan  = date ("m");
      $tahun      = date("Y");
  }
    $hari       = date("d");
    
    $jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));
    $s          = date ("w", mktime (0,0,0,$bulan,1,$tahun));


echo'<!DOCTYPE html>
<html>
<head>
<title>Cetak Data Absensi '.$employees_name.'</title>
<style>
body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{line-height:25px;font-size:20px;margin:0px 0 10px;text-transform:uppercase}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{font-size:30px}table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}
.label {display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap; vertical-align: baseline;border-radius: .25em;}
.label-success{background-color: #00a65a !important;}.label-warning {background-color: #f0ad4e;}.label-info {background-color: #5bc0de;}.label-danger{background-color: #dd4b39 !important;}
p{line-height:20px;padding:0px;margin: 5px;}.pull-right{float:right}
</style>
</head>
<body>';
echo'
<section class="container_box">
  <div class="row">';
  if(isset($_GET['from']) OR isset($_GET['to'])){
    echo'<h3 class="text-center">LAPORAN ABSENSI BULAN <br>'.ambilbulan($_GET['from']).' - '.$_GET['to'].'</h3>';}
    else{
    echo'<h3 class="text-center">LAPORAN ABSENSI BULAN <br>'.ambilbulan($month).' - '.$year.'</h3>';
   }
    echo'
    <p>Nama   : '.$row['employees_name'].'</p>
    <p>Kategori : '.$row['position_name'].'</p><br>
  <div class="content_box">
    <table class="customTable">
      <thead>
        <tr>
          <th class="text-center">No.</th>
          <th>Tanggal</th>
          <td>Shift</td>
          <th class="text-center">Scan Masuk</th>
          <th>Terlambat</th>
          <th class="text-center">Scan Pulang</th>
          <th class="text-center">Pulang Cepat</th>
          <th>Durasi</th>
          <th>Lembur</th>
          <th>Status</th>
          <th>Keterangan</th>
        </tr>
      </thead>
    <tbody>';
    $sum =0;
    $libur =0;

     for ($d=1;$d<=$jumlahhari;$d++) {
        $warna      = '';
        $background = '';
        $status     = 'Tidak Hadir';
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
    }else{
      $query_holiday="SELECT holiday_date,description FROM holiday WHERE holiday_date='$date_month_year'";
      $result_holiday = $connection->query($query_holiday);
        if($result_holiday->num_rows > 0){
            $data_holiday = $result_holiday->fetch_assoc();
            $warna='#ffffff';
            $background ='#FF0000';
            $libur++;
            $status_hadir = $data_holiday['description'];
        }else{
          $status_hadir ='-';
        }

    }


    if(isset($_GET['from']) OR isset($_GET['to'])){
      $month = $_GET['from'];
      $year  = $_GET['to'];
      $filter ="employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
    } 
    else{
      $filter ="employees_id='$id' AND  presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
    }


    $query_absen ="SELECT presence_id,presence_date,shift_id,jam_masuk,jam_pulang,picture_in,picture_out,time_in,time_out,kehadiran,latitude_longtitude_in,latitude_longtitude_out,status_in,status_out,information FROM presence WHERE $filter ";
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
          $diff = (strtotime($row_absen['jam_pulang']) - strtotime($row_absen['time_out']) + strtotime('00:00:00'));
          $selisih_pulang ='-'.date('H:i:s', $diff ).'';
        }
        else{
          $status_pulang='';
          $selisih_pulang ='-';
        }

          // DURASI KERJA  =========================================
          $durasi_kerja_start = strtotime(''.$date_month_year.' '.$row_absen['time_in'].'');
          $durasi_kerja_end   = strtotime(''.$date_month_year.' '.$row_absen['time_out'].'');
          $diff  				= $durasi_kerja_end - $durasi_kerja_start;
          $durasi_jam   		= floor($diff / (60 * 60));
          $durasi_menit 		= $diff - ($durasi_jam * (60 * 60) );
          $durasi_detik 		= $diff % 60;
          $durasi_kerja 		= ''.$durasi_jam.' jam, '.floor($durasi_menit/60 ).' menit';

          // JAM LEMBUR =========================================
          if($row_absen['time_out'] > $row_absen['jam_pulang']){
            $lembur_kerja_start = strtotime(''.$date_month_year.' '.$row_absen['jam_pulang'].'');
            $lembur_kerja_end   = strtotime(''.$date_month_year.' '.$row_absen['time_out'].'');
            $diff  				      = $lembur_kerja_end - $lembur_kerja_start;
            $lembur_jam   		  = floor($diff / (60 * 60));
            $lembur_menit 		  = $diff - ($lembur_jam * (60 * 60) );
            $lembur 			      = ''.$lembur_jam.' jam, '.floor($lembur_menit/60 ).' menit';

          }else{
            $lembur = '';
          }
          echo'
          <tr style="background:'.$background.';color:'.$warna.'">
              <td class="text-center">'.$d.'</td>
              <td>'.format_hari_tanggal($date_month_year).'</td>
              <td>'.$shift_name.'<br>'.$row_absen['jam_masuk'].' - '.$row_absen['jam_pulang'].'</td>
              <td class="text-center">'.$row_absen['time_in'].' '.$status_masuk.'</td>
              <td class="text-center">'.$selisih_masuk.'</td>
              <td class="text-center">'.$row_absen['time_out'].' '.$status_pulang.'</td>
              <td class="text-center">'.$selisih_pulang.'</td>
              <td>'.$durasi_kerja.'</td>
              <td>'.$lembur.'</td>
              <td>'.$status_hadir.'</td>
              <td>'.strip_tags($row_absen['information']).'</td>
            </tr>';
            }else{
              echo'
              <tr style="background:'.$background.';color:'.$warna.'">
              <td class="text-center">'.$d.'</td>
              <td>'.format_hari_tanggal($date_month_year).'</td>
              <td>-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td class="text-center">-</td>
              <td>'.$status_hadir.'</td>
              <td class="text-right">-</td>
            </tr>';
            }
        }
  
      echo'<tbody>
      </table>';
      if(isset($_GET['from']) OR isset($_GET['to'])){
        $month = $_GET['from'];
        $year  = $_GET['to'];
        $filter ="employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
      } 
      else{
        $filter ="employees_id='$id' AND MONTH(presence_date) ='$month'  AND year(presence_date)='$year'";
      }

      $query_hadir="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Hadir'";
      $hadir= $connection->query($query_hadir);

      $query_sakit="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Sakit'";
      $sakit = $connection->query($query_sakit);

      $query_izin="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Izin'";
      $izin = $connection->query($query_izin);

      $query_cuty="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Cuti'";
      $cuty = $connection->query($query_cuty);

      $query_telat ="SELECT presence_id FROM presence WHERE $filter AND status_in='Telat'";
      $telat = $connection->query($query_telat);

      echo'
      <table class="customTable">
        <tr>
          <td><p>Hadir : <span class="label label-success">'.$hadir->num_rows.'</span></p></td>
          <td><p>Telat : <span class="label label-danger">'.$telat->num_rows.'</span></p></td>
          
          <td><p>Sakit : <span class="label label-warning">'.$sakit->num_rows.'</span></p></td>
          <td><p>Izin : <span class="label label-info">'.$izin->num_rows.'</span></p></td>
          <td><p>Cuti: <span class="label label-warning">'.$cuty->num_rows.'</span></p></td>
          <tr>
      </table>

      <table class="table" style="padding:10px;margin-top:50px;">
        <tr>
            <td width="300">'.tgl_indo($date).'<br>Mengetahui<br><br><br><br><br><br><b>'.$site_director.'</b></td>
        </tr>
       </table>
      </div>
    </div>
  </section>
</body>
</html>';

}else{
  echo'<center><h3>Data Tidak Ditemukan</h3></center>';
}

break;
}
}?>