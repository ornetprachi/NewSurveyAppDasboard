<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$peopleCount = '';
$star = "<b style='color:red;'>*</b>";
$action = "Insert";


$fromdate = '2024-07-01';
$todate = date('Y-m-d');

if(
    (isset($_GET['SiteName']) && !empty($_GET['SiteName'])) &&
    (isset($_GET['electionName']) && !empty($_GET['electionName']))
) {
    $electionName = $_GET['electionName'];
    $SiteName = $_GET['SiteName'];

    if(
        (isset($_SESSION['SurveyUA__FromDate_For_SocietyIssue'])) &&
        (isset($_SESSION['SurveyUA__ToDate_For_SocietyIssue']))
    ){
        $fromdate = $_SESSION['SurveyUA__FromDate_For_SocietyIssue'];
        $todate = $_SESSION['SurveyUA__ToDate_For_SocietyIssue'];

      
    }else{
        if($electionName == 'PT188'){
            
            $fromdate = '2024-08-01';
            $todate = date('Y-m-d');

        }else{
            
            $fromdate = '2024-01-01';
            $todate = date('Y-m-d');
        }
    }

    

    $Qry = "SELECT SiteName,SocietyName,Society_Cd,Rooms,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,Remark,Building_Image,
            COALESCE(um.ExecutiveName,'') as ExecutiveName,
            CONVERT(varchar,sm.UpdatedDate,23) as UpdatedDate
            FROM Survey_Entry_Data..Society_Master	as sm
            LEFT JOIN Survey_Entry_Data..User_Master as um on (sm.UpdatedByUser = um.UserName and um.AppName = 'SurveyUtilityApp')
            WHERE SiteName = '$SiteName' AND sm.ElectionName = '$electionName' AND Remark != '' AND Remark IS NOT NULL  AND YEAR(sm.UpdatedDate) = 2024
            AND CONVERT(varchar,sm.UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate'
            order by SocietyName";

    $Data = $db->ExecutveQueryMultipleRowSALData($Qry, $userName, $appName, $developmentMode);

}else{
    $Data =array();
}
if(isset($_GET['SocietycD']) && isset($_GET['action'])){
    $SocietycD = $_GET['SocietycD'];
    $action = $_GET['action'];
    $SiteName = $_GET['SiteName'];
    $query = "SELECT TOP (1)
                COALESCE(SocietyName,'') AS SocietyName,
                COALESCE(Society_Cd,0) AS Society_Cd,
                COALESCE(Rooms,0) AS Rooms,
                COALESCE(ChairmanName,'') AS ChairmanName,
                COALESCE(ChairmanMobileNo,'') AS ChairmanMobileNo,
                COALESCE(SecretaryName,'') AS SecretaryName,
                COALESCE(SecretaryMobileNo,'') AS SecretaryMobileNo,
                COALESCE(Remark,'') AS Remark,sm.ElectionName,
                COALESCE(um.ExecutiveName,'') as ExecutiveName,
                CONVERT(varchar,sm.UpdatedDate,23) as UpdatedDate,  
                COALESCE(IssueSolvedRemark,'') AS IssueSolvedRemark
                FROM Survey_Entry_Data..Society_Master as sm
                LEFT JOIN Survey_Entry_Data..User_Master as um on (sm.UpdatedByUser = um.UserName and um.AppName = 'SurveyUtilityApp')
                WHERE Society_Cd = '$SocietycD' ";

        $SocietyData = $db->ExecutveQuerySingleRowSALData($query, $userName, $appName, $developmentMode);
        // print_r($SocietyData);
        // die();
        if(sizeof($SocietyData)>0){
            $electionName = $SocietyData["ElectionName"];
            $SocietyName = $SocietyData["SocietyName"];
            $Society_Cd = $SocietyData["Society_Cd"];
            $Rooms = $SocietyData["Rooms"];
            $Chairman_Name = $SocietyData["ChairmanName"];
            $Chairman_MobNo = $SocietyData["ChairmanMobileNo"];
            $Secretory_Name = $SocietyData["SecretaryName"];
            $Secretory_MobNo = $SocietyData["SecretaryMobileNo"];
            $IssueSolvedRemark = $SocietyData["IssueSolvedRemark"];
            $ExecutiveName = $SocietyData["ExecutiveName"];
            $Remark = trim($SocietyData["Remark"]);
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

        $SocietyName = "";
        $Society_Cd = "";
        $Rooms = "";
        $Chairman_Name = "";
        $Chairman_MobNo = "";
        $Secretory_Name = "";
        $Secretory_MobNo = "";
        $Remark = "";
        $ExecutiveName = "";
        $action = "Insert";
    }

        
    $Query = "SELECT SiteName
                FROM Survey_Entry_Data..Society_Master 
                WHERE ElectionName = '$electionName' AND Remark != '' AND Remark is NOT NULL  AND YEAR(UpdatedDate) = 2024
                GROUP BY SiteName";
    $SiteData = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);
?>
<div class="row match-height" style="margin-bottom:-10px">
    <div class="col-md-12">
        <div class="card" id="EditSociety" style="padding-bottom:-10px;display:<?php if($action == 'Update' || $action == 'edit'){ echo "block";}else{ echo "none";} ?>">
            <div class="card-header mt-0" style="padding-top:10px">
                <div class="row" style="width:100%;padding-bottom:10px;">
                    <div class="col-md-8" >
                        <div style="padding-top:10px">
                            <h4 class="card-title">Edit Society(<?php echo $SocietyName; ?>)</h4>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="padding-top:10px">
                           <h5> <span style="float:right"><b>Added By - </b><b style="color:blue;"><?php echo $ExecutiveName; ?></b></span></h5>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body pt-1">
                <div class="row" style="padding-bottom: -10px;">

                    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                            <div class="form-group col-md-3 has-info" style="display:none;">
                                <label class="control-label  ">SocietyName <?php echo $star; ?></label>
                                <input type="text"  name="Society"  value="<?php echo $SocietyName; ?>"
                                class="form-control" readonly>
                            </div>
                        <div class="row">
                            <div class="form-group col-md-3 has-info">
                                <label class="control-label  ">Society  Issue<?php echo $star; ?></label>
                                <textarea type="text"  name="Issue" class="form-control" placeholder="Enter Issue"><?php echo $Remark; ?></textarea>
                            </div>
                            <div class="form-group col-md-1 has-info">
                                <label class="control-label  ">Rooms</label>
                                <input type="text"  name="Rooms" value="<?php echo $Rooms; ?>"
                                placeholder="Enter Rooms" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Secretory Name</label>
                                <input type="text"  name="SecretoryName" value="<?php echo $Secretory_Name; ?>"
                                placeholder="Enter Chairman Name" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Secretory No</label>
                                <input type="text"  name="SecretoryNo" value="<?php echo $Secretory_MobNo; ?>"
                                placeholder="Enter Chairman Name" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Chairman Name</label>
                                <input type="text"  name="ChairmnName" value="<?php echo $Chairman_Name; ?>"
                                placeholder="Enter Chairman Name" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>
                            <div class="form-group col-md-2 has-info">
                                <label class="control-label  ">Chairman Mobile No</label>
                                <input type="text"  name="ChairmnNo" value="<?php echo $Chairman_MobNo; ?>"
                                placeholder="Enter Chairman Mobile No" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                            </div>

                            <div class="form-group col-md-1 has-info" style="margin-bottom:1px;">
                                <label><b>Issue Solved</b></label>
                                <input class="form-check-input checkbox" type="checkbox" style=" margin-left:10px;width: 15px; height: 15px;" onchange="showSolvedIssue()" id="IssueSolved" name="SoxietyCd[]" >

                            </div>
                            <div class="form-group col-md-3 has-info" >
                                <textarea type="text"  name="SolvedIssue" id="SolvedIssue" class="form-control" placeholder="Enter Solved Issue" style="display:none;" value="<?php echo $IssueSolvedRemark; ?>"></textarea>
                            </div>
                            <div class="form-group col-md-7 has-info" style="margin-bottom:1px;">
                                <input type="hidden" class="form-control" name="SocietyCd" value="<?php echo $Society_Cd; ?>"/>
                                <input type="hidden" class="form-control" name="EleNme" value="<?php echo $electionName; ?>"/>
                                <input type="hidden" class="form-control" name="SiteName" value="<?php echo $SiteName; ?>"/>
                                <input type="hidden" name="action" value="<?php echo $action; ?>" />
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed"  class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>

                            </div>
                            <div class="form-group col-md-1 has-info" style="float:right;margin-bottom:1px;">
                                <label class="control-label  "></label>
                                <button id="submitSocIssue" type="submit" class="btn btn-primary form-control" onclick="getSocietyWiseIssue()" > <?php if($action == "Update" ){ ?> EDIT <?php }else if($action == "Remove" ){ ?>DELETE <?php }else{ ?>ADD <?php } ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body pt-2">
                <div class="row" style="padding-bottom: -10px;">
                    <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                        <div class="form-group">
                            <label>Site</label>
                            <div class="controls">
                                <select class="select2 form-control"  name="siteName" onchange="getSite(this.value)">
                                    <option value="">--Select--</option>
                                    <?php
                                        if (sizeof($SiteData)>0) 
                                        {
                                            foreach ($SiteData as $key => $value) 
                                            {
                                                if($SiteName == $value["SiteName"])
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
                    
                    <div class="col-xs-1 col-md-1 col-xl-1">
                        <div class="controls" style="padding-top:25px;">
                            <button type="button" class="btn btn-primary" onclick="setDateForSocietyIssue()"  id="SocietyIssueBtn">
                                    Refresh
                            </button>
                        </div>
                        <script>
                            document.getElementById('SocietyIssueBtn').addEventListener("click", function(){
                                this.classList.add("loading");
                                this.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="padding-bottom:-10px">
            <div class="card-header mt-0" style="padding-top:10px">
                <div class="row" style="width:100%">
                    <div class="col-md-8" >
                        <div style="padding-top:10px">
                            <h2><?php echo $SiteName?> Society Issue</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0" id="">

                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;" id="SurveyQCList">
                                <thead>
                                <tr>
                                    <th style="background-color:#36abb9;color: white;">Sr</th>
                                    <th style="background-color:#36abb9;color: white;">Action</th>
                                    <th style="background-color:#36abb9;color: white;">Photo</th>
                                    <th style="background-color:#36abb9;color: white;">Society Name</th>
                                    <th style="background-color:#36abb9;color: white;">Rooms</th>
                                    <th style="background-color:#36abb9;color: white;">Issue</th>
                                    <th style="background-color:#36abb9;color: white;">Secretory</th>
                                    <th style="background-color:#36abb9;color: white;">Chairman</th>
                                    <th style="background-color:#36abb9;color: white;">Contact Person</th>
                                    <th style="background-color:#36abb9;color: white;">Added By</th>
                                    <th style="background-color:#36abb9;color: white;">Added Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(sizeof($Data) > 0){
                                            $srNo = 1;
                                            foreach($Data AS $Key=>$value){
                                            ?>
                                        <tr>
                                            <td><?php echo $srNo++;  ?></td>
                                            <td>
                                                <a href="index.php?p=SocietyIssue&action=edit&SiteName=<?php echo $SiteName; ?>&electionName=<?php echo $electionName; ?>&SocietycD=<?php echo $value["Society_Cd"]; ?>"><i style="color:#41bdcc;" class="feather icon-edit"></i></a>
                                                <!-- <a href="index.php?p=SocietyIssue&action=delete&Society=<?php echo $value["Society"]; ?>"><i style="color:#41bdcc;" class="feather icon-trash"></i></a> -->
                                            </td>
                                            <td>

                                                <img src="<?php echo $value['Building_Image']?>" class="docimg" height="80" width="90" style="border:1px solid #36abb9;border-radius:12px;" <?php if($value['Building_Image'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                            </td>
                                            <td><?php echo $value['SocietyName']; ?></td>
                                            <td><?php echo $value['Rooms']; ?></td>
                                            <td><?php echo $value['Remark']; ?></td>
                                            <td><?php echo $value['SecretaryName']."(".$value['SecretaryMobileNo'].")"; ?></td>
                                            <td><?php echo $value['ChairmanName']."(".$value['ChairmanMobileNo'].")"; ?></td>
                                            <td><?php echo $value['TresurerName']."(".$value['TresurerMobileNo'].")"; ?></td>
                                            <td><?php echo $value['ExecutiveName']; ?></td>
                                            <td><?php echo $value['UpdatedDate']; ?></td>
                                        </tr>
                                            <?php
                                            }
                                        }else{ ?>
                                            <tr><td colspan="9">No Record Found</td></tr>
                                    <?php
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
<script>
    function getSocietyWiseIssue() {

        var SocietyCd = document.getElementsByName('SocietyCd')[0].value;
        var SocietyName = document.getElementsByName('Society')[0].value;
        var Rooms = document.getElementsByName('Rooms')[0].value;
        var Chairman_Name = document.getElementsByName('ChairmnName')[0].value;
        var Chairman_No = document.getElementsByName('ChairmnNo')[0].value;
        var Secretory_Name = document.getElementsByName('SecretoryName')[0].value;
        var Secretory_No = document.getElementsByName('SecretoryNo')[0].value;
        var Issue = document.getElementsByName('Issue')[0].value;
        var SiteName = document.getElementsByName('SiteName')[0].value;
        var EleNme = document.getElementsByName('EleNme')[0].value;
        var IssueSolvedRemark = document.getElementsByName('SolvedIssue')[0].value;
        var action = document.getElementsByName('action')[0].value;
        var checkboxes = document.getElementsByClassName('checkbox');
        if(checkboxes[0].checked){
         var Solve =1;
        }else{
         var Solve =0;
        }
        // die();
        if(Issue === '' ){
            alert("Please Enter Issue!");
        }else{
            $.ajax({
                type: "POST",
                url: 'action/EditSocietyIssue.php',
                data: {
                    SocietyCd: SocietyCd,
                    SocietyName: SocietyName,
                    Rooms: Rooms,
                    Chairman_Name: Chairman_Name,
                    Chairman_No: Chairman_No,
                    Secretory_Name: Secretory_Name,
                    Secretory_No: Secretory_No,
                    Issue: Issue,
                    EleNme: EleNme,
                    SiteName: SiteName,
                    Solve: Solve,
                    IssueSolvedRemark: IssueSolvedRemark,
                    action: action
                },
                beforeSend: function() {
                    // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('html').addClass("ajaxLoading");
                },
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        $("#msgsuccess").html(dataResult.msg)
                            .hide().fadeIn(1000, function() {
                                $("msgsuccess").append("");
                                window.location.href='index.php?p=SocietyIssue&electionName='+dataResult.Election+'&SiteName='+dataResult.SiteName;
                                    //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                        }).delay(3000).fadeOut("fast");
                    }else{
                        $("#msgfailed").html(dataResult.msg)
                            .hide().fadeIn(800, function() {
                                $("msgfailed").append("");
                                window.location.href='index.php?p=SocietyIssue&electionName='+dataResult.Election+'&SiteName='+dataResult.SiteName;
                                    // $( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                        }).delay(3000).fadeOut("fast");
                    }
                },
                complete: function() {
                }
            });
        }
    }
    function getSite(value){
        var ajaxRequest; // The variable that makes Ajax possible!
    // alert(Executive);
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
            window.location.href='index.php?p=SocietyIssue&electionName=NMMC_ES_151&SiteName='+value;
            }

            var queryString = "?SiteName="+value;
            ajaxRequest.open("POST", "setSiteNameForElectionSummaryInSession.php" + queryString, true);
            ajaxRequest.send(null);

    }
    function setDateForSocietyIssue(){
        

        var fromdate = document.getElementsByName('fromdate')[0].value;
        var todate = document.getElementsByName('todate')[0].value;
        var SiteName = document.getElementsByName('siteName')[0].value;
        var EleNme = document.getElementsByName('EleNme')[0].value;

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
                window.location.href='index.php?p=SocietyIssue&electionName='+EleNme+'&SiteName='+SiteName;
            }
        }


        // var div = 'DateWise';
        // alert(WorkingDays);
        // alert(ToWorkingdays);
        // alert(Site);
        if (fromdate > todate) {
            alert("Please select fromdate less than todate!");
        } else {
            var queryString = "?fromdate="+fromdate+"&todate="+todate+"&SiteName="+SiteName+"&Election="+EleNme;
            ajaxRequest.open("POST", "SetFromAndToDateForSocietyIssue.php" + queryString, true);
            ajaxRequest.send(null);
        }



    }
    function showSolvedIssue(){
        var checkboxes = document.getElementsByClassName('checkbox');
        if(checkboxes[0].checked){
            $('#SolvedIssue').show();
        }else{
            $('#SolvedIssue').hide();
        }
        // 
    }
</script>
