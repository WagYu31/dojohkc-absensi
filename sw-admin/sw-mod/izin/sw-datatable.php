<?php session_start();
if(empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])){
    header('location:../../login/');
 exit;}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../login/login_session.php';
include('../../../sw-library/sw-function.php');

    $aColumns = ['permission_id','employees_id','date','permission_name','permission_date','permission_date_finish','permission_description','files','type','status'];
    $sIndexColumn = "permission_id";
    $sTable = "permission";
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

    $sOrder = "ORDER BY permission_id DESC";
    if (isset($_GET['iSortCol_0']))
    {
        $sOrder = "ORDER BY permission_id DESC";
        for ($i=0; $i<intval($_GET['iSortingCols']) ; $i++)
        {
            if ($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true")
            {
                $sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i])]."
                    ".mysqli_real_escape_string($gaSql['link'], $_GET['sSortDir_'.$i]) .", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY permission_id DESC")
        {
            $sOrder = "ORDER BY permission_id DESC";
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

        if($level_user==1){
            $update ='
            <div class="btn-group">
            <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Proses
              <span class="caret"></span>
              <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0);" data-id="'.$aRow['permission_id'].'" data-status="1" class="btn-setujui">Setujui</a></li>

              <li><a href="javascript:void(0);" data-id="'.$aRow['permission_id'].'" data-status="2" class="update-status">Tidak disetujui</a></li>
            </ul>
          </div>';


            if(!$aRow['files']==''){
                $download ='<a href="../sw-content/izin/'.$aRow['files'].'" target="_blank" class="btn btn-sm btn-primary" title="Download"><i class="fa fa-download" aria-hidden="true"></i></a>';
            }else{
                $download ='';
            }
            
        $delete = '
            <button class="btn btn-sm btn-danger delete" title="Delete" data-id="'.epm_encode($aRow['permission_id']).'" data-employees="'.epm_encode($aRow['employees_id']).'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
        }
        else{
            $update ='';
        $download ='
            <button type="button" class="btn btn-warning btn-sm access-failed enable-tooltip" title="Download"><i class="fa fa-download" aria-hidden="true"></i></button>';
        $delete ='
            <buton type="button" class="btn btn-sm btn-danger access-failed" title="Hapus"><i class="fa fa-trash-o"></i></button>';
        }

        if($aRow['status'] =='1'){
            $status ='<span class="text-primary">Disetujui</span>';
          }elseif ($aRow['status'] =='2') {
            $status='<span class="text-danger">Tidak Disetujui</span>';
          }else{
            $status='<span class="text-muted">Menunggu</span>';
          }

        $row = array();
        for ($i=1 ; $i<count($aColumns) ; $i++){
            $row[] = '<div class="text-center">'.$no.'</div>';
            $row[] = tgl_indo($aRow['date']);
            $row[] = strip_tags($aRow['permission_name']);
            $row[] = tgl_indo($aRow['permission_date']);
            $row[] = tgl_indo($aRow['permission_date_finish']);
            $row[] = strip_tags($aRow['type']);
            $row[] = strip_tags($aRow['permission_description']);
            $row[] = strip_tags($status);
            $row[] = '<div class="text-center">
                        '.$update.'
                        '.$download.'
                        '.$delete.'
                      </div>';
        }
        $output['aaData'][] = $row;
        $no++;
    }
    echo json_encode($output);
  
}