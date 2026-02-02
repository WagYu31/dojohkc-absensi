<?php 
if ($mod ==''){
    header('location:../404');
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
    setcookie('COOKIES_MEMBER', '', 0, '/');
    header("location:./");
}else{
if (isset($_GET['details'])){
    $details = mysqli_real_escape_string($connection,$_GET['details']);
    $blog = str_replace('-',' ',$details);
    $query_artikel ="SELECT * FROM artikel WHERE active='Y' AND artikel_id='$details'"; 
    $result_artikel = $connection->query($query_artikel);
}
echo'
<div id="appCapsule">
    <div class="section mt-2">';
    if($result_artikel->num_rows > 0){
        $data_artikel = $result_artikel->fetch_assoc();

        $statistik = $data_artikel['statistik']+1;
        $update = "UPDATE artikel SET statistik='$statistik' WHERE artikel_id='$data_artikel[artikel_id]'";
        $connection->query($update);
    echo'
        <h3>'.strip_tags($data_artikel['judul']).'</h3>
        <div class="blog-header-info mt-2 mb-2">
            <div>
              <span class="mr-1"><i class="fa fa-user" aria-hidden="true"></i> '.strip_tags($data_artikel['penerbit']).'</span> | 
              <span class="ml-1"><i class="fa fa-calendar" aria-hidden="true"></i> '.tgl_indo($data_artikel['date']).'</span>
            </div>
        </div>
        <div class="lead">
             '.$data_artikel['deskripsi'].'
        </div>';
    }
    echo'
    </div>
    <hr>
    <div class="mt-3 text-center mb-3">
        <a href="./blog" class="btn btn-secondary btn-md">Kembali</a>
    </div>
</div>';
}
  include_once 'sw-mod/sw-footer.php';
} ?>