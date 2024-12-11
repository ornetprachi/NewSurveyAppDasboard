<?php
session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
ini_set('max_execution_time', 600);
$ULB=$_SESSION['SurveyUtility_ULB'];
// $DBName = $_SESSION['SurveyUtility_DBName'];
$ExecutiveName = "";
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    
    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }
$MobileNo = "";


if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['ExecutiveName']) && !empty($_GET['ExecutiveName'])
    && isset($_GET['flag']) && !empty($_GET['flag']) && $_GET['flag'] == 'EW')
   {
    
    try  
        {  
            $_SESSION['Survey_Utility_ExecutiveName']  = $_GET['ExecutiveName'];
            $_SESSION['Survey_Utility_MobileNo']  = $_GET['MobileNo'];
            $ExecutiveName = $_SESSION['Survey_Utility_ExecutiveName'];
            $MobileNo = $_SESSION['Survey_Utility_MobileNo'];

            $DBName = $_GET['DBName'];
            $FamilyNos = $_GET['FamilyNos'];
            $datacnt = $_GET['datacnt'];
            $flag = $_GET['flag'];


        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
         
  }else if(isset($_GET['MobileNo']) && !empty($_GET['MobileNo'])
  && isset($_GET['flag']) && !empty($_GET['flag']) && $_GET['flag'] == 'MW')
   {
    
    try  
        {  
            $_SESSION['Survey_Utility_MobileNo']  = $_GET['MobileNo'];
            $MobileNo = $_SESSION['Survey_Utility_MobileNo'];

            $ExecutiveName1 = $_GET['ExecutiveName'];
            $DBName = $_GET['DBName'];
            $FamilyNos = $_GET['FamilyNos'];
            $datacnt = $_GET['datacnt'];
            $flag = $_GET['flag'];


        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
         
  }
}
  

        $MobileSearch = "";
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            if  ((isset($_POST['MobileSearch']) && !empty($_POST['MobileSearch'])))
            {
                $MobileSearch = $_POST['MobileSearch'];
                if($MobileSearch != "0"){
                    $condMobileNoWise = "WHERE MobileNo LIKE '%$MobileSearch%'"; 
                }else{
                    $condMobileNoWise = "";
                }
        

        echo $query = "SELECT  
            COALESCE(AMD.MobileNo,'') AS MobileNo
           ,COALESCE(AMD.AC_No,0) AS AC_No
           ,COALESCE(AMD.List_No,0) AS List_No
           ,COALESCE(AMD.Voter_Id,0) AS Voter_Id
           ,COALESCE(AMD.FamilyNo,'') AS FamilyNo
           ,COALESCE(AMD.SubLocation_Cd,0) AS SubLocation_Cd
           ,COALESCE(AMD.SiteName,'') AS SiteName
           ,COALESCE(AMD.IdCard_No,'') AS IdCard_No
           ,COALESCE(AMD.QC_Calling_Status_Cd,'') AS QC_Calling_Status_Cd
           ,COALESCE(AMD.UpdateByUser,'') AS UpdateByUser
           ,CONVERT(VARCHAR,AMD.UpdatedDate, 29) AS UpdatedDate
           ,COALESCE(AMD.DBName,'') AS DBName
           ,COALESCE(AMD.FullName,'') AS FullName
           ,COALESCE(AMD.SocietyName,'') AS SocietyName
           ,COALESCE(AMD.RoomNo,'') AS RoomNo
           ,COALESCE(AMD.Gender,'') AS Gender
           ,COALESCE(AMD.Age,0) AS Age
       FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS AMD
       INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] as um on (AMD.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
       $condMobileNoWise
       ORDER BY AMD.UpdatedDate ASC;";
                
        $data = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

        }
    } 
?>

<style>
  .modal-content {
      width: auto;
      border-radius: 0.5rem;
      overflow: hidden;
      border: none;
      box-shadow: 0 0 20px 0 rgb(0 0 0 / 10%);
      position: absolute;
      /* left: 100%; */
      top: 1%; 
      margin-left: 10px;
      /* margin-top: -320px; */
  }
</style>


<?php 
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    
if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
}
else{
    $ServerIP ="103.14.99.154";
}

    
    $query ="SELECT DISTINCT DBName
        FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] 
        WHERE MobileNo = '$MobileNo'";
    
    $db1=new DbOperation();
    $AllMobileMergeData = $db1->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

foreach($AllMobileMergeData AS $key =>$value){
        // $dbName = $value['DBName'];
       $query1 = "SELECT * FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Election_Master]
            WHERE DBName = '$DBName';";

    $db2=new DbOperation();
    $ElectionMasterData = $db2->ExecutveQuerySingleRowSALData($query1, $userName, $appName, $developmentMode);
    // print_r($ElectionMasterData);

    if(sizeof($ElectionMasterData)>0){
        $ServerName = $ElectionMasterData['ServerName'];
        $ServerId = $ElectionMasterData['ServerId'];
        $ServerPwd = $ElectionMasterData['ServerPwd'];
    }

    if($flag == 'EW'){
        $con = "WHERE ExecutiveName = '$ExecutiveName' AND MobileNo = '$MobileNo'";
    }else if($flag == 'MW'){
        $con = "WHERE MobileNo = '$MobileNo'";
    }else{
        $con = "";
    }
    
    $TotalPageCounts1 = "";
    $totalRecords = 0;
    $maxPageNo = 0;
    
    $recordPerPage = 30;


    if(isset($_SESSION['SurveyUtility_Pagination_PageNo']) && !empty($_SESSION['SurveyUtility_Pagination_PageNo'])){
        $pageNo = $_SESSION['SurveyUtility_Pagination_PageNo'];
    }else{
        $pageNo = 1;
        $_SESSION['SurveyUtility_Pagination_PageNo'] = $pageNo;  
    }

    $db1=new DbOperation();

    $querygetCount = "SELECT  
        ISNULL(COUNT(AMD.MobileNo), '') AS TotalExecutiveAndMobileWise
    FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS AMD
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] as um on (AMD.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
    $con";

    $TableCountData = $db->ExecutveQuerySingleRowSALData($querygetCount, $userName, $appName, $developmentMode);
    $CountTotalExecutiveAndMobileWise = $TableCountData["TotalExecutiveAndMobileWise"];
    // $totalRecords = $Count;
    $totalRecords = CEIL($CountTotalExecutiveAndMobileWise/ $recordPerPage);
    // echo "/".$totalDivideIntoPageQuery;
    // Pagination Code -------------------------------------------------------


    $ExecutiveQuery = "SELECT  
        COALESCE(AMD.MobileNo,'') AS MobileNo
        ,COALESCE(AMD.AC_No,0) AS AC_No
        ,COALESCE(AMD.List_No,0) AS List_No
        ,COALESCE(AMD.Voter_Id,0) AS Voter_Id
        ,COALESCE(AMD.FamilyNo,'') AS FamilyNo
        ,COALESCE(AMD.SubLocation_Cd,0) AS SubLocation_Cd
        ,COALESCE(AMD.SiteName,'') AS SiteName
        ,COALESCE(AMD.IdCard_No,'') AS IdCard_No
        ,COALESCE(AMD.QC_Calling_Status_Cd,'') AS QC_Calling_Status_Cd
        ,COALESCE(AMD.UpdateByUser,'') AS UpdateByUser
        ,CONVERT(VARCHAR,AMD.UpdatedDate, 29) AS UpdatedDate
        ,COALESCE(AMD.DBName,'') AS DBName
        ,COALESCE(AMD.FullName,'') AS FullName
        ,COALESCE(AMD.SocietyName,'') AS SocietyName
        ,COALESCE(AMD.RoomNo,'') AS RoomNo
        ,COALESCE(AMD.Gender,'') AS Gender
        ,COALESCE(AMD.Age,0) AS Age
    FROM [$ServerIP].[DataAnalysis].[dbo].[All_Mobile_Merge_Data] AS AMD
    INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].[User_Master] as um on (AMD.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AS)
    $con
    ORDER BY AMD.UpdatedDate ASC;";

// $db1=new DbOperation();
$data = $db->ExecutveQueryMultipleRowSALData($ExecutiveQuery, $userName, $appName, $developmentMode);


    // print_r($data);

        // $getDetail = sqlsrv_query($DBcon, $query3); 
        // // $data = array();
        // while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
        //         $data[] = $row;
        //     } 

        }

?>
<!-- <center> -->
<div id="ExecutiveMobileDiv"  style="display:none;margin-top:10px;">
    <!-- <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId">
        <div class="modal-content" style="width:100%;"> -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <!-- <div class="col-xl-6 col-md-6 col-sm-12"> -->
                            <!-- <div class="card"> -->
                            <div class="card" >
                                <div class="card-header" style="">
                                <h4 class="card-title"><?php if($flag == 'EW'){?> Executive Wise  ( <?php echo $CountTotalExecutiveAndMobileWise;?> ) - <?php echo $ExecutiveName; } ?><?php if($flag == 'MW'){?> Mobile No Wise( <?php echo $CountTotalExecutiveAndMobileWise; ?> ) - <?php echo $ExecutiveName1; }?></h4> 
                                    <!-- <div class="pagination-area" style="float:right;">
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
                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSessionModal(<?php  if($loopStart==1){ echo '1'; }else{ echo ($loopStart - 1); } ?>)" >Previous</a></li>
                                                <?php } ?>

                                                <?php
                                                    for($i=$loopStart;$i<=$loopStop;$i++){ 

                                                            $activePageCondition = ""; 
                                                            if($pageNo == $i){
                                                                $activePageCondition = "active";                                
                                                            }
                                                        ?>
                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSessionModal(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                <?php } ?>
                                                <?php if($totalRecords > $loopStop){ ?> 
                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSessionModal(<?php echo ($loopStop + 1);  ?>)" >Next</a></li>
                                                <?php }  ?>

                                            </ul>
                                        </nav>
                                    </div> -->
                                    <!-- Pagination Code -->
                                </div>                                    
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="row match-height" style="margin-top:-5px;">
                                            <div class="col-md-12" style="margin-bottom: -40px;">
                                                <div class="card">
                                                    
                                                    <div class="content-body">
                                                        <table class="table table-hover-animation table-hover table-striped" id="OnClickModalView">
                                                            <thead>
                                                                <tr>
                                                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                                    <th style="background-color:#36abb9;color: white;">Family No</th>
                                                                    <th style="background-color:#36abb9;color: white;">Survey Date</th>
                                                                    <th style="background-color:#36abb9;color: white;">Ac No / List No / Voter Id</th>
                                                                    <th style="background-color:#36abb9;color: white;">Site Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Voter Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">
                                                                    Mobile No
                                                                    <!-- <form action="ExecutiveAndMobileWiseModalView.php" method="POST">
                                                                        <input type="text" id="SearchTable" name="MobileSearch" value="<?php //echo $MobileSearch; ?>" placeholder="Search Mobile " style="width: 65%;">
                                                                        <button type="submit" id="SearchButton" name="SearchButton" value="Search" style="width: 30px;"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                                    </form> -->
                                                                </th>
                                                                    <th style="background-color:#36abb9;color: white;">Age</th>
                                                                    <th style="background-color:#36abb9;color: white;">Gender</th>
                                                                    <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                                    <th style="background-color:#36abb9;color: white;">Room No</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                        // $srNo = $srNo + 1;
                                                                    if(sizeof($data) > 0){
                                                                        $srNo = 1;
                                                                        foreach($data AS $Key=>$value){  
                                                                        ?>
                                                                        <tr>
                                                                            <td><b><?php echo $srNo++; ?></b></td>
                                                                            <td><b><?php echo $value['FamilyNo']; ?></b></td>
                                                                            <td><b><?php echo $value['UpdatedDate']; ?></b></td>
                                                                            <td><b><?php echo $value['AC_No'].'/'.$value['List_No'].'/'.$value['Voter_Id']; ?></b></td>
                                                                            <td><b><?php echo $value['SiteName']; ?></b></td>
                                                                            <td><b><?php echo $value['FullName']; ?></b></td>
                                                                            <td><b><?php echo $value['MobileNo']; ?></b></td>
                                                                            <td><b><?php echo $value['Age']; ?></b></td>
                                                                            <td><b><?php echo $value['Gender']; ?></b></td>
                                                                            <td><b><?php echo $value['SocietyName']; ?></b></td>
                                                                            <td><b><?php echo $value['RoomNo']; ?></b></td>
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- Pagination Code -->    
                                                    <!-- <div class="pagination-area" style="float:right;margin-right:20px;">
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
                                                                    <li class="page-item"><a class="page-link" onclick="setPaginationNoInSessionModal(<?php  if($loopStart==1){ echo '1'; }else{ echo ($loopStart - 1); } ?>)" >Previous</a></li>
                                                                <?php } ?>

                                                                <?php
                                                                    for($i=$loopStart;$i<=$loopStop;$i++){ 

                                                                            $activePageCondition = ""; 
                                                                            if($pageNo == $i){
                                                                                $activePageCondition = "active";                                
                                                                            }
                                                                        ?>
                                                                        <li class="page-item <?php echo $activePageCondition; ?>"><a class="page-link" onclick="setPaginationNoInSessionModal(<?php echo $i; ?>)" ><?php echo $i; ?></a></li>
                                                                <?php } ?>
                                                                <?php if($totalRecords > $loopStop){ ?> 
                                                                    <li class="page-item"><a class="page-link"  onclick="setPaginationNoInSessionModal(<?php echo ($loopStop + 1);  ?>)" >Next</a></li>
                                                                <?php }  ?>

                                                            </ul>
                                                        </nav>
                                                    </div> -->
                                                    <!-- Pagination Code -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
            </section>
        <!-- </div>
    </div> -->
</div>

<!-- </center> -->