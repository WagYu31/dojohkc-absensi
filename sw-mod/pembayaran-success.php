<?php if($connection){
require_once './sw-library/midtrans.php';
include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
 header("location:./");
}else{
    if (isset($_COOKIE['order'])) {
        $order_id = $_COOKIE['order'];
    }

$order_status =  '';
$query ="SELECT * FROM pembayaran_spp WHERE employees_id='$row_user[id]' AND order_id='$order_id'";
$result = $connection->query($query);
if($result->num_rows > 0){
$data = $result->fetch_assoc();
    $order_id = $data['order_id'];
    $tanggal = tanggal_ind($data['tanggal']);
    $nominal = format_angka($data['nominal']);

    /* Cek pembayaran Otomatis */
    $url = "$payment_link/{$order_id}/status";
    $serverKeyOrder = $payment_server;
    $auth = base64_encode($serverKeyOrder . ':');
    // Inisialisasi cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic $auth",
        "Content-Type: application/json",
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        $result = json_decode($response, true);
        if (isset($result['transaction_status']) && $result['transaction_status'] === 'settlement') {
            $pembayaran_success = 'Y';
            /** Update */
            $update = "UPDATE pembayaran_spp SET status='Berhasil' WHERE employees_id='$row_user[id]' AND order_id='$order_id'";
            $connection->query($update);
        } else {
            $pembayaran_success = 'N';
        }
    }
echo'
<div id="appCapsule">
    <div class="section">
        <div class="splash-page mt-5 mb-5">
            <div class="iconbox bg-success mb-3">
                <i class="fas fa-check-double"></i>
            </div>
            <h2 class="mb-2">Pembayaran Berhasil</h2>
            <p>
                Pembayaran SPP untuk bulan ['.ambilbulan($data['bulan']).'] sebesar Rp ['.$nominal.'] telah BERHASIL.<br>
                Tanggal transaksi: ['.$tanggal.']<br>
                ID Pembayaran: ['.$order_id.']<br>
                Terima kasih atas pembayaran Anda.
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-12 text-center">
            <a href="./spp" class="btn btn-lg btn-success">Kembali</a>
        </div>
    </div>
</div>';
}

include_once 'sw-mod/sw-footer.php';
} }?>