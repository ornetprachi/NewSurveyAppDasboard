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
            $PostExecutive = " AND OrnetUpdateByUser = '".$_POST['ExecutiveVal']."'";
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

    
    $TotalRecordsQuery = "SELECT COUNT(DISTINCT tb.OrnetUpdateByUser) AS TotalRecords
                                FROM (
                                    SELECT 
                                        OrnetUpdateByUser,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                            THEN 1 ELSE 0 END) AS searched,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                            THEN 1 ELSE 0 END) AS Notsearched,
                                        OrnetUpdatedDate
                                    FROM $DBName..Dw_VotersInfo 
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                                    UNION
                                    SELECT 
                                        OrnetUpdateByUser,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                            THEN 1 ELSE 0 END) AS searched,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                            THEN 1 ELSE 0 END) AS Notsearched,
                                        OrnetUpdatedDate
                                    FROM $DBName..NewVoterRegistration
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                                ) AS tb
                                INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                                $PostExecutive
                                $PostFromToDateCond;";

    $TotalRecordsArr = $db->ExecutveQueryMultipleRowSALData($ULB,$TotalRecordsQuery , $userName, $appName, $developmentMode);

    $totalRecords = $TotalRecordsArr[0]['TotalRecords'];

    $SearchValueCond = "";
    $searchValue = trim($searchValue);
    if($searchValue != ""){
        $SearchValueCond= " AND um.ExecutiveName LIKE '%$searchValue%'";
    }

    // Total records with filter
    $TotalRecordsWithCondArr = array();
    $TotalRecordsWithCondQuery = "SELECT COUNT(DISTINCT tb.OrnetUpdateByUser) AS TotalRecords
                                FROM (
                                    SELECT 
                                        OrnetUpdateByUser,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                            THEN 1 ELSE 0 END) AS searched,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                            THEN 1 ELSE 0 END) AS Notsearched,
                                        OrnetUpdatedDate
                                    FROM $DBName..Dw_VotersInfo 
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                                    UNION
                                    SELECT 
                                        OrnetUpdateByUser,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                            THEN 1 ELSE 0 END) AS searched,
                                        SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                            THEN 1 ELSE 0 END) AS Notsearched,
                                        OrnetUpdatedDate
                                    FROM $DBName..NewVoterRegistration
                                    WHERE OrnetUpdateByUser IS NOT NULL
                                    GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                                ) AS tb
                                INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                                $SearchValueCond
                                $PostExecutive
                                $PostFromToDateCond
                                ";

    $TotalRecordsWithCondArr = $db->ExecutveQueryMultipleRowSALData($ULB,$TotalRecordsWithCondQuery , $userName, $appName, $developmentMode);
    $totalRecordswithFilter = $TotalRecordsWithCondArr[0]['TotalRecords'];

    $offset = ($page - 1) * $rowperpage;
    $recordsQuery = "";
    $data = array();

    // Fetched Records
    $recordsQuery = "SELECT tb.OrnetUpdateByUser,um.ExecutiveName,SUM(tb.searched) as Search,SUM(tb.Notsearched) as NotSearch
                    FROM (
                        SELECT 
                            OrnetUpdateByUser,
                            SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                THEN 1 ELSE 0 END) AS searched,
                            SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                THEN 1 ELSE 0 END) AS Notsearched,
                            OrnetUpdatedDate
                        FROM $DBName..Dw_VotersInfo 
                        WHERE OrnetUpdateByUser IS NOT NULL
                        GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                        UNION
                        SELECT 
                            OrnetUpdateByUser,
                            SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') != '' OR COALESCE(UpdateByUser,'') != '' OR OrnetUpdateByUser IS NOT NULL OR UpdateByUser IS NOT NULL)
                                THEN 1 ELSE 0 END) AS searched,
                            SUM(CASE WHEN (COALESCE(OrnetUpdateByUser,'') = '' OR COALESCE(UpdateByUser,'') = '' OR OrnetUpdateByUser IS NULL OR UpdateByUser IS NULL)
                                THEN 1 ELSE 0 END) AS Notsearched,
                            OrnetUpdatedDate
                        FROM $DBName..NewVoterRegistration
                        WHERE OrnetUpdateByUser IS NOT NULL
                        GROUP BY OrnetUpdateByUser, OrnetUpdatedDate
                    ) AS tb
                    INNER JOIN Survey_Entry_Data..User_Master AS um ON tb.OrnetUpdateByUser = um.UserName
                    $SearchValueCond
                    $PostExecutive
                    $PostFromToDateCond
                    GROUP BY tb.OrnetUpdateByUser,um.ExecutiveName
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