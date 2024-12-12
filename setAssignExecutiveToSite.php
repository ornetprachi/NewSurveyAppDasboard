
<section id="dashboard-analytics">
    
<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];


$from_Date = '';
$to_Date = '';
$QCStatus = 0;
$SurveyStatus = 1;
$ExecutiveCd = "";
$Site_Cd = "";
$Pocket_Cd = "";
$FilterType = "All";

$currentDate = date('Y-m-d');

$QCAssignList = array(); 

// if($ServerIP == "103.14.99.154"){
//     $ServerIP =".";
// }else{
//     $ServerIP ="103.14.99.154";
// }
 
$dataElectionNameAverageCount = $db->getCorporationDataForAssignExecutiveToSite($userName, $appName, $developmentMode);


if(isset($_SESSION['assign-executive-to-site']) && !empty($_SESSION['assign-executive-to-site'])){
    $TabDiv = $_SESSION['assign-executive-to-site'];
}else{
    $TabDiv = "AssignTab";
    $_SESSION['assign-executive-to-site'] = $TabDiv;
}

if
((isset($_SESSION['SurveyUA_Election_Cd']) && !empty($_SESSION['SurveyUA_Election_Cd'])) && 
(isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']))){
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];
}else{
    $electionCd = $dataElectionNameAverageCount[0]['Election_Cd'];
    $electionName = $dataElectionNameAverageCount[0]['ElectionName'];
    $_SESSION['SurveyUA_Election_Cd'] = $electionCd;
    $_SESSION['SurveyUA_ElectionName'] = $electionName;
}

// Site DropDown --------------------------------------------
if(isset($_SESSION['SurveyUA_SiteCd_QC_Assign']) && isset($_SESSION['SurveyUA_SiteName_QC_Assign'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_QC_Assign'];
    $SiteName = $_SESSION['SurveyUA_SiteName_QC_Assign'];
}

$dataSite = array();
// if(isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName'])){
//     $electionCd = $_SESSION['SurveyUA_Election_Cd']; 
//     $electionName = $_SESSION['SurveyUA_ElectionName'];
    $dataSite = $db->getSiteDropDownDatabyElectionName($ULB,$userName, $appName,  $developmentMode);
    
    // $query = "SELECT 
    //             distinct(SiteName) as SiteName, 
    //             COALESCE(sm.Site_Cd,0) AS Site_Cd, 
    //             COALESCE(sm.ClientName,'') AS ClientName,
    //             COALESCE(sm.Area, '') AS Area,
    //             COALESCE(sm.Ward_No,0) AS Ward_No,
    //             COALESCE(sm.Address,'') AS Address,
    //             COALESCE(sm.ElectionName,'') AS ElectionName
    //         FROM [Survey_Entry_Data].[dbo].[Site_Master] sm
    //         WHERE sm.ElectionName = '$electionName'
    //         AND Closed = 0 ORDER BY Ward_No
    //         ";

    // $dataSite = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
// }
// Site DropDown --------------------------------------------

// Supervisor DropDown --------------------------------------------
    $SuperVisorquery = "SELECT 
                        COALESCE(Executive_Cd, 0) as Executive_Cd,
                        COALESCE(ExecutiveName, '') as ExecutiveName, 
                        COALESCE(MobileNo, '') as MobileNo,
                        COALESCE(Designation, '') as Designation 
                        FROM [Survey_Entry_Data].[dbo].[Executive_Master]
                        WHERE (Designation = 'SP' or Designation = 'Survey Supervisor') 
                        AND EmpStatus = 'A' AND ElectionName <> 'AMC' 
                        ORDER BY ExecutiveName;";
    $SuperVisorData = $db->ExecutveQueryMultipleRowSALData($ULB,$SuperVisorquery, $userName, $appName, $developmentMode);
// Supervisor DropDown --------------------------------------------

if(isset($_SESSION['SurveyUA_Filter_AssignExecutiveToSite']) && !empty($_SESSION['SurveyUA_Filter_AssignExecutiveToSite'])){
    $FilterType = $_SESSION['SurveyUA_Filter_AssignExecutiveToSite'];
}else{
    $FilterType = "All";
    $_SESSION['SurveyUA_Filter_AssignExecutiveToSite'] = $FilterType;
}

if(isset($_SESSION['SurveyUA_Date_AssignExecutiveToSite']) && !empty($_SESSION['SurveyUA_Date_AssignExecutiveToSite'])){
    $Date = $_SESSION['SurveyUA_Date_AssignExecutiveToSite'];
}else{
    $Date = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
    $_SESSION['SurveyUA_Date_AssignExecutiveToSite'] = $Date;
}

$FilterTypeCondition = "";
if($FilterType == "All"){
    $FilterTypeCondition = "";
}else if($FilterType == "Not Assigned"){
    $FilterTypeCondition = "AND ((em.SurveyDate IS NOT NULL AND convert(varchar, em.SurveyDate, 23) <> '$Date') OR em.SurveyDate IS NULL)";
}

// $DBName = $db->GetDBName($electionName, $electionCd, $userName, $appName, $developmentMode);

// print_r($DBName);

$TableQuery = "SELECT 
                COALESCE(em.ExecutiveName, '') as ExecutiveName,
                COALESCE(em.MobileNo, '') as MobileNo,
                COALESCE(em.ElectionName, '') as ElectionName,
                COALESCE(em.ExeType,'') as ExeType,
                COALESCE(em.Executive_Cd, 0) as Executive_Cd,0 as Attendance,
                CASE 
                    WHEN COALESCE(convert(varchar, em.SurveyDate, 23),'') = '1900-01-01' THEN '' 
                    WHEN COALESCE(convert(varchar, em.SurveyDate, 23),'') = '1970-01-01' THEN ''
                    ELSE COALESCE(convert(varchar, em.SurveyDate, 23),'') 
                END as SurveyDate,
                COALESCE(em.SiteName, '') as SiteName,
                '' as remark,
                '' as presenttime,
                COALESCE(CONCAT(cem.address1,' ' ,cem.address2,' ',cem.address3),'') AS Address
                FROM [Survey_Entry_Data].[dbo].[Executive_Master] em 
                INNER JOIN [Survey_Entry_Data].[dbo].[Election_Master] eem on (em.ElectionName=eem.ElectionName) 
                INNER JOIN [Survey_Entry_Data].[dbo].[User_Master] um on (em.Executive_Cd = um.Executive_Cd AND um.AppName = 'SurveyUtilityApp')
                LEFT JOIN  [ChankyaAdmin].[dbo].[emp_mst] cem on (em.CHAdminEmp_Id = cem.emp_id)
                WHERE em.EmpStatus = 'A' AND (Designation LIKE '%SE-Belapur%' OR Designation LIKE '%Survey Executive%') 
                AND eem.ActiveFlag = 1  $FilterTypeCondition
                ORDER BY ExecutiveName;";

          

$db1=new DbOperation();
$TableData = $db->ExecutveQueryMultipleRowSALData($ULB,$TableQuery, $userName, $appName, $developmentMode);


$ReportTableQuery = "SELECT 
                tb1.SiteName,
                max(tb1.SupervisorName) as SupervisorName,
                max(tb1.Supervisor_Cd) as Supervisor_Cd,
                tb1.ElectionName,
                SUM(attendance+absents) as totalexecutives,
                sum(tb1.attendance) as present,
                --sum(tb1.absents) as absent,
                sum(tb1.assigned) as assigned,
                sum(tb1.actualabsents) as absent,
                sum(tb1.contracts) as contract,
                sum(tb1.staff) as staff from 
                (	
                    SELECT ed.SiteName,sm.SupervisorName,sm.Supervisor_Cd,ed.ElectionName,
                    CASE WHEN ed.Attendance='1' THEN 1 ELSE 0 END as attendance,
                    CASE WHEN ed.Attendance='1' THEN 0 ELSE 1 END as absents,
                    CASE WHEN ed.Attendance='2' THEN 1 ELSE 0 END as actualabsents,
                    CASE WHEN ed.Attendance='0' THEN 1 ELSE 0 END as assigned,
                    CASE WHEN em.ExeType='C' THEN 1 ELSE 0 END as contracts,
                    CASE WHEN em.ExeType='S' THEN 1 ELSE 0 END as staff 
                    from [Survey_Entry_Data].[dbo].[Executive_Details] ed 
                    INNER JOIN [Survey_Entry_Data].[dbo].[Executive_Master] em on (ed.Executive_Cd=em.Executive_Cd) 
                    INNER JOIN [Site_Master] sm on (sm.SiteName=ed.SiteName) 
                    WHERE convert(varchar, ed.SurveyDate, 23) = '$Date' and ed.ElectionName <> 'OFFICE STAFF'
                ) as tb1 
                GROUP BY tb1.ElectionName,tb1.SiteName 
                ORDER BY tb1.ElectionName,tb1.SiteName;";


$db2=new DbOperation();
$ReportTableData = $db2->ExecutveQueryMultipleRowSALData($ULB,$ReportTableQuery, $userName, $appName, $developmentMode);




$MinDate = "";


$currentMonth = date('m');
if($currentMonth == 1 || $currentMonth == 2){
    $previousSecondMonth = 11;
}else{
    $previousSecondMonth = $currentMonth - 2;
}


// Handle wrap-around for months
if ($previousSecondMonth < 1) {
    $previousSecondMonth = 12 - abs($previousSecondMonth);
}

$previousSecondMonth = str_pad($previousSecondMonth, 2, '0', STR_PAD_LEFT);

if($currentMonth == 1 || $currentMonth == 2){
    $currentYear = date('Y');
    $CurYear = $currentYear - 1;
}else{
    $CurYear = date('Y');
}
$MinDate =  $CurYear."-$previousSecondMonth-01";


?>


<style type="text/css">
    /* img.docimg{

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
    } */

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

    .dot {
        height: 15px;
        width: 15px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }
    table.dataTable th, table.dataTable td {
        border-bottom: 1px solid #F8F8F8;
        border-top: 0;
        padding: 5PX;
    }
    .element {
        cursor: default;
    }

    /* Custom cursor on hover */
    .element:hover {
        cursor: pointer;
    }
</style>
   

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body" style="margin-top: -12px;margin-bottom: -30px;">
                        <div class="row">                                  
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Executive Filter</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="FilterType" onchange="setFilterTypeInSessionFromAETS(this.value);">
                                            <option <?php echo $FilterType == "All" ? "selected=true" : "" ?> value="All">All</option>
                                            <option <?php echo $FilterType == "Not Assigned" ? "selected=true" : "" ?> value="Not Assigned">Not Assigned</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>Date <span area-hidden="true" style="color:red;">*</span></label>
                                <div class="controls"> 
                                    <input onchange="setDateInSessionFromAETS(this.value);" type="date" name="Date" id="Date" value="<?php echo $Date; ?>" min="<?php echo $MinDate; ?>" class="form-control" placeholder="Date">
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Site <span area-hidden="true" style="color:red;">*</span></label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="SiteName" onchange="setSupervisorNameOnChangeofSite(this.value)">
                                            <option value="">--Select--</option>
                                            <?php
                                            if (sizeof($dataSite)>0) 
                                            {
                                                foreach ($dataSite as $key => $value) 
                                                {
                                                    if($Site_Cd == $value["Site_Cd"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['Site_Cd']; ?>~<?php echo $value["SiteName"];?>"><?php echo "<b>".$value["SiteName"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value['Site_Cd']; ?>~<?php echo $value["SiteName"];?>"><?php echo "<b>".$value["SiteName"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>

                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Supervisor <span area-hidden="true" style="color:red;">*</span></label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="Supervisor" id="Supervisor">
                                            <option value="">--Select--</option>
                                            <?php
                                            if (sizeof($SuperVisorData)>0) 
                                            {
                                                foreach ($SuperVisorData as $key1 => $value1) 
                                                {
                                                    if($Executive_CdSupervisor == $value1["Executive_Cd"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value1['Executive_Cd']; ?>~<?php echo $value1['ExecutiveName']; ?>"><?php echo "<b>" . $value1["ExecutiveName"] . "</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value1['Executive_Cd']; ?>~<?php echo $value1['ExecutiveName']; ?>"><?php echo "<b>" . $value1["ExecutiveName"] . "</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                <div class="form-group">
                                    <label>Attendance<span area-hidden="true" style="color:red;">*</span></label>
                                    <div class="controls">
                                        <select class="select2 form-control" style="width:100%" name="AttendanceFilter" id="AttendanceFilter">
                                            <option value="">-Select-</option>
                                            <option value="1">P</option>
                                            <option value="2">A</option>
                                            <option value="4">T</option>
                                            <option value="3">HF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                <div class="controls" style="margin-top:28px">
                                    <div id='spinnerLoader2' style='display:none;float:left;'>
                                        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="controls" style="margin-top:28px">
                                    <input type="hidden" value="" class="form-control" name="ExecutiveCds"/>
                                    <button type="button" id="UpdateButton" style="padding:10px;" class="btn btn-primary" onclick="AssignExecutiveToSite()">
                                        Update
                                    </button>
                                    <button type="button" id="TransferButton" style="padding:10px;" class="btn btn-primary" onclick="AssignedExecutiveToSiteTransferFunction()">
                                        Transfer
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

<div class="tab-pane" id="home" aria-labelledby="home-tab" role="tabpanel" style="margin-top: -25px;">
    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item">
            <a class="nav-link <?php if($TabDiv == "AssignTab"){ echo "active"; }else{ echo ""; } ?>" id="AssignExecutive-tab" data-toggle="tab" href="#AssignExecutive" aria-controls="AssignExecutive" role="tab" aria-selected="flase">Assign Executive To Site</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($TabDiv == "AssignedReportTab"){ echo "active"; }else{ echo ""; } ?>" id="AssignedData-tab" data-toggle="tab" href="#AssignedData" aria-controls="AssignedData" role="tab" aria-selected="true">Assigned Data</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link <?php //if($TabDiv == "AttendanceTab"){ echo "active"; }else{ echo ""; } ?>" id="Attendance-tab" data-toggle="tab" href="#Attendance" aria-controls="Attendance" role="tab" aria-selected="true">Attendance</a>
        </li> -->
    </ul>
    <div class="tab-content">
        <div class="tab-pane <?php if($TabDiv == "AssignTab"){ echo "active"; }else{ echo ""; } ?>" id="AssignExecutive" aria-labelledby="AssignExecutive-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" >
                                <div class="card-header" style="margin-top: -10px;">
                                    <h4 class="card-title">Executive List ( <?php echo count($TableData);?> ) : ( Selected Executives : <span id="SelectedExecutiveCds"> 0 </span> )</h4> 
                                    <h4 class="card-title">
                                        <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;"></div>
                                        <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;"></div>
                                        <div id="waitMSG" class="controls alert alert-warning text-center" role="alert" style="display: none;padding:5px;"></div>
                                        <button style="padding:10px;" class="btn btn-primary" onclick="uncheckAllCheckboxes()">Clear</button>
                                    </h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="row match-height" style="margin-top:-15px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    
                                                    <div class="content-body" style="overflow:scroll;">
                                                        <table class="table table-hover-animation table-hover table-striped" id="AssignExecutiveToSiteTableID" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Search By Site Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column1Search" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Search By Executive Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column3Search" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Search By Mobile No" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column4Search" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                </tr>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        
                                                                    </th>
                                                                    <th style="margin-left: 20px;background-color:#36abb9;color: white;">
                                                                        Survey Date
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        Site Name
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        Executive Name
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        Mobile No
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">Corporation Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Address</th>
                                                                    <th style="background-color:#36abb9;color: white;">Assign Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if(sizeof($TableData) > 0){
                                                                        $srNo = 1;
                                                                        foreach($TableData AS $Key=>$value){  
                                                                            $ElectionName = $value['ElectionName'];
                                                                            $ElectionName = explode('_', $ElectionName);
                                                                            $ElectionName = $ElectionName[0];
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input class="form-check-input element checkbox" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['Executive_Cd'] ; ?>" id="AssignCheckbox" onclick="setAssignExecutiveToSite(<?php echo $value['Executive_Cd'] ; ?>)" >
                                                                            </td>
                                                                            <td><b style="margin-left: 20px;"><?php echo date('d-m-Y',strtotime($value['SurveyDate'])); ?></b></td>
                                                                            <td><b><?php echo $value['SiteName']; ?></b></td>
                                                                            <td><b><?php echo $value['ExecutiveName']; ?></b></td>
                                                                            <td><b><?php echo $value['MobileNo']; ?></b></td>
                                                                            <td><b><?php echo $ElectionName; ?></b></td>
                                                                            <td><b><?php echo wordwrap($value['Address'],25,"<br>\n"); ?></b></td>
                                                                            <td>
                                                                                <?php 
                                                                                    if($Date == $value['SurveyDate']){
                                                                                ?>
                                                                                        <span class="badge badge-success"><b>Assigned</b></span>
                                                                                <?php 
                                                                                    }else{
                                                                                ?>
                                                                                        <span class="badge badge-danger" style="background-color:#d96a6a;"><b>Not Assigned</b></span>
                                                                                <?php 
                                                                                    }
                                                                                ?>
                                                                            </td>
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
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="tab-pane <?php if($TabDiv == "AssignedReportTab"){ echo "active"; }else{ echo ""; } ?>" id="AssignedData" aria-labelledby="AssignedData-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="margin-top: -10px;">
                                    <h4 class="card-title">Assigned Executives List :</h4> 
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                        <div class="row match-height" style="margin-top:-15px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    <div class="content-body" style="overflow:scroll;">
                                                        <table class="table table-hover-animation table-hover table-striped" id="AssignExecutiveToSiteTableReportID">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                    <th style="background-color:#36abb9;color: white;">View</th>
                                                                    <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Supervisor Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Corporation Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Total Executive</th>
                                                                    <th style="background-color:#36abb9;color: white;">Assigned / P / A</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if(sizeof($ReportTableData) > 0){
                                                                        $srNo = 1;
                                                                        foreach($ReportTableData AS $Key=>$value){  
                                                                            $ElectionName = $value['ElectionName'];
                                                                            $ElectionName = explode('_', $ElectionName);
                                                                            $ElectionName = $ElectionName[0];
                                                                        ?>
                                                                        <tr>
                                                                            <td><b><?php echo $srNo++; ?></b></td>
                                                                            <td>
                                                                                <a style="color: #36abb9;font-size:16px;" id="openModalButton" onclick="getSiteSupervisorWiseDetail('<?php echo $Date; ?>','<?php echo $value['SiteName']; ?>','<?php echo $value['SupervisorName']; ?>','<?php echo $value['totalexecutives']; ?>','<?php echo $value['ElectionName']; ?>')"><i class="fa fa-eye"></i></a>
                                                                            </td>
                                                                            <td><b><?php echo $value['SiteName']; ?></b></td>
                                                                            <td><b><?php echo $value['SupervisorName']; ?></b></td>
                                                                            <td><b><?php echo $ElectionName; ?></b></td>
                                                                            <td><b><?php echo $value['totalexecutives']; ?></b></td>
                                                                            <td>
                                                                                <b>
                                                                                    <?php echo $value['assigned']." / ".$value['present']." / ".$value['absent']; ?>
                                                                                </b>
                                                                            </td>
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
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- <div class="tab-pane <?php //if($TabDiv == "AttendanceTab"){ echo "active"; }else{ echo ""; } ?>" id="Attendance" aria-labelledby="Attendance-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                        <div class="row match-height" style="margin-top:-15px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    <div class="content-body">
                                                        <?php //include('Attendance.php'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div> -->
    </div>
</div>


<div id="SiteSupervisorWiseDetail" class="SiteSupervisorWiseDetail">
</div>  


<script>

    function setAssignExecutiveToSite(Executive_Cd){
        let selected = 0;
        let check = Executive_CdArray.includes(Executive_Cd);                                                                   
        if(check == false){
            Executive_CdArray.push(Executive_Cd);
            // selected++;
        }else{
            const index = Executive_CdArray.indexOf(Executive_Cd);
            if (index > -1) {
                Executive_CdArray.splice(index, 1); 
            }
        }
        selected = Executive_CdArray.length;
        document.getElementsByName("ExecutiveCds")[0].value = Executive_CdArray;
        document.getElementById("SelectedExecutiveCds").innerHTML = selected; 
    }


    $('.select2').select2();
</script>