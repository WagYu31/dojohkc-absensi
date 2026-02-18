<?php session_start();

if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {

require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';

$validImageMimes = ["image/jpeg","image/png","image/gif","image/webp"];
$allowedImageExts = ["jpg","jpeg","png","gif","webp"];
$validVideoMimes = ["video/mp4","video/webm"];
$allowedVideoExts = ["mp4","webm"];
$maxImageSize = 5 * 1024 * 1024;   // 5MB
$maxVideoSize = 50 * 1024 * 1024;  // 50MB

$uploadDir = '../../../sw-content/galeri/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

switch (@$_GET['action']){
case 'add':
$error = array();
  if (empty($_POST['judul'])) {
    $error[] = 'Judul tidak boleh kosong';
  } else {
    $judul = anti_injection($_POST['judul']);
  }

  $tipe = (!empty($_POST['tipe']) && $_POST['tipe'] == 'video') ? 'video' : 'foto';
  $active = (!empty($_POST['active']) && $_POST['active'] == 'N') ? 'N' : 'Y';

  $fileField = ($tipe == 'video') ? 'video' : 'foto';

  if (empty($_FILES[$fileField]['name'])) {
    $error[] = ucfirst($tipe).' belum diunggah';
  } else {
    $fileTmpPath = $_FILES[$fileField]['tmp_name'];
    $fileSize = $_FILES[$fileField]['size'];
    $fileName = basename($_FILES[$fileField]['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmpPath);
    finfo_close($finfo);

    $newFileName = uniqid("galeri_", true) . "." . $fileExt;
    $destPath = $uploadDir . $newFileName;
  }

  if (empty($error)){
    if($tipe == 'foto'){
      if (!in_array($fileExt, $allowedImageExts)) { die("Hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!"); }
      if ($fileSize > $maxImageSize) { die("Ukuran foto terlalu besar, maksimal 5MB!"); }
      if (!in_array($mimeType, $validImageMimes)) { die("Tipe MIME foto tidak valid."); }
    } else {
      if (!in_array($fileExt, $allowedVideoExts)) { die("Hanya file MP4 dan WEBM yang diperbolehkan!"); }
      if ($fileSize > $maxVideoSize) { die("Ukuran video terlalu besar, maksimal 50MB!"); }
      if (!in_array($mimeType, $validVideoMimes)) { die("Tipe MIME video tidak valid."); }
    }

    if(move_uploaded_file($fileTmpPath, $destPath)){
      $add = "INSERT INTO galeri(judul, file, tipe, active, created_at)
              VALUES('$judul', '$newFileName', '$tipe', '$active', NOW())";
      if($connection->query($add) === false) {
        die($connection->error.__LINE__);
      } else {
        echo'success';
      }
    } else {
      die("Gagal mengupload file.");
    }
  }else{
    foreach ($error as $key => $values) {
      echo"$values\n";
    }
  }

break;
case 'update':
$error = array();
    if (empty($_POST['id'])) {
      $error[] = 'ID tidak ditemukan';
    } else {
      $id = anti_injection(epm_decode($_POST['id']));
    }

    if (empty($_POST['judul'])) {
      $error[] = 'Judul tidak boleh kosong';
    } else {
      $judul = anti_injection($_POST['judul']);
    }

    $tipe = (!empty($_POST['tipe']) && $_POST['tipe'] == 'video') ? 'video' : 'foto';
    $active = (!empty($_POST['active']) && $_POST['active'] == 'N') ? 'N' : 'Y';

    $fileField = ($tipe == 'video') ? 'video' : 'foto';

    if (empty($_FILES[$fileField]['name'])) {
      $newFileName = null;
    } else {
      $fileTmpPath = $_FILES[$fileField]['tmp_name'];
      $fileSize = $_FILES[$fileField]['size'];
      $fileName = basename($_FILES[$fileField]['name']);
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeType = finfo_file($finfo, $fileTmpPath);
      finfo_close($finfo);

      $newFileName = uniqid("galeri_", true) . "." . $fileExt;
      $destPath = $uploadDir . $newFileName;
    }

  if (empty($error)) {
      if (!empty($_FILES[$fileField]['name'])) {
        if($tipe == 'foto'){
          if (!in_array($fileExt, $allowedImageExts)) { die("Hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!"); }
          if ($fileSize > $maxImageSize) { die("Ukuran foto terlalu besar, maksimal 5MB!"); }
          if (!in_array($mimeType, $validImageMimes)) { die("Tipe MIME foto tidak valid."); }
        } else {
          if (!in_array($fileExt, $allowedVideoExts)) { die("Hanya file MP4 dan WEBM yang diperbolehkan!"); }
          if ($fileSize > $maxVideoSize) { die("Ukuran video terlalu besar, maksimal 50MB!"); }
          if (!in_array($mimeType, $validVideoMimes)) { die("Tipe MIME video tidak valid."); }
        }
      }

      $update = "UPDATE galeri SET judul='$judul', tipe='$tipe', active='$active'";
      if (!empty($_FILES[$fileField]['name'])) {
        $update .= ", file='$newFileName'";
      }
      $update .= " WHERE galeri_id='$id'";

      if($connection->query($update) === false) {
        die($connection->error.__LINE__);
      } else {
        echo'success';
        if (!empty($_FILES[$fileField]['name'])) {
          move_uploaded_file($fileTmpPath, $destPath);
        }
      }
  }else{
    foreach ($error as $key => $values) {
      echo"$values\n";
    }
  }

break;
case 'delete':
$id = anti_injection(epm_decode($_POST['id']));
$deleted = "DELETE FROM galeri WHERE galeri_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else {
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}

break;
}}
