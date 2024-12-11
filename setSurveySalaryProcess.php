 
<section id="dashboard-analytics">
    
<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
$Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];

if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}else{
    $ServerIP ="103.14.99.154";
}

$Designation = "";
$PaymentStatus = "";
$Reference_Cd = "";
$Month = "";
$Year = "";
$CorproationULB = "";
$currentDate = date('Y-m-d');
$ReferenceWiseData = array();
 
$dataElectionNameAverageCount = $db->getCorporationDataForAssignExecutiveToSite($userName, $appName, $developmentMode);

function IND_money_format($number){
    $decimal = (string)($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);

    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $delimiter .=',';
        }
        $delimiter .=$money[$i];
    }

    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);

    if( $decimal != '0'){
        $result = $result.$decimal;
    }

    return $result;
}

if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == "all"){
    unset($_SESSION['SurveyUA_Salary_Process_Designation']);
    unset($_SESSION['SurveyUA_Salary_Process_PaymentStatus']);
    unset($_SESSION['SurveyUA_Salary_Process_Reference']);
    unset($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile']);
    unset($_SESSION['SurveyUA_Salary_Process_Electionname']);
    header('location:index.php?p=survey-salary-process');
}

$currentMonth = date('m');
if(isset($_SESSION['SurveyUA_Salary_Process_Month']) && !empty($_SESSION['SurveyUA_Salary_Process_Month'])){
    $Month = $_SESSION['SurveyUA_Salary_Process_Month'];
}else{
    $Month = $currentMonth;
    $_SESSION['SurveyUA_Salary_Process_Month'] = $Month;
}

if(isset($_SESSION['SurveyUA_Salary_Process_Year']) && !empty($_SESSION['SurveyUA_Salary_Process_Year'])){
    $Year = $_SESSION['SurveyUA_Salary_Process_Year'];
}else{
    $Year = date('Y');
    $_SESSION['SurveyUA_Salary_Process_Year'] = $Year;
}

if(isset($_SESSION['SurveyUA_Salary_Process_Designation']) && !empty($_SESSION['SurveyUA_Salary_Process_Designation'])){
    $Designation = $_SESSION['SurveyUA_Salary_Process_Designation'];
}else{
    $Designation = 'All';
    $_SESSION['SurveyUA_Salary_Process_Designation'] = $Designation;
}

$DesignationCond = "";
if($Designation != "All"){

    if($Designation == "Site Manager"){
        
        $DesignationCond = "AND Designation IN ('Site Manager')";
        
    }else if($Designation == "Supervisor"){
        
        $DesignationCond = "AND Designation IN ('Survey Supervisor')";
        
    }else if($Designation == "Survey Executive"){

        $DesignationCond = "AND Designation IN ('SE-Belapur','Survey Executive')";

    }else{
        $DesignationCond = "";    
    }

}else{
    $DesignationCond = "";
}

if(isset($_SESSION['SurveyUA_Salary_Process_PaymentStatus']) && !empty($_SESSION['SurveyUA_Salary_Process_PaymentStatus'])){
    $PaymentStatus = $_SESSION['SurveyUA_Salary_Process_PaymentStatus'];
}else{
    $PaymentStatus = 'All';
    $_SESSION['SurveyUA_Salary_Process_PaymentStatus'] = $PaymentStatus;
}

$PaymentStatusCond= "";
if($PaymentStatus != "All"){
    if($PaymentStatus != "Un-Paid"){
        $PaymentStatusCond= "AND PaymentStatus = '$PaymentStatus'";
    }else{
        $PaymentStatusCond= "AND COALESCE(PaymentStatus,'') = ''";
    }
}else{
    $PaymentStatusCond= "";
}
$TableData = array();
$ReportTableData = array();
$ReferenceDDData = array();
$ReferenceQuery = "SELECT Reference_Cd, ReferenceName FROM ChankyaAdmin..Reference_Master WHERE IsActive = 1 ORDER BY ReferenceName";
$db2=new DbOperation();
$ReferenceDDData = $db2->ExecutveQueryMultipleRowSALData($ReferenceQuery, $userName, $appName, $developmentMode);

if(isset($_SESSION['SurveyUA_Salary_Process_Reference']) && !empty($_SESSION['SurveyUA_Salary_Process_Reference'])){
    $Reference_Cd = $_SESSION['SurveyUA_Salary_Process_Reference'];
}else{
    $Reference_Cd = 'All';
    $_SESSION['SurveyUA_Salary_Process_Reference'] = $Reference_Cd;
}
$ReferenceCond = "";
if($Reference_Cd != "All"){
    $ReferenceCond = " AND ReferenceName = '$Reference_Cd'";
}else{
    $ReferenceCond = "";
}

$ULBcorporation = $db->getSurveyUtilityULB_Data($userName, $appName, $developmentMode);
if(isset($_SESSION['SurveyUA_Salary_Process_Electionname']) && !empty($_SESSION['SurveyUA_Salary_Process_Electionname'])){
    $CorproationULB = $_SESSION['SurveyUA_Salary_Process_Electionname'];
}else{
    $CorproationULB = 'All';
    $_SESSION['SurveyUA_Salary_Process_Electionname'] = $CorproationULB;
}

$ULBCondJoin = "";
$ULBCondJoin2 = "";
$UBLwhereCond = "";
if($CorproationULB !== 'All'){
    $ULBCondJoin = "INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (t1.Executive_Cd= em.Executive_Cd)
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] AS elm ON (em.ElectionName = elm.ElectionName)";
    
    $ULBCondJoin2 = "INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (t2.Executive_Cd= em.Executive_Cd)
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] AS elm ON (em.ElectionName = elm.ElectionName)";

    $UBLwhereCond = "AND elm.ULB = '$CorproationULB'";
}else{
    $ULBCondJoin = "";
    $UBLwhereCond = "";
}

if(isset($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile']) && !empty($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile'])){
    $SearchedValue = $_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile'];
}else{
    $SearchedValue = "";
}

$searchCondition = "";
$searchCondition2 = "";
$searchCondition3 = "";
if(!empty($SearchedValue)){

    if ($SearchedValue == trim($SearchedValue) && strpos($SearchedValue, ' ') !== false) {
        $strArr = explode(" ", $SearchedValue);
        foreach($strArr as $value){
            $searchCondition .= " AND (ExecutiveName like '%$value%' OR Executive_Cd like '%$value%') ";
            $searchCondition2 .= " AND (t2.ExecutiveName like '%$value%' OR t2.Executive_Cd like '%$value%') ";
            $searchCondition3 .= " AND (t1.ExecutiveName like '%$value%' OR t1.Executive_Cd like '%$value%') ";
        }
    }else{
            $searchCondition = " AND (ExecutiveName like '%$SearchedValue%' OR Executive_Cd like '%$SearchedValue%') ";
            $searchCondition2 = " AND (t2.ExecutiveName like '%$SearchedValue%' OR t2.Executive_Cd like '%$SearchedValue%') ";
            $searchCondition3 = " AND (t1.ExecutiveName like '%$SearchedValue%' OR t1.Executive_Cd like '%$SearchedValue%') ";
    }
}

$dataSite = array();
$dataSite = $db->getSiteDropDownDatabyElectionName($userName, $appName,  $developmentMode);
$SuperVisorquery = "SELECT 
                    COALESCE(Executive_Cd, 0) as Executive_Cd,
                    COALESCE(ExecutiveName, '') as ExecutiveName, 
                    COALESCE(MobileNo, '') as MobileNo,
                    COALESCE(Designation, '') as Designation 
                    FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master]
                    WHERE (Designation = 'SP' or Designation = 'Survey Supervisor') 
                    AND EmpStatus = 'A' AND ElectionName <> 'AMC' 
                    ORDER BY ExecutiveName;";
$SuperVisorData = $db->ExecutveQueryMultipleRowSALData($SuperVisorquery, $userName, $appName, $developmentMode);

$TableName = "SalaryProcess_".$Month."_".$Year;
$data = array();
$connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
$conn154 = sqlsrv_connect("103.14.99.154", $connectionString154);
$CheckIfAlreadyProcessed = "IF OBJECT_ID('Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year."', 'U') IS NOT NULL
                                BEGIN
                                    SELECT 'YES' as Flag
                                END
                            ELSE
                                BEGIN
                                    SELECT 'NO' as Flag
                                END";
$runQueryExec = sqlsrv_query($conn154, $CheckIfAlreadyProcessed);
if ($runQueryExec !== FALSE) {
    $row_count = sqlsrv_num_rows( $runQueryExec );
    while($row = sqlsrv_fetch_array($runQueryExec, SQLSRV_FETCH_ASSOC)){
        $data['Flag'] = $row['Flag'];
    }
}
if($data['Flag'] == "YES"){
    $TableQuery = "SELECT 
                    COALESCE(t2.SalaryP_ID,0) AS SalaryP_ID,
                    COALESCE(t2.Executive_Cd,0) AS Executive_Cd,
                    COALESCE(t2.ExecutiveName,'') AS ExecutiveName,
                    COALESCE(t2.UserName,'') AS UserName,
                    COALESCE(t2.Designation,'') AS Designation,
                    COALESCE(t2.ReferenceName,'') AS ReferenceName,
                    COALESCE(t2.Present,0) AS Present,
                    COALESCE(t2.Absent,0) AS Absent,
                    COALESCE(t2.HalfDay,0) AS HalfDay,
                    COALESCE(t2.Training,0) AS Training,
                    COALESCE(CONVERT(VARCHAR,t2.JoiningDate,105),'') AS JoiningDate,
                    COALESCE(CONVERT(VARCHAR,t2.FirstEntryDate,105),'') AS FirstEntryDate,
                    COALESCE(t2.RoomSurveyDone,0) AS RoomSurveyDone,
                    COALESCE(t2.Average,0) AS Average,
                    COALESCE(t2.Salary,0) AS Salary,
                    COALESCE(t2.SalaryType,0) AS SalaryType,
                    COALESCE(t2.DeductionType,0) AS DeductionType,
                    COALESCE(t2.DeductionAmt,0) AS DeductionAmt,
                    COALESCE(t2.PayableSalary,0) AS PayableSalary,
                    COALESCE(t2.AdvanceAmt,0) AS AdvanceAmt,
                    COALESCE(t2.IncentivesAmt,0) AS IncentivesAmt,
                    COALESCE(t2.MonthDays,0) AS MonthDays,
                    COALESCE(t2.PaymentStatus,'') AS PaymentStatus,
                    COALESCE(t2.PayStatusRemark,'') AS PayStatusRemark,
                    COALESCE(t2.TotalMobileCount,0) AS TotalMobileCount,
                    COALESCE(t2.ReceivedMobileNo,0) AS ReceivedMobileNo,
                    COALESCE(t2.WrongMobileNo,0) AS WrongMobileNo,
                    COALESCE(t2.NotConnectedMobileNo,0) AS NotConnectedMobileNo,
                    COALESCE(elm.ULB,'') AS ULB
                    FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] AS t2
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em ON (t2.Executive_Cd = em.Executive_Cd)
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] elm ON (em.ElectionName = elm.ElectionName)
                    WHERE t2.RoomSurveyDone IS NOT NULL
                    $searchCondition2
                    $DesignationCond
                    $ReferenceCond
                    $PaymentStatusCond
                    $UBLwhereCond
                    ;";
    $TableData = $db->ExecutveQueryMultipleRowSALData($TableQuery, $userName, $appName, $developmentMode);
    
    // $ReferenceWiseQuery = "SELECT
    //                         COALESCE(t1.ReferenceName,'NA') AS ReferenceName,
    //                         SUM(t1.Salary) AS Salary,
    //                         SUM(t1.PayableSalary) AS PayableSalary,
    //                         COUNT(t1.SalaryP_ID) AS TotalExecutives,
    //                         COALESCE((
    //                             SELECT 
    //                             COALESCE(t2.SalaryP_ID,0) AS SalaryP_ID,
    //                             COALESCE(t2.Executive_Cd,0) AS Executive_Cd,
    //                             COALESCE(t2.ExecutiveName,'') AS ExecutiveName,
    //                             COALESCE(t2.Designation,'') AS Designation,
    //                             COALESCE(CONVERT(VARCHAR,t2.JoiningDate,105),'') AS JoiningDate,
    //                             COALESCE(CONVERT(VARCHAR,t2.FirstEntryDate,105),'') AS FirstEntryDate,
    //                             COALESCE(t2.RoomSurveyDone,0) AS RoomSurveyDone,
    //                             COALESCE(t2.Average,0) AS Average,
    //                             COALESCE(t2.Salary,0) AS Salary,
    //                             COALESCE(t2.PayableSalary,0) AS PayableSalary,
    //                             COALESCE(t2.PaymentStatus,'') AS PaymentStatus,
    //                             COALESCE(t2.PayStatusRemark,'') AS PayStatusRemark
    //                             FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] AS t2
    //                             $ULBCondJoin2
    //                             WHERE t2.ReferenceName = t1.ReferenceName
    //                             $UBLwhereCond
    //                             $searchCondition2
    //                             ORDER BY ExecutiveName
    //                             FOR JSON PATH
    //                         ),'') AS SubDataExecutives
    //                         FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] AS t1
    //                         $ULBCondJoin
    //                         WHERE t1.RoomSurveyDone != 0
    //                         $searchCondition3
    //                         $DesignationCond
    //                         $ReferenceCond
    //                         $PaymentStatusCond
    //                         $UBLwhereCond
    //                         GROUP BY t1.ReferenceName;";
    // $ReferenceWiseData = $db->ExecutveQueryMultipleRowSALData($ReferenceWiseQuery, $userName, $appName, $developmentMode);

}
// echo "<pre>";
// print_r($ReferenceWiseData);
// echo "</pre>";
$totalDays = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);

$currentDateCompare = new DateTime();
$lastDateOfAugust = new DateTime(date('Y-'.$Month.'-t'));

?>


<style type="text/css">
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

    th{
        background: lightgrey;
    }

    .dot {
        height: 15px;
        width: 15px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }
    table.dataTable th, table.dataTable td {
        border-bottom: 1px solid #F8F8F8;
        border-top: 0;
        padding: 5PX;
    }
    .element {
        cursor: default;
    }

    /* Custom cursor on hover */
    .element:hover {
        cursor: pointer;
    }

    .nav.nav-tabs .nav-item .nav-link.active {
        border: none;
        position: relative;
        color: #0e728a;
        -webkit-transition: all 0.2s ease;
        transition: all 0.2s ease;
        background-color: transparent;
    }

    .nav.nav-tabs .nav-item .nav-link.active:after {
        content: attr(data-before);
        height: 2px;
        width: 100%;
        left: 0;
        position: absolute;
        bottom: 0;
        top: 100%;
        background: -webkit-linear-gradient(60deg, #7367F0, rgba(115, 103, 240, 0.5)) !important;
        background: linear-gradient(30deg, #0d6fab, rgb(103 227 240 / 50%)) !important;
        box-shadow: 0 0 8px 0 rgba(115, 103, 240, 0.5) !important;
        -webkit-transform: translateY(0px);
        -ms-transform: translateY(0px);
        transform: translateY(0px);
        -webkit-transition: all 0.2s linear;
        transition: all 0.2s linear;
    }
    
    .select2-container--classic.select2-container--open .select2-selection--single, .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #41bdcc !important;
        outline: 0;
    }
</style>
   

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body" style="margin-top: -12px;margin-bottom: -30px;">
                        <div class="row">                                  
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Month</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="Month" onchange="setMonthInSessionFromSalaryProcess(this.value);">
                                            <?php 
                                                $months = array(
                                                    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                                                    'September', 'October', 'November', 'December'
                                                );
                                            
                                                foreach ($months as $index => $month) {
                                                    $value = str_pad(($index + 1), 2, '0', STR_PAD_LEFT);
                                                    if($value == $Month){
                                            ?>
                                                        <option selected=true value="<?php echo $value;?>"><?php echo $month; ?></option>
                                            <?php
                                                    }else{
                                            ?>
                                                        <option value="<?php echo $value;?>"><?php echo $month; ?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Year</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="Month" onchange="setYearInSessionFromSalaryProcess(this.value);">
                                            <?php
                                                $startYear = date('Y', strtotime('-1 year'));
                                                $endYear = $startYear + 5;
                                                for ($year = $startYear; $year <= $endYear; $year++) {
                                                    if($year == $Year){
                                            ?>
                                                        <option selected=true value="<?php echo $year;?>"><?php echo $year; ?></option>
                                            <?php
                                                    }else{
                                            ?>
                                                        <option value="<?php echo $year;?>"><?php echo $year; ?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Corporation</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="SiteName" onchange="CorporationNameInSessionFromSalaryProcess(this.value)">
                                            <option <?php echo $CorproationULB == "All" ? "selected=true" : "" ?> value="All">All</option>
                                            <?php
                                            if (sizeof($ULBcorporation)>0) 
                                            {
                                                foreach ($ULBcorporation as $key => $value) 
                                                {
                                                    if($CorproationULB == $value["ULB"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['ULB']; ?>"><?php echo "<b>".$value["ULB"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value['ULB']; ?>"><?php echo "<b>".$value["ULB"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="Designation" onchange="setDesignationInSessionFromSalaryProcess(this.value)">
                                            <option <?php echo $Designation == "All" ? "selected=true" : "" ?> value="All">All</option>
                                            <option <?php echo $Designation == "Site Manager" ? 'selected=true' : '' ?> value="Site Manager">Site Manager</option>
                                            <option <?php echo $Designation == "Supervisor" ? 'selected=true' : '' ?> value="Supervisor">Supervisor</option>
                                            <option <?php echo $Designation == "Survey Executive" ? 'selected=true' : '' ?> value="Survey Executive">Survey Executive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="PaymentStatus" onchange="setPaymentStatusInSessionFromSalaryProcess(this.value);">
                                            <option <?php echo $PaymentStatus == "All" ? "selected=true" : "" ?> value="All">All</option>
                                            <option <?php echo $PaymentStatus == "Paid" ? "selected=true" : "" ?> value="Paid">Paid</option>
                                            <option <?php echo $PaymentStatus == "Un-Paid" ? "selected=true" : "" ?> value="Un-Paid">Un-Paid</option>
                                            <option <?php echo $PaymentStatus == "Hold" ? "selected=true" : "" ?> value="Hold">Hold</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="Reference" onchange="setReferenceInSessionFromSalaryProcess(this.value);">
                                            <option <?php echo $Reference_Cd == "All" ? "selected=true" : "" ?> value="All">All</option>
                                            <?php
                                            if(sizeof($ReferenceDDData) > 0)
                                            {
                                                foreach ($ReferenceDDData as $key => $value) 
                                                {
                                                    if($Reference_Cd == $value["ReferenceName"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['ReferenceName']; ?>"><?php echo "<b>".$value["ReferenceName"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value['ReferenceName']; ?>"><?php echo "<b>".$value["ReferenceName"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Search by Executive Cd / Name</label>
                                    <div class="controls">
                                        <input type="text" value="<?php echo $SearchedValue; ?>" placeholder="Search by Executive Cd / Name" class="form-control" name="ExecutiveCdOrNameOrMobile"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                <div class="controls" style="margin-top:20px">
                                    <div id='spinnerLoader2' style='display:none;float:left;'>
                                        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="controls" style="margin-top:28px">
                                    <button type="button" id="UpdateButton" style="padding:10px;" class="btn btn-primary" onclick="searchedNameCdOrMobileInSessionFromSalaryProcess()">
                                        Search
                                    </button>
                                    <a href="index.php?p=survey-salary-process&action=all" style="padding:10px;" class="btn btn-primary">
                                        ALL
                                    </a>
                                </div> 
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="controls" style="margin-top:28px">
                                    <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;padding:5px;"></div>
                                    <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;padding:5px;"></div>
                                    <div id="waitMSG" class="controls alert alert-warning text-center" role="alert" style="display: none;padding:5px;"></div>
                                </div> 
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="controls" style="margin-top:28px">
                                    <button type="button" id="ProcessButton" 
                                    <?php if($currentDateCompare < $lastDateOfAugust){ echo "disabled"; }?>
                                    style="float:right;padding:10px;" class="btn btn-primary" onclick="processSalary('<?php echo $Executive_Cd; ?>', '<?php echo $Month; ?>', '<?php echo $Year; ?>', '<?php echo $totalDays; ?>')">
                                        Process
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

<div class="tab-pane" id="home" aria-labelledby="home-tab" role="tabpanel" style="margin-top: -25px;">
    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item">
            <a class="nav-link active" id="SalaryGeneration-tab" data-toggle="tab" href="#SalaryGeneration" aria-controls="SalaryGeneration" role="tab" aria-selected="flase">Salary Processed</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" id="ReferenceWiseReport-tab" data-toggle="tab" href="#ReferenceWiseReport" aria-controls="ReferenceWiseReport" role="tab" aria-selected="true">Reference Wise Report</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link"  id="ReferenceWiseReport-tab" data-toggle="tab"  href="#ReferenceWiseReport" aria-controls="ReferenceWiseReport" role="tab" aria-selected="true"
            onclick="SurveySalaryProcessTab('ReferenceWiseReport','<?php echo $Month;?>','<?php echo $Year;?>');"
            >Reference Wise Report</a>
            <!-- ,'<?php //echo $ULBCondJoin2;?>','<?php //echo $ULBCondJoin;?>','<?php //echo $searchCondition3;?>','<?php //echo $DesignationCond;?>','<?php //echo $ReferenceCond;?>','<?php //echo $PaymentStatusCond;?>','<?php //echo $UBLwhereCond;?>','<?php //echo $searchCondition2;?>' -->
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="SalaryGeneration" aria-labelledby="SalaryGeneration-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="row match-height" style="margin-bottom: -20px;">
                                    <div class="col-md-6">
                                        <div class="row" style="margin-left: 12px;">
                                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                                <div class="form-group">
                                                    <div class="controls" style="margin-top:10px">
                                                        <h4>Total Executives : (<?php echo "<b>".count($TableData)."</b>";?>) - Selected Executive : <span id="SelectedExecutiveCds"> 0 </span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none" id="UpdateStatusForm">
                                        <div class="row" style="margin-bottom: 0px;">
                                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                                <div class="form-group">
                                                    <div class="controls" style="margin-top:10px">
                                                        <input type="hidden" name="SelectedExecutives" class="form-control" id="SelectedExecutives" value="">
                                                        <input type="hidden" class="form-control" name="TableName" id="TableName" value="<?php echo $TableName; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                                <div class="form-group">
                                                    <div class="controls" style="margin-top:10px">
                                                        <select class="select2 form-control"  name="PayStatus" onchange="setRemarkinputbyPayStatus(this.value)">
                                                            <option value="">- Status -</option>
                                                            <option value="Paid">Paid</option>
                                                            <option value="Hold">Hold</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-xl-4 col-md-4 col-12" id="RemarkDiv" style="display:none">
                                                <div class="form-group">
                                                    <div class="controls"  style="margin-top:10px">
                                                        <input type="text" value="" placeholder="Remark" class="form-control" name="Remark" id="Remark"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-xl-1 col-md-1 col-12">
                                                <div class="controls" style="margin-top:12px">
                                                    <button onclick="UpdatePaymentDetailes();" id="UpdatePaymentStatus" style="padding:10px;float:right;" class="btn btn-primary">
                                                        Save
                                                    </button>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content" style="margin-top: -5px;">
                                    <div class="card-body card-dashboard">
                                        <div class="row match-height" style="margin-top:-15px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    <div class="content-body" style="overflow:scroll;">
                                                        <table class="table table-hover-animation table-hover table-striped" id="SalaryProcessUpdateTable" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <!-- <input class="form-control" placeholder="Executive Cd" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column1SearchMain" /> -->
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Executive Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column3SearchMain" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="ULB" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column5SearchMain" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Designation" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column4SearchMain" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                </tr>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="margin-left: 20px;background-color:#36abb9;color: white;">Executive Name</th>
                                                                    <th style="margin-left: 20px;background-color:#36abb9;color: white;">ULB</th>
                                                                    <th style="background-color:#36abb9;color: white;">Designation</th>
                                                                    <th style="background-color:#36abb9;color: white;">Reference</th>
                                                                    <th style="background-color:#36abb9;color: white;">Joining Date</th>
                                                                    <th style="background-color:#36abb9;color: white;">First Entry Date</th>
                                                                    <th style="background-color:#36abb9;color: white;">Absent</th>
                                                                    <th style="background-color:#36abb9;color: white;">Half Day</th>
                                                                    <th style="background-color:#36abb9;color: white;">Mobile No<br>T/R/W/NC</th>
                                                                    <!-- <th style="background-color:#36abb9;color: white;">Room Survey Done</th>
                                                                    <th style="background-color:#36abb9;color: white;">Average</th> -->
                                                                    <th style="background-color:#36abb9;color: white;">Salary</th>
                                                                    <th style="background-color:#36abb9;color: white;">Deduction<br>A+HD/A/D</th>
                                                                    <th style="background-color:#36abb9;color: white;">Incentives</th>
                                                                    <th style="background-color:#36abb9;color: white;">Payable Salary</th>
                                                                    <th style="background-color:#36abb9;color: white;">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    if(sizeof($TableData) > 0){
                                                                        $srNo = 1;
                                                                        $SalaryTotal = 0;
                                                                        $DeductionTotal = 0;
                                                                        $IncentivesTotal = 0;
                                                                        $PayableTotal = 0;
                                                                        $DeductionAMT = 0;
                                                                        foreach($TableData AS $Key=>$value){
                                                                            $SalaryTotal += $value['Salary'];
                                                                            $DeductionAMT = round(($value['Absent'] + (0.5 * $value['HalfDay'])) * ($value['Salary'] / $value['MonthDays']))+$value['AdvanceAmt']+$value['DeductionAmt'];
                                                                            $DeductionTotal += $DeductionAMT;
                                                                            $IncentivesTotal += $value['IncentivesAmt'];
                                                                            $PayableTotal += $value['PayableSalary'];
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <input class="form-check-input element checkbox" type="checkbox" style=" margin-left:0px;width: 17px; height: 17px;margin-top:5px;" value="<?php echo $value['SalaryP_ID']; ?>" id="StatusUpdateCheckbox" onclick="setSelectedExecutivesExecutive(<?php echo $value['SalaryP_ID']; ?>)" />
                                                                                <a style="color: #36abb9;font-size:20px;margin-right:5px;margin-left:20px;" id="openModalButton" onclick="getModalForEditSalaryProcessData('<?php echo $value['SalaryP_ID']; ?>','<?php echo $Month; ?>','<?php echo $Year; ?>')"><i class="feather icon-edit"></i></a>
                                                                                <!-- <b><?php //echo $value['Executive_Cd']; ?></b> -->
                                                                            </td>
                                                                            <td><b><?php echo $value['ExecutiveName']; ?></b></td>
                                                                            <td><b><?php echo $value['ULB']; ?></b></td>
                                                                            <td><b><?php echo $value['Designation']; ?></b></td>
                                                                            <td><b><?php echo $value['ReferenceName']; ?></b></td>
                                                                            <td><b><?php echo $value['JoiningDate']; ?></b></td>
                                                                            <td><b><?php echo $value['FirstEntryDate']; ?></b></td>
                                                                            <td><b><?php echo $value['Absent']; ?></b></td>
                                                                            <td><b><?php echo $value['HalfDay']; ?></b></td>
                                                                            <td><b><?php echo "<b style='color:black'>".$value['TotalMobileCount']."</b>/<b style='color:green'>".$value['ReceivedMobileNo']."</b>/<b style='color:red'>".$value['WrongMobileNo']."</b>/<b style='color:orange'>".$value['NotConnectedMobileNo']."</b>"; ?></b></td>
                                                                            <!-- <td><b><?php //echo $value['RoomSurveyDone']; ?></b></td> -->
                                                                            <!-- <td><b><?php //echo $value['Average']; ?></b></td> -->
                                                                            <td><b><?php echo IND_money_format($value['Salary']); ?></b></td>
                                                                            <td>
                                                                                <b>
                                                                                    <b style="color:red;"><?php echo IND_money_format(round(($value['Absent'] + (0.5 * $value['HalfDay'])) * ($value['Salary'] / $value['MonthDays']))+$value['AdvanceAmt']+$value['DeductionAmt']); ?></b>
                                                                                </b>
                                                                            </td>
                                                                            <td><b style="color:green;"><?php echo IND_money_format($value['IncentivesAmt']); ?></b></td>
                                                                            <td>
                                                                                <span class="badge badge-success"><b style='font-size:15px;'><?php echo IND_money_format($value['PayableSalary']); ?></b></span>
                                                                            </td>
                                                                            <td>
                                                                                <?php 
                                                                                if($value['PaymentStatus'] != ""){
                                                                                    if($value['PaymentStatus'] == "Paid"){ ?>
                                                                                        <span class="badge badge-success"><b style='font-size:15px;'><?php echo $value['PaymentStatus']; ?></b></span>
                                                                                    <?php }else{ ?>
                                                                                        <span class="badge badge-warning"><b style='font-size:15px;'><?php echo $value['PaymentStatus']; ?></b></span>
                                                                                        <br><b><?php echo $value['PayStatusRemark']; ?></b>
                                                                                    <?php }
                                                                                }?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;"></th>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;" colspan="9">Total</th>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;"><?php echo IND_money_format($SalaryTotal);?></th>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;"><?php echo IND_money_format($DeductionTotal);?></th>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;"><?php echo IND_money_format($IncentivesTotal);?></th>
                                                                    <th style="background-color:#36abb9;color: white;font-size:15px;font-weight:bold;"><?php echo IND_money_format($PayableTotal);?></th>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="tab-pane" id="ReferenceWiseReport" aria-labelledby="ReferenceWiseReport-tab" role="tabpanel">
            <div class="content-body">
                <div id='spinnerLoader2Tab' style='display:none;float:left;'>
                    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="SiteSupervisorWiseDetail" class="SiteSupervisorWiseDetail">
</div>  


<script>
    var Executive_CdArray = [];
    function setSelectedExecutivesExecutive(Executive_Cd){
        var selected = 0;
        var check = Executive_CdArray.includes(Executive_Cd);                                                                   
        if(check == false){
            Executive_CdArray.push(Executive_Cd);
            // selected++;
        }else{
            const index = Executive_CdArray.indexOf(Executive_Cd);
            if (index > -1) {
                Executive_CdArray.splice(index, 1); 
            }
        }
        selected = Executive_CdArray.length;
        document.getElementsByName("SelectedExecutives")[0].value = Executive_CdArray;
        document.getElementById("SelectedExecutiveCds").innerHTML = selected; 

        if(Executive_CdArray != ""){
            $("#UpdateStatusForm").show();
        }else{
            $("#UpdateStatusForm").hide();
        }
    }

    // $('.select2').select2();
</script>

<script>
    document.getElementById("show").addEventListener(
        "click",
        function(event) {
            if (event.target.value === "Hide") {
            event.target.value = "Show";
            } else {
            event.target.value = "Hide";
            }
        },
        false
        );
</script>
<?php 
    sqlsrv_close($conn154);
    //unset($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile']); 
?>