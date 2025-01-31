<?php
// Changes By Prachi For Report
// include 'api/includes/DbOperation.php';

// $db=new DbOperation();
// $userName=$_SESSION['SurveyUA_UserName'];
// $appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
// $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB = $_SESSION['SurveyUtility_ULB'];
// $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
$SiteQcQury = "WITH unionTable AS (SELECT 
                COALESCE(Combined.SiteName, '') AS SiteName,
                COALESCE(COUNT(DISTINCT CASE 
                                            WHEN Combined.Mobileno <> '' AND Combined.Mobileno IS NOT NULL AND LEN(Combined.Mobileno) > 9 
                                            THEN Combined.Mobileno 
                                        END), 0) AS Mobileno,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.SF = 1 AND Combined.IdCard_No IS NOT NULL THEN 1 END), 0) AS TotalVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' THEN 1 END), 0) AS TotalNonVoters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.QC_Done = 1 THEN 1 END), 0) AS QC_Done_Voters,
                COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' AND Combined.QC_Done = 1 THEN 1 END), 0) AS QC_Done_NonVoters ,
                COALESCE(COUNT(CASE WHEN Combined.QC_Calling_Status_Cd IN (3, 6, 8) THEN 1 END), 0) AS QC_Calling_Status_Count
            FROM (
                SELECT 
                    dw.SiteName, 
                    dw.SF,
                    dw.IdCard_No,
                    'Dw_VotersInfo' AS Source, 
                    dw.MobileNo AS Mobileno,
                    dw.QC_Done, 
                    dw.QC_Calling_Status_Cd
                FROM Dw_VotersInfo AS dw
                WHERE dw.Society_Cd IS NOT NULL 
                AND dw.Society_Cd <> 0 

                UNION ALL
                
                 SELECT 
                    nv.SiteName, 
                    NULL AS SF,
                    NULL AS IdCard_No,
                    'NewVoterRegistration' AS Source, 
                    nv.Mobileno AS Mobileno,
                    nv.QC_Done,
                    nv.QC_Calling_Status_Cd
                FROM NewVoterRegistration AS nv
                WHERE nv.Society_Cd IS NOT NULL 
                AND nv.Society_Cd <> 0 
            ) AS Combined
            GROUP BY Combined.SiteName)
            SELECT 
                COALESCE(sm.SiteName, '') AS Sites,
                COALESCE(sm.SiteStatus, '') AS SiteStatus, 
                COALESCE(sm.SupervisorName, '') AS SupervisorName,
                COALESCE(em1.MobileNo, '') AS MobileNo,
                (SELECT COUNT(DISTINCT Society_Cd) AS Listing
                FROM Society_Master
                WHERE SiteName = sm.SiteName)  AS Listing, 
                COALESCE((SELECT COUNT(CASE WHEN BList_QC_UpdatedFlag = 1 THEN 1 ELSE 0 END) 
                FROM Society_Master 
                WHERE SiteName = sm.SiteName 
                AND CONVERT(varchar, BList_QC_UpdatedDate, 23) < CONVERT(varchar, GETDATE(), 23)
                ),0) AS ListingQc,
                COALESCE(Tbl.TotalVoters, 0) AS TotalVoters, 
                COALESCE(Tbl.TotalNonVoters, 0) AS TotalNonVoters, 
                COALESCE(Tbl.Mobileno, 0) AS TotalMobileCount,
                COALESCE(Tbl.QC_Done_Voters, 0) AS VoterQCDone, 
                COALESCE(Tbl.QC_Done_NonVoters, 0) AS NonVoterQCDone, 
                COALESCE(Tbl.QC_Calling_Status_Count, 0) AS WrongMobileNo,
                (select COALESCE(COUNT(DISTINCT(Voter_Cd)),0) from NewVoterRegistrationDeleted WHERE SiteName =  sm.SiteName) AS NonVotersConverted 
                FROM Site_Master AS sm
                LEFT JOIN Society_Master AS soc 
                ON sm.Site_Cd = soc.Site_Cd
                INNER JOIN Survey_Entry_Data..Election_Master AS elm 
                ON sm.ElectionName = elm.ElectionName
                LEFT JOIN Survey_Entry_Data..Executive_Master AS em1 
                ON sm.Supervisor_Cd = em1.Executive_Cd
                LEFT JOIN unionTable AS Tbl 
                ON sm.SiteName = Tbl.SiteName
                WHERE elm.ULB = '$ULB' AND sm.Ac_No <> 0 AND sm.Ac_No IS NOT NULL
                GROUP BY 
                sm.SiteName, 
                sm.SupervisorName, 
                em1.MobileNo, 
                sm.SiteStatus, 
                Tbl.TotalVoters, 
                Tbl.TotalNonVoters, 
                Tbl.Mobileno,
                QC_Done_Voters,
                QC_Done_NonVoters,
                QC_Calling_Status_Count
                ORDER BY sm.SiteName DESC;";


$SiteQcData = $db->ExecutveQueryMultipleRowSALData($ULB, $SiteQcQury, $userName, $appName, $developmentMode);
// print_r("<pre>");
// print_r($SiteQcData);
// print_r("</pre");
?>
<div class="card-header">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title" style="padding:5px;margin-left:10px;">Site Wise Qc</h4>
        </div>
        <div class="col-md-2">
            <?php if ($ExcelExportButton == "show") { ?>
                <button id="exportBtn1" class="btn btn-primary"
                    onclick="ExportToExcel('xlsx','SiteWiseQcTable')">Excel</button>
            <?php } ?>
        </div>
    </div>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">

                            <table class="table table-striped table-bordered complex-headers" id="SiteWiseQcTable"
                                width="100%" border=1>
                                <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            rowspan="2">No</th>
                                        <!-- <th style="background-color:#36abb9;color: white;" class="text-center" rowspan="2">View</th> -->
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            rowspan="2">Site Name</th>
                                        <!-- <th class="text-center" >Assembly</th> -->
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            colspan=3>Listing</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            colspan=3>Voters</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            colspan=4>NonVoters</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"
                                            colspan=3>Mobile No</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Total
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Pending
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Total
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Pending
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Total
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Pending
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Converted
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Total
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">Wrong
                                        </th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center">
                                            Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Percentage = 0;
                                    if (sizeof($SiteQcData) > 0) {
                                        $srNo = 1;
                                        foreach ($SiteQcData as $key => $value) {
                                            ?>
                                            <tr style="padding-top:0px;">
                                                <td class="text-center" style="align:center;"><?php echo $srNo++; ?></td>
                                                <!-- <td class="text-center"style="color: #36abb9;align-items:center;text-center;">
                                                    <a class="" onclick="GetSiteDetailQc('<?php echo $value['Sites'] ?>')">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td> -->
                                                <td class="text-center" style="align:center;"><?php echo $value["Sites"]; ?>
                                                </td>
                                                <td class="text-center" style="align:center;"><?php echo $value["Listing"]; ?>
                                                </td>
                                                <td class="text-center" style="align:center;"><?php echo $value["ListingQc"]; ?>
                                                </td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["Listing"] - $value["ListingQc"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["TotalVoters"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["VoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["TotalVoters"] - $value["VoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["TotalNonVoters"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["NonVoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["TotalNonVoters"] - $value["NonVoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["NonVotersConverted"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["TotalMobileCount"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php echo $value["WrongMobileNo"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php
                                                    if ($value["TotalMobileCount"] != 0) {
                                                        $Percentage = ($value["WrongMobileNo"] / $value["TotalMobileCount"]) * 100;
                                                        echo $Percentage = number_format($Percentage, 2) . " %";
                                                    } else {
                                                        echo "0.00 %";
                                                    }
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
</section>
<script>
    $(document).ready(function () {
        $('#SiteWiseQcTable').DataTable({
            "lengthMenu": [[-1, 20, 40, 50], ["All", 20, 40, 50]]
        });
    });
</script>