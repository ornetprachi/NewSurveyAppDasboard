<section id="dashboard-analytics">

<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];




// $fromDate = "2023-01-01";
$fromDate = date('Y-m-d');
$toDate = date('Y-m-d');

if(
    (isset($_SESSION['SurveyUA_BirthdayReport_fromDate']) && !empty($_SESSION['SurveyUA_BirthdayReport_fromDate'])) &&
    (isset($_SESSION['SurveyUA_BirthdayReport_toDate']) && !empty($_SESSION['SurveyUA_BirthdayReport_toDate'])) 
){
    $fromDate = $_SESSION['SurveyUA_BirthdayReport_fromDate'];
    $toDate = $_SESSION['SurveyUA_BirthdayReport_toDate'];
}else{

}

if(isset($_GET['SiteName']) && !empty($_GET['SiteName'])){
    $SiteName = $_GET['SiteName'];
    
    if(isset($_GET['Date']) && !empty($_GET['Date']) && isset($_GET['Month']) && !empty($_GET['Month']) ){
        $day = $_GET['Date'];  
        $month = $_GET['Month']; 
        $year = date('Y');  

        $fromDate = date('Y-m-d', strtotime("$year-$month-$day"));
        $toDate = $fromDate;
    }
    

    // &SiteName=DB42


$SQL = "SELECT TOP(1) COALESCE(em.DBName,'') AS DBName FROM Site_Master sm 
        INNER JOIN Survey_Entry_Data..Election_Master em ON (em.ElectionName = sm.ElectionName)
        WHERE sm.SiteName ='$SiteName'";
$GetDBName = $db->ExecutveQuerySingleRowSALData($ULB,$SQL , $userName, $appName, $developmentMode);

$DBName = $GetDBName['DBName'];

    $sql2 = "SELECT 
                COALESCE(dw.Society_Cd,0) AS Society_Cd,
                COALESCE(dw.Ac_No,0) AS Ac_No,
                COALESCE(dw.List_No,0) AS List_No,
                COALESCE(dw.Voter_Cd,'') AS Voter_Cd,
                COALESCE(dw.Latitude,'') AS Latitude,
                COALESCE(dw.Longitude,'') AS Longitude,
                COALESCE(dw.IdCard_No,'') AS IdCard_No,
                COALESCE(dw.FullName,'') AS FullName,
                COALESCE(dw.FullNameMar,'') AS FullNameMar,
                COALESCE(dw.MobileNo,'') AS MobileNo,
                COALESCE(dw.Sex,'') AS Sex,
                COALESCE(dw.FamilyNo,'') AS FamilyNo,
                COALESCE(dw.FamilyCount,'') AS FamilyCount,
                COALESCE(dw.RoomNo,'') AS RoomNo,
                COALESCE(dw.Ward_no,'') AS Ward_no,
                COALESCE(dw.BirthDate,'') AS BirthDate
            FROM Dw_VotersInfo AS dw
            WHERE
                dw.SiteName = '$SiteName' AND 
            DATEPART(month, CONVERT(varchar,BirthDate,23)) * 100 + DATEPART(day, CONVERT(varchar,BirthDate,23)) 
            BETWEEN MONTH('$fromDate') * 100 + DAY('$fromDate') 
            AND MONTH('$toDate') * 100 + DAY('$toDate')
            AND dw.SF = 1

            UNION ALL
            SELECT
                COALESCE(nv.Society_Cd,0) AS Society_Cd,
                COALESCE(nv.Ac_No,0) AS Ac_No,
                COALESCE(nv.List_No,0) AS List_No,
                COALESCE(nv.Voter_Cd,'') AS Voter_Cd,
                COALESCE(nv.Latitude,'') AS Latitude,
                COALESCE(nv.Longitude,'') AS Longitude,
                NULL AS IdCard_No,
                COALESCE(nv.FullName,'') AS FullName,
                NULL AS FullNameMar,
                COALESCE(nv.MobileNo,'') AS MobileNo,
                COALESCE(nv.Sex,'') AS Sex,
                COALESCE(nv.FamilyNo,'') AS FamilyNo,
                COALESCE(nv.FamilyCount,'') AS FamilyCount,
                COALESCE(nv.RoomNo,'') AS RoomNo,
                COALESCE(nv.Ward_no,'') AS Ward_no,
                COALESCE(nv.BirthDate,'') AS BirthDate
            FROM NewVoterRegistration AS nv
            WHERE
                nv.SiteName = '$SiteName' AND 
            DATEPART(month, CONVERT(varchar,BirthDate,23)) * 100 + DATEPART(day, CONVERT(varchar,BirthDate,23)) 
            BETWEEN MONTH('$fromDate') * 100 + DAY('$fromDate') 
            AND MONTH('$toDate') * 100 + DAY('$toDate');
            ";

    $CountListMain = $db->ExecutveQueryMultipleRowSALData($ULB,$sql2, $userName, $appName, $developmentMode);

    $CountOFbirthday = sizeof($CountListMain);
    
// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
        background-color: #e6f4f4;
    }
</style>

<div class="row match-height" style="display:block;">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php //include 'dropdown-site-Birthday-Report.php'; ?>
                            </div> -->
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="getDatesForBirthdayReport('<?php echo $SiteName;?>')">
                                            Refresh 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>

<div class="row" id="">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Birthday Count - ( <?php echo $CountOFbirthday; ?> )
                </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Name</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Mobile</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">BirthDate</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Gender</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo  $value['FullName'];?></td>
                                                                <td><?php echo  $value['MobileNo'];?></td>
                                                                <td><?php echo  date_format($value['BirthDate'],"d-m-Y");?></td>
                                                                <td><?php echo  $value['Sex'];?></td>
                                                                <td> 
                                                                    <a onclick="getVoctersDetail('<?php echo  $value['Voter_Cd'];?>','<?php echo $DBName;?>')">
                                                                        <i style="color:#41bdcc;cursor:pointer;" class="feather icon-grid"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a href="<?php echo 'index.php?p=BirthdayFamilyListView&FamilyNo='.$value['FamilyNo'].'&DBName='.$DBName  ?>" target="_blank">
                                                                        <i style="color:#41bdcc;cursor:pointer;" class="feather icon-list"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a title="Map View"  target="_blank" href="<?php echo 'https://www.google.com/maps/search/?api=1&query='.$value['Latitude'].','.$value['Longitude'].'' ; ?>">
                                                                        <i class="feather icon-map-pin" style="color:#41bdcc;"></i>
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
<!-- <center> -->
<div id="BdayGridView" class="BdayGridView">
</div>
<!-- </center> -->
<?php } ?>
</section>