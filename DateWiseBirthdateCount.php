<div class="Birthday">
<?php
//Chnages Added By Prachi For Report
// include 'api/includes/DbOperation.php'; 

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
if(isset($_SESSION['SurveyUA_BdayDate_BirthdateFilter']) && !empty($_SESSION['SurveyUA_BdayDate_BirthdateFilter']) ){
    // echo "Set";
    $Date = $_SESSION['SurveyUA_BdayDate_BirthdateFilter'];
}else
{   
    // echo "NotSet";
    $Date = date("Y-m-d");
}
$BdayQuery = "SELECT 
                  Total_Mob_Nos.Birthdate AS Date,
                  COUNT(Total_Mob_Nos.Birthdate) AS Birthdays
              FROM 
                  (
                      SELECT 
                          FORMAT(TRY_CONVERT(date, dw.Birthdate), 'dd-MM') + '-' + CAST(YEAR(GETDATE()) AS VARCHAR(4)) AS Birthdate
                      FROM 
                          Dw_VotersInfo AS dw
                      WHERE 
                          dw.SF = 1 
                          AND dw.Birthdate <> '' 
                          AND dw.Birthdate IS NOT NULL 
                          AND TRY_CONVERT(date, dw.BirthDate) IS NOT NULL
                          AND DATEPART(month, TRY_CONVERT(date, dw.BirthDate)) = MONTH('$Date') 
                          AND DATEPART(day, TRY_CONVERT(date, dw.BirthDate)) >= DAY('$Date')
                          AND dw.SiteName IS NOT NULL
                      UNION ALL  
                      SELECT 
                          FORMAT(TRY_CONVERT(date, nv.Birthdate), 'dd-MM') + '-' + CAST(YEAR(GETDATE()) AS VARCHAR(4)) AS Birthdate
                      FROM 
                          NewVoterRegistration AS nv
                      WHERE 
                          nv.Birthdate <> '' 
                          AND nv.Birthdate IS NOT NULL
                          AND TRY_CONVERT(date, nv.BirthDate) IS NOT NULL
                          AND DATEPART(month, TRY_CONVERT(date, nv.BirthDate)) = MONTH('$Date') 
                          AND DATEPART(day, TRY_CONVERT(date, nv.BirthDate)) >= DAY('$Date')
                          AND nv.SiteName IS NOT NULL
                  ) AS Total_Mob_Nos
              GROUP BY 
                  Total_Mob_Nos.Birthdate
              ORDER BY 
                  Total_Mob_Nos.Birthdate ASC;";

$BdayCount = $db->ExecutveQueryMultipleRowSALData($ULB,$BdayQuery, $userName, $appName, $developmentMode);

?>

<link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
    <!-- <link rel="stylesheet" type="text/css" href="app-assets/css/components.css"> -->
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/plugins/file-uploaders/dropzone.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/data-list-view.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
<div class="row">
    <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <h4 class="card-title"><b>Birthdate List</b></h4>
                </div>
                <div class="col-8">
                    <div class="form-group">
                        <label>From Date</label>
                        <div class="controls"> 
                            <input type="date" name="fromdate" value="<?php echo $Date; ?>"  class="form-control" placeholder="From Date" onchange="getMonthForBday(this.value)">
                        </div>
                    </div>
                </div>
                <ul class="list-group list-group-flush">
                    <?php 
                    if(sizeof($BdayCount) > 0){
                        $sr = 1;
                        foreach($BdayCount as $key=>$value){
                    ?>
                    <li class="list-group-item">
                        <span class="badge badge-pill bg-primary float-right"><a onclick="GetBdayCount('<?php echo $value['Date']; ?>')"><?php echo $value['Birthdays']; ?></a></span>
                        <?php echo $value['Date']; ?>  
                    </li>
                <?php 
                        }
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-md-6 col-sm-12" id="BdayListTable">

    <!-- <section id="data-list-view" class="data-list-view-header"> -->
        <div class="card" id="DateBirthday">
            <?php 
            if(isset($_SESSION['SurveyUA_BdayDate_Birthdate_Report']) && !empty($_SESSION['SurveyUA_BdayDate_Birthdate_Report']) ){

                // echo "Set";
                $Birthdate = $_SESSION['SurveyUA_BdayDate_Birthdate_Report'];
            } else{
                
                $Birthdate = date("d-m-Y");
            }
            // echo $Birthdate;
            $date = date("d",strtotime($Birthdate));
            $month = date("m",strtotime($Birthdate));

            $DateWiseBdayQuery = "SELECT 
                                        sm.SiteName,
                                        sm.ClientName,
                                        COUNT(CombinedBirthdates.Birthdate) AS Birthdays,
                                        CombinedBirthdates.Birthdate AS Date
                                    FROM 
                                        Site_Master AS sm
                                    JOIN 
                                        (SELECT 
                                            dw.SiteName,
                                            CONCAT(
                                                DATEPART(day, TRY_CONVERT(date, dw.Birthdate)),
                                                '-',
                                                DATEPART(month, TRY_CONVERT(date, dw.Birthdate)),
                                                '-',
                                                YEAR(GETDATE())
                                            ) AS Birthdate
                                        FROM 
                                        Dw_VotersInfo AS dw
                                        WHERE 
                                            dw.SF = 1
                                            AND dw.Birthdate <> ''
                                            AND dw.Birthdate IS NOT NULL
                                            AND DATEPART(month, TRY_CONVERT(date, dw.BirthDate)) = '$month' 
                                            AND DATEPART(day, TRY_CONVERT(date, dw.BirthDate)) = '$date' 
                                        UNION ALL
                                        SELECT 
                                            nv.SiteName,
                                            CONCAT(
                                                DATEPART(day, TRY_CONVERT(date, nv.Birthdate)),
                                                '-',
                                                DATEPART(month, TRY_CONVERT(date, nv.Birthdate)),
                                                '-',
                                                YEAR(GETDATE())
                                            ) AS Birthdate
                                        FROM 
                                            NewVoterRegistration AS nv
                                        WHERE 
                                            nv.Birthdate <> ''
                                            AND nv.Birthdate IS NOT NULL
                                            AND DATEPART(month, TRY_CONVERT(date, nv.BirthDate)) = '$month'
                                            AND DATEPART(day, TRY_CONVERT(date, nv.BirthDate)) = '$date' 
                                        ) AS CombinedBirthdates
                                    ON sm.SiteName = CombinedBirthdates.SiteName
                                    WHERE sm.ElectionName = '$ULB'
                                    GROUP BY 
                                        CombinedBirthdates.Birthdate,
                                        sm.SiteName, sm.ClientName
                                    ORDER BY 
                                        sm.SiteName, sm.ClientName;";

                $DateWiseBdayCount = $db->ExecutveQueryMultipleRowSALData($ULB,$DateWiseBdayQuery, $userName, $appName, $developmentMode);

                ?>
                <div class="card-header">
                    <h4 class="card-title"><b><?php echo $Birthdate; ?></b> Birthdays</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table table-hover-animation table-striped table-hover" id="SurveySummaryList">
                                                    <thead>
                                                        <tr>
                                                            <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                            <th style="background-color:#36abb9;color: white;">Client Name</th>
                                                            <th style="background-color:#36abb9;color: white;">SiteName</th>
                                                            <th style="background-color:#36abb9;color: white;">Count</th>
                                                            <th style="background-color:#36abb9;color: white;">View</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if(sizeof($DateWiseBdayCount) > 0 ){
                                                            $srNo = 1;
                                                            $Total = 0;
                                                            foreach ($DateWiseBdayCount as $key => $value) {
                                                                $Total += $value["Birthdays"];
                                                            ?> 
                                                                <tr style="padding-top:0px;">
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo "<b>" . $value["ClientName"] . "</b>"; ?></td>
                                                                    <td><?php echo  $value["SiteName"]; ?></td>
                                                                    <td><?php echo $value["Birthdays"]; ?></td>
                                                                    <td style="color: #36abb9;">
                                                                        <a href="<?php echo 'index.php?p=SiteWiseBirthdateReport&SiteName='.$value['SiteName'].'&Date='.$date.'&Month='.$month; ?>">
                                                                            <i style="color:#36abb9;cursor:pointer;" class="fa fa-eye"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th></th>
                                                            <th><b>Total</b></th>
                                                            <td></td>
                                                            <td><?php echo $Total; ?></td>
                                                        </tr>
                                                    </tfoot>
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
    </div>
</div>
            
<script>
  $(document).ready(function () {
    $('#BirthdayTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
            <script src="app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="app-assets/vendors/js/extensions/dropzone.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="app-assets/js/scripts/ui/data-list-view.js"></script>
</div>