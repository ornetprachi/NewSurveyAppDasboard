<section id="ModalSection">
<?php
/**Chnages Added By prachi */
session_start();
include 'api/includes/DbOperation.php'; 
// include_once 'includes/ajaxscript.php';  
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
// if($ServerIP == "103.14.99.154"){
//     $ServerIP =".";
// }else{
//     $ServerIP ="103.14.99.154";
// }

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(
        isset($_GET['Site']) && !empty($_GET['Site']) &&
        isset($_GET['Date']) && !empty($_GET['Date']) &&
        isset($_GET['SupervisorName']) && !empty($_GET['SupervisorName']) &&
        isset($_GET['totalexecutives']) && !empty($_GET['totalexecutives'])  &&
        isset($_GET['ElectionName']) && !empty($_GET['ElectionName']) 
    ){

    try  
        {  
            $Site = $_GET['Site'];
            $Date = $_GET['Date']; 
            $SupervisorName = $_GET['SupervisorName']; 
            $totalexecutives = $_GET['totalexecutives']; 
            $ElectionName = $_GET['ElectionName'];

            $dataSite = $db->getSiteDropDownDatabyElectionName($ULB,$userName, $appName,  $developmentMode);

            $SiteWiseQuery = "SELECT
                    ed.Executive_Cd,
                    ed.ExecutiveName,
                    CASE 
                        WHEN ed.Attendance='1' THEN 'Present'
                        WHEN ed.Attendance='2' THEN 'Absent'
                        WHEN ed.Attendance='3' THEN 'Half Day'
                        WHEN ed.Attendance='4' THEN 'Training'
                        WHEN ed.Attendance='0' THEN 'Assigned'
                    ELSE '' END as attendance
                    from [Survey_Entry_Data].[dbo].[Executive_Details] ed 
                    INNER JOIN [Survey_Entry_Data].[dbo].[Executive_Master] em on (ed.Executive_Cd=em.Executive_Cd) 
                    INNER JOIN [Site_Master] sm on (sm.SiteName=ed.SiteName) 
                    WHERE convert(varchar, ed.SurveyDate, 23) = '$Date' 
                    AND ed.ElectionName <> 'OFFICE STAFF' AND ed.SiteName = '$Site' 
                    AND sm.SupervisorName = '$SupervisorName' AND ed.ElectionName = '$ElectionName'
                    ORDER BY ed.ExecutiveName;";

            $SiteWiseData = $db->ExecutveQueryMultipleRowSALData($ULB,$SiteWiseQuery, $userName, $appName, $developmentMode);
            // print_r($SiteWiseData);

        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else{
    //echo "ddd";
  }

}
    
?>
    <center>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <div id="MODAL_VIEW" class="modal">
            <div class = "SiteData">
                <style>
                    .card-header {
                        padding: 5px;
                        margin-bottom: 0;
                        background-color: rgba(34, 41, 47, 0.03);
                        border-bottom: 1px solid rgba(34, 41, 47, 0.125);
                    }
                    .table td, .table th {
                        margin: 0;
                        padding-top: 3px;
                        padding-bottom: 3px;
                    }
                    .card-title {
                        float: left;
                        margin-bottom: 0.5rem;
                    }
                </style>
                <div class="modal-dialog  modal-xl chatapp-call-window" role="document">
                <!-- modal-dialog-centered -->
                    <div class="modal-content" style="width:50%;">
                        <div class="card-header">
                            <div class = "row">
                                <div class="col-6">
                                    <h4 class="card-title" style="text-align:left;">
                                        <?php echo "Site Name : ".$Site."<br>Supervisor : ".$SupervisorName; ?>
                                    </h4>
                                </div>
                                <div class="col-5">
                                    <h4 class="card-title" style="text-align:left;">
                                        <?php echo "Survey Date : ".date('d-m-Y', strtotime($Date))."<br>Total Executives : ".$totalexecutives; ?>
                                    </h4>
                                </div>
                                <div class="col-1">
                                    <span class="close" style="cursor:pointer;" onclick="CloseModal()">&times;</span>
                                </div>
                            </div>
                        </div>
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body card-dashboard">
                                                <div class="row" id="ShowTransfer" style="display:none;">
                                                    <!-- <div class="col-12"> -->
                                                        <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                                            <div id="msgsuccessTR" class="controls alert alert-success text-center" role="alert" style="display: none;padding:4px;"></div>
                                                            <div id="msgfailedTR" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:4px;"></div>
                                                        </div>
                                                        <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                                            <input type="hidden" value=""  class="form-control" name="SelectedExecutiveCds"/>
                                                            <input type="hidden" value="<?php echo $Date; ?>" class="form-control" name="Date"/>
                                                            <input type="hidden" value="<?php echo $Site; ?>" class="form-control" name="Site"/>
                                                            <input type="hidden" value="<?php echo $SupervisorName; ?>" class="form-control" name="SupervisorName"/>
                                                        </div>
                                                        <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                                            <div id='spinnerLoader2Modal' style='display:none;float:left;'>
                                                                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-xl-3 col-md-3 col-12" style="">
                                                            <select class="select2 form-control" style="padding:7px;width:100%;" name="SiteNameNew">
                                                                <option value="">--Select--</option>
                                                                <?php
                                                                if (sizeof($dataSite)>0) 
                                                                {
                                                                    foreach ($dataSite as $key => $value) 
                                                                    {
                                                                ?>
                                                                    <option value="<?php echo $value['Site_Cd']; ?>~<?php echo $value["SiteName"];?>"><?php echo "<b>".$value["SiteName"]."</b>" ; ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?> 
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                                            <button type="button" id="UpdateButton" style="padding:7px;margin-left:5px;" class="btn btn-danger float-right" onclick="ExecutiveRemoveFromCurrentSite()">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <button type="button" id="UpdateButton" style="padding:7px;" class="btn btn-primary float-right" onclick="ExecutiveTransferToNewSite()">
                                                                <i class="fa fa-exchange"></i>
                                                            </button>
                                                        </div>
                                                    <!-- </div> -->
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover-animation table-hover" id="SiteWiseSociety">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                <th style="background-color:#36abb9;color: white;">
                                                                    <input class="form-check-input checkbox_All" type="checkbox" style="  margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="getExecutiveCdsToTransferALL(this)" >
                                                                </th>
                                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                                <th style="background-color:#36abb9;color: white;">Attendance</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if(sizeof($SiteWiseData) > 0 ){
                                                                $srNo = 1;
                                                                foreach ($SiteWiseData as $key => $value) {
                                                                ?> 
                                                                    <tr style="padding-top:0px;">
                                                                        <td><?php echo $srNo++; ?></td>
                                                                        <td>
                                                                            <input class="form-check-input element checkboxALL" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['Executive_Cd']; ?>" id="AssignCheckbox" onclick="getExecutiveCdsToTransfer()" >
                                                                        </td>
                                                                        <td><?php echo "<b>" . $value["ExecutiveName"] . "</b>" ?></td>
                                                                        <td>
                                                                        <?php 
                                                                            if($value["attendance"] == "Absent"){
                                                                                echo "<span class='badge badge-danger'><b>".$value["attendance"]."</b></span>";
                                                                            }else if($value["attendance"] == "Present"){
                                                                                echo "<span class='badge badge-success'><b>".$value["attendance"]."</b></span>";
                                                                            }else if($value["attendance"] == "Half Day"){
                                                                                echo "<span class='badge badge-warning'><b>".$value["attendance"]."</b></span>";
                                                                            }else if($value["attendance"] == "Assigned"){
                                                                                echo "<span class='badge badge-primary'><b>".$value["attendance"]."</b></span>";
                                                                            }else if($value["attendance"] == "Training"){
                                                                                echo "<span class='badge badge-secondary'><b>".$value["attendance"]."</b></span>";
                                                                            }else{

                                                                            } 
                                                                        ?>
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
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </center>
    <script>
        $('.select2').select2();
    </script>
</section>
