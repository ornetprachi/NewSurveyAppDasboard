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
    2 => 'ClientName',
    3 => 'Ac_No',
    4 => 'Ward_No',
    6 => 'Result',
    7 => 'Listing',
    8 => 'Survey',
    9 => null,
    10 => 'TotalRooms',
    11 => 'Rooms',
    12 => 'lockroom',
    13 => 'voters',
    14 => 'nonvoter',
    15 => 'LBS',
    16 => 'Mobile',
    17 => 'Bday'
];

$orderColumnIndex = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];

$orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'ClientName';

if ($orderColumn === null) {
    $orderClause = "ORDER BY sm.ClientName ASC";
} else {
    $orderClause = "ORDER BY $orderColumn $orderDirection";
}

$searchCon = "";
if (!empty($searchValue)) {
    $searchCon = "AND (sm.ClientName LIKE '%$searchValue%')";
}

$mainQuery = "WITH unionTable AS (SELECT
                Combined.SiteName AS SiteName,
                Combined.AddedDate,
                COALESCE(Combined.Society_Cd, 0) AS Society_Cd,
                COALESCE(COUNT(DISTINCT Combined.RoomNo), 0) AS RoomCount, 
                COALESCE(COUNT(DISTINCT CASE 
                                        WHEN Combined.Mobileno <> '' AND Combined.Mobileno IS NOT NULL AND LEN(Combined.Mobileno) > 9 
                                        THEN Combined.Mobileno 
                                    END), 0) AS Mobileno,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.IdCard_No IS NOT NULL AND Combined.IdCard_No <> '' THEN 1 END), 0) AS TotalVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' AND Combined.Voter_Cd IS NOT NULL AND Combined.Voter_Cd <> ''  THEN 1 END), 0) AS TotalNonVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'LockRoom' THEN 1 END), 0) AS LockRoom,
                COALESCE(COUNT(CASE WHEN Combined.BirthDate IS NOT NULL AND Combined.BirthDate <> '01/01/1900' THEN 1 END), 0) AS BirthdaysCount,
                COALESCE(COUNT(DISTINCT CASE 
                                WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
                            END), 0) AS LBS
                FROM (
                    SELECT 
                            dw.IdCard_No,
                            dw.Voter_Cd AS Voter_Cd,
                            dw.Society_Cd, 
                            dw.RoomNo, 
                            dw.SiteName, 
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
                        AND dw.SF = 1

                        UNION ALL

                        SELECT 
                            NULL AS IdCard_No,
                            nv.Voter_Cd AS Voter_Cd,
                            nv.Society_Cd, 
                            nv.RoomNo, 
                            nv.SiteName AS SiteName, 
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
                        
                        UNION ALL

                        SELECT 
                            NULL AS IdCard_No,
                            NULL AS Voter_Cd,
                            lr.Society_Cd, 
                            lr.RoomNo, 
                            lr.SiteName AS SiteName, 
                            Convert(varchar,lr.added_date,23) AS AddedDate,
                            'LockRoom' AS Source, 
                            NULL AS LBS, 
                            NULL AS Mobileno, 
                            NULL AS BirthDate
                        FROM LockRoom AS lr
                        WHERE lr.Society_Cd IS NOT NULL 
                        AND lr.Society_Cd <> 0 
                        AND COALESCE(lr.Ward_No, 0) != 0
                ) AS Combined
                GROUP BY Combined.SiteName,Combined.Society_Cd,Combined.AddedDate),
                SocietyCounts AS (
                                SELECT SiteName, SUM(Rooms) AS TotalRooms,COUNT(Society_Cd) AS Listing 
                                FROM Society_Master
                                GROUP BY SiteName
                )";

$subquery = "SELECT  
                COALESCE(sm.SiteName,'') AS SiteName,
                COALESCE(sm.ClientName,'') AS ClientName,
                COALESCE(sm.SiteStatus,'') AS SiteStatus,
                COALESCE(sm.Ac_No,'') AS Ac_No,
                COALESCE(sm.Ward_No,'') AS Ward_No,
                COALESCE(sc.Listing,0) AS Listing,
                COALESCE(sc.TotalRooms,0) AS TotalRooms,
                COUNT(DISTINCT ut.Society_Cd) AS Survey,
                SUM(ut.Mobileno) AS Mobile,
                SUM(ut.TotalVoters) AS voters,
                SUM(ut.TotalNonVoters) AS nonvoter,
                SUM(ut.LockRoom) AS lockroom,
                SUM(ut.BirthdaysCount) AS Bday,
                SUM(ut.LBS) AS LBS,
                SUM(ut.RoomCount) AS Rooms,
                COALESCE((SElECT (SUM(Voting)/COUNT(DISTINCT(COALESCE(Panel_Code, '1'))))  FROM Survey_Entry_Data..ElectionResults 
                WHERE Ward_No = sm.Ward_No AND ULB = '$ULB' 
                AND ElectionYear = (SELECT MAX(ElectionYear) FROM Survey_Entry_Data..ElectionResults WHERE ULB = '$ULB')
                Group By Ward_No),'') As Result
                from Site_Master  AS sm 
                Inner JOIN unionTable AS ut ON sm.SiteName = ut.SiteName 
                Inner JOIN SocietyCounts AS sc ON sc.SiteName = ut.SiteName 
                AND sm.ElectionName = '$ULB'
                $searchCon
                GROUP BY sm.SiteName,sm.ClientName, sm.Ac_No, sm.Ward_No,sm.SiteStatus,sc.Listing,
                sc.TotalRooms";

$query = $mainQuery . " $subquery $orderClause $limitClause";
$RptData = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);

$totalFilterQuery = $mainQuery . "SELECT COUNT(*) AS total_count FROM ($subquery) AS final_result";
$totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];

$totalRecordQuery = $mainQuery . " SELECT COUNT(*) AS total_count FROM (SELECT  
                COALESCE(sm.SiteName,'') AS SiteName,
                COALESCE(sm.ClientName,'') AS ClientName,
                COALESCE(sm.SiteStatus,'') AS SiteStatus,
                COALESCE(sm.Ac_No,'') AS Ac_No,
                COALESCE(sm.Ward_No,'') AS Ward_No,
                COALESCE(sc.Listing,0) AS Listing,
                COALESCE(sc.TotalRooms,0) AS TotalRooms,
                COUNT(DISTINCT ut.Society_Cd) AS Survey,
                SUM(ut.Mobileno) AS Mobile,
                SUM(ut.TotalVoters) AS voters,
                SUM(ut.TotalNonVoters) AS nonvoter,
                SUM(ut.LockRoom) AS lockroom,
                SUM(ut.BirthdaysCount) AS Bday,
                SUM(ut.LBS) AS LBS,
                SUM(ut.RoomCount) AS Rooms,
                COALESCE((SElECT (SUM(Voting)/COUNT(DISTINCT(COALESCE(Panel_Code, '1'))))  FROM Survey_Entry_Data..ElectionResults 
                WHERE Ward_No = sm.Ward_No AND ULB = '$ULB' 
                AND ElectionYear = (SELECT MAX(ElectionYear) FROM Survey_Entry_Data..ElectionResults WHERE ULB = '$ULB')
                Group By Ward_No),'') As Result
                from Site_Master  AS sm 
                Inner JOIN unionTable AS ut ON sm.SiteName = ut.SiteName 
                Inner JOIN SocietyCounts AS sc ON sc.SiteName = ut.SiteName 
                AND sm.ElectionName = '$ULB'
                GROUP BY sm.SiteName,sm.ClientName, sm.Ac_No, sm.Ward_No,sm.SiteStatus,sc.Listing,
                sc.TotalRooms) AS result_table";
$totalRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalRecordQuery, $userName, $appName, $developmentMode)['total_count'];

$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFilterRecords,
    "data" => $RptData
];

echo json_encode($response);

?>