<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';

switch (@$_GET['action']){
case 'add':
  $error = array();
  if (empty($_POST['judul'])) {
    $error[] = 'Judul tidak boleh kosong';
  } else {
    $judul = anti_injection($_POST['judul']);
  }

  if (empty($_POST['tanggal'])) {
      $error[] = 'Tanggal tidak boleh kosong';
    } else {
      $tanggal = date('Y-m-d', strtotime(anti_injection($_POST['tanggal'])));
  }

  if (empty($_POST['jam'])) {
    $error[] = 'Jam tidak boleh kosong';
  } else {
    $jam = anti_injection($_POST['jam']);
  }

  if (empty($_POST['keterangan'])) {
    $error[] = 'Metode Pembayaran tidak boleh kosong';
  } else {
    $keterangan = anti_injection($_POST['keterangan']);
  }

  if (empty($error)) {
    $add ="INSERT INTO pengumuman(admin_id,
          judul,
          tanggal,
          jam,
          keterangan,
          date,
          time) values('$user_id',
          '$judul',
          '$tanggal',
          '$jam',
          '$keterangan',
          '$date',
          '$time')";
    if($connection->query($add) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
      echo'success';
    }   
}else{           
  foreach ($error as $key => $values) {            
    echo"$values\n";
  }
}


/** Update */
break;
case'update':
$error = array();

  if (empty($_POST['id'])) {
    $error[] = 'ID tidak boleh kosong';
  } else {
    $id = anti_injection($_POST['id']);
  }

  if (empty($_POST['judul'])) {
    $error[] = 'Judul tidak boleh kosong';
  } else {
    $judul = anti_injection($_POST['judul']);
  }

  if (empty($_POST['tanggal'])) {
      $error[] = 'Tanggal tidak boleh kosong';
    } else {
      $tanggal = date('Y-m-d', strtotime(anti_injection($_POST['tanggal'])));
  }

  if (empty($_POST['jam'])) {
    $error[] = 'Jam tidak boleh kosong';
  } else {
    $jam = anti_injection($_POST['jam']);
  }

  if (empty($_POST['keterangan'])) {
    $error[] = 'Metode Pembayaran tidak boleh kosong';
  } else {
    $keterangan = anti_injection($_POST['keterangan']);
  }

  if (empty($error)) {
    $update = "UPDATE pengumuman SET
        judul = '$judul',
        tanggal = '$tanggal',
        jam = '$jam',
        keterangan = '$keterangan',
        date = '$date',
        time = '$time' WHERE pengumuman_id = '$id'";
    if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
      echo'success';
    }   
}else{           
  foreach ($error as $key => $values) {            
    echo"$values\n";
  }
}
    
/* --------------- Delete ------------*/
break;
case 'delete':
$id       = anti_injection(epm_decode($_POST['id']));
$deleted  = "DELETE FROM pengumuman WHERE pengumuman_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else { 
  //tidak berhasil
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}




break;
}}