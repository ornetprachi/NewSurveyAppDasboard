<?php
function fetchData($page) {
    session_start();
    include 'api/includes/DbOperation.php';
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];

    $db=new DbOperation();
    $data = array();

    $draw = $_POST['draw'];
    $start = $_POST["start"];
    $rowperpage = $_POST["length"]; // Rows display per page
    $columnIndex_arr = $_POST['order'];
    $columnName_arr = $_POST['columns'];
    $order_arr = $_POST['order'];
    $search_arr = $_POST['search'];

    $columnIndex = $columnIndex_arr[0]['column']; // Column index
    $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    $searchValue = $search_arr['value']; // Search value
    $dataElectionName = array();
    $dataElectionName = $db->getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode);

    if(
        (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName'])) 
    ){
        // print_r("Gauriiii");
        $electionName = $_SESSION['SurveyUA_ElectionName'];
        $electionCd = $_SESSION['SurveyUA_Election_Cd'];

        $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
    }else{
        $DBName = "";
    }

    // New Filter Code ------------------------------------------------------------------------------------------------
    $PostExecutive = "";
    if(isset($_POST['ExecutiveVal']) && !empty($_POST['ExecutiveVal'])){
        if($_POST['ExecutiveVal'] != "All"){
            $PostExecutive = " WHERE OrnetUpdateByUser = '".$_POST['ExecutiveVal']."'";
        }else{
            $PostExecutive = "";
        }
        $_SESSION['VS_SSKaryakartaSummary_Executive_Cd'] = $_POST['ExecutiveVal'];
    }
        
    $PostFromToDateCond = "";
    if(isset($_POST['FromDate']) && !empty($_POST['FromDate']) && isset($_POST['ToDate']) && !empty($_POST['ToDate'])){
        $PostFromToDateCond = " AND CONVERT(VARCHAR,OrnetUpdatedDate,23) BETWEEN '".$_POST['FromDate']."' AND '".$_POST['ToDate']."'";
        $_SESSION['VS_SSKaryakartaSummary_FromDate'] = $_POST['FromDate'];
        $_SESSION['VS_SSKaryakartaSummary_ToDate'] = $_POST['ToDate'];
    }

    $PostSearchNotSearchVal = "";
    if (isset($_POST['SearchNotSearchVal']) && !empty($_POST['SearchNotSearchVal'])) {
        if ($_POST['SearchNotSearchVal'] != "All") {
            if ($_POST['SearchNotSearchVal'] == "Search") {
                $PostSearchNotSearchVal = " AND (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR UpdateByUser IS NOT NULL OR OrnetUpdateByUser IS NOT NULL)";
            } elseif ($_POST['SearchNotSearchVal'] == "NotSearch") {
                $PostSearchNotSearchVal = " AND (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR UpdateByUser IS NULL OR OrnetUpdateByUser IS NULL)";
            } else {
                $PostSearchNotSearchVal = "";    
            }
        } else {
            $PostSearchNotSearchVal = "";
        }
        $_SESSION['VS_KK_FilterMaster_SearchNotSearchVal'] = $_POST['SearchNotSearchVal'];
    }

    $TotalRecordsQuery = "SELECT COUNT(DISTINCT tb.Voter_Cd) AS TotalRecords
                                FROM (
                                    SELECT
                                        COALESCE(Voter_Cd, 0) AS Voter_Cd,
                                        COALESCE(UpdateByUser, '') AS UpdateByUser,
                                        COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                                        CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                                    FROM $DBName..Dw_VotersInfo 
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, UpdateByUser
                                    UNION
                                    SELECT 
                                        COALESCE(Voter_Cd, 0) AS Voter_Cd,
                                        COALESCE(UpdateByUser, '') AS UpdateByUser,
                                        COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                                        CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                                    FROM $DBName..NewVoterRegistration
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, UpdateByUser
                                ) AS tb
                                INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                                $PostExecutive
                                $PostFromToDateCond
                                $PostSearchNotSearchVal;";

    $TotalRecordsArr = $db->ExecutveQueryMultipleRowSALData($ULB,$TotalRecordsQuery , $userName, $appName, $developmentMode);

    $totalRecords = $TotalRecordsArr[0]['TotalRecords'];

    $SearchValueCond = "";
    $searchValue = trim($searchValue);
    if($searchValue != ""){
        $SearchValueCond= " AND um.ExecutiveName LIKE '%$searchValue%'
        OR tb.Voter_Id LIKE '%$searchValue%' 
        OR tb.List_No LIKE '%$searchValue%' 
        OR tb.Age LIKE '%$searchValue%'
        OR tb.Sex LIKE '%$searchValue%'
        OR tb.Ac_No LIKE '%$searchValue%'
        OR tb.FullName LIKE '%$searchValue%'
        OR tb.MobileNo LIKE '%$searchValue%'
        OR tb.Ward_no LIKE '%$searchValue%'
        OR tb.BirthDate LIKE '%$searchValue%'
        OR tb.SocietyName LIKE '%$searchValue%'
        OR tb.OrnetUpdateByUser LIKE '%$searchValue%'";
    }

    // Total records with filter
    $TotalRecordsWithCondArr = array();
    $TotalRecordsWithCondQuery = "SELECT COUNT(DISTINCT tb.Voter_Cd) AS TotalRecords,tb.OrnetUpdateByUser,um.ExecutiveName,tb.Voter_Cd,tb.Voter_Id,tb.List_No,tb.Age,tb.Sex,tb.FullName,tb.Ac_No,tb.RoomNo,tb.Ward_no,tb.MobileNo,tb.BirthDate,tb.Education,tb.UpdatedStatus,tb.SocietyName,tb.UpdateByUser
                                FROM (
                                    SELECT 
                                       COALESCE(Voter_Cd, 0) AS Voter_Cd,
                                        COALESCE(Voter_Id, 0) AS Voter_Id,
                                        COALESCE(List_No, 0) AS List_No,
                                        COALESCE(Age, 0) AS Age,
                                        COALESCE(Sex, '') AS Sex,
                                        COALESCE(FullName, '') AS FullName,
                                        COALESCE(Ac_No, 0) AS Ac_No,
                                        COALESCE(RoomNo, '') AS RoomNo,
                                        COALESCE(Ward_no, '') AS Ward_no,
                                        COALESCE(MobileNo, '') AS MobileNo,
                                        CONVERT(VARCHAR,BirthDate, 23) AS BirthDate,
                                        COALESCE(Education, '') AS Education,
                                        COALESCE(UpdatedStatus, '') AS UpdatedStatus,
                                        COALESCE(SocietyName, '') AS SocietyName,
                                        COALESCE(UpdateByUser, '') AS UpdateByUser,
                                        COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                                        CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                                    FROM $DBName..Dw_VotersInfo 
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, Voter_Id, List_No, Age, Sex, FullName, Ac_No, RoomNo, Ward_no, MobileNo, BirthDate, Education, UpdatedStatus, SocietyName,UpdateByUser
                                    UNION
                                    SELECT 
                                        COALESCE(Voter_Cd, 0) AS Voter_Cd,
                                        COALESCE(Voter_Id, 0) AS Voter_Id,
                                        COALESCE(List_No, 0) AS List_No,
                                        COALESCE(Age, 0) AS Age,
                                        COALESCE(Sex, '') AS Sex,
                                        COALESCE(FullName, '') AS FullName,
                                        COALESCE(Ac_No, 0) AS Ac_No,
                                        COALESCE(RoomNo, '') AS RoomNo,
                                        COALESCE(Ward_no, '') AS Ward_no,
                                        COALESCE(MobileNo, '') AS MobileNo,
                                        CONVERT(VARCHAR,BirthDate, 23) AS BirthDate,
                                        COALESCE(Education, '') AS Education,
                                        COALESCE(UpdatedStatus, '') AS UpdatedStatus,
                                        COALESCE(SocietyName, '') AS SocietyName,
                                        COALESCE(UpdateByUser, '') AS UpdateByUser,
                                        COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                                        CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                                    FROM $DBName..NewVoterRegistration
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, Voter_Id, List_No, Age, Sex, FullName, Ac_No, RoomNo, Ward_no, MobileNo, BirthDate, Education, UpdatedStatus, SocietyName,UpdateByUser
                                    ) AS tb
                                INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                                $PostExecutive
                                $SearchValueCond
                                $PostFromToDateCond
                                $PostSearchNotSearchVal
                                GROUP BY tb.OrnetUpdateByUser,um.ExecutiveName,tb.Voter_Cd,tb.Voter_Id,tb.List_No,tb.Age,tb.Sex,tb.FullName,tb.Ac_No,tb.RoomNo,tb.Ward_no,tb.MobileNo,tb.BirthDate,tb.Education,tb.UpdatedStatus,tb.SocietyName,tb.UpdateByUser
                                ORDER BY um.ExecutiveName
                                ";

    $TotalRecordsWithCondArr = $db->ExecutveQueryMultipleRowSALData($ULB,$TotalRecordsWithCondQuery , $userName, $appName, $developmentMode);
    $totalRecordswithFilter = $TotalRecordsWithCondArr[0]['TotalRecords'];

    $offset = ($page - 1) * $rowperpage;
    $recordsQuery = "";
    $data = array();

    // Fetched Records
    $recordsQuery = "SELECT tb.OrnetUpdateByUser,um.ExecutiveName,tb.Voter_Cd,tb.Voter_Id,tb.List_No,tb.Age,tb.Sex,tb.FullName,tb.Ac_No,tb.RoomNo,tb.Ward_no,tb.MobileNo,tb.BirthDate,tb.Education,tb.UpdatedStatus,tb.SocietyName,tb.UpdateByUser
                    FROM (
                        SELECT 
                            COALESCE(Voter_Cd, 0) AS Voter_Cd,
                            COALESCE(Voter_Id, 0) AS Voter_Id,
                            COALESCE(List_No, 0) AS List_No,
                            COALESCE(Age, 0) AS Age,
                            COALESCE(Sex, '') AS Sex,
                            COALESCE(FullName, '') AS FullName,
                            COALESCE(Ac_No, 0) AS Ac_No,
                            COALESCE(RoomNo, '') AS RoomNo,
                            COALESCE(Ward_no, '') AS Ward_no,
                            COALESCE(MobileNo, '') AS MobileNo,
                            CONVERT(VARCHAR,BirthDate, 23) AS BirthDate,
                            COALESCE(Education, '') AS Education,
                            COALESCE(UpdatedStatus, '') AS UpdatedStatus,
                            COALESCE(SocietyName, '') AS SocietyName,
                            COALESCE(UpdateByUser, '') AS UpdateByUser,
                            COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                            CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                        FROM $DBName..Dw_VotersInfo
                        WHERE OrnetUpdateByUser IS NOT NULL
                        GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, Voter_Id, List_No, Age, Sex, FullName, Ac_No, RoomNo, Ward_no, MobileNo, BirthDate, Education, UpdatedStatus, SocietyName,UpdateByUser
                        UNION
                        SELECT
                            COALESCE(Voter_Cd, 0) AS Voter_Cd,
                            COALESCE(Voter_Id, 0) AS Voter_Id,
                            COALESCE(List_No, 0) AS List_No,
                            COALESCE(Age, 0) AS Age,
                            COALESCE(Sex, '') AS Sex,
                            COALESCE(FullName, '') AS FullName,
                            COALESCE(Ac_No, 0) AS Ac_No,
                            COALESCE(RoomNo, '') AS RoomNo,
                            COALESCE(Ward_no, '') AS Ward_no,
                            COALESCE(MobileNo, '') AS MobileNo,
                            CONVERT(VARCHAR,BirthDate, 23) AS BirthDate,
                            COALESCE(Education, '') AS Education,
                            COALESCE(UpdatedStatus, '') AS UpdatedStatus,
                            COALESCE(SocietyName, '') AS SocietyName,
                            COALESCE(UpdateByUser, '') AS UpdateByUser,
                            COALESCE(OrnetUpdateByUser, '') AS OrnetUpdateByUser,
                            CONVERT(VARCHAR,OrnetUpdatedDate, 23) AS OrnetUpdatedDate
                        FROM $DBName..NewVoterRegistration
                        WHERE OrnetUpdateByUser IS NOT NULL
                        GROUP BY OrnetUpdateByUser, OrnetUpdatedDate, Voter_Cd, Voter_Id, List_No, Age, Sex, FullName, Ac_No, RoomNo, Ward_no, MobileNo, BirthDate, Education, UpdatedStatus, SocietyName,UpdateByUser
                    ) AS tb
                    INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                    $PostExecutive
                    $SearchValueCond
                    $PostFromToDateCond
                    $PostSearchNotSearchVal
                    GROUP BY tb.OrnetUpdateByUser,um.ExecutiveName,tb.Voter_Cd,tb.Voter_Id,tb.List_No,tb.Age,tb.Sex,tb.FullName,tb.Ac_No,tb.RoomNo,tb.Ward_no,tb.MobileNo,tb.BirthDate,tb.Education,tb.UpdatedStatus,tb.SocietyName,tb.UpdateByUser
                    ORDER BY um.ExecutiveName
                    OFFSET $start ROWS
                    FETCH NEXT $rowperpage ROWS ONLY";

    $dtList1 = new DbOperation();
    $data = $db->ExecutveQueryMultipleRowSALData($ULB,$recordsQuery , $userName, $appName, $developmentMode);
       
    return array(
        'data' => $data,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecordswithFilter,
    );
}

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$data = fetchData($page);

header('Content-Type: application/json');
echo json_encode($data);
exit();
?>