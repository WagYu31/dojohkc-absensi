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
case'excel':

$filterParts = [];
$tahun_ajaran = isset($_GET['tahun_ajaran']) ? $_GET['tahun_ajaran'] : $year;
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : $month;
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : $year;
$filterParts[] = "pembayaran_spp.tahun_pelajaran='$tahun_ajaran' AND pembayaran_spp.bulan='$bulan' AND pembayaran_spp.tahun='$tahun'";

if (!empty($_GET['user'])) {
    $user = htmlentities($_GET['user']);
    $filterParts[] = "pembayaran_spp.employees_id='$user'";
}

if (!empty($_GET['status'])) {
    $status = htmlentities($_GET['status']);
    $filterParts[] = "pembayaran_spp.status='$status'";
}

$filter = 'WHERE ' . implode(' AND ', $filterParts);


$query ="SELECT pembayaran_spp.*, employees.employees_name,tahun_pelajaran.tahun_mulai,tahun_pelajaran.tahun_selesai FROM pembayaran_spp 
LEFT JOIN tahun_pelajaran ON tahun_pelajaran.tahun_pelajaran_id =  pembayaran_spp.tahun_pelajaran
LEFT JOIN employees ON employees.id= pembayaran_spp.employees_id";
$result = $connection->query($query);
if($result->num_rows > 0){

$query_total = "SELECT SUM(nominal) AS total_transaksi FROM pembayaran_spp";
$result_total = mysqli_query($connection, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_transaksi = $row_total['total_transaksi'] ?? 0;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
// Set header style
$style_col = [
  'font' => ['bold' => true],
  'alignment' => [
    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
  ],
  'borders' => [
    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
  ],
  'fill' => [
    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    'startColor' => ['argb' => 'FFC0C0C0']
  ]
];

// Set row style
$style_row = [
  'alignment' => [
    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
  ],
  'borders' => [
    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE],
    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE],
    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE],
    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE]
  ]
];


// Judul
$sheet->setCellValue('A1', "DATA PEMBAYARAN SPP BULAN ".ambilbulan($bulan)." - ".$tahun."");
$sheet->mergeCells('A1:I1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15);

// Header
$headers = [
  "No", "ID", "Nama", "Tahun Ajaran", "Bulan", "Angsuran_Ke", "Nominal", "Tanggal Bayar", "Status Pembayaran"
];

$col = 'A';
foreach ($headers as $header) {
  $sheet->setCellValue($col . '3', $header);
  $sheet->getStyle($col . '3')->applyFromArray($style_col);
  $sheet->getColumnDimension($col)->setAutoSize(true);
  $col++;
}

$row = 4;
$no = 1;

while ($data = $result->fetch_assoc()) {
  $col = 'A';
  $columns = [
    $no++,
    htmlspecialchars($data['order_id'] ?? ''),
    htmlspecialchars($data['employees_name'] ?? ''),
    $data['tahun_mulai'].' s.d '.$data['tahun_selesai'],
    ambilbulan($data['bulan']).' '.$data['tahun'],
    strip_tags($data['angsuran_ke']??'-'),
    strip_tags($data['nominal']??'0'),
    tanggal_ind($data['tanggal']).' '.$data['time'],
    ucfirst($data['status']),
  ];

  foreach ($columns as $value) {
    $sheet->setCellValue($col . $row, $value);
    $col++;
  }
  $row++;
}

// Tambah total transaksi
$sheet->setCellValue('F' . ($row + 1), 'Total Transaksi');
$sheet->setCellValue('G' . ($row + 1), $total_transaksi);
$sheet->getStyle('F' . ($row + 1))->getFont()->setBold(true);
$sheet->getStyle('G' . ($row + 1))->getFont()->setBold(true);
$sheet->getStyle('F' . ($row + 1) . ':G' . ($row + 1))->applyFromArray([
    'borders' => [
        'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
    ]
]);
$sheet->getStyle('G' . ($row + 1))->getNumberFormat()->setFormatCode('#,##0');

// Set column widths
foreach (range('A', 'H') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set orientasi halaman (opsional)
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

// Output file ke browser
$filename = "Data_Pembayaran_".$bulan."-".$tahun.".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

}else{
  echo 'Tidak ada data data pembayaran!.';
}


break;
case'print':
  $orderId = isset($_GET['order']) ? htmlentities($_GET['order']) : null;
  if (!$orderId) {
      echo 'Order ID tidak ditemukan!';
      exit;
  }

  $query ="SELECT pembayaran_spp.*, employees.employees_name,tahun_pelajaran.tahun_mulai,tahun_pelajaran.tahun_selesai FROM pembayaran_spp 
  LEFT JOIN tahun_pelajaran ON tahun_pelajaran.tahun_pelajaran_id =  pembayaran_spp.tahun_pelajaran
  LEFT JOIN employees ON employees.id= pembayaran_spp.employees_id
  WHERE pembayaran_spp.order_id='$orderId'";
  $result = $connection->query($query);
  if($result->num_rows > 0){
    $data = $result->fetch_assoc();
 
  echo'
  <!DOCTYPE html>
  <html lang="id">
  <head>
      <meta charset="UTF-8">
      <title>Struk Pembayaran</title>
      <style>
          body {
              font-family:arial;
              width:450px;
              margin: 0 auto;
              padding: 20px;
              border: 1px dashed #aaa;
              background: #fff;
          }
          .center {
              text-align: center;
          }
          .line {
              border-bottom: 1px dashed #aaa;
              margin: 10px 0;
          }
          .bold {
              font-weight: bold;
          }
          .print-btn {
              text-align: center;
              margin: 20px 0;
          }
          @media print {
              .print-btn {
                  display: none;
              }
              body {
                  border: none;
              }
          }

          .table tr td{
            padding: 3px 0px;
          }
      </style>';?>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function($) {
      var ua = navigator.userAgent.toLowerCase();
      var isAndroid = ua.indexOf("android") > -1;

        if (isAndroid) {
          var gadget = new cloudprint.Gadget();
          gadget.setPrintDocument("url", $('title').html(), window.location.href, "utf-8");
          gadget.openPrintDialog();
        } else {
          window.print();
          window.onafterprint = window.close;
        }
        return false;
        
    });
  </script>

  </head>
  <body>
  <?php
    echo'
    <div class="center">
        <h4 style="margin:0px">Struk Pembayaran SPP<br>'.$site_name.'</h4>
        <div class="line"></div>
    </div>

      <table class="table">
        <tbody>
            <tr>
              <td width="55%">ID Pembayaran</td>
              <td>:</td>
              <td>'.htmlspecialchars($data['order_id']).'</td>
            </tr>

            <tr>
              <td>Tahun Ajaran</td>
              <td>:</td>
              <td>'.$data['tahun_mulai'].' s.d '.$data['tahun_selesai'].'</td>
            </tr>

            <tr>
              <td>Angsuran ke</td>
              <td>:</td>
              <td>'.strip_tags($data['angsuran_ke']).'</td>
            </tr>

            <tr>
              <td>Nama</td>
              <td>:</td>
              <td>'.htmlspecialchars($data['employees_name']).'</td>
            </tr>

            <tr>
              <td>Jumlah</td>
              <td>:</td>
              <td>'.format_angka($data['nominal']??'0').'</td>
            </tr>

            <tr>
              <td>Dibuat tanggal</td>
              <td>:</td>
              <td>'.tanggal_ind($data['tanggal']).' '.$data['time'].'</td>
            </tr>

            <tr>
              <td>Status</td>
              <td>:</td>
              <td>'.ucfirst($data['status']).'</td>
            </tr>
        </tbody>
      </table>
      <div class="line"></div>
      <div class="center">Terima kasih telah bertransaksi</div>

  </body>
  </html>';
    
}else{
  echo 'Tidak ada data data pembayaran!.';
}


/** Print berdasarkan User */
break;
case'print-cekspp':

if(isset($_GET['user'])){
  $user = anti_injection($_GET['user']);
}else{
  $user = '';
}

if(isset($_GET['tahun_pelajaran'])){
  $tahun_pelajaran = anti_injection($_GET['tahun_pelajaran']);
}else{
  $tahun_pelajaran = '';
}

$query_spp  ="SELECT tahun,nominal FROM spp WHERE tahun_pelajaran='$tahun_pelajaran' AND status='Y'";
$result_spp = $connection->query($query_spp);
if($result_spp->num_rows > 0){
  $data_spp = $result_spp->fetch_assoc();
  $tunggakan = $data_spp['nominal'];
}else{
  $tunggakan = 0;
}

$no =0;
$jumlah_dibayar = 0;
$jumlah_tunggakan = 0;
$total_pembayaran = 0;

$query_pembayaran ="SELECT pembayaran_spp.*,employees.employees_name FROM pembayaran_spp
INNER JOIN employees ON employees.id=pembayaran_spp.employees_id WHERE pembayaran_spp.employees_id='$user' AND pembayaran_spp.tahun_pelajaran='$tahun_pelajaran' AND pembayaran_spp.status='berhasil' ORDER BY pembayaran_spp_id ASC";
$result_pembayaran = $connection->query($query_pembayaran);
if($result_pembayaran->num_rows > 0){

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
  </tr>
</thead>
<tbody>';
    while($data_pembayaran = $result_pembayaran->fetch_assoc()){$no++;
    echo'
      <tr>
        <td class="text-center">'.$no.'</td>
        <td>'.strip_tags($data_pembayaran['employees_name']).'</td>
        <td>'.$tahun.'</td>
        <td>'.tanggal_ind($data_pembayaran['tanggal']).'</td>
        <td>'.ambilbulan($data_pembayaran['bulan']).'</td>
        <td>'.strip_tags($data_pembayaran['tahun']).'</td>
        <td>Rp '.format_angka($data_pembayaran['nominal']).'</td>
      </tr>';
    }

  $query_total = "SELECT SUM(nominal) AS total_pembayaran FROM pembayaran_spp WHERE employees_id='$user' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
  $result_total = $connection->query($query_total);
  if($result_total->num_rows > 0){
    $data_total = $result_total->fetch_assoc();
    $total_pembayaran = $data_total['total_pembayaran']??'0';
    $jumlah_tunggakan = $tunggakan - $total_pembayaran;
  }else{
    $total_pembayaran ='0';
    $jumlah_tunggakan = '0';
  }

echo'
</tbody>
    <tfoot>
      <tr>
        <td colspan="6" class="text-right text-success"><b>SPP Tahun Ajaran '.$tahun.'</b></td>
        <td class="text-right"><b>Rp '.format_angka($tunggakan).'</b></td>
      </tr>

      <tr>
        <td colspan="6" class="text-right text-info"><b>Jumlah Dibayar</b></td>
        <td class="text-right"><b>Rp '.format_angka($total_pembayaran).'</b></td>
      </tr>

      <tr>
        <td colspan="6" class="text-right text-danger"><b>Tunggakan</b></td>
        <td class="text-right text-danger"><b>Rp '.format_angka($jumlah_tunggakan).'</b></td>
      </tr>
    </tfoot>
</table>
</div>';
}else{
  echo'Belum ada data pembayaran!';
}
break;
 }
}?>
