<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
} else{
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
require_once'../../../sw-library/sw-function.php';

$filterParts = [];
$tahun_pelajaran = isset($_POST['tahun_pelajaran']) ? $_POST['tahun_pelajaran'] : $year;
$bulan = isset($_POST['bulan']) ? $_POST['bulan'] : $month;
$tahun = isset($_POST['tahun']) ? $_POST['tahun'] : $year;
$filterParts[] = "tahun_pelajaran='$tahun_pelajaran' AND bulan='$bulan' AND tahun='$tahun'";

if (!empty($_POST['user'])) {
    $user = htmlentities($_POST['user']);
    $filterParts[] = "employees_id='$user'";
}

if (!empty($_POST['status'])) {
    $status = htmlentities($_POST['status']);
    $filterParts[] = "status='$status'";
}

$filter   = 'WHERE ' . implode(' AND ', $filterParts);

$aColumns = ['pembayaran_spp_id','admin_id', 'order_id', 'employees_id', 'tahun_pelajaran','bulan', 'tahun', 'nominal', 'tanggal', 'time','status'];
$sIndexColumn = "pembayaran_spp_id";
$sTable = "pembayaran_spp";
$gaSql['user'] = DB_USER;
$gaSql['password'] = DB_PASSWD;
$gaSql['db'] = DB_NAME;
$gaSql['server'] = DB_HOST;

    $gaSql['link'] =  new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db']);

    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
    {
        $sLimit = "LIMIT ".mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayStart']).", ".
            mysqli_real_escape_string($gaSql['link'], $_GET['iDisplayLength']);
    }

    $sOrder = "ORDER BY pembayaran_spp_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY pembayaran_spp_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY pembayaran_spp_id DESC")
        {
            $sOrder = "ORDER BY pembayaran_spp_id DESC";
        }
    }

    $sWhere = "$filter";
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != ""){
        $sWhere = "WHERE (";
        for ($i=0; $i<count($aColumns); $i++)
        {
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch'])."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    for ($i=0 ; $i<count($aColumns); $i++)
    {
        if (isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
        {
            if ($sWhere == "")
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_'.$i])."%' ";
        }
    }

    $sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM $sTable
        $sWhere
        $sOrder
        $sLimit ";
    $rResult = mysqli_query($gaSql['link'], $sQuery);

    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultTotal = mysqli_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array( 
       // "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
    while ($aRow = mysqli_fetch_array($rResult)){$no++;
      extract($aRow);
        $row = array();

        $query  ="SELECT id,employees_name FROM employees WHERE id='$aRow[employees_id]'";
        $result = $connection->query($query);
        $data_pegawai = $result->fetch_assoc();

        $query_spp  ="SELECT tahun,nominal FROM spp WHERE tahun='$aRow[tahun]' AND status='Y'";
        $result_spp = $connection->query($query_spp);
        if($result_spp->num_rows > 0){
          $data_spp = $result_spp->fetch_assoc();
          $tunggakan = $data_spp['nominal'];
        }else{
          $tunggakan = 0;
        }

        $query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$aRow[tahun_pelajaran]'";
        $result_tahun = $connection->query($query_tahun);
        if($result_tahun->num_rows > 0){
            $data_tahun = $result_tahun->fetch_assoc();
            $tahun = ''.$data_tahun['tahun_mulai'].' - '.$data_tahun['tahun_selesai'].'';
        }else{
            $tahun = 'Tahun Pelajaran tidak ada';
        }

        /** Transaksi */
        if($aRow['status'] == 'pending'){
            $status = '<span class="badge-pembayaran'.htmlspecialchars($aRow['pembayaran_spp_id']).' btn btn-warning btn-xs" style="float:left">Pending</span>';
        }elseif($aRow['status'] == 'berhasil'){
            $status = '<span class="badge-pembayaran'.htmlspecialchars($aRow['pembayaran_spp_id']).' btn btn-primary btn-xs" style="float:left">Berhasil</span>';
        }else{
            $status = '<span class="badge-pembayaran'.htmlspecialchars($aRow['pembayaran_spp_id']).' btn btn-danger btn-xs" style="float:left">Batal</span>';
        }


        for ($i=1 ; $i<count($aColumns) ; $i++){
            $onlick = "','";
            $onlick = explode(",",$onlick);
            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = '<b>'.strip_tags($data_pegawai['employees_name']??'').'</b>';
            $row[] = $tahun;
            $row[] = ''.ambilbulan($aRow['bulan']).' s.d '.$aRow['tahun'].'';
            $row[] = 'Rp '.format_angka($aRow['nominal']).'';
            $row[] = tanggal_ind($aRow['tanggal']);
            $row[] = ''.$status.' <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle btn-xs" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <li><a class="btn-stts-pembayaran '.($aRow['status'] == 'pending' ? 'active' : '').'" href="javascript:void(0)" data-id="'.$aRow['pembayaran_spp_id'].'" data-status="pending">Pending</a></li>

                            <li><a class="btn-stts-pembayaran '.($aRow['status'] == 'berhasil' ? 'active' : '').'" href="javascript:void(0)" data-id="'.$aRow['pembayaran_spp_id'].'" data-status="berhasil">Berhasil</a></li>

                            <li><a class="btn-stts-pembayaran '.($aRow['status'] == 'gagal' ? 'active' : '').'" href="javascript:void(0)" data-id="'.$aRow['pembayaran_spp_id'].'" data-status="gagal" >Gagal</a></li>
                        </ul>
                        </div>';
            $row[] = '<a href="javascript:void(0)" class="btn btn-warning btn-md btn-print"  data-order="'.htmlspecialchars($aRow['order_id']??'-').'">
                   <i class="fa fa-print" aria-hidden="true"></i>
                </a>
                <a href="javascript:void(0)" class="btn btn-danger btn-md btn-delete"  title="Hapus" data-id="'.htmlentities(epm_encode($aRow['pembayaran_spp_id'])).'">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
  
}