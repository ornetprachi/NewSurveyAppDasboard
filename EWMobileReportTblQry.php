<?php
date_default_timezone_set('Asia/Kolkata');
include 'api/includes/DbOperation.php';
session_start();
$db = new DbOperation();
$userName = $_SESSION['SurveyUA_UserName'];
$appName = $_SESSION['SurveyUA_AppName'];
$electionCd = $_SESSION['SurveyUA_Election_Cd'];
$electionName = $_SESSION['SurveyUA_ElectionName'];
$developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
$ULB = $_SESSION['SurveyUtility_ULB'];

$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$RptData = [];
$totalRecords = 0;
$totalFilterRecords = 0 ;
  
$limitClause = "";
if ($length != -1) {
    $limitClause = "OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
}


if(isset($_POST['flag']) && $_POST['flag'] == 'EW'){
    $columnMapping = [
        0 => null,
        1 => 'ExecutiveName',
        2 => 'MobileNo',
        3 => 'DBName',
        4 => 'FamilyNos',
        5 => 'datacnt'
    ];
    
    $orderColumnIndex = $_POST['order'][0]['column'];
    $orderDirection = $_POST['order'][0]['dir'];
    
    $orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'ExecutiveName';
    
    if ($orderColumn === null) {
        $orderClause = "ORDER BY um.ExecutiveName ASC";
    } else {
        $orderClause = "ORDER BY $orderColumn $orderDirection";
    }
    
    $searchCon = "";
    if(!empty($searchValue)){
        $searchCon = "AND (um.ExecutiveName LIKE '%$searchValue%' OR MobileNo LIKE '%$searchValue%')";
    }
    
    $mainQuery = "SELECT 
                    COALESCE(um.ExecutiveName,'') AS ExecutiveName,
                    MobileNo,
                    MAX(um.DbName) AS DBName,
                    COUNT(DISTINCT dw.FamilyNo) AS FamilyNos,
                    COUNT(*) AS datacnt 
                    FROM  
                    Dw_VotersInfo AS dw
                    INNER JOIN ".DB_NAME_USER."..User_Master AS um ON dw.AddedBy = um.Executive_Cd
                    INNER JOIN ".DB_NAME_USER."..Election_Master AS em ON um.ElectionName = em.ElectionName 
                    WHERE 
                    dw.MobileNo <> ''
                    AND LEN(dw.MobileNo) = 10
                    AND em.ULB = '$ULB'
                    AND dw.SF = 1 
                    AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                    AND um.DbName NOT LIKE 'KDMC%' 
                    $searchCon
                    GROUP BY 
                    dw.MobileNo, 
                    um.ExecutiveName
                    HAVING COUNT(*) > 4";
    
    $query = $mainQuery." $orderClause $limitClause";
    $RptData = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);
    
    $totalFilterQuery = "SELECT COUNT(*) AS total_count FROM ($mainQuery) AS final_result";
    $totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];
    
    $totalRecordQuery = "SELECT COUNT(*) AS total_count FROM (SELECT COUNT(*) AS total_count
                            FROM  
                            Dw_VotersInfo AS dw
                            INNER JOIN ".DB_NAME_USER."..User_Master AS um ON dw.AddedBy = um.Executive_Cd
                            INNER JOIN ".DB_NAME_USER."..Election_Master AS em ON um.ElectionName = em.ElectionName 
                            WHERE 
                            dw.MobileNo <> ''
                            AND LEN(dw.MobileNo) = 10
                            AND em.ULB = '$ULB'
                            AND dw.SF = 1 
                            AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                            AND um.DbName NOT LIKE 'KDMC%' 
                            GROUP BY 
                            dw.MobileNo, 
                            um.ExecutiveName
                            HAVING COUNT(*) > 4) AS final_result;";
    $totalRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalRecordQuery, $userName, $appName, $developmentMode)['total_count'];
}
elseif(isset($_POST['flag']) && $_POST['flag'] == 'MW'){
    $columnMapping = [
        0 => null,
        1 => 'MobileNo',
        2 => 'DBName',
        3 => 'FamilyNos',
        4 => 'datacnt'
    ];
    
    $orderColumnIndex = $_POST['order'][0]['column'];
    $orderDirection = $_POST['order'][0]['dir'];
    
    $orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'MobileNo';
    
    if ($orderColumn === null) {
        $orderClause = "ORDER BY MobileNo ASC";
    } else {
        $orderClause = "ORDER BY $orderColumn $orderDirection";
    }
    
    $searchCon = "";
    if(!empty($searchValue)){
        $searchCon = "AND MobileNo LIKE '%$searchValue%'";
    }
    
    $mainQuery = "SELECT 
                MobileNo,
                COUNT(DISTINCT dw.FamilyNo) AS FamilyNos,
                COALESCE(um.ExecutiveName,'') AS ExecutiveName,
                COUNT(*) AS datacnt,
                MAX(um.DbName) AS DBName
                FROM  
                Dw_VotersInfo AS dw
                INNER JOIN ".DB_NAME_USER."..User_Master AS um ON dw.AddedBy = um.Executive_Cd
                INNER JOIN ".DB_NAME_USER."..Election_Master AS em ON um.ElectionName = em.ElectionName 
                WHERE 
                dw.MobileNo <> ''
                AND LEN(dw.MobileNo) = 10
                AND em.ULB = '$ULB'
                AND dw.SF = 1 
                AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                AND um.DbName NOT LIKE 'KDMC%' 
                $searchCon
                GROUP BY 
                dw.MobileNo, 
                um.ExecutiveName
                HAVING COUNT(*) > 4";
    
    $query = $mainQuery." $orderClause $limitClause";
    $RptData = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);
    
    $totalFilterQuery = "SELECT COUNT(*) AS total_count FROM ($mainQuery) AS final_result";
    $totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];
    
    $totalRecordQuery = "SELECT COUNT(*) AS total_count FROM (SELECT COUNT(*) AS total_count
                            FROM  
                            Dw_VotersInfo AS dw
                            INNER JOIN ".DB_NAME_USER."..User_Master AS um ON dw.AddedBy = um.Executive_Cd
                            INNER JOIN ".DB_NAME_USER."..Election_Master AS em ON um.ElectionName = em.ElectionName 
                            WHERE 
                            dw.MobileNo <> ''
                            AND LEN(dw.MobileNo) = 10
                            AND em.ULB = '$ULB'
                            AND dw.SF = 1 
                            AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                            AND um.DbName NOT LIKE 'KDMC%' 
                            GROUP BY 
                            dw.MobileNo, 
                            um.ExecutiveName
                            HAVING COUNT(*) > 4) AS final_result;";
    $totalRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalRecordQuery, $userName, $appName, $developmentMode)['total_count'];
}


$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilterRecords,
    "data" => $RptData
];

echo json_encode($response);

?>



