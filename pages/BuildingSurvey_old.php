<div id="BuildingListingEditForm"> 
<?php
    if(
        (isset($_SESSION['Society_Cd_BL']) && !empty($_SESSION['Society_Cd_BL']))
    )
    {
        $Society_Cd = $_SESSION['Society_Cd_BL'];
        $election_Cd = $_SESSION['election_Cd_BL'];
        $electionName = $_SESSION['ElectionName_BL'];
        $Site_Cd = $_SESSION['Site_Cd_BL'];
        $SiteName = $_SESSION['SiteName_BL'];
        $SocietyName = $_SESSION['SocietyName_BL'];
        $SocietyNameMar = $_SESSION['SocietyNameMar_BL'];
        $Area = $_SESSION['Area_BL'];
        $AreaMar = $_SESSION['AreaMar_BL'];
        $Floor = $_SESSION['Floor_BL'];
        $Rooms = $_SESSION['Rooms_BL'];
        $Sector = $_SESSION['Sector_BL'];
        $PlotNo = $_SESSION['PlotNo_BL'];
        $Pocket_Cd = $_SESSION['Pocket_Cd_BL'];
        $Latitude = $_SESSION['Latitude_BL'];
        $Longitude = $_SESSION['Longitude_BL'];
        $Building_Image = $_SESSION['Building_Image_BL'];
        $Building_Plate_Image = $_SESSION['Building_Plate_Image_BL'];

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
</style>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card"> 
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body"> 
                       
                        <div class="row mt-2">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'Dropdown-site-building-listing-save.php'; ?>
                            </div>
                            <!-- <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Site Name </label>
                                <input class="form-control" name="siteName" id="siteName" type="text" value="<?php //echo $Site_Cd; ?>" placeholder=" Enter Site Name" oninput="this.value=this.value.replace(/[^a-zA-Z]/gi,'')" >
                            </div> -->
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Society Name (Eng)<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="society" id="society" type="text" value="<?php echo $SocietyName; ?>" placeholder=" Enter Society Name Eng" oninput="this.value=this.value.replace(/[^a-zA-Z0-9\s]/gi,'')" >
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Society Name (Mar)</label>
                                <input class="form-control" name="societyMar" id="societyMar" type="text" value="<?php echo $SocietyNameMar; ?>"  placeholder=" Enter Society Name Mar"  >
                                <!-- oninput="this.value=this.value.replace(/[^ऀ-ॿ\s]/g, '')" -->
                            </div>

                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Area Name (Eng)<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="area" id="area" type="text" value="<?php echo $Area; ?>" placeholder=" Enter Area Name Eng">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Area Name (Mar)</label>
                                <input class="form-control" name="areaMar" id="areaMar" type="text" value="<?php echo $AreaMar; ?>" placeholder=" Enter Area Name Mar"  >
                                <!-- oninput="this.value=this.value.replace(/[^ऀ-ॿ\s]/g, '')" -->
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Floor<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="floor" id="floor" type="text" value="<?php echo $Floor; ?>"placeholder=" Enter Floor"  maxlength="3">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Room<span area-hidden="true" style="color:red;">*</span></label>
                                <input class="form-control" name="room" id="room" type="text" value="<?php echo $Rooms; ?>" placeholder=" Enter Room No"  maxlength="4">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Sector</label>
                                <input class="form-control" name="sector" id="sector" type="text" value="<?php echo $Sector; ?>" placeholder= "Enter Sector" maxlength="2">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label class="col-form-label">Plot No</label>
                                <input class="form-control" name="plotNo" id="plotNo" type="text" value="<?php echo $PlotNo; ?>" placeholder="Enter Plot No" maxlength="3" >
                            </div>
                            
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12" style="margin-top:10px;">
                                <?php include 'dropdown-building-listing-pocket-save.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <!-- <label class="col-form-label">Address</label>
                                <input class="form-control" name="address" id="address" type="text"  placeholder=" Enter Address"  oninput="this.value=this.value.replace(/[^a-zA-Z]/gi,'')"> -->
                            </div>
                            <!-- <div class="row"> -->
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 text-center">
                                    <img src="<?php echo $Building_Image; ?>" name="previewImg1"  id="previewImg1"  class="docimg img-fluid" height="110" width="90" style="border:1px solid #007D88;border-radius:10px;"/>
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 text-center">
                                    <img src="<?php echo $Building_Plate_Image; ?>" name="previewImg2" id="previewImg1" class="docimg img-fluid" height="110" width="90" style="border:1px solid #007D88;border-radius:10px;"/>
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2">
                                    <div  style="text-align:center;"><label>Building Image</label></div>
                                    <input type="file" name="buildingImg" id="buildingImg" value="<?php echo $Building_Image; ?>" class="form-control" >
                                </div>
                                <div class="col-xs-12 col-xl-6 col-md-6 col-12 mt-2" >
                                <div  style="text-align:center;"><label>Building Plate Image</label></div>
                                    <input type="file" name="buildingPlateImg" id="buildingPlateImg" value="<?php echo $Building_Plate_Image; ?>" class="form-control">
                                </div>
                           <!-- </div>  -->
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="margin-top:20px;margin-bottom: 10px;">

                                <div id="BuildingSurveyQCMap" style="height: 500px;" ></div>
                                
                                <input type="hidden" name="election_Cd" id="election_Cd" value="<?php echo $election_Cd; ?>" >
                                <input type="hidden" name="electionName" id="electionName" value="<?php echo $electionName; ?>" >
                                <input type="hidden" name="SiteNameSave" id="SiteNameSave" value="<?php echo $SiteName; ?>" >
                                <input type="hidden" name="Society_Cd" id="Society_Cd" value="<?php echo $Society_Cd; ?>" >
                                <input type="hidden" name="newLat" id="newLat" value="<?php echo $Latitude; ?>" >
                                <input type="hidden" name="newLng" id="newLng" value="<?php echo $Longitude; ?>" >
                                <input type="hidden" name="buildingImg_OLD_URL" id="buildingImg_OLD_URL" value="<?php echo $Building_Image; ?>" >
                                <input type="hidden" name="buildingPlateImg_OLD_URL" id="buildingPlateImg_OLD_URL" value="<?php echo $Building_Plate_Image; ?>" >

                                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script>
                                <script type="text/javascript">
                                    function initMap() {
                                    const myLatLng = { lat: <?php echo $Latitude; ?>, lng: <?php echo $Longitude; ?> };
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
                        </div> 
                        <div class="row">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                        </div>   
                        <div class="row d-flex flex-row-reverse mt-2">
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <button type="button" class="btn btn-primary float-right" id="submitBtnId" onclick="saveBuildingListingQCData()">
                                    save
                                </button>
                            </div> 
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <!-- <a href="" onclick="cancelBtnForBuildingListingQC()"> -->
                                    <button type="button" class="btn btn-danger float-right" id="submitBtnId" onclick="cancelBtnForBuildingListingQC()">
                                        Cancel
                                    </button>
                                </a>
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


<!-- </div>