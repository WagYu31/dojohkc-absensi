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
    $cuty_id = anti_injection($_POST['id']);
  }

  if (empty($error)) { 
    $query_cuty  ="SELECT cuty.*,employees.employees_name from cuty
    INNER JOIN employees ON cuty.employees_id = employees.id WHERE cuty.cuty_id='$cuty_id'";
    $result_cuty = $connection->query($query_cuty);
    if($result_cuty->num_rows > 0){
       $row = $result_cuty->fetch_assoc();
       $start = date('Y-m-d',strtotime('-1 days',strtotime($row['cuty_start'])));
       $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['cuty_end'])));
          
        if($row['cuty_status']!=='1'){
              $update="UPDATE cuty SET cuty_status='1' WHERE cuty_id='$cuty_id'"; 
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
                              'Cuti',
                              '',
                              '',
                              'Cuti',
                              'Cuti',
                              'Cuti')";
                          $connection->query($add_absent) or die($connection->error.__LINE__);
                      }
                }
        }else{
          echo'Permohonan Data Cuti sebelumnya sudah disetujui'; 
        }
      }else{
        echo'Data Cuti tidak ditemukan';
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
      $cuty_id = anti_injection($_POST['id']);
  }

  if (empty($_POST['status'])) {
      $error[] = 'Status tidak boleh kosong';
    } else {
      $status = anti_injection($_POST['status']);
  }
  if (empty($error)) { 

    $query_cuty  ="SELECT cuty.*,employees.employees_name from cuty
    INNER JOIN employees ON cuty.employees_id = employees.id WHERE cuty.cuty_id='$cuty_id'";
    $result_cuty = $connection->query($query_cuty);
    if($result_cuty->num_rows > 0){
       $row = $result_cuty->fetch_assoc();
       $start = date('Y-m-d',strtotime('-1 days',strtotime($row['cuty_start'])));
       $finish = date('Y-m-d',strtotime('-1 days',strtotime($row['cuty_end'])));

      /** Update */
        $update="UPDATE cuty SET cuty_status='$status' WHERE cuty_id='$cuty_id'"; 
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
      echo'Data Permohonan Cuti tidak ditemukan';
    }

  }
    else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }



break;
}

}
