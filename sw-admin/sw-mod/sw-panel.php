<?php if(empty($connection)){
  header('location:./404');
} else {

echo'<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="slimScrollDiv">
    <section class="sidebar">
      <!-- Sidebar user panel -->
    
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>';
      if($mod =='home'){echo'<li class="active">'; }else{echo'<li>';}
        echo'<a href="./"><i class="fa fa-home"></i><span>Dashboard</span></a></li>';
  
      if($mod =='karyawan' OR $mod=='jabatan' OR $mod=='shift' OR $mod=='lokasi' OR $mod=='libur' OR $mod=='tahun-ajaran'){echo'<li class="active treeview">'; }else{
        echo'<li class="treeview">';
      }

      if($level_user =='1'){
      echo'
          <a href="#">
            <i class="fa fa-database"></i> <span>Master Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">';
            if($mod =='karyawan'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./karyawan"><i class="fa fa-circle-o"></i> Data Atlet</a></li>';
            if($mod =='jabatan'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./jabatan"><i class="fa fa-circle-o"></i> Data Sabuk</a></li>';
            if($mod =='shift'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="shift"><i class="fa fa-circle-o"></i> Data Jam Latihan</a></li>';
            if($mod =='lokasi'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./lokasi"><i class="fa fa-circle-o"></i> Data Lokasi</a></li>';
            if($mod =='libur'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./libur"><i class="fa fa-circle-o"></i>Libur Nasional</a></li>';
            if($mod =='tahun-ajaran'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./tahun-ajaran"><i class="fa fa-circle-o"></i>Tahun Ajaran</a></li>
          </ul>
        </li>';
     }

      if($mod =='spp' OR $mod=='pembayaran-spp' OR $mod=='laporan-spp'){ echo'<li class="active treeview">'; 
      }else{echo'<li class="treeview">';}

      if($level_user =='1'){
      echo'
          <a href="#">
            <i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span>SPP</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">';
            if($mod =='spp'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./spp"><i class="fa fa-circle-o"></i> Master SPP</a></li>';
            if($mod =='pembayaran-spp'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./pembayaran-spp"><i class="fa fa-circle-o"></i> Pembayaran SPP</a></li>';
             if($mod =='laporan-spp'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./laporan-spp"><i class="fa fa-circle-o"></i> Laporan SPP</a></li>
          </ul>
        </li>';
     }


     if($mod =='jadwal-lomba' OR $mod=='informasi' OR $mod=='poster' OR $mod=='galeri'){ echo'<li class="active treeview">'; 
      }else{echo'<li class="treeview">';}

      if($level_user =='1'){
      echo'
          <a href="#">
            <i class="fa fa-file-text" aria-hidden="true"></i> <span>Informasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">';
            if($mod =='jadwal-lomba'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./jadwal-lomba"><i class="fa fa-circle-o"></i> Jadwal Lomba/Latihan</a></li>';
            if($mod =='informasi'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./informasi"><i class="fa fa-circle-o"></i> Informasi</a></li>';
            if($mod =='poster'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./poster"><i class="fa fa-circle-o"></i> Poster Rekrutmen</a></li>';
            if($mod =='galeri'){echo'<li class="active">'; }else{echo'<li>';}
             echo'<a href="./galeri"><i class="fa fa-circle-o"></i> Galeri Media</a></li>
          </ul>
        </li>';
     }


     
      if($mod =='izin'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./izin"><i class="fa fa-calendar" aria-hidden="true"></i> <span>Data Izin</span></a></li>';

      if($mod =='cuty'){echo'<li class="active">'; }else{echo'<li>';}
      echo'<a href="./cuty"><i class="fa fa-calendar" aria-hidden="true"></i> <span>Data Permohonan Cuti</span></a></li>';

      if($mod =='absensi' OR $mod=='laporan-harian'){ echo'<li class="active treeview">'; 
      }else{echo'<li class="treeview">';}
      echo'
        <a href="#">
          <i class="fa fa-print" aria-hidden="true"></i> <span>Laporan Absensi</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>

        <ul class="treeview-menu">';
          if($mod =='spp'){echo'<li class="active">'; }else{echo'<li>';}
            echo'<a href="./absensi"><i class="fa fa-circle-o"></i> Laporan Absensi</a></li>';
          if($mod =='laporan-harian'){echo'<li class="active">'; }else{echo'<li>';}
            echo'<a href="./laporan-harian"><i class="fa fa-circle-o"></i> Laporan Harian</a></li>
        </ul>
      </li>';

      if($level_user =='1'){

        if($mod =='setting'){echo'<li class="active">'; }else{echo'<li>';}
          echo'<a href="./setting"><i class="fa fa-cogs" aria-hidden="true"></i> <span>Pengaturan Web</span></a></li>';

        if($mod =='landing-setting'){echo'<li class="active">'; }else{echo'<li>';}
          echo'<a href="./landing-setting"><i class="fa fa-desktop" aria-hidden="true"></i> <span>Landing Page</span></a></li>';

        if($mod =='user'){echo'<li class="active">'; }else{echo'<li>';}
        echo'<a href="./user"><i class="fa fa-user"></i> <span>Akun Pengurus Dojo</span></a></li>';
  	  }?>
      
      <li><a href="javascript:void();" onClick="location.href='./logout';"><i class="fa fa-sign-out text-red"></i>  <span>Keluar</span></a></li>
      <?php echo'
      </ul>
    </section>
  </div>
</aside>';
}?>