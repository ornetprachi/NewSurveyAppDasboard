<?php
    session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;
$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
$searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$RptData = [];
$totalRecords = 0;
$totalFilterRecords = 0 ;


if(isset($_POST['Site']) && !empty($_POST['Site'])){
    $Site = $_POST['Site'];
    if(isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['Tdate']) && !empty($_POST['Tdate'])){
        $fromdate = $_POST['date'];
        $todate = $_POST['Tdate'];
        $dwCon = "AND CONVERT(varchar,dw.AddedDate,23) BETWEEN '$fromdate' AND '$todate'";
        $nvCon = "AND CONVERT(varchar,nv.added_date,23) BETWEEN '$fromdate' AND '$todate'";
        $lrCon = "AND CONVERT(varchar,lr.added_date,23) BETWEEN '$fromdate' AND '$todate'";
    }else{
        $fromdate = date('Y-m-d');
        $todate = date('Y-m-d');
        $dwCon = "";
        $nvCon = "";
        $lrCon = "";
    }


    $limitClause = "";
    if ($length != -1) {
        $limitClause = "OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
    }

    $columnMapping = [
        0 => null,
        2 => 'SocietyName',
        3 => 'PlotNo',
        4 => 'PocketNo',
        5 => 'PocketName',
        6 => 'ListedBy',
        7 => 'TotalRoom',
        8 => 'RoomSurveyDone',
        9 => 'LockRoom',
        10 => 'TotalVoters',
        11 => 'TotalNonVoters',
        12 => 'LBS',
        13 => 'TotalMobileCount',
        14 => 'BirthdaysCount'
    ];

    $orderColumnIndex = $_POST['order'][0]['column'];
    $orderDirection = $_POST['order'][0]['dir'];

    $orderColumn = isset($columnMapping[$orderColumnIndex]) ? $columnMapping[$orderColumnIndex] : 'SocietyName';

    if ($orderColumn === null) {
        $orderClause = "ORDER BY som.SiteName ASC";
    } else {
        $orderClause = "ORDER BY $orderColumn $orderDirection";
    }

    $searchCon = "";
    if (!empty($searchValue)) {
        $searchCon = "WHERE (som.SocietyName LIKE '%$searchValue%')";
    }
    $mainQuery = "WITH unionTable AS (SELECT
                Combined.AddedBy AS AddedBy,
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
                COALESCE(COUNT(CASE WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN 1 END), 0) AS LBS
                FROM (
                    SELECT 
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
                            AND dw.SF = 1
                        AND dw.SiteName = '$Site' $dwCon

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
                        AND nv.SiteName = '$Site' $nvCon

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
                        AND lr.SiteName = '$Site' $lrCon
                ) AS Combined
                GROUP BY Combined.AddedBy,Combined.Society_Cd,Combined.AddedDate)";

    $subquery = "SELECT  
                        COALESCE(um.ExecutiveName,'') AS ListedBy,
                        COALESCE(som.ElectionName,'') AS ElectionName,
                        COALESCE(um.Mobile, '') AS MobileNo,
                        COALESCE(pom.PocketName,'') AS PocketName,
                        COALESCE(pom.PocketNo,0) AS PocketNo,
                        COALESCE(som.SocietyName,'') AS SocietyName,
                        COALESCE(som.Plot_No,'') AS PlotNo,
                        COALESCE(som.SiteName,'') AS SiteName,
                        COALESCE(sum(som.NewRooms),'') AS Rooms,
                        COALESCE(sum(som.Rooms),'') AS TotalRoom,
                        SUM(ut.Mobileno) AS TotalMobileCount,
                        SUM(ut.TotalVoters) AS TotalVoters,
                        SUM(ut.TotalNonVoters) AS TotalNonVoters,
                        SUM(ut.LockRoom) AS LockRoom,
                        SUM(ut.BirthdaysCount) AS BirthdaysCount,
                        SUM(ut.LBS) AS LBS,
                        SUM(ut.RoomCount) AS RoomSurveyDone,
                        COALESCE(count(DISTINCT ut.AddedBy),0) AS SurveyBy
                FROM unionTable  AS ut 
                Inner Join Society_Master as som ON ut.Society_Cd = som.Society_Cd
                LEFT JOIN Pocket_Master as pom ON som.Pocket_Cd = pom.Pocket_Cd
                LEFT JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
                AND um.ElectionName = '$ULB'
                LEFT JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
                $searchCon
                GROUP BY um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile,ut.Society_Cd,
                som.SocietyName,pom.PocketName,som.SiteName,som.ElectionName,som.Plot_No,pom.PocketNo";

    $query = $mainQuery . " $subquery $orderClause $limitClause";
    $RptData = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

    $totalFilterQuery = $mainQuery . "SELECT COUNT(*) AS total_count FROM ($subquery) AS final_result";
    $totalFilterRecords = $db->ExecutveQuerySingleRowSALData($ULB, $totalFilterQuery, $userName, $appName, $developmentMode)['total_count'];

    $totalRecordQuery = $mainQuery . " SELECT COUNT(*) AS total_count FROM (SELECT  
                        COALESCE(um.ExecutiveName,'') AS ListedBy,
                        COALESCE(som.ElectionName,'') AS ElectionName,
                        COALESCE(um.Mobile, '') AS MobileNo,
                        COALESCE(pom.PocketName,'') AS PocketName,
                        COALESCE(pom.PocketNo,0) AS PocketNo,
                        COALESCE(som.SocietyName,'') AS SocietyName,
                        COALESCE(som.Plot_No,'') AS PlotNo,
                        COALESCE(som.SiteName,'') AS SiteName,
                        COALESCE(sum(som.NewRooms),'') AS Rooms,
                        COALESCE(sum(som.Rooms),'') AS TotalRoom,
                        SUM(ut.Mobileno) AS TotalMobileCount,
                        SUM(ut.TotalVoters) AS TotalVoters,
                        SUM(ut.TotalNonVoters) AS TotalNonVoters,
                        SUM(ut.LockRoom) AS LockRoom,
                        SUM(ut.BirthdaysCount) AS BirthdaysCount,
                        SUM(ut.LBS) AS LBS,
                        SUM(ut.RoomCount) AS RoomSurveyDone,
                        COALESCE(count(DISTINCT ut.AddedBy),0) AS SurveyBy
                FROM unionTable  AS ut 
                Inner Join Society_Master as som ON ut.Society_Cd = som.Society_Cd
                LEFT JOIN Pocket_Master as pom ON som.Pocket_Cd = pom.Pocket_Cd
                LEFT JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
                AND um.ElectionName = '$ULB'
                LEFT JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
                GROUP BY um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile,ut.Society_Cd,
                som.SocietyName,pom.PocketName,som.SiteName,som.ElectionName,som.Plot_No,pom.PocketNo) AS result_table";
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
   
