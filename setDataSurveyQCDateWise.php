<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];

$Designation = $_SESSION['SurveyUA_Designation'];



// $fromDate = date('Y-m-d');
$fromDate = "2023-05-01";
$toDate = date('Y-m-d');

$Site_Cd = "";
$Pocket_Cd = "";
$ExecutiveCd = "";
// $QCAssignedTo = "";
$QCStatus = 3; 
$SurveyStatus = 0;
$SurveyQCList = array(); 
$FINALResult = array();

$societyCountTotal = 0;
$roomSurveyDoneTotal = 0;
$totalVotersTotal = 0;
$totalNonVotersTotal = 0;
$lockRoomTotal = 0;
$birthdaysCountTotal = 0;
$lbsTotal = 0;
$totalMobileCountTotal = 0;

if($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other' || $Designation == 'SP' || $Designation == 'Survey Supervisor'){
    $DesignationCond = '';
    $QCAssignedTo = "";
}else{
    $DesignationCond = 'Disabled';
    $QCAssignedTo = $_SESSION['SurveyUA_Executive_Cd_Login'];
}


if
(
    (isset($_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise']) && !empty($_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'])) && 
    (isset($_SESSION['SurveyUA_SiteName_SurveyQC_DateWise']) && !empty($_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'])) &&
    (isset($_SESSION['SurveyUA_ClientName_SurveyQC_DateWise']) && !empty($_SESSION['SurveyUA_ClientName_SurveyQC_DateWise'])) && 
    (isset($_SESSION['SurveyUA_Election_Cd']) && !empty($_SESSION['SurveyUA_Election_Cd'])) && 
    (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']))
)
{
    
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'];
    $SiteName = $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'];

    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];

    $ClientName = $_SESSION['SurveyUA_ClientName_SurveyQC_DateWise'];

}else{
    $query = "SELECT 
                COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                COALESCE(sm.ClientName,'') AS ClientName,
                COALESCE(sm.SiteName,'') AS SiteName,
                COALESCE(sm.Area, '') AS Area,
                COALESCE(sm.Ward_No,0) AS Ward_No,
                COALESCE(sm.Address,'') AS Address,
                COALESCE(sm.ElectionName,'') AS ElectionName,
                COALESCE(em.Election_Cd,0) AS Election_Cd,
                COALESCE(sm.SiteStatus,'') AS SiteStatus
            FROM Site_Master sm
            INNER JOIN Election_Master em ON (sm.ElectionName = em.ElectionName)
            WHERE em.ULB = '$ULB';
            ";

    $dataSite = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

    if(sizeof($dataSite) > 0){

        $Site_Cd = $dataSite[0]['Site_Cd'];
        $SiteName = $dataSite[0]['SiteName'];
        $electionCd = $dataSite[0]['Election_Cd'];
        $electionName = $dataSite[0]['ElectionName'];
        $ClientName = $dataSite[0]['ClientName'];

        $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'] = $Site_Cd;
        $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'] = $SiteName;

        $_SESSION['SurveyUA_Election_Cd'] = $electionCd;
        $_SESSION['SurveyUA_ElectionName'] = $electionName;

    }  
}



if(
    isset($_SESSION['SurveyQCDateWise_tbl_fromDate']) && 
    isset($_SESSION['SurveyQCDateWise_tbl_toDate']) 
)
{
    $fromDate = $_SESSION['SurveyQCDateWise_tbl_fromDate'];
    $toDate = $_SESSION['SurveyQCDateWise_tbl_toDate'];
}


$DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
 $sqlreport = "SELECT * FROM 
(
    SELECT DISTINCT(em.ExecutiveName) As Executive,em.MobileNo,
    (
        SELECT 
        COUNT(Voter_Cd) 
        FROM $DBName..Dw_VotersInfo as dw
        LEFT JOIN Survey_Entry_Data..User_Master as um1 on (dw.QC_UpdateByUser = um1.UserName COLLATE Latin1_General_CI_AI)
        LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (um1.Executive_Cd = em1.Executive_Cd)
        WHERE dw.SiteName = '$SiteName' AND SF = 1
        AND um1.Executive_Cd = em.Executive_Cd
        AND CONVERT(varchar,QC_UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'
    ) as Voter,
    (
        SELECT 
        COUNT(Voter_Cd) 
        FROM $DBName..NewVoterRegistration as nv
        LEFT JOIN Survey_Entry_Data..User_Master as um on (nv.QC_UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI)
        LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (um.Executive_Cd = em1.Executive_Cd)
        WHERE nv.SiteName = '$SiteName'
        AND um.Executive_Cd = em.Executive_Cd
        AND CONVERT(varchar,QC_UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'
    ) as NonVoter,
    (
        SELECT COUNT(SocietyName)
        FROM Survey_Entry_Data..Society_Master 
        WHERE SiteName = '$SiteName' AND QC_Done_Flag = 1 And QC_Assign_To = em.Executive_Cd
        AND CONVERT(varchar,QC_Done_Date,23) BETWEEN '$fromDate' AND '$toDate'
    ) as SocDone,
    (
        SELECT COUNT(SocietyName)
        FROM Survey_Entry_Data..Society_Master 
        WHERE SiteName = '$SiteName' AND QC_Done_Flag = 2 And QC_Assign_To = em.Executive_Cd
        AND CONVERT(varchar,QC_Done_Date,23) BETWEEN '$fromDate' AND '$toDate'
    ) as SocRejected,
    (
        SELECT COUNT(SocietyName)
        FROM Survey_Entry_Data..Society_Master 
        WHERE SiteName = '$SiteName' AND QC_Done_Flag = 3 And QC_Assign_To = em.Executive_Cd
        AND CONVERT(varchar,QC_Done_Date,23) BETWEEN '$fromDate' AND '$toDate'
    ) as SocAssignButPending
    FROM Survey_Entry_Data..Executive_Master as em 
    WHERE em.Designation IN ('Data Entry Executive','DE')
) as t
WHERE t.SocAssignButPending <> 0 OR t.SocDone <> 0  OR t.NonVoter <> 0 OR t.SocRejected <> 0 OR t.Voter <> 0
ORDER BY t.Voter DESC
";

$resultReport = $db->ExecutveQueryMultipleRowSALData($ULB,$sqlreport , $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($resultReport);
// print_r("</pre>");
$FINALResult = $db->RunQueryDataSurveyQCDateWise($ULB,$userName, $appName,  $developmentMode, $Executive_Cd, $fromDate , $toDate,$DBName,$SiteName);

if(sizeof($FINALResult)>0){
    $societyCountTotal = array_sum(array_column($FINALResult, 'SocietyCount'));
    $roomSurveyDoneTotal = array_sum(array_column($FINALResult, 'RoomSurvey'));
    $totalVotersTotal = array_sum(array_column($FINALResult, 'Voters'));
    $totalNonVotersTotal = array_sum(array_column($FINALResult, 'NonVoters'));
    $lockRoomTotal = array_sum(array_column($FINALResult, 'LockRoom'));
    $birthdaysCountTotal = array_sum(array_column($FINALResult, 'BirthDayCount'));
    $lbsTotal = array_sum(array_column($FINALResult, 'LBS'));
    $totalMobileCountTotal = array_sum(array_column($FINALResult, 'MobileCount'));
    $totalQCDONECountTotal = array_sum(array_column($FINALResult, 'QC_Done'));
    $totalQCPENDINGCountTotal = array_sum(array_column($FINALResult, 'QC_Pending'));
    $totalCONVERTEDCountTotal = array_sum(array_column($FINALResult, 'Converted'));
    $totalVNVQCCount = $totalQCDONECountTotal+$totalQCPENDINGCountTotal;
}

// print_r("<pre>");
// print_r($FINALResult);
// print_r("</pre>");

// Create DateTime objects from the string dates
$fromDateTime = new DateTime($fromDate);
$toDateTime = new DateTime($toDate);

// Calculate the difference between the two dates
$dateInterval = $fromDateTime->diff($toDateTime);

// Get the number of days from the DateInterval object
$daysBetween = $dateInterval->days;

?>


<script>document.body.style.zoom="90%"</script>
<style type="text/css">
    
    img.docimg{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg:hover{
        z-index: 9999999990909090990909;
        transform: scale(5.2); 
        position: relative;
    }
    img.docimg1{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg1:hover{
        z-index: 9999999990909090990909;
        transform: scale(10.2); 
    }

    img.docimg2{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg2:hover{
        z-index: 9999999990909090990909;
        transform: scale(3.2); 
    }

    .collapse-simple > .card > .card-header > *::before {
        content: '\f2f9';
        font: normal normal normal 14px/1 'Material-Design-Iconic-Font';
        font-size: 1.25rem;
        text-rendering: auto;
        position: absolute;
        top: 8px;
        right: 0;
        color: black;
    }

    th{
        background: lightgrey;
    }

    .card{
        margin-bottom: 10px;
    }

    .card-body{
        padding-top:10px;
        padding-bottom:2px;
        padding-right:10px;
        padding-left:10px;
    }

    .table th, .table td {
        padding: 3px;
        text-align: left; 
        vertical-align: middle;
    }
</style>

<div class="row match-height">
    <div class="col-md-12">
            <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row pl-5">
                            <div class="col-xs-6 col-xl-1 col-md-1 col-12">
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/NonVoter.svg" alt="Non-Voters" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <a href="index.php?p=SiteWiseNonVoter&electionName=<?php echo $electionName ?>&electionCd=<?php echo $electionCd ?>&SiteName=<?php echo $SiteName;?>" style="color:black;">
                                            <h4 class="font-weight-bolder mb-0"><?php echo $totalNonVotersTotal; ?></h4>
                                            
                                            <p class="card-text font-small-4 mb-0">Non-Voters </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/VoterQC.svg" alt="Birthday" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0" title="QC Done / QC Pending"><?php echo "<b style='color:green;'>" . $totalQCDONECountTotal . "</b> + <b style='color:red;'>" . $totalQCPENDINGCountTotal . "</b> = <b>" . $totalVNVQCCount . "</b>" ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">V-NV QC</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 pl-5">
                            <div class="col-xs-6 col-xl-1 col-md-1 col-12">
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
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
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2" style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/ConvertVNV.svg" alt="Birthday" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $totalCONVERTEDCountTotal; ?></h4>
                                        
                                        <p class="card-text font-small-4 mb-0">Converted</p>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-6 col-xl-1 col-md-1 col-12">
                            </div> -->
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
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-site-Survey-QC-DateWise.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>From Date</label>
                                <div class="controls"> 
                                    <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>To Date</label>
                                <div class="controls"> 
                                    <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <input type="hidden" name="electionName" id="electionName" value="<?php echo $electionName; ?>"  class="form-control">
                                    <button type="button" class="btn btn-primary float-right" onclick="getSurveyQCDateWiseTableFilterData()">
                                            Refresh 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="align-items:center">
        <center>
            <div id='spinnerLoader2' style='display:none'>
                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
            </div>
        </center>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------ -->
<ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item">
            <a class="nav-link active" id="Site-tab" data-toggle="tab" href="#Site" aria-controls="Site" role="tab" aria-selected="flase">Site</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="QCExecutiveReport-tab" data-toggle="tab" href="#QCExecutiveReport" aria-controls="Site" role="QCExecutiveReport" aria-selected="flase">QC Executive</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="Qc-tab" data-toggle="tab" href="#Qc" aria-controls="Qc" role="tab" aria-selected="true">Qc Report</a>
        </li>
    </ul>
<div class="tab-content">
    <div class="tab-pane active" id="Site" aria-labelledby="Site-tab" role="tabpanel">
        <div class="row match-height" id="SurveyQCTblDataHideDiv">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-start">
                        <h4 class="card-title" style="color: rgb(54, 171, 185);"><b><?php echo $ClientName . " (" . $SiteName . ")"; ?></b></h4>
                    </div>
                    <div class="card-header" Style="padding-top:5px;">
                        <h5><?php echo $fromDate . " - " . $toDate . " ( Total Days - " . $daysBetween . " ) | " . " Total Executive - " . sizeof($FINALResult); ?></h5>
                    </div>
                    <div class="content-body">
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        
                                        <div class="card-content">
                                            <div class="card-body card-dashboard pt-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover-animation table-striped table-hover" id="SurveyQCLiveSiteWise">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                                <th style="background-color:#36abb9;color: white;">Action</th>
                                                                <th style="background-color:#36abb9;color: white;">Executive</th>
                                                                <th style="background-color:#36abb9;color: white;">Soc</th>
                                                                <th style="background-color:#36abb9;color: white;">Ro</th>
                                                                <th style="background-color:#36abb9;color: white;">V</th>
                                                                <th style="background-color:#36abb9;color: white;">NV</th>
                                                                <th style="background-color:#36abb9;color: white;">LR</th>
                                                                <th style="background-color:#36abb9;color: white;">BirDt</th>
                                                                <th style="background-color:#36abb9;color: white;">Mob</th>
                                                                <th style="background-color:#36abb9;color: white;">LBS</th>
                                                                <th style="background-color:#36abb9;color: white;">QC</th>
                                                                <th style="background-color:#36abb9;color: white;">Conv</th>
                                                                <th style="background-color:#36abb9;color: white;">V %</th>
                                                                <th style="background-color:#36abb9;color: white;">NV %</th>
                                                                <th style="background-color:#36abb9;color: white;">LR %</th>
                                                                <th style="background-color:#36abb9;color: white;">LBS %</th>
                                                                <th style="background-color:#36abb9;color: white;">BirDt %</th>
                                                                <th style="background-color:#36abb9;color: white;">Mob %</th>
                                                                <th style="background-color:#36abb9;color: white;">QC %</th>
                                                                <th style="background-color:#36abb9;color: white;">Conv %</th>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(sizeof($FINALResult) > 0){
                                                                    $srNo = 1;
                                                                    foreach($FINALResult AS $Key=>$value){  
                                                                    ?>
                                                                    <tr <?php if(($value["Voters"]+$value["NonVoters"]) != ''){ if((CEIL(($value["NonVoters"]/($value["Voters"]+$value["NonVoters"]))*100)) > '50'){ echo "style='background-color:#FFC0C5'";} } ?>>
                                                                        <td><?php echo $srNo++; ?></td>
                                                                        <td style="color: #36abb9;align-items:center;text-center">
                                                                            <a href="index.php?p=Survey-QC-Details_DateWise-View&electionName=<?php echo $electionName ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&UserName=<?php echo $value['UserName'] ?>&SiteName=<?php echo $SiteName ?>" target="_blank" class="">
                                                                                <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td title="<?php echo $value['Mobile']; ?>"><?php echo "<b>" . $value['ExecutiveName'] . "</b>";?></td>
                                                                        <td><?php echo $value['SocietyCount']?></td>
                                                                        <td><?php echo $value['RoomSurvey']-$value['LockRoom']?></td>
                                                                        <td><?php echo $value['Voters']?></td>
                                                                        <td><?php echo $value['NonVoters']?></td>
                                                                        <td><?php echo $value['LockRoom']?></td>
                                                                        <td><?php echo $value['BirthDayCount']?></td>
                                                                        <td><?php echo $value['MobileCount']?></td>
                                                                        <td><?php echo $value['LBS']?></td>
                                                                        <td title="QC Done / QC Pending"><?php echo $value['QC_Done'] . "/" . $value['QC_Pending']; ?></td>
                                                                        <td title="<?php echo $value['Converted'] . " out of " . $value['QC_Done'] . " converted"; ?>" ><?php echo $value['Converted']?></td>
                                                                        <td><?php if(($value["Voters"]+$value["NonVoters"]) != '') { echo CEIL(($value["Voters"]/($value["Voters"]+$value["NonVoters"]))*100)."%";}else{ echo "0";} ?></td>
                                                                        <td><?php if(($value["Voters"]+$value["NonVoters"]) != '') { echo CEIL(($value["NonVoters"]/($value["Voters"]+$value["NonVoters"]))*100)."%";}else{ echo "0";} ?></td>
                                                                        <td><?php if($value["RoomSurvey"] != '') { echo CEIL(($value["LockRoom"]/$value["RoomSurvey"])*100)."%"; }else{ echo "0";}?></td>
                                                                        <td><?php if($value["RoomSurvey"] != '') { echo CEIL(($value["LBS"]/$value["RoomSurvey"])*100)."%"; }else{ echo "0";}?></td>
                                                                        <td><?php if(($value["Voters"]+$value["NonVoters"]) != '') { echo CEIL(($value["BirthDayCount"]/($value["Voters"]+$value["NonVoters"]))*100)."%"; }else{ echo "0";}?></td>
                                                                        <td><?php if($value["RoomSurvey"] != '') { echo CEIL(($value["MobileCount"]/$value["RoomSurvey"])*100)."%";}else{ echo "0";} ?></td>
                                                                        <td><?php if(($value["Voters"]+$value["NonVoters"]) != '') { echo CEIL(($value["QC_Done"]/($value["QC_Done"]+$value["QC_Pending"]))*100)."%";}else{ echo "0";} ?></td>
                                                                        <td><?php if(($value["Converted"]+$value["QC_Done"]) != '') { echo CEIL(($value["Converted"]/$value["QC_Done"])*100)."%";}else{ echo "0";} ?></td>

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
    </div>
    <div class="tab-pane" id="QCExecutiveReport" aria-labelledby="QCExecutiveReport-tab" role="tabpanel">
        <div class="row match-height" id="SurveyQCTblDataHideDiv">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-start">
                        <h4 class="card-title" style="color: rgb(54, 171, 185);"><b><?php echo $ClientName . " (" . $SiteName . ")"; ?></b></h4>
                    </div>
                    <div class="card-header" Style="padding-top:5px;">
                        <h5><?php echo $fromDate . " - " . $toDate . " ( Total Days - " . $daysBetween . " ) | " . " Total Executive - " . sizeof($resultReport); ?></h5>
                    </div>
                    <div class="content-body">
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        
                                        <div class="card-content">
                                            <div class="card-body card-dashboard pt-0">
                                                <div class="table-responsive">
                                                    <table class="table table-hover-animation table-striped table-hover" id="SurveyQCExecutiveSiteWise">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                                <th style="background-color:#36abb9;color: white;">Executive</th>
                                                                <th style="background-color:#36abb9;color: white;">QC Done</th>
                                                                <th style="background-color:#36abb9;color: white;">Voters</th>
                                                                <th style="background-color:#36abb9;color: white;">NonVoters</th>
                                                                <th style="background-color:#36abb9;color: white;">Assign But Pending</th>
                                                                <th style="background-color:#36abb9;color: white;">Qc Rejected</th>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(sizeof($resultReport) > 0){
                                                                    $srNo = 1;
                                                                    foreach($resultReport AS $Key=>$value){  
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $srNo++; ?></td>
                                                                        <td title="<?php echo $value['MobileNo']; ?>"><?php echo "<b>" . $value['Executive'] . "</b>";?></td>
                                                                        <td><?php echo $value['SocDone']?></td>
                                                                        <td><?php echo $value['Voter']?></td>
                                                                        <td><?php echo $value['NonVoter']?></td>
                                                                        <td><?php echo $value['SocAssignButPending']?></td>
                                                                        <td><?php echo $value['SocRejected']?></td>
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
    </div>
    <div class="tab-pane" id="Qc" aria-labelledby="Qc-tab" role="tabpanel">
        <?php 
            include 'QcDetailData.php';
        ?>
        <div calss="QcSiteWiseView" Id="QcSiteWiseView">

        </div>
    </div>
</div>
