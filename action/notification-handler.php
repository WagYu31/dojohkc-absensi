<?php namespace Midtrans;
require_once'../sw-library/sw-config.php'; 
require_once'../sw-library/sw-function.php';

require_once'../sw-library/Midtrans/Midtrans.php';
Config::$isProduction = false;
Config::$serverKey = ''.$payment_server.'';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

$json = file_get_contents('php://input');
$payload = json_decode($json, true);

if (!isset($payload['order_id'])) {
    http_response_code(400);
    exit("Invalid Midtrans notification: order_id missing.");
}

$order_id = $payload['order_id'];

try {
    $notif = new Notification();
}
catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

switch ($transaction) {
    case 'capture':
        if ($type == 'credit_card' && $fraud == 'challenge') {
            echo "Transaction order_id: $order_id is challenged by FDS";
        } else {
            echo "Transaction order_id: $order_id successfully captured using $type";
        }
        break;
        case 'settlement':
            $status = 'berhasil';
            break;
        case 'pending':
            $status = 'pending';
            break;
        case 'deny':
            $status = 'deny';
            break;
        case 'expire':
            $status = 'expire';
            break;
        case 'cancel':
            $status = 'cancel';
            break;
        default:
        $status = 'unknown';
}

if ($status !== 'unknown') {

    $query_pembayaran ="SELECT employees_id FROM pembayaran_spp WHERE order_id='$order_id' LIMIT 1";
    $result_pembayaran = $connection->query($query_pembayaran);
    if($result_pembayaran->num_rows > 0){
        $data_pembayaran = $result_pembayaran->fetch_assoc();
        
        $update ="UPDATE pembayaran_spp SET status='$status' WHERE order_id='$order_id'";
        $connection->query($update);

    }else{
        echo'Data tidak ditemukan!';
    }
    
}

function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }   
}
