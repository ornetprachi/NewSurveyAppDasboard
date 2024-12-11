
<section id="dashboard-analytics">

<?php
    
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $dataElectionName = $db->getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode);
        
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
                                    <label>Corporation</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="electionName" onChange="setElectionNameInSession(this.value)" >
                                            <?php
                                            if (sizeof($dataElectionName)>0) 
                                            {
                                                foreach ($dataElectionName as $key => $value) 
                                                {
                                                    if($_SESSION['SurveyUA_Election_Cd'] == $value["Election_Cd"])
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


                            <div class="col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Pocket No</label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketNo" value="<?php echo $PocketNo;?>"  class="form-control" placeholder="Enter Pocket No" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Pocket Name *</label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketName" value="<?php echo $PocketName; ?>"  class="form-control" placeholder="Pocket Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Pocket Name in Marathi *</label>
                                    <div class="controls"> 
                                        <input type="text" name="PocketNameMar" value="<?php echo $PocketNameMar; ?>"  class="form-control" placeholder="Pocket Name in Marathi" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-2 col-md-2 col-12">
                            
                                <?php
                                    $querySite = "SELECT 
                                    COALESCE(Site_Cd,0) AS Site_Cd, 
                                    COALESCE(ClientName,'') AS ClientName,
                                    COALESCE(SiteName,'') AS SiteName,
                                    COALESCE(Area, '') AS Area,
                                    COALESCE(Ward_No,0) AS Ward_No,
                                    COALESCE(Address,'') AS Address,
                                    COALESCE(ElectionName,'') AS ElectionName
                                    FROM Site_Master WHERE ElectionName = '$electionName' ";
                                    $dbSite=new DbOperation();
                                    $dataSite = $dbSite->ExecutveQueryMultipleRowSALData($ULB,$querySite, $userName, $appName, $developmentMode);

                                ?>
                                <div class="form-group">
                                    <label>Site</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="siteName">
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


                            <div class="col-xl-10 col-md-10 col-12">
                                <div class="form-group">
                                    <label>KML File Url </label>
                                    <div class="controls"> 
                                        <input type="file" name="KMLFile_Url" value="<?php echo $KMLFile_Url; ?>"  class="form-control" placeholder="KML File Url" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="margin-top:10px;margin-bottom: 10px;">
                                
                                <?php 
                                    if(!empty($KMLFile_Url)){
                                        
                                ?>
                                    <div id="mapSurveyUtilitySurvey" style="height: 500px;" ></div>
                                    <!-- <div id="capture"></div> -->

                                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script>
                                    <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script> -->
                                    <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap&v=weekly"  ></script> -->
                                    
                                    <script type="text/javascript">
                                     // Google Maps
                                        function initMap() {

                                            const map = new google.maps.Map(document.getElementById("mapSurveyUtilitySurvey"), {
                                             
                                                mapTypeId: google.maps.MapTypeId.SATELLITE,
                                                zoom: 18,
                                            });

                                            var src = '<?php echo $KMLFile_Url; ?>';
                                        
                                            var infowindow = new google.maps.InfoWindow();

                                            var kmlLayer = new google.maps.KmlLayer(src, {
                                              suppressInfoWindows: true,
                                              preserveViewport: false,
                                              map: map
                                            });
                                            kmlLayer.addListener('click', function(event) {
                                              // var content = event.featureData.infoWindowHtml;
                                              // var testimonial = document.getElementById('capture');
                                              // testimonial.innerHTML = content;
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

                            <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Area *</label>
                                    <div class="controls"> 
                                        <input type="text" name="Area" value="<?php echo $Area; ?>"  class="form-control" required placeholder="Area Name " >
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Area in Marathi *</label>
                                    <div class="controls"> 
                                        <input type="text" name="AreaNameMarathi" value="<?php echo $AreaMarathi; ?>" required class="form-control" placeholder="Area Name in Marathi" >
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
                                    <label>Corporator Name <b style="color:red;">*</b></label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="CorporatorCd" required>
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
