<?php
    $db1=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];


    $currentDate = date('Y-m-d');
    $previousdate = date('Y-m-d', strtotime('-7 days'));


if( isset($_SESSION['FromDate_Society_Summary_Dashboard']) &&
    isset($_SESSION['ToDate_Society_Summary_Dashboard']) 
)
{
    // isset($_SESSION['SurveyUA_SiteCd_For_Dashboard'])
    
    // $Site_Cd_Get = $_SESSION['SurveyUA_SiteCd_For_Dashboard'];
    $Fromdate = $_SESSION['FromDate_Society_Summary_Dashboard'];
    $Todate = $_SESSION['ToDate_Society_Summary_Dashboard'];

    $date = strtotime($Fromdate);
    $fromdate = date('Y-m-d', $date);

    $date1 = strtotime($Todate);
    $todate = date('Y-m-d', $date1);

    $dateCondition = " AND sm.AssignedDate BETWEEN '$fromdate' AND '$todate'";
    
}
else{

    $Site_Cd_Get = 1;
    $fromdate = $previousdate;
    $todate = $currentDate;
    $dateCondition = " AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '$fromdate' AND '$todate'";

}

if(isset($_SESSION['SurveyUA_ElectionName_For_Dashboard'])){
            $electionNameforData = $_SESSION['SurveyUA_ElectionName_For_Dashboard'];
            $electionCondition = "AND sm.ElectionName = '$electionNameforData'";
}else{
    $electionCondition = "";
    $electionNameforData = "";
}


    $dataSocietySurveySummary = array();

    $query1 = "SELECT 
                ssm.SiteName AS SiteName,
                (
                    SELECT COUNT(Site_Cd) FROM Site_Master WHERE ElectionName = 'KHOPOLI' 
                ) AS TotalSites,
                (
                    SELECT 
                    ISNULL(COUNT(DISTINCT(sm.SiteName)), 0) AS AssignedSites 
                    FROM Society_Master sm
                    WHERE sm.SiteName = ssm.SiteName 
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25' 
                ) AS AssignedSites, 
                (
                    SELECT 
                    ISNULL(COUNT(DISTINCT(sm.SiteName)), 0) AS CompltedSites 
                    FROM Society_Master sm
                    WHERE sm.SiteName = ssm.SiteName AND sm.IsCompleted = 1 
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25' 
                    
                ) AS CompletedSites,
                (
                    SELECT COUNT(sm.Society_Cd) FROM Society_Master sm
                    WHERE SiteName = ssm.SiteName
                    $electionCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                ) AS TotalSocieties, 
                (
                    SELECT 
                    ISNULL(COUNT(sm.Society_Cd), 0 ) AS TotalAssignedSocieties
                    FROM Society_Master sm
                    WHERE sm.SiteName = ssm.SiteName 
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25' 
                ) AS TotalAssignedSocieties,
                (
                    SELECT 
                    ISNULL(COUNT(sm.Society_Cd), 0 ) AS TotalCompletedSocieties
                    FROM Society_Master sm
                    WHERE sm.SiteName = ssm.SiteName AND sm.IsCompleted = 1
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25' 
                    
                ) AS TotalCompletedSocieties,
                (
                    SELECT COUNT(Pocket_Cd) FROM Pocket_Master WHERE SiteName = ssm.SiteName
                ) AS TotalPockets, 
                (
                    SELECT COUNT(DISTINCT(sm.Pocket_Cd)) 
                    FROM Society_Master sm
                    WHERE sm.Pocket_Cd <> 0 
                    AND sm.SiteName = ssm.SiteName
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25'
                ) AS TotalAssignedPockets,
                (
                    SELECT 
                    ISNULL(COUNT(DISTINCT(sm.Pocket_Cd)), 0) 
                    FROM Society_Master sm
                    WHERE sm.SiteName = ssm.SiteName AND sm.IsCompleted = 1 
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25' 
                    
                ) AS CompltedPockets,
                (
                    SELECT COUNT(DISTINCT(sm.Executive_Cd)) 
                    FROM Society_Master sm 
                    WHERE sm.SiteName = ssm.SiteName
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25'
                ) AS TotalAssignedExecutive, 
                (
                    SELECT COUNT(DISTINCT(sm.Executive_Cd)) 
                    FROM Society_Master sm 
                    WHERE sm.SiteName = ssm.SiteName AND sm.IsCompleted = 1 
                    $electionCondition
                    $dateCondition
                    -- AND sm.ElectionName = 'KHOPOLI' 
                    -- AND CONVERT(VARCHAR,sm.AssignedDate,23) BETWEEN '2022-07-01' AND '2022-07-25'
                    
                ) AS ExecutiveCompletedSurevyCount
                FROM Society_Master  ssm
                WHERE ssm.ElectionName = '$electionNameforData' 
                AND CONVERT(VARCHAR,ssm.AssignedDate,23) BETWEEN '$fromdate' AND '$todate'
                GROUP BY  ssm.SiteName ;";

    // echo $query1;

    $dataSocietySurveySummary = $db1->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);



  
?>
  

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Assigned Societies Summary</h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table  style="width:100%;" class="table zero-configuration table-striped table-bordered complex-headers">
                                                <thead>
                                                    <tr>
                                                        <th  style="text-align:center;">Sr No</th>
                                                        <th  style="text-align:center;">Site Name</th>
                                                        <th colspan="3" style="text-align:center; ">Site</th>
                                                        
                                                        <th colspan="3" style="text-align:center;">Pocket</th>
                                                        
                                                        <th colspan="3" style="text-align:center;">Society</th>
                                                        
                                                        <th colspan="2" style="text-align:center;">Executive</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Assign</th>
                                                        <th>Completed</th>
                                                        <th>Total</th>
                                                        <th>Assign</th>
                                                        <th>Completed</th>
                                                        <th>Total</th>
                                                        <th>Assign</th>
                                                        <th>Completed</th>
                                                        <th>Total</th>
                                                        <th>Survey Completed</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $srNo = 1;
                                                    if(sizeof($dataSocietySurveySummary) > 0){
                                                        foreach ($dataSocietySurveySummary as $key => $value) {
                                                    
                                                        ?> 
                                                            <tr>
                                                                <td  style="text-align:center;"><?php echo $srNo++; ?></td>
                                                                <td  style="text-align:center;"><?php echo $value["SiteName"]; ?></td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["AssignedSites"];?>
                                                                </td>  
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["CompletedSites"]; ?>
                                                                </td>  
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalSites"]; ?>
                                                                </td>  
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalAssignedSocieties"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;"> 
                                                                    <?php echo $value["TotalCompletedSocieties"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalSocieties"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalAssignedPockets"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["CompltedPockets"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalPockets"]; ?>
                                                                </td>
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["ExecutiveCompletedSurevyCount"]; ?>
                                                                </td>   
                                                                <td  style="text-align:center;">
                                                                    <?php echo $value["TotalAssignedExecutive"]; ?>
                                                                </td>   
                                                              
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <!-- <tfoot>
                                                    <tr>
                                                        <th  style="text-align:center;">Sr No</th>
                                                        <th  style="text-align:center;">Site Name</th>
                                                        <th colspan="3" style="text-align:center;">Site</th>
                                                        
                                                        <th colspan="3" style="text-align:center;">Pocket</th>
                                                        
                                                        <th colspan="3" style="text-align:center;">Society</th>
                                                        
                                                        <th colspan="2"  style="text-align:center;">Executive</th>
                                                        
                                                    </tr>
                                                </tfoot> -->
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