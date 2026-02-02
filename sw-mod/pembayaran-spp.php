<?php if($connection){
require_once './sw-library/midtrans.php';
include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
 header("location:./");
}else{
    if (empty($_GET['jum']) || !is_numeric($_GET['jum']) || empty($_GET['tahun_ajaran'])) {
        header("location:./spp");
    }else{
        
        function prosesPembayaran($connection, $user_id, $order_id, $total_tagihan, $date, $time,  $bulan, $tahun, $tahun_pelajaran) {
            $user_id = htmlentities($user_id, ENT_QUOTES, 'UTF-8');
            // Cek status pembayaran di bulan dan tahun ini
            $sql = "SELECT status, order_id FROM pembayaran_spp 
                    WHERE employees_id= '$user_id' AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun ORDER BY tanggal DESC LIMIT 1";
            $result = mysqli_query($connection, $sql);

            if ($result) {
                $last_status_data = $result->num_rows > 0 ? mysqli_fetch_assoc($result) : null;
                $last_status = $last_status_data['status'] ?? null;

                // Hitung jumlah pembayaran berhasil
                $query_berhasil = "SELECT COUNT(*) as total FROM pembayaran_spp WHERE employees_id = '$user_id' AND MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun AND status='Berhasil'";
                $result_berhasil = $connection->query($query_berhasil);
                $row_berhasil = $result_berhasil->fetch_assoc();
                $jumlah_berhasil = (int)$row_berhasil['total'];
                
                switch ($last_status) {
                    case 'berhasil':
                        return [
                            'notifikasi' => '<div class="alert alert-success">Anda sudah melakukan pembayaran.</div>',
                            'jumlah_berhasil' => $jumlah_berhasil,
                            'order_id' => $last_status_data['order_id'] ?? null
                        ];
                    case 'cancel':
                        return [
                            'notifikasi' => '<div class="alert alert-warning">Pembayaran Anda sebelumnya dibatalkan. Silakan lakukan pembayaran ulang.</div>',
                            'jumlah_berhasil' => $jumlah_berhasil,
                            'order_id' => $last_status_data['order_id'] ?? null
                        ];
                    case 'batal':
                        return [
                            'notifikasi' => '<div class="alert alert-danger">Pembayaran Anda sebelumnya dibatalkan secara sistem. Silakan hubungi admin.</div>',
                            'jumlah_berhasil' => $jumlah_berhasil,
                            'order_id' => $last_status_data['order_id'] ?? null
                        ];
                    case 'pending':
                        // Lanjut ke pengecekan dan update
                        break;
                }
            }

            // Cek apakah ada transaksi pending yang belum selesai
            $query_check = "SELECT order_id FROM pembayaran_spp 
                            WHERE MONTH(tanggal) =$bulan AND YEAR(tanggal) = $tahun AND employees_id='$user_id' AND status='pending' 
                            ORDER BY tanggal DESC LIMIT 1";
            $result_check = mysqli_query($connection, $query_check);

            if ($result_check && mysqli_num_rows($result_check) > 0) {
                $pending_data = mysqli_fetch_assoc($result_check);
                $order_id_pending = $pending_data['order_id'];

                $update = "UPDATE pembayaran_spp 
                    SET order_id='$order_id', nominal=$total_tagihan, tanggal='$date', time='$time' 
                    WHERE MONTH(tanggal) = $bulan AND YEAR(tanggal) = $tahun AND employees_id='$user_id' AND status='pending'";
                if (mysqli_query($connection, $update)) {
                    $notifikasi = '<div class="alert alert-info">Segera lakukan pembayaran!</div>';
                    setcookie('order', $order_id, time() + (7 * 24 * 60 * 60), '/');
                } else {
                    $notifikasi = '<div class="alert alert-danger">Gagal memperbarui data pembayaran: ' . mysqli_error($connection) . '</div>';
                }

                return [
                    'notifikasi' => $notifikasi,
                    'jumlah_berhasil' => $jumlah_berhasil,
                    'order_id' => $order_id_pending
                ];
            } else {
                // Insert pembayaran baru
                $query_angsuran = "SELECT COUNT(*) as total FROM pembayaran_spp 
                WHERE employees_id='$user_id' AND tahun_pelajaran='$tahun_pelajaran'";
                $result = mysqli_query($connection, $query_angsuran);
                $data = mysqli_fetch_assoc($result);
                $angsuran_ke = $data['total'] + 1;
                $insert = "INSERT INTO pembayaran_spp (
                    employees_id, order_id, tahun_pelajaran, bulan, tahun, nominal, angsuran_ke, 
                    metode_pembayaran, tanggal, time, status
                ) VALUES (
                    '$user_id', '$order_id', '$tahun_pelajaran', '$bulan', '$tahun', '$total_tagihan', 
                    '$angsuran_ke', 'Pembayaran Online', '$date', '$time', 'pending'
                )";

                if (mysqli_query($connection, $insert)) {
                    $notifikasi = '<div class="alert alert-info">Segera lakukan pembayaran!</div>';
                    setcookie('order', $order_id, time() + (7 * 24 * 60 * 60), '/');
                } else {
                    $notifikasi = '<div class="alert alert-danger">Gagal menyimpan data pembayaran: ' . mysqli_error($connection) . '</div>';
                }

                return [
                    'notifikasi' => $notifikasi,
                    'jumlah_berhasil' => $jumlah_berhasil,
                    'order_id' => $order_id
                ];
            }
        }

        
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
                $update = "UPDATE pembayaran_spp 
                SET status='Berhasil' WHERE MONTH(tanggal) =$month AND YEAR(tanggal) =$year AND employees_id='$row_user[id]' AND tahun_pelajaran='$tahun_pelajaran'";
                $connection->query($update);
            } else {
                $pembayaran_success = 'N';
            }
        }

        /** Cek Pembayaran Secara Otomatis */
        $order_id   = ''.htmlentities($row_user['id']).rand();
        $total_tagihan = strip_tags($_GET['jum']??'0');
        $tahun_pelajaran = epm_decode($_GET['tahun_ajaran']??'0');

        $transaction_details = array(
            'order_id' => $order_id,
            'gross_amount' =>  $total_tagihan, // no decimal allowed for creditcard
        );
        // Optional
        $item_details = array(
            array(
                'id' => $order_id, // ID untuk item pertama
                'price' => $total_tagihan, // Harga item (pastikan ini adalah nilai yang benar dari variabel $biaya)
                'quantity' => 1, // Jumlah item yang dibeli
                'name' => 'Pembayaran SPP '.$site_name.'', // Nama item
                'category' => 'spp',
                'merchant_name' => strip_tags($site_name), // Nama penyedia layanan
                'description' => 'Pembayaran SPP', // Deskripsi dari item
                'type' => 'product', // Tipe item: 'service' atau 'product'
                //'sku' => 'ppdb2025',
            ),
        );
        // Optional

        $customer_details = array(
            'first_name'    => htmlspecialchars($row_user['employees_name']??''),
            'last_name'     => "",
            'email'         => strip_tags($row_user['employees_email']??''),

        );
        // Fill transaction details
        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );
        $snap_token = '';
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    }

    $result = prosesPembayaran($connection, $row_user['id'], $order_id, $total_tagihan, $date, $time, $month, $year, $tahun_pelajaran);
}

echo'
<div id="appCapsule">
    <div class="container">
        <div class="bill-box">
            <div class="img-wrapper">
            <h3>Jumlah Pembayaran</h3>
            </div>
            <div class="price">'.format_angka($total_tagihan).'</div>
        </div>
        <div class="text-center mt-3">
         <a href="./spp" class="btn btn-success">Kembali</a>
        </div>
    </div>
</div>';
  include_once 'sw-mod/sw-footer.php';
echo'
<script src="'.$payment_js.'" data-client-key="'.$payment_client.'"></script>';?>
<script type="text/javascript">
    snap.pay('<?php echo $snapToken?>', {
        // Optional
        onSuccess: function(result){
            window.location = "./pembayaran-success";
        },
        onPending: function(result){
            window.location = "./pembayaran-gagal";
        },
        onError: function(result){
             window.location = "./pembayaran-gagal";
        }
    });
</script>
<?php 
} ?>