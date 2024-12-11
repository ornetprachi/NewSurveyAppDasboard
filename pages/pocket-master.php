
<section id="dashboard-analytics">

<?php
    
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $dataElectionName = $db->getULBWiseAssemblyData($ULB, $userName, $appName, $developmentMode);
    if(isset($_SESSION['SurveyUA_AcNo_Cd'])){
        $Ac_No = $_SESSION['SurveyUA_AcNo_Cd'];
    }else{

        $Ac_No = '';
    }
   
// print_r($Ac_No);
    if(isset($_GET['flag']) && $_GET['flag'] == 'U'){
        echo "<script>
                    alert('Updated Successfully');
                    window.location.href = 'index.php?p=pocket-master';
              </script>";
    }
    else if(isset($_GET['flag']) && $_GET['flag'] == 'D'){
        echo "<script>
                    alert('Deleted Successfully');
                    window.location.href = 'index.php?p=pocket-master';
               </script>";
    }
    else if(isset($_GET['flag']) && $_GET['flag'] == 'I'){
        echo "<script>
                    alert('Inserted Successfully');
                    window.location.href = 'index.php?p=pocket-master';
            </script>";
    }
    else if(isset($_GET['flag']) && $_GET['flag'] == 'F'){
        echo "<script>
                    alert('Failed To Insert !!');
                    window.location.href = 'index.php?p=pocket-master';
            </script>";
    }
    else if(isset($_GET['flag']) && $_GET['flag'] == 'ED'){
        echo "<script>
                    alert('Pocket Already Exists but Deactive !!');
                    window.location.href = 'index.php?p=pocket-master';
            </script>";
    }
    else if(isset($_GET['flag']) && $_GET['flag'] == 'E'){
        echo "<script>
                    alert('Pocket Already Exists !!');
                    window.location.href = 'index.php?p=pocket-master';
            </script>";
    }
    $PocketCd = 0;
    $PocketName = "";
    $PocketNameMar = "";
    $Site_Cd = 0;
    $KMLFile_Url = "";
    $deActiveDate = "";
    $action = "";
    $isActive = 1;
    $Election_NameGet = '';
    $Area = "";
    $AreaMarathi = "";
    $PocketNo = "";

    if(isset($_GET['Pocket_Cd']) && $_GET['Pocket_Cd'] != 0 && isset($_GET['action']) ){
        $PocketCd = $_GET['Pocket_Cd'];
        $action = $_GET['action'];
        $query = "SELECT TOP (1) 
                        COALESCE(Pocket_Cd,0) AS Pocket_Cd,
                        COALESCE(PocketName,'') AS PocketName,
                        COALESCE(PocketNameM, '') AS PocketNameM,
                        COALESCE(Area,'') AS Area,
                        COALESCE(AreaM,'') AS AreaM,
                        COALESCE(ElectionName,'') AS ElectionName,
                        COALESCE(PocketNo, 0) AS PocketNo,
                        COALESCE(SiteName,'') AS SiteName,
                        COALESCE(Site_Cd,0) AS Site_Cd,
                        COALESCE(Ward_No,0) AS Ward_No,
                        COALESCE(IsActive,0) AS IsActive,
                        COALESCE(KMLFile_Url,'') AS KMLFile_Url,
                        COALESCE(CONVERT(VARCHAR,DeActiveDate,100),'') AS DeActiveDate,
                        COALESCE(Corporator_Cd,0) AS Corporator_Cd
                    FROM Pocket_Master
                    WHERE Pocket_Cd = $PocketCd;  ";
                
            $PocketMasterData = $db->ExecutveQuerySingleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

            if(sizeof($PocketMasterData)>0){
                $PocketCd = $PocketMasterData["Pocket_Cd"];
                $PocketName = $PocketMasterData["PocketName"];
                $PocketNameMar = $PocketMasterData["PocketNameM"];
                $Election_NameGet = $PocketMasterData["ElectionName"];
                $PocketNo = $PocketMasterData["PocketNo"];
                $Site_Cd = $PocketMasterData["Site_Cd"];
                $KMLFile_Url = $PocketMasterData["KMLFile_Url"];
                $deActiveDate = $PocketMasterData["DeActiveDate"];
                $isActive = $PocketMasterData["IsActive"];
                $Area = $PocketMasterData["Area"];
                $AreaMarathi = $PocketMasterData["AreaM"];
                $Corporator_Cd = $PocketMasterData["Corporator_Cd"];
                
                if(!empty($action) && $action == 'edit'){
                    $action = "Update";
                }else if(!empty($action) && $action == 'delete'){
                    $action = "Remove";
                    $isActive = 0;
                }
            }else{
                $action = "Insert";
                $PocketCd = 0;
            }

        }else{
        $action = "Insert";
    } 





    // $query = "SELECT 
    //                 COALESCE(Pocket_Cd,0) AS Pocket_Cd,
    //                 COALESCE(PocketName,'') AS PocketName,
    //                 COALESCE(PocketNameM, '') AS PocketNameM,
    //                 COALESCE(Area,'') AS Area,
    //                 COALESCE(AreaM,'') AS AreaM,
    //                 COALESCE(ElectionName,'') AS ElectionName,
    //                 COALESCE(PocketNo, 0) AS PocketNo,
    //                 COALESCE(SiteName,'') AS SiteName,
    //                 COALESCE(Site_Cd,0) AS Site_Cd,
    //                 COALESCE(Ward_No,0) AS Ward_No,
    //                 COALESCE(IsActive,0) AS IsActive,
    //                 COALESCE(KMLFile_Url,'') AS KMLFile_Url,
    //                 COALESCE(CONVERT(VARCHAR,DeActiveDate,100),'') AS DeActiveDate
    //             FROM Pocket_Master 
    //             WHERE ElectionName = '$electionName'
    //             AND IsActive = 1;";
    if(isset($_SESSION['SurveyUA_SiteCd'])){
        $Site_Cd = $_SESSION['SurveyUA_SiteCd'];
    }

    if($Site_Cd != 'All' && $Site_Cd != 0){
        $siteNameCondition = " AND COALESCE(pm.Site_Cd,0) = $Site_Cd ";
    }else{
        $siteNameCondition = " ";
    }

    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
    
    $queryPocketList = "SELECT
                COALESCE(pm.Pocket_Cd,0) AS Pocket_Cd,
                COALESCE(pm.PocketName,'') AS PocketName,
                COALESCE(pm.PocketNameM, '') AS PocketNameM,
                COALESCE(pm.Area,'') AS Area,
                COALESCE(pm.AreaM,'') AS AreaM,
                COALESCE(pm.ElectionName,'') AS ElectionName,
                COALESCE(pm.PocketNo, 0) AS PocketNo,
                COALESCE(pm.SiteName,'') AS SiteName,
                COALESCE(pm.Site_Cd,0) AS Site_Cd,
                COALESCE(pm.Ward_No,0) AS Ward_No,
                COALESCE(pm.IsActive,0) AS IsActive,
                COALESCE(pm.KMLFile_Url,'') AS KMLFile_Url,
                COALESCE(CONVERT(VARCHAR,pm.DeActiveDate,100),'') AS DeActiveDate,
                COALESCE(cm.Corporator_Name,'') AS Corporator_Name
            FROM $DBName..Pocket_Master  pm
            LEFT JOIN $DBName..Corporator_Master cm ON (pm.Corporator_Cd = cm.Corporator_Cd)
            WHERE pm.ElectionName = '$electionName'
            AND pm.IsActive = 1 $siteNameCondition  ";
    //    print_r($queryPocketList);
    //    die();
    $PocketMasterListData = $db->ExecutveQueryMultipleRowSALData($ULB,$queryPocketList, $userName, $appName, $developmentMode);
 
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
            <h4 class="card-title">Pocket Master - <?php if(isset($PocketCd) && $PocketCd != 0 && $action == 'Update' ){ ?> Edit <?php } else if(isset($PocketCd) && $PocketCd != 0 && $action == 'Remove' ){ ?> Delete <?php }else{ ?> Add  <?php } ?></h4>
    
            </div>
        <div class="content-body">
            <div class="card-content">
                <div class="card-body">
                    <form method="post" action="action/savePocketMasterFormData.php"  enctype="multipart/form-data">
                        <div class="row">
                        
                            <div class="col-xl-2 col-md-2 col-12">
                                <?php //include 'dropdown-electionname.php'; ?>
                                <div class="form-group">
                                    <label>AcNo</label>
                                    <div class="controls">
                                        <select class="select2 form-control" <?php if($action == 'Update'){ echo "disabled"; } ?> name="AcNo" onchange="setAcNoInSession(this.value)" >
                                        <option selected="true" value="">Select</option>
                                            <?php
                                            if (sizeof($dataElectionName)>0) 
                                            {
                                                foreach ($dataElectionName as $key) 
                                                {
                                                    $acNos = json_decode($key['Ac_Nos'], true);
                                                    if(is_array($acNos))
                                                    {
                                                        foreach ($acNos as $key => $value) {
                                                            if($Ac_No == $value){
                                            ?>
                                                    <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                            <?php
                                                        }else{
                                            ?>
                                                    <option  value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                            <?php
                                                            }
                                                        }
                                                    }
                                            ?>
                                            <?php
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-2 col-12">
                            
                                <?php 
                                if(isset($_SESSION['SurveyUA_AcNo_Cd'])){
                                    $Ac_No = $_SESSION['SurveyUA_AcNo_Cd'];
                                //  if($electionName == 'PANVEL'){
                                //     $electionName= 'PT188';
                                // }else{
                                //     $electionName=$_SESSION['SurveyUA_ElectionName'];
                                // }
                                    $querySite = "SELECT 
                                    COALESCE(Site_Cd,0) AS Site_Cd, 
                                    COALESCE(ClientName,'') AS ClientName,
                                    COALESCE(SiteName,'') AS SiteName,
                                    COALESCE(Area, '') AS Area,
                                    COALESCE(Ward_No,0) AS Ward_No,
                                    COALESCE(Address,'') AS Address,
                                    COALESCE(ElectionName,'') AS ElectionName
                                    FROM Site_Master WHERE ElectionName = '$electionName' AND Ac_No = $Ac_No";
                                    $dbSite=new DbOperation();
                                    $dataSite = $dbSite->ExecutveQueryMultipleRowSALData($ULB,$querySite, $userName, $appName, $developmentMode);

                                }else{
                                    $dataSite = array(); 
                                }
                                ?>
                                <div class="form-group">
                                    <label>Site</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="siteName" onchange="setSiteInSession(this.value)">
                                            <option value="">--Select--</option>
                                            <?php
                                                if (sizeof($dataSite)>0) 
                                                {
                                                    foreach ($dataSite as $key => $value) 
                                                    {
                                                        if($Site_Cd == $value["Site_Cd"])
                                                        {
                                            ?>
                                                            <option selected="true" value="<?php echo $value['Site_Cd']; ?>"><?php echo $value["SiteName"]; ?></option>
                                            <?php
                                                        }
                                                        else
                                                        {
                                            ?>
                                                            <option value="<?php echo $value["Site_Cd"];?>"><?php echo $value["SiteName"];?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Pocket Name <b style='color:red;'>*</b></label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketName" value="<?php echo $PocketName; ?>" 
                                        class="form-control" placeholder="Pocket Name" required 
                                        oninput="this.value=this.value.replace(/[^a-zA-Z0-9()\s]/gi,'')"
                                        <?php if($action == 'Update'){ echo "readonly"; } ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Pocket Name in Marathi <b style='color:red;'>*</b></label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketNameMar" value="<?php echo $PocketNameMar; ?>"  class="form-control" placeholder="Pocket Name in Marathi" required
                                        oninput="validateMarathiTextPocketName(this)"
                                        <?php if($action == 'Update'){ echo "readonly"; } ?>>
                                        <div style="color:red;font-weight:bold;" id="validationMessage"></div>
                                    </div>
                                </div>
                            </div>

                            
                            
                            <div class="col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Pocket No</label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketNo" value="<?php echo $PocketNo;?>" 
                                        class="form-control" placeholder="Enter Pocket No" 
                                        <?php if($action == 'Update'){ echo "readonly"; } ?>>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-10 col-md-10 col-12">
                                <div class="form-group">
                                    <label>KML File Url <b style='color:red;'>*</b></label>
                                    <div class="controls"> 
                                        <input type="file" name="KMLFile_Url" value="<?php echo $KMLFile_Url; ?>"  class="form-control" placeholder="KML File Url" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="margin-top:10px;margin-bottom: 10px;">
                                
                                <?php 
                                    // if(!empty($KMLFile_Url)){
                                            
                                        if(sizeof($PocketMasterListData)>0){ 
                                ?>
                                    <div id="mapSurveyUtilitySurvey" style="height: 500px;" ></div>
                                    <!-- <div id="capture"></div> -->

                                    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script> -->
                                    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script>
                                    <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script> -->
                                    
                                    <script type="text/javascript">
                                     // Google Maps
                                        function initMap() {

                                            const map = new google.maps.Map(document.getElementById("mapSurveyUtilitySurvey"), {
                                             
                                                mapTypeId: google.maps.MapTypeId.SATELLITE,
                                                zoom: 18,
                                            });

                                            // var src = '<?php //echo $KMLFile_Url; ?>';
                                        
                                            // var infowindow = new google.maps.InfoWindow();

                                            // var kmlLayer = new google.maps.KmlLayer(src, {
                                            //   suppressInfoWindows: true,
                                            //   preserveViewport: false,
                                            //   map: map
                                            // });
                                            // kmlLayer.addListener('click', function(event) {
                                            //   // var content = event.featureData.infoWindowHtml;
                                            //   // var testimonial = document.getElementById('capture');
                                            //   // testimonial.innerHTML = content;
                                            //   var content = "<div>" + event.featureData.infoWindowHtml + "</div>";
                                            //         infowindow.setPosition(event.latLng);
                                            //         infowindow.setOptions({
                                            //           pixelOffset: event.pixelOffset,
                                            //           content: content
                                            //         });
                                            //         infowindow.open(map);
                                            // });
                                            
                                            function addKMLMarkerWithTimeout(src, timeout){
                                                    var kmlLayer = new google.maps.KmlLayer(src, {
                                                    suppressInfoWindows: true,
                                                    preserveViewport: false,
                                                    map: map
                                                    });

                                                    google.maps.event.addListener(kmlLayer, 'click', function(event) {
                                                    var content = "<div>" + event.featureData.infoWindowHtml + "</div>";
                                                    infowindowKML.setPosition(event.latLng);
                                                    infowindowKML.setOptions({
                                                        pixelOffset: event.pixelOffset,
                                                        content: content
                                                    });
                                                    infowindowKML.open(map);
                                                    });
                                                }

                                            <?php 
                                            if($action == "Update"){
                                                  if(!empty($KMLFile_Url) > 0){
                                                    $srNoKML = 0;
                                                    // foreach ($PocketMasterListData as $key => $value){
                                                        $srNoKML = $srNoKML+1;
                                                        if(filter_var($KMLFile_Url, FILTER_VALIDATE_URL)){
                                                ?>
                                                        addKMLMarkerWithTimeout('<?php echo $KMLFile_Url; ?>', '<?php echo ($srNoKML*300); ?>');
                                                <?php
                                                        }
                                                    // }
                                                    }
                                                ?>
                                            <?php 
                                            }else{
                                                if(sizeof($PocketMasterListData) > 0){ 

                                                        if(sizeof($PocketMasterListData) > 0){
                                                            $srNoKML = 0;
                                                            foreach ($PocketMasterListData as $key => $value){
                                                                $srNoKML = $srNoKML+1;
                                                                if(filter_var($value["KMLFile_Url"], FILTER_VALIDATE_URL)){
                                                    ?>
                                                                addKMLMarkerWithTimeout('<?php echo $value["KMLFile_Url"]; ?>', '<?php echo ($srNoKML*300); ?>');
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                    ?>

                                            <?php 
                                                } 
                                            }
                                            ?>
                                            

                                        }

                                    </script>

                                <?php    
                                    }
                                ?>
                            </div>

                            <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Area <b style='color:red;'>*</b></label>
                                    <div class="controls"> 
                                        <input type="text" name="Area" value="<?php echo $Area; ?>"  class="form-control" required placeholder="Area Name " 
                                        oninput="this.value=this.value.replace(/[^a-zA-Z0-9()\s]/gi,'')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Area in Marathi <b style='color:red;'>*</b></label>
                                    <div class="controls"> 
                                        <input type="text" name="AreaNameMarathi" value="<?php echo $AreaMarathi; ?>" required class="form-control" placeholder="Area Name in Marathi" 
                                        oninput="validateMarathiTextAreaName(this)">
                                        <div style="color:red;font-weight:bold;" id="validationMessageArea"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3 col-12" style="display:none;">
                                <div class="form-group">
                                    <label>DeActive Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="deActiveDate" value="<?php echo $deActiveDate; ?>"  class="form-control" placeholder="DeActive Date" >
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Is Pocket Active</label>
                                    <div class="controls"> 
                                    <select class="select2 form-control" name="isActive" <?php if(isset($PocketCd) && $PocketCd != 0 && $action == 'Remove' ){ ?> disabled <?php }  ?> >
                                        <option value="">--Select--</option>   
                                        <option <?php echo $isActive == '1' ? 'selected=true' : '';?>  value="1">Yes</option>
                                        <option <?php echo $isActive == '0' ? 'selected=true' : '';?>  value="0">No</option>
                                    </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Corporator Name DropDown -->
                            <div class="col-xl-2 col-md-2 col-12">
                                <?php
                                    $dbCorp=new DbOperation();
                                    $CorporatorDDData  =array();
                                    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
                                    $CorporatorDDQuery = "SELECT Corporator_Cd,Corporator_Name FROM $DBName..Corporator_Master";
                                    $CorporatorDDData = $dbCorp->ExecutveQueryMultipleRowSALData($ULB,$CorporatorDDQuery, $userName, $appName, $developmentMode);
                                ?>
                                <div class="form-group">
                                    <label>Corporator Name</label>
                                     <!-- <b style="color:red;">*</b> -->
                                    <div class="controls">
                                        <select class="select2 form-control"  name="CorporatorCd">
                                            <option value="">--Select--</option>
                                            <?php
                                                if (sizeof($CorporatorDDData)>0) 
                                                {
                                                    foreach ($CorporatorDDData as $key => $value) 
                                                    {
                                                        if($Corporator_Cd == $value["Corporator_Cd"])
                                                        {
                                            ?>
                                                            <option selected="true" value="<?php echo $value['Corporator_Cd']; ?>"><?php echo $value["Corporator_Name"]; ?></option>
                                            <?php
                                                        }
                                                        else
                                                        {
                                            ?>
                                                            <option value="<?php echo $value["Corporator_Cd"];?>"><?php echo $value["Corporator_Name"];?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Corporator Name DropDown -->


                            
                       
                       
                       <?php if(!empty($deActiveDate)){  ?>
                             <div class="col-xs-12 col-md-6 col-xl-6 text-right"> 
                       <?php }else{ ?>
                            <div class="col-xs-12 col-md-12 col-xl-12 text-right">
                       <?php }  ?>
                        
                        
                                <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="controls text-right">


                                    <input type="hidden" name="Pocket_Cd" value="<?php echo $PocketCd; ?>" >
                                    <input type="hidden" name="KMLFile_Url_OLD" value="<?php echo $KMLFile_Url; ?>" >
                                    <input type="hidden" name="action" value="<?php echo $action; ?>" >
                                    <div id="submitmsgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                    <div id="submitmsgfailed"  class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>

                                    <!-- onclick="submitPocketMasterFormData()" -->
                                    <button id="submitPocketMasterBtnId" type="submit" class="btn btn-primary"  >
                                
                                    <?php if(isset($PocketCd) && $PocketCd != 0 && $action == 'Update' ){ ?> Edit Pocket<?php } else if(isset($PocketCd) && $PocketCd != 0 && $action == 'Remove' ){ ?> Delete Pocket<?php }else{ ?> Add Pocket <?php } ?>

                                    </button>
                                </div>
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
    <?php include 'datatbl/tblPocketMaster.php'; ?>
</div>


</section>
<script>
    function setAcNoInSession(acno) {
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
                location.reload(true);
            }
        }
    
    if (acno === '') {
        alert("Please Select acno!");
    } else {
        var queryString = "?assembly="+acno;
        ajaxRequest.open("POST", "setAcNoInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
</script>