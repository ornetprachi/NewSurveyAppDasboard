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
if(isset($_SESSION['SurveyUA_Div'])){
    $Div = $_SESSION['SurveyUA_Div']; 
}

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['ExecutiveName']) && !empty($_GET['ExecutiveName']) && isset($_GET['ExecutiveCd']) && !empty($_GET['ExecutiveCd'])){

    try  
        {  
            
            $_SESSION['SurveyUA_Executive_Name'] = $_GET['ExecutiveName'];
            $ExecutiveName = $_SESSION['SurveyUA_Executive_Name'];

            $_SESSION['SurveyUA_Executive_Cd'] = $_GET['ExecutiveCd'];
            $ExecutiveCd = $_SESSION['SurveyUA_Executive_Cd'];
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
<div id="Executivedetail">
<?php
if(isset($_SESSION['SurveyUtility_ULB'])){
    $ULB=$_SESSION['SurveyUtility_ULB'];
}
if(
    (isset($_SESSION['SurveyUA_Executive_Name']) && !empty($_SESSION['SurveyUA_Executive_Name']))
){
    $Executive_Name = $_SESSION['SurveyUA_Executive_Name'];
}


$Query = "WITH unionTable AS (
            SELECT
                Combined.AddedBy AS AddedBy,
                Combined.AddedDate,
                Combined.WardNo,
                COALESCE(COUNT(DISTINCT Combined.Society_Cd), 0) AS Society_Cd,
                COALESCE(COUNT(DISTINCT Combined.RoomNo), 0) AS RoomCount, 
                COALESCE(COUNT(DISTINCT CASE 
                                        WHEN Combined.Mobileno <> '' AND Combined.Mobileno IS NOT NULL AND LEN(Combined.Mobileno) > 9 
                                        THEN Combined.Mobileno 
                                    END), 0) AS Mobileno,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.IdCard_No IS NOT NULL AND Combined.IdCard_No <> '' THEN 1 END), 0) AS TotalVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' AND Combined.Voter_Cd IS NOT NULL AND Combined.Voter_Cd <> ''  THEN 1 END), 0) AS TotalNonVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'LockRoom' THEN 1 END), 0) AS LockRoom,
                COALESCE(COUNT(CASE WHEN Combined.BirthDate IS NOT NULL AND Combined.BirthDate <> '01/01/1900' THEN 1 END), 0) AS BirthdaysCount,
                COALESCE(COUNT(DISTINCT CASE 
                    WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
                END), 0) AS LBS
                FROM (
                    SELECT 
                            dw.IdCard_No,
                            dw.Voter_Cd AS Voter_Cd,
                            dw.Ward_no AS WardNo, 
                            dw.Society_Cd, 
                            dw.RoomNo, 
                            dw.AddedBy, 
                            Convert(varchar,dw.AddedDate,23) AS AddedDate, 
                            'Dw_VotersInfo' AS Source, 
                            dw.LockedButSurvey AS LBS, 
                            dw.MobileNo AS Mobileno,
                            CASE
                                WHEN TRY_CONVERT(date, dw.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 101), 101)
                                WHEN TRY_CONVERT(date, dw.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 105), 101)
                                WHEN TRY_CONVERT(date, dw.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 23), 101)
                                ELSE NULL
                            END AS BirthDate 
                        FROM Dw_VotersInfo AS dw
                        WHERE dw.Society_Cd IS NOT NULL 
                        AND dw.Society_Cd <> 0 AND COALESCE(dw.Ward_no, 0) != 0
                        AND (dw.BirthDate <> '' AND dw.BirthDate IS NOT NULL OR CONVERT(date,dw.BirthDate,23) = '1900-01-01')

                        UNION ALL

                        SELECT 
                            NULL AS IdCard_No,
                            nv.Voter_Cd AS Voter_Cd,
                            nv.Ward_No AS WardNo, 
                            nv.Society_Cd, 
                            nv.RoomNo, 
                            nv.added_by AS AddedBy, 
                            Convert(varchar,nv.added_date,23) AS AddedDate, 
                            'NewVoterRegistration' AS Source, 
                            nv.LockedButSurvey AS LBS, 
                            nv.Mobileno AS Mobileno,
                            CASE
                                WHEN TRY_CONVERT(date, nv.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 101), 101)
                                WHEN TRY_CONVERT(date, nv.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 105), 101)
                                WHEN TRY_CONVERT(date, nv.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 23), 101)
                                ELSE NULL
                            END AS BirthDate
                        FROM NewVoterRegistration AS nv
                        WHERE nv.Society_Cd IS NOT NULL 
                        AND nv.Society_Cd <> 0 AND COALESCE(nv.Ward_No, 0) != 0
                        AND (nv.BirthDate <> '' AND nv.BirthDate IS NOT NULL OR CONVERT(date,nv.BirthDate,23) = '1900-01-01')

                        UNION ALL

                        SELECT 
                            NULL AS IdCard_No,
                            NULL AS Voter_Cd,
                            lr.Ward_No AS WardNo, 
                            lr.Society_Cd, 
                            lr.RoomNo, 
                            lr.added_by AS AddedBy, 
                            Convert(varchar,lr.added_date,23) AS AddedDate,
                            'LockRoom' AS Source, 
                            NULL AS LBS, 
                            NULL AS Mobileno, 
                            NULL AS BirthDate
                        FROM LockRoom AS lr
                        WHERE lr.Society_Cd IS NOT NULL 
                        AND lr.Society_Cd <> 0 
                        AND COALESCE(lr.Ward_No, 0) != 0
                ) AS Combined
                GROUP BY Combined.AddedBy,Combined.AddedDate,Combined.WardNo
        )
        SELECT 
            COALESCE(um.ElectionName, '') AS ElectionName,
            COALESCE(um.UserName, '') AS UserName,
            COALESCE(um.ExecutiveName, '') AS ExecutiveName,
            COALESCE(ut.Society_Cd, 0) AS Societies, 
            um.Executive_cd AS Executive_cd,
            ut.AddedDate AS SurveyDate,
            ut.WardNo AS Ward_No,
            COALESCE(ut.RoomCount, 0) AS RoomSurveyDone, 
            COALESCE(ut.TotalVoters, 0) AS TotalVoters, 
            COALESCE(ut.TotalNonVoters, 0) AS TotalNonVoters, 
            COALESCE(ut.LockRoom, 0) AS LockRoom,
            COALESCE(ut.BirthdaysCount, 0) AS BirthdaysCount, 
            COALESCE(ut.LBS, 0) AS LBS, 
            COALESCE(ut.Mobileno, 0) AS TotalMobileCount
            FROM unionTable AS ut
        Inner JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
        AND um.ElectionName = '$ULB' AND um.Executive_Cd = $ExecutiveCd";
$ExecutiveDataCount = $db->ExecutveQueryMultipleRowSALData($ULB,$Query, $userName, $appName, $developmentMode);

?>
<style>
.executiveDetailTable{
  display: none;
}
</style>
<div id = "executiveDetailTable" class = "executiveDetailTable">
<div class="row match-height mb-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-1" id="">
                <div class="card-header">
                    <h4 class="card-title">Execuitve Details - <?php echo $Executive_Name . " (" . sizeof($ExecutiveDataCount) . ")";?></h4>
                    <?php //if($ExcelExportButton == "show"){ ?>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ExecutiveWiseDetail')">Excel</button>
                    <?php //} ?>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;height: auto;" id="ExecutiveWiseDetail">
                                <thead>
                                <tr>
                                    <th class="text-center" style=''>SrNo</th>
                                    <th class="text-center" style=''>Action</th>
                                    <!--class="text-center" <th  style=''>Executive Name</th> -->
                                    <th class="text-center" style=''>Survey Date</th>
                                    <th class="text-center" style=''>Ward No</th>
                                    <th class="text-center" style=''>Society</th>
                                    <th class="text-center" style=''>Rooms</th>
                                    <th class="text-center" style="">Voters</th>
                                    <th class="text-center" style="">NonVoters</th>
                                    <th class="text-center" style="">Lockroom</th>
                                    <th class="text-center" style="">LBS</th>
                                    <th class="text-center" style="">Birthday</th>
                                    <th class="text-center" style="">Mobile</th>
                                    <th class="text-center" Title ="Voters Ratio">V %</th>
                                    <th class="text-center" Title ="NonVoters Ratio">NV %</th>
                                    <th class="text-center" Title ="LockRoom Ratio">LR %</th>
                                    <th class="text-center" Title ="Locked But Survey Ratio">LBS %</th>
                                    <th class="text-center" Title ="Birthdate Ratio">BirtDt %</th>
                                    <th class="text-center" Title ="Mobile Ratio">Mob %</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($ExecutiveDataCount) > 0){
                                        $srNo = 1;
                                        // echo "<pre>".print_r($ExecutiveDataCount);
                                        foreach($ExecutiveDataCount AS $Key=>$value){  
                                        ?>
                                        <tr>
                                            <td style="margin:15px;"><?php echo $srNo++; ?></td>
                                            <td style="color: #36abb9;align-items:center;text-center;">
                                                <a href="index.php?p=Survey-QC-Details-View&electionName=<?php echo $value['ElectionName'] ?>&SurveyDate=<?php echo date('d/m/Y', strtotime($value['SurveyDate'])) ?>&UserName=<?php echo $value['UserName'] ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>&ExecutiveCd=<?php echo $value['Executive_cd'] ?>" target="_blank" class="">
                                                    <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                </a>
                                            </td>
                                            <!-- <td><?php //echo $value['ExecutiveName']; ?></td> -->
                                            <td class="text-center" style="margin:15px;"><?php echo date('d/m/Y', strtotime($value['SurveyDate'])); ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['Ward_No']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['Societies']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['RoomSurveyDone']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalVoters']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalNonVoters']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['LockRoom']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['LBS']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['BirthdaysCount']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalMobileCount']; ?></td>
                                            <td>
                                                <?php 
                                                echo ($value["TotalVoters"] + $value["TotalNonVoters"]) > 0 ? 
                                                    CEIL(($value["TotalVoters"] / ($value["TotalVoters"] + $value["TotalNonVoters"])) * 100) . " %" : "0 %"; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                echo ($value["TotalVoters"] + $value["TotalNonVoters"]) > 0 ? 
                                                    CEIL(($value["TotalNonVoters"] / ($value["TotalVoters"] + $value["TotalNonVoters"])) * 100) . " %" : "0 %"; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                echo $value["RoomSurveyDone"] > 0 ? 
                                                    CEIL(($value["LockRoom"] / $value["RoomSurveyDone"]) * 100) . " %" : "0 %"; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                echo $value["RoomSurveyDone"] > 0 ? 
                                                    CEIL(($value["LBS"] / $value["RoomSurveyDone"]) * 100) . " %" : "0 %"; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                echo $value["RoomSurveyDone"] > 0 ? 
                                                    CEIL(($value["BirthdaysCount"] / $value["RoomSurveyDone"]) * 100) . " %" : "0 %"; 
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                echo $value["RoomSurveyDone"] > 0 ? 
                                                    CEIL(($value["TotalMobileCount"] / $value["RoomSurveyDone"]) * 100) . " %" : "0 %"; 
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
</div>
</div>
</div>

