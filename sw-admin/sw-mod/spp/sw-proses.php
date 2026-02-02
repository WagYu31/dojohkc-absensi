<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
require_once'../../../sw-library/sw-function.php';

switch (@$_GET['action']){
case 'add':
    $error = array();
    $id = anti_injection($_POST['id']);

    if (empty($_POST['tahun'])) {
      $error[] = 'Tahun tidak boleh kosong';
    } else {
      $tahun = strip_tags($_POST['tahun']);
    }

    if (empty($_POST['nominal'])) {
      $error[] = 'Nominal tidak boleh kosong';
    } else {
      $nominal = strip_tags($_POST['nominal']);
    }

    if (empty($_POST['status'])) {
      $status = 'N';
    } else {
      $status = strip_tags($_POST['status']);
    }


  if (empty($error)) {

  $query="SELECT spp_id FROM spp where spp_id='$id'";
  $result= $connection->query($query) or die($connection->error.__LINE__);
  if(!$result ->num_rows >0){
      
        /* ---- Tambah data ------*/
          $query_tahun="SELECT tahun FROM spp WHERE tahun_pelajaran='$tahun'";
          $result_tahun = $connection->query($query_tahun);
          if(!$result_tahun->num_rows > 0){

              $add ="INSERT INTO spp (tahun_pelajaran,
                      tahun,
                      nominal,
                      status) values('$tahun',
                      '$year',
                      '$nominal',
                      '$status')";
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
        $update="UPDATE spp SET tahun_pelajaran='$tahun',
                tahun='$year',
                nominal ='$nominal',
                status='$status' WHERE spp_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
        }
    }
  }
  else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }


/** --------- Set Active Lokasi ------- */
break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE spp SET status='$active' WHERE spp_id='$id'";
  if($connection->query($update) === false) { 
    echo 'error';
    die($connection->error.__LINE__); 
  }
  else{
    echo'success';
  }
  


/* --------------- Delete ------------*/
    break;
    case 'delete':
        $id       = anti_injection(epm_decode($_POST['id']));
      /* Script Delete Data ------------*/
        $deleted = "DELETE FROM spp WHERE spp_id='$id'";
        if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
        }

       
   

break;
}}