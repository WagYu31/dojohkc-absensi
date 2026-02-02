<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';


switch (@$_GET['action']){
case 'dropdown':
  if (empty($_POST['kelas'])) {
    $kelas = '';
  } else {
    $kelas = anti_injection($_POST['kelas']);
  }

$query_user = "SELECT user_id,nama_lengkap FROM user WHERE kelas='$kelas'";
$result_user = $connection->query($query_user);
if($result_user->num_rows > 0) {
echo'<option value="">Semua Siswa</option>';
  while($data_user = $result_user->fetch_assoc()){
    echo'<option value="'.$data_user['user_id'].'">'.strip_tags($data_user['nama_lengkap']).'</option>';
  }
}else{
  echo'<option value="">Data tidak ditemukan</option>';
}



/** Datta Pembayaran */
break;
case 'data-pembayaran':
if(isset($_POST['user'])){
  $user = anti_injection($_POST['user']);
}else{
  $user = '';
}

if(isset($_POST['tahun_pelajaran'])){
  $tahun_pelajaran = anti_injection($_POST['tahun_pelajaran']);
}else{
  $tahun_pelajaran = '';
}


$query_spp  ="SELECT tahun_pelajaran,tahun,nominal FROM spp WHERE tahun_pelajaran='$tahun_pelajaran'";
$result_spp = $connection->query($query_spp);
if($result_spp->num_rows > 0){
  $data_spp = $result_spp->fetch_assoc();
  $tunggakan = $data_spp['nominal'];

  $query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$data_spp[tahun_pelajaran]'";
  $result_tahun = $connection->query($query_tahun);
    if($result_tahun->num_rows > 0){
        $data_tahun = $result_tahun->fetch_assoc();
        $status_tahun_pelajaran = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
    }else{
      $status_tahun_pelajaran = 'Tahun Pelajaran tidak ada';
    }
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
echo'
<table class="table align-items-center table-bordered table-striped datatable">
<thead class="thead-light">
  <tr>
    <th width="4">No</th>
    <th>Nama Siswa</th>
    <th>Tahun Pelajaran</th>
    <th>Tanggal Bayar</th>
    <th>Bulan Bayar</th>
    <th>Tahun Bayar</th>
    <th>Nominal</th>
    <th class="text-center">Aksi</th>
  </tr>
</thead>
<tbody>';
  if($result_pembayaran->num_rows > 0){
    while($data_pembayaran = $result_pembayaran->fetch_assoc()){$no++;
     
      $query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$data_spp[tahun_pelajaran]'";
      $result_tahun = $connection->query($query_tahun);
      if($result_tahun->num_rows > 0){
          $data_tahun = $result_tahun->fetch_assoc();
          $tahun_pelajaran_spp = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
      }else{
          $tahun_pelajaran_spp = 'Tahun Pelajaran tidak ada';
      }

    echo'
      <tr>
        <td class="text-center">'.$no.'</td>
        <td>'.strip_tags($data_pembayaran['employees_name']).'</td>
        <td>'.$tahun_pelajaran_spp.'</td>
        <td>'.tanggal_ind($data_pembayaran['tanggal']).'</td>
        <td>'.ambilbulan($data_pembayaran['bulan']).'</td>
        <td>'.strip_tags($data_pembayaran['tahun']).'</td>
        <td>Rp '.format_angka($data_pembayaran['nominal']).'</td>
        <td class="text-center">
          <a href="javascript:;" class="btn btn-success btn-sm btn-tooltip btn-update" data-id="'.epm_encode($data_pembayaran['pembayaran_spp_id']).'" data-toggle="tooltip" title="Edit">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          </a>
          <a href="javascript:;" class="btn btn-danger btn-sm btn-delete" data-toggle="tooltip" data-id="'.epm_encode($data_pembayaran['pembayaran_spp_id']).'" title="Hapus">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
          </a>
          </td>
      </tr>';
    }
  }else{
    echo'
    <tr>
      <td colspan="8" class="text-center"><h4 class="text-danger">Belum ada Data pembayaran!</h4></td>
    </tr>';
  }

  $query_total = "SELECT SUM(nominal) AS total_pembayaran FROM pembayaran_spp WHERE employees_id='$user' AND tahun_pelajaran='$tahun_pelajaran' AND status='berhasil'";
  $result_total = $connection->query($query_total);
  if($result_total->num_rows > 0){
    $data_total = $result_total->fetch_assoc();
    $total_pembayaran = $data_total['total_pembayaran']??'0';
    $jumlah_tunggakan = $tunggakan - $total_pembayaran;
  }else{
    $total_pembayaran = 0;
    $jumlah_tunggakan = '0';
  }
echo'
</tbody>
    <tfoot>
      <tr>
        <td colspan="7" class="text-right text-success"><b>Tahun Ajaran '.$status_tahun_pelajaran.'</b></td>
        <td class="text-right"><b>Rp '.format_angka($tunggakan).'</b></td>
      </tr>

      <tr>
        <td colspan="7" class="text-right text-info"><b>Jumlah Dibayar</b></td>
        <td class="text-right"><b>Rp '.format_angka($total_pembayaran).'</b></td>
      </tr>

      <tr>
        <td colspan="7" class="text-right text-danger"><b>Tunggakan</b></td>
        <td class="text-right text-danger"><b>Rp '.format_angka($jumlah_tunggakan).'</b></td>
      </tr>
    </tfoot>
</table>';

/* ---------- ADD  ---------- */
break;
case 'add':
  $error = array();
  if (empty($_POST['id'])) {
    $id ='';
  } else {
    $id = anti_injection($_POST['id']);
  }

  if (empty($_POST['user'])) {
    $error[] = 'Siswa tidak boleh kosong';
  } else {
    $user = anti_injection($_POST['user']);
  }

  if (empty($_POST['tahun_pelajaran'])) {
    $error[] = 'Tahun Pelajaran tidak boleh kosong';
  } else {
    $tahun_pelajaran = anti_injection($_POST['tahun_pelajaran']);
  }

  if (empty($_POST['bulan'])) {
      $error[] = 'Bulan Bayar tidak boleh kosong';
    } else {
      $bulan = anti_injection($_POST['bulan']);
  }

  if (empty($_POST['tahun'])) {
    $error[] = 'Tahun tidak boleh kosong';
  } else {
    $tahun = anti_injection($_POST['tahun']);
  }

  if (empty($_POST['nominal'])) {
    $error[] = 'Nominal Bayar tidak boleh kosong';
  } else {
    $nominal = anti_injection($_POST['nominal']);
  }

  if (empty($_POST['metode_pembayaran'])) {
    $error[] = 'Metode Pembayaran tidak boleh kosong';
  } else {
    $metode_pembayaran = anti_injection($_POST['metode_pembayaran']);
  }

  if (empty($error)) {
    $query_user ="SELECT * FROM employees WHERE id='$user'";
    $result_user = $connection->query($query_user);
      if($result_user->num_rows > 0){
        $data_user  = $result_user->fetch_assoc();
        $order_id   = ''.htmlentities($data_user['id']).rand();

        if($id =='') {
            $query_pembayaran ="SELECT employees_id FROM pembayaran_spp WHERE employees_id='$user' AND tahun_pelajaran='$tahun_pelajaran' AND bulan='$bulan' AND tahun='$tahun'";
            $result_pembayaran = $connection->query($query_pembayaran);
            if(!$result_pembayaran->num_rows > 0){

              $query_angsuran = "SELECT COUNT(*) as total FROM pembayaran_spp 
              WHERE employees_id='$user' AND tahun_pelajaran='$tahun_pelajaran'";
              $result = mysqli_query($connection, $query_angsuran);
              $data = mysqli_fetch_assoc($result);
              $angsuran_ke = $data['total'] + 1;
                /* Add*/
                $add ="INSERT INTO pembayaran_spp(admin_id,
                      employees_id,
                      order_id,
                      tahun_pelajaran,
                      bulan,
                      tahun,
                      nominal,
                      angsuran_ke,
                      metode_pembayaran,
                      tanggal,
                      time,
                      status) values('$user_id',
                      '$user',
                      '$order_id',
                      '$tahun_pelajaran',
                      '$bulan',
                      '$tahun',
                      '$nominal',
                      '$angsuran_ke',
                      '$metode_pembayaran',
                      '$date',
                      '$time',
                      'berhasil')";
                if($connection->query($add) === false) { 
                      die($connection->error.__LINE__); 
                      echo'Data tidak berhasil disimpan!';
                  } else{
                    echo'success';
                  }
          }else{
            echo'Data pembayaran bulan '.ambilbulan($bulan).', '.$tahun.' sudah ada!';
          }

        }else{
            /** Update */
            $update="UPDATE pembayaran_spp SET 
                    employees_id='$user',
                    tahun_pelajaran='$tahun_pelajaran',
                    bulan='$bulan',
                    tahun='$tahun',
                    nominal='$nominal',
                    metode_pembayaran='$metode_pembayaran',
                    tanggal='$date',
                    time='$time' WHERE pembayaran_spp_id='$id'"; 
            if($connection->query($update) === false) { 
                die($connection->error.__LINE__); 
                echo'Data tidak berhasil disimpan!';
            } else{
                echo'success';
            }
          }
      }else{
        echo'Data Siswa tidak ditemukan!';
      }
  }else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }


break;
  case 'get-data-update':
  $id             = anti_injection(epm_decode($_POST['id']));
  $query_pembayaran   = "SELECT * FROM pembayaran_spp WHERE pembayaran_spp_id='$id'";
  $result_pembayaran  = $connection->query($query_pembayaran);
  if($result_pembayaran->num_rows > 0){
      $data_pembayaran = $result_pembayaran->fetch_assoc();
      $data['pembayaran_spp_id']  = $data_pembayaran["pembayaran_spp_id"];
      $data['user_id']            = $data_pembayaran["employees_id"];
      $data['tahun_pelajaran']    = $data_pembayaran["tahun_pelajaran"];
      $data['bulan']              = $data_pembayaran["bulan"];
      $data['tahun']              = $data_pembayaran["tahun"];
      $data['nominal']            = $data_pembayaran["nominal"];
    echo json_encode($data);
  }else{
      echo'Data tidak ditemukan!';
  }



/** Status Transaksi */
break;
case 'status-pembayaran':
$id = !empty($_POST['id']) ? htmlentities($_POST['id']) : null;
$status = !empty($_POST['status']) ? htmlentities($_POST['status']) : null;
$update="UPDATE pembayaran_spp SET status='$status' WHERE pembayaran_spp_id='$id'";
if($connection->query($update) === false) { 
  echo 'error';
  die($connection->error.__LINE__); 
}else{
  echo'success';
}
    
/* --------------- Delete ------------*/
break;
case 'delete':
$id       = anti_injection(epm_decode($_POST['id']));
$deleted  = "DELETE FROM pembayaran_spp WHERE pembayaran_spp_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else { 
  //tidak berhasil
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}


/** Cek Data SPP */
break;
case 'cek-data-spp':
if(isset($_POST['user'])){
  $user = anti_injection($_POST['user']);
}else{
  $user = '';
}

if(isset($_POST['tahun_pelajaran'])){
  $tahun_pelajaran = anti_injection($_POST['tahun_pelajaran']);
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


$query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$tahun_pelajaran'";
  $result_tahun = $connection->query($query_tahun);
  if($result_tahun->num_rows > 0){
      $data_tahun = $result_tahun->fetch_assoc();
      $tahun = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
  }else{
      $tahun = 'Tahun Pelajaran tidak ada';
  }
echo'
<table class="table align-items-center table-bordered table-striped datatable">
<thead class="thead-light">
  <tr>
    <th width="4">No</th>
    <th>Nama Siswa</th>
    <th>Tahun Pelajaran</th>
    <th>Tanggal Bayar</th>
    <th>Bulan Bayar</th>
    <th>Tahun Bayar</th>
    <th>Nominal</th>
  </tr>
</thead>
<tbody>';
  if($result_pembayaran->num_rows > 0){
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
  }else{
    echo'
    <tr>
      <td colspan="7" class="text-center"><h4 class="text-danger">Belum ada Data pembayaran!</h4></td>
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
<hr>
<button class="btn btn-info btn-print-cekspp"><i class="fa fa-print" aria-hidden="true"></i> Print</button>';


break;
}}