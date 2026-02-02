<?php session_start(); error_reporting(0);
    require_once'../sw-library/sw-config.php'; 
    require_once'../sw-library/sw-function.php';
    include_once'../sw-library/vendor/autoload.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) OR !isset($_COOKIE['COOKIES_COOKIES'])){
    //Kondisi tidak login
}else{
require_once'../sw-mod/out/sw-cookies.php';


switch (@$_GET['action']){
case 'pdf':
$query ="SELECT employees.id,employees.employees_name,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id  AND employees.id='$row_user[id]'";
$result = $connection->query($query);
  if($result->num_rows > 0){
  $row= $result->fetch_assoc();
if(isset($_GET['from']) OR isset($_GET['to'])){
      $from = date('Y-m-d', strtotime($_GET['from']));
      $to   = date('Y-m-d', strtotime($_GET['to']));
      $filter ="presence_date BETWEEN '$from' AND '$to'";
  } 
  else{
      $filter ="MONTH(presence_date) ='$month'";
}


    $mpdf = new \Mpdf\Mpdf();
    ob_start();
    
    $mpdf->SetHTMLFooter('
      <table width="100%" style="border-top:solid 1px #333;font-size:11px;">
          <tr>
              <td width="60%" style="text-align:left;">Simpanlah lembar Absensi ini.</td>
              <td width="35%" style="text-align: right;">Dicetak tanggal '.tgl_indo($date).'</td>
          </tr>
      </table>');
echo'<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{padding:10px 0;line-height:25px;font-size:20px;margin:5px 0 15px;text-transform: uppercase;}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{
      font-size:30px;}
    table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}.badge-danger,a.badge-danger{background:#ff396f!important}.badge-success,a.badge-success{background:#1dcc70!important}.badge-warning,a.badge-warning{background:#ffb400!important;color:#fff}.badge-info,a.badge-info{background:#754aed!important}.badge{font-size:12px;line-height:1em;border-radius:100px;letter-spacing:0;height:22px;min-width:22px;padding:0 6px;display:inline-flex;align-items:center;justify-content:center;font-weight:400;color:#fff}
    </style>
</head>
<body>';
echo'
    <section class="container_box">
      <div class="row">';
      if(isset($_GET['from']) OR isset($_GET['to'])){
         echo'<h3>DATA ABSENSI "'.$employees_name.'" PER TANGGAl '.tanggal_ind($from).' S/D '.tanggal_ind($to).'</h3>';}
        else{
        echo'<h3>DATA ABSENSI "'.$employees_name.'" BULAN '.ambil_bulan($month).' '.$year.'</h3>';
        }
        echo'
      <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th>Waktu Masuk</th>
              <th>Waktu Pulang</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';

    $query_absen ="SELECT presence_id,presence_date,shift_id,jam_masuk,jam_pulang,time_in,time_out,kehadiran,status_in,status_out,information FROM presence WHERE employees_id='$row_user[id]' AND $filter ORDER BY presence_id ASC";
    $result_absen = $connection->query($query_absen);
    if($result_absen->num_rows > 0){
    while ($row_absen= $result_absen->fetch_assoc()) {$no++;

      if($row_absen['status_in']=='Telat'){
        $status=' <span class="badge badge-danger">'.$row_absen['status_in'].'</span>';
      }
      elseif ($row_absen['status_in']='Tepat Waktu') {
        $status='<span class="badge badge-success">'.$row_absen['status_in'].'</span>';
      }
      else{
        $status='<span class="badge badge-danger">'.$row_absen['status_in'].'</span>';
      }

      
      if($row_absen['time_out']=='00:00:00'){
        $status_pulang='Belum Absen';
      }else{
        if($row_absen['status_out']=='Pulang Cepat'){
          $status_pulang='<span class="badge badge-danger">'.$row_absen['status_pulang'].'</span>';
        }
        else{
          $status_pulang='';
        }
      }


       echo'<tr>
              <td class="text-center">'.$no.'</td>
              <td>'.tgl_ind($row_absen['presence_date']).'<br>'.$row_absen['jam_masuk'].' - '.$row_absen['jam_pulang'].'</td>
              <td>'.$row_absen['time_in'].' '.$status.'</td>
              <td>'.$row_absen['time_out'].' '.$status_pulang.'</td>
              <td>'.$row_absen['kehadiran'].'</td>
              <td>'.$row_absen['information'].'</td>
            </tr>';
        }
  
      }else{
        echo'<center><h3>Data tidak ditemukan..!</h3></center>';
      }
      echo'<tbody>
      </table>';

      $query_hadir="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Hadir'";
      $hadir= $connection->query($query_hadir);

      $query_sakit="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Sakit' ORDER BY presence_id";
      $sakit = $connection->query($query_sakit);

      $query_izin="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Izin' ORDER BY presence_id";
      $izin = $connection->query($query_izin);

      $query_telat ="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND status_in='Telat'";
      $telat = $connection->query($query_telat);

      echo'
      <p>Hadir : <span class="badge badge-success">'.$hadir->num_rows.'</span></p>
      <p>Telat : <span class="label badge badge-danger">'.$telat->num_rows.'</span></p>
      <p>Sakit : <span class="badge badge-warning">'.$sakit->num_rows.'</span></p>
      <p>Izin : <span class="badge badge-info">'.$izin->num_rows.'</span></p>
        </div>
      </div>
    </section>
</body>
</html>';
    $html = ob_get_contents(); 
    ob_end_clean();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output("Absensi-$employees_name-$date.pdf" ,'I');

}else{

}

break;
case 'excel':
$query ="SELECT employees.id,employees.employees_name,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$row_user[id]'";
$result = $connection->query($query);
if($result->num_rows > 0){
  $row= $result->fetch_assoc();

  if(isset($_GET['from']) OR isset($_GET['to'])){
        $from = date('Y-m-d', strtotime($_GET['from']));
        $to   = date('Y-m-d', strtotime($_GET['to']));
        $filter ="presence_date BETWEEN '$from' AND '$to'";
    } 
    else{
        $filter ="MONTH(presence_date) ='$month'";
  }

   header("Content-type: application/vnd-ms-excel");
   header("Content-Disposition: attachment; filename=Data-Absensi-$employees_name-$date.xls");

echo'<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{padding:10px 0;line-height:25px;font-size:20px;margin:5px 0 15px;text-transform: uppercase;}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{
      font-size:30px;}
      table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}.badge-danger,a.badge-danger{background:#ff396f!important}.badge-success,a.badge-success{background:#1dcc70!important}.badge-warning,a.badge-warning{background:#ffb400!important;color:#fff}.badge-info,a.badge-info{background:#754aed!important}.badge{font-size:12px;line-height:1em;border-radius:100px;letter-spacing:0;height:22px;min-width:22px;padding:0 6px;display:inline-flex;align-items:center;justify-content:center;font-weight:400;color:#fff}
    </style>
</head>
<body>';
echo'
    <section class="container_box">
      <div class="row">';
      if(isset($_GET['from']) OR isset($_GET['to'])){
        echo'<h3>DATA ABSENSI "'.$employees_name.'" PER TANGGAl '.tanggal_ind($from).' S/D '.tanggal_ind($to).'</h3>';}
      else{
         echo'<h3>DATA ABSENSI "'.$employees_name.'" BULAN '.ambilbulan($month).' '.$year.'</h3>';
      }
        echo'
      <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th>Waktu Masuk</th>
              <th>Waktu Pulang</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';
        $no=0;
        $query_absen ="SELECT presence_id,presence_date,shift_id,jam_masuk,jam_pulang,time_in,time_out,kehadiran,status_in,status_out,information FROM presence WHERE employees_id='$row_user[id]' AND $filter ORDER BY presence_id ASC";
    $result_absen = $connection->query($query_absen);
    if($result_absen->num_rows > 0){
    while ($row_absen= $result_absen->fetch_assoc()) {$no++;

      if($row_absen['status_in']=='Telat'){
        $status=' <span class="badge badge-danger">'.$row_absen['status_in'].'</span>';
      }
      elseif ($row_absen['status_in']='Tepat Waktu') {
        $status='<span class="badge badge-success">'.$row_absen['status_in'].'</span>';
      }
      else{
        $status='<span class="badge badge-danger">'.$row_absen['status_in'].'</span>';
      }

      
      if($row_absen['time_out']=='00:00:00'){
        $status_pulang='Belum Absen';
      }else{
        if($row_absen['status_out']=='Pulang Cepat'){
          $status_pulang='<span class="badge badge-danger">'.$row_absen['status_pulang'].'</span>';
        }
        else{
          $status_pulang='';
        }
      }


       echo'<tr>
              <td class="text-center">'.$no.'</td>
              <td>'.tgl_ind($row_absen['presence_date']).'<br>'.$row_absen['jam_masuk'].' - '.$row_absen['jam_pulang'].'</td>
              <td>'.$row_absen['time_in'].' '.$status.'</td>
              <td>'.$row_absen['time_out'].' '.$status_pulang.'</td>
              <td>'.$row_absen['kehadiran'].'</td>
              <td>'.$row_absen['information'].'</td>
            </tr>';
        }
  
      }else{
        echo'<center><h3>Data tidak ditemukan..!</h3></center>';
      }
      echo'<tbody>
      </table>';

      $query_hadir="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Hadir'";
      $hadir= $connection->query($query_hadir);

      $query_sakit="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Sakit' ORDER BY presence_id";
      $sakit = $connection->query($query_sakit);

      $query_izin="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND kehadiran='Izin' ORDER BY presence_id";
      $izin = $connection->query($query_izin);

      $query_telat ="SELECT presence_id FROM presence WHERE employees_id='$row_user[id]' AND $filter AND status_in='Telat'";
      $telat = $connection->query($query_telat);

      echo'
      <p>Hadir : <span class="badge badge-success">'.$hadir->num_rows.'</span></p>
      <p>Telat : <span class="label badge badge-danger">'.$telat->num_rows.'</span></p>
      <p>Sakit : <span class="badge badge-warning">'.$sakit->num_rows.'</span></p>
      <p>Izin : <span class="badge badge-info">'.$izin->num_rows.'</span></p>

        </div>
      </div>
    </section>
</body>
</html>';
}else{
  echo'Data tidak ditemukan';
}


break;
case'print-spp':

if(isset($_GET['id'])){
  $id = anti_injection(epm_decode($_GET['id']));

$query_pembayaran ="SELECT * FROM pembayaran_spp WHERE pembayaran_spp_id='$id'";
$result_pembayaran = $connection->query($query_pembayaran);
if($result_pembayaran->num_rows > 0){
echo'
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <title>Laporan SPP</title>
    <style>

    body{font-family:Arial,Helvetica,sans-serif}
    .text-center{
      text-align: center;
    }
    
    .kop {
      position:relative;
      display:contents;
      
    }

    .kop img{
      width:100%;
      height:200px;margin:0px 0px 20px 0px;
      object-fit: contain;
    }

    table.datatable{
      width:100%;
      background-color:#fff;
      border-collapse:collapse;
      border-width:1px;
      border-color:#b3b3b3;
      border-style:solid;
      color:#000;
      margin:10px 0px 0px 0px;
  }
    table.datatable td,table.datatable th{
      border-width:1px;
      border-color:#b3b3b3;
      border-style:solid;
      padding:5px;text-align:left;
      
    }
    table.datatable th,
    table.datatable th tr{
      background-color:#666666;
      color:#ffffff;
    }
    table.datatable td.text-center,
    table.datatable th.text-center{text-align:center}

    .badge {
      font-size: 66%;
      font-weight: 600;
      line-height: 1;
      display: inline-block;
      padding: 0.35rem 0.375rem;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
      text-align: center;
      vertical-align: baseline;
      white-space: nowrap;
      border-radius: 0.375rem;
    }
    .badge-success {
      color: #1aae6f;
      background-color: #b0eed3;
    }
    
    .badge-danger {
      color: #f80031;
      background-color: #fdd1da;
    }
    
    .badge-info{
      color: #0080c0;
      background-color: #4aa5ff;
    }

    .badge-warning{
      color: #ff3709;
      background-color: #fee6e0;
    }

    .rounded {
      border-radius: 0.375rem !important;
    }

    .footer-count{
      position:relative;
      display: inline-block;
    }
    .footer-count p{
      display: inline-block;
      font-size:14px;
      margin-right:10px;
    }

    </style>
  <script>
    window.onafterprint = window.close;
    window.print();
  </script>

</head>
<body>

<div class="container">
  <div class="mt-3">DATA PEMBAYARAN SPP TAHUN PELAJARAN '.$tahun.'</div>
<table class="table datatable">
<thead class="thead-light">
  <tr>
    <th width="4">No</th>
    <th>Nama</th>
    <th>Tahun Ajaran</th>
    <th>Tanggal Bayar</th>
    <th>Bulan Bayar</th>
    <th>Tahun Bayar</th>
    <th>Nominal</th>
    <th>Status</th>
  </tr>
</thead>
<tbody>';
    while($data_pembayaran = $result_pembayaran->fetch_assoc()){$no++;

  $query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$data_pembayaran[tahun_pelajaran]'";
  $result_tahun = $connection->query($query_tahun);
  if($result_tahun->num_rows > 0){
    $data_tahun = $result_tahun->fetch_assoc();
    $tahun = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
  }else{
    $tahun = 'Tahun Pelajaran tidak ada';
  }

    echo'
      <tr>
        <td class="text-center">'.$no.'</td>
        <td>'.strip_tags($row_user['employees_name']).'</td>
        <td>'.$tahun.'</td>
        <td>'.tanggal_ind($data_pembayaran['tanggal']).'</td>
        <td>'.ambilbulan($data_pembayaran['bulan']).'</td>
        <td>'.strip_tags($data_pembayaran['tahun']).'</td>
        <td>Rp '.format_angka($data_pembayaran['nominal']).'</td>
        <td>'.ucfirst($data_pembayaran['status']).'</td>
      </tr>';
    }

  echo'
  </tbody>
  </table>
  </div>';
  }else{
    echo'Belum ada data pembayaran!';
  }
}

break;
}
}?>