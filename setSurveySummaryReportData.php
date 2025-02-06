<?php
$db = new DbOperation();

$userName = $_SESSION['SurveyUA_UserName'];
$appName = $_SESSION['SurveyUA_AppName'];
$electionCd = $_SESSION['SurveyUA_Election_Cd'];
$electionName = $_SESSION['SurveyUA_ElectionName'];
$developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
$ULB = $_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
$fromdate = date('Y-m-d');
$todate = date('Y-m-d');
$Site = "";

$WorkForFilter = '';
$CountQuery = "SELECT 
                (SELECT COUNT(DISTINCT(IdCard_No)) FROM Dw_VotersInfo
                WHERE COALESCE(IdCard_No,'') ! = '') as Voters,
                (SELECT COUNT(DISTINCT(Voter_Cd)) FROM NewVoterRegistration 
                WHERE Voter_Cd Is not null) as NonVoter,
                (SELECT COUNT(DISTINCT(Society_cd)) FROM Society_Master) as Society,
                (SELECT COUNT(LR_Cd) FROM LockRoom
                ) as LockRoom,
                (
                SELECT COUNT(tb.RoomNo)
                FROM(SELECT Society_Cd,RoomNo FROM Dw_VotersInfo
                UNION
                SELECT Society_Cd,Roomno as RoomNo FROM NewVoterRegistration
                UNION 
                SELECT Society_Cd,RoomNo FROM LockRoom
                ) as tb ) As Rooms,
                (
                SELECT COUNT(tb.RoomNo)
                FROM(SELECT Society_Cd,RoomNo FROM Dw_VotersInfo
                WHERE  COALESCE(LockedButSurvey,'') != ''
                UNION
                SELECT Society_Cd,Roomno as RoomNo FROM NewVoterRegistration
                WHERE  COALESCE(LockedButSurvey,'') != ''
                ) as tb ) As LBS,
                (
                SELECT COUNT(tb.Mob)
                FROM(SELECT DISTINCT(MobileNo) as Mob FROM Dw_VotersInfo
                WHERE  COALESCE(MobileNo,'') != ''
                UNION
                SELECT DISTINCT(Mobileno) as Mob  FROM NewVoterRegistration
                WHERE  COALESCE(Mobileno,'') != ''
                ) as tb ) As Mobile,
                (
                SELECT SUM(tb.Bday)
                FROM(SELECT COUNT(*) as Bday FROM Dw_VotersInfo
                WHERE COALESCE(BirthDate, '') != '' 
                UNION
                SELECT COUNT(*) as Bday  FROM NewVoterRegistration
                WHERE  COALESCE(Birthdate,'') != ''
                ) as tb ) As Bday";

$CountListMain = $db->ExecutveQuerySingleRowSALData($ULB, $CountQuery, $userName, $appName, $developmentMode);



?>
<style>
    table.dataTable th,
    table.dataTable td {
        border-bottom: 1px solid #F8F8F8;
        border-top: 0;
        padding: 3PX;
    }

    table.dataTable thead>tr>th.sorting_asc,
    table.dataTable thead>tr>th.sorting_desc,
    table.dataTable thead>tr>th.sorting,
    table.dataTable thead>tr>td.sorting_asc,
    table.dataTable thead>tr>td.sorting_desc,
    table.dataTable thead>tr>td.sorting {
        padding-right: 3px;
    }

    .card-body {
        -webkit-box-flex: 1;
        -webkit-flex: 1 1 auto;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 5px;
        font-size: 12px;
    }

    .card .card-header {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -webkit-box-pack: justify;
        -webkit-justify-content: space-between;
        -ms-flex-pack: justify;
        justify-content: space-between;
        border-bottom: none;
        padding: 1rem 1rem 0;
        background-color: transparent;
    }

    .form-control {
        display: block;
        /* width: 100%; */
        height: 30px;
        padding: 3px;
        font-size: 0.96rem;
        font-weight: 400;
        line-height: 1.25;
        color: #4E5154;
        background-color: #FFFFFF;
        background-clip: padding-box;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        -webkit-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition-duration: 0.15s, 0.15s;
        transition-timing-function: ease-in-out, ease-in-out;
        transition-delay: 0s, 0s;
        transition-property: border-color, box-shadow;
    }

    .card {
        margin-bottom: 10px;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row pl-5">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;margin-left: 5px;">
                                        <div class="avatar-content">
                                            <img src="app-assets/images/votersvg.svg" alt="Voters" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 3px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['Voters']; ?></h4>

                                        <p class="card-text font-small-4 mb-0">Voters </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/NonVoter.svg" alt="Non-Voters" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['NonVoter']; ?>
                                        </h4>

                                        <p class="card-text font-small-4 mb-0">Non-Voters </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <!-- <div class="bg-light-danger p-50  mr-2" style="background-color:white;"> -->
                                    <div class="avatar-content" style="margin-left: 3px;">
                                        <img src="app-assets/images/sitiessvg.svg" alt="Societies" width="40"
                                            height="60">
                                    </div>
                                    <!-- </div> -->
                                    <div class="media-body my-auto" style="margin-left: 20px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['Society']; ?>
                                        </h4>

                                        <p class="card-text font-small-4 mb-0">Societies</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/pendingsvg.svg" alt="Lockroom" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['LockRoom']; ?>
                                        </h4>

                                        <p class="card-text font-small-4 mb-0">Lockroom</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 pl-5">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/socitetiessvg.svg" alt="Room Done" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['Rooms']; ?></h4>

                                        <p class="card-text font-small-4 mb-0">Room Done</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/Report2.png" alt="LBS" width="40" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['LBS']; ?></h4>

                                        <p class="card-text font-small-4 mb-0">LBS</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/MobileNo.svg" alt="Mobile" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['Mobile']; ?></h4>

                                        <p class="card-text font-small-4 mb-0">Mobile</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="media">
                                    <div class="bg-light-danger p-10  mr-2"
                                        style="background-color:white;  margin-left: 5px;">
                                        <div class="avatar-content" style="margin-left: 3px;">
                                            <img src="app-assets/images/Birthday.svg" alt="Birthday" width="40"
                                                height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 8px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $CountListMain['Bday']; ?></h4>

                                        <p class="card-text font-small-4 mb-0">Birthday</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
                <li class="nav-item">
                    <a class="nav-link active" id="site-tab" data-toggle="tab" href="#site" aria-controls="site"
                        role="tab" aria-selected="true">Site</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="executive-tab" data-toggle="tab" href="#executive" aria-controls="executive"
                        role="tab" aria-selected="false">Executive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="DateWise-tab" data-toggle="tab" href="#DateWise" aria-controls="DateWise"
                        role="tab" aria-selected="false">Date Wise</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="QC-tab" data-toggle="tab" href="#QC" aria-controls="QC" role="tab"
                        aria-selected="false">Qc</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="ExecAndMobReport-tab" data-toggle="tab" href="#ExecAndMobReport"
                        aria-controls="ExecAndMobReport" role="tab" aria-selected="false">Executive & Mobile Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="SocietyIssue-tab" data-toggle="tab" href="#SocietyIssue"
                        aria-controls="SocietyIssue" role="tab" aria-selected="false">Society Issue</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="site" aria-labelledby="site-tab" role="tabpanel" aria-selected="true">
                    <?php
                    include 'SiteReport.php';
                    ?>
                </div>
                <div class="tab-pane" id="executive" aria-labelledby="executive-tab" role="tabpanel"
                    aria-selected="false">
                    <?php
                    include 'ExecutiveOverallData.php';
                    ?>
                </div>
                <div class="tab-pane" id="QC" aria-labelledby="QC-tab" role="tabpanel" aria-selected="false">
                    <?php
                    include 'QcDetailData.php';
                    ?>
                </div>

                <div class="tab-pane" id="ExecAndMobReport" aria-labelledby="ExecAndMobReport-tab" role="tabpanel"
                    aria-selected="false">
                    <?php
                    include 'setExecutiveAndMobileNoWiseReports.php';
                    ?>
                </div>

                <div class="tab-pane" id="SocietyIssue" aria-labelledby="SocietyIssue-tab" role="tabpanel"
                    aria-selected="false">
                    <?php
                    include "getSocietyIsssueReport.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="ExecutiveAndMobileWiseModal" class="ExecutiveAndMobileWiseModal" style="display:none;">
        <div id="ExecutiveMobileDiv" style="margin-top:10px;">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="">
                                <h4 class="card-title"><span id="execMobTitle"></span></h4>
                            </div>
                            <div class="card-body card-dashboard">
                                <table class="table table-hover-animation table-hover table-striped"
                                    id="OnClickModalView">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color: white;">Sr No
                                            </th>
                                            <th style="background-color:#36abb9;color: white;">Family No
                                            </th>
                                            <th style="background-color:#36abb9;color: white;">Survey
                                                Date</th>
                                            <th style="background-color:#36abb9;color: white;">Ac No /
                                                List No / Voter Id</th>
                                            <th style="background-color:#36abb9;color: white;">Site Name
                                            </th>
                                            <th style="background-color:#36abb9;color: white;">Voter
                                                Name</th>
                                            <th style="background-color:#36abb9;color: white;">Mobile No
                                            </th>
                                            <th style="background-color:#36abb9;color: white;">Age</th>
                                            <th style="background-color:#36abb9;color: white;">Gender
                                            </th>
                                            <th style="background-color:#36abb9;color: white;">Society
                                                Name</th>
                                            <th style="background-color:#36abb9;color: white;">Room No
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="DetailSiteData" style="display:none;" id="SiteWiseAllDetail">
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="card-title" style="align:center;color:rgb(54, 171, 185);"><b><span
                                                id="siteName"></span></b></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="text-start">Date</label>
                                        <input type="date" name="fdate" id="fdate" value="<?php echo $fromdate; ?>"
                                            class="form-control" placeholder="From Date">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="text-start">Date</label>
                                        <input type="date" name="tdate" id="tdate" value="<?php echo $todate; ?>"
                                            class="form-control" placeholder="To Date">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="controls" style="padding-top:20px;">
                                        <button type="button" class="btn btn-primary" onclick="dateforlist()"
                                            id="RefreshBtn">
                                            Refresh
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button id="exportBtn1" class="btn btn-primary"
                                onclick="ExportToExcel('xlsx','SiteWiseAllSociety')">Excel</button>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation table-hover" id="SiteWiseAllSociety">
                                        <thead>
                                            <tr>
                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                <th style="background-color:#36abb9;color: white;">View</th>
                                                <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                <th style="background-color:#36abb9;color: white;">Plot No</th>
                                                <th style="background-color:#36abb9;color: white;">PocketNo</th>
                                                <th style="background-color:#36abb9;color: white;">Pocket Name</th>
                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                <th style="background-color:#36abb9;color: white;">RoomsDone</th>
                                                <th style="background-color:#36abb9;color: white;" Title="LockRoom">
                                                    LockRoom</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Voters">Voters
                                                </th>
                                                <th style="background-color:#36abb9;color: white;" Title="NonVoters">
                                                    NonVoters</th>
                                                <th style="background-color:#36abb9;color: white;"
                                                    Title="Locked But Survey">LBS</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Mobile">Mobile
                                                </th>
                                                <th style="background-color:#36abb9;color: white;" Title="Birthdate">
                                                    BirDt</th>
                                            </tr>
                                        </thead>
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
<div class="row match-height">
    <div class="col-md-12">
        <center>
            <script src="app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
            <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
            <div class="SiteData" style="display:none" id="SiteData">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="card-title" style="align:center;"> <span
                                                    id="siteDetailTitleName"></span> Detail</h4>
                                        </div>
                                    </div>
                                    <button id="exportBtn1" class="btn btn-primary"
                                        onclick="ExportToExcel('xlsx','SiteNameWiseSurveyTable')">Excel</button>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-hover"
                                                id="SiteNameWiseSurveyTable" name="SiteNameWiseSurveyTable">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;">View</th>
                                                        <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Pocket Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Executive Name
                                                        </th>
                                                        <th style="background-color:#36abb9;color: white;">Total Rooms</th>
                                                        <th style="background-color:#36abb9;color: white;">RoomsDone</th>
                                                        <th style="background-color:#36abb9;color: white;" Title="LockRoom">
                                                            LockRoom</th>
                                                        <th style="background-color:#36abb9;color: white;" Title="Voters">
                                                            Voters</th>
                                                        <th style="background-color:#36abb9;color: white;"
                                                            Title="NonVoters">NonVoters</th>
                                                        <th style="background-color:#36abb9;color: white;"
                                                            Title="Locked But Survey">LBS</th>
                                                        <th style="background-color:#36abb9;color: white;" Title="Mobile">
                                                            Mobile</th>
                                                        <th style="background-color:#36abb9;color: white;"
                                                            Title="Birthdate">BirDt</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </center>
    </div>
</div>

<div class="row match-height">
    <div class="col-md-12">
        <div id="SurveySummaryExecutiveDataLoad" style="display:none;">
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

<script>
    function ExportToExcel(type, TableID) {
        var fn = "";
        var dl = "";
        var elt = document.getElementById(TableID);
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
            XLSX.writeFile(wb, fn || (TableID + '.' + (type || 'xlsx')));
    }
</script>