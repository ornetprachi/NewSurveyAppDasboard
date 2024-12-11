<!-- <div id="tblBuildingListingQCtblscreen"> -->

<?php
    session_start();
include '../api/includes/DbOperation.php'; 
  

// if((isset($_SESSION['Building_Listing_tbl_electionName']) && !empty($_SESSION['Building_Listing_tbl_electionName'])))
// {
    // $electionName = $_SESSION['Building_Listing_tbl_electionName'];
    // $Site_Cd = $_SESSION['Building_Listing_tbl_SiteCd'];
    // $Pocket_Cd = $_SESSION['Building_Listing_tbl_pocketCd'];
    // $fromDate = $_SESSION['Building_Listing_tbl_fromDate'];
    // $toDate = $_SESSION['Building_Listing_tbl_toDate'];
    // $ExecutiveCd = $_SESSION['Building_Listing_tbl_executiveCd'];
    // $QCStatus = $_SESSION['Building_Listing_tbl_QCStatus']; 


    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
        if
        (
          (isset($_GET['electionName']) && !empty($_GET['electionName']))
        )
        {
            $db=new DbOperation();
            $userName=$_SESSION['SurveyUA_UserName'];
            $appName=$_SESSION['SurveyUA_AppName'];
            $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
            $ULB=$_SESSION['SurveyUtility_ULB'];
            
            $election_Cd=$_GET['electionName'];
            $dataElection = $db->getSurveyUtilityCorporationElectionByCdData($ULB,$userName, $appName, $election_Cd,  $developmentMode);
            
            $election_Name = $dataElection["ElectionName"];

            $electionName = $election_Name;
            $Site_Cd = $_GET['SiteCd'];
            $Pocket_Cd = $_GET['pocketCd'];
            $fromDate = $_GET['fromDate'];
            $toDate = $_GET['toDate'];
            $ExecutiveCd = $_GET['executiveCd'];
            $QCStatus = $_GET['QCStatus']; 

        



    if($Site_Cd == "ALL" || $Site_Cd == ""){
        $siteCondition = "";
    }else{
        $siteCondition = " AND Site_Cd = '$Site_Cd' ";
    }
    
    if($Pocket_Cd == "ALL" || $Pocket_Cd == ""){
        $PocketCondition = "";
    }else{
        $PocketCondition = " AND Pocket_Cd = '$Pocket_Cd' ";
    }
    
    
    if($ExecutiveCd == "ALL" || $ExecutiveCd == ""){
        $ExecutiveCondition = "";
    }else{
        $ExecutiveCondition = " AND Executive_Cd = '$ExecutiveCd' ";
    }
    
    if($fromDate == '' && $toDate == ''){
        $dateCondition = "";
    }else{
        $dateCondition = "AND (AddedDate BETWEEN '$fromDate' AND '$toDate')";
    }

    $query1 = "  SELECT Society_Cd
    ,Site_Cd
    ,SiteName
    ,SocietyName
    ,ElectionName
    ,SocietyNameMar
    ,Area
    ,AreaMar
    ,Floor
    ,Rooms
    ,PocketName
    ,Pocket_Cd
    ,Executive_Cd
    ,SequenceCode
    ,Building_Image
    ,Building_Plate_Image
    ,Latitude
    ,Longitude
    ,Sector
    ,PlotNo
    FROM Society_Master 
    WHERE ElectionName = '$electionName' $siteCondition $PocketCondition $ExecutiveCondition  $dateCondition;";
    $db1=new DbOperation();
    // echo $query1;
    $BuildingListingList = $db1->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);
}
}
    // unset($_SESSION['Building_Listing_tbl_electionName']);
// }


?>

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
</style>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
<?php
    //if(isset($_GET['p']) && $_GET['p'] == 'home-dashboard' ){ ?> 
        <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/charts/apexcharts.css">
<?php //}?>
 <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/tether-theme-arrows.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/tether.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/extensions/shepherd-theme-default.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/forms/select/select2.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/datatables.min.css">

<!-- Data List View -->
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/datatables.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/file-uploaders/dropzone.min.css">
<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/tables/datatable/extensions/dataTables.checkboxes.css">
<!-- End Data List View -->

<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/components.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/themes/dark-layout.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/themes/semi-dark-layout.css">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
<!-- <link rel="stylesheet" type="text/css" href="app-assets/css/pages/dashboard-analytics.css"> -->
<link rel="stylesheet" type="text/css" href="app-assets/css/pages/card-analytics.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/tour/tour.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-todo.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-user.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/pages/app-ecommerce-details.css">

<!-- Data List View -->
<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/file-uploaders/dropzone.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/pages/data-list-view.css">

<link rel="stylesheet" type="text/css" href="app-assets/vendors/css/pickers/pickadate/pickadate.css">

<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/validation/form-validation.css">
<link rel="stylesheet" type="text/css" href="app-assets/css/plugins/forms/pickers/form-flat-pickr.css">

<!-- maps -->
<!-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> -->
<!-- End Data List View -->
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="app-assets/css/style.css">
<!-- END: Custom CSS-->


<!-- <div class="row match-height"> -->
    <div class="card">
        <?php
            // print_r("<pre>");
            // print_r($BuildingListingList);
            // print_r("</pre>");
        ?>
        <div class="row">
            <div class="col-xl-12 col-md-12 col-xs-12">
                <div class="card-header">
                    <h4 class="card-title">
                        Building Listing QC List - <?php echo sizeof($BuildingListingList);?>
                    </h4>
                </div>
            </div>
        </div>
        <?php
            // echo $electionName;
            // echo "<br>";
            // echo $Site_Cd;
            // echo "<br>";
            // echo $Pocket_Cd;
            // echo "<br>";
            // echo $fromDate;
            // echo "<br>";
            // echo $toDate;
            // echo "<br>";
            // echo $ExecutiveCd;
            // echo "<br>";
            // echo $QCStatus;
            // echo "<br>";
        ?>
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table zero-configuration table-hover-animation table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Site Name</th>
                                <th>Society Name (ENG)</th>
                                <th>Society Name (MAR)</th>
                                <th>Area Name (ENG)</th>
                                <th>Area Name (MAR)</th>
                                <th>Floor</th>
                                <th>Room</th>
                                <th>Pocket Name</th>
                                <th>Sequence Code</th>
                                <th>Society Photo</th>
                                <th>Society Name Board</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(sizeof($BuildingListingList) > 0){
                                    $srNo = 1;
                                    foreach($BuildingListingList AS $Key=>$value){  
                                    ?>
                                    <tr>
                                        <td><?php echo $srNo++; ?></td>
                                        <td><?php echo $value['SiteName']?></td>
                                        <td><?php echo $value['SocietyName']?></td>
                                        <td><?php echo $value['SocietyNameMar']?></td>
                                        <td><?php echo $value['Area']?></td>
                                        <td><?php echo $value['AreaMar']?></td>
                                        <td><?php echo $value['Floor']?></td>
                                        <td><?php echo $value['Rooms']?></td>
                                        <td><?php echo $value['PocketName']?></td>
                                        <td><?php echo $value['SequenceCode']?></td>
                                        <td>
                                            <img src="<?php echo $value['Building_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;"/>
                                        </td>
                                        <td>
                                            <img src="<?php echo $value['Building_Plate_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;"/>
                                        </td>
                                        <td>
                                            <a onclick="getBuildingListingDataInForm('<?php echo $election_Cd; ?>','<?php echo $value['ElectionName']?>','<?php echo $value['Society_Cd']?>','<?php echo $value['Site_Cd']?>','<?php echo $value['SocietyName']?>','<?php echo $value['SocietyNameMar']?>','<?php echo $value['Area']?>','<?php echo $value['AreaMar']?>','<?php echo $value['Floor']?>','<?php echo $value['Rooms']?>','<?php echo $value['Sector']?>','<?php echo $value['PlotNo']?>','<?php echo $value['Pocket_Cd']?>','<?php echo $value['Latitude']?>','<?php echo $value['Longitude']?>','<?php echo $value['Building_Image']?>','<?php echo $value['Building_Plate_Image']?>')" >
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
            </div>
        </div>
    </div>













    <script src="app-assets/vendors/js/tables/
    
    
    
    
    
    
    
    
    
    /dataTables.select.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    
    <script src="app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>

    <script src="app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>



        <!-- Data List View -->
        <script src="app-assets/vendors/js/extensions/dropzone.min.js"></script>
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script> -->
    <script src="app-assets/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    
    <!-- End Data List View -->

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="app-assets/js/scripts/pages/dashboard-analytics.js"></script> -->
    <script src="app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="app-assets/js/scripts/datatables/datatable.js"></script>

<!-- </div> -->
<!-- </div> -->