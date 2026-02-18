<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
    exit;
} else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php');

// Helper: save a setting
function saveSetting($conn, $key, $value){
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);
    $conn->query("INSERT INTO landing_settings (setting_key, setting_value) VALUES ('$key','$value') 
                  ON DUPLICATE KEY UPDATE setting_value='$value'");
}

// Helper: handle image upload
function handleImageUpload($conn, $fieldName, $settingKey, $subdir = 'landing'){
    if(!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK || $_FILES[$fieldName]['name'] == ''){
        return true; // No file uploaded, skip
    }
    
    $file = $_FILES[$fieldName];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];
    
    // Validate size
    if($file['size'] > $maxSize){
        echo 'Ukuran file terlalu besar! Maksimal 2MB.';
        return false;
    }
    
    // Validate type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if(!in_array($mime, $allowedMime)){
        echo 'Format file tidak diizinkan! Harus JPG, PNG, WEBP, atau GIF.';
        return false;
    }
    
    // Generate filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $newName = $settingKey . '_' . time() . '.' . $ext;
    $uploadDir = '../../../sw-content/' . $subdir . '/';
    
    // Create dir if not exists
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0755, true);
    }
    
    // Delete old file
    $oldKey = mysqli_real_escape_string($conn, $settingKey);
    $q = $conn->query("SELECT setting_value FROM landing_settings WHERE setting_key='$oldKey'");
    if($q && $q->num_rows > 0){
        $old = $q->fetch_assoc();
        if(!empty($old['setting_value']) && file_exists($uploadDir . $old['setting_value'])){
            unlink($uploadDir . $old['setting_value']);
        }
    }
    
    // Move file
    if(move_uploaded_file($file['tmp_name'], $uploadDir . $newName)){
        saveSetting($conn, $settingKey, $newName);
        return true;
    } else {
        echo 'Gagal mengupload file.';
        return false;
    }
}

$extensionList = array("jpg", "png", "webp", "gif");

switch (@$_GET['action']){

// =====================
// SAVE HERO
// =====================
case 'hero':
if($level_user == 1){
    
    // Handle image first
    if(!handleImageUpload($connection, 'hero_image', 'hero_image')){
        break; // Error already printed
    }
    
    // Save text settings
    saveSetting($connection, 'hero_badge', $_POST['hero_badge'] ?? '');
    saveSetting($connection, 'hero_title', $_POST['hero_title'] ?? '');
    saveSetting($connection, 'hero_subtitle', $_POST['hero_subtitle'] ?? '');
    saveSetting($connection, 'hero_btn_primary', $_POST['hero_btn_primary'] ?? '');
    saveSetting($connection, 'hero_btn_secondary', $_POST['hero_btn_secondary'] ?? '');
    
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// SAVE ABOUT
// =====================
case 'about':
if($level_user == 1){
    
    if(!handleImageUpload($connection, 'about_image', 'about_image')){
        break;
    }
    
    saveSetting($connection, 'about_label', $_POST['about_label'] ?? '');
    saveSetting($connection, 'about_title', $_POST['about_title'] ?? '');
    saveSetting($connection, 'about_desc1', $_POST['about_desc1'] ?? '');
    saveSetting($connection, 'about_desc2', $_POST['about_desc2'] ?? '');
    saveSetting($connection, 'about_stat_label', $_POST['about_stat_label'] ?? '');
    
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// SAVE FEATURES
// =====================
case 'features':
if($level_user == 1){
    
    saveSetting($connection, 'feature_label', $_POST['feature_label'] ?? '');
    saveSetting($connection, 'feature_title', $_POST['feature_title'] ?? '');
    saveSetting($connection, 'feature_desc', $_POST['feature_desc'] ?? '');
    
    // Handle features JSON
    $featuresJson = $_POST['features_json'] ?? '[]';
    // Validate it's valid JSON
    $decoded = json_decode($featuresJson, true);
    if(!is_array($decoded)){
        $featuresJson = '[]';
    }
    saveSetting($connection, 'features_json', $featuresJson);
    
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// SAVE CTA & FOOTER
// =====================
case 'cta':
if($level_user == 1){
    
    saveSetting($connection, 'cta_label', $_POST['cta_label'] ?? '');
    saveSetting($connection, 'cta_title', $_POST['cta_title'] ?? '');
    saveSetting($connection, 'cta_desc', $_POST['cta_desc'] ?? '');
    saveSetting($connection, 'footer_text', $_POST['footer_text'] ?? '');
    
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// ADD POSTER
// =====================
case 'add-poster':
if($level_user == 1){
    if(empty($_POST['judul'])){
        echo 'Judul tidak boleh kosong!';
        break;
    }
    if(!isset($_FILES['file_poster']) || $_FILES['file_poster']['error'] !== UPLOAD_ERR_OK){
        echo 'File poster harus diupload!';
        break;
    }
    
    $file = $_FILES['file_poster'];
    $maxSize = 5 * 1024 * 1024;
    $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];
    
    if($file['size'] > $maxSize){
        echo 'Ukuran file terlalu besar! Maksimal 5MB.';
        break;
    }
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if(!in_array($mime, $allowedMime)){
        echo 'Format file tidak diizinkan! Harus JPG, PNG, WEBP, atau GIF.';
        break;
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $newName = 'poster_' . time() . '_' . rand(100,999) . '.' . $ext;
    $uploadDir = '../../../sw-content/poster/';
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
    if(move_uploaded_file($file['tmp_name'], $uploadDir . $newName)){
        $judul = mysqli_real_escape_string($connection, $_POST['judul']);
        $connection->query("INSERT INTO poster (judul, file, active, created_at) VALUES ('$judul', '$newName', 'Y', NOW())");
        echo 'success';
    } else {
        echo 'Gagal mengupload file.';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// EDIT POSTER
// =====================
case 'edit-poster':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    if(empty($_POST['judul'])){
        echo 'Judul tidak boleh kosong!';
        break;
    }
    $judul = mysqli_real_escape_string($connection, $_POST['judul']);

    // Check if a new file was uploaded
    if(isset($_FILES['file_poster']) && $_FILES['file_poster']['error'] === UPLOAD_ERR_OK){
        $file = $_FILES['file_poster'];
        $maxSize = 5 * 1024 * 1024;
        $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];

        if($file['size'] > $maxSize){
            echo 'Ukuran file terlalu besar! Maksimal 5MB.';
            break;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if(!in_array($mime, $allowedMime)){
            echo 'Format file tidak diizinkan! Gunakan JPG/PNG/WEBP/GIF.';
            break;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = 'poster_' . time() . '_' . rand(100,999) . '.' . $ext;
        $uploadDir = '../../../sw-content/poster/';

        if(move_uploaded_file($file['tmp_name'], $uploadDir . $newName)){
            // Delete old file
            $q = $connection->query("SELECT file FROM poster WHERE poster_id=$id");
            if($q && $q->num_rows > 0){
                $old = $q->fetch_assoc();
                $oldPath = $uploadDir . $old['file'];
                if(file_exists($oldPath)) unlink($oldPath);
            }
            $connection->query("UPDATE poster SET judul='$judul', file='$newName' WHERE poster_id=$id");
        } else {
            echo 'Gagal mengupload file.';
            break;
        }
    } else {
        // Only update title
        $connection->query("UPDATE poster SET judul='$judul' WHERE poster_id=$id");
    }
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// TOGGLE POSTER STATUS
// =====================
case 'toggle-poster':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] == 'Y' ? 'Y' : 'N';
    $connection->query("UPDATE poster SET active='$status' WHERE poster_id=$id");
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// DELETE POSTER
// =====================
case 'delete-poster':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $q = $connection->query("SELECT file FROM poster WHERE poster_id=$id");
    if($q && $q->num_rows > 0){
        $row = $q->fetch_assoc();
        $filePath = '../../../sw-content/poster/' . $row['file'];
        if(file_exists($filePath)) unlink($filePath);
        $connection->query("DELETE FROM poster WHERE poster_id=$id");
        echo 'success';
    } else {
        echo 'Data tidak ditemukan.';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// ADD GALERI
// =====================
case 'add-galeri':
if($level_user == 1){
    if(empty($_POST['judul'])){
        echo 'Judul tidak boleh kosong!';
        break;
    }
    
    $tipe = ($_POST['tipe'] ?? 'foto') == 'video' ? 'video' : 'foto';
    $judul = mysqli_real_escape_string($connection, $_POST['judul']);
    
    if($tipe == 'foto'){
        // Photo: upload file
        if(!isset($_FILES['file_galeri']) || $_FILES['file_galeri']['error'] !== UPLOAD_ERR_OK){
            echo 'File foto harus diupload!';
            break;
        }
        $file = $_FILES['file_galeri'];
        $maxSize = 5 * 1024 * 1024;
        $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];
        
        if($file['size'] > $maxSize){
            echo 'Ukuran file terlalu besar! Maksimal 5MB.';
            break;
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if(!in_array($mime, $allowedMime)){
            echo 'Format file tidak diizinkan! Gunakan JPG/PNG/WEBP/GIF.';
            break;
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newName = 'galeri_' . time() . '_' . rand(100,999) . '.' . $ext;
        $uploadDir = '../../../sw-content/galeri/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        if(move_uploaded_file($file['tmp_name'], $uploadDir . $newName)){
            $connection->query("INSERT INTO galeri (judul, file, tipe, active, created_at) VALUES ('$judul', '$newName', 'foto', 'Y', NOW())");
            echo 'success';
        } else {
            echo 'Gagal mengupload file.';
        }
    } else {
        // Video: save YouTube URL
        $ytUrl = trim($_POST['youtube_url'] ?? '');
        if(empty($ytUrl)){
            echo 'Link YouTube tidak boleh kosong!';
            break;
        }
        // Validate YouTube URL
        if(!preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $ytUrl)){
            echo 'Link YouTube tidak valid! Gunakan format: https://www.youtube.com/watch?v=xxxxx';
            break;
        }
        $ytUrl = mysqli_real_escape_string($connection, $ytUrl);
        $connection->query("INSERT INTO galeri (judul, file, tipe, active, created_at) VALUES ('$judul', '$ytUrl', 'video', 'Y', NOW())");
        echo 'success';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// EDIT GALERI
// =====================
case 'edit-galeri':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    if(empty($_POST['judul'])){
        echo 'Judul tidak boleh kosong!';
        break;
    }
    $judul = mysqli_real_escape_string($connection, $_POST['judul']);
    $tipe = ($_POST['tipe'] ?? 'foto') == 'video' ? 'video' : 'foto';

    if($tipe == 'foto'){
        // Check if a new file was uploaded
        if(isset($_FILES['file_galeri']) && $_FILES['file_galeri']['error'] === UPLOAD_ERR_OK){
            $file = $_FILES['file_galeri'];
            $maxSize = 5 * 1024 * 1024;
            $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];

            if($file['size'] > $maxSize){
                echo 'Ukuran file terlalu besar! Maksimal 5MB.';
                break;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            if(!in_array($mime, $allowedMime)){
                echo 'Format file tidak diizinkan! Gunakan JPG/PNG/WEBP/GIF.';
                break;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $newName = 'galeri_' . time() . '_' . rand(100,999) . '.' . $ext;
            $uploadDir = '../../../sw-content/galeri/';

            if(move_uploaded_file($file['tmp_name'], $uploadDir . $newName)){
                // Delete old file
                $q = $connection->query("SELECT file FROM galeri WHERE galeri_id=$id");
                if($q && $q->num_rows > 0){
                    $old = $q->fetch_assoc();
                    $oldPath = $uploadDir . $old['file'];
                    if(file_exists($oldPath)) unlink($oldPath);
                }
                $connection->query("UPDATE galeri SET judul='$judul', file='$newName' WHERE galeri_id=$id");
            } else {
                echo 'Gagal mengupload file.';
                break;
            }
        } else {
            // Only update title
            $connection->query("UPDATE galeri SET judul='$judul' WHERE galeri_id=$id");
        }
    } else {
        // Video: update title and YouTube URL
        $ytUrl = trim($_POST['youtube_url'] ?? '');
        if(!empty($ytUrl)){
            if(!preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $ytUrl)){
                echo 'Link YouTube tidak valid! Gunakan format: https://www.youtube.com/watch?v=xxxxx';
                break;
            }
            $ytUrl = mysqli_real_escape_string($connection, $ytUrl);
            $connection->query("UPDATE galeri SET judul='$judul', file='$ytUrl' WHERE galeri_id=$id");
        } else {
            // Only update title
            $connection->query("UPDATE galeri SET judul='$judul' WHERE galeri_id=$id");
        }
    }
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// TOGGLE GALERI STATUS
// =====================
case 'toggle-galeri':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] == 'Y' ? 'Y' : 'N';
    $connection->query("UPDATE galeri SET active='$status' WHERE galeri_id=$id");
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// DELETE GALERI
// =====================
case 'delete-galeri':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $q = $connection->query("SELECT file, tipe FROM galeri WHERE galeri_id=$id");
    if($q && $q->num_rows > 0){
        $row = $q->fetch_assoc();
        // Only delete physical file if it's a photo (videos are YouTube URLs)
        if($row['tipe'] == 'foto'){
            $filePath = '../../../sw-content/galeri/' . $row['file'];
            if(file_exists($filePath)) unlink($filePath);
        }
        $connection->query("DELETE FROM galeri WHERE galeri_id=$id");
        echo 'success';
    } else {
        echo 'Data tidak ditemukan.';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// ADD ATLET
// =====================
case 'add-atlet':
if($level_user == 1){
    $nama = mysqli_real_escape_string($connection, trim($_POST['nama'] ?? ''));
    $prestasi = mysqli_real_escape_string($connection, trim($_POST['prestasi'] ?? ''));
    $kategori = mysqli_real_escape_string($connection, trim($_POST['kategori'] ?? ''));

    if(empty($nama) || empty($prestasi)){
        echo 'Nama dan prestasi wajib diisi!';
        break;
    }

    $uploadDir = '../../../sw-content/atlet/';
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if(isset($_FILES['foto_atlet']) && $_FILES['foto_atlet']['error'] == 0){
        $ext = strtolower(pathinfo($_FILES['foto_atlet']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if(!in_array($ext, $allowed)){
            echo 'Format foto tidak didukung!';
            break;
        }
        if($_FILES['foto_atlet']['size'] > 5*1024*1024){
            echo 'Ukuran foto maksimal 5MB!';
            break;
        }
        $fileName = 'atlet_'.uniqid().'.'.$ext;
        move_uploaded_file($_FILES['foto_atlet']['tmp_name'], $uploadDir.$fileName);
        $connection->query("INSERT INTO atlet (nama, prestasi, kategori, foto, active, created_at) VALUES ('$nama', '$prestasi', '$kategori', '$fileName', 'Y', NOW())");
        echo 'success';
    } else {
        echo 'Foto wajib diupload!';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// EDIT ATLET
// =====================
case 'edit-atlet':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $nama = mysqli_real_escape_string($connection, trim($_POST['nama'] ?? ''));
    $prestasi = mysqli_real_escape_string($connection, trim($_POST['prestasi'] ?? ''));
    $kategori = mysqli_real_escape_string($connection, trim($_POST['kategori'] ?? ''));

    $connection->query("UPDATE atlet SET nama='$nama', prestasi='$prestasi', kategori='$kategori' WHERE atlet_id=$id");

    if(isset($_FILES['foto_atlet']) && $_FILES['foto_atlet']['error'] == 0){
        $ext = strtolower(pathinfo($_FILES['foto_atlet']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if(in_array($ext, $allowed)){
            $uploadDir = '../../../sw-content/atlet/';
            // Delete old file
            $old = $connection->query("SELECT foto FROM atlet WHERE atlet_id=$id")->fetch_assoc();
            if($old && !empty($old['foto']) && file_exists($uploadDir.$old['foto'])){
                unlink($uploadDir.$old['foto']);
            }
            $fileName = 'atlet_'.uniqid().'.'.$ext;
            move_uploaded_file($_FILES['foto_atlet']['tmp_name'], $uploadDir.$fileName);
            $connection->query("UPDATE atlet SET foto='$fileName' WHERE atlet_id=$id");
        }
    }
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// TOGGLE ATLET STATUS
// =====================
case 'toggle-atlet':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] == 'Y' ? 'Y' : 'N';
    $connection->query("UPDATE atlet SET active='$status' WHERE atlet_id=$id");
    echo 'success';
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

// =====================
// DELETE ATLET
// =====================
case 'delete-atlet':
if($level_user == 1){
    $id = intval($_POST['id'] ?? 0);
    $q = $connection->query("SELECT foto FROM atlet WHERE atlet_id=$id");
    if($q && $q->num_rows > 0){
        $row = $q->fetch_assoc();
        $filePath = '../../../sw-content/atlet/'.$row['foto'];
        if(file_exists($filePath)) unlink($filePath);
        $connection->query("DELETE FROM atlet WHERE atlet_id=$id");
        echo 'success';
    } else {
        echo 'Data tidak ditemukan.';
    }
} else {
    echo 'Anda tidak memiliki hak akses!';
}
break;

}}
