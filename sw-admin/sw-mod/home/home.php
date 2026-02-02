<?php
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';

  $query_employees ="SELECT id FROM employees";
  $result_count = $connection->query($query_employees);

  $query_position ="SELECT position_id FROM position";
  $result_count_position = $connection->query($query_position);

  $query_building ="SELECT building_id FROM building";
  $result_count_building = $connection->query($query_building);

  $query_shift ="SELECT shift_id FROM shift";
  $result_count_shift = $connection->query($query_shift);
  
  
  
  $query="SELECT
  SUM(CASE WHEN status = 'berhasil' AND tahun ='$year' THEN nominal ELSE 0 END) AS total_pembayaran_spp,
  SUM(CASE WHEN status = 'berhasil' AND tanggal = CURRENT_DATE THEN nominal ELSE 0 END) AS total_pembayaran_hari_ini,
  SUM(CASE WHEN status = 'pending' AND tanggal = CURRENT_DATE THEN nominal ELSE 0 END) AS pembayaran_pending,
  SUM(CASE WHEN status = 'berhasil' AND tanggal = CURRENT_DATE THEN nominal ELSE 0 END) AS pembayaran_berhasil FROM pembayaran_spp";
  $result = $connection->query($query);
  $data = $result->fetch_assoc();
  

$filter_bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : $month;
$filter_tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : $year;

echo'
<div class="content-wrapper">
  <section class="content">
    <div class="row">

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>'.$result_count->num_rows.'</h3>
            <p>Atlet</p>
          </div>
          <div class="icon">
            <i class="fa fa-user"></i>
          </div>
            <a href="./karyawan" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>'.$result_count_position->num_rows.'</h3>
            <p>Kategori</p>
          </div>
          <div class="icon">
            <i class="fa fa fa-briefcase"></i>
          </div>
          <a href="./jabatan" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3>'.$result_count_building->num_rows.'</h3>
            <p>Lokasi Absen</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
          <a href="./lokasi" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
          <div class="inner">
            <h3>'.$result_count_shift->num_rows.'</h3>
            <p>Jam Latihan</p>
          </div>
          <div class="icon">
            <i class="fa fa-retweet"></i>
          </div>
          <a href="./shift" class="small-box-footer">
            More Info <i class="fa fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>


      <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Pembayaran SPP</span>
            <span class="info-box-number">'.format_angka($data['total_pembayaran_spp']??'0').'</span>
            <span class="progress-description">
              Tahun '.$year.'
            </span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pembayaran Hari ini</span>
            <span class="info-box-number">'.format_angka($data['total_pembayaran_hari_ini']??'0').'</span>
            <span class="progress-description">
              '.tanggal_ind($date).'
            </span>
          </div>
        </div>
      </div>


      <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pembayaran Berhasil</span>
            <span class="info-box-number">'.$data['pembayaran_berhasil'].'</span>
            <span class="progress-description">
              '.tanggal_ind($date).'
            </span>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-xs-6">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Pembayaran Pending</span>
            <span class="info-box-number">'.$data['pembayaran_berhasil'].'</span>
            <span class="progress-description">
              '.tanggal_ind($date).'
            </span>
          </div>
        </div>
      </div>
        
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Statistik Absensi</h3>
          <div class="box-tools pull-right">
            <div class="row">
              <div class="col-md-6">
                  <select id="filterBulan" class="form-control bulan float-right" required>';
                  $bulan_nama =array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                  for($bulan=1; $bulan<=12; $bulan++){
                    if($bulan==$filter_bulan ) {
                      echo'<option value="'.$bulan.'" selected>'.$bulan_nama[$bulan].'</option>';
                    }else { 
                      echo'<option value="'.$bulan.'">'.$bulan_nama[$bulan].'</option>'; 
                    }
                  }
                    echo'
                  </select>
              </div>

              <div class="col-md-6">
                <select id="filterTahun" class="form-control tahun" required>';
                $mulai= date('Y') - 2;
                for($i = $mulai;$i<$mulai + 50;$i++){
                    $sel = $i == $filter_tahun ? ' selected="selected"' : '';
                    echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                }
                echo'
                </select>
               </div>
            </div>
            
    
          </div>
        </div>
          <div class="box-body">
            <div class="chart">
               <canvas id="areaChart" style="height:300px"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Absensi Hari ini</h3>
        </div>
        
          <div class="box-body no-padding">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width: 10px" class="text-center">No.</th>
                  <th>Nama</th>
                  <th>Jam Masuk</th>
                  <th>Jam Pulang</th>
                  <th class="text-right">Aksi</th>
                </tr>
                <tr>';
                $query_absent_day ="SELECT presence.employees_id,presence.time_in,presence.time_out,employees.employees_name FROM presence,employees WHERE presence.employees_id=employees.id AND presence.presence_date='$date' ORDER BY presence.presence_id LIMIT 10";
                $result_absent_day = $connection->query($query_absent_day);
                if($result_absent_day->num_rows > 0){
                $no=0;
                while ($row = $result_absent_day->fetch_assoc()) {
                  $no++;
                  echo'
                  <td class="text-center">'.$no.'</td>
                  <td>'.$row['employees_name'].'</td>
                  <td>'.$row['time_in'].'</td>
                  <td>'.$row['time_out'].'</td>
                  <td class="text-right"><a href="absensi&op=views&id='.epm_encode($row['employees_id']).'" class="btn btn-warning btn-xs"><i class="fa fa-external-link-square" aria-hidden="true"></i></a></td>
                </tr>';}}
                echo'
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Permohonan Cuti</h3>
          <div class="box-tools pull-right">
            <a href="cuty" class="btn btn-success btn-flat">Data Cuti</a>
          </div>
        </div>
          <div class="box-body no-padding">
          <table class="table">
            <tbody>
                <tr>
                  <th style="width: 10px" class="text-center">No.</th>
                  <th>Nama</th>
                  <th>Tanggal Cuti</th>
                  <th class="text-center">Jumlah</th>
                  <th class="text-right">Masuk Latihan</th>
                </tr>
                <tr>';
                $query_cuty="SELECT employees.employees_name,cuty.* FROM employees,cuty WHERE employees.id=cuty.employees_id AND cuty.cuty_status='3' order by cuty.cuty_id DESC LIMIT 10";
                $result_cuty = $connection->query($query_cuty);
                if($result_cuty->num_rows > 0){
                $no=0;
                while ($row_cuty= $result_cuty->fetch_assoc()) {
                $no++;
                  echo'
                  <td class="text-center">'.$no.'</td>
                  <td>'.$row_cuty['employees_name'].'</td>
                  <td>'.tgl_ind($row_cuty['cuty_start']).' sampai '.tgl_ind($row_cuty['cuty_end']).'</td>
                  <td class="text-center"><label class="label label-warning">'.$row_cuty['cuty_total'].'</label></td>
                  <td class="text-right">'.tgl_ind($row_cuty['date_work']).'</td>
                </tr>';}
                }
          echo'
            </tbody>
          </table>
          </div>
        </div>
      </div>
  </div>
</section>
</div>';

// Hari ini
// --- MULAI KODE BARU ---
// Hari ini - Logika Grafik Absensi Bulanan
$tanggal_visitor = [];
$absensi = [];

// DEBUG: Tampilkan filter yang digunakan
echo "<!-- DEBUG: Filter Bulan = $filter_bulan, Filter Tahun = $filter_tahun -->";

// 1. Pastikan format bulan memiliki 2 digit (misal: 1 jadi 01)
$bulan_pilih = sprintf("%02d", $filter_bulan);
$tahun_pilih = $filter_tahun;

// 2. Tentukan tanggal awal bulan tersebut
$tgl_awal_bulan = "$tahun_pilih-$bulan_pilih-01";

// DEBUG
echo "<!-- DEBUG: Tanggal Awal Bulan = $tgl_awal_bulan -->";

// 3. Hitung total hari dalam bulan yang dipilih
$jumlah_hari = date('t', strtotime($tgl_awal_bulan));

// DEBUG
echo "<!-- DEBUG: Jumlah Hari = $jumlah_hari -->";

// 4. Looping dari hari ke-1 sampai habis bulan
for ($i = 0; $i < $jumlah_hari; $i++) {
    // Tambahkan hari ke-i dari tanggal awal
    $tgl_loop = strtotime("+$i day", strtotime($tgl_awal_bulan));
    $hasil_tgl = date("Y-m-d", $tgl_loop);

    // Simpan label tanggal untuk grafik (Sumbu X)
    $tanggal_visitor[] = tgl_ind($hasil_tgl); 

    // Ambil data absensi dari database untuk tanggal tersebut
    $query_absensi = "SELECT presence_date FROM presence WHERE presence_date = '$hasil_tgl'";
    $result_absensi = $connection->query($query_absensi);
    
    // Simpan jumlah absensi (Sumbu Y)
    $absensi[] = $result_absensi->num_rows;
}

$tanggal_visitor = implode('","', $tanggal_visitor);
?>
// --- SELESAI KODE BARU ---

 $tanggal_visitor = implode('","',$tanggal_visitor);?>
 <script type="text/javascript">
    var lineChartData = {
      labels :["<?php echo $tanggal_visitor;?>"],
      datasets : [
        {
          label: "Statistik Absensi",
          fillColor : "rgba(29,75,251,0.7)",
          strokeColor : "rgba(220,220,220,1)",
          pointColor : "rgba(220,220,220,1)",
          pointStrokeColor : "#fff",
          pointHighlightFill : "#fff",
          pointHighlightStroke : "rgba(220,220,220,1)",
          data :<?php echo json_encode($absensi);?>

        }
      ]

    }

  window.onload = function(){
    var ctx = document.getElementById("areaChart").getContext("2d");
    window.myLine = new Chart(ctx).Line(lineChartData, {
      responsive: true
    });
    
    // Event listener untuk filter bulan dan tahun
    var bulanSelect = document.getElementById('filterBulan');
    var tahunSelect = document.getElementById('filterTahun');
    
    console.log('Bulan Select:', bulanSelect);
    console.log('Tahun Select:', tahunSelect);
    console.log('Current URL:', window.location.href);
    
    if(bulanSelect && tahunSelect) {
      function updateFilter() {
        var bulan = bulanSelect.value;
        var tahun = tahunSelect.value;
        
        console.log('Filter diubah - Bulan:', bulan, 'Tahun:', tahun);
        
        // Ambil URL parameters yang ada
        var urlParams = new URLSearchParams(window.location.search);
        
        // Set/update parameter bulan dan tahun
        urlParams.set('bulan', bulan);
        urlParams.set('tahun', tahun);
        
        console.log('URL baru:', urlParams.toString());
        console.log('Full URL:', window.location.pathname + '?' + urlParams.toString());
        
        // Redirect dengan semua parameter
        window.location.search = urlParams.toString();
      }
      
      bulanSelect.addEventListener('change', updateFilter);
      tahunSelect.addEventListener('change', updateFilter);
      
      console.log('Event listeners berhasil ditambahkan pada filterBulan dan filterTahun');
    } else {
      console.error('Dropdown tidak ditemukan!', 'Bulan:', bulanSelect, 'Tahun:', tahunSelect);
    }
  }
 
</script>
<?PHP
}?>
