<section id="ModalSection">
<?php
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
if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}else{
    $ServerIP ="103.14.99.154";
}

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(
        isset($_GET['SalaryP_ID']) && !empty($_GET['SalaryP_ID']) &&
        isset($_GET['Month']) && !empty($_GET['Month']) &&
        isset($_GET['Year']) && !empty($_GET['Year'])
    ){

    try  
        {  
            $SalaryP_ID = $_GET['SalaryP_ID'];
            $Month = $_GET['Month'];
            $Year = $_GET['Year'];

            $TableName = "SalaryProcess_".$Month."_".$Year;
            $data = array();
            $TableData = array();
            $TableQuery = "SELECT TOP 1
                        COALESCE(SalaryP_ID,0) AS SalaryP_ID,
                        COALESCE(Executive_Cd,0) AS Executive_Cd,
                        COALESCE(ExecutiveName,'') AS ExecutiveName,
                        COALESCE(UserName,'') AS UserName,
                        COALESCE(Designation,'') AS Designation,
                        COALESCE(ReferenceName,'') AS ReferenceName,
                        COALESCE(Present,0) AS Present,
                        COALESCE(Absent,0) AS Absent,
                        COALESCE(HalfDay,0) AS HalfDay,
                        COALESCE(Training,0) AS Training,
                        COALESCE(CONVERT(VARCHAR,JoiningDate,105),'') AS JoiningDate,
                        COALESCE(CONVERT(VARCHAR,FirstEntryDate,105),'') AS FirstEntryDate,
                        COALESCE(RoomSurveyDone,0) AS RoomSurveyDone,
                        COALESCE(Average,0) AS Average,
                        COALESCE(Salary,0) AS Salary,
                        COALESCE(SalaryType,0) AS SalaryType,
                        COALESCE(DeductionType,0) AS DeductionType,
                        COALESCE(PayableSalary,0) AS PayableSalary,
                        COALESCE(PaymentStatus,'') AS PaymentStatus,
                        COALESCE(MonthDays,0) AS MonthDays,
                        COALESCE(PerDaySalary,0) AS PerDaySalary,
                        COALESCE(DeductionAmt,0) AS DeductionAmt,
                        COALESCE(AdvanceAmt,0) AS AdvanceAmt,
                        COALESCE(IncentivesAmt,0) AS IncentivesAmt,
                        COALESCE(Remark,'') AS Remark
                        FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName]
                        WHERE SalaryP_ID = $SalaryP_ID;";
            $TableData = $db->ExecutveQuerySingleRowSALData($TableQuery, $userName, $appName, $developmentMode);
            if(sizeof($TableData)>0){
                $SalaryP_ID = $TableData['SalaryP_ID'];
                $Executive_Cd = $TableData['Executive_Cd'];
                $ExecutiveName = $TableData['ExecutiveName'];
                $UserName = $TableData['UserName'];
                $Designation = $TableData['Designation'];
                $ReferenceName = $TableData['ReferenceName'];
                $Present = $TableData['Present'];
                $Absent = $TableData['Absent'];
                $HalfDay = $TableData['HalfDay'];
                $Training = $TableData['Training'];
                $RoomSurveyDone = $TableData['RoomSurveyDone'];
                $Average = $TableData['Average'];
                $Salary = $TableData['Salary'];
                $SalaryType = $TableData['SalaryType'];
                $DeductionType = $TableData['DeductionType'];
                $DeductionAmt = $TableData['DeductionAmt'];
                $PayableSalary = $TableData['PayableSalary'];
                $PaymentStatus = $TableData['PaymentStatus'];
                $MonthDays = $TableData['MonthDays'];
                $PerDaySalary = $TableData['PerDaySalary'];
                $AdvanceAmt = $TableData['AdvanceAmt'];
                $IncentivesAmt = $TableData['IncentivesAmt'];
                $Remark = $TableData['Remark'];
                $JoiningDate = $TableData['JoiningDate'];
                $FirstEntryDate = $TableData['FirstEntryDate'];

                $AbsentAndHDdeduction = ($Absent + (0.5 * $HalfDay)) * ($Salary / $MonthDays);
            }
			
			if($PaymentStatus == "Paid"){
                $FieldDisable = "disabled";
            }else{
                $FieldDisable = "";
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
    
?>
    <center>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <div id="MODAL_VIEW" class="modal" style="margin-top:13%;">
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
                    .Payable{
                        font-size:15px;
                        background-color:#b6f2b8;
                        width:100%;
                        text-align:center;
                        font-weight:bold;
                        border:1px solid #b5b0b0;
                    }
                </style>
                <div class="modal-dialog  modal-xl chatapp-call-window" role="document">
                    <div class="modal-content" style="width:50%;">
                        <div class="card-header" style="padding-left:10px;padding-top:10px;padding-bottom:7px;">
                            <div class = "row">
                                <div class="col-11">
                                    <h4 class="card-title" style="text-align:left;">
                                        <?php echo $Executive_Cd." - ".$ExecutiveName." ( ".$Designation." )" ; ?>
                                    </h4>
                                </div>
                                <div class="col-1">
                                    <span class="close" style="cursor:pointer;font-size:30px;" onclick="CloseModal()">&times;</span>
                                </div>
                            </div>
                        </div>

                        <div class="row match-height">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="content-body">
                                        <table class="table table-hover-animation table-hover" style="margin-top:10px;">
                                            <tr style="border-collapse: collapse;border: 1px solid #adadad;">
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b>Salary</b></td>
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b id="Salary"><?php echo $Salary; ?></b></td>
                                            </tr>
                                            <tr style="color:red;border-collapse: collapse;border: 1px solid #adadad;">
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b>Absent+HaldDay</b></td>
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b><?php echo round($AbsentAndHDdeduction); ?></b></td>
                                            </tr>
                                            <tr style="color:red;border-collapse: collapse;border: 1px solid #adadad;">
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b>Advance (-)</b></td>
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b id="AdvanceAmt"><?php echo $AdvanceAmt; ?></b></td>
                                            </tr>
                                            <tr style="color:red;border-collapse: collapse;border: 1px solid #adadad;">
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b>Deduction (-)</b></td>
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b id="DeductionAmt"><?php echo $DeductionAmt; ?></b></td>
                                            </tr>
                                            <tr style="color:green;border-collapse: collapse;border: 1px solid #adadad;">
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b>Incentives (+)</b></td>
                                                <td  style="border-collapse: collapse;border: 1px solid #adadad;"><b id="IncentiveAmt"><?php echo $IncentivesAmt; ?></b></td>
                                            </tr>
                                            <tr style="border-collapse: collapse;border: 1px solid #adadad;">
                                                <td colspan="2"  style="color:green;border-collapse: collapse;border: 1px solid #adadad;">
                                                    <input class="Payable" id="PayableAmtChange" name="PayableAmtChange" type="text" readonly style="" value="<?php echo number_format($PayableSalary, 2); ?>">
                                                    <input name="PayableAmt" type="hidden" readonly style="" value="<?php echo $PayableSalary; ?>">
                                                    <input name="TotalSalary" type="hidden" readonly style="" value="<?php echo $Salary; ?>">
                                                    <input name="AbsentAndHalfDays" type="hidden" readonly style="" value="<?php echo round($AbsentAndHDdeduction); ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <h4 style="float:left;margin-left:10px;margin-top:10px;">P : <?php echo $Present; ?> / A : <?php echo $Absent; ?> / HF : <?php echo $HalfDay; ?> / T : <?php echo $Training; ?></h4>
                                    <div class="content-body">
                                        <div class="card-content">
                                            <div class="card-body" style="margin-top:-20px;margin-bottom: -28px;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                                        <div class="form-group">
                                                            <label style="float:left;">Advance</label>
                                                            <div class="controls">
                                                                <input type="text" <?php echo $FieldDisable; ?> onkeypress="return isNumberKey(event,this)" onkeyup="CalculateSalary()" value="<?php echo $AdvanceAmt; ?>" placeholder="Advance Paid" class="form-control" name="Advance" id="AdvanceInput"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                                        <div class="form-group">
                                                            <label style="float:left;">Deduction</label>
                                                            <div class="controls">
                                                                <input type="text" <?php echo $FieldDisable; ?> onkeypress="return isNumberKey(event,this)"  onkeyup="CalculateSalary()" value="<?php echo $DeductionAmt; ?>" placeholder="Deduction" class="form-control" name="Deduction" id="DeductionInput"/>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                                        <div class="form-group">
                                                            <label style="float:left;">Incentives</label>
                                                            <div class="controls">
                                                                <input type="text" <?php echo $FieldDisable; ?> onkeypress="return isNumberKey(event,this)"  onkeyup="CalculateSalary()" value="<?php echo $IncentivesAmt; ?>" placeholder="Incentives" class="form-control" name="Incentives" id="IncentivesInput"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:-13px;">
                                                    <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label style="float:left;">Remark</label>
                                                            <div class="controls">
                                                                <input type="text" <?php echo $FieldDisable; ?> value="<?php echo $Remark; ?>" placeholder="Remark" class="form-control" name="RemarkDeduction" id="RemarkDeduction"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                                                        <div class="controls" style="margin-top:28px">
                                                            <div id='spinnerLoader2Modal' style='display:none;float:left;'>
                                                                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                                            </div>
                                                            <div id="msgsuccessSalaryProcessed" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;float:left;"></div>
                                                            <div id="msgfailedSalaryProcessed" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;float:left;"></div>
                                                            <div id="waitMSGSalaryProcessed" class="controls alert alert-warning text-center" role="alert" style="display: none;padding:5px;float:left;"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                                        <div class="controls" style="margin-top:28px">
                                                            <button type="button" id="UpdateButtonProcessedSalary" style="float:right;padding:10px;" class="btn btn-primary" onclick="updateProcessedSalary('<?php echo $TableName;?>','<?php echo $SalaryP_ID; ?>')">
                                                                Save
                                                            </button>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card"> 
                                        <div class="card-content">
                                            <div class="card-body card-dashboard">
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
                                                            if(sizeof($TableData) > 0 ){
                                                                $srNo = 1;
                                                                foreach ($TableData as $key => $value) {
                                                                ?> 
                                                                    <tr style="padding-top:0px;">
                                                                        <td><?php //echo $srNo++; ?></td>
                                                                        <td>
                                                                            <input class="form-check-input element checkboxALL" type="checkbox" style=" margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" value="<?php echo $value['Executive_Cd']; ?>" id="AssignCheckbox" onclick="getExecutiveCdsToTransfer()" >
                                                                        </td>
                                                                        <td><?php //echo "<b>" . $value["ExecutiveName"] . "</b>" ?></td>
                                                                        <td>
                                                                        <?php 
                                                                            // if($value["attendance"] == "Absent"){
                                                                            //     echo "<span class='badge badge-danger'><b>".$value["attendance"]."</b></span>";
                                                                            // }else if($value["attendance"] == "Present"){
                                                                            //     echo "<span class='badge badge-success'><b>".$value["attendance"]."</b></span>";
                                                                            // }else if($value["attendance"] == "Half Day"){
                                                                            //     echo "<span class='badge badge-warning'><b>".$value["attendance"]."</b></span>";
                                                                            // }else if($value["attendance"] == "Assigned"){
                                                                            //     echo "<span class='badge badge-primary'><b>".$value["attendance"]."</b></span>";
                                                                            // }else if($value["attendance"] == "Training"){
                                                                            //     echo "<span class='badge badge-secondary'><b>".$value["attendance"]."</b></span>";
                                                                            // }else{

                                                                            // } 
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
                        </section> -->
                    </div>
                </div>
            </div>
        </div>
    </center>
    <script>
        $('.select2').select2();
    </script>

</section>
