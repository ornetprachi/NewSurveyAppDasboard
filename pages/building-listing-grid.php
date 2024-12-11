<section id="dashboard-analytics">

<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$SiteName = '';
if(isset($_GET['SiteName']) && !empty($_GET['SiteName'])){
    $SiteName = $_GET['SiteName'];
}

$fromDate = '2023-05-25';
$toDate = date('Y-m-d');

$sql2 = "SELECT
        COALESCE(sm.Society_Cd,0) AS Society_Cd ,
        COALESCE(sm.Site_Cd, 0) AS Site_Cd 
        ,COALESCE(sm.SiteName, '') AS SiteName 
        ,COALESCE(sm.SocietyName, '') AS SocietyName 
        ,COALESCE(sm.ElectionName, '') AS ElectionName 
        ,COALESCE(sm.SocietyNameM, '') AS SocietyNameMar 
        ,COALESCE(sm.Area, '') AS Area 
        ,COALESCE(sm.AreaM, '') AS AreaMar 
        ,COALESCE(sm.Floor, '') AS Floor 
        ,COALESCE(sm.NewRooms, 0) AS Rooms 
        ,COALESCE(pm.PocketName, '') AS PocketName 
        ,COALESCE(sm.Pocket_Cd, 0) AS Pocket_Cd  
        ,COALESCE(sm.Building_Image, '') AS Building_Image 
        ,COALESCE(sm.Building_Plate_Image, '') AS Building_Plate_Image 
        ,COALESCE(sm.Latitude, '') AS Latitude 
        ,COALESCE(sm.Longitude, '') AS Longitude 
        ,COALESCE(sm.Sector, '') AS Sector 
        ,COALESCE(sm.Plot_No, '') AS PlotNo 
        ,COALESCE(sm.Category, '') AS BuildingCategory 
        ,COALESCE(CONVERT(VARCHAR,sm.added_date,22), '') AS BList_UpdatedDate 
        ,COALESCE(em.ExecutiveName , '') AS ExecutiveName 
        FROM Society_Master sm 
        INNER JOIN Survey_Entry_Data..Pocket_Master pm on (sm.Pocket_Cd = pm.Pocket_Cd)
        INNER JOIN Survey_Entry_Data..Executive_Master em ON (em.Executive_Cd = sm.added_by)
        WHERE sm.SiteName = '$SiteName';";


$CountListMain = $db->ExecutveQueryMultipleRowSALData($ULB, $sql2, $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>



<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #e6f4f4;
}
.img-fluid {
    max-width: 100%;
    height: 200px;
}
</style>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Total Count - <?php echo sizeof($CountListMain);?>
                </h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  table-hover-animation table-hover" id="BuildingListingQCListGRID">
                            <tbody>
                                <?php
                                    if(sizeof($CountListMain)>0){
                                        foreach($CountListMain AS $key => $value){
                                ?>   
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-xl-6 col-md-5">
                                                            <div class="row">
                                                                <?php if($value['Building_Image'] != '') { ?>
                                                                <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls" style="text-align:center;">
                                                                            <?php if($value['Building_Image'] != '') { ?>
                                                                                <img src="<?php echo $value['Building_Image']; ?>"   class="docimg rounded img-fluid" height="50px" alt="Building Photo" title="Building Photo"  name="previewImg1"  id="previewImg1" <?php if($value['Building_Image'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                                                                <!-- <label for="">Building Image</label> -->
                                                                            <?php } else { ?>   
                                                                            <img src="app-assets/images/NoImageAvailable.jpg" height="50px"   alt="Building Photo"  style="border:1px solid #007D88;border-radius:10px;"/>
                                                                            <!-- <label for="">Building Image</label> -->
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <?php if($value['Building_Plate_Image'] != '') { ?>
                                                                <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                                                    <div class="form-group">
                                                                        <div class="controls" style="text-align:center;">
                                                                            <?php if($value['Building_Plate_Image'] != '') { ?>
                                                                                <img src="<?php echo $value['Building_Plate_Image']; ?>" class="docimg rounded img-fluid" height="50px" alt="Building Plate Photo" title="Building Plate Photo"  name="previewImg2" id="previewImg1" <?php if($value['Building_Plate_Image'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                                                                <!-- <label for="">Name Plate Image</label> -->
                                                                            <?php } else { ?>   
                                                                            <img src="app-assets/images/NoImageAvailable.jpg" height="50px"   alt="Building Plate Photo" style="border:1px solid #007D88;border-radius:10px;" />
                                                                            <!-- <label for="">Name Plate Image</label> -->
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-xl-6 col-md-5">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-xl-11 col-md-5">
                                                                    <h5 class="mb-1 mt-1 pb-0"> <?php echo "<b>".$value["SocietyName"]."</b>"; ?></h5>
                                                                    <h6 class="mb-1 "> <?php echo "<b>".$value["SocietyNameMar"]."</b>"; ?></h6>
                                                                    <h6><?php echo "<b>Floors</b> : ".$value["Floor"]; ?></h6>
                                                                    <h6><?php echo "<b>Rooms</b> : ".$value["Rooms"]; ?></h6>
                                                                    <h6><?php echo "<b>Area</b> : ".$value["Area"] . " - " . $value["AreaMar"] ?></h6>
                                                                    <h6><?php echo "<b>Pocket</b> : ".$value["PocketName"]; ?></h6>
                                                                    <h6><?php echo "<b>Survey Date</b> : ". date('d/m/Y', strtotime($value["BList_UpdatedDate"])) . "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;By</b> : " .$value['ExecutiveName']; ?></h6>
                                                                </div>
                                                                <div class="col-xs-12 col-xl-1 col-md-5 mt-1">
                                                                    <a title="Map View"  target="_blank" href="<?php echo 'https://www.google.com/maps/search/?api=1&query='.$value['Latitude'].','.$value['Longitude'].'' ; ?>">
                                                                        <i class="feather icon-map-pin" style="font-size: 1.5rem;color:#BBD001;"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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

    
<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>

</section>