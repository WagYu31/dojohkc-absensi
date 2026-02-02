<?php if($connection){
require_once './sw-library/midtrans.php';
include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
 header("location:./");
}else{
   if (isset($_COOKIE['order'])) {
        $order_id = $_COOKIE['order'];
    }
echo'
<div id="appCapsule">
    <div class="section">
        <div class="splash-page mt-5 mb-5">
            <div class="iconbox bg-danger mb-3">
               <i class="fas fa-times"></i>
            </div>
            <h2 class="mb-2">Pembayaran Gagal</h2>
            <p>Pembayaran untuk ID ['.$order_id.'] tidak dapat diproses.<br>
                Silakan periksa kembali dan coba lagi.
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-12 text-center">
            <a href="./spp" class="btn btn-lg btn-success">Kembali</a>
        </div>
    </div>
</div>';
include_once 'sw-mod/sw-footer.php';
} }?>