 
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
// if($CorproationULB !== 'All'){
//     $ULBCondJoin = "INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (t1.Executive_Cd= em.Executive_Cd)
//     INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] AS elm ON (em.ElectionName = elm.ElectionName)";
    
//     $ULBCondJoin2 = "INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (t2.Executive_Cd= em.Executive_Cd)
//     INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] AS elm ON (em.ElectionName = elm.ElectionName)";

//     $UBLwhereCond = "AND elm.ULB = '$CorproationULB'";
// }else{
//     $ULBCondJoin = "";
//     $UBLwhereCond = "";
// }

// if(isset($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile']) && !empty($_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile'])){
//     $SearchedValue = $_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile'];
// }else{
//     $SearchedValue = "";
// }

$searchCondition = "";
$searchCondition2 = "";
$searchCondition3 = "";
// if(!empty($SearchedValue)){

//     if ($SearchedValue == trim($SearchedValue) && strpos($SearchedValue, ' ') !== false) {
//         $strArr = explode(" ", $SearchedValue);
//         foreach($strArr as $value){
//             $searchCondition .= " AND (ExecutiveName like '%$value%' OR Executive_Cd like '%$value%') ";
//             $searchCondition2 .= " AND (t2.ExecutiveName like '%$value%' OR t2.Executive_Cd like '%$value%') ";
//             $searchCondition3 .= " AND (t1.ExecutiveName like '%$value%' OR t1.Executive_Cd like '%$value%') ";
//         }
//     }else{
//             $searchCondition = " AND (ExecutiveName like '%$SearchedValue%' OR Executive_Cd like '%$SearchedValue%') ";
//             $searchCondition2 = " AND (t2.ExecutiveName like '%$SearchedValue%' OR t2.Executive_Cd like '%$SearchedValue%') ";
//             $searchCondition3 = " AND (t1.ExecutiveName like '%$SearchedValue%' OR t1.Executive_Cd like '%$SearchedValue%') ";
//     }
// }

$dataSite = array();
$AttendanceTableData = array();
$data = array();
$totalDays = cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
$PivotColumn = "";
for($Day = 1; $Day<=$totalDays; $Day++){
    $PivotColumn .= '['.$Day.'],';
}
$PivotColumn = substr($PivotColumn,0,-1);



// Attendance Tab --------------------------------------------------------
$AttendanceTab = "";
$AttendanceTabCond = "";
$AttendanceTabColorAll = "";
$AttendanceTabColorActive = "";
$AttendanceTabColorInActive = "";
if(isset($_SESSION['SurveyUA_AttendanceReportTab']) && !empty($_SESSION['SurveyUA_AttendanceReportTab'])){
    $AttendanceTab = $_SESSION['SurveyUA_AttendanceReportTab'];
}else{
    $AttendanceTab = "";
}

if($AttendanceTab == "All"){
    $AttendanceTabColorAll = "background-color:#abdbf5;";
    $AttendanceTabCond = "AND (em.EmpStatus = 'A' OR (em.EmpStatus = 'NA' AND CONVERT(VARCHAR,em.LeavingDate,23) BETWEEN '$Year-$Month-01' AND '$Year-$Month-$totalDays'))";
    $AttendanceTabColorActive = "";
    $AttendanceTabColorInActive ="";

}else if($AttendanceTab == "Active"){
    $AttendanceTabCond = "AND em.EmpStatus = 'A'";
    $AttendanceTabColorActive = "background-color:#c4f5d4;";
    $AttendanceTabColorInActive = "";
    $AttendanceTabColorAll = "";
}else if($AttendanceTab == "InActive"){
    $AttendanceTabCond = "AND em.EmpStatus = 'NA' AND CONVERT(VARCHAR,em.LeavingDate,23) BETWEEN '$Year-$Month-01' AND '$Year-$Month-$totalDays'";
    $AttendanceTabColorActive = "";
    $AttendanceTabColorInActive = "background-color:#facaca;";
    $AttendanceTabColorAll = "";
}else{
    $AttendanceTabCond = "AND (em.EmpStatus = 'A' OR (em.EmpStatus = 'NA' AND CONVERT(VARCHAR,em.LeavingDate,23) BETWEEN '$Year-$Month-01' AND '$Year-$Month-$totalDays'))";
    $AttendanceTabColorActive = "";
    $AttendanceTabColorInActive = "";
    $AttendanceTabColorAll = "background-color:#c4f5d4;";
}
// Attendance Tab --------------------------------------------------------



$AttendanceTableQuery = "SELECT * FROM (
                                    SELECT 
                                    ed.ExecutiveName,
                                    ed.Executive_Cd,
                                    ed.Attendance,
                                    em.Designation,
                                    elm.ULB,
                                    CONVERT(VARCHAR,em.JoiningDate,105) AS JoiningDate,
                                    COALESCE(CONVERT(VARCHAR,em.LeavingDate,105),'') AS LeavingDate,
                                    DATEPART(DAY, ed.SurveyDate) as DaysOfMonth
                                    FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em 
                                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] ed ON (ed.Executive_Cd = em.Executive_Cd)
                                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] elm ON (em.ElectionName = elm.ElectionName)
                                    WHERE DATEPART(YEAR, ed.SurveyDate) = '$Year'
                                    AND DATEPART(MONTH, ed.SurveyDate) = '$Month'
                                    $AttendanceTabCond
                                ) AS source_table
                        PIVOT (
                            MAX(Attendance) FOR DaysOfMonth IN ($PivotColumn)
                        ) AS PivotTable";
$AttendanceTableData = $db->ExecutveQueryMultipleRowSALData($AttendanceTableQuery, $userName, $appName, $developmentMode);



// Attendance Tab --------------------------------------------------------
$AttendanceTableCountData = array();
$CountQuery = "SELECT 
                    (SELECT
                    COUNT(DISTINCT(ed.ExecutiveName)) AS ExeCount
                    FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em 
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] ed ON (ed.Executive_Cd = em.Executive_Cd)
                    WHERE DATEPART(YEAR, ed.SurveyDate) = '$Year'
                    AND DATEPART(MONTH, ed.SurveyDate) = '$Month'
                    AND em.EmpStatus = 'A') AS ActiveCount,
                    (SELECT
                    COUNT(DISTINCT(ed.ExecutiveName)) AS ExeCount
                    FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em 
                    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] ed ON (ed.Executive_Cd = em.Executive_Cd)
                    WHERE DATEPART(YEAR, ed.SurveyDate) = '$Year'
                    AND DATEPART(MONTH, ed.SurveyDate) = '$Month'
                    AND em.EmpStatus = 'NA' AND CONVERT(VARCHAR,em.LeavingDate,23) BETWEEN '$Year-$Month-01' AND '$Year-$Month-$totalDays') AS InActiveCount";
$AttendanceTableCountData = $db->ExecutveQueryMultipleRowSALData($CountQuery, $userName, $appName, $developmentMode);
// print_r($AttendanceTableCountData);

$ActiveCount = 0;
$InActiveCount = 0;
if(sizeof($AttendanceTableCountData)>0){
    $ActiveCount = $AttendanceTableCountData[0]['ActiveCount'];
    $InActiveCount = $AttendanceTableCountData[0]['InActiveCount'];
}else{
    $ActiveCount = 0;
    $InActiveCount = 0;
}
// Attendance Tab --------------------------------------------------------

// echo sizeof($AttendanceTableData);
// echo "<pre>";
// print_r($AttendanceTableData);
// echo "</pre>";
$str = $Year."-".$Month."-";
$a = date('t');
$sundayArr=array();
for($i=1; $i<=$totalDays; $i++)
{
    $d = $str.$i;
    $sun_date = date('Y m D d', $time = strtotime($d) );
    if(strpos($sun_date,'Sun') )
    {
        $date = DateTime::createFromFormat('Y m D d', $sun_date);
        $formatted_date = $date->format('Y-m-d');
        array_push($sundayArr, $formatted_date);
    
    }
}

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

    /* th{
        background: lightgrey;
        border: 1px solid white;
        border-collapse:collapse;
    } */

    .dot {
        height: 15px;
        width: 15px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }
    table.dataTable th, table.dataTable td {
        /* border-bottom: 1px solid #F8F8F8;
        border-top: 0; */
        border: 1px solid #edf5ef;
        border-collapse:collapse;
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

  .datatable-container {
    max-height: 1000px; /* Set a maximum height for the container to enable scrolling */
    overflow-y: auto; /* Enable vertical scrolling */
  }

  .datatable thead {
    position: sticky;
    top: 0;
    z-index: 1;
  }
    .BGColorActive:hover{
    background-color:#c4f5d4;  
  }
  .BGColorInActive:hover{
    background-color:#facaca;
  }
</style>

<!-- <div class="tab-pane" id="home" aria-labelledby="home-tab" role="tabpanel" style="margin-top: -25px;">
    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item">
            <a class="nav-link active" id="SalaryGeneration-tab" data-toggle="tab" href="#SalaryGeneration" aria-controls="SalaryGeneration" role="tab" aria-selected="flase">Attedance Report</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="SalaryGeneration" aria-labelledby="SalaryGeneration-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable"> -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="row match-height" style="margin-bottom: -20px;">
                                    <div class="col-md-6">
                                        <div class="row" style="margin-left: 12px;">
                                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                                <div class="form-group">
                                                    <div class="controls" style="margin-top:10px">
                                                        <h4>
                                                            Attendance - <?php echo date('F', mktime(0, 0, 0, $Month, 1))." ".$Year ; ?>
                                                            <?php echo " :: Total Executives - ".sizeof($AttendanceTableData);?>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:block" id="UpdateStatusForm">
                                        <div class="row" style="margin-bottom: 0px;">
                                            <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                                                <div id='spinnerLoader2' style='display:none;float:right;margin-top:8px;'>
                                                    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                                <div class="form-group">
                                                    <div class="controls" style="margin-top:10px">
                                                        <select class="select2 form-control" name="Month" onchange="setMonthInSessionFromAttedanceReport(this.value);">
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
                                                    <div class="controls" style="margin-top:10px">
                                                        <select class="select2 form-control" name="Month" onchange="setYearInSessionFromAttendanceReport(this.value);">
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
                                                <button id="exportBtn1" style="padding:7px;margin-top:15px;" class="btn btn-primary" onclick="ExportToExcel('xlsx','AttendanceReportTableID')">Excel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row match-height" style="margin-bottom: -20px;">
                                    <div class="col-md-12">
                                        <div class="row" style="margin-left: 12px;">
                                            <div class="col-xs-4 col-xl-4 col-md-12 col-12">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <button style="padding:7px;color:#147bb3;<?php echo $AttendanceTabColorAll; ?>" onclick="setActiveInActiveTabSessionFromAttedanceReport('All');" class="btn BGColorActive" >All</button>
                                                        <button style="padding:7px;color:green;<?php echo $AttendanceTabColorActive; ?>" onclick="setActiveInActiveTabSessionFromAttedanceReport('Active');" class="btn BGColorActive" 
                                                            <?php if($ActiveCount == "0"){ echo "disabled";} ?>>Active <b>(<?php echo $ActiveCount; ?>)</b></button>
                                                        <button style="padding:7px;color:red;<?php echo $AttendanceTabColorInActive; ?>"  onclick="setActiveInActiveTabSessionFromAttedanceReport('InActive');" class="btn BGColorInActive"
                                                        <?php if($InActiveCount == "0"){ echo "disabled";} ?> >In Active <b>(<?php echo $InActiveCount; ?>)</b></button>
                                                    </div>
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
                                                    <div class="content-body datatable-container" style="overflow:scroll;">
                                                        <table class="table table-hover-animation table-hover table-striped datatable" id="AttendanceReportTableID" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;"></th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Executive Name" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column1SearchName" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="Designation" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column2SearchULB" />
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        <input class="form-control" placeholder="ULB" style="padding: 0.2rem 0.2rem;height: 25px;line-height: 1.00;" type="text" id="column3SearchULB" />
                                                                    </th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">J Date</th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">L Date</th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">P</th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">A</th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">H</th>
                                                                    <th rowspan="2" style="vertical-align: middle;text-align: center;background-color:#36abb9;color: white;">T</th>
                                                                    <?php
                                                                        $daysArray = [];
                                                                        for ($dayTH = 1; $dayTH <= $totalDays; $dayTH++) {
                                                                            $dateString = "{$Year}-{$Month}-{$dayTH}";
                                                                            $dateTime = new DateTime($dateString);

                                                                            if($dateTime->format('m') != $Month) {
                                                                                break;
                                                                            }

                                                                            $dayName = $dateTime->format('D');
                                                                            $daysArray[] = $dayName;
                                                                        }
                                                                        foreach($daysArray AS $daysArrayLoop){
                                                                        ?>
                                                                            <th style="vertical-align: middle;background-color:#36abb9;color: white;">
                                                                                <?php echo substr($daysArrayLoop,0,-2); ?>
                                                                            </th>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">No</th>
                                                                    <th style="background-color:#36abb9;color: white;">Executive</th>
                                                                    <th style="margin-left: 20px;background-color:#36abb9;color: white;">Designation</th>
                                                                    <th style="margin-left: 20px;background-color:#36abb9;color: white;">ULB</th>
                                                                    <?php
                                                                        for($i = 1; $i<=$totalDays; $i++){
                                                                    ?>
                                                                            <th style="background-color:#36abb9;color: white;"><?php echo $i; ?></th>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                $SrNo = 0;
                                                                $TotalPresentDays = 0;
                                                                $TotalAbsentDays = 0;
                                                                $TotalHFDays = 0;
                                                                $TotalTDays = 0;
                                                                $CompareDate = "";
                                                                if(sizeof($AttendanceTableData)>0){
                                                                    foreach($AttendanceTableData AS $key=>$value){
                                                                        $SrNo = $SrNo + 1;
                                                                        $TotalPresentDays = 0;
                                                                        $TotalAbsentDays = 0;
                                                                        $TotalHFDays = 0;
                                                                        $TotalTDays = 0;
                                                                        $AttendanceVal = "";
                                                                        $CompareDate = "";
                                                                        $backgroundColor = "";
                                                                        for($Tc=1; $Tc <= $totalDays; $Tc++){
                                                                            
                                                                            if($value["$Tc"] == 1){
                                                                                $TotalPresentDays = $TotalPresentDays + 1;
                                                                            }else if($value["$Tc"] == 2){
                                                                                $TotalAbsentDays = $TotalAbsentDays + 1;
                                                                            }else if($value["$Tc"] == 3){
                                                                                $TotalHFDays = $TotalHFDays + 1;
                                                                            }else if($value["$Tc"] == 4){
                                                                                $TotalTDays = $TotalTDays + 1;
                                                                            }
                                                                        }
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $SrNo;?></td>
                                                                        <td><b><?php echo $value['ExecutiveName'];?></b></td>
                                                                        <td><?php echo $value['Designation'];?></td>
                                                                        <td><?php echo $value['ULB'];?></td>
                                                                        <td style="font-weight:bold;"><?php echo $value['JoiningDate']; ?></td>
                                                                        <td style="font-weight:bold;"><?php echo $value['LeavingDate']; ?></td>
                                                                        <td style="font-size:15px;color:black;font-weight:bold;"><?php echo $TotalPresentDays; ?></td>
                                                                        <td style="font-size:15px;color:black;font-weight:bold;"><?php echo $TotalAbsentDays; ?></td>
                                                                        <td style="font-size:15px;color:black;font-weight:bold;"><?php echo $TotalHFDays; ?></td>
                                                                        <td style="font-size:15px;color:black;font-weight:bold;"><?php echo $TotalTDays; ?></td>
                                                                        <?php
                                                                        for($Att = 1; $Att<=$totalDays; $Att++){

                                                                            if($value["$Att"] == 1){
                                                                                $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-success'>P</span>";
                                                                            }else if($value["$Att"] == 2){
                                                                                $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-danger'>A</span>";
                                                                            }else if($value["$Att"] == 3){
                                                                                $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-secondary'>HF</span>";
                                                                            }else if($value["$Att"] == 4){
                                                                                $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-warning'>T</span>";
                                                                            }else{
                                                                                //$AttendanceVal = "-";
                                                                                $GetCurrentMonth = "";
                                                                                $GetCurrentMonth = date('m');

                                                                                if($Month == $GetCurrentMonth){
                                                                                    $AttendanceVal = "-";
                                                                                }else{
                                                                                    //$AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-danger'>A</span>";
                                                                                    $AttendanceVal = "-";
                                                                                }
                                                                            }

                                                                            if($Att < 10){
                                                                                $CheckDay = "0".$Att;
                                                                            }else{
                                                                                $CheckDay = $Att;
                                                                            }
                                                                            $CompareDate = $CheckDay."-".$Month."-".$Year;
                                                                            $date1 = new DateTime($CompareDate);
                                                                            $date2 = new DateTime($value['LeavingDate']);
                                                                           

                                                                            $formattedDate = sprintf("%04d-%02d-%02d", $Year, $Month, $Att);
                                                                            $dayName = date('D', strtotime($formattedDate));
                                                                            if(in_array($formattedDate,$sundayArr)){
                                                                                if($value["$Att"] == 1){
                                                                                    $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-success'>P</span>";
                                                                                }else if($value["$Att"] == 2){
                                                                                    $AttendanceVal = "<span style='font-weight:bold;font-size:13px;' class='badge badge-danger'>A</span>";
                                                                                }else{
                                                                                    $AttendanceVal ="<span class='badge badge-primary'>WO</span>";
                                                                                }
                                                                            }

                                                                            if($value['LeavingDate'] != ''){
                                                                                if($date2 <= $date1){
                                                                                    $backgroundColor = "style='background-color:#fcbbbb;border: 1px solid #f59898;'";
                                                                                    $AttendanceVal = "";
                                                                                }else{
                                                                                    $backgroundColor = "";
                                                                                }
                                                                            }else{
                                                                                $backgroundColor = "";
                                                                            }
                                                                        ?>
                                                                            <td <?php echo $backgroundColor; ?>><?php echo $AttendanceVal; ?></td>
                                                                        <?php
                                                                        }
                                                                        ?>
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
                            </div>
                        </div>
                    </div>
                <!-- </section>
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
</div> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>    
      $(document).ready(function () {
          $('#AttendanceReportTableID').DataTable({
            "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
          columns: [
            { searchable: false },
            { searchable: true },
            { searchable: true },
            { searchable: true },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            <?php 
            for ($scr = 1; $scr <= $totalDays; $scr++) {
            ?>
                { searchable: false },
            <?php
            }
            ?>
          ]
          });        
        });

        $(document).ready(function() {
            var dataTable = $('#AttendanceReportTableID').DataTable();
            $('#column1SearchName').on('keyup', function() {
                dataTable.column(1).search(this.value).draw();
            });
            $('#column2SearchULB').on('keyup', function() {
                dataTable.column(2).search(this.value).draw();
            });
            $('#column3SearchULB').on('keyup', function() {
                dataTable.column(3).search(this.value).draw();
            });
        });
</script>
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