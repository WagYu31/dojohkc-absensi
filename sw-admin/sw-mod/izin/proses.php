<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php');

switch (@$_GET['action']){
  case  'setujui':

  if (empty($_POST['id'])) {
    $error[] = 'ID tidak boleh kosong';
  } else {
    $id = anti_injection($_POST['id']);
  }

  if (empty($error)) { 
    $query_izin  ="SELECT * FROM permission WHERE permission_id='$id'";
    $result_izin = $connection->query($query_izin);
    if($result_izin->num_rows > 0){
       $row = $result_izin->fetch_assoc();

       $start = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date'])));
       $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date_finish'])));
          
        if($row['status']!=='1'){
              $update="UPDATE permission SET status='1' WHERE permission_id='$id'"; 
                if($connection->query($update) === false) { 
                    die($connection->error.__LINE__); 
                    echo'Data tidak berhasil disimpan!';
                } else{
                    echo'success';
                    /** Tambah Data Table Absensi */
                      while ($start <= $finish) {
                        $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
                        $add_absent ="INSERT INTO presence (employees_id,
                              presence_date,
                              shift_id,
                              jam_masuk,
                              jam_pulang,
                              time_in,
                              time_out,
                              picture_in,
                              picture_out,
                              kehadiran,
                              latitude_longtitude_in,
                              latitude_longtitude_out,
                              status_in,
                              status_out,
                              information) values('$row[employees_id]',
                              '$start',
                              '0',
                              '00:00:00',
                              '00:00:00',
                              '00:00:00',
                              '00:00:00',
                              '', /*picture in*/
                              '', /*picture out*/
                              '$row[type]',
                              '',
                              '',
                              '$row[type]',
                              '$row[type]',
                              '$row[type]')";
                          $connection->query($add_absent) or die($connection->error.__LINE__); 
                      }
                }
        }else{
          echo'Permohonan Data Izin sebelumnya sudah disetujui'; 
        }
      }else{
        echo'Data Izin tidak ditemukan';
      }
    }else{
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }

    
/* ------------------------------
    Update status
---------------------------------*/
break;
case 'update-status':
 $error = array();
   if (empty($_POST['id'])) {
      $error[] = 'ID tidak boleh kosong';
    } else {
      $id = anti_injection($_POST['id']);
  }

  if (empty($_POST['status'])) {
      $error[] = 'Status tidak boleh kosong';
    } else {
      $status = anti_injection($_POST['status']);
  }
  if (empty($error)) { 

    $query_izin  ="SELECT * FROM permission WHERE permission_id='$id'";
    $result_izin = $connection->query($query_izin);
    
    if($result_izin->num_rows > 0){
       $row = $result_izin->fetch_assoc();
       
       $start = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date'])));
       $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date_finish'])));

      /** Update */
      $update="UPDATE permission SET status='$status' WHERE permission_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
            if($status =='2'){
              while ($start <= $finish) {
                $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
                $deleted_absent  = "DELETE FROM presence WHERE employees_id='$row[employees_id]' AND presence_date='$start'";
                $connection->query($deleted_absent);
              }
             }
        }
      /** Update */
    }else{
      echo'Data Permohonan Izin tidak ditemukan';
    }

  }
    else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }

    break;
case 'delete':
  $id           = mysqli_real_escape_string($connection,epm_decode($_POST['id']));
  $employees_id = mysqli_real_escape_string($connection,epm_decode($_POST['employees_id']));
  $query_delete  ="SELECT files,permission_date,permission_date_finish from permission WHERE employees_id='$employees_id' AND permission_id='$id'";
  $result_delete = $connection->query($query_delete);
  if($result_delete->num_rows > 0){
     $row = $result_delete->fetch_assoc();

    $start = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date'])));
    $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['permission_date_finish'])));
    
    $images_delete = strip_tags($row['files']);
      $directory='../../../sw-content/izin/'.$images_delete.'';
      if(file_exists("../../../sw-content/izin/$images_delete")){
          unlink ($directory);
      }

    while ($start <= $finish) {
          $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
          $deleted_absent  = "DELETE FROM presence WHERE employees_id='$employees_id' AND presence_date='$start'";
          $connection->query($deleted_absent);
    }
    $deleted  = "DELETE FROM permission WHERE  permission_id='$id'";
    if($connection->query($deleted) === true) {
      echo'success';
    } else { 
      //tidak berhasil
      echo'Data tidak berhasil dihapus.!';
      die($connection->error.__LINE__);
    }
  }
  
break;

}

}
