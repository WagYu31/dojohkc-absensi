<?php session_start();

// <?php
// ini_set('log_errors', '1');
// ini_set('error_log', __DIR__ . '/debug-error.log');
// ini_set('display_errors', '0'); // biar aman, kita log saja
// error_reporting(E_ALL);

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e) {
        file_put_contents(__DIR__ . '/debug-fatal.log', print_r($e, true), FILE_APPEND);
    }
});

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
  "image/WEBP"
];

$allowedTypes = ["jpg", "jpeg", "png", "gif", "WEBP"];
$maxFileSize = 5 * 1024 * 1024; // 5MB

$uploadDir = '../../../sw-content/artikel/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function resizeImage($resourceType, int $image_width, int $image_height): GdImage {
  $resizeWidth = 600;
  $resizeHeight = (int)(($image_height / $image_width) * $resizeWidth);
  $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
  if ($imageLayer === false) {
      throw new RuntimeException("Failed to create a true color image.");
  }
  if (!imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height)) {
      throw new RuntimeException("Failed to resample the image.");
  }
  return $imageLayer;
}


switch (@$_GET['action']){
case 'add':
$error = array();
  if (empty($_POST['judul'])) {
    $error[] = 'Judul tidak boleh kosong';
  } else {
    $judul = anti_injection($_POST['judul']);
    $domain = seo_title($judul);
  }

  if (empty($_POST['deskripsi'])) {
      $error[] = 'Deskripsi tidak boleh kosong';
    } else {
      $deskripsi = mysqli_real_escape_string($connection,$_POST['deskripsi']);
  }


  if (empty($_POST['date'])) {
      $error[] = 'Tanggal tidak boleh kosong';
    } else {
      $date = date('Y-m-d', strtotime($_POST['date']));
  }

  if (empty($_POST['time'])) {
    $error[] = 'Waktu tidak boleh kosong';
  } else {
    $time = strip_tags($_POST['time']);
  }

  if (empty($_FILES['foto']['name'])) {
    $error[] = 'Thumbnail belum diunggah';
  } else {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
    $fileName = basename($_FILES['foto']['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $sourceProperties = getimagesize($fileTmpPath);
    $uploadImageType  = $sourceProperties[2];
    $sourceImageWidth = $sourceProperties[0];
    $sourceImageHeight = $sourceProperties[1];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmpPath);
    finfo_close($finfo);

    // Beri nama unik dan pindahkan file
    $newFileName = uniqid("file_", true) . "." . $fileExt;
    $destPath = $uploadDir . $newFileName;
  }

  if (empty($error)){
    // Validasi ekstensi file
    if (!in_array($fileExt, $allowedTypes)) {
        die("Hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!");
    }

    // Validasi ukuran file
    if ($fileSize > $maxFileSize) {
        die("Ukuran file terlalu besar, Maksimal Size 5MB!");
    }

    if (!in_array($mimeType, $validMimeTypes)) {
      die("Tipe MIME file tidak valid.");
    }

    $add ="INSERT INTO artikel(penerbit,
          judul,
          domain,
          deskripsi,
          foto,
          time,
          date,
          statistik,
          active) values('$row_user[fullname]',
          '$judul',
          '$domain',
          '$deskripsi',
          '$newFileName',
          '$time',
          '$date',
          '0',
          'Y')";
    if($connection->query($add) === false) { 
          die($connection->error.__LINE__); 
          echo'Data tidak berhasil disimpan!';
      } else{
          echo'success';
          $imageProcess = processImage($uploadImageType, $fileTmpPath, $sourceImageWidth, $sourceImageHeight, $destPath);
      }
  }else{           
    foreach ($error as $key => $values) {            
      echo"$values\n";
    }
  }


/* -------------- Update ----------*/
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
      $domain = seo_title($judul);
    }

    if (empty($_POST['deskripsi'])) {
        $error[] = 'Deskripsi tidak boleh kosong';
      } else {
        $deskripsi = mysqli_real_escape_string($connection,$_POST['deskripsi']);
    }

    if (empty($_POST['date'])) {
        $error[] = 'Tanggal tidak boleh kosong';
      } else {
        $date = date('Y-m-d', strtotime($_POST['date']));
    }

    if (empty($_POST['time'])) {
      $error[] = 'Waktu tidak boleh kosong';
    } else {
      $time = strip_tags($_POST['time']);
    }


    if (empty($_FILES['foto']['name'])) {
      $newFileName = null;
    } else {
      $fileTmpPath = $_FILES['foto']['tmp_name'];
      $fileSize = $_FILES['foto']['size'];
      $fileName = basename($_FILES['foto']['name']);
      $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      $sourceProperties = getimagesize($fileTmpPath);
      $uploadImageType  = $sourceProperties[2];
      $sourceImageWidth = $sourceProperties[0];
      $sourceImageHeight = $sourceProperties[1];

      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mimeType = finfo_file($finfo, $fileTmpPath);
      finfo_close($finfo);

      // Beri nama unik dan pindahkan file
      $newFileName = uniqid("file_", true) . "." . $fileExt;
      $destPath = $uploadDir . $newFileName;
    }

  if (empty($error)) {
      if (!empty($_FILES['foto']['name'])) {
          if (!in_array($fileExt, $allowedTypes)) {
            die("Foto  hanya file JPG, JPEG, PNG, GIF, dan WEBP yang diperbolehkan!");
          }

          // Validasi ukuran file
          if ($fileSize > $maxFileSize) {
            die("Foto  ukuran file terlalu besar, maksimal ukuran 5MB!");
          }

          if (!in_array(mime_content_type($fileTmpPath), $validMimeTypes)) {
            die("Foto  tipe MIME file tidak valid.");
          }
      }

      $update="UPDATE artikel SET judul='$judul',
            domain='$domain',
            deskripsi='$deskripsi',
            time='$time',
            date='$date'";
      if (!empty($_FILES['foto']['name'])) {
        $update .= ", foto='$newFileName'";
      }
        
    //   $update .= "WHERE artikel_id='$id'"; 
    $update .= " WHERE artikel_id='$id'";
    if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
        echo'success';
        if (!empty($_FILES['foto']['name'])) {
          $imageProcess = processImage($uploadImageType, $fileTmpPath, $sourceImageWidth, $sourceImageHeight, $destPath);
        }
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
$deleted  = "DELETE FROM artikel WHERE artikel_id='$id'";
if($connection->query($deleted) === true) {
  echo'success';
} else { 
  //tidak berhasil
  echo'Data tidak berhasil dihapus.!';
  die($connection->error.__LINE__);
}




break;
}}