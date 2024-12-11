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

$Div = $_SESSION['SurveyUA_Div']; 

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


$sql2 = " SELECT 
        COALESCE(ssd.SiteName, '') AS SiteName,
        COALESCE(ssm.ClientName, '') AS ClientName,
        COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
        COALESCE(count(DISTINCT(ss.Society_Cd)),'') AS SocietyCount,
        COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
        COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
        COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
        COALESCE(sum(ss.LockRoom),0) AS LockRoom,
        COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
        COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
        COALESCE(sum(ss.LBS),0) AS LBS,
        COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
        FROM DataAnalysis..SurveySummary as ssd
        LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd)
        INNER JOIN Survey_Entry_Data..Site_Master as ssm on(ssd.Site_Cd = ssm.Site_Cd) 
        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
        WHERE elm.ULB = '$ULB' AND ssm.Ward_No <> 100
        GROUP BY ssd.SiteName,ssm.ClientName
        ORDER BY ssd.SiteName";


$CountListMain = $db->ExecutveQueryMultipleRowSALData($sql2, $userName, $appName, $developmentMode);
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
$surveyByTotal = array_sum(array_column($CountListMain, 'SurveyBy'));
$lbsTotal = array_sum(array_column($CountListMain, 'LBS'));
$totalMobileCountTotal = array_sum(array_column($CountListMain, 'TotalMobileCount'));
$TotalRooms = $roomSurveyDoneTotal+$lockRoomTotal+$lbsTotal;
 

$sqlQuery = "SELECT COALESCE(em.ExecutiveName,'') AS ExecutiveName , 
            CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag,
            CASE WHEN CONVERT(DATE, ExpDate, 103) >= CONVERT(DATE, GETDATE(), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired, 
            COALESCE(CONVERT(varchar,em.JoiningDate,34),'') AS JoiningDate,
            COALESCE(rm.ReferenceName,'') AS ReferenceName,
            COALESCE(count(ss.Society_Cd),0) AS SocietyCount,
            COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
            COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
            COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
            COALESCE(sum(ss.LockRoom),0) AS LockRoom,
            COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
            COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
            COALESCE(sum(ss.LBS),0) AS LBS,
            COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
            (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
            WHERE SurveyBy = um.UserName 
            GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays 
            FROM DataAnalysis..SurveySummaryExecutiveDateWise AS  ss 
            INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
            INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName) 
            INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
            INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
            LEFT JOIN $Ip ChankyaAdmin.dbo.EnquiryMaster as enm on (em.Enquiry_Cd = enm.Enquiry_Cd)
            LEFT JOIN $Ip ChankyaAdmin.dbo.Enquiry_Details as ed on (enm.Enquiry_Cd = ed.Enquiry_Cd  AND ed.AddedDate = (SELECT MAX(edd.AddedDate) 
            FROM $Ip ChankyaAdmin.dbo.Enquiry_Details as edd WHERE edd.Enquiry_Cd = ed.Enquiry_Cd))
            LEFT JOIN $Ip ChankyaAdmin.dbo.Reference_Master as rm on (ed.ReferenceSource = rm.Reference_Cd)  
            WHERE elm.ULB = '$ULB' $cond
            GROUP BY em.ExecutiveName, um.UserName,CONVERT(varchar,em.JoiningDate,34) ,um.DeactiveFlag,um.ExpDate,rm.ReferenceName
            ORDER BY em.ExecutiveName;";


$ExecutiveWiseCount = $db->ExecutveQueryMultipleRowSALData($sqlQuery, $userName, $appName, $developmentMode);

$Query1 = "SELECT COALESCE(em.ExecutiveName,'') AS ExecutiveName , 
CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag,
CASE WHEN CONVERT(DATE, ExpDate, 103) >= CONVERT(DATE, GETDATE(), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired, 
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
(SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
WHERE SurveyBy = um.UserName 
GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays 
FROM DataAnalysis..SurveySummaryExecutiveDateWise AS  ss 
INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName) 
INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
WHERE elm.ULB = '$ULB'
GROUP BY em.ExecutiveName, um.UserName,CONVERT(varchar,em.JoiningDate,34) ,um.DeactiveFlag,um.ExpDate 
ORDER BY em.ExecutiveName;";


$Count = $db->ExecutveQueryMultipleRowSALData($Query1, $userName, $appName, $developmentMode);
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
// print_r("<pre>");
// print_r($ExecutiveWiseCount);
// print_r("</pre>");

?>
<style>
    table.dataTable th, table.dataTable td {
    border-bottom: 1px solid #F8F8F8;
    border-top: 0;
    padding: 5PX;
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
    width: 100%;
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
                                        <img src="app-assets/images/votersvg.svg" alt="Voters" width="30" height="60">
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
                                        
                                        <p class="card-text font-small-4 mb-0">Rooms</p>
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
                <li class="nav-item">
                    <a class="nav-link <?php if($Div != 'profile' && $Div != 'DateWise'){echo "active";}else{ echo "";} ?>" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="flase">Site</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'profile'){echo "active";}else{ echo "";} ?>" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="true">Executive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php if($Div == 'DateWise'){echo "active";}else{ echo "";} ?>" id="DateWise-tab" data-toggle="tab" href="#DateWise" aria-controls="DateWise" role="tab" aria-selected="false">Date Wise</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane <?php if($Div != 'profile' && $Div != 'DateWise'){echo "active";}else{ echo "";} ?>" id="home" aria-labelledby="home-tab" role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
                        <li class="nav-item">
                            <a class="nav-link active" id="Site-tab" data-toggle="tab" href="#Site" aria-controls="Site" role="tab" aria-selected="flase">Site Wise Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="Overall-tab" data-toggle="tab" href="#Overall" aria-controls="Overall" role="tab" aria-selected="true">Overall Summary</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="Site" aria-labelledby="Site-tab" role="tabpanel">
                            <div class="card-header">
                                <h4 class="card-title">Summary Report - Site Wise</h4>
                                <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveySummaryList')">Excel</button>
                            </div>
                            <div class="content-body">
                                <section id="basic-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body card-dashboard">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover-animation table-striped table-hover" id="SurveySummaryList">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                                        <th style="background-color:#36abb9;color: white;">Client</th>
                                                                        <th style="background-color:#36abb9;color: white;">Site</th>
                                                                        <th style="background-color:#36abb9;color: white;">Listing</th>
                                                                        <th style="background-color:#36abb9;color: white;">Society</th>
                                                                        <th style="background-color:#36abb9;color: white;">TotalRooms</th>
                                                                        <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                                        <th style="background-color:#36abb9;color: white;">Voters</th>
                                                                        <th style="background-color:#36abb9;color: white;">NonVoters</th>
                                                                        <th style="background-color:#36abb9;color: white;">Lockroom</th>
                                                                        <th style="background-color:#36abb9;color: white;">LBS</th>
                                                                        <th style="background-color:#36abb9;color: white;">Mobile</th>
                                                                        <th style="background-color:#36abb9;color: white;">Birthday</th>
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
                                                                                <td><?php echo "<b>" . $value["ClientName"] . "</b>"; ?></td>
                                                                                <td><?php echo "<b>" . $value["SiteName"] . "</b>"; ?></td>
                                                                                <td><?php echo $value["Listing"]; ?></td>
                                                                                <td><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td><?php echo ($value["RoomSurveyDone"] + $value["LockRoom"] + $value["LBS"]); ?></td>
                                                                                <td><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td><?php echo $value["LockRoom"]; ?></td>
                                                                                <td><?php echo $value["LBS"]; ?></td>
                                                                                <td><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td><?php echo $value["BirthdaysCount"]; ?></td>
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
                         <div class="tab-pane" id="Overall" aria-labelledby="Overall-tab" role="tabpanel">
                            <div class="card-header">
                                <h4 class="card-title">Summary Report </h4>
                                <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','OverallSummaryTable')">Excel</button>
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
                                                                        <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                                        <th style="background-color:#36abb9;color: white;">Date</th>
                                                                        <th style="background-color:#36abb9;color: white;">Society</th>
                                                                        <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                                        <th style="background-color:#36abb9;color: white;">Voters</th>
                                                                        <th style="background-color:#36abb9;color: white;">NonVoters</th>
                                                                        <th style="background-color:#36abb9;color: white;">Lockroom</th>
                                                                        <th style="background-color:#36abb9;color: white;">LBS</th>
                                                                        <th style="background-color:#36abb9;color: white;">Mobile</th>
                                                                        <th style="background-color:#36abb9;color: white;">Birthday</th>
                                                                        <th style="background-color:#36abb9;color: white;">Average</th>
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
                                                                                <td><?php echo CEIL($value["RoomSurveyDone"]/$value["SurveyBy"]); ?></td>
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
                    ?>
                    <div class="card-header">
                        <h4 class="card-title">Summary Report - Executive Wise</h4>
                        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveySummaryExecutiveList')">Excel</button>
                    </div>
                    <div class="card-header">
                       <h6>Total Executive - <?php echo $TotalExecutive;?></h6>
                    </div>                     
                    &nbsp;&nbsp;
                    <button type="button" class="btn btn-flat-success mr-1 mb-1" onclick="getExeFilter('ACTIVE')" style="<?php if($Filter == 'ACTIVE'){ echo "background-color:#28C76F;color: white;";} ?>">Active<?php echo "(".$Active.")" ;?></button>
                    <button type="button" class="btn btn-flat-danger mr-1 mb-1" onclick="getExeFilter('INACTIVE')" style="<?php if($Filter == 'INACTIVE'){ echo "background-color:#EA5455;color: white;";} ?>">InActive<?php echo "(".$Inactive.")" ; ?></button>
                    <div class="content-body">
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body card-dashboard">
                                                <div class="table-responsive">
                                                    <table class="table table-hover-animation table-hover" id="SurveySummaryExecutiveListClient">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                <th style="background-color:#36abb9;color: white;">Action</th>
                                                                <th style="background-color:#36abb9;color: white;">Execuitve Name</th>
                                                                <th style="background-color:#36abb9;color: white;">Reference Name</th>
                                                                <th style="background-color:#36abb9;color: white;">Society</th>
                                                                <!-- <th style="background-color:#36abb9;color: white;">Total Rooms</th> -->
                                                                <th style="background-color:#36abb9;color: white;">Voters</th>
                                                                <th style="background-color:#36abb9;color: white;">NonVoters</th>
                                                                <th style="background-color:#36abb9;color: white;">TotalRooms</th>
                                                                <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                                <th style="background-color:#36abb9;color: white;">Lockroom</th>
                                                                <th style="background-color:#36abb9;color: white;">LBS</th>
                                                                <th style="background-color:#36abb9;color: white;">Mobile</th>
                                                                <th style="background-color:#36abb9;color: white;">Birthday</th>
                                                                <th style="background-color:#36abb9;color: white;">Working Days</th>
                                                                <th style="background-color:#36abb9;color: white;">Average</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if(sizeof($ExecutiveWiseCount) > 0 ){
                                                                $srNo = 1;
                                                                foreach ($ExecutiveWiseCount as $key => $value) {
                                                                ?> 
                                                                    <tr style="padding-top:0px;">
                                                                        <td><?php echo $srNo++; ?></td>
                                                                        <td style="color: #36abb9;">
                                                                            <a class="" onclick="getExecutiveData('<?php echo $value['ExecutiveName']?>')">
                                                                                <i class="fa fa-eye"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td><?php echo "<b>" . $value["ExecutiveName"] . "</b>" ?></td>
                                                                        <td><?php echo $value["ReferenceName"]; ?></td> 
                                                                        <td><?php echo $value["SocietyCount"]; ?></td>
                                                                        <td><?php echo $value["TotalVoters"]; ?></td>
                                                                        <td><?php echo $value["TotalNonVoters"]; ?></td>
                                                                        <td><?php echo ($value["LockRoom"] + $value["LBS"] + $value["RoomSurveyDone"]); ?></td>
                                                                        <td><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                        <td><?php echo $value["LockRoom"]; ?></td>
                                                                        <td><?php echo $value["LBS"]; ?></td>
                                                                        <td><?php echo $value["TotalMobileCount"]; ?></td>
                                                                        <td><?php echo $value["BirthdaysCount"]; ?></td>
                                                                        <td><?php echo $value["WorkingDays"]; ?></td>
                                                                        <td><?php echo CEIL($value["RoomSurveyDone"]/$value["WorkingDays"]); ?></td>
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
                    
<style>
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 7px;
    }

    .table td,
    .table th {
        padding: 0.75px;
        margin: 0;
    }

    .table th {
        background-color:#36abb9 ;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
        }

    .table tr {
        padding: 0;
        margin: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    td {
        border: 1px solid grey;
        padding: 8px;
    }
</style>
<div id="SurveySummaryExecutiveDataLoad" style="display:none;">
 <?php include 'pages/ExecutiveWiseDetail.php'; ?>
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
                            COALESCE(em.UserName,'') AS UserName , 
                            COALESCE(ssd.ElectionName,'') AS ElectionName, 
                            COALESCE(CONVERT(DATE,ss.SDate,23),'') AS SurveyDate,
                            COALESCE(CONVERT(DATE,em.JoiningDate,34),'') AS JoiningDate,
                            COALESCE(ssd.SiteName,'') AS SiteName,
                            COALESCE(rm.ReferenceName,'') AS ReferenceName,
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
                            (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(DATE, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
                            WHERE SurveyBy = em.UserName GROUP BY SurveyBy, CONVERT(DATE, SDate, 23)) AS t1) AS WorkingDays
                            FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss 
                            INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
                            -- INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName)
                            INNER JOIN Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName)
                            INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                            INNER JOIN Survey_Entry_Data..Site_Master as sm on (ssd.SiteName = sm.SiteName)
                            LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (sm.Supervisor_Cd = em1.Executive_Cd)
                            LEFT JOIN $Ip ChankyaAdmin.dbo.EnquiryMaster as enm on (em.Enquiry_Cd = enm.Enquiry_Cd)
                            LEFT JOIN $Ip ChankyaAdmin.dbo.Enquiry_Details as ed on 
                            (enm.Enquiry_Cd = ed.Enquiry_Cd  AND ed.AddedDate = (SELECT MAX(edd.AddedDate) FROM ChankyaAdmin..Enquiry_Details as edd WHERE edd.Enquiry_Cd = ed.Enquiry_Cd))
                            LEFT JOIN ChankyaAdmin..Reference_Master as rm on (ed.ReferenceSource = rm.Reference_Cd)
                            WHERE elm.ULB = '$ULB' AND CONVERT(DATE,ss.SDate,23) BETWEEN '$fromdate' AND '$todate' $condition
                            GROUP BY em.ExecutiveName, em.UserName,CONVERT(DATE,ss.SDate,23),CONVERT(DATE,em.JoiningDate,34),em.MobileNo,ssd.SiteName,sm.SupervisorName
                            ,rm.ReferenceName,em1.MobileNo,ssd.ElectionName) as tb1
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
                                        (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
                                        WHERE SurveyBy = em.UserName GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays
                                        FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss 
                                        INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
                                        -- INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName)
                                        INNER JOIN Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName)
                                        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                                        WHERE elm.ULB = '$ULB' AND CONVERT(varchar,ss.SDate,23) BETWEEN '$fromdate' AND '$todate'
                                        GROUP BY em.ExecutiveName, em.UserName,CONVERT(varchar,ss.SDate,23),CONVERT(varchar,em.JoiningDate,34),em.MobileNo
                                        ORDER BY em.ExecutiveName,CONVERT(varchar,ss.SDate,23) DESC;";


                                    $ExecutiveAvrgDateWiseCount = $db->ExecutveQueryMultipleRowSALData($ExAvrgQuery, $userName, $appName, $developmentMode);
                                    $ExecutiveCount = sizeof($ExecutiveAvrgDateWiseCount);
                                    $SumRooms = array_sum(array_column($ExecutiveAvrgDateWiseCount, 'RoomSurveyDone'));
                                    $SiteQuery = "SELECT 
                                    COALESCE(CONVERT(varchar,ss.SDate,23),'') AS SurveyDate, 
                                    COALESCE(ssd.SiteName,'') AS SiteName, 
                                    COALESCE(sm.Ward_No,'') AS Ward_No,  
                                    COALESCE(sm.Ac_No,'') AS Ac_No, 
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
                                    GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss 
                                    INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd) 
                                    INNER JOIN Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName) 
                                    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
                                    INNER JOIN Survey_Entry_Data..Site_Master as sm on (ssd.SiteName = sm.SiteName) 
                                    LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (sm.Supervisor_Cd = em1.Executive_Cd)
                                    LEFT JOIN $Ip ChankyaAdmin.dbo.EnquiryMaster as enm on (em.Enquiry_Cd = enm.Enquiry_Cd) 
                                    LEFT JOIN $Ip ChankyaAdmin.dbo.Enquiry_Details as ed on (enm.Enquiry_Cd = ed.Enquiry_Cd AND ed.AddedDate = 
                                    (SELECT MAX(edd.AddedDate) FROM ChankyaAdmin..Enquiry_Details as edd WHERE edd.Enquiry_Cd = ed.Enquiry_Cd)) 
                                    LEFT JOIN ChankyaAdmin..Reference_Master as rm on (ed.ReferenceSource = rm.Reference_Cd) 
                                    WHERE elm.ULB = '$ULB' AND CONVERT(varchar,ss.SDate,23) BETWEEN '$fromdate' AND '$todate'
                                    GROUP BY 
                                    CONVERT(varchar,ss.SDate,23),ssd.SiteName,sm.SupervisorName,em1.MobileNo,sm.Ward_No,sm.Ac_No
                                    ORDER BY CONVERT(varchar,ss.SDate,23) DESC;";


                        $SiteDateWiseCount = $db->ExecutveQueryMultipleRowSALData($SiteQuery, $userName, $appName, $developmentMode);

                                    // print_r("<pre>");
                                    // print_r($ExecutiveDateWiseCount);
                                    // print_r("</pre>");
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
                        $(document).ready(function () {
                            $('#DateWiseSurveySummaryList').DataTable({
                                "lengthMenu": [ [-1,20, 40, 50], [ "All",20, 40, 50] ],
                                    'columnDefs' : [
                                    //hide the second & fourth column
                                    { 'visible': false, 'targets': [13,14,15] }
                                ]
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
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="Executive" aria-labelledby="Executive-tab" role="tabpanel">
                                
                    <div class="card-header">
                        <h4 class="card-title">Summary Report - Date Wise Executive</h4>
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
                                                                        <th style="background-color:#36abb9;color: white;">Execuitve Name</th>
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
                                                                                <td style="align:center;"><?php echo date_format($value["SurveyDate"],"d-m"); ?></td>
                                                                                <td style="align:center;"><?php echo date_format($value["JoiningDate"],"d-m"); ?></td>
                                                                                <td style="align:center;"><?php echo $value["SocietyCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalNonVoters"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LockRoom"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["LBS"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["BirthdaysCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["TotalMobileCount"]; ?></td>
                                                                                <td style="align:center;"><?php echo $value["WorkingDays"]; ?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '60' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%";}else{echo "0";} ?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '70' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;<?php if((CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)) < '90' ){ echo "background:#FFD6D6;" ;}else{ echo "" ;} ?>"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
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
                                <h4 class="card-title">Summary Report - Date Wise Site</h4>
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
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }else{echo "0";}?></td>
                                                                                <td style="align:center;"><?php if($value["Executive"] != '') { echo CEIL($value["RoomSurveyDone"]/$value["Executive"]); }else{echo "0";}?></td>
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
        </div>
    </div>
</div>

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
</script>

   

</section>
