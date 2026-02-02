<?php
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';

switch (@$_GET['action']){
case 'add':
    $error = array();
    $id = anti_injection($_POST['id']);

    if (empty($_POST['tahun_mulai'])) {
      $error[] = 'Tahun Mulai tidak boleh kosong';
    } else {
      $tahun_mulai = strip_tags($_POST['tahun_mulai']);
    }

    if (empty($_POST['tahun_selesai'])) {
      $error[] = 'Tahun Selesai tidak boleh kosong';
    } else {
      $tahun_selesai = strip_tags($_POST['tahun_selesai']);
    }


  if (empty($error)) {

  $query="SELECT tahun_pelajaran_id FROM tahun_pelajaran where tahun_pelajaran_id='$id'";
  $result= $connection->query($query);
  if(!$result ->num_rows >0){
      /* ---- Tambah data ------*/
      $query_tahun="SELECT tahun_pelajaran_id from tahun_pelajaran WHERE tahun_mulai='$tahun_mulai' AND tahun_selesai='$tahun_selesai'";
      $result_tahun = $connection->query($query_tahun);
      if(!$result_tahun->num_rows > 0){
        $add ="INSERT INTO tahun_pelajaran (tahun_mulai, tahun_selesai) values('$tahun_mulai', '$tahun_selesai')";
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
          }
      } else{
        echo 'Data yang Anda masukkan sudah ada!';
      }
    }else{
      /* --  Update data -- */
      $update="UPDATE tahun_pelajaran SET tahun_mulai='$tahun_mulai',
                tahun_selesai='$tahun_selesai' WHERE tahun_pelajaran_id='$id'"; 
      if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
      } else{
          echo'success';
      }
  }

}else{           
  foreach ($error as $key => $values) {            
    echo"$values\n";
  }
}

break;
case 'get-data-update':
  $id      = anti_injection(epm_decode($_POST['id']));
  $query   = "SELECT * FROM tahun_pelajaran WHERE tahun_pelajaran_id='$id'";
  $result  = $connection->query($query);
  if($result->num_rows > 0){
    $data = $result->fetch_assoc();
    $data['tahun_pelajaran_id'] = $data["tahun_pelajaran_id"];
    $data['tahun_mulai']        = $data["tahun_mulai"];
    $data['tahun_selesai']      = $data["tahun_selesai"];
    echo json_encode($data);
}else{
  echo'Data tidak ditemukan!';
}
  
/* --------------- Delete ------------*/
break;
case 'delete':
$id       = anti_injection(epm_decode($_POST['id']));
$deleted = "DELETE FROM tahun_pelajaran WHERE tahun_pelajaran_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else { 
  //tidak berhasil
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}
break;
}}