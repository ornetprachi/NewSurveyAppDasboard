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
$totalFilterRecords = 0;

if (isset($_POST['flag']) && !empty($_POST['flag'])) {
    $flag = $_POST['flag'];
    $ExecutiveName = $_POST['ExecutiveName'];
    $MobileNo = $_POST['MobileNo'];

    $limitClause = "";
    if ($length != -1) {
        $limitClause = "OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
    }

    $columnMapping = [
        0 => null,
        1 => 'FamilyNo',
        2 => 'UpdatedDate',
        3 => 'List_No',
        4 => 'SiteName',
        5 => 'FullName',
        6 => 'MobileNo',
        7 => 'Age',
        8 => 'Gender',
        9 => 'SocietyName',
        10 => 'RoomNo'
    ];

    $orderColumnIndex = $_POST['order'][0]['column'];
    $orderDirection = $_POST['order'][0]['dir'];

    $orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'UpdatedDate';

    if ($orderColumn === null) {
        $orderClause = "ORDER BY dw.AddedDate ASC";
    } else {
        $orderClause = "ORDER BY $orderColumn $orderDirection";
    }

    $searchCon = "";
    if (!empty($searchValue)) {
        $searchCon = "AND (dw.FamilyNo LIKE '%$searchValue%' OR dw.FullName LIKE '%$searchValue%' OR dw.SocietyName LIKE '%$searchValue%')";
    }

    if ($flag == 'EW' && !empty($ExecutiveName) && !empty($MobileNo)) {
        $con = "AND ExecutiveName = '$ExecutiveName' AND MobileNo = '$MobileNo'";
    } else if ($flag == 'MW' && !empty($MobileNo)) {
        $con = "AND MobileNo = '$MobileNo'";
    } else {
        $con = "";
    }

    $mainQuery = "SELECT  
                     COALESCE(dw.MobileNo,'') AS MobileNo
                    ,COALESCE(dw.AC_No,0) AS AC_No
                    ,COALESCE(dw.List_No,0) AS List_No
                    ,COALESCE(dw.Voter_Id,0) AS Voter_Id
                    ,COALESCE(dw.FamilyNo,'') AS FamilyNo
                    ,COALESCE(dw.SubLocation_Cd,0) AS SubLocation_Cd
                    ,COALESCE(dw.SiteName,'') AS SiteName
                    ,COALESCE(dw.IdCard_No,'') AS IdCard_No
                    ,COALESCE(dw.QC_Calling_Status_Cd,'') AS QC_Calling_Status_Cd
                    ,COALESCE(dw.AddedBy,'') AS UpdateByUser
                    ,CONVERT(VARCHAR,dw.AddedDate, 29) AS UpdatedDate
                    ,COALESCE(um.DBName,'') AS DBName
                    ,COALESCE(dw.FullName,'') AS FullName
                    ,COALESCE(dw.SocietyName,'') AS SocietyName
                    ,COALESCE(dw.RoomNo,'') AS RoomNo
                    ,COALESCE(dw.Sex,'') AS Gender
                    ,COALESCE(dw.Age,0) AS Age
                    FROM Dw_VotersInfo AS dw
                    INNER JOIN " . DB_NAME_USER . "..User_Master as um on (dw.AddedBy = um.Executive_Cd)
                    INNER JOIN " . DB_NAME_USER . "..Election_Master AS em ON um.ElectionName = em.ElectionName 
                    WHERE 
                    dw.MobileNo <> ''
                    AND LEN(dw.MobileNo) = 10
                    AND em.ULB = '$ULB'
                    AND dw.SF = 1 
                    AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                    $con
                    $searchCon";

    $query = $mainQuery . " $orderClause $limitClause";
    $RptData = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);

    $totalFilterQuery = "SELECT COUNT(*) AS total_count FROM ($mainQuery) AS final_result";
    $totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];

    $totalRecordQuery = "SELECT COUNT(*) AS total_count
                    FROM Dw_VotersInfo AS dw
                    INNER JOIN " . DB_NAME_USER . "..User_Master as um on (dw.AddedBy = um.Executive_Cd)
                    INNER JOIN " . DB_NAME_USER . "..Election_Master AS em ON um.ElectionName = em.ElectionName 
                    WHERE 
                    dw.MobileNo <> ''
                    AND LEN(dw.MobileNo) = 10
                    AND em.ULB = '$ULB'
                    AND dw.SF = 1 
                    AND LEFT(dw.MobileNo, 1) BETWEEN '5' AND '9'
                    $con;";
    $totalRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalRecordQuery, $userName, $appName, $developmentMode)['total_count'];
}

$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilterRecords,
    "data" => $RptData
];
echo json_encode($response);




