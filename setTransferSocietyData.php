<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$Designation = $_SESSION['SurveyUA_Designation'];


$Site  = '';
$SocietyCd  = '';
$ToServerName = '';
$FromServerName = '';
$FromElectionName = '';
$ToElectionName = '';
$FromElectionNameData = array();
$ToElectionNameData = array();
$AllVotersData = '';
 
// echo $electionCd . " - " . $electionName;



    $query = "SELECT sm.SiteName FROM Site_Master AS sm
            LEFT JOIN Survey_Entry_Data..Election_Master as em on (sm.ElectionName = em.ElectionName)
            WHERE em.ULB = '$ULB'
            order by sm.SiteName ;";
    $FromSiteNameData = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

 

if(isset($_SESSION['To_SocietyTransfer_SocietyCd']) && !empty($_SESSION['To_SocietyTransfer_SocietyCd'])){
    $ToSociety = $_SESSION['To_SocietyTransfer_SocietyCd'];
}



if(isset($_SESSION['From_SocietyTransfer_SiteName']) && !empty($_SESSION['From_SocietyTransfer_SiteName'])){
    $Site = $_SESSION['From_SocietyTransfer_SiteName'];

    $query = "SELECT Society_Cd,SocietyName FROM Society_Master WHERE SiteName = '$Site' order by SocietyName ;";
    $FromSocietyNameData = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);
}
if(isset($_SESSION['From_SocietyTransfer_SocietyCd']) && !empty($_SESSION['From_SocietyTransfer_SocietyCd'])){
    $SocietyCd = $_SESSION['From_SocietyTransfer_SocietyCd'];

    if(isset($_SESSION['SocietyTransfer_UpdatedDate']) && !empty($_SESSION['SocietyTransfer_UpdatedDate'])){
        $UpdatedDate = $_SESSION['SocietyTransfer_UpdatedDate'];

    }else{
        $UpdatedDate = "";
    }
    if(isset($_SESSION['SocietyTransfer_UpdatedBy']) && !empty($_SESSION['SocietyTransfer_UpdatedBy'])){
        $UpdatedBy = $_SESSION['SocietyTransfer_UpdatedBy'];
    }else{
        $UpdatedBy = "";
    }

    $cond= "";
    if($UpdatedDate !== "" && $UpdatedBy == ""){
        $cond = "WHERE Convert(Varchar, D.UpdatedDate, 34) = '$UpdatedDate'";
    }

    if($UpdatedBy !== "" && $UpdatedDate == ""){
        $cond = "WHERE D.UpdateByUser = '$UpdatedBy'";
    }

    if($UpdatedDate !== "" && $UpdatedBy !== ""){
        $cond = "WHERE Convert(Varchar, D.UpdatedDate, 34) = '$UpdatedDate' AND D.UpdateByUser = '$UpdatedBy'";
    }

    $Qry = " SELECT DBName FROM Survey_Entry_Data..Election_Master as em
   LEFT JOIN Site_Master as sm on (em.ElectionName = sm.ElectionName) WHERE sm.SiteName = '$Site'";
    $DbData = $db->ExecutveQuerySingleRowSALData($ULB,$Qry, $userName, $appName, $developmentMode);
    $DBName = $DbData['DBName'];

    $Subqry= "SELECT SocietyName FROM Society_Master WHERE Society_Cd = '$SocietyCd'";
    $SocietyData = $db->ExecutveQuerySingleRowSALData($ULB,$Subqry, $userName, $appName, $developmentMode);;


// IF($SocietyCd !=''){
    $InfoQry = "SELECT 
    D.UpdateByUser,
    ex.ExecutiveName,
    Convert(Varchar, D.UpdatedDate, 34) AS UpdatedDate
    FROM 
    (
        SELECT
             UpdateByUser,CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM  
            Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
            AND Society_Cd = $SocietyCd
        UNION 
        SELECT 
            UpdateByUser, CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM 
            NewVoterRegistration 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
            AND Society_Cd = $SocietyCd
        UNION  
        SELECT 
           UpdateByUser,CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM 
            LockRoom 
            WHERE (Locked = 1) 
            AND Society_Cd = $SocietyCd
    ) AS D
	LEFT JOIN Survey_Entry_Data..User_Master as em on (D.UpdateByUser = em.UserName COLLATE SQL_Latin1_General_CP1_CI_AS)
	LEFT JOIN Survey_Entry_Data..Executive_Master as ex on (em.Executive_Cd = ex.Executive_Cd)
    ORDER BY  Convert(varchar, D.UpdatedDate,20);";

    $UpdtedData = $db->ExecutveQueryMultipleRowSALData($ULB,$InfoQry, $userName, $appName, $developmentMode);


    $DetailQuery = "SELECT 
    D.Voter_Cd,
    D.Datatype,
	D.FullName,
	D.Floor,
	D.RoomNo,
	D.Remark,
    D.UpdateByUser,
    ex.ExecutiveName,
    Convert(Varchar, D.UpdatedDate, 34) AS UpdatedDate
    FROM 
    (
        SELECT
            Voter_Cd,'Voter' as Datatype,FullName,RoomNo,Col4 as Floor,Remark,UpdateByUser,CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM  
            Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
            AND Society_Cd = $SocietyCd
        UNION 
        SELECT 
            Voter_Cd,'NonVoter' as Datatype,Fullname,Roomno,Col4 as Floor,Remark,UpdateByUser, CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM 
            NewVoterRegistration 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
            AND Subloc_cd = $SocietyCd
        UNION  
        SELECT 
            LR_Cd AS Voter_Cd,'LockRoom' as Datatype,'LOCKED' AS FullName,RoomNo,FloorNo as Floor,Remark,UpdateByUser,CONVERT(varchar,UpdatedDate,34) AS UpdatedDate
            FROM 
            LockRoom 
            WHERE (Locked = 1) 
            AND Society_Cd = $SocietyCd
    ) AS D
	LEFT JOIN Survey_Entry_Data..User_Master as em on (D.UpdateByUser = em.UserName COLLATE SQL_Latin1_General_CP1_CI_AS)
	LEFT JOIN Survey_Entry_Data..Executive_Master as ex on (em.Executive_Cd = ex.Executive_Cd)
    $cond
    ORDER BY  Convert(varchar, D.UpdatedDate,20);";
    $DetailData = $db->ExecutveQueryMultipleRowSALData($ULB,$DetailQuery, $userName, $appName, $developmentMode);

    foreach($DetailData as $Key=>$val){
        $Vtr = $val['Voter_Cd'];
        $Dtp = $val['Datatype'];
        $AllVotersData .= $Vtr.'~'.$Dtp.",";

    }
    // print_r($DetailQuery);
// }
}

?>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-xs-12 col-xl-4 col-md-4 col-12"> -->
                                <!-- <div class="row"> -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Site</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="FromSite" onchange="SetSiteNameforTransfer(this.value)">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($FromSiteNameData)>0) 
                                                    {
                                                        foreach ($FromSiteNameData as $key => $value) 
                                                            {
                                                                if($Site == $value["SiteName"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['SiteName']; ?>"><?php echo $value["SiteName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["SiteName"];?>"><?php echo $value["SiteName"];?></option>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Society</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="FromSociety" onchange="SetfromSociety(this.value)">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($FromSocietyNameData)>0) 
                                                    {
                                                        foreach ($FromSocietyNameData as $key => $value) 
                                                            {
                                                                if($SocietyCd == $value["Society_Cd"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['Society_Cd']; ?>"><?php echo $value["SocietyName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["Society_Cd"];?>"><?php echo $value["SocietyName"];?></option>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Society</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="ToSociety" onchange="SetToSociety(this.value)">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($FromSocietyNameData)>0) 
                                                    {
                                                        foreach ($FromSocietyNameData as $key => $value) 
                                                            {
                                                                if($SocietyCd == $value["Society_Cd"]){
                                                                    continue;
                                                                }else{
                                                                    
                                                                if($ToSociety == $value["Society_Cd"])
                                                                {
                                                    ?>
                                                                 <option selected="true" value="<?php echo $value['Society_Cd']; ?>"><?php echo $value["SocietyName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["Society_Cd"];?>"><?php echo $value["SocietyName"];?></option>
                                                    <?php
                                                                }
                                                            }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Updated Date</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="UpdateDate" onchange="SetUpdatedDate(this.value)">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($UpdtedData)>0) 
                                                    {
                                                        foreach ($UpdtedData as $key => $value) 
                                                            {
                                                                if($UpdatedDate == $value["UpdatedDate"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['UpdatedDate']; ?>"><?php echo $value["UpdatedDate"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["UpdatedDate"];?>"><?php echo $value["UpdatedDate"];?></option>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Updated By</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="UpdatedBy" onchange="SetUpdatedBy(this.value)">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($UpdtedData)>0) 
                                                    {
                                                        foreach ($UpdtedData as $key => $value) 
                                                            {
                                                                if($UpdatedBy == $value["UpdateByUser"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['UpdateByUser']; ?>"><?php echo $value["ExecutiveName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["UpdateByUser"];?>"><?php echo $value["ExecutiveName"];?></option>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?> 
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-xl-10 col-md-10 col-12">
                                <input type="" value="" class="form-control" name="SocietyCds"/>
                                <input type="hidden" value="<?php echo substr($AllVotersData,0,-1); ?>" class="form-control" name="AllSocietyCds"/>
                                <div id="waitMSG" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12"> 
                                <button type="button" id="UpdateButton" class="btn btn-primary float-right"  onclick="TransferData('<?php echo $DBName; ?>')" >
                                    Trasfer
                                </button>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12" style="align-items:center">
                                <center>
                                    <div id='Loader2' style='display:none'>
                                        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                    </div>
                                </center>
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
            <div class="card-header" style="margin-top: -10px;">
            <?php if($SocietyCd != ''){?>
                <h4 class="card-title"><?php echo $SocietyData['SocietyName']; ?> :  ( Selected Entries : <span id="SelectedSocietiesCds"> 0 </span> )</h4>
                <?php } ?>
            </div>
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover-animation table-striped table-hover" id="SelectedSocietyData">
                                <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                        <th style="background-color:#36abb9;color: white;">
                                        <input  class="form-control form-control-sm flatpickr  flatpickr-input active" class="form-check-input checkbox_All" 
                                            type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;" id="SelectSctAllCheckbox" name="SelectSctAllCheckbox" onclick="setSelectedAllVotersCds()" >
                                        </th>
                                        <th style="background-color:#36abb9;color: white;">Name</th>
                                        <th style="background-color:#36abb9;color: white;">Floor</th>
                                        <th style="background-color:#36abb9;color: white;">Room No</th>
                                        <th style="background-color:#36abb9;color: white;">Updated Date</th>
                                        <th style="background-color:#36abb9;color: white;">Updated By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                    if (sizeof($DetailData)>0){
                                        $Sr = 1;
                                        foreach ($DetailData as $key => $value) 
                                            { ?>
                                    <tr>
                                        <td><?php echo $Sr++; ?></td>
                                        <td>
                                            <input  class="form-control form-control-sm flatpickr  flatpickr-input active form-check-input checkbox_All Voter-checkbox" type="checkbox" 
                                            style="margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" id="VoterCheck"
                                            onclick="getSelectedSocietyList('<?php echo $value['Voter_Cd'].'~'.$value['Datatype']; ?>')" >
                                        </td>
                                        <td><?php echo $value['FullName']; ?></td>
                                        <td><?php echo $value['Floor']; ?></td>
                                        <td><?php echo $value['RoomNo']; ?></td>
                                        <td><?php echo $value['UpdatedDate']; ?></td>
                                        <td><?php echo $value['ExecutiveName']; ?></td>
                                    </tr>
                                    <?php 
                                        }
                                    }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

  



        // ------------------------------New--------------------------------

    var EmployeeCdArray = [];
    function getSelectedSocietyList(ValueOfEmployee){
        // alert(ValueOfEmployee);
        var selected = 0;
        var Checkboxes = document.getElementById('VoterCheck');
        const ValueOfEmployeeParts = ValueOfEmployee.split("~");
        const EmployeeCd = ValueOfEmployeeParts[0];
        const EmployeeName = ValueOfEmployeeParts[1];
        const EmployeeNameArr = EmployeeName.split(" ");
        EmployeeFirstName = EmployeeNameArr[0];
        var check = EmployeeCdArray.includes(ValueOfEmployee);
        if(check == false && Checkboxes.checked){
            // selected++;
            EmployeeCdArray.push(ValueOfEmployee);
        }else{
            const index = EmployeeCdArray.indexOf(ValueOfEmployee);
            if (index > -1) {
                EmployeeCdArray.splice(index, 1); 
            }
        }
        selected = EmployeeCdArray.length;
        document.getElementsByName("SocietyCds")[0].value = EmployeeCdArray;
        document.getElementById("SelectedSocietiesCds").innerHTML = selected; 
    }
    function setSelectedAllVotersCds(){
        
        var Checkboxes = document.getElementById('VoterCheck');
        var AllEmployeesData = document.getElementsByName("AllSocietyCds")[0].value;
        const AllEmployeesDataValues = AllEmployeesData.split(",");
        
        var selectAllCheckbox = document.getElementById('SelectSctAllCheckbox');

        var selected = 0;
        for (var i = 0; i < AllEmployeesDataValues.length; i++) {
            
            var check = EmployeeCdArray.includes(AllEmployeesDataValues[i]);
            // alert(check);
            if(check == false){
                // EmployeeCdArray.splice();
                EmployeeCdArray.push(AllEmployeesDataValues[i]);
                selected++;
            }
            else if(check == true && !Checkboxes.checked || !selectAllCheckbox.checked){
                const index = EmployeeCdArray.indexOf(AllEmployeesDataValues[i]);
                if (index > -1) {
                    EmployeeCdArray.splice(index, 1); 
                }
            }
        }

        selectAllCheckbox.addEventListener('change', function() {
            var employeeCheckboxes = document.getElementsByClassName('Voter-checkbox');
            for (var j = 0; j < employeeCheckboxes.length; j++) {
                employeeCheckboxes[j].checked = selectAllCheckbox.checked;
            }
        });

        document.getElementsByName("SocietyCds")[0].value = EmployeeCdArray;
        document.getElementById("SelectedSocietiesCds").innerHTML = selected; 
    }

</script>

</section>