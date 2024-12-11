<?php
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $server=$_SESSION['SurveyUtility_ServerIP'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $star = "<b style='color:red;'>*</b>";
    // echo $server;
    $Issue_Solve = "";

    if(isset($_GET['Society']) && isset($_GET['action'])){
        $Society = $_GET['Society'];
        $action = $_GET['action'];
        $query = "SELECT TOP (1) 
                    COALESCE(Ac_No,0) AS Ac_No,
                    COALESCE(Ward,0) AS Ward,
                    COALESCE(Corporator_Name,'') AS Corporator_Name,
                    COALESCE(Society,'') AS Society,
                    COALESCE(Rooms,0) AS Rooms,
                    COALESCE(Pocket_Name,'') AS Pocket_Name,
                    COALESCE(Chairman_Name,'') AS Chairman_Name,
                    COALESCE(Chairman_MobNo,'') AS Chairman_MobNo,
                    COALESCE(Secretory_Name,'') AS Secretory_Name,
                    COALESCE(Secretory_MobNo,'') AS Secretory_MobNo,
                    COALESCE(Issues,'') AS Issues,
                    COALESCE(Issue_Solve,'') AS Issue_Solve,
                    COALESCE(Bulding_Img,'') AS Bulding_Img,
                    COALESCE(Longitude,'') AS Longitude,
                    COALESCE(Lattitude,'') AS Lattitude,
                    COALESCE(SocietyDetail,'') AS SocietyDetail
                    FROM Survey_Entry_Data..Society_Issues
                    WHERE Society = '$Society' AND isActive = 1";
                
            $SocietyData = $db->ExecutveQuerySingleRowSALData($query, $userName, $appName, $developmentMode);
            // print_r($SocietyData);
            // die();
            if(sizeof($SocietyData)>0){
                $Ac_No = $SocietyData["Ac_No"];
                $Ward = $SocietyData["Ward"];
                $Corporator_Name = $SocietyData["Corporator_Name"];
                $Society = $SocietyData["Society"];
                $Rooms = $SocietyData["Rooms"];
                $Pocket_Name = $SocietyData["Pocket_Name"];
                $Chairman_Name = $SocietyData["Chairman_Name"];
                $Chairman_MobNo = $SocietyData["Chairman_MobNo"];
                $Secretory_Name = $SocietyData["Secretory_Name"];
                $Secretory_MobNo = $SocietyData["Secretory_MobNo"];
                $Issues = trim($SocietyData["Issues"]);
                $Issue_Solve = $SocietyData["Issue_Solve"];
                $Bulding_Img = $SocietyData["Bulding_Img"];
                $Longitude = $SocietyData["Longitude"];
                $Lattitude = $SocietyData["Lattitude"];
                $SocietyDetail = $SocietyData["SocietyDetail"];
                // print_r($Issues);
                    if(!empty($action) && $action == 'edit'){
                        $action = "Update";
                    }else if(!empty($action) && $action == 'delete'){
                        $action = "Remove";
                    }
                }else{
                $action = "Insert";
            }
        }else{
            
            $Ac_No = "";
            $Ward = "";
            $Corporator_Name = "";
            $Society = "";
            $Rooms = "";
            $Pocket_Name = "";
            $Chairman_Name = "";
            $Chairman_MobNo = "";
            $Secretory_Name = "";
            $Secretory_MobNo = "";
            $Issues = "";
            $Issue_Solve = "";
            $Bulding_Img = "";
            $Longitude = "";
            $Lattitude = "";
            $SocietyDetail = "";
            $action = "Insert";
        } 
    $DDQuery ="SELECT DISTINCT(Ac_No) as Ac_No FROM Society_Master WHERE Ac_No <> 0";
    $AcNoData = $db->ExecutveQueryMultipleRowSALData($DDQuery, $userName, $appName, $developmentMode);

    $tableQuery ="SELECT
                   COALESCE(Ac_No,0) AS Ac_No,
                    COALESCE(Ward,0) AS Ward,
                    COALESCE(Corporator_Name,'') AS Corporator_Name,
                    COALESCE(Society,'') AS Society,
                    COALESCE(Rooms,0) AS Rooms,
                    COALESCE(Pocket_Name,'') AS Pocket_Name,
                    COALESCE(Chairman_Name,'') AS Chairman_Name,
                    COALESCE(Chairman_MobNo,'') AS Chairman_MobNo,
                    COALESCE(Secretory_Name,'') AS Secretory_Name,
                    COALESCE(Secretory_MobNo,'') AS Secretory_MobNo,
                    COALESCE(Issues,'') AS Issues,
                    COALESCE(Issue_Solve,'') AS Issue_Solve,
                    COALESCE(Bulding_Img,'') AS Bulding_Img,
                    COALESCE(Longitude,'') AS Longitude,
                    COALESCE(Lattitude,'') AS Lattitude,
                    COALESCE(SocietyDetail,'') AS SocietyDetail
                    FROM Survey_Entry_Data..Society_Issues
                    WHERE Issues is not null AND isActive = 1
                    ORDER BY Ac_No,Ward";
    $TableData = $db->ExecutveQueryMultipleRowSALData($tableQuery, $userName, $appName, $developmentMode);
?>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">KDMC Society Issue </h4>
            </div>
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-1 has-info">
                                <label class="control-label  ">Assembly <?php echo $star; ?></label>
                                <input type="text" id="Ac_NoS" name="Ac_NoS" placeholder="Enter Ac No" 
                                class="form-control">
                                <!-- <select name="Ac_NoS" id="Ac_NoS" class="form-control select2">
                                    <option value="">--SELECT--</option>
                                    <?php foreach($AcNoData as $key=>$val){ ?>
                                    <option value=""><?php echo $val['Ac_No'];  ?></option>
                                    <?php } ?>
                                </select> -->
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Society Name</label>
                                <input type="text"  name="Societynm" 
                                placeholder="Enter Society" class="form-control">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Pocket Name </label>
                                <input type="text"  name="PocketS" placeholder="Enter Pocket" class="form-control" >
                            </div>
                            <div class="form-group col-md-1 has-info" style="float:right;">
                                <label class="control-label  "></label>
                                <br>
                                <button id="submitIssue" type="submit" class="btn btn-primary" onclick="getsearchSocieties()"> <i class="fa fa-search" style="color:;"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" id="SocietyList" style="width:100%">

        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">KDMC Society Issue </h4>
            </div>
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-1 has-info">
                                <label class="control-label  ">Assembly <?php echo $star; ?></label>
                                <input type="text"  name="Ac_No" placeholder="Enter Ac No"  value="<?php echo $Ac_No; ?>"
                                class="form-control">
                            </div>
                            <div class="form-group col-md-1 has-info">
                                <label class="control-label  ">ward No</label>
                                <input type="text"  name="Ward_No" value="<?php echo $Ward; ?>"
                                placeholder="Enter Ward No" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-3 has-info">
                                <label class="control-label  ">Society <?php echo $star; ?></label>
                                <input type="text"  name="SocietyName" value="<?php echo $Society; ?>" 
                                placeholder="Enter Society" class="form-control">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Corporator Name</label>
                                <input type="text"  name="Corporator"  value="<?php echo $Corporator_Name; ?>"
                                placeholder="Enter Corporator Name" class="form-control">
                            </div>
                            <div class="form-group col-md-1 has-info">
                                <label class="control-label  ">Rooms</label>
                                <input type="text"  name="Rooms"  value="<?php echo $Rooms; ?>"
                                placeholder="Enter Rooms" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Pocket Name </label>
                                <input type="text"  name="Pocket" placeholder="Enter Pocket" class="form-control" value="<?php echo $Pocket_Name; ?>" >
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Chairman Name </label>
                                <input type="text"  name="Chairman_Name" placeholder="Enter Chairman Name" class="form-control" value="<?php echo $Chairman_Name; ?>" >
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Chairman no</label>
                                <input type="text"  name="Chairman_No" placeholder="Enter Chairman No" class="form-control" value="<?php echo $Chairman_MobNo; ?>" maxlength="10" onkeyup="return onlymobile(event,this);" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Secretory Name</label>
                                <input type="text"  name="Secretory_Name" placeholder="Enter Secretory Name" class="form-control" value="<?php echo $Secretory_Name; ?>" >
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Secretory No</label>
                                <input type="text"  name="Secretory_No" class="form-control" placeholder="Enter Secretory No" value="<?php echo $Secretory_MobNo; ?>" maxlength="10" onkeyup="return onlymobile(event,this);" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-3 has-info">
                                <label class="control-label  ">Issue<?php echo $star; ?></label>
                                <textarea type="text"  name="Issue" class="form-control" placeholder="Enter Issue"><?php echo $Issues; ?></textarea>
                            </div>
                            <?php //if($action == "Update" ){ ?>
                            <div class="form-group col-md-3 has-info">
                                <label class="control-label  ">Issue Solve</label>
                                <textarea type="text" Name="IssueSolve" id="IssueSolve" class="form-control" placeholder="Enter Issue Solve"><?php echo $Issue_Solve; ?></textarea>
                            </div>
                            <?php// } ?>
                            <div class="form-group col-md-2 has-info" style="float:right;">
                                <label class="control-label  "></label>
                                <button id="submitIssue" type="submit" class="btn btn-primary form-control" onclick="getSocietyIssue()" > <?php if($action == "Update" ){ ?> EDIT <?php }else if($action == "Remove" ){ ?>DELETE <?php }else{ ?>ADD <?php } ?></button>
                            </div>

                            <!-- <div class="col-xs-12 col-xl-12   col-12"> -->
                            

                            <div class="col-md-12 text-right" style="float:right;margin-top:20px">
                                <input type="hidden" class="form-control" name="SctCds"/>
                                <input type="hidden" class="form-control" name="SctJsonCds"/>
                                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed"  class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">KDMC Society Issue </h4>
        </div>
        <div class="content-body">
            <div class="card-content">
                <div class="card-body">
                    <table id="SocietyIssueTable" name="SocietyIssueTable" class="table table-hover-animation table-striped table-hover" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Sr No</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Action</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">AcNo</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Ward</th> 
                                <th class="text-center" style="background-color:#F5933C;color:white;">Society</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Issues</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Issues Solve</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Pocket</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Secretory</th>
                                <th class="text-center" style="background-color:#F5933C;color:white;">Chairman</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(sizeof($TableData) > 0){
                                $sr= 1;
                                foreach($TableData as $key=>$val){
                            ?>
                            <tr>
                                <td><?php echo $sr++; ?></td>
                                <td><a href="index.php?p=IssueEntry&action=edit&Society=<?php echo $val["Society"]; ?>"><i style="color:#41bdcc;" class="feather icon-edit"></i></a>
                                <a href="index.php?p=IssueEntry&action=delete&Society=<?php echo $val["Society"]; ?>"><i style="color:#41bdcc;" class="feather icon-trash"></i></a>
                                </td>
                                <td><?php echo $val['Ac_No']; ?></td>
                                <td><?php echo $val['Ward']; ?></td>
                                <td><?php echo $val['Society']; ?></td>
                                <td><?php echo $val['Issues']; ?></td>
                                <td><?php echo $val['Issue_Solve']; ?></td>
                                <td><?php echo $val['Pocket_Name']; ?></td>
                                <td title="<?php echo $val['Secretory_MobNo']; ?>"><?php echo $val['Secretory_Name']; ?></td>
                                <td title="<?php echo $val['Chairman_MobNo']; ?>"><?php echo $val['Chairman_Name']; ?></td>
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
<script>
     var EmployeeCdArray = [];
     var SocietyArray = [];
     var SocietyData = [];
    function getSelectedSocietiesList(ValueOfEmployee){
        // alert(ValueOfEmployee);
        var selected = 0;
        var Checkboxes = document.getElementById('SocietyCheck');
        var Ac_No = document.getElementsByName('Ac_NoS')[0].value;
        var PocketName = document.getElementsByName('PocketS')[0].value;
        // alert(Ac_No);
        const ValueOfEmployeeParts = ValueOfEmployee.split("~");
        const SocietyCd = ValueOfEmployeeParts[0];
        const SocietyName = ValueOfEmployeeParts[1];
        const Building_Image = ValueOfEmployeeParts[2];
        const Longitude = ValueOfEmployeeParts[3];
        const Latitude = ValueOfEmployeeParts[4];
        const Society = Building_Image+'~'+Longitude+'~'+Latitude;
        var check = EmployeeCdArray.includes(Society);
        // if(check == false && Checkboxes.checked){
        // }else{
        //     const index = EmployeeCdArray.indexOf(Society);
        //     if (index > -1) {
        //         EmployeeCdArray.splice(index, 1); 
        //     }
        // }
        var existingSocietyIndex = SocietyData.findIndex(society => society.SocietyCd === SocietyCd);
        if(existingSocietyIndex === -1 ){

            selected++;
            EmployeeCdArray.push(Society);
            SocietyArray.push(SocietyName);
            SocietyData.push({
                "SocietyCd": SocietyCd,
                "SocietyName": SocietyName,
                "Building_Image": Building_Image,
                "Longitude": Longitude,
                "Latitude": Latitude 
            });
        }else{
            if (existingSocietyIndex !== -1) {
            EmployeeCdArray.splice(existingSocietyIndex, 1);
            SocietyArray.splice(existingSocietyIndex, 1);
            SocietyData.splice(existingSocietyIndex, 1);
        }
        }
        let commonPrefix = '';

        // Splitting the society names to find the common prefix
        for (let i = 0; i < SocietyArray.length; i++) {
            const words = SocietyArray[i].split(' ');
            
            if (commonPrefix === '') {
                commonPrefix = words;
            } else {
                for (let j = 0; j < commonPrefix.length; j++) {
                    if (commonPrefix[j] !== words[j]) {
                        commonPrefix = commonPrefix.slice(0, j);
                        break;
                    }
                }
            }
        }

        commonPrefix = commonPrefix.join(' ');

        const uncommonParts = [];

        // Gathering uncommon parts
        for (let i = 0; i < SocietyArray.length; i++) {
            const uncommonPart = SocietyArray[i].replace(commonPrefix + ' ', '');
            uncommonParts.push(uncommonPart);
        }

        const result = commonPrefix + ' ~ ' + uncommonParts.join(', ');

        var selectedSocietiesJSON = JSON.stringify(SocietyData);
        selected = EmployeeCdArray.length;

        document.getElementsByName("SctJsonCds")[0].value = selectedSocietiesJSON;
        document.getElementsByName("SocietyName")[0].value = result;
        document.getElementsByName("Ac_No")[0].value = Ac_No;
        document.getElementsByName("Pocket")[0].value = PocketName;
        document.getElementsByName("SctCds")[0].value = EmployeeCdArray;
        document.getElementById("SelectedSocietyCds").innerHTML = selected; 
    }
   



 
</script>