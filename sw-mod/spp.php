<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
 header("location:./");
}else{


$data_spp = null;
$query_spp ="SELECT * FROM spp WHERE status='Y' AND tahun_pelajaran='$row_user[tahun_pelajaran]' ORDER BY spp_id DESC";
$result_spp = $connection->query($query_spp);
$data_spp = $result_spp->fetch_assoc();

$sql_total = "SELECT SUM(nominal) AS total_bayar FROM pembayaran_spp WHERE employees_id='$row_user[id]' AND tahun_pelajaran='$row_user[tahun_pelajaran]' AND status='berhasil'";
$result_total = mysqli_query($connection, $sql_total);
if ($result_total) {
    $data_total = mysqli_fetch_assoc($result_total);
    $total_bayar = $data_total['total_bayar'] ?? 0;
} else {
    $total_bayar = 0;
}


$sql_last = "SELECT nominal, tahun_pelajaran, tahun, tanggal as terakhir_bayar FROM pembayaran_spp 
WHERE employees_id='$row_user[id]' AND status='berhasil' AND tahun_pelajaran='$row_user[tahun_pelajaran]' ORDER BY tanggal DESC LIMIT 1";
$result_last = mysqli_query($connection, $sql_last);
if ($result_last && mysqli_num_rows($result_last) > 0) {
    $data_last = mysqli_fetch_assoc($result_last);
    $nominal_per_bulan = $data_last['nominal'];
    $terakhir_bayar = tanggal_ind($data_last['terakhir_bayar']);
    $tahun_pelajaran = $data_last['tahun_pelajaran'];
    $tahun = $data_last['tahun'];
} else {
    // Jika data kosong, set default
    $nominal_per_bulan = 0;
    $terakhir_bayar = '-';
    $tahun_pelajaran = 0;
    $tahun = date("Y");
}

$tunggakan_nominal = $data_spp['nominal'] - $total_bayar;

echo'<!-- App Capsule -->
<div id="appCapsule">
    <div class="section mt-2">
        
        <div class="card-block bg-secondary mb-2">
        <div class="card-main">
            <div class="card-button dropdown">';
                if($tunggakan_nominal !== 0){
                echo'
                <button class="btn btn-success btn-bayar">Bayar</button>';
                }
            echo'
            </div>

            <div class="balance">
                <span class="label">PEMBAYARAN SPP</span>
                <h3 class="text-white">Rp '.format_angka($data_spp['nominal']).'</h3>
            </div>
            <div class="in">
                <div class="card-number">
                    <span class="label">Tunggakan</span>
                    Rp '.format_angka($tunggakan_nominal).'
                </div>
                <div class="bottom">
                    <div class="card-expiry">
                        <span class="label">Terakhir Pembayaran</span>
                        '.$terakhir_bayar.'
                    </div>
                </div>
            </div>
        </div>
        
        </div>
    </div>

    <div class="section mt-2">
        <div class="section-title">Histori 
            
        </div>
        <div class="transactions">
            <div class="loaddataSpp"></div>
        </div>
    </div>
    
    <div class="modal fade modal-spp" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <a href="javascript:;" data-dismiss="modal">Close</a>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="label">Jumlah</label>
                            <input type="number" class="form-control nominal" name="nominal" required> 
                            <input type="text" class="form-control tahun-pelajaran d-none" value="'.epm_encode($data_spp['tahun_pelajaran']??'').'" readonly required>
                        </div>
                        <div class="form-group basic">
                            <button type="submit" class="btn btn-success btn-block mt-2 btn-submit-spp">Bayar Sekarang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
  }
  include_once 'sw-mod/sw-footer.php';
} ?>