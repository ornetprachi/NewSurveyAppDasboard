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

    $MobileSearchMobile = "";
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

    
    $querygetMobileWiseCount = "SELECT 
    COUNT(t.MobileNo) AS TotalMobileWise
    FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS t 
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] AS um ON (t.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
    ";
    $MobileTableCountData = $db->ExecutveQuerySingleRowSALData($querygetMobileWiseCount, $userName, $appName, $developmentMode);
    $MobileCount = $MobileTableCountData["TotalMobileWise"];
    // $totalRecords1 = $MobileCount;
    $totalRecords1 = CEIL($MobileCount/ $recordPerPage1);

 ?>
<div class="content-body" id="Mobiledata" style="display:none;">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="margin-top: -10px;">
                                    <h4 class="card-title">Mobile No wise ( <?php echo $MobileCount;?> )</h4> 
                                    <!-- Pagination Code -->
                                    <div class="pagination-area" style="float:right;">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-start">

                                                <?php 
                                                    // echo $totalRecords1;
                                                    $loopStart1 = 1;
                                                    $loopStop1 = $totalRecords1;

                                                    if($totalRecords1 > 5){
                                                            if($pageNo==1){
                                                            $loopStart1 = $pageNo;
                                                        }else if($pageNo==2){
                                                            $loopStart1 = $pageNo - 1;
                                                        }else if($pageNo>=3){
                                                            $loopStart1 = $pageNo - 2;
                                                        }else{
                                                            $loopStart1 = $pageNo ;
                                                        }
                                                        
                                                        $loopStop1 = $loopStart1 + 5;
                                                        if($loopStop1>$totalRecords1){
                                                            $loopStop1 = $totalRecords1;
                                                            $loopStart1 = $loopStop1 - 5;
                                                        }
                                                    }
                                                ?>

                                                <?php
                                                    if($pageNo != $loopStart1 && $loopStop1 >5 ){ 
                                                ?>  
                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSession(<?php  if($loopStart1==1){ echo '1'; }else{ echo ($loopStart1 - 1); } ?>)" >Previous</a></li>
                                                <?php } ?>

                                                <?php
                                                    for($i=$loopStart1;$i<=$loopStop1;$i++){ 

                                                            $activePageCondition = ""; 
                                                            if($pageNo == $i){
                                                                $activePageCondition = "active";                                
                                                            }
                                                        ?>
                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSession(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                <?php } ?>
                                                <?php if($totalRecords1 > $loopStop1){ ?> 
                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSession(<?php echo ($loopStop1 + 1);  ?>)" >Next</a></li>
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
                                                        <table class="table table-hover-animation table-hover table-striped" >
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                    <th style="background-color:#36abb9;color: white;">Mobile No
                                                                        <form action="index.php?p=ExecutiveAndMobileNoWise" method="POST">
                                                                                <input type="text" id="MobileSearchMobile" name="MobileSearchMobile" value="<?php echo $MobileSearchMobile; ?>" placeholder="Search Mobile" style="width: 40%;">
                                                                                <button type="submit" id="SearchButtonMobileWise" name="SearchButtonMobileWise" value="Search" style="width: 30px;"><i class="fa fa-search" aria-hidden="true"></i></button>
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
                                                                    if(sizeof($MobileData) > 0){
                                                                        $srNo = 1;
                                                                        foreach($MobileData AS $Key=>$value){  
                                                                ?>
                                                                        <tr>
                                                                            <td><b><?php echo $srNo++; ?></b></td>
                                                                            <td><b><?php echo $value['MobileNo']; ?></b></td>
                                                                            <td><b><?php echo $value['DBName']; ?></b></td>
                                                                            <td><b><?php echo $value['FamilyNos']; ?></b></td>
                                                                            <td><b><?php echo $value['datacnt']; ?></b></td>
                                                                            <td>
                                                                                <a onclick="getMobileNoWiseDataInForm('<?php echo $value['MobileNo']?>','<?php echo $value['ExecutiveName']?>','<?php echo $value['DBName']?>','<?php echo $value['FamilyNos']?>','<?php echo $value['datacnt']?>','MW')" >
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
                                                                    // echo $totalRecords1;
                                                                    $loopStart1 = 1;
                                                                    $loopStop1 = $totalRecords1;

                                                                    if($totalRecords1 > 5){
                                                                         if($pageNo==1){
                                                                            $loopStart1 = $pageNo;
                                                                        }else if($pageNo==2){
                                                                            $loopStart1 = $pageNo - 1;
                                                                        }else if($pageNo>=3){
                                                                            $loopStart1 = $pageNo - 2;
                                                                        }else{
                                                                            $loopStart1 = $pageNo ;
                                                                        }
                                                                        
                                                                        $loopStop1 = $loopStart1 + 5;
                                                                        if($loopStop1>$totalRecords1){
                                                                            $loopStop1 = $totalRecords1;
                                                                            $loopStart1 = $loopStop1 - 5;
                                                                        }
                                                                    }
                                                                ?>

                                                                <?php
                                                                    if($pageNo != $loopStart1 && $loopStop1 >5 ){ 
                                                                ?>  
                                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSession(<?php  if($loopStart1==1){ echo "1"; }else{ echo ($loopStart1 - 1); } ?>)" >Previous</a></li>
                                                                <?php } ?>

                                                                <?php
                                                                    for($i=$loopStart1;$i<=$loopStop1;$i++){ 

                                                                            $activePageCondition = ""; 
                                                                            if($pageNo == $i){
                                                                                $activePageCondition = "active";                                
                                                                            }
                                                                        ?>
                                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSession(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                                <?php } ?>
                                                                <?php if($totalRecords1 > $loopStop1){ ?> 
                                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSession(<?php echo ($loopStop1 + 1);  ?>)" >Next</a></li>
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