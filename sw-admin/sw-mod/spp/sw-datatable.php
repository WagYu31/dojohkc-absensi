<?php
session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;
}else {

    require_once'../../../sw-library/sw-config.php';
    require_once'../../../sw-library/sw-function.php';

    $aColumns = ['spp_id', 'tahun_pelajaran', 'tahun','nominal','status'];
    $sIndexColumn = "spp_id";
    $sTable = "spp";
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

    $sOrder = "ORDER BY spp_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY spp_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY spp_id DESC")
        {
            $sOrder = "ORDER BY spp_id DESC";
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

        if($aRow['status'] =='Y'){
            $status = '<label class="custom-toggle" style="display:inline-block">
                <input type="checkbox" class="btn-active active'.$aRow['spp_id'].'" data-id="'.$aRow['spp_id'].'" data-active="Y" checked>
                    <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';

        }else{
            $status = '<label class="custom-toggle" style="display:inline-block">
            <input type="checkbox" class="btn-active active'.$aRow['spp_id'].'"  data-id="'.$aRow['spp_id'].'"  data-active="N">
            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';
        }

        $query_tahun ="SELECT tahun_mulai,tahun_selesai FROM tahun_pelajaran WHERE tahun_pelajaran_id='$aRow[tahun_pelajaran]'";
        $result_tahun = $connection->query($query_tahun);
        if($result_tahun->num_rows > 0){
            $data_tahun = $result_tahun->fetch_assoc();
            $tahun = ''.$data_tahun['tahun_mulai'].' s/d '.$data_tahun['tahun_selesai'].'';
        }else{
            $tahun = 'Tahun Pelajaran tidak ada';
        }

        for ($i=1 ; $i<count($aColumns) ; $i++){
            $onlick = "','";
            $onlick = explode(",",$onlick);

            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = $tahun;
            $row[] = 'Rp '.format_angka($aRow['nominal']).'';
            $row[] = '<div class="text-center">'.$status.'</td>';
            $row[] = '<div class="text-center">
                <a href="javascript:void(0)" class="btn btn-warning btn-sm btn-update btn-tooltip"  title="Edit" data-id="'.$aRow['spp_id'].'" data-tahun="'.$aRow['tahun_pelajaran'].'" data-nominal="'.$aRow['nominal'].'">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </a>
                 <a href="javascript:void(0)" class="btn btn-danger btn-sm btn-tooltip btn-delete"  title="Hapus" data-id="'.epm_encode($aRow['spp_id']).'">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
            </div>';
        }
        $output['aaData'][] = $row;
   
    }
    echo json_encode($output);
}
