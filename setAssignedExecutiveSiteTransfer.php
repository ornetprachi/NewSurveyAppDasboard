
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

$QCAssignList = array(); 

if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}else{
    $ServerIP ="103.14.99.154";
}
 
$dataElectionNameAverageCount = $db->getCorporationDataForAssignExecutiveToSite($userName, $appName, $developmentMode);

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

$currentDate = date('Y-m-d');
$PreviousCurrentDate = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
if(isset($_SESSION['SurveyUA_Date_AssignExecutiveToSite']) && !empty($_SESSION['SurveyUA_Date_AssignExecutiveToSite'])){
    $Date = $_SESSION['SurveyUA_Date_AssignExecutiveToSite'];
}else{
    
    $Date = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));
    $_SESSION['SurveyUA_Date_AssignExecutiveToSite'] = $Date;
}

$TableQuery = "SELECT 
                tb1.SiteName,
                max(tb1.SupervisorName) as SupervisorName,
                max(tb1.Supervisor_Cd) as Supervisor_Cd,
                tb1.ElectionName,
                SUM(attendance+absents) as totalexecutives,
                sum(tb1.attendance) as present,
                sum(tb1.absents) as absent,
                sum(tb1.contracts) as contract,
                sum(tb1.staff) as staff from 
                (	
                    SELECT ed.SiteName,sm.SupervisorName,sm.Supervisor_Cd,ed.ElectionName,
                    CASE WHEN ed.Attendance='1' THEN 1 ELSE 0 END as attendance,
                    CASE WHEN ed.Attendance='1' THEN 0 ELSE 1 END as absents,
                    CASE WHEN em.ExeType='C' THEN 1 ELSE 0 END as contracts,
                    CASE WHEN em.ExeType='S' THEN 1 ELSE 0 END as staff 
                    from [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] ed 
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em on (ed.Executive_Cd=em.Executive_Cd) 
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Site_Master] sm on (sm.SiteName=ed.SiteName) 
                    WHERE convert(varchar, ed.SurveyDate, 23) = '$Date' and ed.ElectionName <> 'OFFICE STAFF'
                ) as tb1 
                GROUP BY tb1.ElectionName,tb1.SiteName 
                ORDER BY tb1.ElectionName,tb1.SiteName;";

$db1=new DbOperation();
$TableData = $db->ExecutveQueryMultipleRowSALData($TableQuery, $userName, $appName, $developmentMode);
// echo "<pre>";
// print_r($TableData);
// echo "</pre>";
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
    .CardBodyMarginBottom{
        /* margin-bottom: -20px; */
    }
</style>
   

<div class="row match-height">
    <div class="col-md-6">
        <div class="card">
            <div class="content-body" style="margin-top: -12px;">
                <div class="card-content">
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-xs-12 col-xl-5 col-md-5 col-12" style="display:none;">
                                <div class="form-group">
                                    <label>Corporation</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="electionName" onchange="setElectionNameInSessionFromAETSTransfer(this.value)" >
                                        <option value="">--Select--</option>
                                            <?php
                                            if (sizeof($dataElectionNameAverageCount)>0) 
                                            {
                                                foreach ($dataElectionNameAverageCount as $key => $value) 
                                                {
                                                    if($electionCd == $value["Election_Cd"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['Election_Cd']; ?>"><?php echo $value["ElectionName"]; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value["Election_Cd"];?>"><?php echo $value["ElectionName"];?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                                <label>Date</label>
                                <div class="controls"> 
                                    <input onchange="setDateInSessionFromAETSTransfer(this.value);" type="date" name="Date" id="Date" value="<?php echo $Date; ?>" class="form-control" placeholder="Date">
                                    <!-- min="<?php //echo $PreviousCurrentDate; ?>" -->
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>&nbsp;</label>
                                <div class="controls"> 
                                    <div id='spinnerLoader2' style='display:none'>
                                        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="content-body" style="margin-top: -12px;">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label>Date <span area-hidden="true" style="color:red;">*</span></label>
                                <div class="controls"> 
                                    <input type="date" name="TransferDate" id="TransferDate" value="<?php echo $currentDate; ?>" min="<?php echo $currentDate; ?>" class="form-control" placeholder="Date">
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="controls text-center" style="margin-top:28px">
                                    <button type="button" id="UpdateButton" class="btn btn-primary float-right" onclick="AssignedExecutiveToSiteTransfer()">
                                        Transfer
                                    </button>
                                </div> 
                            </div>
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="controls">
                                        <div id='spinnerLoader2' style='display:none;float:left;'><img src='app-assets/images/loader/loading.gif' width="50" height="50"/></div>
                                        <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                        <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                                        <input type="hidden" value=""  class="form-control" name="SupervisorCds"/>
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
 
<div class="row match-height" style="margin-top:-15px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-top: -10px;">
                <h4 class="card-title">Assigned Executives List : ( Selected Records : <span id="SelectedExecutiveCds"> 0 </span> )</h4> 
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: -35px;">
                            <div class="card">  
                                <div class="card-content">
                                    <div class="card-body card-dashboard pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-hover table-striped" id="AssignExecutiveToSiteTableReportIDTransfer">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">
                                                            <input class="form-check-input checkbox_All" type="checkbox" style="  margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setAssignedExecutiveToSiteToTransferAll(this)" >
                                                        </th>
                                                        <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Supervisor Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Corporation Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Total Executive</th>
                                                        <th style="background-color:#36abb9;color: white;">P / A</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if(sizeof($TableData) > 0){
                                                            $srNo = 1;
                                                            foreach($TableData AS $Key=>$value){  
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <input class="form-check-input element checkbox" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['SupervisorName']; ?>~<?php echo $value['Supervisor_Cd'] ; ?>~<?php echo $value['SiteName']; ?>~<?php echo $value['ElectionName']; ?>" id="AssignCheckbox" onclick="setAssignedExecutiveToSiteToTransfer()" >
                                                                </td>
                                                                <td><b><?php echo $value['SiteName']; ?></b></td>
                                                                <td><b><?php echo $value['SupervisorName']; ?></b></td>
                                                                <td><b><?php echo $value['ElectionName']; ?></b></td>
                                                                <td><b><?php echo $value['totalexecutives']; ?></b></td>
                                                                <td>
                                                                    <b>
                                                                        <?php echo $value['present']." / ".$value['absent']; ?>
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
                </section>
            </div>
        </div>
    </div>
</div>


