<?php
if(empty($connection)){
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';

echo'
  <div class="content-wrapper">';
switch(@$_GET['op']){ 
    default:
echo'
<section class="content-header">
  <h1>Landing Page Settings</h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Landing Page Settings</li>
    </ol>
</section>';
echo'
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_hero" data-toggle="tab" onclick="loadTabHero();"><i class="fa fa-star"></i> Hero</a></li>
              <li><a href="#tab_about" data-toggle="tab" onclick="loadTabAbout();"><i class="fa fa-info-circle"></i> Tentang Kami</a></li>
              <li><a href="#tab_features" data-toggle="tab" onclick="loadTabFeatures();"><i class="fa fa-th"></i> Fitur</a></li>
              <li><a href="#tab_cta" data-toggle="tab" onclick="loadTabCta();"><i class="fa fa-bullhorn"></i> CTA & Footer</a></li>
              <li><a href="#tab_poster" data-toggle="tab" onclick="loadTabPoster();"><i class="fa fa-image"></i> Poster</a></li>
              <li><a href="#tab_galeri" data-toggle="tab" onclick="loadTabGaleri();"><i class="fa fa-film"></i> Galeri</a></li>
              <li><a href="#tab_atlet" data-toggle="tab" onclick="loadTabAtlet();"><i class="fa fa-trophy"></i> Atlet</a></li>
            </ul>
            <div class="tab-content">
              <div id="loadLanding">

              </div>
            </div>
          </div>

      
</div>
</section>';
break;
}?>

</div>
<?php }?>
