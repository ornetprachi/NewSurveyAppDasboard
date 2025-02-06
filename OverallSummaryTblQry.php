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

$limitClause = "";
if ($length != -1) {
    $limitClause = "OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
}

$columnMapping = [
    0 => null,
    1 => 'SurvyeDate',
    2 => 'SurvyeSociety',
    3 => 'SurveyBy',
    4 => 'RoomSurveyDone',
    5 => 'TotalVoters',
    6 => 'TotalNonVoters',
    7 => 'LockRoom',
    8 => 'LBS',
    9 => 'TotalMobileCount',
    10 => 'BirthdaysCount'
];

$orderColumnIndex = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];

$orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'SurvyeDate';

if ($orderColumn === null) {
    $orderClause = "ORDER BY SurvyeDate DESC";
} else {
    $orderClause = "ORDER BY $orderColumn $orderDirection";
}

$searchCon = "";
if (!empty($searchValue)) {
    $searchCon = "WHERE (Combined.AddedDate LIKE '%$searchValue%')";
}


$mainQuery = "SELECT 
            COALESCE(count(DISTINCT Combined.AddedBy),0) AS SurveyBy,
            Combined.AddedDate AS SurvyeDate,
            COALESCE(count(DISTINCT(Combined.Society_Cd)), 0) AS SurvyeSociety,
            COALESCE(COUNT(DISTINCT Combined.RoomNo), 0) AS RoomSurveyDone, 
            COALESCE(COUNT(DISTINCT CASE 
                                    WHEN Combined.Mobileno <> '' AND Combined.Mobileno IS NOT NULL AND LEN(Combined.Mobileno) > 9 
                                    THEN Combined.Mobileno 
                                END), 0) AS TotalMobileCount,
            COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.IdCard_No IS NOT NULL AND Combined.IdCard_No <> '' THEN 1 END), 0) AS TotalVoters,
            COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' AND Combined.Voter_Cd IS NOT NULL AND Combined.Voter_Cd <> ''  THEN 1 END), 0) AS TotalNonVoters,
            COALESCE(COUNT(CASE WHEN Combined.Source = 'LockRoom' THEN 1 END), 0) AS LockRoom,
            COALESCE(COUNT(CASE WHEN Combined.BirthDate IS NOT NULL AND Combined.BirthDate <> '01/01/1900' THEN 1 END), 0) AS BirthdaysCount,
            COALESCE(COUNT(DISTINCT CASE 
                WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
            END), 0) AS LBS

            FROM 
            (SELECT 
                        dw.IdCard_No,
                        dw.Voter_Cd AS Voter_Cd,
                        dw.Society_Cd, 
                        dw.RoomNo, 
                        dw.AddedBy, 
                        Convert(varchar,dw.AddedDate,23) AS AddedDate, 
                        'Dw_VotersInfo' AS Source, 
                        dw.LockedButSurvey AS LBS, 
                        dw.MobileNo AS Mobileno,
                        CASE
                            WHEN TRY_CONVERT(date, dw.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 101), 101)
                            WHEN TRY_CONVERT(date, dw.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 105), 101)
                            WHEN TRY_CONVERT(date, dw.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 23), 101)
                            ELSE NULL
                        END AS BirthDate 
                    FROM Dw_VotersInfo AS dw
                    WHERE dw.Society_Cd IS NOT NULL 
                    AND dw.Society_Cd <> 0 AND COALESCE(dw.Ward_no, 0) != 0
                    AND dw.AddedBy IS NOT NULL AND dw.AddedDate <> '' AND dw.AddedDate IS NOT NULL

                    UNION ALL

                    SELECT 
                        NULL AS IdCard_No,
                        nv.Voter_Cd AS Voter_Cd,
                        nv.Society_Cd, 
                        nv.RoomNo, 
                        nv.added_by AS AddedBy, 
                        Convert(varchar,nv.added_date,23) AS AddedDate, 
                        'NewVoterRegistration' AS Source, 
                        nv.LockedButSurvey AS LBS, 
                        nv.Mobileno AS Mobileno,
                        CASE
                            WHEN TRY_CONVERT(date, nv.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 101), 101)
                            WHEN TRY_CONVERT(date, nv.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 105), 101)
                            WHEN TRY_CONVERT(date, nv.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 23), 101)
                            ELSE NULL
                        END AS BirthDate
                    FROM NewVoterRegistration AS nv
                    WHERE nv.Society_Cd IS NOT NULL 
                    AND nv.Society_Cd <> 0 AND COALESCE(nv.Ward_No, 0) != 0
                    AND nv.added_by IS NOT NULL AND nv.added_date <> '' AND nv.added_date IS NOT NULL
                    UNION ALL

                    SELECT 
                        NULL AS IdCard_No,
                        NULL AS Voter_Cd,
                        lr.Society_Cd, 
                        lr.RoomNo, 
                        lr.added_by AS AddedBy, 
                        Convert(varchar,lr.added_date,23) AS AddedDate,
                        'LockRoom' AS Source, 
                        NULL AS LBS, 
                        NULL AS Mobileno, 
                        NULL AS BirthDate
                    FROM LockRoom AS lr
                    WHERE lr.Society_Cd IS NOT NULL 
                    AND lr.Society_Cd <> 0 
                    AND COALESCE(lr.Ward_No, 0) != 0
                    AND lr.added_by IS NOT NULL AND lr.added_date <> '' AND lr.added_date IS NOT NULL)  AS Combined
                $searchCon
            GROUP BY Combined.AddedDate";

$query = $mainQuery . " $orderClause $limitClause";
$RptData = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);

$totalFilterQuery = "SELECT COUNT(*) AS total_count FROM ($mainQuery) AS final_result";
$totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];



$response = [
    "draw" => $draw,
    "recordsTotal" => $totalFilterRecords,
    "recordsFiltered" => $totalFilterRecords,
    "data" => $RptData
];

echo json_encode($response);

?>