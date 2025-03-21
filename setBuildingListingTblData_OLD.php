
<section id="dashboard-analytics">

<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

$fromDate = '';
$toDate = '';
$fromDate = date('Y-m-d');
$toDate = date('Y-m-d');
$assignDate = date('Y-m-d');

// $electionName = "";
$Site_Cd = "";
$Pocket_Cd = "";
$ExecutiveCd = "";
$QCStatus = 0; 
$BuildingListingList = array(); 


if(isset($_SESSION['SurveyUA_Election_Cd']) && isset($_SESSION['SurveyUA_ElectionName']))
{
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];
}


if(isset($_SESSION['SurveyUA_SiteCd_Building_Listing']) && isset($_SESSION['SurveyUA_SiteName_Building_Listing'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_Building_Listing'];
    $SiteName = $_SESSION['SurveyUA_SiteName_Building_Listing'];
}else{
    $Site_Cd = "ALL";
    $SiteName = '';
}


if(
    isset($_SESSION['Building_Listing_tbl_pocketCd']) && 
    isset($_SESSION['Building_Listing_tbl_fromDate']) && 
    isset($_SESSION['Building_Listing_tbl_toDate']) && 
    isset($_SESSION['Building_Listing_tbl_executiveCd']) &&
    isset($_SESSION['Building_Listing_tbl_QCStatus']) 
)
{
    $Pocket_Cd = $_SESSION['Building_Listing_tbl_pocketCd'];
    $fromDate = $_SESSION['Building_Listing_tbl_fromDate'];
    $toDate = $_SESSION['Building_Listing_tbl_toDate'];
    $ExecutiveCd = $_SESSION['Building_Listing_tbl_executiveCd'];
    $QCStatus = $_SESSION['Building_Listing_tbl_QCStatus']; 

}

if($Site_Cd == "ALL" || $Site_Cd == ""){
    $siteCondition = "";
}else{
    $siteCondition = " AND sm.Site_Cd = '$Site_Cd' ";
}

if($Pocket_Cd == "ALL" || $Pocket_Cd == ""){
    $PocketCondition = "";
}else{
    $PocketCondition = " AND sm.Pocket_Cd = '$Pocket_Cd' ";
}


if($ExecutiveCd == "ALL" || $ExecutiveCd == ""){
    $ExecutiveCondition = "";
}else{
    $ExecutiveCondition = " AND sm.Executive_Cd = '$ExecutiveCd' ";
}

if($fromDate == '' && $toDate == ''){
    $dateCondition = "";
}else{
    $dateCondition = "AND CONVERT(VARCHAR,sm.BList_UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'";
}

//todo add coalesce to all select item
$query1 = "  SELECT 
    COALESCE(Society_Cd,0) AS Society_Cd
    ,COALESCE(Site_Cd, 0) AS Site_Cd
    ,COALESCE(SiteName, '') AS SiteName
    ,COALESCE(SocietyName, '') AS SocietyName
    ,COALESCE(ElectionName, '') AS ElectionName
    ,COALESCE(SocietyNameMar, '') AS SocietyNameMar
    ,COALESCE(Area, '') AS Area
    ,COALESCE(AreaMar, '') AS AreaMar
    ,COALESCE(Floor, '') AS Floor
    ,COALESCE(Rooms, 0) AS Rooms
    ,COALESCE(PocketName, '') AS PocketName
    ,COALESCE(Pocket_Cd, 0) AS Pocket_Cd
    ,COALESCE(Executive_Cd, 0) AS Executive_Cd
    ,COALESCE(SequenceCode, 0) AS SequenceCode
    ,COALESCE(Building_Image, '') AS Building_Image
    ,COALESCE(Building_Plate_Image, '') AS Building_Plate_Image
    ,COALESCE(Latitude, '') AS Latitude
    ,COALESCE(Longitude, '') AS Longitude
    ,COALESCE(Sector, '') AS Sector
    ,COALESCE(PlotNo, '') AS PlotNo
    ,COALESCE(BList_QC_UpdatedFlag , '') AS BList_QC_UpdatedFlag 
    FROM Society_Master 
    WHERE ElectionName = '$electionName' $siteCondition $PocketCondition $ExecutiveCondition  $dateCondition 
    AND BList_QC_UpdatedFlag = $QCStatus";


$sql1 = "SELECT 
            COALESCE(sm.Society_Cd,0) AS Society_Cd
            ,COALESCE(sm.Site_Cd, 0) AS Site_Cd
            ,COALESCE(sm.SiteName, '') AS SiteName
            ,COALESCE(sm.SocietyName, '') AS SocietyName
            ,COALESCE(sm.ElectionName, '') AS ElectionName
            ,COALESCE(sm.SocietyNameMar, '') AS SocietyNameMar
            ,COALESCE(sm.Area, '') AS Area
            ,COALESCE(sm.AreaMar, '') AS AreaMar
            ,COALESCE(sm.Floor, '') AS Floor
            ,COALESCE(sm.Rooms, 0) AS Rooms
            ,COALESCE(sm.PocketName, '') AS PocketName
            ,COALESCE(sm.Pocket_Cd, 0) AS Pocket_Cd
            ,COALESCE(sm.Executive_Cd, 0) AS Executive_Cd
            ,COALESCE(sm.SequenceCode, 0) AS SequenceCode
            ,COALESCE(sm.Building_Image, '') AS Building_Image
            ,COALESCE(sm.Building_Plate_Image, '') AS Building_Plate_Image
            ,COALESCE(sm.Latitude, '') AS Latitude
            ,COALESCE(sm.Longitude, '') AS Longitude
            ,COALESCE(sm.Sector, '') AS Sector
            ,COALESCE(sm.PlotNo, '') AS PlotNo
            ,COALESCE(sm.BList_QC_UpdatedFlag , '') AS BList_QC_UpdatedFlag 
            ,COALESCE(CONVERT(VARCHAR,BList_UpdatedDate,34), '') AS BList_UpdatedDate 
            ,COALESCE(em.ExecutiveName , '') AS ExecutiveName 
            ,COALESCE(em.MobileNo , '') AS MobileNo
        FROM Survey_Entry_Data..Society_Master sm 
        INNER JOIN User_Master um ON (um.UserName = sm.BList_UpdatedByUser)
        INNER JOIN Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
        WHERE sm.ElectionName = '$electionName' $siteCondition $PocketCondition $dateCondition
        AND sm.BList_QC_UpdatedFlag = $QCStatus ;
        ";

$db1=new DbOperation();
// echo $query1;
$BuildingListingList = $db->ExecutveQueryMultipleRowSALData($sql1, $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($BuildingListingList);
// print_r("</pre>");

?>

<script>document.body.style.zoom="90%"</script>
<style type="text/css">
    

/* 
    img.center_1 {
    /* vertical-align: middle; */
    /* margin-left: 178px;
    border-style: none; }*/
    img.docimg{

        transition: 0.4s ease;
        transform-origin: 10% 30%;

    }
    img.docimg:hover{
        z-index: 9999999990909090990909;
        transform: scale(5.2); 
        position: relative;
    }
    img.docimg1{
        transition: 0.4s ease;
        transform-origin: 10% 30%;

    }
    img.docimg1:hover{
        z-index: 9999999990909090990909;
        transform: scale(10.2); 
    }

    img.docimg2{
        transition: 0.4s ease;
        transform-origin: 10% 30%;

    }
    img.docimg2:hover{
        z-index: 9999999990909090990909;
        transform: scale(3.2); 
    }

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
</style>


<style>
    .table-container {
        position: relative; /* add position relative to container */
        text-align: center;
        align-items: center;
    }
    .table {
        border-collapse: collapse;
        width: 100%; /* subtract the scrollbar width */
        /* margin-top: 7px; */
    }

    .table td,
    .table th {
        padding: 0.75px;
        margin: 0;
    }

    .table th {
        background-color:#36abb9 ;
        color: white;
        /* background-color: #f2f2f2; */
        position: sticky; /* add position sticky to header */
        top: 0; /* add top property to position header at top */
        z-index: 1; /* add z-index to ensure header stays above content */
        }

    .table tr {
        padding: 0;
        margin: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    td {
        border: 1px solid grey;
        padding: 8px;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: 85px;
    }

</style>
   
   

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                    
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-electionname-building-listing.php'; ?>
                            </div>

                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-site-building-listing.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-building-listing-pocket.php'; ?>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>QC Status</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="QCStatus" value="<?php echo $QCStatus; ?>">
                                            <option value="0" <?php if($QCStatus == "0"){ ?> selected <?php } ?>>Pending</option>
                                            <option value="1" <?php if($QCStatus == "1"){ ?> selected <?php } ?>>Done</option>                                               
                                            <option value="2" <?php if($QCStatus == "2"){ ?> selected <?php } ?>>Rejected</option>                                               
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="Assign Date" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="Assign Date" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-4 col-md-4 col-12">
                                <?php include 'dropdown-executive-building-list.php'; ?>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="getBuildingListingTableFilterData()">
                                            Refresh 
                                    </button>
                                </div>
                            </div>


                            <div class="col-xs-12 col-md-12 col-xl-12" >
                                <input class="form-control form-control-sm basic" type="hidden" name="society_cds">
                                <span id="idAssignPocketMsg" class="btn btn-success" style="display: none;"></span>
                                 <span id="idAssignPocketMsgSuccess" class="btn btn-success" style="display: none;"></span>
                                <span id="idAssignPocketMsgFailure" class="btn btn-danger" style="display: none;"></span>
                            </div>
                        </div>
                        <!-- <?php //if($QCStatus != '1'){?>
                            <hr class="mt-0">
                            <div class="row mt-0">
                                <div class="col-xs-6 col-xl-10 col-md-10 col-12" >
                                    <label for="empName">Society Name</label>
                                    <input type=text  class="form-control " id="societyNames" name="societyNames" placeholder="Select or Check Society Names from Below List"   onkeydown="return false;" required style="caret-color: transparent !important;"   > 
                                </div>
                                <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                    <div class="controls text-center" style="margin-top:25px">
                                        <button type="button" class="btn btn-primary float-right" onclick="saveBuildingListingQCcheckbox()">
                                                QC Done 
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php //} ?>
                        <div class="row mt-2">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                        </div>  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>
<div class="row match-height" id="tblBuildingListingQCtbl">
    <div class="col-xs-12 col-xl-4 col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Building Listing QC List - <?php echo sizeof($BuildingListingList);?>
                </h4>
            </div>
        
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  table-hover-animation table-striped table-hover" id="BuildingListingQCList">
                            <tbody>
                                <?php
                                    if(sizeof($BuildingListingList) > 0){
                                        $srNo = 1;
                                        foreach($BuildingListingList AS $Key=>$value){  
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="row" style="padding:10px">
                                                    <div class="col-xs-12 col-xl-12 col-md-12">
                                                        <h6 class="mb-1"> <?php echo $srNo++.")  <b>".$value["SocietyName"]."</b><br style='margin-top:15px'> ".$value["SocietyNameMar"]; ?></h6>
                                                    </div>
                                                    <div class="col-xs-11 col-xl-6 col-md-6">
                                                        
                                                        <?php echo "<b>Floors</b> : ".$value["Floor"] . "<br><b>Rooms</b> : " .$value["Rooms"] . "<br><b>Area</b> : ".$value["Area"]; ?>
                                                                        
                                                        <h6><?php echo "<b>Survey Date</b> : ". date('d/m/Y', strtotime($value["BList_UpdatedDate"])); ?>
                                                        <?php 
                                                        
                                                        if (array_key_exists("ExecutiveName",$value)){
                                                            if(strpos($value["ExecutiveName"], " ") !== false){
                                                                $executiveArr = explode(" ", $value["ExecutiveName"]);
                                                                $executive = $executiveArr[0];
                                                            }else{
                                                                $executive = $value["ExecutiveName"];
                                                            }
                                                            $ExecutiveFullName = $value['ExecutiveName'];
                                                            $MobileNo = $value['MobileNo'];
                                                        }else{
                                                            $ExecutiveFullName = '';
                                                            $MobileNo = '';
                                                        }
                                                        echo "</br><b>Survey By</b> : <div class= 'p-0 m-0' title='". $ExecutiveFullName ."'>".$executive . "</div>" . $MobileNo; 
                                                        ?>
                                                        </h6>

                                                                        
                                                    </div>
                                                    <div class="col-xs-11 col-xl-5 col-md-5">
                                                        <img src="<?php echo $value['Building_Image']; ?>" class="rounded" height="120" width="100" alt="Building Photo" title="Building Photo" />
                                                        <h6></h6>
                                                    </div>
                                                    <div class="col-xs-1 col-xl-1 col-md-1">
                                                        <div id="check_QC" name="check_QC" style="margin-left: -30px;padding: 10px;cursor: pointer" class="badge badge-<?php  if ($value['BList_QC_UpdatedFlag'] != 1) { ?>danger<?php } else { ?>success<?php } ?>"
                                                            onclick="getBuildingListingDataInFormNew('<?php echo $electionCd; ?>','<?php echo $value['ElectionName']?>','<?php echo $value['Society_Cd']?>','<?php echo $value['Site_Cd']?>','<?php echo $value['SiteName']?>','<?php echo $value['SocietyName']?>','<?php echo $value['SocietyNameMar']?>','<?php echo $value['Area']?>','<?php echo $value['AreaMar']?>','<?php echo $value['Floor']?>','<?php echo $value['Rooms']?>','<?php echo $value['Sector']?>','<?php echo $value['PlotNo']?>','<?php echo $value['Pocket_Cd']?>','<?php echo $value['Latitude']?>','<?php echo $value['Longitude']?>','<?php echo $value['Building_Image']?>','<?php echo $value['Building_Plate_Image']?>')">
                                                            QC
                                                        </div>
                                                    

                                                    </div>
                                                </div>
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
    <div id='spinnerLoader1' style='display:none'>
        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
    </div>
    <div class="col-xs-12 col-xl-8 col-md-12 col-12" id="BuildingListingQCDataId" >

    </div>
</div>

<script>
$(document).ready(function() {
    "use strict"
    $('#BuildingListingQCList').DataTable({
        responsive: true,
        columnDefs: [
            {
                orderable: false,
                targets: 0,
            }
        ],
        ordering:false,
        bInfo: false,
        lengthChange:false,
        pageLength: 10,
        paging:true
    });
});
</script>

</section>
