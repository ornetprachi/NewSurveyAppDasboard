<?php
//Old Data from ExecutiveOverallData
// print_r($_POST);
session_start();
include '../api/includes/DbOperation.php';
$db=new DbOperation();
header('Content-Type: application/json');

// $ExecutiveWiseCount = '';
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
$WorkForFilter = '';
if(isset($_SESSION['SurveyUA_Status'])){
    $Filter = $_SESSION['SurveyUA_Status']; 
    
    if($Filter == 'INACTIVE'){
    $cond = " WHERE DeactiveFlag = 'D'";
    }else{
        $cond = " WHERE DeactiveFlag IS NULL";
    }
    }else{
    $Filter = '';   
    $cond = '';
}
if(
    isset($_SESSION['SurveyUA__WorkingDaysExec_For_SummaryReport']) &&
    isset($_SESSION['SurveyUA__ToWorkingDaysExec_For_SummaryReport']) 
){
    $WorkingDaysExec = $_SESSION['SurveyUA__WorkingDaysExec_For_SummaryReport']; 
    $ToWorkingDaysExec = $_SESSION['SurveyUA__ToWorkingDaysExec_For_SummaryReport']; 

    if($WorkingDaysExec != '' && $ToWorkingDaysExec != ''){
    $WorkForFilter = "HAVING SUM(WorkingDays)  BETWEEN '$WorkingDaysExec' AND '$ToWorkingDaysExec' ";
    }else{
        $WorkForFilter = "";
    }
}else{
    $WorkingDaysExec = ''; 
    $ToWorkingDaysExec = '';
    
    
}
$sqlQuery = "WITH unionTable AS (SELECT
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
 COALESCE(COUNT(DISTINCT CASE 
                    WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
                END), 0) AS LBS
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
) AS Combined
GROUP BY Combined.AddedBy,Combined.Society_Cd,Combined.AddedDate)
SELECT  
um.ExecutiveName,
um.Executive_Cd AS Excutive_cd,
COALESCE(um.Mobile, '') AS MobileNo,
COALESCE(CONVERT(varchar, em.JoiningDate, 34), '') AS JoiningDate,
CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag, 
CASE WHEN CONVERT(varchar, ExpDate, 103) >= CONVERT(varchar, GETDATE(), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired,
COALESCE(em.Designation, '') AS Designation,
COUNT(DISTINCT ut.Society_Cd) AS SocietyCount,
SUM(ut.Mobileno) AS TotalMobileCount,
SUM(ut.TotalVoters) AS TotalVoters,
SUM(ut.TotalNonVoters) AS TotalNonVoters,
SUM(ut.LockRoom) AS LockRoom,
SUM(ut.BirthdaysCount) AS BirthdaysCount,
SUM(ut.LBS) AS LBS,
SUM(ut.RoomCount) AS RoomSurveyDone,
COUNT(DISTINCT ut.AddedDate) AS WorkingDays
from unionTable  AS ut 
Inner JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
AND um.ElectionName = '$ULB'
Inner JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
$cond
GROUP BY um.Executive_Cd,um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile
$WorkForFilter 
ORDER BY ExecutiveName;";

// print_r($sqlQuery);
// die;
$ExecutiveWiseCount = $db->ExecutveQueryMultipleRowSALData($ULB,$sqlQuery, $userName, $appName, $developmentMode);


// print_r($ExecutiveWiseCount);
// die;
$Query1 = "WITH unionTable AS (SELECT
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
COALESCE(COUNT(DISTINCT CASE 
				WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
			END), 0) AS LBS
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
) AS Combined
GROUP BY Combined.AddedBy,Combined.Society_Cd,Combined.AddedDate)
SELECT  
um.ExecutiveName,
um.Executive_Cd AS Excutive_cd,
COALESCE(um.Mobile, '') AS MobileNo,
COALESCE(CONVERT(varchar, em.JoiningDate, 34), '') AS JoiningDate,
CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag, 
CASE WHEN CONVERT(varchar, ExpDate, 103) >= CONVERT(varchar, GETDATE(), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired,
COALESCE(em.Designation, '') AS Designation,
COUNT(DISTINCT ut.Society_Cd) AS SocietyCount,
SUM(ut.Mobileno) AS TotalMobileCount,
SUM(ut.TotalVoters) AS TotalVoters,
SUM(ut.TotalNonVoters) AS TotalNonVoters,
SUM(ut.LockRoom) AS LockRoom,
SUM(ut.BirthdaysCount) AS BirthdaysCount,
SUM(ut.LBS) AS LBS,
SUM(ut.RoomCount) AS RoomSurveyDone,
COUNT(DISTINCT ut.AddedDate) AS WorkingDays
from unionTable  AS ut 
Inner JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
AND um.ElectionName = '$ULB'
Inner JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
GROUP BY um.Executive_Cd,um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile 
ORDER BY ExecutiveName;";

$Count = $db->ExecutveQueryMultipleRowSALData($ULB,$Query1, $userName, $appName, $developmentMode);
$TotalExecutive = sizeof($Count);
$ACount = 0;
$IACount = 0;
foreach($Count as $key=>$value){
    if($value['DeactiveFlag'] == 'ACTIVE'){
        $ACount++;
       
    }else{
        $IACount ++;

    }
}
$Active =$ACount;
$Inactive =  $IACount;




if ($ExecutiveWiseCount) {
    $response = [
        'error' => 'false',
        'ExecutiveWiseCount' => $ExecutiveWiseCount,
        'Active' => $Active,
        'Inactive' => $Inactive,
        'TotalExecutive' => $TotalExecutive
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'No data found.'
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>