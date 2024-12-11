
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
    
    
    //$from_Date = '';
    $to_Date = '';
    $QCStatus = 0;
    $SurveyStatus = 1;
    $ExecutiveCd = "";
    $Site_Cd = "";
    $Pocket_Cd = "";
    $FilterType = "";
    
    $QCAssignList = array(); 
    
    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    
    // Site DropDown --------------------------------------------
    if(isset($_SESSION['SurveyUA_Site_Attendance'])){
        $SiteName = $_SESSION['SurveyUA_Site_Attendance'];
    }else{
        $SiteName = 'ALL';
    }
    if(isset($_SESSION['SurveyUA_WorkIn_Attendance'])){
        $WorkIn = $_SESSION['SurveyUA_WorkIn_Attendance'];
    }else{
        $WorkIn ='';
    }
    
    $dataSite = array();
        $dataSite = $db->getSiteDropDownDatabyElectionName($userName, $appName,  $developmentMode, $electionCd, $electionName);
        
        $Query = "SELECT 
                    DropDownCd,Dtype,Dvalue 
                    FROM [$ServerIP].[ChankyaAdmin].[dbo].DropDownMaster 
                    WHERE Dtype = 'FeedbackType' AND IsActive = 1";
    
        $WorkInData = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);
    
    
    if(isset($_SESSION['SurveyUA_Filter_Attendance']) && !empty($_SESSION['SurveyUA_Filter_Attendance'])){
        if($_SESSION['SurveyUA_Filter_Attendance'] == 5){
            $FilterType = '0';
        }else{
        $FilterType = $_SESSION['SurveyUA_Filter_Attendance'];
        }
    }else{
        $FilterType = "ALL";
    }
    
    if(isset($_SESSION['SurveyUA_Date_Attendance']) && !empty($_SESSION['SurveyUA_Date_Attendance'])){
        $Date = $_SESSION['SurveyUA_Date_Attendance'];
    }else{
        $Date = date('Y-m-d');
    }
    
    
    $FilterTypeCondition = "";
    if($SiteName == "ALL"){
        $SiteNameCondition = "";
    }else {
        $SiteNameCondition = "AND ed.SiteName = '$SiteName'";
    }
    if($FilterType == "ALL"){
        $FilterTypeCondition = "";
    }else {
         $FilterTypeCondition = "AND ed.Attendance = '$FilterType'";
    }

    if(isset($_SESSION['SurveyUA_designation_Attendanmce']) && !empty($_SESSION['SurveyUA_designation_Attendanmce'])){
        $Designation = $_SESSION['SurveyUA_designation_Attendanmce'];
     }else{
         $Designation = 'ALL';
     }
 
     if($Designation == 'Survey Executive')
     {
        $DesignationCon = "AND em.Designation IN ('SE-Belapur','SE-Belapur')";
     }elseif($Designation == 'Survey Supervisor'){
         $DesignationCon = "AND em.Designation IN ('Survey Supervisor','SP')";
     }elseif($Designation == 'Site Manager'){
         $DesignationCon = "AND em.Designation IN ('Site Manager')";
     }else{
         $DesignationCon = "";
     }


     $TableQuery = "SELECT 
                    COALESCE(ed.ExecutiveName, '') as ExecutiveName,
                    COALESCE(em.MobileNo, '') as MobileNo,
                    COALESCE(em.Designation, '') as Designation, 
                    COALESCE(ed.Executive_Cd, '') as Executive_Cd,
                    COALESCE(elm.ULB, '') as ULB,
                    COALESCE(ed.SiteName, '') as SiteName,
                    COALESCE(sm.SiteStatus, '') as SiteStatus,
                    COALESCE(sm.SupervisorName, '') as SupervisorName, 
                    COALESCE(em1.MobileNo, '') as MobileNo1, 
                    COALESCE(ed.ElectionName, '') as ElectionName,
                    COALESCE(ed.Attendance, '') as Attendance
                    FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] as ed
                    LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] as em on (ed.Executive_Cd = em.Executive_Cd) 
                    LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Site_Master] as sm on (ed.SiteName = sm.SiteName) 
                    LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] as em1 on (sm.SupervisorName = em1.ExecutiveName)
                    LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] as elm on (ed.ElectionName = elm.ElectionName) 
                    WHERE CONVERT(varchar,ed.SurveyDate,23) = '$Date' $SiteNameCondition $FilterTypeCondition $DesignationCon
                    ORDER BY Attendance DESC;";
    
    $db1=new DbOperation();
    $TableData = $db->ExecutveQueryMultipleRowSALData($TableQuery, $userName, $appName, $developmentMode);
    
    $SPQuery = "SELECT Executive_Cd,ExecutiveName,SiteName,ElectionName,MobileNo 
                FROM Executive_Master where Designation IN ('Survey Supervisor','SP','Site Manager','Manager') AND EmpStatus = 'A';";
    
    $SPTableData = $db->ExecutveQueryMultipleRowSALData($SPQuery, $userName, $appName, $developmentMode);
    
    ?>
    
    
    <style type="text/css">
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
        font-size: 13px;
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
        <div class="col-md-8">
            <div class="card">
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body" style="margin-top: -12px;margin-bottom:-30px;">
                            <div class="row" style="background-color: #F8F8F8;border-radius: 10px;">
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <label>Date </label>
                                    <div class="controls"> 
                                        <input style="width:100%" onchange="setDateInSessionForAttendance(this.value);" type="date" name="Date" id="Date" value="<?php echo $Date; ?>"  class="form-control" placeholder="Date">
                                    </div>
                                </div>
                                      
                                <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                    <div class="form-group">
                                        <label>Site</label>
                                        <div class="controls">
                                            <select class="select2 form-control" style="width:100%" name="SiteName"  onchange="SetSityeForAttendance(this.value)">
                                                <option value="ALL">ALL</option>
                                                <?php
                                                if (sizeof($dataSite)>0) 
                                                {
                                                    foreach ($dataSite as $key => $value) 
                                                    {
                                                        if($SiteName == $value["SiteName"])
                                                        {
                                                ?>
                                                            <option selected="true" value="<?php echo $value["SiteName"];?>"><?php echo "<b>".$value["SiteName"]."-".$value['SiteStatus'] ."</b>"; ?></option>
                                                <?php
                                                        }
                                                        else
                                                        {
                                                ?>
                                                            <option value="<?php echo $value["SiteName"];?>"><?php echo "<b>".$value["SiteName"]."-".$value['SiteStatus'] ."</b>" ; ?></option>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?> 
                                            </select>
                                        </div>
    
                                    </div>
                                </div>
                                <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                    <div class="form-group">
                                        <label>Filter</label>
                                        <div class="controls">
                                            <select class="select2 form-control" style="width:100%" name="FilterType" onchange="setAttendanceFilterInSessionForAttendance(this.value);">
                                                <option value="ALL">All</option>
                                                <option <?php echo $FilterType == "1" ? "selected=true" : "" ?> value="1">P</option>
                                                <option <?php echo $FilterType == "2" ? "selected=true" : "" ?> value="2">A</option>
                                                <option <?php echo $FilterType == "4" ? "selected=true" : "" ?> value="4">T</option>
                                                <option <?php echo $FilterType == "3" ? "selected=true" : "" ?> value="3">HF</option>
                                                <option <?php echo $FilterType == "0" ? "selected=true" : "" ?> value="5">--</option>
                                                <!-- <option <?php //echo $FilterType == "0" ? "selected=true" : "" ?> value="0">Assign</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <div class="controls">
                                            <select class="select2 form-control" name="Designation" onchange="setDesignationInSessionForAttendance(this.value);">
                                                <option value="ALL">All</option>
                                                <option <?php echo $Designation == "Survey Executive" ? "selected=true" : "" ?> value="Survey Executive">Survey Executive</option>
                                                <option <?php echo $Designation == "Survey Supervisor" ? "selected=true" : "" ?> value="Survey Supervisor">Survey Supervisor</option>
                                                <option <?php echo $Designation == "Site Manager" ? "selected=true" : "" ?> value="Site Manager">Site Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="controls">
                                            <div id="msgsuccessAttendance" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;"></div>
                                            <div id="msgfailedAttendance" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body" style="margin-top: -12px;margin-bottom:-30px;">
                            <div class="row" style="background-color: #F8F8F8;border-radius: 10px;">
                                <div class="col-md-5 col-12">
                                    <div class="form-group">
                                        <label>Attendance</label>
                                        <div class="controls">
                                            <select class="select2 form-control" style="width:100%" name="AttendanceFilter">
                                                <option value="1">P</option>
                                                <option value="2">A</option>
                                                <option value="4">T</option>
                                                <option value="3">HF</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                    <div class="col-md-2 col-12">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div class="controls">
                                                <div id='spinnerLoader2Attendance' style='display:none;'>
                                                    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="controls text-center" style="margin-top:30px">
                                            <input type="hidden" value="" class="form-control" name="ExecutiveCds"/> &nbsp;<input type="hidden" value="" class="form-control" name="SPExecutiveCds"/>
                                            <button type="button" id="UpdateButton" class="btn btn-primary float-right"  onclick="getFunctionUpdate()" >
                                                Update
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
    </div>
      
    <div class="row match-height" style="margin-top:-15px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-6" style="margin-bottom: -35px;">
                    <div class="card">
                        <div class="card-header" style="margin-top: -10px;">
                            <h4 class="card-title">Executive List : <?php echo sizeof($TableData); ?> ( Selected Executives : <span id="SelectedExecutiveCds"> 0 </span> )</h4> 
    
                        </div>
                        <div class="content-body">
                            <section id="basic-datatable">
                                <div class="card">  
                                    <div class="card-content">
                                        <div class="card-body card-dashboard pt-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover-animation table-hover table-striped" id="AttendanceTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;">
                                                                <input class="form-control" placeholder="Search By Executive Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="AllExeSearch" />
                                                            </th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                        </tr>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;">
                                                                <input class="form-check-input checkbox_All" type="checkbox" style=" margin-left:10px;width: 15px; height: 15px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setSelectedExecutiveCdALL(this)" >
                                                            </th>
                                                            <th style="background-color:#36abb9;color: white;" title="Executive Name">Name</th>
                                                            <th style="background-color:#36abb9;color: white;" title="Designation">Desg</th>
                                                            <th style="background-color:#36abb9;color: white;" title="Corporation">Corp</th>
                                                            <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                            <th style="background-color:#36abb9;color: white;" title="">Supervisor</th>
                                                            <th style="background-color:#36abb9;color: white;">Attendance</th>
                                                            <!-- <th style="background-color:#36abb9;color: white;">Assign Status</th> -->
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
                                                                        <input class="form-check-input element checkbox" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['Executive_Cd'] ; ?>" id="AssignCheckbox" onclick="setSelectedExecutiveCd()" >
                                                                    </td>
                                                                    <!-- <td><?php //echo $srNo++; ?></td> -->
                                                                    <td title="<?php echo $value['MobileNo']; ?>" style="cursor:pointer;"><b><?php echo $value['ExecutiveName']?></b></td>
                                                                    <td><?php if($value['Designation'] == 'Survey Supervisor' || $value['Designation'] == 'SP'){ echo "Supervisor";}
                                                                    elseif($value['Designation'] == 'SE-Belapur' || $value['Designation'] == 'Survey Executive'){echo "Survey Executive";}else{echo $value['Designation'];} ?></td>
                                                                    <td><?php echo $value['ULB']?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td title="<?php echo $value['MobileNo1']; ?>" style="cursor:pointer;"><?php echo $value['SupervisorName']?></td>
                                                                    <td><b><?php if($value['Attendance'] == 1){echo "P"; }elseif($value['Attendance'] == 2){ echo  "A";}elseif($value['Attendance'] == 3){ echo  "HalfDay";}elseif($value['Attendance'] == 4){ echo  "T";}else{ echo "-";} ?></b></td>
                                                                    
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
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-6" style="margin-bottom: -35px;">
                    <div class="card">
                        <div class="card-header" style="margin-top: -10px;">
                            <h4 class="card-title">Executive List : ( Selected Executives : <span id="SelectedSPExecutiveCds"> 0 </span> )</h4> 
                            <h4 class="card-title"> 
                                <div id="SPmsgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;"></div>
                                <div id="SPmsgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;"></div>
                            </h4> 
                        </div>
                        <div class="content-body">
                            <section id="basic-datatable">
                                <div class="card">  
                                    <div class="card-content">
                                        <div class="card-body card-dashboard pt-0">
                                            <div class="table-responsive">
                                            <div id="siteDiv">
                                                <table class="table table-hover-animation table-hover table-striped" id="SPAttendanceTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;">
                                                                <input class="form-control" placeholder="Search By Executive Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="ExeSearch" />
                                                            </th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                            <th style="background-color:#36abb9;color: white;"></th>
                                                        </tr>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;">
                                                                <!-- <input class="form-check-input checkbox_All" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;" id="SelectSPAllCheckbox" name="SelectAllSPCheckbox[]" onchange="setSelectedSPExecutiveCdALL(this)" > -->
                                                            </th>
                                                            <th style="background-color:#36abb9;color: white;" title="Executive Name">Name</th>
                                                            <th style="background-color:#36abb9;color: white;display:none;">ExecutiveName</th>
                                                            <!-- <th style="background-color:#36abb9;color: white;">Corporation Name</th> -->
                                                            <th style="background-color:#36abb9;color: white;" >Site Name</th>
                                                            <th style="background-color:#36abb9;color: white;">InTime</th>
                                                            <th style="background-color:#36abb9;color: white;">Remark</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($SPTableData) > 0){
                                                                $srNo = 1;
                                                                foreach($SPTableData AS $Key=>$value){  
                                                                    
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <input class="form-check-input element checkboxs" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" 
                                                                        value="<?php echo $value['Executive_Cd'] ; ?>" 
                                                                        id="SPCheckbox" onclick="setSelectedSPExecutiveCd()" >
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $value['ExecutiveName']?>
                                                                    </td>
                                                                    <td style="display:none;">
                                                                        <input type="text" style="width:100%;border:0px;" readonly name="Name_<?php echo $value['Executive_Cd']; ?>" id="Name_<?php echo $value['Executive_Cd']; ?>" value="<?php echo $value['ExecutiveName']?>"> 
                                                                    </td>
                                                                    <td style="width:100px;">
                                                                        <select class="select2 form-control"  name="Site_<?php echo $value['Executive_Cd']; ?>" id="Site_<?php echo $value['Executive_Cd']; ?>" >
                                                                        <?php
                                                                           if (sizeof($dataSite)>0) 
                                                                           {
                                                                               foreach ($dataSite as $key => $value1) 
                                                                               {
                                                                                   if($value1["SiteName"] == $value["SiteName"])
                                                                                   {
                                                                           ?>
                                                                                       <option selected="true" value="<?php echo $value1["SiteName"];?>"><?php echo "<b>".$value1["SiteName"]."</b>"; ?></option>
                                                                           <?php
                                                                                   }
                                                                                   else
                                                                                   {
                                                                           ?>
                                                                                       <option value="<?php echo $value1["SiteName"];?>"><?php echo "<b>".$value1["SiteName"]."</b>" ; ?></option>
                                                                           <?php
                                                                                   }
                                                                               }
                                                                           }
                                                                            ?> 
                                                                        </select>
                                                                    </td>
                                                                    <td> <input type="time" style="width:70px;" id="InTime_<?php echo $value['Executive_Cd']; ?>" name="InTime_<?php echo $value['Executive_Cd']; ?>" value="09:30"> </td>
                                                                    <td> <input type="text" style="width:70px;" value="" name="Remark_<?php echo $value['Executive_Cd']; ?>" id="Remark_<?php echo $value['Executive_Cd']; ?>"> </td>
                                                                    
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
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        
        
            
        function SetSityeForAttendance(Site) {
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
                    // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                    // ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    location.reload(true);
                    document.getElementById("spinnerLoader2Attendance").style.display = "none";
                    // $(document).ready(function () {
                    //     $('#AssignExecutiveToSiteTableID').DataTable({
                    //         "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    //     });
                    // });
                    $('.select2').select2();
                }
            }
            
            if (Date === '') {
                alert("Please Select Date!");
            } else {
                document.getElementById("spinnerLoader2Attendance").style.display = "block";
                var queryString = "?Site="+Site;
                ajaxRequest.open("POST", "setElectionNameinsessionAttendanceToSite.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        }
    
        function setAttendanceFilterInSessionForAttendance(Filter) {
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
                    // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                    // ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    location.reload(true);
                    document.getElementById("spinnerLoader2Attendance").style.display = "none";
                    // $(document).ready(function () {
                    //     $('#AssignExecutiveToSiteTableID').DataTable({
                    //         "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    //     });
                    // });
                    $('.select2').select2();
                }
            }
            // alert(Filter);
            if (Date === '') {
                alert("Please Select Date!");
            } else {
                document.getElementById("spinnerLoader2Attendance").style.display = "block";
                var queryString = "?Filter="+Filter;
                ajaxRequest.open("POST", "setElectionNameinsessionAttendanceToSite.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        }
        
        function setWorkInInSessionForAttendance(WorkIn) {
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
                    // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                    // ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    location.reload(true);
                    document.getElementById("spinnerLoader2Attendance").style.display = "none";
                    // $(document).ready(function () {
                    //     $('#AssignExecutiveToSiteTableID').DataTable({
                    //         "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    //     });
                    // });
                    $('.select2').select2();
                }
            }
            
            if (electionName === '') {
                alert("Please Select Corporation!");
            } else {
                document.getElementById("spinnerLoader2Attendance").style.display = "block";
                var queryString = "?WorkIn="+WorkIn;
                ajaxRequest.open("POST", "setElectionNameinsessionAssignExecToSite.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        }
    
        function setSelectedExecutiveCd() {
            var input = document.getElementsByClassName("checkbox");
            var selected = 0;
            var chkAllCDS = "";
            for (var i = 0; i < input.length; i++) {
            if (input[i].checked) {
                var splits = input[i].value;
                
                var CD_Val = '';
                CD_Val += "" + splits + "";
                chkAllCDS += splits + ",";
                // chkAllNames += Name_Val + ", ";
                selected++;
            }
            }
            document.getElementsByName("ExecutiveCds")[0].value = "" + chkAllCDS;
            document.getElementById("SelectedExecutiveCds").innerHTML = selected;
        }
        
        function setSelectedExecutiveCdALL(ele) {
            var checkboxes = document.getElementsByClassName('checkbox');
            if (ele.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                        checkboxes[i].checked = true;
                    }
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    console.log(i)
                    if (checkboxes[i].type == 'checkbox') {
                        checkboxes[i].checked = false;
                    }
                }
            }
    
            setSelectedExecutiveCd();
        }
        
        function getFunctionUpdate(){
    
            var ExecutiveCds = document.getElementsByName('ExecutiveCds')[0].value;
            var SPExecutiveCds = document.getElementsByName('SPExecutiveCds')[0].value;
            if(SPExecutiveCds != ''){
                setSPExecutiveCds();
            }
            if(ExecutiveCds != ''){
                UpdateAttendance();
            }
        }
        
      function setSPExecutiveCds() {
     
     var SurveyDate = document.getElementsByName('Date')[0].value;
     var Attendance = document.getElementsByName('AttendanceFilter')[0].value;
     var ExecutiveCds = document.getElementsByName('SPExecutiveCds')[0].value;
     ExecutiveCds = ExecutiveCds.substring(0, ExecutiveCds.length - 1);
    var ExecutiveCdArr = ExecutiveCds.split(",");
    
     // console.log(ExecutiveCdArr);
     // exit();
     for (let i = 0; i < ExecutiveCdArr.length; i++) {
         var Namecd = ExecutiveCdArr[i];
         var Name = document.getElementsByName("Name_"+Namecd)[0].value;
         var Site = document.getElementsByName("Site_"+Namecd)[0].value;
         var InTime = document.getElementsByName("InTime_"+Namecd)[0].value;    
         // alert(Name + "/" + Site + "/" + InTime);
         InsertData(Namecd,Name,Site,InTime,SurveyDate,Attendance);
     }
    }
    function InsertData(Namecd,Name,Site,InTime,SurveyDate,Attendance){
         $.ajax({
    
             type: "POST",
             url: 'action/SpAttendanceData.php',
             data: { 
                 Namecd: Namecd,
                 Name: Name,
                 SurveyDate: SurveyDate,
                 Attendance: Attendance,
                 Site: Site,
                 InTime: InTime
             },
             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                 $('#SPUpdateButton').attr("disabled", true);
                 $('html').addClass("ajaxLoading");
                 document.getElementById("spinnerLoader2Attendance").style.display = "block";
             },
             success: function(dataResult) {
    
                 var dataResult = JSON.parse(dataResult);
                 if(dataResult.statusCode == 200){
                     $("#SPmsgsuccess").html(dataResult.msg)
                         .hide().fadeIn(1000, function() {
                             $("SPmsgsuccess").append("");
                                 location.reload(true);
                         }).delay(6000).fadeOut("fast");
                 }else{
                     $("#SPmsgfailed").html(dataResult.msg)
                         .hide().fadeIn(800, function() {
                             $("SPmsgfailed").append("");
                                 location.reload(true);
                         }).delay(6000).fadeOut("fast");
                 }
             },
             complete: function() {
                 $('#SPUpdateButton').attr("disabled", false);
                 $('html').removeClass("ajaxLoading");
                 document.getElementById("spinnerLoader2Attendance").style.display = "none";
             }
         });
     }
      function UpdateAttendance() {
     
            //var electionName = document.getElementsByName('electionName')[0].value;
            // var SiteName = document.getElementsByName('SiteName')[0].value;
            var FilterType = document.getElementsByName('AttendanceFilter')[0].value;
            var Date = document.getElementsByName('Date')[0].value;
            var ExecutiveCds = document.getElementsByName('ExecutiveCds')[0].value;
            // var Supervisor = document.getElementsByName('Supervisor')[0].value;
    
    
            //Election_Cd: electionName,
            // if(electionName === ''){
            //     alert("Please select Corporation!");
            // }else 
    
            if(Date === ''){
                alert("Please select Date!");
            }else if(ExecutiveCds === ''){
                alert("Please select Executives!");
            }else{
                $.ajax({
    
                    type: "POST",
                    url: 'action/attendanceData.php',
                    data: { 
                        FilterType: FilterType,
                        Date: Date,
                        ExecutiveCds: ExecutiveCds
                    },
                    beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                        $('#UpdateButton').attr("disabled", true);
                        $('html').addClass("ajaxLoading");
                        document.getElementById("spinnerLoader2Attendance").style.display = "block";
                    },
                    success: function(dataResult) {
                        // alert('in success');
                        // console.log(dataResult);
                        // alert(dataResult);
    
                        var dataResult = JSON.parse(dataResult);
                        if(dataResult.statusCode == 200){
                            $("#msgsuccessAttendance").html(dataResult.msg)
                                .hide().fadeIn(1000, function() {
                                    $("msgsuccessAttendance").append("");
                                        location.reload(true);
                                }).delay(6000).fadeOut("fast");
                        }else{
                            $("#msgfailedAttendance").html(dataResult.msg)
                                .hide().fadeIn(800, function() {
                                    $("msgfailedAttendance").append("");
                                        location.reload(true);
                                }).delay(6000).fadeOut("fast");
                        }
                    },
                    complete: function() {
                        $('#UpdateButton').attr("disabled", false);
                        $('html').removeClass("ajaxLoading");
                        document.getElementById("spinnerLoader2Attendance").style.display = "none";
                    }
                });
            }
            }
        function setSelectedSPExecutiveCd() {
            var input = document.getElementsByClassName('checkboxs');
            var selected = 0;
            var chkAllCDS = "";
            for (var i = 0; i < input.length; i++) {
            if (input[i].checked) {
                var splits = input[i].value;
                
                var CD_Val = '';
                CD_Val += "" + splits + "";
                chkAllCDS += splits + ",";
                // chkAllNames += Name_Val + ", ";
                selected++;
            }
            }
            document.getElementsByName("SPExecutiveCds")[0].value = "" + chkAllCDS;
            document.getElementById("SelectedSPExecutiveCds").innerHTML = selected;
        }
        
    //     function setSelectedSPExecutiveCdALL(ele) {
        
        
    //     var checkboxes = document.getElementsByClassName('checkbox');
    //     if (ele.checked) {
    //         for (var i = 0; i < checkboxes.length; i++) {
    //             if (checkboxes[i].type == 'checkbox') {
    //                 checkboxes[i].checked = true;
    //             }
    //         }
    //     } else {
    //         for (var i = 0; i < checkboxes.length; i++) {
    //             console.log(i)
    //             if (checkboxes[i].type == 'checkbox') {
    //                 checkboxes[i].checked = false;
    //             }
    //         }
    //     }
    
    //     setSelectedSPExecutiveCd();
    
    // }
    
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <script>
                    $(document).ready(function() {
            // Attach an event listener to the search input
            $("#ExeSearch").on("keyup", function() {
                var searchText = $(this).val().toLowerCase(); // Get the lowercase search value
                filterTable(searchText); // Call the filterTable function with the search value
            });
    
            // Function to filter the table based on the search input
            function filterTable(searchText) {
                $("#SPAttendanceTable tbody tr").each(function() {
                    var rowData = $(this).text().toLowerCase(); // Get the lowercase text content of the row
                    // If the row data contains the search value, show the row, otherwise hide it
                    if (rowData.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    
     </script>
        <script>
                    $(document).ready(function() {
            // Attach an event listener to the search input
            $("#AllExeSearch").on("keyup", function() {
                var searchText = $(this).val().toLowerCase(); // Get the lowercase search value
                filterTable(searchText); // Call the filterTable function with the search value
            });
    
            // Function to filter the table based on the search input
            function filterTable(searchText) {
                $("#AttendanceTable tbody tr").each(function() {
                    var rowData = $(this).text().toLowerCase(); // Get the lowercase text content of the row
                    // If the row data contains the search value, show the row, otherwise hide it
                    if (rowData.includes(searchText)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    
    </script>
    
    