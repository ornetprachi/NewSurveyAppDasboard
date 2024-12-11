<?php
date_default_timezone_set('Asia/Kolkata');

    
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$dataElectionName = $db->getSurveyUtilityCorporationElectionData($ULB, $userName, $appName, $developmentMode);

$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}else{
    $ServerIP ="103.14.99.154";
}

$currentDate = date('Y-m-d');
$loggedusername='';
$exename = '';
$msg ='';
$Site_Cd = "";
$ClientName = '';
$SiteName = '';
$Area = '';
$Ward_No = '';
$Ac_No = '';
$Site_Start_Date = '';
$Site_End_Date = '';
$Remark = '';
$kmlFileUrl = '';
$SupervisorName = '';
$ManagerName = '';
$Manager2 = '';
$ElectionName = '';
$ClientNameM = '';
$MobileNo = '';
$table_data='';
$action = 'Insert';
$SiteStatus = "";
$SiteNameForEdit = "";
$star = "<b style='color:red;'>*</b>";



        
if(isset($_GET['flag']) && $_GET['flag'] == 'U'){
    echo "<script>
                alert('Updated Successfully');
                window.location.href = 'index.php?p=site-master';
          </script>";
}
else if(isset($_GET['flag']) && $_GET['flag'] == 'D'){
    echo "<script>
                alert('Deleted Successfully');
                window.location.href = 'index.php?p=site-master';
           </script>";
}
else if(isset($_GET['flag']) && $_GET['flag'] == 'I'){
    echo "<script>
                alert('Inserted Successfully');
                window.location.href = 'index.php?p=site-master';
        </script>";
}else if(isset($_GET['flag']) && $_GET['flag'] == 'IF'){
    echo "<script>
                alert('Failed to Insert');
                window.location.href = 'index.php?p=site-master';
        </script>";
}else if(isset($_GET['flag']) && $_GET['flag'] == 'UF'){
    echo "<script>
                alert('Failed to Update');
                window.location.href = 'index.php?p=site-master';
        </script>";
}else if(isset($_GET['flag']) && $_GET['flag'] == 'E'){
    echo "<script>
                alert('Entry Already Exist');
                window.location.href = 'index.php?p=site-master';
        </script>";
}else if(isset($_GET['flag']) && $_GET['flag'] == 'ANS'){
    echo "<script>
                alert('Action Not Specified');
                window.location.href = 'index.php?p=site-master';
        </script>";
}else if(isset($_GET['flag']) && $_GET['flag'] == 'RDM'){
    echo "<script>
                alert('Required Data is Missing');
        </script>";
        // window.location.href = 'index.php?p=site-master';
}


        $query2 = "SELECT DISTINCT ExecutiveName AS SupervisorName, Executive_Cd AS Supervisor_Cd FROM [Survey_Entry_Data].[dbo].[Executive_Master]  WHERE Designation IN ('SP','Survey Supervisor') ORDER BY SupervisorName;";
        // $dbSite=new DbOperation();
        $SupervisorNameData = $db->ExecutveQueryMultipleRowSALData($ULB, $query2, $userName, $appName, $developmentMode);
       
        $query3 = "SELECT DISTINCT ExecutiveName AS ManagerName FROM [Survey_Entry_Data].[dbo].[Executive_Master]  WHERE Designation IN ('Manager','Site Manager','General Manager','Survey Manager') ORDER BY ManagerName;";
        $ManagerNameData = $db->ExecutveQueryMultipleRowSALData($ULB, $query3, $userName, $appName, $developmentMode);
     
        $query4 = "SELECT DISTINCT ExecutiveName AS Manager2 FROM [Survey_Entry_Data].[dbo].[Executive_Master] WHERE Designation IN ('Manager','Site Manager','General Manager','Survey Manager') ORDER BY Manager2;";
        $Manager2Data = $db->ExecutveQueryMultipleRowSALData($ULB, $query4, $userName, $appName, $developmentMode);
 
        $query5 = "SELECT DISTINCT ElectionName FROM [Survey_Entry_Data].[dbo].[Election_Master] WHERE survey_flag = 1
                    ORDER BY ElectionName;";
        $ElectionNameData = $db->ExecutveQueryMultipleRowSALData($ULB, $query5, $userName, $appName, $developmentMode);
    


        if(isset($_GET['Site_Cd']) && isset($_GET['action']) && $_GET['action'] == "edit") {

            $Site_Cd = $_GET['Site_Cd'];
            $action = $_GET['action'];

            $getData = "SELECT TOP (1) ClientName, SiteName, Area, Ward_No, Ac_No, Supervisor_Cd, SupervisorName, CONVERT(Varchar,Site_Start_Date, 23) AS Site_Start_Date, 
                            CONVERT(Varchar,Site_End_Date, 23) AS Site_End_Date, ManagerName, Manager2, ElectionName, ClientNameM, MobileNo, Remark, KMLFile_Url, SiteStatus, LetterStatus, ApkStatus, AreaVisit, Bld_Listing_Status
                            FROM Site_Master WHERE Site_Cd = $Site_Cd";
            $SiteMasterDataedit = $db->ExecutveQuerySingleRowSALData($ULB, $getData, $userName, $appName, $developmentMode);
          
            if(sizeof($SiteMasterDataedit) > 0){
                $ClientName = $SiteMasterDataedit['ClientName'];
                $SiteName = $SiteMasterDataedit['SiteName'];
                $SiteNameForEdit = $SiteName;
                $Area = $SiteMasterDataedit['Area'];
                $Ward_No = $SiteMasterDataedit['Ward_No'];
                $Ac_No = $SiteMasterDataedit['Ac_No'];
                $SupervisorName =$SiteMasterDataedit['SupervisorName'];
                $ManagerName = $SiteMasterDataedit['ManagerName'];
                $Manager2 = $SiteMasterDataedit['Manager2'];
                $ElectionName = $SiteMasterDataedit['ElectionName'];
                $Site_Start_Date = $SiteMasterDataedit['Site_Start_Date'];
                $Site_End_Date = $SiteMasterDataedit['Site_End_Date'];
                $ClientNameM = $SiteMasterDataedit['ClientNameM'];
                $MobileNo = $SiteMasterDataedit['MobileNo'];
                $Remark = $SiteMasterDataedit['Remark'];
                $kmlFileUrl = $SiteMasterDataedit['KMLFile_Url'];
                $SiteStatus = $SiteMasterDataedit['SiteStatus'];
                $Supervisor_Cd = $SiteMasterDataedit['Supervisor_Cd'];

                $LetterStatus = $SiteMasterDataedit['LetterStatus'];
                $ApkStatus = $SiteMasterDataedit['ApkStatus'];
                $AreaVisit = $SiteMasterDataedit['AreaVisit'];
                $Bld_Listing_Status = $SiteMasterDataedit['Bld_Listing_Status'];
            }
        }

?>
<style>
    .form-group {
        margin-bottom: 0.1rem;
    }
</style>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Site Master - <?php if(!empty($Site_Cd) && $action == 'edit' ){ ?> Edit <?php } else if(isset($Site_Cd) && $Site_Cd != 0 && $action == 'Remove' ){ ?> Delete <?php }else{ ?> Add  <?php } ?></h4>
            </div>
        <div class="content-body">
            <div class="card-content">
                <div class="card-body">
                        <form method="post" action="action/saveSiteMasterFormData.php"  enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Client Name <?php echo $star; ?></label>
                                    <input type="text"  name="ClientName" placeholder="Enter Client Name" 
                                    class="form-control" value="<?php echo $ClientName;?>" 
                                    oninvalid="setCustomValidity('Please enter client Name !')" 
                                    onchange="try{setCustomValidity('')}catch(e){}" maxlength="100" 
									required onkeypress="return onlyCharacters(event,this)"
                                    onkeyup="convertToUpperCaseClientName(this)">
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Site Name <?php echo $star; ?></label>
                                    <input type="text"  name="SiteName" 
                                    placeholder="Enter Site Name" class="form-control" 
                                    value="<?php echo $SiteName;?>" 
                                    <?php if($action == 'edit'){ echo "readonly"; } ?>
                                    oninvalid="setCustomValidity('Please enter Site Name !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="60"  required onkeypress="return onlyUpperNum(event,this)"
                                    onkeyup="convertToUpperCaseAndNumbersOnly(this)">
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Supervisor Name<?php echo $star; ?></label>
                                    <select class="select2 form-control" data-live-search="true" name="SupervisorName" oninvalid="setCustomValidity('Please select Supervisor Name !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" required>
                                        <option value="" >Choose Supervisor Name</option>
                                        <?php 
                                        if(sizeof($SupervisorNameData)>0){
                                            foreach($SupervisorNameData AS $key => $value1){
                                                if($SupervisorName == $value1['SupervisorName']){
                                        ?>
                                                    <option selected="true" value="<?php echo $value1['SupervisorName'];?>~<?php echo $value1['Supervisor_Cd'];?>" ><?php echo $value1['SupervisorName'];?></option>
                                        <?php   }else{ ?>
                                                    <option value="<?php echo $value1['SupervisorName'];?>~<?php echo $value1['Supervisor_Cd'];?>" ><?php echo $value1['SupervisorName'];?></option>
                                        <?php   }

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Manager Name<?php echo $star; ?></label>
                                    <select class="select2 form-control" data-live-search="true" name="ManagerName" oninvalid="setCustomValidity('Please select Manager Name !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" required>
                                        <option value="" >Choose Manager Name</option>
                                        <?php 
                                        if(sizeof($ManagerNameData)>0){
                                            foreach($ManagerNameData AS $key => $value1){
                                                if($ManagerName == $value1['ManagerName']){
                                        ?>
                                                    <option selected="true" value="<?php echo $value1['ManagerName'];?>" ><?php echo $value1['ManagerName'];?></option>
                                        <?php   }else{ ?>
                                                    <option value="<?php echo $value1['ManagerName'];?>" ><?php echo $value1['ManagerName'];?></option>
                                        <?php   }

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Area <?php echo $star; ?></label>
                                    <input type="text"  name="Area" placeholder="Enter Area" class="form-control" value="<?php echo $Area;?>" oninvalid="setCustomValidity('Please enter Area !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="60"  required onkeypress="return onlyCharacters(event,this)">
                                </div>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label  ">Ward No<?php echo $star; ?></label>
                                    <input type="text"  name="Ward_No" class="form-control" placeholder="Enter Ward No" value="<?php echo $Ward_No;?>" oninvalid="setCustomValidity('Please enter Ward No !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="6"  required oninput="process(this)">
                                </div>
                                    <div class="form-group col-md-2 has-info">
                                    <label class="control-label  ">Ac No<?php echo $star; ?></label>
                                    <input type="text"  name="Ac_No" class="form-control" placeholder="Enter Ac No" value="<?php echo $Ac_No;?>" oninvalid="setCustomValidity('Please enter Ac No !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="6"  required oninput="process(this)">
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Election Name<?php echo $star; ?></label>
                                    <select class="select2 form-control" data-live-search="true" name="ElectionName" oninvalid="setCustomValidity('Please select Election Name !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" required>
                                        <option value="" >Choose Election Name</option>
                                        <?php 
                                        if(sizeof($ElectionNameData)>0){
                                            foreach($ElectionNameData AS $key => $value1){
                                                if($ElectionName == $value1['ElectionName']){
                                        ?>
                                                    <option selected="true" value="<?php echo $value1['ElectionName'];?>" ><?php echo $value1['ElectionName'];?></option>
                                        <?php   }else{ ?>
                                                    <option value="<?php echo $value1['ElectionName'];?>" ><?php echo $value1['ElectionName'];?></option>
                                        <?php   }

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Site Start Date<?php //echo $star; ?></label>
                                    <input type="date"  name="Site_Start_Date" value="<?php echo $Site_Start_Date; ?>" class="form-control" oninvalid="setCustomValidity('Please select Site Start Date !')"  onchange="try{setCustomValidity('')}catch(e){}"  max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group col-md-2 has-info">
                                    <label>Site End Date<?php //echo $star; ?></label>
                                    <input type="date"  name="Site_End_Date" class="form-control" value="<?php echo $Site_End_Date;?>" oninvalid="setCustomValidity('Please select Site End Date !')"  onchange="try{setCustomValidity('')}catch(e){}" >
                                </div>
                                
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Manager2<?php //echo $star; ?></label>
                                    <select class="select2 form-control" data-live-search="true" name="Manager2" oninvalid="setCustomValidity('Please select Manager2 Name !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="" >Choose Manager2 Name</option>
                                        <?php 
                                        if(sizeof($Manager2Data)>0){
                                            foreach($Manager2Data AS $key => $value1){
                                                if($Manager2 == $value1['Manager2']){
                                        ?>
                                                    <option selected="true" value="<?php echo $value1['Manager2'];?>" ><?php echo $value1['Manager2'];?></option>
                                        <?php   }else{ ?>
                                                    <option value="<?php echo $value1['Manager2'];?>" ><?php echo $value1['Manager2'];?></option>
                                        <?php   }

                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  "> Client Name Marathi<?php //echo $star; ?></label>
                                    <input type="text"  name="ClientNameM" placeholder="Enter Client Name Marathi" value="<?php echo $ClientNameM;?>" class="form-control" oninvalid="setCustomValidity('Please enter Client Name In Marathi !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="60" >
                                </div>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label  ">Mobile No</label>
                                    <input type="text"  name="MobileNo" placeholder="Enter Mobile No" value="<?php echo $MobileNo;?>" class="form-control" oninvalid="setCustomValidity('Please enter Mobile No !')"  onchange="try{setCustomValidity('')}catch(e){}" oninput="process(this)" maxlength="10" onkeyup="return onlymobile(event,this);">
                                    <span id="mobilealert" style="color:red;"></span> 
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">Remark<?php //echo $star; ?></label>
                                    <input type="text" name="Remark" placeholder="Enter Remark" value="<?php echo $Remark;?>" class="form-control" oninvalid="setCustomValidity('Please enter Remark !')"  onchange="try{setCustomValidity('')}catch(e){}" maxlength="60" onkeypress="return onlyCharacters(event,this)">
                                </div>
                                <div class="form-group col-md-3 has-info">
                                    <label class="control-label  ">KML File<?php //echo $star; ?></label>
                                    <input type="file" name="kmlFileUrl" value=""  class="form-control" placeholder="KML File Url" >
                                </div>

                                <?php $siteStatArray = array("Done","On Going","Hold"); ?>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Site Status</label>
                                    <select class="select2 form-control" data-live-search="true" name="SiteStatus" oninvalid="setCustomValidity('Please select Site Status !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($siteStatArray AS $siteStatArrayLoop){
                                        ?>
                                            <option 
                                            <?php if($SiteStatus == $siteStatArrayLoop){
                                                echo "selected=true";
                                            }?>
                                            value="<?php echo $siteStatArrayLoop;?>" ><?php echo $siteStatArrayLoop;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <?php $LetterStatArray = array("Done","On Going","Pending"); ?>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Letter Status</label>
                                    <select class="select2 form-control" data-live-search="true" name="LetterStatus" oninvalid="setCustomValidity('Please select Site Status !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($LetterStatArray AS $LetterStatArrayLoop){
                                        ?>
                                            <option 
                                            <?php if($LetterStatus == $LetterStatArrayLoop){
                                                echo "selected=true";
                                            }?>
                                            value="<?php echo $LetterStatArrayLoop;?>" ><?php echo $LetterStatArrayLoop;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <?php $ApkStatArray = array("Done","On Going","Pending"); ?>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Apk Status</label>
                                    <select class="select2 form-control" data-live-search="true" name="ApkStatus" oninvalid="setCustomValidity('Please select Site Status !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($ApkStatArray AS $ApkStatArrayLoop){
                                        ?>
                                            <option 
                                            <?php if($ApkStatus == $ApkStatArrayLoop){
                                                echo "selected=true";
                                            } ?>
                                            value="<?php echo $ApkStatArrayLoop;?>" ><?php echo $ApkStatArrayLoop;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <?php $AreaVisitArray = array("Done","On Going","Pending"); ?>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Area Visit</label> 
                                    <select class="select2 form-control" data-live-search="true" name="AreaVisit" oninvalid="setCustomValidity('Please select Site Status !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($AreaVisitArray AS $AreaVisitArrayLoop){
                                        ?>
                                            <option 
                                            <?php if($AreaVisit == $AreaVisitArrayLoop){
                                                echo "selected=true";
                                            }?>
                                            value="<?php echo $AreaVisitArrayLoop;?>" ><?php echo $AreaVisitArrayLoop;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <?php $BldListingArray = array("Done","On Going","Pending"); ?>
                                <div class="form-group col-md-2 has-info">
                                    <label class="control-label">Bld Listing Status</label>
                                    <select class="select2 form-control" data-live-search="true" name="BldListingStatus" oninvalid="setCustomValidity('Please select Site Status !')"  onchange="try{setCustomValidity('')}catch(e){}" data-size="8" >
                                        <option value="">--Select--</option>
                                        <?php 
                                        foreach($BldListingArray AS $BldListingArrayLoop){
                                        ?>
                                            <option 
                                            <?php if($Bld_Listing_Status == $BldListingArrayLoop){
                                                echo "selected=true";
                                            }?>
                                            value="<?php echo $BldListingArrayLoop;?>" ><?php echo $BldListingArrayLoop;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4 has-info" style="float:right;margin-top:28px;">
                                    <button type="reset" class="btn btn-primary">Clear</button>
                                    <button name="submit"  type="submit"  class="btn btn-primary">
                                        <?php 
                                            if($action == 'Insert'){
                                                echo "Add";
                                            }else if($action == 'edit'){
                                                echo "Update";
                                            }
                                        ?>
                                    </button>
                                </div>
                                <div class="col-xs-12 col-xl-12   col-12" style="margin-top:10px;margin-bottom: 10px;">
                                    
                                    <?php 
                                        if(!empty($kmlFileUrl)){
                                            
                                    ?>
                                        <div id="mapCRM" style="height: 500px;" ></div>
                                        <!-- <div id="capture"></div> -->

                                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script>
                                        <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script> -->
                                        <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script> -->
                                        
                                        <script type="text/javascript">
                                        // Google Maps
                                            function initMap() {

                                                const map = new google.maps.Map(document.getElementById("mapCRM"), {
                                                
                                                    mapTypeId: google.maps.MapTypeId.SATELLITE,
                                                    zoom: 18,
                                                });

                                                var src = '<?php echo $kmlFileUrl; ?>';
                                            
                                                var infowindow = new google.maps.InfoWindow();

                                                var kmlLayer = new google.maps.KmlLayer(src, {
                                                suppressInfoWindows: true,
                                                preserveViewport: false,
                                                map: map
                                                });
                                                kmlLayer.addListener('click', function(event) {
                                                
                                                var content = "<div>" + event.featureData.infoWindowHtml + "</div>";
                                                        infowindow.setPosition(event.latLng);
                                                        infowindow.setOptions({
                                                        pixelOffset: event.pixelOffset,
                                                        content: content
                                                        });
                                                        infowindow.open(map);
                                                });
                                            }
                                        </script>
                                    <?php    
                                        }
                                    ?>
                                </div>

                                <!-- <div class="col-xs-12 col-xl-12   col-12"> -->
                                

                                <div class="col-md-2 text-right" style="float:right;margin-top:20px">
                                    <input type="hidden" name="token" value="raelrkgj3ty465jrkhj5werfg0525SiteAdd" />
                                    <input type="hidden" name="KMLFile_Url_OLD" value="<?php echo $kmlFileUrl ; ?>" >
                                    <input type="hidden" name="action" value="<?php echo $action; ?>" />
                                    <input type="hidden" name="Site_Cd" value="<?php echo $Site_Cd; ?>" />
                                    <input type="hidden" name="SiteNameForEdit" value="<?php echo $SiteNameForEdit; ?>" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row match-height">
    <?php include 'datatbl/tblSiteMaster.php'; ?>
</div>
<script type="text/javascript">
    (function() {
        var input = document.getElementById('MobileNo');
        var pattern = /^[6-9][0-9]{0,9}$/;
        var value = input.value;
        !pattern.test(value) && (input.value = value = '');
        input.addEventListener('input', function() {
            var currentValue = this.value;
            if(currentValue && !pattern.test(currentValue)) this.value = value;
            else value = currentValue;
        });
    })();
</script>