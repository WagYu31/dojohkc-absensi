<?php session_start();

if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {

require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/login_session.php';

$validMimeTypes = [
  "image/jpeg",
  "image/png",
  "image/gif",
  "image/webp"
];

$allowedTypes = ["jpg", "jpeg", "png", "gif", "webp"];
$maxFileSize = 5 * 1024 * 1024; // 5MB

$uploadDir = '../../../sw-content/poster/';
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

  $active = (!empty($_POST['active']) && $_POST['active'] == 'N') ? 'N' : 'Y';

  if (empty($_FILES['foto']['name'])) {
    $error[] = 'Poster belum diunggah';
  } else {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileName = basename($_FILES['foto']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmpPath);
    finfo_close($finfo);

    $newFileName = uniqid("poster_", true) . "." . $fileExt;
    $destPath = $uploadDir . $newFileName;
  }

  if (empty($error)){
    if (!in_array($fileExt, $allowedTypes)) {
        die("Hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!");
    }
    if ($fileSize > $maxFileSize) {
        die("Ukuran file terlalu besar, Maksimal Size 5MB!");
    }
    if (!in_array($mimeType, $validMimeTypes)) {
      die("Tipe MIME file tidak valid.");
    }

    if(move_uploaded_file($fileTmpPath, $destPath)){
      $add = "INSERT INTO poster(judul, file, active, created_at)
              VALUES('$judul', '$newFileName', '$active', NOW())";
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

    $active = (!empty($_POST['active']) && $_POST['active'] == 'N') ? 'N' : 'Y';

    if (empty($_FILES['foto']['name'])) {
      $newFileName = null;
    } else {
      $fileTmpPath = $_FILES['foto']['tmp_name'];
      $fileSize = $_FILES['foto']['size'];
      $fileName = basename($_FILES['foto']['name']);
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeType = finfo_file($finfo, $fileTmpPath);
      finfo_close($finfo);

      $newFileName = uniqid("poster_", true) . "." . $fileExt;
      $destPath = $uploadDir . $newFileName;
    }

  if (empty($error)) {
      if (!empty($_FILES['foto']['name'])) {
          if (!in_array($fileExt, $allowedTypes)) {
            die("Hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!");
          }
          if ($fileSize > $maxFileSize) {
            die("Ukuran file terlalu besar, maksimal 5MB!");
          }
          if (!in_array($mimeType, $validMimeTypes)) {
            die("Tipe MIME file tidak valid.");
          }
      }

      $update = "UPDATE poster SET judul='$judul', active='$active'";
      if (!empty($_FILES['foto']['name'])) {
        $update .= ", file='$newFileName'";
      }
      $update .= " WHERE poster_id='$id'";

      if($connection->query($update) === false) {
        die($connection->error.__LINE__);
      } else {
        echo'success';
        if (!empty($_FILES['foto']['name'])) {
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
$deleted = "DELETE FROM poster WHERE poster_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else {
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}

break;
}}
