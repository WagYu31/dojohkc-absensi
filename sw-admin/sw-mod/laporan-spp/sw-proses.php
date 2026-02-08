<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';

switch (@$_GET['action']){
case 'filtering':

$filterParts = [];
$tahun_pelajaran = isset($_POST['tahun_pelajaran']) ? $_POST['tahun_pelajaran'] : $year;
$filterParts[] = "tahun_pelajaran='$tahun_pelajaran'";

if (!empty($_POST['user'])) {
  $user = htmlentities($_POST['user']);
  $filter = "WHERE id='$user'";
}else{
  $filter ='';
}

$bulan_nama =array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

$query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$tahun_pelajaran'";
$result_tahun = $connection->query($query_tahun);
if($result_tahun->num_rows > 0){
    $data_tahun = $result_tahun->fetch_assoc();
    $tahun = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
}else{
    $tahun = 'Tahun Pelajaran tidak ada';
}

$jumlah_dibayar = 0;
$jumlah_tunggakan = 0;
$total_pembayaran = 0;
echo'
<div class="table-responsive" style="overflow-x: auto!important;">
<table class="table align-items-center table-bordered datatable" style="width:100%">
  <thead class="bg-light">
    <tr>
      <th rowspan="2" width="40" class="text-center" style="vertical-align: middle;">No</th>
      <th rowspan="2" style="vertical-align: middle;">Nama</th>
      <th class="text-center" colspan="12">Tahun Ajaran '.$tahun.'</th>
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
          <td>'.strip_tags($data_user['employees_name']).'</td>';

          for($b=1; $b<=12; $b++){
            $query_pembayaran ="SELECT SUM(nominal) AS total_nominal FROM pembayaran_spp WHERE employees_id='$data_user[id]' AND CAST(bulan AS UNSIGNED)=$b AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
            $result_pembayaran = $connection->query($query_pembayaran);
            $data_pembayaran = $result_pembayaran->fetch_assoc();
            if(!empty($data_pembayaran['total_nominal'])){
              $nominal_pembayaran = 'Rp '.format_angka($data_pembayaran['total_nominal']).'';
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
  </div>';?>
  <script>
  $(".datatable").dataTable({
      "iDisplayLength":30,
      "aLengthMenu": [[30, 40, 50, -1], [30, 40, 50, "All"]]
  });
</script>
<?php 
break;
}}