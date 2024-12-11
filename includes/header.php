<?php 

    // header("Content-Security-Policy: default-src 'self';");

    include 'checksession.php';
    include 'api/includes/DbOperation.php';
    $page = $_SERVER['PHP_SELF'];
    $pageName = basename($page);

    $containmentZoneMapAndListDetail = array();
    $dataPocketMapAndListSummary = array();
    $pocketShopsSurveyMapAndListDetail = array();

        
    // ULB AND SERVERNAME
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $dbULB = new DbOperation();
    $dataULD_Names = $dbULB->getSurveyUtilityULB_Data($userName, $appName, $developmentMode);
    if(isset($_SESSION['SurveyUtility_ULB']) && isset($_SESSION['SurveyUtility_ServerIP'])){
        // && isset($_SESSION['SurveyUtility_ServerPassword'])
        $ULB = $_SESSION['SurveyUtility_ULB'];
        $ServerName = $_SESSION['SurveyUtility_ServerIP'];
        $ele_cd = $_SESSION['SurveyUA_Election_Cd'];
        $ele = $_SESSION['SurveyUA_ElectionName'];
        // $ServerPwd = $_SESSION['SurveyUtility_ServerPassword'];
        $_SESSION['SurveyUtility_ULB'] = $ULB;
        $_SESSION['SurveyUtility_ServerIP'] = $ServerName;
        $_SESSION['SurveyUA_Election_Cd'] =  $ele_cd;
        $_SESSION['SurveyUA_ElectionName'] = $ele;
        // $_SESSION['SurveyUtility_ServerPassword'] = $ServerPwd;
    }else{
        // $ULB = $dataULD_Names[0]['ULB'];
        // $ServerName = $dataULD_Names[0]['ServerName'];
        $ULB = 'PANVEL_SURVEY_TEST';
        $ServerName = '52.140.77.2';
        $ele_cd = '145';
        $ele = 'PANVEL_SURVEY_TEST';
        // $ServerName = '92.204.145.32';
        // $ServerPwd = $dataULD_Names[0]['ServerPwd'];
        $_SESSION['SurveyUtility_ULB'] = $ULB;
        $_SESSION['SurveyUtility_ServerIP'] = $ServerName;
        $_SESSION['SurveyUA_Election_Cd'] =  $ele_cd;
        $_SESSION['SurveyUA_ElectionName'] = $ele;
        // $_SESSION['SurveyUtility_ServerPassword'] = $ServerPwd;
        $url = basename($_SERVER['PHP_SELF']);
        header('location:'.$url.'');
    }

    // ULB AND SERVERNAME
    $dbElection = new DbOperation();
    $dataElectionName = $dbElection->getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode);
// print_r($dataElectionName);
    if(isset($_SESSION['SurveyUA_Election_Cd'])){
        if($_SESSION['SurveyUA_Election_Cd'] == 0){
          $election_Cd = $dataElectionName[0]['Election_Cd'];
          $electionName = $dataElectionName[0]['ElectionName'];
          $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
          $_SESSION['SurveyUA_ElectionName'] = $electionName;
        }else{
            $election_Cd = $_SESSION['SurveyUA_Election_Cd'];
            $electionName = $_SESSION['SurveyUA_ElectionName'];
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            $_SESSION['SurveyUA_ElectionName'] = $electionName;
        }
    }else{
        $election_Cd = $dataElectionName[0]['Election_Cd'];
        $electionName = $dataElectionName[0]['ElectionName'];
        $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
        $_SESSION['SurveyUA_ElectionName'] = $electionName;
    }

$ExcelExportButton = "";
if($_SESSION['SurveyUA_Mobile'] == "9820480368"
    || $_SESSION['SurveyUA_Mobile'] == "9820480999"
    || $_SESSION['SurveyUA_Mobile'] == "9820743654"
    || $_SESSION['SurveyUA_Mobile'] == "9223575189"
    || $_SESSION['SurveyUA_Mobile'] == "9223575193"
    || $_SESSION['SurveyUA_Mobile'] == "8097485495"
    || $_SESSION['SurveyUA_Mobile'] == "9969787575"
    || $_SESSION['SurveyUA_Mobile'] == "7045991170"
    || $_SESSION['SurveyUA_Mobile'] == "7700998602"
    || $_SESSION['SurveyUA_Mobile'] == "7400272333"
    || $_SESSION['SurveyUA_Mobile'] == "9920480368"
    || $_SESSION['SurveyUA_Mobile'] == "9757427575"
    || $_SESSION['SurveyUA_Mobile'] == "7721036013"
    || $_SESSION['SurveyUA_Mobile'] == "9892521519"
    || $_SESSION['SurveyUA_Mobile'] == "9356338373"
    || $_SESSION['SurveyUA_Mobile'] == "7039797103"
    
){
    $ExcelExportButton = "show";
 }else{
    $ExcelExportButton = "hide";
 }
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
 
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Survey Utility Application..">
    <meta name="keywords" content="Survey Utility Application">
    <meta name="author" content="ORNET">
    <title> 
            Survey Utility | Dashboard 
    </title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">
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
    <!-- Prevent Screen Shots and Right Click and Copy Paste and Print-->
    <style>
         
        /* We are stopping user from
        printing our webpage */
        @media print {
 
            html,
            body {
 
                /* Hide the whole page */
                display: none;
            }
        }
    </style>

   

    <?php
        if($_SESSION['SurveyUA_Mobile'] != "9967972847"
         && $_SESSION['SurveyUA_Mobile'] != "8828259020" 
         && $_SESSION['SurveyUA_Mobile'] != "7738779669" 
         && $_SESSION['SurveyUA_Mobile'] != "8097485495" 
         && $_SESSION['SurveyUA_Mobile'] != "9324495912" 
         && $_SESSION['SurveyUA_Mobile'] != "9137823017"
         && $_SESSION['SurveyUA_Mobile'] != "7498811704"
         && $_SESSION['SurveyUA_Mobile'] == "9892521519"
         && $_SESSION['SurveyUA_Mobile'] == "9967965953"
         && $_SESSION['SurveyUA_Mobile'] == "9867901429"
         && $_SESSION['SurveyUA_Mobile'] == "8850011594"
         ){
    ?>
         <script>
            document.addEventListener('contextmenu', event => event.preventDefault());
        </script>
        <script>
            document.addEventListener('copy', event => event.preventDefault());
            document.addEventListener('paste', event => event.preventDefault());
        </script>    
        <style>
            html {
                    user-select: none;
                }
        </style>
    <?php
        }
    ?>
    
    
    <!-- Prevent Screen Shots and Right Click and Copy Paste and Print -->

    <script src="includes/ajaxscript.js"></script>
    <script>
        function IND_money_format($number){
        $decimal = (string)($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for($i=0;$i<$length;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                $delimiter .=',';
            }
            $delimiter .=$money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if( $decimal != '0'){
            $result = $result.$decimal;
        }

        return $result;
    }
    </script>

 
<style>
    .myrow img:hover {
      -webkit-transform: scale(1.05);
      -moz-transform: scale(1.05);
      -o-transform: scale(1.05);
      -ms-transform: scale(1.05);
      transform: scale(3.05);
      z-index: 5;
    }

    .myrow img {
      -webkit-transition: -webkit-transform .15s ease;
      -moz-transition: -moz-transform .15s ease;
      -o-transition: -o-transform .15s ease;
      -ms-transition: -ms-transform .15s ease;
      transition: transform .15s ease;
      position: relative;
    }

    .clear {
      clear: both;
      float: none;
      width: 100%;
    }

    .pagination{
                margin: 3px;
                float: right;
            }
            .pagination a.active {
                background-color: #1F8D4E;
                color: white;
                
                border: 0px solid #1F8D4E;
            }

            .pagination a:hover:not(.active) {background-color: #ddd;}

            .pagination a {
              
              color: black;
              float: left;
              padding: 4px 8px;
              text-decoration: none;
              transition: background-color .3s;
              border: 1px solid #ddd;
              /*margin: 0.3px;*/

            }

            
            .pagination a:first-child {
              border-top-left-radius: 5px;
              border-bottom-left-radius: 5px;
            }

            .pagination a:last-child {
              border-top-right-radius: 5px;
              border-bottom-right-radius: 5px;
            }

            .paginate_button .page-item {
                width:30px;
            }
           
            .main-menu.menu-light .navigation > li.active > a {
                background: -webkit-linear-gradient(332deg, #1F8D4E, rgba(31, 141, 78, 0.7));
                background: linear-gradient(118deg, #41bdcc, rgba(31, 141, 78, 0.7));
                box-shadow: 0 0 10px 1px rgb(31, 141, 78 / 70%);
                color: #FFFFFF;
                font-weight: 400;
                border-radius: 4px;
            }

            [data-letters]:before {
                content: attr(data-letters);
                display: inline-block;
                font-size: 1.25em;
                width: 36px;
                height: 36px;
                margin-top: 8px;
                padding-top: 8px;
                /* line-height: 2.5em; */
                text-align: center;
                border-radius: 50%;
                background: #41bdcc;
                vertical-align: middle;
                margin-right: 1em;
                color: white;
            }
        <?php //if($_SESSION['SurveyUA_Mobile'] == '9820480368'){ ?>
            /* body.vertical-layout.vertical-menu-modern.menu-collapsed .app-content, body.vertical-layout.vertical-menu-modern.menu-collapsed .footer {
                margin-left: 0px;
            }
            body.vertical-layout.vertical-menu-modern.menu-collapsed .header-navbar.floating-nav {
                width: calc(100vw - (100vw - 100%) - 0rem - 60px);
            } */
        <?php //} ?>
</style>
   
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
  <?php //if( ( isset($_GET['p']) && ( $_GET['p'] == 'tree-census-grid' || $_GET['p'] == 'tree-census-list' || $_GET['p'] == 'tree-census-map' ||   $_GET['p'] == 'tree-census-qc' ) ) ){ ?>  
 
 <body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  pace-done menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

<?php //}else{  ?>
  <!--   <body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns"> -->
<?php //} ?>


    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav">
                            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu primary"></i></a></li>
                        </ul> 
                        <ul class="nav navbar-nav bookmark-icons">
                            <!-- li.nav-item.mobile-menu.d-xl-none.mr-auto-->
                            <!--   a.nav-link.nav-menu-main.menu-toggle.hidden-xs(href='#')-->
                            <!--     i.ficon.feather.icon-menu-->
                            <li class="nav-item">
                                <a class="nav-link" href="index.php" data-toggle="tooltip" data-placement="top" >
                                <!-- <i class="ficon feather icon-check-square"></i> -->

                                    <h2 class="brand-text mb-0 "> 
                                        <?php  //if($_SESSION['SurveyUA_Mobile'] == '9820480368'){ }else{ echo "ADMIN"; }?>
                                        <?php  echo "ADMIN"; ?>
                                        <?php if(!isset($_GET['p']) || ( isset($_GET['p']) && $_GET['p'] == 'home-dashboard') ){ ?>  
                                            Survey Utility Dashboard
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'survey-utility-pocket-assign' ){ ?> 
                                                Pocket Assign 
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Analysis_report' ){ ?> 
                                                Analysis 
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'pocket-master' ){ ?> 
                                                Pocket Master
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'survey-utility-society-assign' ){ ?> 
                                                Society Assign
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'survey-utility-average-count' ){ ?> 
                                                Average Count
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'building-listing-qc' ){  ?> 
                                                Building Listing
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'BuildingSurvey' ){ ?>
                                                Building Listing QC
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'BList_Summary_Report' ){  ?> 
                                                Building Listing Summary Report
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'QC_Assign' ){ ?>
                                                QC Assign
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Survey_QC' ){ ?>
                                                Survey QC
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Survey-QC-DateWise' ){ ?>
                                                Survey QC DateWise
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_Report' ){ ?>
                                                Survey Summary Report
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'building-listing-grid' ){ ?>
                                                Building Listing Detailed View - Grid
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'building-listing-list' ){ ?>
                                                Building Listing Detailed View - List
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'MapClientDashboard' ){ ?>
                                                Map Client Dashboard
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Client-Dashboard' ){ ?>
                                                Client Dashboard
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'Birthday-report' ){ ?>
                                                Birthday Report Summary
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'assign-executive-to-site' ){ ?>
                                            : Assign Executive To Site
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'assigned-executive-site-transfer' ){ ?>
                                            : Transfer Assigned Executive To Site
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'survey-salary-process' ){ ?>
                                            : Process Survey Executive Salary
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'AttendanceModule' ){ ?>
                                            : Attendance
                                        <?php }elseif(isset($_GET['p']) && $_GET['p'] == 'TransferScocietyData' ){ ?>
                                            : Transfer Society
                                        <?php } ?>
                                    </h2>
                                </a>
                                
                            </li>
                            
                        </ul>
                        
                    </div>
             

                    <ul class="nav navbar-nav align-items-center ml-auto">
                        
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                        
                        <style>
                        [data-ULB]:before {
                            content: attr(data-ULB);
                            display: inline-block;
                            font-size: 1.25em;
                            font-weight:bold;
                            width: 100%;
                            height: 36px;
                            margin-top: 8px;
                            padding-top: 8px;
                            text-align: center;
                            border-radius: 10%;
                            background: #41bdcc;
                            vertical-align: middle;
                            margin-right: 1em;
                            color: white;
                        }
                        </style>
                        <div class="row match-height" id='LoaderBeforeLoadMainDataDIV' style="display:none;margin-top:20px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group  col-md-12 has-info" id="loaderId" style="text-align:center;">
                                            <img src="app-assets/loader/loading.gif" width="50" height="30">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <!-- <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600"></span><span class="user-status">
                                        <?php //echo $_SESSION['SurveyUtility_ULB'];?>
                                    </span>
                                </div> -->
                                 <span class="d-sm-inline-block" data-ULB="<?php echo $_SESSION['SurveyUtility_ULB'];?>"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php 
                                if(sizeof($dataULD_Names) > 0){
                                    foreach($dataULD_Names AS $key=>$value){
                                      
                                       if(($value['ULB'] == 'TMC' && $value['ServerName'] == '103.14.99.154') || ($value['ULB'] == 'UMC' && $value['ServerName'] == '103.14.97.58')){
                                        continue;
                                       }    ?>
                                            <a class="dropdown-item" onclick="setULDandServerNameIsSession('<?php echo $value['ULB'];?>','<?php echo $value['ServerName'];?>','<?php echo $value['ElectionName'];?>','<?php echo $value['Election_Cd']; ?>');"><?php if($value['ULB'] == 'MIRA-BHAINDER'){ echo 'MBMC';}elseif($value['ServerName'] == '103.14.97.58' && $value['ULB'] == 'NMMC'){echo "VN_151";}else{echo $value['ULB']; }?></a>
                                            <?php
                                    }
                                }
                                ?>
                            </div>
                        </li>

                        <li class="dropdown dropdown-user nav-item">
                               
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name text-bold-600"></span><span class="user-status"><?php echo $_SESSION['SurveyUA_FullName']; ?></span>
                                </div>
                                  <!--<span>
                                    <img class="round" src="app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="40" width="40"></span> -->
                                      <?php  
                                  $fulNameInitial = "";
                                  $fulNameArray = explode(" ", $_SESSION['SurveyUA_FullName']);
                                  if(sizeof($fulNameArray)>1){
                                    $nameInitial1 = $fulNameArray[0]; 
                                    $nameInitial2 = $fulNameArray[1]; 
                                    $fulNameInitial = strtoupper(substr($nameInitial1, 0, 1)."".substr($nameInitial2, 0, 1));
                                  }else{
                                    $nameInitial1 = $fulNameArray[0]; 
                                    $fulNameInitial = strtoupper(substr($nameInitial1, 0, 1));
                                  }
                                ?>
                                 <span class="d-none d-sm-inline-block ml-1" data-letters="<?php echo $fulNameInitial; ?>"></span>
              
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <!-- <a class="dropdown-item" href="page-user-profile.html"><i class="feather icon-user"></i> Edit Profile</a>
                                <a class="dropdown-item" href="app-email.html"><i class="feather icon-mail"></i> My Inbox</a>
                                <a class="dropdown-item" href="app-todo.html"><i class="feather icon-check-square"></i> Task</a>
                                <a class="dropdown-item" href="app-chat.html"><i class="feather icon-message-square"></i> Chats</a>
                                <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="logout.php"><i class="feather icon-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion d-flex align-items-center justify-content-between cursor-pointer"><a class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="mr-75 feather icon-alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" >
    <!-- <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true"  <?php //if($_SESSION['SurveyUA_Mobile'] == '9820480368'){ echo "style='display:none;'"; } ?>> -->
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="index.php">
                        <div class="brand-logo" ></div>
                        <h2 class="brand-text mb-0" >Survey Utility</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
             <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            <?php   
                if($_SESSION['SurveyUA_Mobile'] == "9820480368"
                    || $_SESSION['SurveyUA_Mobile'] == "9820480999"
                    || $_SESSION['SurveyUA_Mobile'] == "9820743654"
                    || $_SESSION['SurveyUA_Mobile'] == "9223575189"
                    || $_SESSION['SurveyUA_Mobile'] == "9223575193"
                    || $_SESSION['SurveyUA_Mobile'] == "8097485495"
                    || $_SESSION['SurveyUA_Mobile'] == "9969787575"
                    || $_SESSION['SurveyUA_Mobile'] == "7045991170"
                    || $_SESSION['SurveyUA_Mobile'] == "7700998602"
                    || $_SESSION['SurveyUA_Mobile'] == "7400272333"
                    || $_SESSION['SurveyUA_Mobile'] == "9920480368"
                    || $_SESSION['SurveyUA_Mobile'] == "9757427575"
                    || $_SESSION['SurveyUA_Mobile'] == "7721036013"
                    || $_SESSION['SurveyUA_Mobile'] == "9892521519"
                    || $_SESSION['SurveyUA_Mobile'] == "8291839797"
                    || $_SESSION['SurveyUA_Mobile'] == "8898427229"
                    || $_SESSION['SurveyUA_Mobile'] == "7498090725"
                ){
            ?>
                        
                        <li <?php if(!isset($_GET['p']) || ( isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_Report') ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php" target="_blank"><i class="feather icon-home"></i><span class="menu-item" data-i18n="Dashboard">Dashboard</span></a>
                        </li>

                         <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Client-Dashboard' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Client-Dashboard" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="Client-Dashboard">Client Dashboard</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Analysis_report' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Analysis_report" target="_blank"><i class="feather icon-bar-chart"></i><span class="menu-item" data-i18n="Client-Dashboard">Analysis</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Birthday-report' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Birthday-report" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="Birthday-report">Birthdate Report</span></a>
                        </li>
                        
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'ShakhaMasterList' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=ShakhaMasterList" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="ShakhaMasterList">Shakha Master</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'KaryakartaMaster' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=KaryakartaMaster" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="KaryakartaMaster">Karyakarta</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'http://103.14.97.58:1613/' ){ ?>   class="active"  <?php } ?> >
                            <a href="http://103.14.97.58:1613/" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="LocationSeg">Location Segregation</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'LossOfHourReport' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=LossOfHourReport" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="LossOfHourReport">LossOfHour Report</span></a>
                        </li>

                        <!-- <li style="margin-top:5px;margin-bottom:-5px;" class="navigation-header"><span>Masters</span></li> -->
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-check-square"></i><span class="menu-title" data-i18n="Masters">Masters</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'pocket-master' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=pocket-master" target="_blank"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Pocket Master">Pocket Master</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'site-master' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=site-master" target="_blank"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Site Master">Site Master</span></a>
                                </li>
                            </ul>
                        </li>

                        <!-- <li style="margin-top:5px;margin-bottom:-3px;" class="navigation-header"><span>Attendence</span></li> -->
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-layers"></i><span class="menu-title" data-i18n="Masters">Attendence</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'AttendanceModule' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=AttendanceModule" target="_blank"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Attendance Module">Attendence</span></a>
                                </li>
                                <!-- <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Attendence_report' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=Attendence_report"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Attendence Report">Attendence Report</span></a>
                                </li> -->
                            </ul>
                        </li>

                        <!-- <li style="margin-top:5px;margin-bottom:-5px;" class="navigation-header"><span>Assigning</span></li> -->
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-users"></i><span class="menu-title" data-i18n="Masters">Assigning</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-pocket-assign' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=survey-utility-pocket-assign" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Pocket Assign">Pocket Assign</span></a>
                                </li> 

                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-society-assign' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=survey-utility-society-assign" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Society Assign">Society Assign</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'assign-executive-to-site' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=assign-executive-to-site" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Assign Executive To Site">Assign Executive To Site</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'assigned-executive-site-transfer' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=assigned-executive-site-transfer" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Transfer Assigned Executive To Site">Transfer Assigned Executive To Site</span></a>
                                </li>
                            </ul>
                        </li>

                        <?php 
                            if(
                                $_SESSION['SurveyUA_Mobile'] == "9223575193" ||
                                $_SESSION['SurveyUA_Mobile'] == "9223575189" ||
                                $_SESSION['SurveyUA_Mobile'] == "9892521519" ||
                                $_SESSION['SurveyUA_Mobile'] == "9920480368"
                            ){
                        ?>
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-dollar-sign"></i><span class="menu-title" data-i18n="Masters">Salary Process</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-salary-process' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=survey-salary-process"><i class="feather icon-dollar-sign"></i><span class="menu-item" data-i18n="Survey Salary Process">Salary Process</span></a>
                                </li>
                            </ul>
                        </li>
                        <?php
                            }
                        ?> 
                        
                        <!-- <li class="navigation-header"><span>QC</span></li> -->
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-fast-forward"></i><span class="menu-title" data-i18n="QC">QC</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'building-listing-qc' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=building-listing-qc" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Building Listing">Building Listing</span></a>
                                </li>

                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'QC_Assign' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=QC_Assign" target="_blank"><i class="feather icon-fast-forward"></i><span class="menu-item" data-i18n="QC Assign">QC Assign</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey_QC' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=Survey_QC" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Survey QC">Survey QC</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey-QC-DateWise' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=Survey-QC-DateWise" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Survey QC">Survey QC DateWise</span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'IssueEntry' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=IssueEntry" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Issue Entry">Society Isuue </span></a>
                                </li>
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Non-Voters-with-Society' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=Non-Voters-with-Society" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Issue Entry">Voter Entry </span></a>
                                </li>
                            </ul>
                        </li>
                        <?php   
                        if($_SESSION['SurveyUA_Mobile'] == "9820743654"
                            || $_SESSION['SurveyUA_Mobile'] == "8097485495"
                            || $_SESSION['SurveyUA_Mobile'] == "9920480368"
                            || $_SESSION['SurveyUA_Mobile'] == "8286894002"
                        ){
                            
                        ?>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'TransferScocietyData' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=MoveSocietyData" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Move DB Data">Transfer Society Data</span></a>
                        </li>
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-fast-forward"></i><span class="menu-title" data-i18n="Action">Action</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'MoveDBData' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=MoveDBData" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Move DB Data">Move DB Data</span></a>
                                </li>
                            </ul>
                        </li>

                        <?php } ?>

                        <li class="navigation-header"><span>Reports</span></li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'BList_Summary_Report' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=BList_Summary_Report" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="BList Summary Report">BList Summary Report</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'ExecutiveAndMobileNoWise' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=ExecutiveAndMobileNoWise" target="_blank"><i class="fa fa-users" ></i><span class="menu-item" data-i18n="Executive And Mobile No Wise Reports">Executive And Mobile No Wise Reports</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'voter-search-executive-wise-society' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=voter-search-executive-wise-society" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Voter Search Executive Wise Society">Voter Search Executive Wise Society</span></a>
                        </li>
 

                        <?php   
                            $Designation = $_SESSION['SurveyUA_Designation'];
                        ?>
                        <?php 
                            if($_SESSION['SurveyUA_UserType'] == 'C' || $_SESSION['SurveyUA_Mobile'] == '9820480368'){ 
                        ?>    
                            <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_ReportClient' ){ ?>   class="active"  <?php } ?> >
                                <a href="index.php?p=Survey_Summary_ReportClient" target="_blank"><i class="feather icon-align-justify"></i><span class="menu-item" data-i18n="Survey Summary Report">Survey Summary Report</span></a>
                            </li>
                        <?php }elseif($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other' || $Designation == 'SP' || $Designation == 'Survey Supervisor'){
                        ?>
                               <!--  <li <?php //if( isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_Report' ){ ?>   class="active"  <?php //} ?> >
                                    <a href="index.php?p=Survey_Summary_Report"><i class="feather icon-align-justify"></i><span class="menu-item" data-i18n="Survey Summary Report">Survey Summary Report</span></a>
                                </li> -->
                        <?php } ?>

            <?php 
                }elseif($_SESSION['SurveyUA_Mobile'] == "9324588400"){
            ?>

                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Client-Dashboard' ){ ?>   class="active"  <?php } ?> >
                    <a href="index.php?p=Client-Dashboard" target="_blank"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="Client-Dashboard">Client Dashboard</span></a>
                </li>
            <?php
                }else{
            ?>


                <?php 
                // Data Entry Executive
                    $Designation = $_SESSION['SurveyUA_Designation'];
                    if($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other'){

                ?>
                        
                        <li <?php if(!isset($_GET['p']) || ( isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_Report') ){ ?>   class="active"  <?php } ?>  ><a href="index.php"><i class="feather icon-home"></i><span class="menu-item" data-i18n="Dashboard">Dashboard</span></a>
                        </li>

						<!--  <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Client-Dashboard' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Client-Dashboard"><i class="feather icon-grid"></i><span class="menu-item" data-i18n="Client-Dashboard">Client Dashboard</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Analysis_report' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Analysis_report"><i class="feather icon-bar-chart"></i><span class="menu-item" data-i18n="Client-Dashboard">Analysis</span></a>
                        </li>

	                    <li class="navigation-header"><span>Masters</span></li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'pocket-master' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=pocket-master"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Pocket Master">Pocket Master</span></a>
                        </li>
                        <li class="navigation-header"><span>Assigning</span></li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-pocket-assign' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=survey-utility-pocket-assign"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Pocket Assign">Pocket Assign</span></a>
                        </li> 

                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-society-assign' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=survey-utility-society-assign"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Society Assign">Society Assign</span></a>
                        </li> -->

                       
                <?php }else if($Designation == 'Data Entry Executive' || $_SESSION['SurveyUA_Mobile'] == '7977255731' || $_SESSION['SurveyUA_Mobile'] == '7773936746'){ ?>

                        <li class="navigation-header"><span>QC</span></li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'building-listing-qc' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=building-listing-qc" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Building Listing">Building Listing</span></a>
                        </li>
                        <?php
                            if  ($_SESSION['SurveyUA_Mobile'] == '8286894002' ){
                        ?>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'QC_Assign' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=QC_Assign" target="_blank"><i class="feather icon-fast-forward"></i><span class="menu-item" data-i18n="QC Assign">QC Assign</span></a>
                        </li>
                        <?php 
                            }
                        ?>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey_QC' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Survey_QC"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Survey QC">Survey QC</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey-QC-DateWise' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Survey-QC-DateWise" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Survey QC">Survey QC DateWise</span></a>
                        </li>
                        <li class="navigation-header"><span>Reports</span></li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'BList_Summary_Report' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=BList_Summary_Report" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="BList Summary Report">BList Summary Report</span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'IssueEntry' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=IssueEntry" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Issue Entry">Society Isuue </span></a>
                        </li>
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Non-Voters-with-Society' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=Non-Voters-with-Society" target="_blank"><i class="feather icon-check-square"></i><span class="menu-item" data-i18n="Issue Entry">Voter Entry </span></a>
                        </li>
                        
                        <li <?php if( isset($_GET['p']) && $_GET['p'] == 'TransferScocietyData' ){ ?>   class="active"  <?php } ?> >
                            <a href="index.php?p=MoveSocietyData"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Move DB Data">Transfer Society Data</span></a>
                        </li>
                        <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-fast-forward"></i><span class="menu-title" data-i18n="Action">Action</span></a>
                            <ul class="menu-content">
                                <li <?php if( isset($_GET['p']) && $_GET['p'] == 'MoveDBData' ){ ?>   class="active"  <?php } ?> >
                                    <a href="index.php?p=MoveDBData" target="_blank"><i class="feather icon-list"></i><span class="menu-item" data-i18n="Move DB Data">Move DB Data</span></a>
                                </li>
                            </ul>
                        </li> 
                <?php   
                    }elseif($_SESSION['SurveyUA_Mobile'] == "9082494701" || $_SESSION['SurveyUA_Mobile'] == "9004555991" || $_SESSION['SurveyUA_Mobile'] == "9653361535"
                            || $_SESSION['SurveyUA_Mobile'] == "9833693359" || $_SESSION['SurveyUA_Mobile'] == "9324593425" || $_SESSION['SurveyUA_Mobile'] == "7977670173"
                            || $_SESSION['SurveyUA_Mobile'] == "9359207143" || $_SESSION['SurveyUA_Mobile'] == "7208094300" || $_SESSION['SurveyUA_Mobile'] == "8369399798"
                            || $_SESSION['SurveyUA_Mobile'] == "9702241332" || $_SESSION['SurveyUA_Mobile'] == "7666305649" || $_SESSION['SurveyUA_Mobile'] == "9834370767"
                            || $_SESSION['SurveyUA_Mobile'] == "9167371520" || $_SESSION['SurveyUA_Mobile'] == "7738865997" || $_SESSION['SurveyUA_Mobile'] == "7588907945"
                            || $_SESSION['SurveyUA_Mobile'] == "8779080013" || $_SESSION['SurveyUA_Mobile'] == "7977862730" || $_SESSION['SurveyUA_Mobile'] == "9987375822"
                            || $_SESSION['SurveyUA_Mobile'] == "9075969086" || $_SESSION['SurveyUA_Mobile'] == "9833693359" || $_SESSION['SurveyUA_Mobile'] == "8779961010"
							|| $_SESSION['SurveyUA_Mobile'] == "7796862170" || $_SESSION['SurveyUA_Mobile'] == "9096387818" || $_SESSION['SurveyUA_Mobile'] == "9220053424"
                            || $_SESSION['SurveyUA_Mobile'] == "9323257432" || $_SESSION['SurveyUA_Mobile'] == "9833693359" || $_SESSION['SurveyUA_Mobile'] == "8850105508"
                            || $_SESSION['SurveyUA_Mobile'] == "7208368819" || $_SESSION['SurveyUA_Mobile'] == "9420973282" || $_SESSION['SurveyUA_Mobile'] == "8828495095"
                            || $_SESSION['SurveyUA_Mobile'] == "9403542671" || $_SESSION['SurveyUA_Mobile'] == "9421785341" || $_SESSION['SurveyUA_Mobile'] == "7045130222"
                            || $_SESSION['SurveyUA_Mobile'] == "9594974907" || $_SESSION['SurveyUA_Mobile'] == "9137581843"){
            ?>
                    <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-check-square"></i><span class="menu-title" data-i18n="Masters">Masters</span></a>
                        <ul class="menu-content">
                            <li <?php if( isset($_GET['p']) && $_GET['p'] == 'pocket-master' ){ ?>   class="active"  <?php } ?> >
                                <a href="index.php?p=pocket-master" target="_blank"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Pocket Master">Pocket Master</span></a>
                            </li>
                            <li <?php if( isset($_GET['p']) && $_GET['p'] == 'site-master' ){ ?>   class="active"  <?php } ?> >
                                <a href="index.php?p=site-master" target="_blank"><i class="feather icon-layers"></i><span class="menu-item" data-i18n="Site Master">Site Master</span></a>
                            </li>
                        </ul>
                    </li>
                    <li style="margin-top:5px;margin-bottom:-5px;" class=" nav-item"><a href="#"><i class="feather icon-users"></i><span class="menu-title" data-i18n="Masters">Assigning</span></a>
                        <ul class="menu-content">
                            <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-pocket-assign' ){ ?>   class="active"  <?php } ?> >
                                <a href="index.php?p=survey-utility-pocket-assign" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Pocket Assign">Pocket Assign</span></a>
                            </li> 

                            <li <?php if( isset($_GET['p']) && $_GET['p'] == 'survey-utility-society-assign' ){ ?>   class="active"  <?php } ?> >
                                <a href="index.php?p=survey-utility-society-assign" target="_blank"><i class="feather icon-users"></i><span class="menu-item" data-i18n="Society Assign">Society Assign</span></a>
                            </li>
                        </ul>
                    </li>
                    <?php }
                    $Designation = $_SESSION['SurveyUA_Designation'];
                    if($_SESSION['SurveyUA_UserType'] == 'C' || $_SESSION['SurveyUA_Mobile'] == '9820480368'){ 
                ?>    
                    <li <?php if( isset($_GET['p']) && $_GET['p'] == 'Survey_Summary_ReportClient' ){ ?>   class="active"  <?php } ?> >
                        <a href="index.php?p=Survey_Summary_ReportClient" target="_blank"><i class="feather icon-align-justify"></i><span class="menu-item" data-i18n="Survey Summary Report">Survey Summary Report</span></a>
                    </li>
                <?php }elseif($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other' || $Designation == 'SP' || $Designation == 'Survey Supervisor'){
                    } 
                }
                ?>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content" >
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body" id="SurveyUtilityKMLDashboardMainScreen">
                