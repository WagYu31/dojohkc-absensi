<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
} else{
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
require_once'../../../sw-library/sw-function.php';

 $aColumns = ['poster_id', 'judul', 'file', 'active', 'created_at'];
    $sIndexColumn = "poster_id";
    $sTable = "poster";
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

    $sOrder = "ORDER BY poster_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY poster_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])].
                    " ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY poster_id DESC")
        {
            $sOrder = "ORDER BY poster_id DESC";
        }
    }

    $sWhere = "";
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
    {
        $sWhere = "WHERE (";
        for ($i=0; $i<count($aColumns); $i++)
        {
            $sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($gaSql['link'], $_GET['sSearch'])."%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
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

    $sQuery = "SELECT COUNT(".$sIndexColumn.") FROM $sTable";
    $rResultTotal = mysqli_query($gaSql['link'], $sQuery);
    $aResultTotal = mysqli_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];

    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    $no = 0;
    while ($aRow = mysqli_fetch_array($rResult)){$no++;
        $row = array();

        // Thumbnail
        if(!empty($aRow['file']) && file_exists('../../../sw-content/poster/'.$aRow['file'])){
          $row[] = '<img src="../sw-content/poster/'.strip_tags($aRow['file']).'" height="60" style="border-radius:6px;">';
        }else{
          $row[] = '<span class="text-muted">No image</span>';
        }

        // Judul
        $row[] = '<b>'.strip_tags($aRow['judul']).'</b>';

        // Tanggal
        $row[] = date('d M Y', strtotime($aRow['created_at']));

        // Status
        if($aRow['active'] == 'Y'){
          $row[] = '<span class="label label-success">Aktif</span>';
        }else{
          $row[] = '<span class="label label-default">Nonaktif</span>';
        }

        // Aksi
        $row[] = '<div class="text-center">
                <a href="./poster&op=update&id='.epm_encode($aRow['poster_id']).'" class="btn btn-warning btn-sm btn-tooltip" title="Edit">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                 <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-tooltip btn-delete" title="Hapus" data-id="'.epm_encode($aRow['poster_id']).'">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
            </div>';

        $output['aaData'][] = $row;
    }
    echo json_encode($output);

}
