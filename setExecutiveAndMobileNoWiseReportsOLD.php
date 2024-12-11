
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
$MobileSearchExec = "";
$condMobileExeWise = "";
$condExeWise = "";
ini_set('max_execution_time', 600);
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    
    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }

    if(isset($_SESSION['ExecutiveAndMobileNoWise']) && !empty($_SESSION['ExecutiveAndMobileNoWise'])){
        $TabDiv = $_SESSION['ExecutiveAndMobileNoWise'];
    }else{
        $TabDiv = "Executive";
        $_SESSION['ExecutiveAndMobileNoWise'] = $TabDiv;
    }

    $MobileSearchExec = "";
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if  ((isset($_POST['MobileSearchExec']) && !empty($_POST['MobileSearchExec'])))
        {
            $MobileSearchExec = $_POST['MobileSearchExec'];
            if($MobileSearchExec != "0"){
                $condMobileExeWise = "AND MobileNo LIKE '%$MobileSearchExec%'"; 
            }else{
                $condMobileExeWise = "";
            }
    
    }
} 


    $ExecutiveSearchExec = "";
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if  ((isset($_POST['ExecutiveSearchExec']) && !empty($_POST['ExecutiveSearchExec'])))
        {
            $ExecutiveSearchExec = $_POST['ExecutiveSearchExec'];
            if($ExecutiveSearchExec != "0"){
                $condExeWise = "AND ExecutiveName LIKE '%$ExecutiveSearchExec%'"; 
            }else{
                $condExeWise = "";
            }
    }
} 

$MobileSearchMobile = "";
$condMobileWise = "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if  ((isset($_POST['MobileSearchMobile']) && !empty($_POST['MobileSearchMobile'])))
    {
        $MobileSearchMobile = $_POST['MobileSearchMobile'];
        if($MobileSearchMobile != "0"){
            $condMobileWise = "AND MobileNo LIKE '%$MobileSearchMobile%'"; 
        }else{
            $condMobileWise = "";
        }
    }
} 

// Pagination Code -------------------------------------------------------
     
        $TotalPageCounts1 = "";
        $totalRecords = 0;
        $maxPageNo = 0;
        
        $recordPerPage = 30;

        $totalRecords1 = 0;
        $maxPageNo1 = 0;

        $recordPerPage1 = 30;
    
        if(isset($_SESSION['SurveyUtility_Pagination_PageNo']) && !empty($_SESSION['SurveyUtility_Pagination_PageNo'])){
            $pageNo = $_SESSION['SurveyUtility_Pagination_PageNo'];
        }else{
            $pageNo = 1;
            $_SESSION['SurveyUtility_Pagination_PageNo'] = $pageNo;  
        }

        $db1=new DbOperation();

       $querygetCount = "SELECT 
            COUNT(t.MobileNo) as TotalExecutive
            FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS t 
            INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] AS um ON (t.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
            ";
        $ExecutiveTableCountData = $db->ExecutveQuerySingleRowSALData($querygetCount, $userName, $appName, $developmentMode);
        $Count = $ExecutiveTableCountData["TotalExecutive"];
        // $totalRecords = $Count;
        $totalRecords = CEIL($Count/ $recordPerPage);
        // echo "/".$totalDivideIntoPageQuery;
        // Pagination Code -------------------------------------------------------
    

   $ExecutiveQuery = "SELECT um.Executive_Cd,um.ExecutiveName, UpdateByUser, MobileNo, MAX(t.DBName) AS DBName, COUNT(DISTINCT t.FamilyNo) AS FamilyNos, COUNT(*) AS datacnt
        FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS t 
        INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] AS um ON (t.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
        WHERE t.DBName NOT LIKE 'KDMC%' 
        $condMobileExeWise
        $condExeWise
        GROUP BY um.Executive_Cd,um.ExecutiveName, UpdateByUser, MobileNo 
        HAVING COUNT(*) > 4
        ORDER BY um.ExecutiveName ASC, datacnt DESC
        OFFSET ($pageNo - 1) * $recordPerPage ROWS 
        FETCH NEXT $recordPerPage ROWS ONLY;";

$db1=new DbOperation();
$ExecutiveData = $db->ExecutveQueryMultipleRowSALData($ExecutiveQuery, $userName, $appName, $developmentMode);



$db1=new DbOperation();

 $querygetMobileWiseCount = "SELECT 
         COUNT(t.MobileNo) AS TotalMobileWise
    FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS t 
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] AS um ON (t.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
    ";
$MobileTableCountData = $db->ExecutveQuerySingleRowSALData($querygetMobileWiseCount, $userName, $appName, $developmentMode);
$MobileCount = $MobileTableCountData["TotalMobileWise"];
// $totalRecords1 = $MobileCount;
$totalRecords1 = CEIL($MobileCount/ $recordPerPage1);
// echo "/".$totalDivideIntoPageQuery;
// Pagination Code -------------------------------------------------------


 $MobileQuery = "SELECT MobileNo, MAX(t.DBName) AS DBName, COUNT(DISTINCT t.FamilyNo) AS FamilyNos, COUNT(*) AS datacnt, UpdateByUser,um.ExecutiveName
    FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS t 
    INNER JOIN [Survey_Entry_Data].[dbo].[User_Master] as um on (t.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
    WHERE t.DBName NOT LIKE 'KDMC%'
    $condMobileWise
    GROUP BY MobileNo,UpdateByUser,um.ExecutiveName 
    HAVING COUNT(*) > 4
    ORDER BY FamilyNos DESC
    OFFSET ($pageNo - 1) * $recordPerPage1 ROWS 
    FETCH NEXT $recordPerPage1 ROWS ONLY;";

$db2=new DbOperation();
$MobileData = $db2->ExecutveQueryMultipleRowSALData($MobileQuery, $userName, $appName, $developmentMode);

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
</style>
   


<div class="tab-pane" id="home" aria-labelledby="home-tab" role="tabpanel" style="margin-top: -25px;">
    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item">
            <a class="nav-link <?php if($TabDiv == "Executive"){ echo "active"; }else{ echo ""; } ?>" id="ExecutiveWise-tab" data-toggle="tab" href="#ExecutiveWise" aria-controls="ExecutiveWise" role="tab" aria-selected="flase">Executive Wise</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($TabDiv == "Mobile"){ echo "active"; }else{ echo ""; } ?>" id="MobileWise-tab" data-toggle="tab" href="#MobileWise" aria-controls="MobileWise" role="tab" aria-selected="true" onclick="getMobileWiseData()">Mobile No Wise</a>
        </li>
    </ul>
    <div class="tab-content">
        
    <div id='TabLoading' style='display:none'>
            <center>
                <img src='app-assets/images/loader/loading.gif' width="80" height="70"/>
            </center>
        </div>  
        <div class="tab-pane <?php if($TabDiv == "Executive"){ echo "active"; }else{ echo ""; } ?>" id="ExecutiveWise" aria-labelledby="ExecutiveWise-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" >
                                <div class="card-header" style="margin-top: -10px;">
                                    <h4 class="card-title">Executive Wise ( <?php echo $Count;?> ) </h4>
                                    <!-- Pagination Code -->
                                    <div class="pagination-area" style="float:right;">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">

                                                <?php 
                                                    // echo $totalRecords;
                                                    $loopStart = 1;
                                                    $loopStop = $totalRecords;

                                                    if($totalRecords > 5){
                                                            if($pageNo==1){
                                                            $loopStart = $pageNo;
                                                        }else if($pageNo==2){
                                                            $loopStart = $pageNo - 1;
                                                        }else if($pageNo>=3){
                                                            $loopStart = $pageNo - 2;
                                                        }else{
                                                            $loopStart = $pageNo ;
                                                        }
                                                        
                                                        $loopStop = $loopStart + 5;
                                                        if($loopStop>$totalRecords){
                                                            $loopStop = $totalRecords;
                                                            $loopStart = $loopStop - 5;
                                                        }
                                                    }
                                                ?>

                                                <?php
                                                    if($pageNo != $loopStart && $loopStop >5 ){ 
                                                ?>  
                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSession(<?php  if($loopStart==1){ echo '1'; }else{ echo ($loopStart - 1); } ?>)" >Previous</a></li>
                                                <?php } ?>

                                                <?php
                                                    for($i=$loopStart;$i<=$loopStop;$i++){ 

                                                            $activePageCondition = ""; 
                                                            if($pageNo == $i){
                                                                $activePageCondition = "active";                                
                                                            }
                                                        ?>
                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSession(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                <?php } ?>
                                                <?php if($totalRecords > $loopStop){ ?> 
                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSession(<?php echo ($loopStop + 1);  ?>)" >Next</a></li>
                                                <?php }  ?>

                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- Pagination Code -->
                                </div>

                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="row match-height" style="margin-top:-15px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    
                                                    <div class="content-body">
                                                        <table class="table table-hover-animation table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                    <th style="background-color:#36abb9;color: white;">Executive Name
                                                                    <form action="index.php?p=ExecutiveAndMobileNoWise" method="POST">
                                                                            <input type="text" id="SearchTable" name="ExecutiveSearchExec" value="<?php echo $ExecutiveSearchExec; ?>" placeholder="Search Executive" style="width: 50%;">
                                                                            <button type="submit" id="SearchButton" name="SearchButton" value="Search" style="width: 30px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                                        </form>
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                        Mobile No
                                                                        <form action="index.php?p=ExecutiveAndMobileNoWise" method="POST">
                                                                            <input type="text" id="SearchTable" name="MobileSearchExec" value="<?php echo $MobileSearchExec; ?>" placeholder="Search Mobile No" style="width: 50%;">
                                                                            <button type="submit" id="SearchButtonMobile" name="SearchButtonMobile" value="Search" style="width: 30px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                                        </form>
                                                                    </th>
                                                                    <th style="background-color:#36abb9;color: white;">DB Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Family Nos</th>
                                                                    <th style="background-color:#36abb9;color: white;">Mobile No Repeat</th>
                                                                    <th style="background-color:#36abb9;color: white;">View</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                    // if(sizeof($dataSlice)>0){
                                                                    //     $srNo = 1;
                                                                    //     if($page != "1"){
                                                                    //         $srNo = (($page * $recordsPerPage) - ($recordsPerPage));
                                                                    //     }

                                                                    //     foreach($dataSlice AS $key=>$TableDataValue){
                                                                    if(sizeof($ExecutiveData) > 0){
                                                                        $srNo = 1;
                                                                        foreach($ExecutiveData AS $Key=>$value){  
                                                                        ?>
                                                                        <tr>
                                                                            <td><b><?php echo $srNo++; ?></b></td>
                                                                            <td><b><?php echo $value['ExecutiveName']; ?></b></td>
                                                                            <td><b><?php echo $value['MobileNo']; ?></b></td>
                                                                            <td><b><?php echo $value['DBName']; ?></b></td>
                                                                            <td><b><?php echo $value['FamilyNos']; ?></b></td>
                                                                            <td><b><?php echo $value['datacnt']; ?></b></td>
                                                                            <td>
                                                                                <a onclick="getExecutiveWiseDataInForm('<?php echo $value['ExecutiveName']?>','<?php echo $value['MobileNo']?>','<?php echo $value['DBName']?>','<?php echo $value['FamilyNos']?>','<?php echo $value['datacnt']?>','EW')" >
                                                                                    <i class="feather icon-eye" style="font-size: 1.5rem;color:#70ccd4;"></i>
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
                                                    <!-- Pagination Code -->    
                                                    <div class="pagination-area" style="float:right;margin-right:20px;">
                                                        <nav aria-label="Page navigation example">
                                                            <ul class="pagination justify-content-start">

                                                                <?php 
                                                                    // echo $totalRecords;
                                                                    $loopStart = 1;
                                                                    $loopStop = $totalRecords;

                                                                    if($totalRecords > 5){
                                                                         if($pageNo==1){
                                                                            $loopStart = $pageNo;
                                                                        }else if($pageNo==2){
                                                                            $loopStart = $pageNo - 1;
                                                                        }else if($pageNo>=3){
                                                                            $loopStart = $pageNo - 2;
                                                                        }else{
                                                                            $loopStart = $pageNo ;
                                                                        }
                                                                        
                                                                        $loopStop = $loopStart + 5;
                                                                        if($loopStop>$totalRecords){
                                                                            $loopStop = $totalRecords;
                                                                            $loopStart = $loopStop - 5;
                                                                        }
                                                                    }
                                                                ?>

                                                                <?php
                                                                    if($pageNo != $loopStart && $loopStop >5 ){ 
                                                                ?>  
                                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSession(<?php  if($loopStart==1){ echo '1'; }else{ echo ($loopStart - 1); } ?>)" >Previous</a></li>
                                                                <?php } ?>

                                                                <?php
                                                                    for($i=$loopStart;$i<=$loopStop;$i++){ 

                                                                            $activePageCondition = ""; 
                                                                            if($pageNo == $i){
                                                                                $activePageCondition = "active";                                
                                                                            }
                                                                        ?>
                                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSession(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                                <?php } ?>
                                                                <?php if($totalRecords > $loopStop){ ?> 
                                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSession(<?php echo ($loopStop + 1);  ?>)" >Next</a></li>
                                                                <?php }  ?>

                                                            </ul>
                                                        </nav>
                                                    </div>
                                                    <!-- Pagination Code -->
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
        <div class="tab-pane <?php if($TabDiv == "Mobile"){ echo "active"; }else{ echo ""; } ?>" id="MobileWise" aria-labelledby="MobileWise-tab" role="tabpanel">
            
        </div>
    </div>
</div>


<div id="ExecutiveAndMobileWiseModal" class="ExecutiveAndMobileWiseModal">
</div>  
