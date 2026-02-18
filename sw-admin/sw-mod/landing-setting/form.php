<?php @session_start();

require_once'../../../sw-library/sw-config.php';
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
  header('location:../../login/');
  exit;
}
else{
  require_once'../../login/login_session.php';

// Helper: get setting value
function getLs($conn, $key, $default = ''){
    $key = mysqli_real_escape_string($conn, $key);
    $q = $conn->query("SELECT setting_value FROM landing_settings WHERE setting_key='$key' LIMIT 1");
    if($q && $q->num_rows > 0){
        $r = $q->fetch_assoc();
        return $r['setting_value'];
    }
    return $default;
}

switch (htmlentities(@$_GET['action'])){

// =====================
// TAB: HERO
// =====================
case 'hero':
    $hero_badge = htmlspecialchars(getLs($connection, 'hero_badge'));
    $hero_title = getLs($connection, 'hero_title');
    $hero_subtitle = htmlspecialchars(getLs($connection, 'hero_subtitle'));
    $hero_btn_primary = htmlspecialchars(getLs($connection, 'hero_btn_primary'));
    $hero_btn_secondary = htmlspecialchars(getLs($connection, 'hero_btn_secondary'));
    $hero_image = getLs($connection, 'hero_image');
    echo'
    <form id="validate" class="form-horizontal update-hero" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
          <label class="col-sm-3 control-label">Badge Label</label>
          <div class="col-sm-6">
            <input type="text" name="hero_badge" class="form-control" value="'.$hero_badge.'" placeholder="INSTITUT KARATE-DO NASIONAL">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Judul Hero <small class="text-muted">(HTML)</small></label>
          <div class="col-sm-6">
            <textarea name="hero_title" class="form-control" rows="2">'.$hero_title.'</textarea>
            <p class="text-muted"><small>Contoh: &lt;span class=&quot;gold&quot;&gt;Halim&lt;/span&gt; &lt;span class=&quot;red&quot;&gt;Karate&lt;/span&gt; Champion</small></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Subtitle</label>
          <div class="col-sm-6">
            <textarea name="hero_subtitle" class="form-control" rows="3">'.$hero_subtitle.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Tombol Utama</label>
          <div class="col-sm-6">
            <input type="text" name="hero_btn_primary" class="form-control" value="'.$hero_btn_primary.'" placeholder="Mulai Bergabung">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Tombol Sekunder</label>
          <div class="col-sm-6">
            <input type="text" name="hero_btn_secondary" class="form-control" value="'.$hero_btn_secondary.'" placeholder="Lihat Jadwal">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Gambar Hero</label>
          <div class="col-sm-6">';
            if(!empty($hero_image) && file_exists('../../../sw-content/landing/'.$hero_image)){
             echo'<img height="80" src="../sw-content/landing/'.$hero_image.'" style="border-radius:8px;margin-bottom:8px;display:block;">';
            }
            echo'<input type="file" class="btn btn-default" name="hero_image" accept="image/*">
            <p class="text-red"><small>*Kosongkan apabila tidak mengganti (maks 2MB, JPG/PNG/WEBP)</small></p>
          </div>
        </div>

      <div class="box-footer">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">';
        if($level_user ==1){
          echo'<button type="submit" class="btn bg-blue"><i class="fa fa-check"></i> Simpan</button>';
        } else {
          echo'<button type="button" class="btn bg-blue access-failed"><i class="fa fa-check"></i> Simpan</button>';
        }
        echo'
          <button type="reset" class="btn btn-danger">Reset</button>
        </div>
      </div>
  </form>';
break;

// =====================
// TAB: ABOUT
// =====================
case 'about':
    $about_label = htmlspecialchars(getLs($connection, 'about_label'));
    $about_title = getLs($connection, 'about_title');
    $about_desc1 = getLs($connection, 'about_desc1');
    $about_desc2 = getLs($connection, 'about_desc2');
    $about_image = getLs($connection, 'about_image');
    $about_stat_label = htmlspecialchars(getLs($connection, 'about_stat_label'));
    echo'
    <form id="validate" class="form-horizontal update-about" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
          <label class="col-sm-3 control-label">Label Section</label>
          <div class="col-sm-6">
            <input type="text" name="about_label" class="form-control" value="'.$about_label.'" placeholder="Tentang Kami">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Judul <small class="text-muted">(HTML)</small></label>
          <div class="col-sm-6">
            <textarea name="about_title" class="form-control" rows="2">'.$about_title.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Deskripsi 1 <small class="text-muted">(HTML)</small></label>
          <div class="col-sm-6">
            <textarea name="about_desc1" class="form-control" rows="3">'.$about_desc1.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Deskripsi 2 <small class="text-muted">(HTML)</small></label>
          <div class="col-sm-6">
            <textarea name="about_desc2" class="form-control" rows="3">'.$about_desc2.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Statistik Kustom</label>
          <div class="col-sm-6">
            <input type="text" name="about_stat_label" class="form-control" value="'.$about_stat_label.'" placeholder="4+">
            <p class="text-muted"><small>Teks angka yang ditampilkan (mis: 4+, 10+)</small></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Gambar About</label>
          <div class="col-sm-6">';
            if(!empty($about_image) && file_exists('../../../sw-content/landing/'.$about_image)){
             echo'<img height="80" src="../sw-content/landing/'.$about_image.'" style="border-radius:8px;margin-bottom:8px;display:block;">';
            }
            echo'<input type="file" class="btn btn-default" name="about_image" accept="image/*">
            <p class="text-red"><small>*Kosongkan apabila tidak mengganti (maks 2MB)</small></p>
          </div>
        </div>

      <div class="box-footer">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">';
        if($level_user ==1){
          echo'<button type="submit" class="btn bg-blue"><i class="fa fa-check"></i> Simpan</button>';
        } else {
          echo'<button type="button" class="btn bg-blue access-failed"><i class="fa fa-check"></i> Simpan</button>';
        }
        echo'
          <button type="reset" class="btn btn-danger">Reset</button>
        </div>
      </div>
  </form>';
break;

// =====================
// TAB: FEATURES
// =====================
case 'features':
    $feature_label = htmlspecialchars(getLs($connection, 'feature_label'));
    $feature_title = htmlspecialchars(getLs($connection, 'feature_title'));
    $feature_desc = htmlspecialchars(getLs($connection, 'feature_desc'));
    $features_json = getLs($connection, 'features_json', '[]');
    $features = json_decode($features_json, true);
    if(!is_array($features)) $features = [];
    
    echo'
    <form id="validate" class="form-horizontal update-features" autocomplete="off">
        <div class="form-group">
          <label class="col-sm-3 control-label">Label Section</label>
          <div class="col-sm-6">
            <input type="text" name="feature_label" class="form-control" value="'.$feature_label.'" placeholder="Fitur Sistem">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Judul Section</label>
          <div class="col-sm-6">
            <input type="text" name="feature_title" class="form-control" value="'.$feature_title.'">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Deskripsi Section</label>
          <div class="col-sm-6">
            <textarea name="feature_desc" class="form-control" rows="2">'.$feature_desc.'</textarea>
          </div>
        </div>

        <hr>
        <h4 style="margin-left:15px;"><i class="fa fa-th"></i> Daftar Fitur Card</h4>
        <div id="featureCards">';
        
        foreach($features as $idx => $f){
            $fIcon = htmlspecialchars($f['icon'] ?? '');
            $fColor = htmlspecialchars($f['color'] ?? 'red');
            $fTitle = htmlspecialchars($f['title'] ?? '');
            $fDesc = htmlspecialchars($f['desc'] ?? '');
            echo'
            <div class="feature-card-item" style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:15px;margin:10px 15px;">
              <div class="row">
                <div class="col-md-3">
                  <label>Icon (Ionicon)</label>
                  <input type="text" class="form-control feature-icon-input" value="'.$fIcon.'" placeholder="camera-outline">
                </div>
                <div class="col-md-2">
                  <label>Warna</label>
                  <select class="form-control feature-color-input">
                    <option value="red" '.($fColor=='red'?'selected':'').'>Merah</option>
                    <option value="gold" '.($fColor=='gold'?'selected':'').'>Emas</option>
                    <option value="green" '.($fColor=='green'?'selected':'').'>Hijau</option>
                    <option value="blue" '.($fColor=='blue'?'selected':'').'>Biru</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label>Judul</label>
                  <input type="text" class="form-control feature-title-input" value="'.$fTitle.'">
                </div>
                <div class="col-md-3">
                  <label>Deskripsi</label>
                  <input type="text" class="form-control feature-desc-input" value="'.$fDesc.'">
                </div>
                <div class="col-md-1" style="padding-top:25px;">
                  <button type="button" class="btn btn-danger btn-sm btn-remove-feature"><i class="fa fa-trash"></i></button>
                </div>
              </div>
            </div>';
        }
        
        echo'
        </div>
        <div style="margin:10px 15px;">
          <button type="button" class="btn btn-success btn-sm" id="btnAddFeature"><i class="fa fa-plus"></i> Tambah Fitur</button>
        </div>
        <hr>

      <div class="box-footer">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">';
        if($level_user ==1){
          echo'<button type="submit" class="btn bg-blue"><i class="fa fa-check"></i> Simpan</button>';
        } else {
          echo'<button type="button" class="btn bg-blue access-failed"><i class="fa fa-check"></i> Simpan</button>';
        }
        echo'
          <button type="reset" class="btn btn-danger">Reset</button>
        </div>
      </div>
  </form>';
break;

// =====================
// TAB: CTA & FOOTER
// =====================
case 'cta':
    $cta_label = htmlspecialchars(getLs($connection, 'cta_label'));
    $cta_title = getLs($connection, 'cta_title');
    $cta_desc = htmlspecialchars(getLs($connection, 'cta_desc'));
    $footer_text = htmlspecialchars(getLs($connection, 'footer_text'));
    echo'
    <form id="validate" class="form-horizontal update-cta" autocomplete="off">
        <h4 style="margin-left:15px;"><i class="fa fa-bullhorn"></i> Section CTA (Call to Action)</h4>
        <div class="form-group">
          <label class="col-sm-3 control-label">Label CTA</label>
          <div class="col-sm-6">
            <input type="text" name="cta_label" class="form-control" value="'.$cta_label.'" placeholder="Siap Berlatih?">
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Judul CTA <small class="text-muted">(HTML)</small></label>
          <div class="col-sm-6">
            <textarea name="cta_title" class="form-control" rows="2">'.$cta_title.'</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Deskripsi CTA</label>
          <div class="col-sm-6">
            <textarea name="cta_desc" class="form-control" rows="2">'.$cta_desc.'</textarea>
          </div>
        </div>

        <hr>
        <h4 style="margin-left:15px;"><i class="fa fa-copyright"></i> Footer</h4>
        <div class="form-group">
          <label class="col-sm-3 control-label">Nama Footer</label>
          <div class="col-sm-6">
            <input type="text" name="footer_text" class="form-control" value="'.$footer_text.'" placeholder="DOJO HKC">
          </div>
        </div>

      <div class="box-footer">
        <label class="col-sm-3 control-label"></label>
        <div class="col-sm-6">';
        if($level_user ==1){
          echo'<button type="submit" class="btn bg-blue"><i class="fa fa-check"></i> Simpan</button>';
        } else {
          echo'<button type="button" class="btn bg-blue access-failed"><i class="fa fa-check"></i> Simpan</button>';
        }
        echo'
          <button type="reset" class="btn btn-danger">Reset</button>
        </div>
      </div>
  </form>';
break;

// =====================
// TAB: POSTER REKRUTMEN
// =====================
case 'poster':
    $posters = [];
    $qp = $connection->query("SELECT * FROM poster ORDER BY created_at DESC");
    if($qp && $qp->num_rows > 0){
        while($rp = $qp->fetch_assoc()) $posters[] = $rp;
    }
    echo'
    <h4 style="margin:0 0 15px 0;"><i class="fa fa-image"></i> Kelola Poster Rekrutmen</h4>
    
    <!-- Add Poster Form -->
    <form class="form-horizontal add-poster" enctype="multipart/form-data" autocomplete="off" style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:15px;margin-bottom:20px;">
        <div class="row">
          <div class="col-md-4">
            <label>Judul Poster</label>
            <input type="text" name="judul" class="form-control" placeholder="Judul poster" required>
          </div>
          <div class="col-md-5">
            <label>File Gambar <small class="text-muted">(JPG/PNG/WEBP, maks 5MB)</small></label>
            <input type="file" name="file_poster" class="form-control" accept="image/*" required>
          </div>
          <div class="col-md-3" style="padding-top:25px;">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Poster</button>
          </div>
        </div>
    </form>
    
    <!-- Poster List -->
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr>
        <th width="50">#</th>
        <th>Preview</th>
        <th>Judul</th>
        <th width="80">Status</th>
        <th width="130">Aksi</th>
      </tr></thead>
      <tbody>';
      if(empty($posters)){
          echo '<tr><td colspan="5" class="text-center text-muted">Belum ada poster</td></tr>';
      } else {
          foreach($posters as $i => $p){
              $statusBadge = $p['active'] == 'Y' ? '<span class="label label-success">Aktif</span>' : '<span class="label label-default">Nonaktif</span>';
              $toggleBtn = $p['active'] == 'Y' ? 
                  '<button class="btn btn-warning btn-xs btn-toggle-poster" data-id="'.$p['poster_id'].'" data-status="N" title="Nonaktifkan"><i class="fa fa-eye-slash"></i></button>' :
                  '<button class="btn btn-info btn-xs btn-toggle-poster" data-id="'.$p['poster_id'].'" data-status="Y" title="Aktifkan"><i class="fa fa-eye"></i></button>';
              echo'<tr>
                <td>'.($i+1).'</td>
                <td><img src="../sw-content/poster/'.htmlspecialchars($p['file']).'" height="50" style="border-radius:4px;"></td>
                <td>'.htmlspecialchars($p['judul']).'</td>
                <td>'.$statusBadge.'</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-edit-poster" data-id="'.$p['poster_id'].'" data-judul="'.htmlspecialchars($p['judul']).'" data-file="'.htmlspecialchars($p['file']).'" title="Edit"><i class="fa fa-pencil"></i></button>
                  '.$toggleBtn.'
                  <button class="btn btn-danger btn-xs btn-delete-poster" data-id="'.$p['poster_id'].'" title="Hapus"><i class="fa fa-trash"></i></button>
                </td>
              </tr>';
          }
      }
      echo'
      </tbody>
    </table>
    </div>
    
    <!-- Modal Edit Poster -->
    <div class="modal fade" id="modalEditPoster" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form class="edit-poster-form" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Poster</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="editPosterId">
              <div class="form-group">
                <label>Judul Poster</label>
                <input type="text" name="judul" id="editPosterJudul" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Gambar Saat Ini</label><br>
                <img id="editPosterPreview" height="80" style="border-radius:6px;">
              </div>
              <div class="form-group">
                <label>Ganti Gambar <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                <input type="file" name="file_poster" class="form-control" accept="image/*">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>';
break;

// =====================
// TAB: GALERI MEDIA
// =====================
case 'galeri':
    $galeris = [];
    $qg = $connection->query("SELECT * FROM galeri ORDER BY created_at DESC");
    if($qg && $qg->num_rows > 0){
        while($rg = $qg->fetch_assoc()) $galeris[] = $rg;
    }
    echo'
    <h4 style="margin:0 0 15px 0;"><i class="fa fa-film"></i> Kelola Galeri Foto & Video</h4>
    
    <!-- Add Gallery Form -->
    <form class="form-horizontal add-galeri" enctype="multipart/form-data" autocomplete="off" style="background:#f9f9f9;border:1px solid #ddd;border-radius:8px;padding:15px;margin-bottom:20px;">
        <div class="row">
          <div class="col-md-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" placeholder="Judul media" required>
          </div>
          <div class="col-md-2">
            <label>Tipe</label>
            <select name="tipe" class="form-control" id="galeriTipeSelect" onchange="toggleGaleriInput(this.value)">
              <option value="foto">Foto</option>
              <option value="video">Video (YouTube)</option>
            </select>
          </div>
          <div class="col-md-4" id="galeriFileInput">
            <label>File <small class="text-muted">(JPG/PNG/WEBP, maks 5MB)</small></label>
            <input type="file" name="file_galeri" class="form-control" accept="image/*">
          </div>
          <div class="col-md-4" id="galeriUrlInput" style="display:none;">
            <label>Link YouTube <small class="text-muted">(paste URL video)</small></label>
            <input type="text" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxxx">
          </div>
          <div class="col-md-3" style="padding-top:25px;">
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Media</button>
          </div>
        </div>
    </form>
    <script>
    function toggleGaleriInput(val){
      if(val=="video"){
        document.getElementById("galeriFileInput").style.display="none";
        document.getElementById("galeriUrlInput").style.display="";
        document.querySelector("#galeriFileInput input").removeAttribute("required");
      } else {
        document.getElementById("galeriFileInput").style.display="";
        document.getElementById("galeriUrlInput").style.display="none";
      }
    }
    </script>
    
    <!-- Gallery List -->
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead><tr>
        <th width="50">#</th>
        <th>Preview</th>
        <th>Judul</th>
        <th width="60">Tipe</th>
        <th width="80">Status</th>
        <th width="130">Aksi</th>
      </tr></thead>
      <tbody>';
      if(empty($galeris)){
          echo '<tr><td colspan="6" class="text-center text-muted">Belum ada media</td></tr>';
      } else {
          foreach($galeris as $i => $g){
              $statusBadge = $g['active'] == 'Y' ? '<span class="label label-success">Aktif</span>' : '<span class="label label-default">Nonaktif</span>';
              $toggleBtn = $g['active'] == 'Y' ? 
                  '<button class="btn btn-warning btn-xs btn-toggle-galeri" data-id="'.$g['galeri_id'].'" data-status="N" title="Nonaktifkan"><i class="fa fa-eye-slash"></i></button>' :
                  '<button class="btn btn-info btn-xs btn-toggle-galeri" data-id="'.$g['galeri_id'].'" data-status="Y" title="Aktifkan"><i class="fa fa-eye"></i></button>';
              $tipeBadge = $g['tipe'] == 'foto' ? '<span class="label label-primary">Foto</span>' : '<span class="label label-danger">Video</span>';
              
              if($g['tipe'] == 'foto'){
                  $preview = '<img src="../sw-content/galeri/'.htmlspecialchars($g['file']).'" height="50" style="border-radius:4px;">';
              } else {
                  // Extract YouTube ID for thumbnail
                  $ytId = '';
                  $ytUrl = $g['file'];
                  if(preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $ytUrl, $m)){
                      $ytId = $m[1];
                  }
                  if($ytId){
                      $preview = '<img src="https://img.youtube.com/vi/'.$ytId.'/mqdefault.jpg" height="50" style="border-radius:4px;">';
                  } else {
                      $preview = '<span class="label label-warning"><i class="fa fa-youtube-play"></i> YT</span>';
                  }
              }
              
              echo'<tr>
                <td>'.($i+1).'</td>
                <td>'.$preview.'</td>
                <td>'.htmlspecialchars($g['judul']).'</td>
                <td>'.$tipeBadge.'</td>
                <td>'.$statusBadge.'</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-edit-galeri" data-id="'.$g['galeri_id'].'" data-judul="'.htmlspecialchars($g['judul']).'" data-tipe="'.$g['tipe'].'" data-file="'.htmlspecialchars($g['file']).'" title="Edit"><i class="fa fa-pencil"></i></button>
                  '.$toggleBtn.'
                  <button class="btn btn-danger btn-xs btn-delete-galeri" data-id="'.$g['galeri_id'].'" title="Hapus"><i class="fa fa-trash"></i></button>
                </td>
              </tr>';
          }
      }
      echo'
      </tbody>
    </table>
    </div>
    
    <!-- Modal Edit Galeri -->
    <div class="modal fade" id="modalEditGaleri" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form class="edit-galeri-form" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Media</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="editGaleriId">
              <input type="hidden" name="tipe" id="editGaleriTipe">
              <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" id="editGaleriJudul" class="form-control" required>
              </div>
              <div id="editGaleriFotoGroup">
                <div class="form-group">
                  <label>Foto Saat Ini</label><br>
                  <img id="editGaleriPreview" height="80" style="border-radius:6px;">
                </div>
                <div class="form-group">
                  <label>Ganti Foto <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                  <input type="file" name="file_galeri" class="form-control" accept="image/*">
                </div>
              </div>
              <div id="editGaleriVideoGroup" style="display:none;">
                <div class="form-group">
                  <label>Link YouTube</label>
                  <input type="text" name="youtube_url" id="editGaleriYtUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxxx">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>';
break;

// =====================
// TAB: ATLET KEBANGGAAN
// =====================
case 'atlet':
    // Ensure table exists
    $connection->query("CREATE TABLE IF NOT EXISTS `atlet` (
        `atlet_id` int(11) NOT NULL AUTO_INCREMENT,
        `nama` varchar(255) NOT NULL,
        `prestasi` varchar(255) NOT NULL,
        `kategori` varchar(100) DEFAULT NULL,
        `foto` varchar(255) NOT NULL,
        `active` enum('Y','N') NOT NULL DEFAULT 'Y',
        `created_at` datetime DEFAULT NULL,
        PRIMARY KEY (`atlet_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $q_atlet = $connection->query("SELECT * FROM atlet ORDER BY created_at DESC");
    $atlets = [];
    if($q_atlet && $q_atlet->num_rows > 0){
        while($row = $q_atlet->fetch_assoc()) $atlets[] = $row;
    }

    echo'
    <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-trophy"></i> Kelola Atlet Kebanggaan</h3>
    </div>
    <div class="box-body">

    <form class="add-atlet" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label>Nama Atlet</label>
          <input type="text" name="nama" class="form-control" placeholder="Nama lengkap atlet" required>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Prestasi / Medali</label>
          <input type="text" name="prestasi" class="form-control" placeholder="Contoh: Medali Emas PON 2025" required>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>Kategori</label>
          <input type="text" name="kategori" class="form-control" placeholder="Kumite -84 Kg">
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Foto <small class="text-muted">(JPG/PNG/WEBP, maks 5MB)</small></label>
          <input type="file" name="foto_atlet" class="form-control" accept="image/*" required>
        </div>
      </div>
      <div class="col-md-1">
        <div class="form-group">
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i></button>
        </div>
      </div>
    </div>
    </form>

    <table class="table table-bordered table-hover table-striped">
      <thead><tr>
        <th width="30">#</th>
        <th>Foto</th>
        <th>Nama</th>
        <th>Prestasi</th>
        <th>Kategori</th>
        <th width="80">Status</th>
        <th width="130">Aksi</th>
      </tr></thead>
      <tbody>';
      if(empty($atlets)){
          echo '<tr><td colspan="7" class="text-center text-muted">Belum ada data atlet</td></tr>';
      } else {
          foreach($atlets as $i => $a){
              $statusBadge = $a['active'] == 'Y' ? '<span class="label label-success">Aktif</span>' : '<span class="label label-default">Nonaktif</span>';
              $toggleBtn = $a['active'] == 'Y' ?
                  '<button class="btn btn-warning btn-xs btn-toggle-atlet" data-id="'.$a['atlet_id'].'" data-status="N" title="Nonaktifkan"><i class="fa fa-eye-slash"></i></button>' :
                  '<button class="btn btn-info btn-xs btn-toggle-atlet" data-id="'.$a['atlet_id'].'" data-status="Y" title="Aktifkan"><i class="fa fa-eye"></i></button>';
              echo'<tr>
                <td>'.($i+1).'</td>
                <td><img src="../sw-content/atlet/'.htmlspecialchars($a['foto']).'" height="50" style="border-radius:4px;"></td>
                <td>'.htmlspecialchars($a['nama']).'</td>
                <td>'.htmlspecialchars($a['prestasi']).'</td>
                <td>'.htmlspecialchars($a['kategori'] ?? '-').'</td>
                <td>'.$statusBadge.'</td>
                <td>
                  <button class="btn btn-primary btn-xs btn-edit-atlet" data-id="'.$a['atlet_id'].'" data-nama="'.htmlspecialchars($a['nama']).'" data-prestasi="'.htmlspecialchars($a['prestasi']).'" data-kategori="'.htmlspecialchars($a['kategori'] ?? '').'" data-foto="'.htmlspecialchars($a['foto']).'" title="Edit"><i class="fa fa-pencil"></i></button>
                  '.$toggleBtn.'
                  <button class="btn btn-danger btn-xs btn-delete-atlet" data-id="'.$a['atlet_id'].'" title="Hapus"><i class="fa fa-trash"></i></button>
                </td>
              </tr>';
          }
      }
      echo'
      </tbody>
    </table>
    </div>

    <!-- Modal Edit Atlet -->
    <div class="modal fade" id="modalEditAtlet" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form class="edit-atlet-form" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Atlet</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" id="editAtletId">
              <div class="form-group">
                <label>Nama Atlet</label>
                <input type="text" name="nama" id="editAtletNama" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Prestasi / Medali</label>
                <input type="text" name="prestasi" id="editAtletPrestasi" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" id="editAtletKategori" class="form-control">
              </div>
              <div class="form-group">
                <label>Foto Saat Ini</label><br>
                <img id="editAtletPreview" height="80" style="border-radius:6px;">
              </div>
              <div class="form-group">
                <label>Ganti Foto <small class="text-muted">(Kosongkan jika tidak ingin mengganti)</small></label>
                <input type="file" name="foto_atlet" class="form-control" accept="image/*">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>';
break;

}}
