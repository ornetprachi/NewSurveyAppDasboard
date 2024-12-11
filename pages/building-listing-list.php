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
        ,COALESCE(ssm.SecretaryName, '') AS SecretaryName 
        ,COALESCE(ssm.SecretaryMobileNo, '') AS SecretaryMobileNo 
        ,COALESCE(ssm.ChairmanName, '') AS ChairmanName 
        ,COALESCE(ssm.ChairmanMobileNo, '') AS ChairmanMobileNo 
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
		INNER JOIN Survey_Entry_Data..Society_Master as ssm on (sm.Society_Cd = ssm.Society_Cd)
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
</style>




<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom:-10px">
                <h4 class="card-title">
                    Total Count - <?php echo sizeof($CountListMain);?>
                </h4>
                <?php if($ExcelExportButton == "show"){ ?>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ClientListView')">Excel</button>
                <?php } ?>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover-animation table-striped table-hover" id="ClientListView">
                            <thead>
                                <tr>
                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                    <th style="background-color:#36abb9;color: white;">Society(ENG)</th>
                                    <th style="background-color:#36abb9;color: white;">Society(Mar)</th>
                                    <th style="background-color:#36abb9;color: white;">Secretary</th>
                                    <th style="background-color:#36abb9;color: white;">Secretary Mobile</th>
                                    <th style="background-color:#36abb9;color: white;">Chairman</th>
                                    <th style="background-color:#36abb9;color: white;">Chairman Mobile</th>
                                    <th style="background-color:#36abb9;color: white;">Floors</th>
                                    <th style="background-color:#36abb9;color: white;">Rooms</th>
                                    <th style="background-color:#36abb9;color: white;">Area(ENG)</th>
                                    <th style="background-color:#36abb9;color: white;">Area(MAR)</th>
                                    <th style="background-color:#36abb9;color: white;">Category</th>
                                    <th style="background-color:#36abb9;color: white;">Pocket</th>
                                    <th style="background-color:#36abb9;color: white;">Survey Date</th>
                                    <th style="background-color:#36abb9;color: white;">Survey By</th>
                                    <th style="background-color:#36abb9;color: white;">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(sizeof($CountListMain) > 0){
                                        $srNo = 1;
                                        foreach($CountListMain AS $Key=>$value){  
                                        ?>
                                        <tr>
                                            <td><?php echo $srNo++ ;?></td>
                                            <td><?php echo $value['SocietyName']?></td>
                                            <td><?php echo $value['SocietyNameMar']?></td>
                                            <td title="<?php echo $value['SecretaryMobileNo']; ?>"><?php echo $value['SecretaryName']?></td>
                                            <td><?php echo $value['SecretaryMobileNo']?></td>
                                            <td title="<?php echo $value['ChairmanMobileNo']; ?>"><?php echo $value['ChairmanName']?></td>
                                            <td><?php echo $value['ChairmanMobileNo']?></td>
                                            <td><?php echo $value['Floor']?></td>
                                            <td><?php echo $value['Rooms']?></td>
                                            
                                            <td><?php echo $value['Area']?></td>
                                            <td><?php echo $value['AreaMar']?></td>
                                            <td title=<?php if($value['BuildingCategory'] == 1){echo "Elite";}elseif($value['BuildingCategory'] == 2){echo "Medium";}elseif($value['BuildingCategory'] == 3){echo "Low";}else{ echo "";} ?>><?php if($value['BuildingCategory'] == 1){ echo "A";
                                                    }elseif($value['BuildingCategory'] == 2){echo "B";
                                                    }elseif($value['BuildingCategory'] == 3){echo "C";
                                                    }else{ echo "";}?></td>
                                            <td><?php echo $value['PocketName']?></td>
                                            <td><?php echo $value['BList_UpdatedDate']?></td>
                                            <td><?php echo $value['ExecutiveName']?></td>
                                            <td>
                                                <a title="Map View"  target="_blank" href="<?php echo 'https://www.google.com/maps/search/?api=1&query='.$value['Latitude'].','.$value['Longitude'].'' ; ?>">
                                                    <i class="feather icon-map-pin" style="font-size: 1.5rem;color:#BBD001;"></i>
                                                </a>
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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type,TableID) {
        var fn = "";
        var dl = "";
        var elt = document.getElementById(TableID);
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || (TableID+'.'+ (type || 'xlsx')));
    }
</script>