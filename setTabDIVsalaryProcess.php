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


if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    
  if(
        isset($_GET['TabName']) && !empty($_GET['TabName']) &&
        isset($_GET['Month']) && !empty($_GET['Month']) &&
        isset($_GET['Year']) && !empty($_GET['Year'])
    ){
    
    try  
        {  
            $TabName = $_GET['TabName'];
            $Month = $_GET['Month'];
            $Year = $_GET['Year'];

            // if(isset($_GET['ULBCondJoin2']) && !empty($_GET['ULBCondJoin2'])){
            //     $ULBCondJoin2 = $_GET['ULBCondJoin2'];
            // }else{
            //     $ULBCondJoin2 = "";
            // }

            // if(isset($_GET['ULBCondJoin']) && !empty($_GET['ULBCondJoin'])){
            //     $ULBCondJoin = $_GET['ULBCondJoin'];
            // }else{
            //     $ULBCondJoin = "";
            // }

            // if(isset($_GET['searchCondition3']) && !empty($_GET['searchCondition3'])){
            //     $searchCondition3 = $_GET['searchCondition3'];
            // }else{
            //     $searchCondition3 = "";
            // }
            // if(isset($_GET['DesignationCond']) && !empty($_GET['DesignationCond'])){
            //     $DesignationCond = $_GET['DesignationCond'];
            // }else{
            //     $DesignationCond = "";
            // }

            // if(isset($_GET['ReferenceCond']) && !empty($_GET['ReferenceCond'])){
            //     $ReferenceCond = $_GET['ReferenceCond'];
            // }else{
            //     $ReferenceCond = "";
            // }
            // if(isset($_GET['PaymentStatusCond']) && !empty($_GET['PaymentStatusCond'])){
            //     $PaymentStatusCond = $_GET['PaymentStatusCond'];
            // }else{
            //     $PaymentStatusCond = "";
            // }

            // if(isset($_GET['UBLwhereCond']) && !empty($_GET['UBLwhereCond'])){
            //     $UBLwhereCond = $_GET['UBLwhereCond'];
            // }else{
            //     $UBLwhereCond = "";
            // }
            // if(isset($_GET['searchCondition2']) && !empty($_GET['searchCondition2'])){
            //     $searchCondition2 = $_GET['searchCondition2'];
            // }else{
            //     $searchCondition2 = "";
            // }

            // Filters ------------------------------------------------------------------------------------------------
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
                            // $searchCondition .= " AND (ExecutiveName like '%$value%' OR Executive_Cd like '%$value%') ";
                            $searchCondition2 .= " AND (t2.ExecutiveName like '%$value%' OR t2.Executive_Cd like '%$value%') ";
                            $searchCondition3 .= " AND (t1.ExecutiveName like '%$value%' OR t1.Executive_Cd like '%$value%') ";
                        }
                    }else{
                            // $searchCondition = " AND (ExecutiveName like '%$SearchedValue%' OR Executive_Cd like '%$SearchedValue%') ";
                            $searchCondition2 = " AND (t2.ExecutiveName like '%$SearchedValue%' OR t2.Executive_Cd like '%$SearchedValue%') ";
                            $searchCondition3 = " AND (t1.ExecutiveName like '%$SearchedValue%' OR t1.Executive_Cd like '%$SearchedValue%') ";
                    }
                }
            // Filters ------------------------------------------------------------------------------------------------

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
            $ReferenceWiseData = array();
            if($data['Flag'] == "YES"){
                $ReferenceWiseQuery = "SELECT
                                        COALESCE(t1.ReferenceName,'NA') AS ReferenceName,
                                        SUM(t1.Salary) AS Salary,
                                        FORMAT(SUM(t1.PayableSalary), 'N2', 'en-IN') AS PayableSalary,
                                        COUNT(t1.SalaryP_ID) AS TotalExecutives,
                                        COALESCE((
                                            SELECT 
                                            COALESCE(t2.SalaryP_ID,0) AS SalaryP_ID,
                                            COALESCE(t2.Executive_Cd,0) AS Executive_Cd,
                                            COALESCE(t2.ExecutiveName,'') AS ExecutiveName,
                                            COALESCE(t2.Designation,'') AS Designation,
                                            COALESCE(CONVERT(VARCHAR,t2.JoiningDate,105),'') AS JoiningDate,
                                            COALESCE(CONVERT(VARCHAR,t2.FirstEntryDate,105),'') AS FirstEntryDate,
                                            COALESCE(t2.RoomSurveyDone,0) AS RoomSurveyDone,
                                            COALESCE(t2.Average,0) AS Average,
                                            COALESCE(t2.Salary,0) AS Salary,
                                            COALESCE(t2.PayableSalary,0) AS PayableSalary,
                                            COALESCE(t2.PaymentStatus,'') AS PaymentStatus,
                                            COALESCE(t2.PayStatusRemark,'') AS PayStatusRemark,
                                            COALESCE(elm.ULB,'') AS ULB
                                            FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] AS t2
                                            INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master] em ON (t2.Executive_Cd = em.Executive_Cd)
                                            INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master] elm ON (em.ElectionName = elm.ElectionName)
                                            WHERE t2.ReferenceName = t1.ReferenceName
                                            $UBLwhereCond
                                            $searchCondition2
                                            ORDER BY ExecutiveName
                                            FOR JSON PATH
                                        ),'') AS SubDataExecutives
                                        FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] AS t1
                                        $ULBCondJoin
                                        WHERE t1.RoomSurveyDone IS NOT NULL
                                        $searchCondition3
                                        $DesignationCond
                                        $ReferenceCond
                                        $PaymentStatusCond
                                        $UBLwhereCond
                                        GROUP BY t1.ReferenceName;";
                                        
                $ReferenceWiseData = $db->ExecutveQueryMultipleRowSALData($ReferenceWiseQuery, $userName, $appName, $developmentMode);
                // print_r($ReferenceWiseData);
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
   

   <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="margin-top: -10px;">
                        <h4 class="card-title">Reference Wise Report : <?php echo date('F', mktime(0, 0, 0, $Month, 1))." ".$Year ; ?></h4> 
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="row match-height" style="margin-top:-15px;">
                                <div class="col-md-12" style="margin-bottom: -40px;">
                                    <div class="card">
                                        <div class="content-body" style="overflow:scroll;">
                                            <table class="table table-hover-animation table-hover table-striped" id="referenceWiseTable" style="width:100%">
                                                <thead>
                                                    <!-- <tr>
                                                        <th style="margin-left: 20px;background-color:#36abb9;color: white;"></th>
                                                        <th colspan="6" style="margin-left: 20px;background-color:#36abb9;color: white;">Referece Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Salary</th>
                                                        <th style="background-color:#36abb9;color: white;">Payable Salary</th>
                                                        <th style="background-color:#36abb9;color: white;"></th>
                                                    </tr> -->
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;"></th>
                                                        <th colspan="5" style="background-color:#36abb9;color: white;">Reference Name</th>
                                                        <th style="margin-left: 20px;background-color:#36abb9;color: white;">Total Executives</th>
                                                        <!--<th style="background-color:#36abb9;color: white;">Designation</th>
                                                        <th style="background-color:#36abb9;color: white;">Joining Date</th>
                                                        <th style="background-color:#36abb9;color: white;">Room Survey Done</th>
                                                        <th style="background-color:#36abb9;color: white;">Average</th> -->
                                                        <th style="background-color:#36abb9;color: white;">Total Salary</th>
                                                        <th style="background-color:#36abb9;color: white;">Payable Salary</th>
                                                        <th style="background-color:#36abb9;color: white;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $srNoFirst = 0;
                                                    $SubDataExecutivesArr = array();
                                                    if(sizeof($ReferenceWiseData)>0){
                                                        foreach($ReferenceWiseData AS $key1=>$value2){
                                                            $srNoFirst = $srNoFirst +1;
                                                            $SubDataExecutives = $value2['SubDataExecutives'];
                                                            $SubDataExecutivesArr = json_decode($SubDataExecutives, true);
                                                    ?>
                                                        <tr style="">
                                                            <td  style="margin-left:10px;" <?php if(!empty($SubDataExecutivesArr)){ ?> class="btn btn-primary" <?php } ?>>
                                                                <?php if(!empty($SubDataExecutivesArr)){ ?> <i style="cursor: pointer;" class="fa fa-plus" aria-hidden="true" onclick="showAndHideInnerDiv('<?php echo $srNoFirst;?>');"></i> <?php } ?>
                                                            </td>
                                                            <td colspan="5"><b style='color:black'><?php echo $value2['ReferenceName']; ?></b></td>
                                                            <td><b style='color:black'><?php echo $value2['TotalExecutives']; ?></b></td>
                                                            <td><b style='color:black'><?php echo IND_money_format($value2['Salary']); ?></b></td>
                                                            <td><b style='color:black'><?php echo $value2['PayableSalary']; ?></b></td>
                                                            <td><?php echo ""; ?></td>
                                                        </tr>
                                                        <?php 
                                                        $row = 0;
                                                        $srNo = 0;
                                                        $backgroundcolor = "";
                                                        if(!empty($SubDataExecutivesArr)){
                                                        ?>
                                                            <td colspan="10" style="width:100%;" id="container">
                                                                <table class="table table-hover-animation table-hover table-striped zero-configuration" style="border-collapse: collapse;border: 1px solid #adadad;width:100%;display:none;" id="InnerDiv_<?php echo $srNoFirst;?>">
                                                                    <tr>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:3%;background-color:#36b9aa;color: white;">Sr No</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:7%;background-color:#36b9aa;color: white;">Executive Cd</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:25%;margin-left: 20px;background-color:#36b9aa;color: white;">Executive Name</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:8%;margin-left: 20px;background-color:#36b9aa;color: white;">ULB</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:12%;background-color:#36b9aa;color: white;">Designation</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:10%;background-color:#36b9aa;color: white;">Joining Date</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:10%;background-color:#36b9aa;color: white;">Room Survey Done</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:5%;background-color:#36b9aa;color: white;">Average</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:9%;background-color:#36b9aa;color: white;">Salary</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:9%;background-color:#36b9aa;color: white;">Payable Salary</th>
                                                                        <th style="border-collapse: collapse;border: 1px solid #adadad;width:30%;background-color:#36b9aa;color: white;">Status</th>
                                                                    </tr>
                                                                    <?php
                                                                        foreach($SubDataExecutivesArr AS $innerKey => $innerValue){
                                                                            $srNo = $srNo + 1;
                                                                            $division = $srNo % 2;
                                                                            if($division == 0){
                                                                                $backgroundcolor = "background-color:#ECEDED;";
                                                                            }else{
                                                                                $backgroundcolor = "background-color:#FFFFFF;";
                                                                            }
                                                                    ?>
                                                                        <tr style="<?php echo $backgroundcolor; ?>color:black;">
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $srNo; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['Executive_Cd']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['ExecutiveName']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['ULB']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['Designation']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['JoiningDate']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['RoomSurveyDone']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo $innerValue['Average']; ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo number_format($innerValue['Salary'],2); ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;"><?php echo number_format($innerValue['PayableSalary'],2); ?></td>
                                                                            <td style="border-collapse: collapse;border: 1px solid #adadad;">
                                                                                <?php 
                                                                                if($innerValue['PaymentStatus'] != ""){
                                                                                    if($innerValue['PaymentStatus'] == "Paid"){ ?>
                                                                                        <span class="badge badge-success"><b style='font-size:15px;'><?php echo $innerValue['PaymentStatus']; ?></b></span>
                                                                                    <?php }else{ ?>
                                                                                        <span class="badge badge-warning"><b style='font-size:15px;'><?php echo $innerValue['PaymentStatus']; ?></b></span>
                                                                                        <b><?php echo $innerValue['PayStatusRemark']; ?></b>
                                                                                    <?php }
                                                                                }?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </table>
                                                            </td>
                                                        <?php
                                                        }
                                                        ?>
                                                        
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
    </section>