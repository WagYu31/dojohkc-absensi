<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

namespace Midtrans;
require_once dirname(__FILE__) . '/../../Midtrans.php';
\Midtrans\Config::$serverKey = 'SB-Mid-server-w5Xxkm7I4xn9kYbAHghNd9v5';
\Midtrans\Config::$clientKey = 'SB-Mid-client-oGWKLVgpk_o-Ccv1';
\Midtrans\Config::$isProduction = false; // Gunakan true jika Anda sudah di production
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;


// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Required

include "../../../koneksi.php";
$order_id = $_GET['order_id'];

// Query untuk menampilkan data siswa berdasarkan NIS yang dikirim
$query = "SELECT * FROM peserta WHERE order_id='".$order_id."'";
$sql = mysqli_query($koneksi, $query);  // Eksekusi/Jalankan query dari variabel $query
$data = mysqli_fetch_array($sql);

$nama = $data['nama'];
$email = $data['email'];
$biaya =$data['biaya'];
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' =>  $biaya, // no decimal allowed for creditcard
);
// Optional
$item_details = array(
    array(
        'id' => 'a1', // ID untuk item pertama
        'price' => $biaya, // Harga item (pastikan ini adalah nilai yang benar dari variabel $biaya)
        'quantity' => 1, // Jumlah item yang dibeli
        'name' => 'PEMBAYARAN PPDB ONLINE', // Nama item
        'category' => 'PPDB',
        'merchant_name' => 'SMAN 1 Bandar LAmpung', // Nama penyedia layanan
        'description' => 'Biaya pendaftaran  PPDB untuk peserta.', // Deskripsi dari item
        'type' => 'service', // Tipe item: 'service' atau 'product'
        'sku' => 'ppdb2025', // Kode unik untuk item
    ),
);
// Optional
$customer_details = array(
    'first_name'    => "$nama",
    'last_name'     => "",
    'email'         => "$email",
    'phone'         => "083160901108",
    'billing_address'  => 'Lampung, Bandar Lampung',

);
// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
}
catch (\Exception $e) {
    echo $e->getMessage();
}


function printExampleWarningMessage() {
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<server key>\';');
        die();
    } 
}
echo "<pre>Snap Token: $snap_token</pre>";
?>

<!DOCTYPE html>
<html>
      <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAYMENT </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
    <body>
        <br>
        <br>
            <div class="container">
<div class="card">
  <div class="card-body">
      <p>Registrasi Berhasil, Selesaikan Pembayaran Sekarang</p>
        <button id="pay-button" class="btn btn-primary">PILIH METODE PEMBAYARAN</button>
    
        <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey;?>"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // SnapToken acquired from previous step
                snap.pay('<?php echo $snap_token?>');
            };

            console.log("Snap token:", "<?php echo $snap_token?>");
            snap.pay('<?php echo $snap_token?>', {
            onSuccess: function(result) {
                alert('Pembayaran berhasil');
                console.log(result);
            },
            onPending: function(result) {
                alert('Menunggu pembayaran');
                console.log(result);
            },
            onError: function(result) {
                alert('Terjadi kesalahan');
                console.log(result);
            },
            onClose: function() {
                alert('Kamu menutup popup tanpa menyelesaikan pembayaran');
            }
        });
        </script>
          </div>
          </div>
          </div>
        
  </body>
</html>
</html>
