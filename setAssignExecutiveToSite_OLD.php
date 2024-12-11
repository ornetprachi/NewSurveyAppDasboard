
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

// Site DropDown --------------------------------------------
if(isset($_SESSION['SurveyUA_SiteCd_QC_Assign']) && isset($_SESSION['SurveyUA_SiteName_QC_Assign'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_QC_Assign'];
    $SiteName = $_SESSION['SurveyUA_SiteName_QC_Assign'];
}

$dataSite = array();
// if(isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName'])){
//     $electionCd = $_SESSION['SurveyUA_Election_Cd'];
//     $electionName = $_SESSION['SurveyUA_ElectionName'];
    $dataSite = $db->getSiteDropDownDatabyElectionName($userName, $appName,  $developmentMode);
    
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
                        FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master]
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
    $Date = date('Y-m-d');
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
                COALESCE(convert(varchar, em.SurveyDate, 23),'') as SurveyDate,
                COALESCE(em.SiteName, '') as SiteName,
                '' as remark,
                '' as presenttime 
                FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em 
                inner join [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] eem on (em.ElectionName=eem.ElectionName) 
                WHERE em.EmpStatus = 'A' AND (Designation LIKE '%SE-Belapur%' OR Designation LIKE '%Survey Executive%') 
                AND eem.ActiveFlag = 1  $FilterTypeCondition
                ORDER BY ExecutiveName;";

$db1=new DbOperation();
$TableData = $db->ExecutveQueryMultipleRowSALData($ULB,$TableQuery, $userName, $appName, $developmentMode);
// echo "<pre>";
// print_r($TableData);


// print_r("<pre>");
// print_r($QCAssignList);
// print_r("</pre>");

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
                    <div class="card-body" style="margin-top: -12px;margin-bottom: -40px;">
                        <div class="row">
                            <!-- <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Corporation <span area-hidden="true" style="color:red;">*</span></label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="electionName" onchange="setElectionNameInSessionFromAETS(this.value)" >
                                        <option value="">--Select--</option>
                                            <?php
                                            // if (sizeof($dataElectionNameAverageCount)>0) 
                                            // {
                                            //     foreach ($dataElectionNameAverageCount as $key => $value) 
                                            //     {
                                            //         if($electionCd == $value["Election_Cd"])
                                            //         {
                                            ?>
                                                        <option selected="true" value="<?php //echo $value['Election_Cd']; ?>"><?php //echo $value["ElectionName"]; ?></option>
                                            <?php
                                                    // }
                                                    // else
                                                    // {
                                            ?>
                                                        <option value="<?php //echo $value["Election_Cd"];?>"><?php //echo $value["ElectionName"];?></option>
                                            <?php
                                                    // }
                                            //     }
                                            // }
                                            ?> 
                                        </select>
                                    </div>

                                </div>
                            </div> -->
                                  
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
                                <label>Date <span area-hidden="true" style="color:red;">*</span></label>
                                <div class="controls"> 
                                    <input onchange="setDateInSessionFromAETS(this.value);" type="date" name="Date" id="Date" value="<?php echo $Date; ?>" min="<?php echo date('Y-m-d'); ?>" class="form-control" placeholder="Date">
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
                            
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:28px">
                                    <div id='spinnerLoader2' style='display:none;float:left;'>
                                        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                    </div>
                                    <button type="button" id="UpdateButton" class="btn btn-primary float-right" onclick="AssignExecutiveToSite()">
                                        Update
                                    </button>
                                </div> 
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <div class="controls">
                                        <input type="hidden" value="" class="form-control" name="ExecutiveCds"/>
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
  
<!-- <div class="row">
    <div class="col-md-12" style="align-items:center">
        <center>
            <div id='spinnerLoader2' style='display:none'>
                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
            </div>
        </center>
    </div>
</div> -->

<div class="row match-height" style="margin-top:-15px;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-top: -10px;">
                <h4 class="card-title">Executive List : ( Selected Executives : <span id="SelectedExecutiveCds"> 0 </span> )</h4> 
                <h4 class="card-title">
                    <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;"></div>
                    <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;"></div>
                </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12" style="margin-bottom: -35px;">
                            <div class="card">  
                                <div class="card-content">
                                    <div class="card-body card-dashboard pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-hover table-striped" id="AssignExecutiveToSiteTableID">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">
                                                            <!-- <input class="form-check-input checkbox_All" type="checkbox" style=" width: 20px; height: 20px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setQCAssignALLIds(this)" > -->
                                                        </th>
                                                        <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Mobile No</th>
                                                        <th style="background-color:#36abb9;color: white;">Corporation Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Executive Type</th>
                                                        <th style="background-color:#36abb9;color: white;">Assign Status</th>
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
                                                                    <input class="form-check-input element checkbox" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['Executive_Cd'] ; ?>" id="AssignCheckbox" onclick="setAssignExecutiveToSite(<?php echo $value['Executive_Cd'] ; ?>)" >
                                                                </td>
                                                                <!-- <td><?php //echo $srNo++; ?></td> -->
                                                                <td><b><?php echo $value['SiteName']?></b></td>
                                                                <td><b><?php echo $value['ExecutiveName']?></b></td>
                                                                <td><b><?php echo $value['MobileNo']?></b></td>
                                                                <td><b><?php echo $value['ElectionName']?></b></td>
                                                                <td><b><?php echo $value['ExeType']?></b></td>
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
                </section>
            </div>
        </div>
    </div>
</div>



<script>
    var Executive_CdArray = [];
    function setAssignExecutiveToSite(Executive_Cd){
        var selected = 0;
        var check = Executive_CdArray.includes(Executive_Cd);                                                                   
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
</script>