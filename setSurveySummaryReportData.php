<section id="dashboard-analytics">
<?php

// include 'api/includes/DbOperation.php';
$db=new DbOperation();

$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

$Div = $_SESSION['SurveyUA_Div']; 

if($ServerIP == '92.204.145.32'){
    $Ip = '[103.14.99.154].';
}else{
    $Ip = '';
}

if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}else{
    $ServerIP ="103.14.99.154";
}

// if($ServerIP == "103.14.97.58"){
// }
// echo $ServerIP;

if($_SERVER['REQUEST_METHOD'] === "POST") {
    if(isset($_POST['ExecutiveSearchExec']) || isset($_POST['MobileSearchExec']))
    {
        $_SESSION['SurveyUA_Div'] = "ExecAndMobReport";
        $Div = $_SESSION['SurveyUA_Div'];
        $_SESSION['SurveyUA_Div_Inner'] = "ExecutiveReportDiv";

    }else if(isset($_POST['MobileSearchMobile']))
    {
        $_SESSION['SurveyUA_Div'] = "ExecAndMobReport";
        $Div = $_SESSION['SurveyUA_Div']; 
        $_SESSION['SurveyUA_Div_Inner'] = "MobileReportDiv";
    }else{
        $_SESSION['SurveyUA_Div_Inner'] = "";
    }
}

if(isset($_SESSION['SurveyUA_Status'])){
    $Filter = $_SESSION['SurveyUA_Status']; 
    
    if($Filter == 'INACTIVE'){
    $cond = " AND DeactiveFlag = 'D'";
    }else{
        $cond = " AND DeactiveFlag IS NULL";
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
    $WorkForFilter = "WHERE tb1.WorkingDays BETWEEN '$WorkingDaysExec' AND '$ToWorkingDaysExec' ";
    }else{
        $WorkForFilter = "";
    }
}else{
    $WorkingDaysExec = ''; 
    $ToWorkingDaysExec = '';
    
    
}
if($ULB == 'PANVEL'){
    $con ="AND ssd.ElectionName = 'PT188'";
$JoinCon ="";
}else
if($ULB == 'NMMC'){
    $con ="AND ssd.ElectionName = 'NMMC_ES_151' AND ssm.ElectionName = 'NMMC_ES_151'";
$JoinCon ="";
}else{
    $con ="";
    $JoinCon =" AND  ssd.SurveyBy = ss.UserName COLLATE Latin1_General_CI_AI";
}
if($ULB == 'TOK'){
    $elec = 'UMC';
}else if($ULB == 'NS2024' || $ULB == 'RM2024'){
    $elec = 'KDMC';
}else{
    $elec = $ULB;
}
 $sql2 = "SELECT 
        COALESCE(ssd.SiteName, '') AS SiteName,
		COALESCE(ssm.Ward_No, '') AS Ward_No,
		COALESCE(ssm.Ac_No, '') AS Ac_No,
        COALESCE(ssm.ClientName, '') AS ClientName,
        COALESCE(ssm.SiteStatus, '') AS SiteStatus,
		(SElECT (SUM(Voting)/COUNT(DISTINCT(COALESCE(Panel_Code, '1'))))  FROM [$ServerIP].MH_CH_WarRoom.dbo.ElectionResults 
        WHERE Ward_No = ssm.Ward_No AND ULB = '$elec' AND ElectionYear = (SELECT MAX(ElectionYear) FROM [$ServerIP].MH_CH_WarRoom.dbo.ElectionResults WHERE ULB = '$elec') Group By Ward_No) As Result,
        COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
        --COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS SocietyCount,
        COALESCE(sum(ssd.NewRooms),'') AS Rooms,
		-- SUM(CASE WHEN ssd.SurveyBy IS NOT NULL THEN 1 ELSE 0 END) AS RoomSurveyDone,
		SUM(CASE WHEN ssd.SurveyBy IS NOT NULL  OR ssd.DwUpdatedDate IS NOT NULL OR ssd.NewVoterUpdatedDate IS NOT NULL OR ssd.LockRoomUpdatedDate IS NOT NULL  THEN 1 ELSE 0 END) AS SocietyCount,
        COALESCE(sum(ssd.RoomSurveyDone),0) AS RoomSurveyDone,
        COALESCE(sum(ssd.TotalVoters),0) AS TotalVoters,
        COALESCE(sum(ssd.TotalNonVoters),0) AS TotalNonVoters,
        COALESCE(sum(ssd.LockRoom),0) AS LockRoom,
        COALESCE(sum(ssd.BirthdaysCount),0) AS BirthdaysCount,
        COALESCE(sum(ssd.LBS),0) AS LBS,
        COALESCE(sum(ssd.TotalMobileCount),0) AS TotalMobileCount
        FROM DataAnalysis..SurveySummary as ssd
        --LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd $JoinCon)
        INNER JOIN Survey_Entry_Data..Site_Master as ssm on(ssd.SiteName = ssm.SiteName) 
        --INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
        WHERE ssd.ULB = '$ULB' AND ssm.Ward_No <> 100 $con
        GROUP BY ssd.SiteName,ssm.Ward_No,ssm.ClientName,ssm.Ac_No,ssm.SiteStatus
        ORDER BY ssd.SiteName";
        // print_r($sql2);
        // die();
$CountListMain = $db->ExecutveQueryMultipleRowSALData($sql2, $userName, $appName, $developmentMode);
// print_r($CountListMain);
$SupervisorQuery = "SELECT 
                    COALESCE(ssm.SupervisorName,'') AS SupervisorName,
                    COALESCE(em.MobileNo,'') AS MobileNo,
                    COALESCE(ssd.SiteName,'') AS Sites,
                    COALESCE(ssm.SiteStatus,'') AS SiteStatus,
                    COALESCE(ssm.ManagerName,'') As SiteManager,
                    COALESCE(em1.MobileNo,'') AS MobileNo1,
                    COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
                    COALESCE(count(DISTINCT(ss.Society_Cd)),'') AS SocietyCount,
                    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
                    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
                    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
                    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
                    COALESCE(count( ss.SurveyBy),0) AS Executive,
                    COALESCE(sum(ss.LBS),0) AS LBS,
                    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
                    FROM DataAnalysis..SurveySummary as ssd
                    LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd )
                    INNER JOIN Survey_Entry_Data..Site_Master as ssm on(ssd.Site_Cd = ssm.Site_Cd) 
                    LEFT JOIN Survey_Entry_Data..Executive_Master as em on (ssm.SupervisorName =  em.ExecutiveName)
                    LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (ssm.ManagerName =  em1.ExecutiveName)
                    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                    WHERE elm.ULB = '$ULB' AND ssm.Ward_No <> 100
                    GROUP BY ssm.SupervisorName,ssd.SiteName,ssm.ManagerName,em.MobileNo,em1.MobileNo,ssm.SiteStatus
                    ORDER BY ssm.SupervisorName";


$SupervisorData = $db->ExecutveQueryMultipleRowSALData($SupervisorQuery, $userName, $appName, $developmentMode);
// print_r($CountListMain);
$SqlQry = " SELECT 
        COALESCE(CONVERT(VARCHAR,ss.SDate, 23), '') AS SurvyeDate,
        COALESCE(count(ss.Society_Cd),'') AS SurvyeSociety,
        COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
        COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
        COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
        COALESCE(sum(ss.LockRoom),0) AS LockRoom,
        COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
        COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
        COALESCE(sum(ss.LBS),0) AS LBS,
        COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
        FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss
        INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
        WHERE ssd.ULB = '$ULB'  
        GROUP BY CONVERT(VARCHAR,ss.SDate, 23)
        ORDER BY CONVERT(VARCHAR,ss.SDate, 23) DESC
        ";


$OverallCount = $db->ExecutveQueryMultipleRowSALData($SqlQry, $userName, $appName, $developmentMode);

$societyCountTotal = array_sum(array_column($CountListMain, 'SocietyCount'));
$roomSurveyDoneTotal = array_sum(array_column($CountListMain, 'RoomSurveyDone'));
$totalVotersTotal = array_sum(array_column($CountListMain, 'TotalVoters'));
$totalNonVotersTotal = array_sum(array_column($CountListMain, 'TotalNonVoters'));
$lockRoomTotal = array_sum(array_column($CountListMain, 'LockRoom'));
$birthdaysCountTotal = array_sum(array_column($CountListMain, 'BirthdaysCount'));
// $surveyByTotal = array_sum(array_column($CountListMain, 'SurveyBy'));
$lbsTotal = array_sum(array_column($CountListMain, 'LBS'));
$totalMobileCountTotal = array_sum(array_column($CountListMain, 'TotalMobileCount'));
 

// print_r("<pre>");
// print_r($ExecutiveWiseCount);
// print_r("</pre>");

?>
<style>
table.dataTable th, table.dataTable td {
    border-bottom: 1px solid #F8F8F8;
    border-top: 0;
    padding: 3PX;
}
table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    padding-right: 3px;
}
.card-body {
    -webkit-box-flex: 1;
    -webkit-flex: 1 1 auto;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 5px;
    font-size: 12px;
}
.card .card-header {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    border-bottom: none;
    padding: 1rem 1rem 0;
    background-color: transparent;
}
.form-control {
    display: block;
    /* width: 100%; */
    height: 30px;
    padding: 3px;
    font-size: 0.96rem;
    font-weight: 400;
    line-height: 1.25;
    color: #4E5154;
    background-color: #FFFFFF;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    -webkit-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition-duration: 0.15s, 0.15s;
    transition-timing-function: ease-in-out, ease-in-out;
    transition-delay: 0s, 0s;
    transition-property: border-color, box-shadow;
}
.card {
    margin-bottom: 10px;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}
</style>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<div class="row match-height">
    <div class="col-md-12">
            <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row pl-5">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;margin-left: 5px;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/votersvg.svg" alt="Voters" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 3px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $totalVotersTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Voters </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/NonVoter.svg" alt="Non-Voters" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $totalNonVotersTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Non-Voters </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <!-- <div class="bg-light-danger p-50  mr-2" style="background-color:white;"> -->
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/sitiessvg.svg" alt="Societies" width="40" height="60">
                                        </div>
                                    <!-- </div> -->
                                    <div class="media-body my-auto" style="margin-left: 20px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $societyCountTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Societies</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/pendingsvg.svg" alt="Lockroom" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $lockRoomTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Lockroom</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 pl-5">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/socitetiessvg.svg" alt="Room Done" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $roomSurveyDoneTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Room Done</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/Report2.png" alt="LBS" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $lbsTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">LBS</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/MobileNo.svg" alt="Mobile" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $totalMobileCountTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Mobile</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/Birthday.svg" alt="Birthday" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $birthdaysCountTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Birthday</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
            
            <?php if($ULB != 'NMMC'){ ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($Div != 'profile' && $Div != 'DateWise' && $Div != 'ExecAndMobReport'){echo "active";}else{ echo "";} ?>" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="flase">Site</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'profile'){echo "active";}else{ echo "";} ?>" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="true">Executive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'DateWise'){echo "active";}else{ echo "";} ?>" id="DateWise-tab" data-toggle="tab" href="#DateWise" aria-controls="DateWise" role="tab" aria-selected="false">Date Wise</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'QC'){echo "active";}else{ echo "";} ?>" id="QC-tab" data-toggle="tab" href="#QC" aria-controls="QC" role="tab" aria-selected="false">Qc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'ExecAndMobReport'){echo "active";}else{ echo "";} ?>" id="ExecAndMobReport-tab" data-toggle="tab" href="#ExecAndMobReport" aria-controls="ExecAndMobReport" role="tab" aria-selected="false">Executive & Mobile Report</a>
                </li>
                <?php }if($ULB == 'NMMC'  || $ULB == 'TOK' || $ULB == 'NS2024' || $ULB == 'PANVEL'){
                    
                      ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($ULB == 'NMMC'){  echo "active"; }else{ echo "";}?>" id="SocietyIssue-tab" data-toggle="tab" href="#SocietyIssue" aria-controls="SocietyIssue" role="tab" aria-selected="false">Society Issue</a>
                </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
            <div class="tab-pane <?php if($Div != 'profile' && $Div != 'DateWise' && $Div != 'ExecAndMobReport' && $ULB != 'NMMC'){echo "active";}else{ echo "";} ?>" id="home" aria-labelledby="home-tab" role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
                        <li class="nav-item">
                            <a class="nav-link active" id="Site-tab" data-toggle="tab" href="#Site" aria-controls="Site" role="tab" aria-selected="flase">Site Wise Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Overall-tab" data-toggle="tab" href="#Overall" aria-controls="Overall" role="tab" aria-selected="true">Overall Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Supervisor-tab" data-toggle="tab" href="#Supervisor" aria-controls="Supervisor" role="tab" aria-selected="true">Supervisor</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="Site" aria-labelledby="Site-tab" role="tabpanel">
                            <div class="card-header">
                                <div class="row">
                                    <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Site Wise</h4>
                                    <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showCountBtn" >Count</button>
                                </div>
                                <?php if($ExcelExportButton == "show"){ ?>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveySummaryList')">Excel</button>
                                <?php } ?>
                            </div>
                            <div class="content-body">
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover-animation table-striped table-hover" id="SurveySummaryList" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">No</th>
                                                                        <th style="background-color:#36abb9;color: white;">View</th>
                                                                        <th style="background-color:#36abb9;color: white;">Client</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Assembly Number">AcNo</th>
                                                                        <th style="background-color:#36abb9;color: white;">Ward</th>
                                                                        <th style="background-color:#36abb9;color: white;">%</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;">Voting</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;">Listing</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Survey Society">SurSoc</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Pending Society">PenSoc</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Total Rooms">TotalRo</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Rooms">Ro</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "LockRoom">LR</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:20px;" Title = "Voters">V</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title= "Locked But Survey">LBS</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile">Mob</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Birthdate">BirtDt</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Society Ratio">Soc %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Voters Ratio">V %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">NV %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LR %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LBS %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "BirthDate Ratio">BirDt %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile Ratio">Mob %</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if(sizeof($CountListMain) > 0 ){
                                                                        $srNo = 1;
                                                                        foreach ($CountListMain as $key => $value) {
                                                                        ?> 
                                                                            <tr style="padding-top:0px;">
                                                                                <td><?php echo $srNo++; ?></td>
                                                                                <td style="color: #36abb9;">
                                                                                    <a id="openModalButton" onclick="getSiteWiseAllDetail('<?php echo $value['SiteName']; ?>')"><i class="fa fa-building"></i></a>&nbsp;
                                                                                   <a id="openModalButton" onclick="getSiteWiseDetail('<?php echo $value['SiteName']; ?>')"><i class="fa fa-eye"></i></a>&nbsp;
                                                                                <a id="openModalButton" onclick="getSiteNameForMap('<?php echo $value['SiteName'];?>')"><i class="feather icon-map-pin"></i></a>
                                                                                </td>
                                                                                <td style="cursor:pointer;word-wrap:break-word;<?php if($value['SiteStatus']== 'Done'){echo "background:#AEEAB7;" ; }elseif($value['SiteStatus']== 'On Going'){ echo "background:#E9EC5C;" ;}elseif($value['SiteStatus']== 'Hold'){ echo "background:#F8956A;";}else{echo "";} ?>" title="<?php echo $value["SiteStatus"]; ?>"><?php echo "<b>" . $value["ClientName"] . "</b>"; ?></td>
                                                                                <td><?php echo "<b>" . $value["Ac_No"] . "</b>"; ?></td>
                                                                                <td><?php echo "<b>" . $value["Ward_No"] . "</b>"; ?></td>
                                                                                <td><?php if($value["Result"] != ''){echo  CEIL(($value["TotalVoters"]/$value["Result"])*100)."%";} ?></td>
                                                                                <td class="text-center"><?php echo $value["Result"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["Listing"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td class="text-center"><a id="openModalButton" onclick="getSiteWisePendingSocDetail('<?php echo $value['SiteName']; ?>')"><?php echo ($value["Listing"]-$value["SocietyCount"]); ?></a></td>
                                                                                <td class="text-center"><?php echo $value["Rooms"]+$value["LockRoom"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["LockRoom"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["LBS"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td class="text-center"><?php if($value["Listing"] != '') {echo CEIL(($value["SocietyCount"]/$value["Listing"])*100)."%"; }else{ echo "0";}?></td>
                                                                                <td class="text-center"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{ echo "0";} ?></td>
                                                                                <td class="text-center"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{ echo "0";} ?></td>
                                                                                <td class="text-center"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%"; }else{ echo "0";}?></td>
                                                                                <td class="text-center"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%"; }else{ echo "0";}?></td>
                                                                                <td class="text-center"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{ echo "0";}?></td>
                                                                                <td class="text-center"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%";}else{ echo "0";} ?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div id="SiteWiseDetail" class="SiteWiseDetail">
                            </div>
                        </div>
                         <div class="tab-pane" id="Overall" aria-labelledby="Overall-tab" role="tabpanel">
                            <div class="card-header">
                                <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report </h4>
                                
                                 <?php if($ExcelExportButton == "show"){ ?>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','OverallSummaryTable')">Excel</button>
                                <?php } ?>
                            </div>
                            <div class="content-body">
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover-animation table-striped table-hover" id="OverallSummaryTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">No</th>
                                                                        <th style="background-color:#36abb9;color: white;">Date</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Society">Soc</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Executive">Exec</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Rooms">Ro</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:10px;" Title = "Voters">V</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "NonVoters">NV</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "LockRoom">LR</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Locked But survey">LBS</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Mobile">Mob</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "BirthDate">BirDt</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Average">Avg</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Voters Ratio">V %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">NV %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LR %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LBS %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "BirthDate Ratio">BirDt %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "Mobile Ratio">Mob %</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if(sizeof($OverallCount) > 0 ){
                                                                        $srNo = 1;
                                                                        foreach ($OverallCount as $key => $value) {
                                                                        ?> 
                                                                            <tr style="padding-top:0px;">
                                                                                <td><?php echo $srNo++; ?></td>
                                                                                <td><?php echo "<b>" . $value["SurvyeDate"] . "</b>"; ?></td>
                                                                                <td><?php echo $value["SurvyeSociety"]; ?></td>
                                                                                <td><?php echo $value["SurveyBy"]; ?></td>
                                                                                <td><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td><?php echo $value["LockRoom"]; ?></td>
                                                                                <td><?php echo $value["LBS"]; ?></td>
                                                                                <td><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td><?php if($value["SurveyBy"] != '') { echo CEIL($value["RoomSurveyDone"]/$value["SurveyBy"]);}else{ echo "0";} ?></td>
                                                                                <td><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{ echo "0";} ?></td>
                                                                                <td><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%";}else{echo "0";} ?></td>
                                                                                <td><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{echo "0";} ?></td>
                                                                                <td><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="tab-pane" id="Supervisor" aria-labelledby="Supervisor-tab" role="tabpanel">
                            <div class="card-header">
                            <h4 class="card-title" style="padding:5px;margin-left:10px;">Supervisor Summary Report</h4>
                                <?php if($ExcelExportButton == "show"){ ?>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveySummaryList')">Excel</button>
                                <?php } ?>
                            </div>
                            <div class="content-body">
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                        <table class="table table-hover-animation table-striped table-hover" id="SupervisorSummary">
                                                            <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                        <!-- <th style="background-color:#36abb9;color: white;">View</th> -->
                                                                        <th style="background-color:#36abb9;color: white;">Supervisor</th>
                                                                        <th style="background-color:#36abb9;color: white;">Site Manager</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;visible:flase;">Site</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;visible:flase;" title="Executive">Exe</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;">Listing</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Survey Society">Survey Soc</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Pending Society">Pending Soc</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Rooms">Ro</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:20px;" Title = "Voters">V</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "LockRoom">LR</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title= "Locked But Survey">LBS</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile">Mob</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Birthdate">BirtDt</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Average">Avg</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Society Ratio">Soc %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Voters Ratio">V %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">NV %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LR %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LBS %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "BirthDate Ratio">BirDt %</th>
                                                                        <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile Ratio">Mob %</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if(sizeof($SupervisorData) > 0 ){
                                                                        $srNo = 1;
                                                                        foreach ($SupervisorData as $key => $value) {
                                                                        ?> 
                                                                            <tr style="padding-top:0px;">
                                                                                <td><?php echo $srNo++; ?></td>
                                                                                <!-- <td style="color: #36abb9;">
                                                                                   <a id="openModalButton" onclick="getSupervisorWiseDetail('<?php echo $value['SupervisorName']; ?>')"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;
                                                                                </td> -->
                                                                                <td style = "cursor:pointer;<?php if($value['SiteStatus']== 'Done'){echo "background:#AEEAB7;" ; }elseif($value['SiteStatus']== 'On Going'){ echo "background:#E9EC5C;" ;}elseif($value['SiteStatus']== 'Hold'){ echo "background:#F8956A;";}else{echo "";}  ?>" title ="<?php echo $value['MobileNo']; ?>"><?php echo "<b>" . $value["SupervisorName"] . "</b>"; ?></td>
                                                                                <td style = "cursor:pointer;" title ="<?php echo $value['MobileNo1']; ?>"><?php echo "<b>" . $value["SiteManager"] . "</b>"; ?></td>
                                                                                <td class="text-center"><?php echo $value["Sites"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["Executive"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["Listing"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td class="text-center" ><a id="openModalButton" onclick="getSiteWisePendingSocDetail('<?php echo $value['SiteName']; ?>')"><?php echo ($value["Listing"]-$value["SocietyCount"]); ?></a></td>
                                                                                <td class="text-center"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["LockRoom"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["LBS"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td class="text-center"><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td class="text-center"><?php if($value["Executive"] != '' && $value["Executive"] != 0){echo CEIL($value["RoomSurveyDone"]/$value["Executive"]); }  ?></td>
                                                                                <td class="text-center"><?php if($value["SocietyCount"] != '' && $value["SocietyCount"] != 0){echo CEIL(($value["SocietyCount"]/$value["Listing"])*100)."%"; }?></td>
                                                                                <td class="text-center"><?php if($value["TotalVoters"] != '' && $value["TotalVoters"] != 0){echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                                                <td class="text-center"><?php if($value["TotalNonVoters"] != '' && $value["TotalNonVoters"] != 0){echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                                                <td class="text-center"><?php if($value["LockRoom"] != '' && $value["LockRoom"] != 0){echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%"; }?></td>
                                                                                <td class="text-center"><?php if($value["LBS"] != '' && $value["LBS"] != 0){echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%";} ?></td>
                                                                                <td class="text-center"><?php if($value["BirthdaysCount"] != '' && $value["BirthdaysCount"] != 0){echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                                                <td class="text-center"><?php if($value["TotalMobileCount"] != '' && $value["TotalMobileCount"] != 0){echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }  ?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane <?php if($Div == 'profile'){echo "active";}else{ echo "";} ?>" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                    <?php 
                        include 'ExecutiveOverallData.php';
                    ?>
                    
                    <div id="SurveySummaryExecutiveDataLoad">
                        <?php //include 'pages/ExecutiveWiseDetail.php'; ?>
                    </div>
                </div>
                <div class="tab-pane <?php if($Div == 'DateWise'){echo "active";}else{ echo "";} ?>"  id="DateWise" aria-labelledby="DateWise-tab" role="tabpanel">
                    <?php  
                    if(
                        (isset($_SESSION['SurveyUA__FromDate_For_SummaryReport']) && !empty($_SESSION['SurveyUA__FromDate_For_SummaryReport'])) &&
                        (isset($_SESSION['SurveyUA__ToDate_For_SummaryReport']) && !empty($_SESSION['SurveyUA__ToDate_For_SummaryReport'])) ||
                        (isset($_SESSION['SurveyUA__SiteName_For_SummaryReport']) ) || (isset($_SESSION['SurveyUA__WorkingDays_For_SummaryReport']))
                        || (isset($_SESSION['SurveyUA__ToWorkingdays_For_SummaryReport']))
                    ){
                        $fromdate = $_SESSION['SurveyUA__FromDate_For_SummaryReport'];
                        $todate = $_SESSION['SurveyUA__ToDate_For_SummaryReport'];
                        $Site = $_SESSION['SurveyUA__SiteName_For_SummaryReport'];
                        $WorkingDays = $_SESSION['SurveyUA__WorkingDays_For_SummaryReport'];
                        $ToWorkingDays = $_SESSION['SurveyUA__ToWorkingdays_For_SummaryReport'];

                        if($Site != ''){
                            $condition = "AND ssd.SiteName = '$Site'";
                        }else{
                            $condition = "";
                        }
                        if($WorkingDays != ''){
                            $WorkDayCon = " WHERE tb1.WorkingDays BETWEEN '$WorkingDays' AND '$ToWorkingDays'";
                        }else{
                            $WorkDayCon = " ";
                        }
                    }else{
                        $fromdate = date('Y-m-d');
                        $todate = date('Y-m-d');
                        $Site = '';
                        $WorkingDays = '';
                        $ToWorkingDays = '';
                    }
                            $ExQuery = "SELECT * FROM (
                            SELECT 
                            COALESCE(em.ExecutiveName,'') AS ExecutiveName ,
                            COALESCE(em.MobileNo,'') AS MobileNo ,
                            COALESCE(um.UserName,'') AS UserName , 
                            COALESCE(ssd.ElectionName,'') AS ElectionName, 
                            COALESCE(CONVERT(DATE,ss.SDate,23),'') AS SurveyDate,
                            COALESCE(CONVERT(DATE,em.JoiningDate,34),'') AS JoiningDate,
                            COALESCE(ssd.SiteName,'') AS SiteName,
                            COALESCE(sm.SupervisorName,'') AS SupervisorName,
                            COALESCE(em1.MobileNo,'') AS SupervisorMobile,
                            COALESCE(count(ss.Society_Cd),0) AS SocietyCount, 
                            COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
                            COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
                            COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                            COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
                            COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
                            COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
                            COALESCE(sum(ss.LBS),0) AS LBS, 
                            COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
                            (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(DATE, DwUpdatedDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
                            WHERE SurveyBy = um.UserName COLLATE Latin1_General_CI_AI
                            GROUP BY SurveyBy, CONVERT(DATE, DwUpdatedDate, 23)) AS t1) AS WorkingDays
                            FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss 
                            INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
                            INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName COLLATE Latin1_General_CI_AI)
                            INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd)
                            INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                            INNER JOIN Survey_Entry_Data..Site_Master as sm on (ssd.SiteName = sm.SiteName)
                            LEFT JOIN [$ServerIP].Survey_Entry_Data.dbo.Executive_Master as em1 on (sm.Supervisor_Cd = em1.Executive_Cd)
                            WHERE elm.ULB = '$ULB' AND CONVERT(DATE,ss.SDate,23) BETWEEN '$fromdate' AND '$todate' $condition
                            GROUP BY em.ExecutiveName, um.UserName,CONVERT(DATE,ss.SDate,23),CONVERT(DATE,em.JoiningDate,34),em.MobileNo,ssd.SiteName,sm.SupervisorName
                            ,em1.MobileNo,ssd.ElectionName) as tb1
							$WorkDayCon
                            ORDER BY tb1.ExecutiveName,tb1.SurveyDate DESC";


                            $ExecutiveDateWiseCount = $db->ExecutveQueryMultipleRowSALData($ExQuery, $userName, $appName, $developmentMode);

                            $totalSocietyTotal = array_sum(array_column($ExecutiveDateWiseCount, 'SocietyCount'));
                            $totalRoomTotal = array_sum(array_column($ExecutiveDateWiseCount, 'RoomSurveyDone'));
                            $totalVotersTotal = array_sum(array_column($ExecutiveDateWiseCount, 'TotalVoters'));
                            $totalNonVotersTotal = array_sum(array_column($ExecutiveDateWiseCount, 'TotalNonVoters'));
                            $totalLockRoomTotal = array_sum(array_column($ExecutiveDateWiseCount, 'LockRoom'));
                            $totalLBSTotal = array_sum(array_column($ExecutiveDateWiseCount, 'LBS'));
                            $totalBirthdaysCountTotal = array_sum(array_column($ExecutiveDateWiseCount, 'BirthdaysCount'));
                            $totalMobileCntCountTotal = array_sum(array_column($ExecutiveDateWiseCount, 'TotalMobileCount'));

                            $ExAvrgQuery = "SELECT 
                                        COALESCE(em.ExecutiveName,'') AS ExecutiveName ,
                                        COALESCE(em.MobileNo,'') AS MobileNo ,
                                        COALESCE(CONVERT(varchar,ss.SDate,23),'') AS SurveyDate,
                                        COALESCE(CONVERT(varchar,em.JoiningDate,34),'') AS JoiningDate,
                                        COALESCE(count(ss.Society_Cd),0) AS SocietyCount, 
                                        COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
                                        COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
                                        COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                                        COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
                                        COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
                                        COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
                                        COALESCE(sum(ss.LBS),0) AS LBS, 
                                        COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
                                        (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, DwUpdatedDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
                                        WHERE SurveyBy = em.UserName COLLATE Latin1_General_CI_AI
                                        GROUP BY SurveyBy, CONVERT(VARCHAR, DwUpdatedDate, 23)) AS t1) AS WorkingDays
                                        FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss 
                                        INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
                                        INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName COLLATE Latin1_General_CI_AI)
                                        INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd)
                                        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                                        WHERE elm.ULB = '$ULB' AND CONVERT(varchar,ss.SDate,23) BETWEEN '$fromdate' AND '$todate'
                                        GROUP BY em.ExecutiveName, em.UserName,CONVERT(varchar,ss.SDate,23),CONVERT(varchar,em.JoiningDate,34),em.MobileNo
                                        ORDER BY em.ExecutiveName,CONVERT(varchar,ss.SDate,23) DESC;";


                                    $ExecutiveAvrgDateWiseCount = $db->ExecutveQueryMultipleRowSALData($ExAvrgQuery, $userName, $appName, $developmentMode);
                                    $ExecutiveCount = sizeof($ExecutiveAvrgDateWiseCount);
                                    $SumRooms = array_sum(array_column($ExecutiveAvrgDateWiseCount, 'RoomSurveyDone'));
                                    $SiteQuery = "SELECT 
                                    COALESCE(CONVERT(varchar,ssd.SocietyMasterUpdatedDate,23),'') AS SurveyDate, 
                                    COALESCE(ssd.SiteName,'') AS SiteName, 
                                    COALESCE(sm.Ward_No,'') AS Ward_No,  
                                    COALESCE(sm.Ac_No,'') AS Ac_No, 
                                    COALESCE(sm.SiteStatus,'') AS SiteStatus, 
                                    COALESCE(sm.SupervisorName,'') AS SupervisorName, 
                                    COALESCE(em1.MobileNo,'') AS SupervisorMobile,
                                    COALESCE(COUNT(DISTINCT(ss.SurveyBy)),'') AS Executive,
                                    COALESCE(count(ss.Society_Cd),0) AS SocietyCount, 
                                    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
                                    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
                                    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
                                    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
                                    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
                                    COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
                                    COALESCE(sum(ss.LBS),0) AS LBS, 
                                    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount, (SELECT COUNT(*) FROM (SELECT SurveyBy,
                                    CONVERT(VARCHAR, SDate, 23) AS SurveyDate 
                                    FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss
                                    GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays 
                                    FROM DataAnalysis..SurveySummary as ssd 
                                    LEFt JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ss.Society_Cd = ssd.Society_Cd) 
                                    LEFT JOIN Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName COLLATE Latin1_General_CI_AI) 
                                    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
                                    INNER JOIN Survey_Entry_Data..Site_Master as sm on (ssd.SiteName = sm.SiteName) 
                                    LEFT JOIN [$ServerIP].Survey_Entry_Data.dbo.Executive_Master as em1 on (sm.Supervisor_Cd = em1.Executive_Cd)
                                    WHERE elm.ULB = '$ULB' AND CONVERT(varchar,ssd.SocietyMasterUpdatedDate,23) BETWEEN '$fromdate' AND '$todate'
                                    GROUP BY 
                                    CONVERT(varchar,ssd.SocietyMasterUpdatedDate,23),ssd.SiteName,sm.SupervisorName,em1.MobileNo,sm.Ward_No,sm.Ac_No,sm.SiteStatus
                                    ORDER BY CONVERT(varchar,ssd.SocietyMasterUpdatedDate,23) DESC;";


                        $SiteDateWiseCount = $db->ExecutveQueryMultipleRowSALData($SiteQuery, $userName, $appName, $developmentMode);

                     $TableULBQuery = "SELECT ULB,CONVERT(varchar,ed.SurveyDate,23) as SurveyDate,em.Designation,
                     (SELECT COUNT(t.Attendance) as P 
                         FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName) 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Master as em1 on (t.Executive_Cd = em1.Executive_Cd) 
                         WHERE t.Attendance = 1  AND t1.ULB = elm.ULB AND CONVERT(varchar,t.SurveyDate,23) = CONVERT(varchar,ed.SurveyDate,23) 
                         AND em.Designation = em1.Designation COLLATE SQL_Latin1_General_CP1_CI_AS
                         ) as Present ,
                     (SELECT COUNT(t.Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName) 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Master as em1 on (t.Executive_Cd = em1.Executive_Cd)
                         WHERE t.Attendance = 2 AND t1.ULB = elm.ULB AND CONVERT(varchar,t.SurveyDate,23) = CONVERT(varchar,ed.SurveyDate,23)
                         AND em.Designation = em1.Designation COLLATE SQL_Latin1_General_CP1_CI_AS
                         ) as Absent, 
                     (SELECT COUNT(t.Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName) 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Master as em1 on (t.Executive_Cd = em1.Executive_Cd)
                         WHERE t.Attendance = 0 AND t1.ULB = elm.ULB AND CONVERT(varchar,t.SurveyDate,23)= CONVERT(varchar,ed.SurveyDate,23)
                         AND em.Designation = em1.Designation COLLATE SQL_Latin1_General_CP1_CI_AS
                         ) as Assign, 
                     (SELECT COUNT(t.Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t 
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Master as em1 on (t.Executive_Cd = em1.Executive_Cd) 
                         WHERE t.Attendance = 3 AND t1.ULB = elm.ULB AND CONVERT(varchar,t.SurveyDate,23) = CONVERT(varchar,ed.SurveyDate,23)
                         AND em.Designation = em1.Designation COLLATE SQL_Latin1_General_CP1_CI_AS
                         ) as HalfDay, 
                     (SELECT COUNT(t.Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t 
                         Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
                         INNER join [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Master as em1 on (t.Executive_Cd = em1.Executive_Cd) 
                         WHERE t.Attendance = 4 AND t1.ULB = elm.ULB AND CONVERT(varchar,t.SurveyDate,23) = CONVERT(varchar,ed.SurveyDate,23)
                         AND em.Designation = em1.Designation COLLATE SQL_Latin1_General_CP1_CI_AS
                         ) as Training 
                     FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as ed 
                     INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Site_Master as sm on (ed.SiteName = sm.SiteName) 
                     INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as elm on (ed.ElectionName = elm.ElectionName) 
                     INNER JOIN [Survey_Entry_Data]..Executive_Master as em on (ed.Executive_Cd = em.Executive_Cd ) 
                     WHERE CONVERT(varchar,ed.SurveyDate,23) BETWEEN '$fromdate' AND '$todate'  
                     GROUP BY ULB,CONVERT(varchar,ed.SurveyDate,23),em.Designation;";
                        $TableULBData = $db->ExecutveQueryMultipleRowSALData($TableULBQuery, $userName, $appName, $developmentMode);

                            $OPresent = array_sum(array_column($TableULBData, 'Present'));
                            $OAbsent = array_sum(array_column($TableULBData, 'Absent'));
                            $OTraining = array_sum(array_column($TableULBData, 'Training'));
                            $OHalfDay = array_sum(array_column($TableULBData, 'HalfDay'));
                            $OAssign = array_sum(array_column($TableULBData, 'Assign'));
                                    // print_r("<pre>");
                                    // print_r($ExecutiveDateWiseCount);
                                    // print_r("</pre>");

                                    $map = [];
                            foreach($TableULBData AS $key=>$value){
                                $ULB = $value['ULB'];                                                            
                                $Designation = $value['Designation'];
                                $SurveyDate = $value['SurveyDate'];
                                if(array_key_exists($value['ULB'],$map)){
                                    // if(array_key_exists($value['SurveyDate'],$map)){
                                        
                                    if($value['Designation'] == "SP" || $value['Designation'] == "Survey Supervisor"){
                                        $Present = $map[$value['ULB']][$value['SurveyDate']]['Supervisor']['P'];
                                        $Absent = $map[$value['ULB']][$value['SurveyDate']]['Supervisor']['A'];
                                        $Trainig = $map[$value['ULB']][$value['SurveyDate']]['Supervisor']['T'];
                                        $HalfDay = $map[$value['ULB']][$value['SurveyDate']]['Supervisor']['HF'];
                                        $Assign = $map[$value['ULB']][$value['SurveyDate']]['Supervisor']['Assign'];
                                        $map[$value['ULB']][$value['SurveyDate']]['Supervisor'] = [
                                            "Designation" => 'Supervisor',
                                            "Total" => $Present+$value['Present']+$Absent+$value['Absent']+$Trainig+$value['Training']+$HalfDay+$value['HalfDay']+$Assign+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $Present+$value['Present'],
                                            "A" => $Absent+$value['Absent'],
                                            "T" => $Trainig+$value['Training'],
                                            "HF" => $HalfDay+$value['HalfDay'],
                                            "Assign" => $Assign+$value['Assign']
                                        ];

                                    }elseif($value['Designation'] == "Survey Executive" || $value['Designation'] == "SE-Belapur"){
                                        
                                        
                                        $Present = $map[$value['ULB']][$value['SurveyDate']]['Executive']['P'];
                                        $Absent = $map[$value['ULB']][$value['SurveyDate']]['Executive']['A'];
                                        $Trainig = $map[$value['ULB']][$value['SurveyDate']]['Executive']['T'];
                                        $HalfDay = $map[$value['ULB']][$value['SurveyDate']]['Executive']['HF'];
                                        $Assign = $map[$value['ULB']][$value['SurveyDate']]['Executive']['Assign'];


                                        $map[$value['ULB']][$value['SurveyDate']]['Executive'] = [
                                            "Designation" => 'Executive',
                                            "Total" => $Present+$value['Present']+$Absent+$value['Absent']+$Trainig+$value['Training']+$HalfDay+$value['HalfDay']+$Assign+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $Present+$value['Present'],
                                            "A" => $Absent+$value['Absent'],
                                            "T" => $Trainig+$value['Training'],
                                            "HF" => $HalfDay+$value['HalfDay'],
                                            "Assign" => $Assign+$value['Assign']
                                        ];
                                    }elseif($value['Designation'] == "Site Manager" || $value['Designation'] == "Manager"){

                                        $Present = $map[$value['ULB']][$value['SurveyDate']]['SiteManager']['P'];
                                        $Absent = $map[$value['ULB']][$value['SurveyDate']]['SiteManager']['A'];
                                        $Trainig = $map[$value['ULB']][$value['SurveyDate']]['SiteManager']['T'];
                                        $HalfDay = $map[$value['ULB']][$value['SurveyDate']]['SiteManager']['HF'];
                                        $Assign = $map[$value['ULB']][$value['SurveyDate']]['SiteManager']['Assign'];

                                        $map[$value['ULB']][$value['SurveyDate']]['SiteManager'] = [
                                            "Designation" => 'Site Manager',
                                            "Total" => $Present+$value['Present']+$Absent+$value['Absent']+$Trainig+$value['Training']+$HalfDay+$value['HalfDay']+$Assign+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $Present+$value['Present'],
                                            "A" => $Absent+$value['Absent'],
                                            "T" => $Trainig+$value['Training'],
                                            "HF" => $HalfDay+$value['HalfDay'],
                                            "Assign" => $Assign+$value['Assign']
                                        ]; 
                                    }else{
                                        $Present = $map[$value['ULB']][$value['SurveyDate']]['$Designation']['P'];
                                        $Absent = $map[$value['ULB']][$value['SurveyDate']]['$Designation']['A'];
                                        $Trainig = $map[$value['ULB']][$value['SurveyDate']]['$Designation']['T'];
                                        $HalfDay = $map[$value['ULB']][$value['SurveyDate']]['$Designation']['HF'];
                                        $Assign = $map[$value['ULB']][$value['SurveyDate']]['$Designation']['Assign'];

                                        $map[$value['ULB']][$value['SurveyDate']]['$Designation'] = [
                                            "Designation" => $value['Designation'], 
                                            "Total" => $Present+$value['Present']+$Absent+$value['Absent']+$Trainig+$value['Training']+$HalfDay+$value['HalfDay']+$Assign+$value['Assign'], 
                                            "Date" => $value['SurveyDate'],
                                            "P" => $Present+$value['Present'],
                                            "A" => $Absent+$value['Absent'],
                                            "T" => $Trainig+$value['Training'],
                                            "HF" => $HalfDay+$value['HalfDay'],
                                            "Assign" => $Assign+$value['Assign']
                                        ];
                                    }
                                }else{
                                    if($value['Designation'] == "SP" || $value['Designation'] == "Survey Supervisor"){

                                        $map[$value['ULB']][$value['SurveyDate']]['Supervisor'] = [
                                            "Designation" => 'Supervisor',
                                            "Total" =>$value['Present']+$value['Absent']+$value['Training']+$value['HalfDay']+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $value['Present'],
                                            "A" => $value['Absent'],
                                            "T" => $value['Training'],
                                            "HF" => $value['HalfDay'],
                                            "Assign" => $value['Assign']
                                        ];
                                    }elseif($value['Designation'] == "Survey Executive" || $value['Designation'] == "SE-Belapur"){
                                        $map[$value['ULB']][$value['SurveyDate']]['Executive'] = [
                                            "Designation" => 'Executive',
                                            "Total" => $value['Present']+$value['Absent']+$value['Training']+$value['HalfDay']+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $value['Present'],
                                            "A" => $value['Absent'],
                                            "T" => $value['Training'],
                                            "HF" => $value['HalfDay'],
                                            "Assign" => $value['Assign']
                                        ];
                                    }elseif($value['Designation'] == "Site Manager" || $value['Designation'] == "Manager"){
                                            $map[$value['ULB']][$value['SurveyDate']]['SiteManager'] = [
                                            "Designation" => 'Site Manager',
                                            "Total" => $value['Present']+$value['Absent']+$value['Training']+$value['HalfDay']+$value['Assign'],
                                            "Date" => $value['SurveyDate'],
                                            "P" => $value['Present'],
                                            "A" => $value['Absent'],
                                            "T" => $value['Training'],
                                            "HF" => $value['HalfDay'],
                                            "Assign" => $value['Assign']
                                        ];
                                    }else{
                                        $map[$value['ULB']][$value['SurveyDate']][$Designation] = [
                                        "Designation" => $value['Designation'],  
                                        "Total" => $value['Present']+$value['Absent']+$value['Training']+$value['HalfDay']+$value['Assign'],
                                        "Date" => $value['SurveyDate'],
                                        "P" => $value['Present'],
                                        "A" => $value['Absent'],
                                        "T" => $value['Training'],
                                        "HF" =>$value['HalfDay'],
                                        "Assign" =>$value['Assign']
                                        ];
                                    }
                                }
                            }
                            ?>
                    <div class="content-body">
                        <div class="row" style="margin-left:10px;">
                            <div class="col-xs-2 col-xl-2 col-md-3 col-12">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="fromdate" value="<?php echo $fromdate; ?>"  class="form-control" placeholder="From Date" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="todate" value="<?php echo $todate; ?>"  class="form-control" placeholder="To Date" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Search Site</label>
                                    <div class="controls"> 
                                        <input type="Search" name="SiteSearch" value=""  class="form-control" placeholder="Search Site" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-1 col-xl-1 col-md-1 col-12">
                                <div class="form-group">
                                    <label>WorkinD</label>
                                    <div class="controls"> 
                                        <input type="search" name="Workingdays" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"  class="form-control" placeholder="WorkingDays">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-1 col-xl-1 col-md-1 col-12">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="controls"> 
                                        <input type="search" name="ToWorkingdays" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"  class="form-control" placeholder="WorkingDays">
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-xs-2 col-xl-2 col-md-2 col-12" >
                                <div class="form-group" style="padding-left:2px;">
                                    <label style="padding-left:2px;">Working Days</label>
                                   <div class="row">
                                         <div class="col-md-4 col-12" style="margin-left:12px;padding:3px;"> 
                                            <div class="controls"> 
                                                <input type="Search" name="Workingdays" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" class="form-control" placeholder="From">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12" style="padding:3px;">
                                            <div class="controls"> 
                                                <input type="Search" name="ToWorkingdays" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"  class="form-control" placeholder="To">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>


                            <div class="col-xs-1 col-md-1 col-xl-1">
                                <div class="controls" style="padding-top:20px;">
                                    <button type="button" class="btn btn-primary" onclick="GetFromAndToDate()"  id="RefreshBtn">
                                            Refresh 
                                    </button>
                                </div>
                                <script>
                                    document.getElementById('RefreshBtn').addEventListener("click", function(){
                                                    this.classList.add("loading");
                                                    this.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
                                                });
                                </script>
                            </div>
                            <div class="col-xs-2 col-md-2 col-xl-2 text-center" style="margin-left:20px;">
                            <div class="card text-white bg-gradient-success text-center">
                                <h5 class="text-white" style="padding-top:10px;">Total Rooms - <?php echo $SumRooms; ?></h5>
                                <h5 class="text-white">Total Executive - <?php echo $ExecutiveCount; ?> <br> <b> Average - <?php if($SumRooms != 0 && $ExecutiveCount != 0){ echo CEIL($SumRooms/$ExecutiveCount);} ?></b></h5>
                            </div>
                            </div>
                        </div>
                        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
                        <script>
                            $(document).ready(function() {
                            var table = $('#DateWiseSurveySummaryList').DataTable({
                                lengthMenu: [ [-1,20, 40, 50], ["All",20, 40, 50] ],
                                columnDefs: [
                                { targets: [12,13,14,15], visible: false } // Initially hide Columns 3 and 4 (indexes 2 and 3)
                                ]
                            });

                            $('#showDateExeCountBtn').click(function() {
                                var columnIndexes = [12,13,14,15]; // Indexes of the columns to show

                                var columns = table.columns().visible(true); // Hide all columns initially 

                                // columnIndexes.forEach(function(index) {
                                //   columns.column(index).visible(true);
                                // });

                                table.columns.adjust().draw(); // Adjust and redraw the DataTable after showing columns
                            });
                            });
                        </script>
                        <div style="margin-left:15px;">
                        
                        </div>
                        
                        <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
                            <li class="nav-item">
                                <a class="nav-link active" id="Executive-tab" data-toggle="tab" href="#Executive" aria-controls="Executive" role="tab" aria-selected="flase">Executive</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="SiteWise-tab" data-toggle="tab" href="#SiteWise" aria-controls="SiteWise" role="tab" aria-selected="true">Site(Supervisor)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="Attendance-tab" data-toggle="tab" href="#Attendance" aria-controls="Attendance" role="tab" aria-selected="true">Attendance</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="AttendanceList-tab" data-toggle="tab" href="#AttendanceList" aria-controls="AttendanceList" role="tab" aria-selected="true">Attendance List</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="Executive" aria-labelledby="Executive-tab" role="tabpanel">
                                
                                <div class="card-header">
                                    <div class="row">
                                        <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Date Wise Executive</h4>
                                        <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showDateExeCountBtn" >Count</button>
                                    </div>
                                    <?php if($ExcelExportButton == "show"){ ?>
                                        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','DateWiseSurveySummaryList')">Excel</button>
                                    <?php } ?>
                                </div>
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">  
                                        <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                        
                                                            <table class="table table-hover-animation table-striped table-hover DateWiseSurveySummaryListClass" id="DateWiseSurveySummaryList" width="100%">
                                                            <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">No</th>
                                                                        <th style="background-color:#36abb9;color: white;">View</th>
                                                                        <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                                        <th style="background-color:#36abb9;color: white;">Supervisor Name</th>
                                                                        <th style="background-color:#36abb9;color: white;">Reference Name</th>
                                                                        <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:10px;" Title = "WorkingDate">WD</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title = "JoiningDate">JOD</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title= "Society">So</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Rooms">Rom</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:10px;" Title="Voters">V</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="LockRooms">LR</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Lockes But Survey">LBS</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Birthdate">BirtDt</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile">Mob</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Working Days">WD</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Voters Ratio">V %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">NV %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">LR %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">LBS %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">BirDt %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">Mob %</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if(sizeof($ExecutiveDateWiseCount) > 0 ){
                                                                        $srNo = 1;
                                                                        foreach ($ExecutiveDateWiseCount as $key => $value) {
                                                                        ?> 
                                                                            <tr style="padding-top:0px;">
                                                                                <td style="align:center;"><?php echo $srNo++; ?></td>
                                                                                <td style="color: #36abb9;align-items:center;text-center;">
                                                                                    <a href="index.php?p=Survey-QC-Details-View&electionName=<?php echo $value['ElectionName'] ?>&SurveyDate=<?php echo date_format($value['SurveyDate'],"d/m/Y") ?>&UserName=<?php echo $value['UserName'] ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>&SiteName=<?php echo $value["SiteName"]; ?>" target="_blank" class="">
                                                                                        <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                                                    </a>
                                                                                </td>
                                                                                <td style="align:center;cursor:pointer;<?php if(((CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '60') || 
                                                                                ((CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '70') || ((CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)) < '70' )){echo "background:#FFD6D6;" ;}else{echo "";} ?>" Title="<?php echo $value["MobileNo"]; ?>" ><?php echo "<b>" . $value["ExecutiveName"] . "</b>"; ?></td>
                                                                                <td style="align:center;cursor:pointer;" Title="<?php echo $value["SupervisorMobile"]; ?>"  >
                                                                                <?php 
                                                                                $nameParts = explode(" ", $value['SupervisorName']);
                                                                                if(Sizeof($nameParts) == 3){
                                                                                    $firstName = $nameParts[0];
                                                                                    $LastName = substr($nameParts[2], 0, 1);
                                                                                    }else{
                                                                                    
                                                                                        $firstName = $nameParts[0];
                                                                                        $LastName = substr($nameParts[1], 0, 1); 
                                                                                    }
                                                                                    echo $firstName." ".$LastName;
                                                                                    ?>
                                                                                </td>
                                                                                <td style="align:center;"><?php echo $value["ReferenceName"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["SiteName"]; ?></td>
                                                                                <td style="align:center;"><?php echo date_format($value["SurveyDate"],"d-m-Y"); ?></td>
                                                                                <td style="align:center;"><?php echo date_format($value["JoiningDate"],"d-m-Y"); ?></td>
                                                                                <td style="align:center;"><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LockRoom"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LBS"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["WorkingDays"]; ?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '60' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '70' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)) < '90' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <!-- <td><?php //echo CEIL($value["RoomSurveyDone"]/$value["WorkingDays"]); ?></td> -->
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="8">Total</th>
                                                                        <th><?php echo $totalSocietyTotal; ?></th>
                                                                        <th><?php echo $totalRoomTotal; ?></th>
                                                                        <th><?php echo $totalVotersTotal; ?></th>
                                                                        <th><?php echo $totalNonVotersTotal; ?></th>
                                                                        <th><?php echo $totalLockRoomTotal; ?></th>
                                                                        <th><?php echo $totalLBSTotal; ?></th>
                                                                        <th><?php echo $totalBirthdaysCountTotal; ?></th>
                                                                        <th><?php echo $totalMobileCntCountTotal; ?></th>
                                                                        <th></th>
                                                                        <th></td>
                                                                        <th></td>
                                                                        <th></td>
                                                                        <th></td>
                                                                        <th></td>
                                                                        <th></td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="tab-pane" id="SiteWise" aria-labelledby="SiteWise-tab" role="tabpanel">
                                <div class="card-header">
                                    <div class="row">
                                        <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Date Wise Site</h4>
                                        <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showDateSiteCountBtn" >Count</button>
                                    </div>
                                <?php if($ExcelExportButton == "show"){ ?>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','DateWiseSiteSurveySummaryList')">Excel</button>
                                    <?php } ?>
                                </div>
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">  
                                        <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                        
                                                            <table class="table table-hover-animation table-striped table-hover" id="DateWiseSiteSurveySummaryList" width="100%">
                                                            <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">No</th>
                                                                        <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                                        <th style="background-color:#36abb9;color: white;">Supervisor Name</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Assembly Number">AcNo</th>
                                                                        <th style="background-color:#36abb9;color: white;">Ward</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title= "Total Executive">Exe</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title= "Society">So</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Rooms">Rom</th>
                                                                        <th style="background-color:#36abb9;color: white;padding-left:10px;" Title="Voters">V</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="LockRooms">LR</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Lockes But Survey">LBS</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Birthdate">BirtDt</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile">Mob</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Voters Ratio">V %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="NonVoters Ratio">NV %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="LockRoom Ratio">LR %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Locked But Survey Ratio">LBS %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Birthdate Ratio">BirDt %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">Mob %</th>
                                                                        <th style="background-color:#36abb9;color: white;" Title="Average">Avg</th>
                                                                    </tr>
                                                                </thead>
                                            
                                                                <tbody>
                                                                    <?php
                                                                    if(sizeof($SiteDateWiseCount) > 0 ){
                                                                        $srNo = 1;
                                                                        foreach ($SiteDateWiseCount as $key => $value) {
                                                                        ?> 
                                                                            <tr style="padding-top:0px;">
                                                                                <td style="align:center;"><?php echo $srNo++; ?></td>
                                                                                <td style="align:center;"><?php echo $value["SiteName"]; ?></td>
                                                                                <td style="align:center;cursor:pointer;"  Title="<?php echo $value["SupervisorMobile"]; ?>">
                                                                                <?php 
                                                                                $nameParts = explode(" ", $value['SupervisorName']);
                                                                                if(Sizeof($nameParts) == 3){
                                                                                    $firstName = $nameParts[0];
                                                                                    $LastName = substr($nameParts[2], 0, 1);
                                                                                    }else{
                                                                                    
                                                                                        $firstName = $nameParts[0];
                                                                                        $LastName = substr($nameParts[1], 0, 1); 
                                                                                    }
                                                                                    echo $firstName." ".$LastName;
                                                                                    ?>
                                                                                </td>
                                                                                <td style="align:center;"><?php echo $value["Ac_No"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["Ward_No"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["Executive"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LockRoom"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LBS"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '' && ($value["TotalVoters"]+$value["TotalNonVoters"]) != 0) { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '' && $value["RoomSurveyDone"] != 0) { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["Executive"] != '' && $value["Executive"] != 0) { echo CEIL($value["RoomSurveyDone"]/$value["Executive"]); }else{echo "0";}?></td>
                                                                            </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="tab-pane" id="Attendance" aria-labelledby="Attendance-tab" role="tabpanel">
                                <div class="card-header">
                                    <div class="row">
                                        <h4 class="card-title" style="padding:5px;margin-left:10px;"> <b>Total -(<?php echo $OPresent+$OAbsent+$OTraining+$OHalfDay+$OAssign; ?>) Present - (<?php echo $OPresent; ?>)  Absent - (<?php echo $OAbsent; ?>) Training - (<?php echo $OTraining; ?>) HalfDay - (<?php echo $OHalfDay ?>) Assign - (<?php echo $OAssign; ?>)</b> </h4>
                                        <!-- <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showDateSiteCountBtn" >Count</button> -->
                                    </div>
                                <?php if($ExcelExportButton == "show"){ ?>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','AttendanceReportTable')">Excel</button>
                                    <?php } ?>
                                </div>
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">  
                                        <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                        
                                                            <table class="table table-hover-animation table-striped table-hover" id="AttendanceReportTable" width="100%">
                                                                <thead>
                                                                <tr>
                                                                        <th rowspan=2 style="border:1;background-color:#36abb9;color: white;">SrNo </th>
                                                                        <!-- <th style="background-color:#36abb9;color: white;">View </th> -->
                                                                        <th rowspan=2 style="border:1;background-color:#36abb9;color: white;">Corporation</th>
                                                                        <th rowspan=2 style="border:1;background-color:#36abb9;color: white;">Survey Date</th>
                                                                        <!-- <th style="background-color:#36abb9;color: white;">Designation</th> -->
                                                                        <th colspan=6 style="border:1;background-color:#36abb9;color: white;" >Site Manager</th>
                                                                        <th colspan=6 style="border:1;background-color:#36abb9;color: white;" >Supervisor </th>
                                                                        <th colspan=6 style="border:1;background-color:#36abb9;color: white;" >Executive</th>
                                                                        <th rowspan=2 style="border:1;background-color:#36abb9;color: white;" >Grand Total</th>
                                                                        <!-- <th style="background-color:#36abb9;color: white;">Assign Status</th> -->
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Total</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">P</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">A</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">T</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">HF</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Assing</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Total</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">P</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">A</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">T</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">HF</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Assing</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Total</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">P</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">A</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">T</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">HF</th>
                                                                        <th style="border:1;background-color:#36abb9;color: white;">Assing</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                    $SR = 1;
                                                                        foreach ($map as $ulb => $desig) {
                                                                            foreach($desig as $date=>$designations){
                                                                                // echo $date;
                                                                                // print_r("<pre>");
                                                                                // print_r($designations);
                                                                                // print_r("</pre>");
                                                                            if(array_key_exists('Executive',$designations)){ 
                                                                               $ex  = $designations['Executive']['Total'];
                                                                            }else{ 
                                                                                $ex ="0";
                                                                            } 
                                                                            
                                                                            if(array_key_exists('Supervisor',$designations)){ 
                                                                                $Sp = $designations['Supervisor']['Total'];
                                                                            }else{ 
                                                                                $Sp = "0";
                                                                            }
                                                                            if(array_key_exists('SiteManager',$designations)){ 
                                                                                // $SM = $designations['Site Manager']['Total'];
                                                                                $SM = $designations['SiteManager']['Total'];
                                                                            }else{
                                                                                $SM = "0";
                                                                            }
                                                                            
                                                                ?>
                                                                            <tr>
                                                                                <td><?php echo $SR++; ?></td>
                                                                                <td><?php echo $ulb; ?></td>
                                                                                <td><?php echo $date; ?></td>
                                                                               <?php if(array_key_exists('SiteManager',$designations)){ ?>
                                                                                    <td><?php echo $designations['SiteManager']['Total']; ?></td>
                                                                                    <td><?php echo $designations['SiteManager']['P']; ?></td>
                                                                                    <td><?php echo $designations['SiteManager']['A']; ?></td>
                                                                                    <td><?php echo $designations['SiteManager']['T']; ?></td>
                                                                                    <td><?php echo $designations['SiteManager']['HF']; ?></td>
                                                                                    <td><?php echo $designations['SiteManager']['Assign']; ?></td>
                                                                                <?php }else{ ?>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                <?php } 
                                                                                if(array_key_exists('Supervisor',$designations)){ ?>
                                                                                    <td><?php echo $designations['Supervisor']['Total']; ?></td>
                                                                                    <td><?php echo $designations['Supervisor']['P']; ?></td>
                                                                                    <td><?php echo $designations['Supervisor']['A']; ?></td>
                                                                                    <td><?php echo $designations['Supervisor']['T']; ?></td>
                                                                                    <td><?php echo $designations['Supervisor']['HF']; ?></td>
                                                                                    <td><?php echo $designations['Supervisor']['Assign']; ?></td>
                                                                                <?php }else{ ?>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                    <td>0</td>
                                                                                <?php }?>
                                                                            <?php if(array_key_exists('Executive',$designations)){ ?>
                                                                                <td><?php echo $designations['Executive']['Total']; ?></td>
                                                                                <td><?php echo $designations['Executive']['P']; ?></td>
                                                                                <td><?php echo $designations['Executive']['A']; ?></td>
                                                                                <td><?php echo $designations['Executive']['T']; ?></td>
                                                                                <td><?php echo $designations['Executive']['HF']; ?></td>
                                                                                <td><?php echo $designations['Executive']['Assign']; ?></td>
                                                                            <?php }else{?>
                                                                                <td>0</td>
                                                                                <td>0</td>
                                                                                <td>0</td>
                                                                                <td>0</td>
                                                                                <td>0</td>
                                                                                <td>0</td>
                                                                            <?php }?>
                                                                            <td><?php echo $ex+$Sp+$SM;?></td>
                                                                            </tr>
                                                                            <?php
                                                                             $OverallTotal = $OverallTotal + $ex+$Sp+$SM;
                                                                             $TotalStMan = $TotalStMan + $designations['SiteManager']['Total'];
                                                                             $TotalStManP = $TotalStManP + $designations['SiteManager']['P'];
                                                                             $TotalStManA = $TotalStManA + $designations['SiteManager']['A'];
                                                                             $TotalStManT = $TotalStManT + $designations['SiteManager']['T'];
                                                                             $TotalStManHF = $TotalStManHF + $designations['SiteManager']['HF'];
                                                                             $TotalStManAss = $TotalStManAss + $designations['SiteManager']['Assign'];
                                                                             $Totalsup = $Totalsup + $designations['Supervisor']['Total'];
                                                                             $TotalsupP = $TotalsupP + $designations['Supervisor']['P'];
                                                                             $TotalsupA = $TotalsupA + $designations['Supervisor']['A'];
                                                                             $TotalsupT = $TotalsupT + $designations['Supervisor']['T'];
                                                                             $TotalsupHF = $TotalsupHF + $designations['Supervisor']['HF'];
                                                                             $TotalsupAss = $TotalsupAss + $designations['Supervisor']['Assign'];
                                                                             $TotalExe = $TotalExe + $designations['Executive']['Total'];
                                                                             $TotalExeP = $TotalExeP + $designations['Executive']['P'];
                                                                             $TotalExeA = $TotalExeA + $designations['Executive']['A'];
                                                                             $TotalExeT = $TotalExeT + $designations['Executive']['T'];
                                                                             $TotalExeHF = $TotalExeHF + $designations['Executive']['HF'];
                                                                             $TotalExeAss = $TotalExeAss + $designations['Executive']['Assign'];
                                                                            }
                                                                                }
                                                                            ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <td colspan = 3><b>Grand Total</b></td>
                                                                    <td><B><?php echo $TotalStMan; ?></B></td>
                                                                    <td><B><?php echo $TotalStManP; ?></B></td>
                                                                    <td><B><?php echo $TotalStManA; ?></B></td>
                                                                    <td><B><?php echo $TotalStManT; ?></B></td>
                                                                    <td><B><?php echo $TotalStManHF; ?></B></td>
                                                                    <td><B><?php echo $TotalStManAss; ?></B></td>
                                                                    <td><B><?php echo $Totalsup; ?></B></td>
                                                                    <td><B><?php echo $TotalsupP; ?></B></td>
                                                                    <td><B><?php echo $TotalsupA; ?></B></td>
                                                                    <td><B><?php echo $TotalsupT; ?></B></td>
                                                                    <td><B><?php echo $TotalsupHF; ?></B></td>
                                                                    <td><B><?php echo $TotalsupAss; ?></B></td>
                                                                    <td><B><?php echo $TotalExe; ?></B></td>
                                                                    <td><B><?php echo $TotalExeP; ?></B></td>
                                                                    <td><B><?php echo $TotalExeA; ?></B></td>
                                                                    <td><B><?php echo $TotalExeT; ?></B></td>
                                                                    <td><B><?php echo $TotalExeHF; ?></B></td>
                                                                    <td><B><?php echo $TotalExeAss; ?></B></td>
                                                                    <td><B><?php echo $OverallTotal; ?></B></td>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="AttendanceModelView" id="AttendanceModelView">
                                </div>
                            </div>
                            <div class="tab-pane" id="AttendanceList" aria-labelledby="AttendanceList-tab" role="tabpanel">
                                <?php include 'AttendanceListData.php'; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="tab-pane <?php if($Div == 'QC'){echo "active";}else{ echo "";} ?>" id="QC" aria-labelledby="QC-tab" role="tabpanel">
                    <?php 
                    include 'QcDetailData.php';
                    ?>
                    <div calss="QcSiteWiseView" Id="QcSiteWiseView">

                    </div>
                </div>
                <div class="tab-pane <?php if($Div == 'ExecAndMobReport'){echo "active";}else{ echo "";} ?>" id="ExecAndMobReport" aria-labelledby="ExecAndMobReport-tab" role="tabpanel">
                    <?php 
                        include 'setExecutiveAndMobileNoWiseReports.php';
                    ?>
                </div>
                <?php if($ULB == 'NMMC'  || $ULB == 'TOK' || $ULB == 'NS2024' || $ULB == 'PANVEL'){ ?>
                <div class="tab-pane <?php if($ULB == 'NMMC'){  echo "active"; }else{ echo "";}?>" id="SocietyIssue" aria-labelledby="SocietyIssue-tab" role="tabpanel">
                    <?php
                        include "getSocietyIsssueReport.php";
                    ?>

                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<style>
    .myModal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 80%;
  height: 80%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

</style>


<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type,TableID) {
        var fn = "";
        var dl = "";
        var elt = document.getElementById(TableID);
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || (TableID+'.'+ (type || 'xlsx')));
    }

    function getSiteWisePendingSocDetail(Site){

var ajaxRequest; // The variable that makes Ajax possible!

try {
    // Opera 8.0+, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
} catch (e) {
    // Internet Explorer Browsers
    try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            // Something went wrong
            alert("Your browser broke!");
            return false;
        }
    }
}

ajaxRequest.onreadystatechange = function() {
    if (ajaxRequest.readyState == 4) {
        var ajaxDisplay = document.getElementById('SiteWiseDetail');
        ajaxDisplay.innerHTML = ajaxRequest.responseText;
        $('#MODAL_VIEW').modal('show');
    }
}
// alert(Status);
//    var div ='profile';
var queryString = "?Site="+Site;
// alert(Site);
ajaxRequest.open("POST", "SetSiteNameForPendingModalInSession.php" + queryString, true);
ajaxRequest.send(null);
}  

function getSupervisorWiseDetail(SupervisorName){

var ajaxRequest; // The variable that makes Ajax possible!

try {
    // Opera 8.0+, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
} catch (e) {
    // Internet Explorer Browsers
    try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            // Something went wrong
            alert("Your browser broke!");
            return false;
        }
    }
}

ajaxRequest.onreadystatechange = function() {
    if (ajaxRequest.readyState == 4) {
        var ajaxDisplay = document.getElementById('SiteWiseDetail');
        ajaxDisplay.innerHTML = ajaxRequest.responseText;
        $('#MODAL_VIEW').modal('show');
    }
}
// alert(Status);
//    var div ='profile';
var queryString = "?SupervisorName="+SupervisorName;
// alert(Site);
ajaxRequest.open("POST", "SetSupervisorNameForModalInSession.php" + queryString, true);
ajaxRequest.send(null);
}   

function getSiteWiseAllDetail(Site){

var ajaxRequest; // The variable that makes Ajax possible!

try {
    // Opera 8.0+, Firefox, Safari
    ajaxRequest = new XMLHttpRequest();
} catch (e) {
    // Internet Explorer Browsers
    try {
        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {
            // Something went wrong
            alert("Your browser broke!");
            return false;
        }
    }
}

ajaxRequest.onreadystatechange = function() {
    if (ajaxRequest.readyState == 4) {
        var ajaxDisplay = document.getElementById('SiteWiseDetail');
        ajaxDisplay.innerHTML = ajaxRequest.responseText;
        // $('#MODAL_VIEW').modal('show');
        $('#DetailSiteData').show();   
        $(document).ready(function () {
            $('#SiteWiseAllSociety').DataTable({
              "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
            });
        });
        $('html, body').animate({
            scrollTop: $("#SiteWiseDetail").offset().top
        }, 500); 
    }
}
// alert(Status);
//    var div ='profile';
var queryString = "?Site="+Site;
// alert(Site);
ajaxRequest.open("POST", "SetSiteNameDeatilForModalInSession.php" + queryString, true);
ajaxRequest.send(null);
}   
function dateforlist()
  {
    var date = document.getElementsByName('fdate')[0].value;
    var Tdate = document.getElementsByName('tdate')[0].value;
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4){
        var ajaxDisplay = document.getElementById('SiteWiseDetail');
        ajaxDisplay.innerHTML = ajaxRequest.responseText;        
        }
    }
// alert(date);
    if (date === '') {
        alert("Image is empty!!");
    } else{
        var queryString = "?date="+date+"&Tdate="+Tdate;
        ajaxRequest.open("POST", "SetSiteNameDeatilForModalInSession.php" + queryString, true);
        ajaxRequest.send(null); 
    }
    
    // alert(date);
  }
</script>



</section>
