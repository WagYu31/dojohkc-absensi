<?php use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once'../../../sw-library/sw-config.php';
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}else{
  require_once'../../../sw-library/sw-function.php';
require '../../../sw-library/PhpSpreadsheet/autoload.php';

$no = 0;
switch (@$_GET['action']){
case 'print':
$filterParts = [];
$tahun_pelajaran = isset($_GET['tahun_pelajaran']) ? $_GET['tahun_pelajaran'] : $year;
$filterParts[] = "tahun_pelajaran='$tahun_pelajaran'";

if (!empty($_GET['user'])) {
  $user = htmlentities($_GET['user']);
  $filter = "WHERE id='$user'";
}else{
  $filter ='';
}

$bulan_nama =array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

$jumlah_dibayar = 0;
$jumlah_tunggakan = 0;
$total_pembayaran = 0;


$query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$tahun_pelajaran'";
$result_tahun = $connection->query($query_tahun);
if($result_tahun->num_rows > 0){
    $data_tahun = $result_tahun->fetch_assoc();
    $tahun = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
}else{
    $tahun = 'Tahun Pelajaran tidak ada';
}

echo'
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <title>Laporan SPP</title>
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="author" content="s-widodo.com">
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
    <div class="row">
      <div class="col-md-12">
        <div class="mt-3">DATA PEMBAYARAN SPP TAHUN PELAJARAN '.$tahun.'</div>
      </div>

      <div class="col-md-12">
      <table class="datatable mt-3">
      <thead>
        <tr>
            <th rowspan="2" width="40" class="text-center" style="vertical-align: middle;">No</th>
            <th rowspan="2" style="vertical-align: middle;">Nama</th>
            <th class="text-center" colspan="12">Tahun'.$tahun.'</th>
            <th rowspan="2">JUMLAH DIBAYAR</th>
            <th rowspan="2">TUNGGAKAN</th>
        </tr>
        <tr class="bg-light">';
          for($bulan=1; $bulan<=12; $bulan++){
            echo'
            <th>'.$bulan_nama[$bulan].'</th>';
          }
          echo'
          </tr>
      </thead>
      <tbody>';

      $query_user ="SELECT * FROM employees $filter";
      $result_user = $connection->query($query_user);
      if ($result_user->num_rows > 0) {$no=0;
        while ($data_user = $result_user->fetch_assoc()){$no++;
  
          $query_spp  ="SELECT tahun,nominal FROM spp WHERE status='Y' AND tahun_pelajaran='$tahun_pelajaran'";
        $result_spp = $connection->query($query_spp);
        if($result_spp->num_rows > 0){
          $data_spp = $result_spp->fetch_assoc();
          $tunggakan = $data_spp['nominal'];
        }else{
          $tunggakan = 0;
        }

        $query_total = "SELECT SUM(nominal) AS total_pembayaran FROM pembayaran_spp WHERE employees_id='$data_user[id]' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
        $result_total = $connection->query($query_total);
        if($result_total->num_rows > 0){

          $data_total = $result_total->fetch_assoc();
          $total_pembayaran = $data_total['total_pembayaran'];
          $jumlah_tunggakan = $tunggakan - $total_pembayaran;
        }else{
          $total_pembayaran ='0';
          $jumlah_tunggakan = '0';
        }
         
          echo'
          <tr>
            <td>'.$no.'</td>
            <td>'.strip_tags($data_user['employees_name']??'-').'</td>';
            for($bulan=1; $bulan<=12; $bulan++){

              $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
              $query_pembayaran ="SELECT nominal FROM pembayaran_spp WHERE employees_id='$data_user[id]' AND bulan='$bulan' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
              $result_pembayaran = $connection->query($query_pembayaran);
              if($result_pembayaran->num_rows > 0){
                $data_pembayaran = $result_pembayaran->fetch_assoc();
                $nominal_pembayaran = 'Rp '.format_angka($data_pembayaran['nominal']??'0').'';
              }else{
                $nominal_pembayaran = '-';
              }
  
              echo'
              <td>'.$nominal_pembayaran.'</td>';
            }
            echo'
            <td class="text-info"><b>Rp '.format_angka($total_pembayaran??'0').'</b></td>
            <td class="text-danger"><b>Rp '.format_angka($jumlah_tunggakan??'0').'</b></td>
          </tr>';
        }
          }else{
            echo'
            <tr>
            <td>-</td>
            <td>-</td>';
            for($bulan=1; $bulan<=12; $bulan++){
              echo'
              <td>-</td>';
            }
            echo'
            <td>-</td>
            <td>-</td>
          </tr>';
          }
        echo'
          </tbody>
        </table>

      </div>
    </div>
  </div>
</body>
</html>';


/** Excel */
break;
case'excel':

$filterParts = [];
$tahun_pelajaran = isset($_GET['tahun_pelajaran']) ? $_GET['tahun_pelajaran'] : $year;
$filterParts[] = "tahun_pelajaran='$tahun_pelajaran'";

if (!empty($_GET['user'])) {
  $user = htmlentities($_GET['user']);
  $filter = "WHERE id='$user'";
} else {
  $filter = '';
}

$bulan_nama = array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

$jumlah_dibayar = 0;
$jumlah_tunggakan = 0;
$total_pembayaran = 0;

$query_tahun = "SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$tahun_pelajaran'";
$result_tahun = $connection->query($query_tahun);
if($result_tahun->num_rows > 0){
    $data_tahun = $result_tahun->fetch_assoc();
    $tahun = $data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'];
} else {
    $tahun = 'Tahun Pelajaran tidak ada';
}

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties
$sheet->setTitle('Laporan SPP');

// Header
$sheet->setCellValue('A1', 'DATA PEMBAYARAN SPP TAHUN PELAJARAN '.$tahun);
$sheet->mergeCells('A1:P1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15);

 // Table Header
$header = ['No', 'Nama'];
foreach ($bulan_nama as $nama_bulan) {
  $header[] = $nama_bulan;
}
$header[] = 'JUMLAH DIBAYAR';
$header[] = 'TUNGGAKAN';

$sheet->fromArray($header, NULL, 'A3');

// Data
$row = 4;
$query_user = "SELECT * FROM employees $filter";
$result_user = $connection->query($query_user);
if ($result_user->num_rows > 0) {
  $no = 0;
  while ($data_user = $result_user->fetch_assoc()) {
    $no++;

    $query_spp = "SELECT tahun,nominal FROM spp WHERE status='Y' AND tahun_pelajaran='$tahun_pelajaran'";
    $result_spp = $connection->query($query_spp);
    if($result_spp->num_rows > 0){
      $data_spp = $result_spp->fetch_assoc();
      $tunggakan = $data_spp['nominal'];
    } else {
      $tunggakan = 0;
    }

    $query_total = "SELECT SUM(nominal) AS total_pembayaran FROM pembayaran_spp WHERE employees_id='{$data_user['id']}' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
    $result_total = $connection->query($query_total);
    if($result_total->num_rows > 0){
      $data_total = $result_total->fetch_assoc();
      $total_pembayaran = $data_total['total_pembayaran'];
      $jumlah_tunggakan = $tunggakan - $total_pembayaran;
    } else {
      $total_pembayaran = 0;
      $jumlah_tunggakan = 0;
    }

    $data_row = [$no, strip_tags($data_user['employees_name']??'-')];
    for($bulan=1; $bulan<=12; $bulan++){
      $bulan_str = str_pad($bulan, 2, '0', STR_PAD_LEFT);
      $query_pembayaran = "SELECT nominal FROM pembayaran_spp WHERE employees_id='{$data_user['id']}' AND bulan='$bulan_str' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
      $result_pembayaran = $connection->query($query_pembayaran);
      if($result_pembayaran->num_rows > 0){
        $data_pembayaran = $result_pembayaran->fetch_assoc();
        $nominal_pembayaran = $data_pembayaran['nominal']??0;
      } else {
        $nominal_pembayaran = 0;
      }
      $data_row[] = $nominal_pembayaran;
    }
    $data_row[] = $total_pembayaran;
    $data_row[] = $jumlah_tunggakan;
    $sheet->fromArray($data_row, NULL, 'A'.$row);
    $row++;
  }
} else {
  $data_row = ['-', '-'];
  for($bulan=1; $bulan<=12; $bulan++){
    $data_row[] = '-';
  }
  $data_row[] = '-';
  $data_row[] = '-';
  $sheet->fromArray($data_row, NULL, 'A'.$row);
}

// Format header
$lastCol = chr(ord('A') + count($header) - 1);
$sheet->getStyle('A3:'.$lastCol.'3')->getFont()->setBold(true);

// Set column width otomatis
for ($col = 0; $col < count($header); $col++) {
  $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
  $sheet->getColumnDimension($colLetter)->setAutoSize(true);
}

// Output Excel file
$filename = 'Laporan_SPP_'.$tahun_pelajaran.'.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
break;
 }
}?>
