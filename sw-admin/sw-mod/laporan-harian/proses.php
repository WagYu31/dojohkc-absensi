<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else {
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php'); 

switch (@$_GET['action']){
/* -------  LOAD LAPORAN ----------*/
case 'laporan':

      if(!empty($_POST['name'])){
        $name     = strip_tags($_POST['name']);
        $filter_employees ="WHERE employees.id='$name'";
        $pagination ="WHERE employees.id='$name'";
      }else{
        $filter_employees ='';
        $pagination ='';
      }

      if(isset($_POST['month']) OR isset($_POST['year'])){
          $bulan      = date ($_POST['month']);
          $tahun      = $_POST['year'];
      } 
      else{
          $bulan  = date ("m");
          $tahun      = date("Y");
      }
        $hari       = date("d");
        $jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));
        $s          = date ("w", mktime (0,0,0,$bulan,1,$tahun));

      
      echo'
        <div class="table-responsive" style="overflow-x: auto!important;">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th rowspan="2" width="40" class="text-center" style="vertical-align: middle;">No</th>
              <th colspan="2" rowspan="2" style="vertical-align: middle;">Nama Atlet</th>
              <th class="text-center" colspan="'.$jumlahhari.'">'.ambilbulan($bulan).'</th>
              <th class="text-center" colspan="4">Keterangan</th>
            </tr>
            <tr>';
            $sum = 0;
            $libur = 0;
            for ($d=1;$d<=$jumlahhari;$d++) {
                $warna      = '';
                $background = '';
                $status_hadir     = 'Tidak Hadir';
                $date_month_year = ''.$tahun.'-'.$bulan.'-'.$d.'';
                $hari_libur     = date('D',strtotime($date_month_year));

                /** Menentukan Hari Libur Umum */
                $query_sabtu ="SELECT shift_name FROM shift WHERE shift_name='Sabtu' AND active='N'";
                $result_sabtu= $connection->query($query_sabtu);
                if($result_sabtu->num_rows >0 ){
                  $sabtu = 'Sat';
                }else{
                  $sabtu ='1';
                }
            
                $query_minggu ="SELECT shift_id FROM shift WHERE shift_name='Minggu' AND active='N'";
                $result_minggu = $connection->query($query_minggu);
                if($result_minggu->num_rows >0 ){
                  $minggu = 'Sun';
                }else{
                  $minggu ='2';
                }

              if($hari_libur == $sabtu OR $hari_libur == $minggu){
                    $warna='color:white';
                    $background ='background:#FF0000';
                    $sum++;
                    $status_hadir ='Libur';
                }else{
                    $query_holiday="SELECT holiday_date,description FROM holiday WHERE holiday_date='$date_month_year'";
                    $result_holiday = $connection->query($query_holiday);
                      if($result_holiday->num_rows > 0){
                        $data_holiday = $result_holiday->fetch_assoc();
                        $warna='color:white';
                        $background ='background:#FF0000';
                        $libur++;
                        $status_hadir = $data_holiday['description'];
                      }
                }

              echo'
              <th width="50" class="text-center" style="'.$warna.'; '.$background.'">'.$d.'</th>';
            }
            echo'
            <th width="50" class="text-center">H</th>
            <th width="50" class="text-center">A</th>
            <th width="50" class="text-center">I</th>
            <th width="50" class="text-center">C</th>
            <th width="50" class="text-center">S</th>
            </tr>
          </thead>
          <tbody>';
          $limit =20; 
          if(isset($_GET['halaman'])){
            $halaman = mysqli_real_escape_string($connection,$_GET['halaman']);}
          else{$halaman = 1;} $offset = ($halaman - 1) * $limit;

          $query ="SELECT id,employees_name FROM employees $filter_employees ORDER BY id ASC LIMIT $offset, $limit";
          $result = $connection->query($query);$no=0;
          $nomor = $halaman+1;
          while ($row = $result->fetch_assoc()){$no++;
            echo'
            <tr>
              <td rowspan="2" class="text-center">'.$no.'</td>
              <td rowspan="2" width="150">'.$row['employees_name'].'</td>
              <td width="60">Masuk</td>';
               for ($d=1;$d<=$jumlahhari;$d++) {
                $date_month_year = ''.$tahun.'-'.$bulan.'-'.$d.'';
                 if(isset($_POST['month']) OR isset($_POST['year'])){
                    $month = $_POST['month'];
                    $year  = $_POST['year'];
                    $filter ="presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
                  } 
                  else{
                    $filter ="presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
                  }

                $query_absen ="SELECT presence_id,presence_date,time_in,kehadiran FROM presence WHERE $filter AND employees_id='$row[id]' ORDER BY presence_id DESC";
                $result_absen = $connection->query($query_absen);
                if($result_absen->num_rows > 0) {
                  $row_absen = $result_absen->fetch_assoc();

                  if($row_absen['kehadiran'] =='Hadir'){
                    echo'
                    <td class="text-center">'.$row_absen['time_in'].'</td>';
                  }else{
                    echo'
                    <td class="text-center"><span class="label label-warning">'.$row_absen['kehadiran'].'</label></td>';
                  }
                }else{
                  echo'
                  <td class="text-center"><span class="text-red"><i class="fa fa-times" aria-hidden="true"></i></span></td>';
                }
               }

             if(isset($_POST['month']) OR isset($_POST['year'])){
                $month = $_POST['month'];
                $year  = $_POST['year'];
                $filter ="MONTH(presence_date)='$month' AND year(presence_date)='$year'";
              } 
              else{
                $filter ="MONTH(presence_date)='$month' AND year(presence_date)='$year'";
              }

              $query_hadir="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Hadir' AND employees_id='$row[id]' ORDER BY presence_id DESC";
              $hadir= $connection->query($query_hadir);
        
              $query_sakit="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Sakit' AND employees_id='$row[id]' ORDER BY presence_id";
              $sakit = $connection->query($query_sakit);
        
              $query_izin="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Izin' AND employees_id='$row[id]' ORDER BY presence_id";
              $izin = $connection->query($query_izin);
        
              $query_cuty="SELECT presence_id FROM presence WHERE $filter AND kehadiran='Cuti' AND employees_id='$row[id]'";
              $cuty = $connection->query($query_cuty);
        
              $query_telat ="SELECT presence_id FROM presence WHERE $filter AND status_in='Telat' AND employees_id='$row[id]'";
              $telat = $connection->query($query_telat);
              
              $alpha = $jumlahhari - $hadir->num_rows - $cuty->num_rows - $izin->num_rows - $sum - $libur;

            echo'
            <td width="50" rowspan="2" class="text-center">'.$hadir->num_rows.'</td>
            <td width="50" rowspan="2" class="text-center">'.$alpha.'</td>
            <td width="50" rowspan="2" class="text-center">'.$izin->num_rows.'</td>
            <td width="50" rowspan="2" class="text-center">'.$cuty->num_rows.'</td>
            <td width="50" rowspan="2" class="text-center">'.$sakit->num_rows.'</td>
            </tr>
            <tr>
              <td width="60">Pulang</td>';
              for ($d=1;$d<=$jumlahhari;$d++) {
                $date_month_year = ''.$tahun.'-'.$bulan.'-'.$d.'';
                 if(isset($_POST['month']) OR isset($_POST['year'])){
                    $month = $_POST['month'];
                    $year  = $_POST['year'];
                    $filter ="presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
                  } 
                  else{
                    $filter ="presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
                  }

                $query_absen ="SELECT presence_id,presence_date,time_out FROM presence WHERE $filter AND employees_id='$row[id]' ORDER BY presence_id DESC";
                $result_absen = $connection->query($query_absen);

                if($result_absen->num_rows > 0){
                  $row_absen = $result_absen->fetch_assoc();
                  if($row_absen['time_out']=='00:00:00'){
                    $jam_pulang ='<span class="text-red"><i class="fa fa-times" aria-hidden="true"></i></span>';
                  }else{
                    $jam_pulang = $row_absen['time_out'];
                  }
                echo'
                  <td class="text-center">'.$jam_pulang.'</td>';
                }else{
                  echo'<td class="text-center"><span class="text-red"><i class="fa fa-times" aria-hidden="true"></i></span></td>';
                }
              }
              echo'
            </tr>';
          }
          echo'
          </tbody>
      </table>
        <p><span class="label label-info">H: Hadir</span> <span class="label label-success">I: Izin</span> <span class="label label-danger">A: Alpha</span> <span class="label label-warning">C: Cuti</span> <span class="label label-warning">S: Sakit</span></p>
          <nav>
            <ul class="pagination justify-content-center">';
                    $query = mysqli_query($connection,"SELECT COUNT(id) AS jumData FROM employees $pagination") or die ('Pagination error');
                      $data  = mysqli_fetch_assoc($query);
                      $jumData = $data['jumData'];
                      $jumPage = ceil($jumData/$limit);
                  //menampilkan link << Previou
                  if ($halaman > 1){echo '<li class="page-item"><a class="page-link btn-pagination" href="javascript:void(0);" data-id="'.($halaman-1).'">«</a></li>';}
                  //menampilkan urutan paging
                      for($i = 1; $i <= $jumPage; $i++){
                  //mengurutkan agar yang tampil i+3 dan i-3
                      if ((($i >= $halaman - 1) && ($i <= $halaman + 4)) || ($i == 1) || ($i == $jumPage)){
                          if($i==$jumPage && $halaman <= $jumPage-4)
                              echo'<li class="disabled"><a href="#">..</a></li>';
                              if ($i == $halaman) echo '<li class="active"><a href="#">'.$i.'</a></li>';
                              else echo '<li class="page-item"><a class="page-link btn-pagination"  href="javascript:void(0)" data-id="'.$i.'">'.$i.'</a></li>';
                      if($i==1 && $halaman >= 4) echo '<li class="disabled"><a href="#">..</a></li>';
                  }}
                  //menampilkan link Next >>
                  if ($halaman < $jumPage){echo'<li class="page-item"><a class="page-link btn-pagination" href="javascript:void(0);" data-id="'.($halaman+1).'">»</a></li>';
                  }

            echo'
            </ul>
          </nav>

        <div>';


break;

}

}
