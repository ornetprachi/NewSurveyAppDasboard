<?php
session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Executive_Cd']) && !empty($_GET['Executive_Cd'])) {
    
    try  
        {  
            $_SESSION['Attendence_EnquiryCd']  = $_GET['Executive_Cd'];
            $Day  = $_GET['Day'];
         //  $_SESSION['Attendence_Doc_No']  = $_GET['Doc_No'];
            
            
           $Executive_Cd = $_SESSION['Attendence_EnquiryCd'];
         // $Doc_No = $_SESSION['Attendence_Doc_No'];
        //    $SurveyDate = $_SESSION['Attendence_SurveyDate'];

        //    print_r( $EnquiryCd);die;

        if(
            (isset($_SESSION['OR_ADMIN_SELECT_YEAR']) && !empty($_SESSION['OR_ADMIN_SELECT_YEAR'])) &&
            (isset($_SESSION['OR_ADMIN_SELECT_MONTH']) && !empty($_SESSION['OR_ADMIN_SELECT_MONTH']))
        ){
            $year = $_SESSION['OR_ADMIN_SELECT_YEAR'];
            $month = $_SESSION['OR_ADMIN_SELECT_MONTH'];
        
        }

        
          
   
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
         
  }else{
    //echo "ddd";
  }
}

// $date_data = $year."-".$month."-".$Day;
// // echo"<pre>";
//  print_r($date_data);
        
?>


<?php 
$Executive_Cd = $_SESSION['Attendence_EnquiryCd'];
//$Doc_No = $_SESSION['Attendence_Doc_No'];


//  print_r($Doc_No );
// $SurveyDate = $_SESSION['Attendence_SurveyDate'];


?>
<style>
  .modal-content {
      width: auto;
      border-radius: 0.5rem;
      overflow: hidden;
      border: none;
      box-shadow: 0 0 20px 0 rgb(0 0 0 / 10%);
      position: absolute;
      left: 50%;
      top: 1%; 
      margin-left: -320px;
      /* margin-top: -320px; */
  }
</style>

<?php 
    
    // if($date_data != ''){
    //     $dateCond = "";
    // }else{
    //     $dateCond = "AND CONVERT(VARCHAR, em.SurveyDate,23) ='$Day' ";
    // }

$query ="SELECT ed.SiteName,ed.Site_Cd,ed.Doc_No, ed.Attendance, ed.ExecutiveName, ed.Executive_Cd, ed.AbsentRemark,CONVERT(VARCHAR, ed.SurveyDate,23) as SurveyDate
            FROM Executive_Details ed
            LEFT JOIN Site_Master sm  ON ed.Site_Cd = sm.Site_Cd
            where Executive_Cd = '$Executive_Cd'
           
            AND CONVERT(VARCHAR, ed.SurveyDate,23) ='$Day'
           ";
    
    $db1=new DbOperation();
    $AttendanceList = $db1->getSurveyUtilityExecutiveData($query, $userName, $appName, $developmentMode);
//    echo"<pre>";
//     print_r($AttendanceList);
    $attendence_count = sizeof($AttendanceList);
    //  print_r($attendence_count);
    if($attendence_count > 0){
       
        $action = 'update';
        echo $action; 
        $SiteName = $AttendanceList[0]['SiteName'];
        $Site_Cd = $AttendanceList[0]['Site_Cd'];
        $Doc_No =$AttendanceList[0]['Doc_No'];
        $SurveyDate = $AttendanceList[0]['SurveyDate'];
        // print_r($SurveyDate);
        $Attendance = $AttendanceList[0]['Attendance'];
        $ExecutiveName = $AttendanceList[0]['ExecutiveName'];
        //$Executive_Cd = $AttendanceList[0]['Executive_Cd'];
        $AbsentRemark = $AttendanceList[0]['AbsentRemark'];
        // $SurveyDate = $AttendanceList[0]['SurveyDate'];
    //   echo $action; 
    }else{
        $action = 'insert';
       echo $action; 
        $query1 ="SELECT sm.SiteName,sm.Site_Cd, ed.Attendance, ed.ExecutiveName, ed.Executive_Cd, ed.AbsentRemark,CONVERT(VARCHAR, ed.SurveyDate,23) as SurveyDate
                    FROM Executive_Details ed
                    LEFT JOIN Site_Master sm  ON ed.Site_Cd = sm.Site_Cd
                    where Executive_Cd = '$Executive_Cd'
                   
                ";
            

        $InsertattendanceList = $db1->getSurveyUtilityExecutiveData($query1, $userName, $appName, $developmentMode);
    //   echo"<pre>";
    //     print_r($InsertattendanceList); 
        $SiteName =  $InsertattendanceList[0]['SiteName'];
        $Site_Cd = $InsertattendanceList[0]['Site_Cd'];
        $Doc_No ="";
        $SurveyDate = $Day;
        // print_r($SurveyDate);
        $Attendance ="";
        $ExecutiveName = $InsertattendanceList[0]['ExecutiveName'];
       // $Executive_Cd = "";
        $AbsentRemark = "";
        
      //  echo $action;

    }
     


      


    $query = "SELECT 
            COALESCE(sm.Site_Cd,0) AS Site_Cd, 
            COALESCE(sm.SiteName,'') AS SiteName
            FROM Site_Master sm
        INNER JOIN Election_Master em ON (sm.ElectionName = em.ElectionName)
        ";


$dataSite = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
// print_r($dataSite);

?>
<!-- <center> -->
<div id="MODAL_VIEW1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="">
    <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId">
        <div class="modal-content" style="width:50%;">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <!-- <div class="col-xl-6 col-md-6 col-sm-12"> -->
                            <!-- <div class="card"> -->
                                <div class="card-content">
                                    <div class="card-body">
                                        <h1>Attendence Details</h1>
                                      
                                        <form class="form-inline" >
                                            
                                            <div class="form-group">
                                                <label for="ExecutiveName">Executive Name:</label>
                                              
                                                <input type="text" class="form-control" name="ExecutiveName" id="ExecutiveName" value="<?php echo $ExecutiveName ; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="SurveyDate">Date:</label>
                                                
                                                <input type="date" class="form-control" id="SurveyDate" name="SurveyDate" value="<?php echo $SurveyDate; ?>" >
                                            </div>
                                            <div class="form-group">
                                                <label for="Attendance">Attendance:</label>
                                                <select class="select2 form-control" id="Attendance"  name="Attendance" value="<?php echo  $Attendance; ?>" >
                                                    <option value="2" <?php if( $Attendance == "2"){ ?> selected <?php } ?> >A</option>
                                                    <option value="1" <?php if( $Attendance == "1"){ ?> selected <?php } ?>>P</option>
                                                    <option value="wo" <?php if( $Attendance == "wo"){ ?> selected <?php } ?> style="color:red">wo</option>                                           
                                                </select>
                                               

                                                
                                            </div>
                                           
                                            <div class="form-group">
                                                <label for="InTime">In Time:</label>
                                                <input type="text" class="form-control" id="InTime" name="InTime"  value="">
                                            </div>

                                            <div class="form-group">
                                                <label for="AbsentRemark">Remark:</label>
                                                <input type="text" class="form-control" id="AbsentRemark" name="AbsentRemark"  value="<?php echo $AbsentRemark; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="SiteName">Site Name:</label>
                                              
                                                <select class="select2 form-control"  name="SiteName" >
                                                    <option value="">--SELECT--</option>
                                                    <?php
                                                    
                                                    if (sizeof($dataSite)>0) 
                                                    {
                                                        foreach ($dataSite as $key => $value) 
                                                        {
                                                            if($Site_Cd== $value["Site_Cd"])
                                                            {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['Site_Cd']; ?>~<?php echo $value['SiteName']; ?>"><?php echo $value["SiteName"];?></option>
                                                    <?php
                                                            }
                                                            else
                                                            {
                                                    ?>
                                                                <option value="<?php echo $value['Site_Cd']; ?>~<?php echo $value['SiteName']; ?>"><?php echo $value["SiteName"]; ?></option>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?> 
                                                </select>
                                              
                                              
                                            </div>
                                            <div class="row">
                                             <?php  if($action == 'update'){ ?>
                                            <button type="submit" class="btn btn-primary float-right waves-effect waves-light" onclick="updateAttendanceData('<?php echo $Executive_Cd; ?>','<?php echo $SurveyDate; ?>','<?php echo $Doc_No;?>','<?php echo $action;?>')">Update</button>
                                              <?php  }else if($action == 'insert'){ ?> 
                                                <button type="submit" class="btn btn-primary float-right waves-effect waves-light" onclick="insertAttendanceData('<?php echo $Executive_Cd; ?>','<?php echo $SurveyDate; ?>','<?php echo $action;?>')">Insert</button>
                                              <?php  } ?>
                                                   </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                                    <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                                    <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                                                </div>
                                            </div> 
                                        </form>
                                    </div>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- </center> -->