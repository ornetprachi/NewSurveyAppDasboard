<div id="BuildingListingEditForm"> 
<?php
    session_start();
include 'api/includes/DbOperation.php'; 


    if(
        (isset($_GET['Society_Cd']) && !empty($_GET['Society_Cd']))
    )
    {


        $Society_Cd = $_GET['Society_Cd'];
        $election_Cd = $_GET['election_Cd'];
        $electionName = $_GET['ElectionName'];
        $Site_Cd = $_GET['Site_Cd'];
        $SiteName = $_GET['SiteName'];
        $SocietyName = $_GET['SocietyName'];
        $SocietyNameMar = $_GET['SocietyNameMar'];
        $Area = $_GET['Area'];
        $AreaMar = $_GET['AreaMar'];
        $Floor = $_GET['Floor'];
        $Rooms = $_GET['Rooms'];
        $Sector = $_GET['Sector'];
        $PlotNo = $_GET['PlotNo'];
        $Pocket_Cd = $_GET['Pocket_Cd'];
        $Latitude = $_GET['Latitude'];
        $Longitude = $_GET['Longitude'];
        $Building_Image = $_GET['Building_Image'];
        $Building_Plate_Image = $_GET['Building_Plate_Image'];
        $Remark = $_GET['Remark'];
        $Category = $_GET['Category'];

        // if($Category == '1'){
        //     $Category = ''
        // }
    }

    if(isset($_SESSION['SurveyUA_SiteString_Building_Listing']) && !empty($_SESSION['SurveyUA_SiteString_Building_Listing'])){
        $SiteString = $_SESSION['SurveyUA_SiteString_Building_Listing'];
        $SiteString = explode("~",$SiteString);
        
        $Site_Cd = $SiteString[0];
        $SiteName = $SiteString[1];
    }
?>

<style type="text/css">
    img.docimg{
        transition: 0.4s ease;
        transform-origin: 10% 30%;

    }
    img.docimg:hover{
        z-index: 9999999990909090990909;
        transform: scale(1.5); 
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
    }

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

    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }



</style>

<div class="row match-height" id="BuildingListingMapDIV">
    <div class="col-md-12">
        <div class="card"> 
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body"> 
                        <div class="row">
                            
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="controls" style="text-align:center;">
                                        <?php if($Building_Plate_Image != '') { ?>
                                            <img src="<?php echo $Building_Plate_Image; ?>" class="docimg rounded img-fluid" width="100%" height="300" alt="Building Plate Photo" title="Building Plate Photo"  name="previewImg2" id="previewImg2" <?php if($Building_Plate_Image != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                        <?php } else { ?>   
                                        <img src="app-assets/images/NoImageAvailable.jpg" height="300" width="100%"  alt="Building Plate Photo" style="border:1px solid #007D88;border-radius:10px;" />
                                        <?php } ?>
                                        <h6></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                <div class="form-group">
                                    <div class="controls" style="text-align:center;">
                                        <?php if($Building_Image != '') { ?>
                                            <img src="<?php echo $Building_Image; ?>"   class="docimg rounded img-fluid" width="100%" height="300" alt="Building Photo" title="Building Photo"  name="previewImg1"  id="previewImg1" <?php if($Building_Image != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                        <?php } else { ?>   
                                        <img src="app-assets/images/NoImageAvailable.jpg" height="300" width="100%"  alt="Building Photo"  style="border:1px solid #007D88;border-radius:10px;"/>
                                        <?php } ?>
                                        <h6></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2" >
                                <?php if($Designation != 'Data Entry Executive' || $Designation != 'DE'){ ?>
                                <button type="button" onclick="rotateimage('<?php echo $Building_Plate_Image; ?>')" class="btn btn-primary" id="rotateimage1" name="rotateimage1" value="90" style="margin-bottom: 10px;" <?php if($Designation == 'Data Entry Executive' || $Designation == 'DE'){ ?> Disabled <?php } ?>>
                                    <i class="fa-solid fa-arrows-rotate"></i> Rotate
                                </button>
                                <button type="button" onclick="DeleteBldimage('<?php echo $Society_Cd; ?>','<?php echo $SiteName; ?>','BuildingPlate')" class="btn btn-primary" id="Deleteimage1" name="Deleteimage1" value="90" style="margin-bottom: 10px;">
                                    <i class="fa-solid fa-arrows-rotate"></i> DELETE
                                </button>
                                <?php } ?>
                                <div style="text-align:center;"><label>Building Plate Image</label></div>
                                <input type="file" name="buildingPlateImg" id="buildingPlateImg" value="<?php echo $Building_Plate_Image; ?>" class="form-control">
                            </div>

                            <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2">
                                <?php if($Designation != 'Data Entry Executive' || $Designation != 'DE'){ ?>
                                    <button type="button" onclick="rotateimage('<?php echo $Building_Image; ?>')" class="btn btn-primary" id="rotateimage1" name="rotateimage1" value="90" style="margin-bottom: 10px;" <?php if($Designation == 'Data Entry Executive' || $Designation == 'DE'){ ?> Disabled <?php } ?>>
                                        <i class="fa-solid fa-arrows-rotate"></i> Rotate
                                    </button>
                                    <button type="button" onclick="DeleteBldimage('<?php echo $Society_Cd; ?>','<?php echo $SiteName; ?>','Building')" class="btn btn-primary" id="Deleteimage1" name="Deleteimage1" value="90" style="margin-bottom: 10px;">
                                        <i class="fa-solid fa-arrows-rotate"></i> DELETE
                                    </button>
                                <?php } ?>
                                <div  style="text-align:center;"><label>Building Image</label></div>
                                <input type="file" name="buildingImg" id="buildingImg" value="<?php echo $Building_Image; ?>" class="form-control" >
                            </div>

                            <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                <?php include 'Dropdown-site-building-listing-save.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12" style="margin-top:10px;">
                                <?php include 'dropdown-building-listing-pocket-save.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12" style="marign-top:-35px">
                                <label class="col-form-label">Society Name (Eng)<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="society" id="society" type="text" value="<?php echo $SocietyName; ?>" placeholder=" Enter Society Name Eng" >
                            </div>
                            <div class="col-xs-12 col-xl-6 col-md-6 col-12" style="marign-top:-35px">
                                <label class="col-form-label">Society Name (Mar)</label>
                                <input class="form-control" name="societyMar" id="societyMar" type="text" value="<?php echo $SocietyNameMar; ?>"  placeholder=" Enter Society Name Mar"  >
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Area Name (Eng)<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="area" id="area" type="text" value="<?php echo $Area; ?>" placeholder=" Enter Area Name Eng">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Area Name (Mar)</label>
                                <input class="form-control" name="areaMar" id="areaMar" type="text" value="<?php echo $AreaMar; ?>" placeholder=" Enter Area Name Mar"  >
                            </div>
                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                <label class="col-form-label">Floor<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="floor" id="floor" type="text" value="<?php echo $Floor; ?>"placeholder=" Enter Floor"  maxlength="3">
                            </div>
                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                <label class="col-form-label">Room<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="room" id="room" type="text" value="<?php echo $Rooms; ?>" placeholder=" Enter Room No"  maxlength="4">
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label class="col-form-label">Sector</label>
                                <input class="form-control" name="sector" id="sector" type="text" value="<?php echo $Sector; ?>" placeholder= "Enter Sector" maxlength="2">
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label class="col-form-label">Plot No</label>
                                <input class="form-control" name="plotNo" id="plotNo" type="text" value="<?php echo $PlotNo; ?>" placeholder="Enter Plot No" maxlength="3" >
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12" style="margin-top:12px;margin-bottom:-12px;">
                                <div class="form-group">
                                    <label>Category</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="Category" id="Category" value="<?php echo $Category; ?>" disabled>
                                            <option value="1" <?php if($Category == "1"){ ?> selected <?php } ?>>Elite</option>
                                            <option value="2" <?php if($Category == "2"){ ?> selected <?php } ?>>Medium</option>
                                            <option value="3" <?php if($Category == "3"){ ?> selected <?php } ?>>Low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                                <label class="col-form-label">Remark</label>
                                <input class="form-control" name="Remark" id="Remark" type="text" value="<?php echo $Remark; ?>" placeholder="Enter Remark">
                            </div>
                            
                                
                            <input type="hidden" name="election_CdBLSave" id="election_CdBLSave" value="<?php echo $election_Cd; ?>" >
                            <input type="hidden" name="electionNameBLSave" id="electionNameBLSave" value="<?php echo $electionName; ?>" >
                            <input type="hidden" name="SiteNameSave" id="SiteNameSave" value="<?php echo $SiteName; ?>" >
                            <input type="hidden" name="Society_CdBLSave" id="Society_CdBLSave" value="<?php echo $Society_Cd; ?>" >
                            <input type="hidden" name="newLat" id="newLat" value="<?php echo $Latitude; ?>" >
                            <input type="hidden" name="newLng" id="newLng" value="<?php echo $Longitude; ?>" >
                            <input type="hidden" name="buildingImg_OLD_URL" id="buildingImg_OLD_URL" value="<?php echo $Building_Image; ?>" >
                            <input type="hidden" name="buildingPlateImg_OLD_URL" id="buildingPlateImg_OLD_URL" value="<?php echo $Building_Plate_Image; ?>" >
                        </div>
                        <!-- <div class="row mt-2">
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 text-center">
                                    <img src="<?php //echo $Building_Image; ?>" name="previewImg1"  id="previewImg1"  class="docimg img-fluid" height="110" width="90" style="border:1px solid #007D88;border-radius:10px;"  <?php if($Building_Image != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 text-center">
                                    <img src="<?php //echo $Building_Plate_Image; ?>" name="previewImg2" id="previewImg1" class="docimg img-fluid" height="110" width="90" style="border:1px solid #007D88;border-radius:10px;"  <?php if($Building_Plate_Image != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2">
                                    <div  style="text-align:center;"><label>Building Image</label></div>
                                    <input type="file" name="buildingImg" id="buildingImg" value="<?php //echo $Building_Image; ?>" class="form-control" >
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2" >
                                <div style="text-align:center;"><label>Building Plate Image</label></div>
                                    <input type="file" name="buildingPlateImg" id="buildingPlateImg" value="<?php //echo $Building_Plate_Image; ?>" class="form-control">
                                </div>
                        </div>  -->
                        <!-- <div class="row">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="margin-top:20px;">
                                <div class="float-right">
                                    <img src="app-assets/images/lock.svg" id="toggleButton" title="Edit Map" class=""  alt="" width="30x" height="30px">
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="margin-top:10px;margin-bottom: 10px;">
                                <div id="BuildingSurveyQCMap" class="disabled" style="height: 500px;" ></div>
                                
                                <input type="hidden" name="election_Cd" id="election_Cd" value="<?php //echo $election_Cd; ?>" >
                                <input type="hidden" name="electionName" id="electionName" value="<?php //echo $electionName; ?>" >
                                <input type="hidden" name="SiteNameSave" id="SiteNameSave" value="<?php //echo $SiteName; ?>" >
                                <input type="hidden" name="Society_Cd" id="Society_Cd" value="<?php //echo $Society_Cd; ?>" >
                                <input type="hidden" name="newLat" id="newLat" value="<?php //echo $Latitude; ?>" >
                                <input type="hidden" name="newLng" id="newLng" value="<?php //echo $Longitude; ?>" >
                                <input type="hidden" name="buildingImg_OLD_URL" id="buildingImg_OLD_URL" value="<?php //echo $Building_Image; ?>" >
                                <input type="hidden" name="buildingPlateImg_OLD_URL" id="buildingPlateImg_OLD_URL" value="<?php //echo $Building_Plate_Image; ?>" >

                                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script>
                                <script type="text/javascript">
                                    function initMap() {
                                        const myLatLng = { lat: <?php //echo $Latitude; ?>, lng: <?php //echo $Longitude; ?> };
                                        const map = new google.maps.Map(document.getElementById("BuildingSurveyQCMap"), {
                                            mapTypeId: google.maps.MapTypeId.SATELLITE,
                                            zoom: 18,
                                            center: myLatLng,
                                        });

                                        const marker = new google.maps.Marker({
                                            position: myLatLng,
                                            map: map,
                                            draggable: true
                                        });

                                        // Add a listener to get the new position when the marker is dragged
                                        marker.addListener('dragend', function(event) {
                                            const newLat = event.latLng.lat();
                                            const newLng = event.latLng.lng();
                                            
                                            document.getElementById("newLat").value = newLat;
                                            document.getElementById("newLng").value = newLng;
                                        });
                                    }

                                    window.initMap = initMap;
                                </script>
                            </div>
                        </div>  -->
                        <div class="row mt-2">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                <div id="msgsuccessBS" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailedBS" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                        </div>   
                        <div class="row d-flex flex-row-reverse mt-2">
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <button type="button" class="btn btn-primary float-right" id="submitBtnId" onclick="saveBuildingListingQCData()">
                                    save
                                </button>
                            </div>  
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <button type="button" class="btn btn-danger float-right" id="submitBtnId" onclick="saveBuildingListingQCRejectedData()">
                                    Reject
                                </button>
                            </div> 
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <button type="button" class="btn btn-light float-right" id="submitBtnId" onclick="cancelBtnForBuildingListingQC()">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<?php 
if(isset($_SESSION['SurveyUA_SiteString_Building_Listing'])){
    unset($_SESSION['SurveyUA_SiteString_Building_Listing']);
}

?>
<script src="includes/ajaxscript.js"></script>

<script>

var toggleButton = document.getElementById('toggleButton');
var buildingSurveyQCMap = document.getElementById('BuildingSurveyQCMap');

toggleButton.addEventListener('click', function() {

    if (confirm("Are you sure you want to edit the marker?") == true){

        buildingSurveyQCMap.classList.remove('disabled');
        toggleButton.src = 'app-assets/images/unlock.svg'; // Update the image src to unlock.svg
        toggleButton.alt = 'Unlock Icon';
        toggleButton.classList.add('disabled');
    }

});


</script>


<!-- </div>