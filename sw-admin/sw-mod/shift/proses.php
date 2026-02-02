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
case 'update':
$item = $_POST['item'];
  for($i = 0; $i < sizeof($item); $i++){
    $id           = strip_tags($_POST["id"][$i]);
    $jam_masuk    = strip_tags($_POST["jam_masuk"][$i]);
    $jam_pulang   = strip_tags($_POST["jam_pulang"][$i]);
    $active       = strip_tags($_POST["active"][$i]);

    $update_jam_kerja ="UPDATE shift SET time_in='$jam_masuk',
                        time_out='$jam_pulang',
                        active='$active' WHERE shift_id='$id'";
    $connection->query($update_jam_kerja); 
  } 
echo'success';

break;

}

}
