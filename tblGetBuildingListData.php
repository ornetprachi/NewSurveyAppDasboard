<?php
   
//    if ($_SERVER['REQUEST_METHOD'] === "GET") {

    session_start();
   include 'api/includes/DbOperation.php'; 
   $db=new DbOperation();


// if  ((isset($_GET['Executive_Cd']) && !empty($_GET['Executive_Cd']))) {
 
    $QC_Done_Flag = $_GET['QC_Done_Flag'];
    $Executive_CdReport = $_GET['Executive_Cd'];
    $Type = $_GET['Type'];
    $QC_Assign_Date = $_GET['QC_Assign_Date'];

    // }
// }

    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ExecutiveName=$_SESSION['SurveyUA_ExecutiveName'];
    $Executive_Cd=$_SESSION['SurveyUA_Executive_Cd_Login'];

   $query = "SELECT sm.ElectionName, sm.Site_Cd, sm.SiteName, sm.SocietyName, sm.Area, sm.Floor, sm.Rooms,sm.PocketName, em.ExecutiveName, convert(varchar, sm.QC_Assign_Date, 23)as QC_Assign_Date,sm.Executive_Cd
    FROM Survey_Entry_Data..Society_Master as sm
    INNER JOIN Survey_Entry_Data..Executive_Master as em on (em.Executive_Cd = sm.Executive_Cd)
    WHERE sm.ElectionName = '$electionName'  AND sm.Executive_Cd = $Executive_CdReport AND sm.QC_Done_Flag = $QC_Done_Flag AND convert(varchar, sm.QC_Assign_Date, 23) = '$QC_Assign_Date'
    GROUP BY sm.ElectionName, em.ExecutiveName, sm.QC_Assign_Date,sm.Executive_Cd,sm.Site_Cd, sm.SiteName, sm.SocietyName, sm.Area, sm.Floor, sm.Rooms,sm.PocketName";
$BuildingList = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

?>
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Building List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover" id="BuildingList">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;">Election Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Area</th>
                                                            <th style="background-color:#36abb9;color: white;">Floor</th>
                                                            <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                            <th style="background-color:#36abb9;color: white;">PocketName</th>
                                                            <th style="background-color:#36abb9;color: white;">ExecutiveName</th>
                                                            <!-- <th>Done</th>
                                                            <th>Reject</th>
                                                            <th>Pending</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($BuildingList) > 0){
                                                                $srNo = 1;
                                                                foreach($BuildingList AS $key=>$value){ 
                                                         ?>
                                                        <tr>                                                            
                                                            <td><?php echo $value['ElectionName']?></td>
                                                            <!-- <td><?php //echo $value['ElectionName'];?></td> -->
                                                            <td><?php echo $value['SiteName'];?></td>
                                                            <td><?php echo $value['SocietyName'];?></td>
                                                            <td><?php echo $value['Area'];?></td>
                                                            <td><?php echo $value['Floor'];?></td>
                                                            <td><?php echo $value['Rooms'];?></td>
                                                            <td><?php echo $value['PocketName'];?></td>
                                                            <td><?php echo $value['ExecutiveName'];?></td>
                                                            <!-- <td><?php //echo $value['Done'];?></td> -->
                                                            <!-- <td><?php //echo $value['Reject'];?></td> -->
                                                            <!-- <td><?php //echo $value['Pending'];?></td> -->
                                                        </tr>
                                                        <?php
                                                            }
                                                            }   
                                                        ?>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;">Election Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                            <th style="background-color:#36abb9;color: white;">Area</th>
                                                            <th style="background-color:#36abb9;color: white;">Floor</th>
                                                            <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                            <th style="background-color:#36abb9;color: white;">PocketName</th>
                                                            <th style="background-color:#36abb9;color: white;">ExecutiveName</th>
                                                            <!-- <th>Done</th> -->
                                                            <!-- <th>Reject</th> -->
                                                            <!-- <th>Pending</th> -->
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        
    </div>