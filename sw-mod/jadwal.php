<?php 
if ($mod ==''){
    header('location:../404');
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])){
    setcookie('COOKIES_MEMBER', '', 0, '/');
    header("location:./");
}else{

$query_artikel="SELECT artikel_id,judul,domain,foto,deskripsi,date FROM artikel WHERE active='Y' ORDER BY artikel_id DESC";
$result_artikel = $connection->query($query_artikel);
echo'
<div id="appCapsule">
    <div class="section mt-2">
        <div class="section-title">Jadwal Lomba/Latihan</div>
        <div class="row">';
        if($result_artikel->num_rows > 0){
            while ($data_artikel = $result_artikel->fetch_assoc()){
            $judul = strip_tags($data_artikel['judul']);
            if(strlen($judul ) >50)$judul= substr($judul,0,50).'..';
                echo'
                <div class="col-sm-6 col-md-3 mb-2">
                <a href="./blog-'.strip_tags($data_artikel['artikel_id']).'-'.strip_tags($data_artikel['domain']).'">
                    <div class="blog-card">';
                        if(file_exists('./sw-content/artikel/'.$data_artikel['foto'].'')){
                            echo'<img src="./sw-content/artikel/'.$data_artikel['foto'].'" height="180" imaged w-100>';
                        }else{
                            echo'<img src="./sw-content/thumbnail.jpg" height="180" imaged w-100>';
                        }
                        echo'
                        <div class="text">
                            <h4 class="title">'.$judul.'</h4>
                        </div>
                    </div>
                </a>
                </div>';
            }
        }
        echo'   
        </div>
    </div>
</div>';
}
  include_once 'sw-mod/sw-footer.php';
} ?>