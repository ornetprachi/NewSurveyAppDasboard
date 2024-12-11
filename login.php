<?php 
session_start();

include  'api/includes/DbOperation.php';


if(!isset($_SESSION['SurveyUA_Mobile']))
{
  if(isset($_POST['mobile']) && isset($_POST['password']))
  {
  $umobile=$_POST['mobile'];
  $upassword=$_POST['password'];

//    $serverIp = $_POST['serverIp'];


//   $_SESSION['SurveyUtility_ServerIP'] = $serverIp;
//   $Server = $_SESSION['SurveyUtility_ServerIP'];


  $data = array();
  $empty= array();
  //login verification
  $appName = "SurveyUtilityApp";
  // $appName = "Chankya_CC_Testing";
//   $developmentMode = "Live";
  $developmentMode = "Testing";
  $startTime = "00:00:00";
  $endTime = "23:59:59";
  $db=new DbOperation();
  $authenticateuser = $db->authenticateUser($umobile, $upassword, $appName);

    if($authenticateuser == USER_LOGIN_SUCCESS){
        $data["error"] = false;
        $data["message"] = "You have LoggedIn Successfully!";
        $data["userData"] = $db->getLoggedInUserDetails($umobile, $upassword, $appName);
    }else if($authenticateuser == USER_LOGIN_FAILED){
        $data["error"] = true;
        $data["message"] = "Invalid User! Please check your mobile and password!";
        $data["userData"] = $empty;
    }else if($authenticateuser == USER_INSTALLATION_EXPIRED){
        $data["error"] = true;
        $data["message"] = "You can not login twice!";
        $data["userData"] = $empty;
    }else if($authenticateuser == USER_STATUS_NOT_ACTIVE){
        $data["error"] = true;
        $data["message"] = "Sorry! You are not Manager!";
        $data["userData"] = $empty;
    }else if($authenticateuser == USER_LICENSE_EXPIRED){
        $data["error"] = true;
        $data["message"] = "Sorry! Your License Expired!";
        $data["userData"] = $empty;
    }else{
        $data["error"] = true;
        $data["message"] = "Something Wrong!";
        $data["userData"] = $empty;
    }

   
    if(sizeof($data["userData"]) > 0){
        foreach ($data["userData"] as $key => $value) 
                  {
                    $userName = $value["UserName"];
                    $appName = $value["AppName"];
                    $fullName = $value["FullName"];
                    $designation = $value["Designation"];
                    $userType = $value["UserType"];
                    $clientCd = $value["Client_Cd"];
                    $electionCd = $value["Election_Cd"];
                    $electionName = $value["ElectionName"];
                    $ExecutiveName = $value["ExecutiveName"];
                    $Executive_Cd = $value["Executive_Cd"];
                    $db=new DbOperation();
             
                    $_SESSION['SurveyUA_Mobile']=$umobile;
                      
                    $_SESSION['SurveyUA_UserName']=$userName;
                    $_SESSION['SurveyUA_LoggedIn_UserName']=$userName;
                    $_SESSION['SurveyUA_AppName']=$appName;
                    $_SESSION['SurveyUA_FullName']=$fullName;
                    $_SESSION['SurveyUA_Designation']=$designation;
                    $_SESSION['SurveyUA_UserType']=$userType;
                    $_SESSION['SurveyUA_Client_Cd']=$clientCd;
                    $_SESSION['SurveyUA_ElectionName']=$electionName;
                    $_SESSION['SurveyUA_Election_Cd']=$electionCd;
                    $_SESSION['SurveyUA_DevelopmentMode']=$developmentMode;
                    $_SESSION['SurveyUA_ExecutiveName']=$ExecutiveName;
                    
                    $_SESSION['StartTime']=$startTime;
                    $_SESSION['EndTime']=$endTime;
                    $_SESSION['Filter_Column'] = "All";
                    $_SESSION['SurveyUA_Assign_Type'] = "GroupMobile";
                    $_SESSION['Action_Type'] = "Assign";
                    $_SESSION['SurveyUA_Executive_Cd_Login']=$Executive_Cd;
                    
                      ?>
                     
                    <?php
                      
                        
                        if(isset($_SESSION['SurveyUA_UserType']) && isset($_SESSION['SurveyUA_Client_Cd'])){

                          if($_SESSION['SurveyUA_UserType'] == "A"){

                            if($_SESSION['SurveyUA_Designation'] == 'CEO/Director' || $_SESSION['SurveyUA_Designation'] == 'Manager' || $_SESSION['SurveyUA_Designation'] == 'Senior Manager' || $_SESSION['SurveyUA_Designation'] == 'Software Developer' || $_SESSION['SurveyUA_Designation'] == 'Admin and Other' || $_SESSION['SurveyUA_Designation'] == 'SP' || $_SESSION['SurveyUA_Designation'] == 'Survey Supervisor' || $_SESSION['SurveyUA_Designation'] == 'Data Entry Executive' || $_SESSION['SurveyUA_Designation'] == 'Client Coordinator' || $_SESSION['SurveyUA_Designation'] == 'COO / Director'){

                                header('Location:index.php');
                                
                            }else{
                                header('Location:index.php?p=building-listing-qc');
                            }
                           
                          }else{
                            header('Location:login.php');
                          }
                        
                      }else{
                        header('Location:index.php');
                      }
              }
      }else{
        ?>
          <script type="text/javascript">
            alert('<?php echo "".$data["message"]; ?>');
            window.location = 'login.php';
          </script>
        <?php
        session_unset();
        session_destroy();
        // header('Location:login.php');
      }
  }


}else if (isset($_SESSION['SurveyUA_Mobile'])){
        // if($_SESSION['SurveyUA_Mobile'] == '9820480368'){ 
        //     header('location:index.php?p=Client-Dashboard');
        // }else{
            header('Location:index.php');
        // }
}else {
   //header('Location:login.php');
} 


?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Survey Utility Dashboard">
    <meta name="keywords" content="Survey Utility Dashboard">
    <meta name="author" content="ORNET">
    <title>Survey Utility Dashboard - Login Page</title>
    <link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
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
    <link rel="stylesheet" type="text/css" href="app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
    <style type="text/css"> 
    .btn-warning {
            border-color: #BBD001 !important;
            background-color: #1F8D4E !important;
            color: #FFFFFF;
        }

        html body.bg-full-screen-image {
            background: url(app-assets/images/pages/login-bg.jpg) no-repeat center center;
            background-size: cover;
        }
        .bg-authentication {
             background-color: #FFFFFF !important;
        }
        

        .btn-warning {
            border-color: #5993ff !important;
            background-color: #41bdcc!important;
            color: #FFFFFF;
        }

    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body" >
                <section class="row flexbox-container" >
                    <div class="col-xl-8 col-11 d-flex justify-content-center" >
                        <div class="card bg-authentication rounded-0 mb-0" style="box-shadow: 5px 5px 40px #000000;">
                            <div class="row m-0">
                                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                                                <img src="app-assets/images/pages/login_page.png" height="230" width="250" alt="branding logo">
                                </div>
                                <div class="col-lg-6 col-12 p-0">
                                    <div class="card rounded-0 mb-0 px-2">
                                        <div class="card-header pb-1">
                                            <div class="card-title">
                                                <h4 class="mb-0">Survey Utility Dashboard </h4>
                                            </div>
                                        </div>
                                        <p class="px-2">Welcome back, please login to your account.</p>
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <form action="login.php" method="post">
                                                    <?php
                                            // if(isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP']) ){
                                            //     $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
                        
                                            // }else{
                                            //     $ServerIP = "";
                                            // }
                                            // $serverIpArray = array();
                                            // $serverIpArray = array("103.14.97.228","103.14.99.154","92.204.137.146");
                                        ?>
                                    
                                        <div class="card-content">
                                            <div class="card-body pt-1">
                                                <!-- <form action="login.php" method="post"> -->
                                                    <!-- <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <select style="height:40px" class="select2 form-control" id="serverIp" name="serverIp"  required>
                                                            <option value="">--Select--</option>

                                                            <?php //foreach($serverIpArray as $serArray)
                                                            //{ ?>
                                                            <option value="<?php //echo $serArray?>"
                                                            <?php //if( $ServerIP == $serArray)
                                                            // {
                                                            //     echo "selected";
                                                            //}?>><?php //echo $serArray?></option>
                                                            <?php //} ?>
                                                        
                                                        </select>
                                                    </fieldset> -->
                                                    <fieldset class="form-label-group form-group position-relative has-icon-left">
                                                        <input type="text" class="form-control" id="mobile-field"  name="mobile"  type="text" maxlength="10" placeholder="Mobile" required onkeypress="return (event.charCode >= 48 && event.charCode <= 57) ">
                                                        <div class="form-control-position">
                                                            <i class="feather icon-user"></i>
                                                        </div>
                                                        <label for="mobile-field">Mobile</label>
                                                    </fieldset>
                                                    <div class="form-group" style="margin-top:-10px;">
                                                        <div class="input-icon" style="text-align:right;">
                                                            <a style="padding:5px;" onclick="checkUserMobileNo()" id="getButtonID" class="btn btn-primary"><b>Get OTP</b></a>
                                                        </div>
                                                    </div>
                                                    <fieldset class="form-label-group position-relative has-icon-left" id="userPWDID" style="display:none;">
                                                        <input type="password" class="form-control" id="user-password" placeholder="Enter OTP" maxlength="4" required name="password">
                                                        <div class="form-control-position">
                                                            <i class="feather icon-lock"></i>
                                                        </div>
                                                        <label for="user-password">OTP</label>
                                                    </fieldset>
                                                    <!-- <div class="form-group d-flex justify-content-between align-items-center"> -->
                                                       <!--  <div class="text-left">
                                                            <fieldset class="checkbox">
                                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                                    <input type="checkbox">
                                                                    <span class="vs-checkbox">
                                                                        <span class="vs-checkbox--check">
                                                                            <i class="vs-icon feather icon-check"></i>
                                                                        </span>
                                                                    </span>
                                                                    <span class="">Remember me</span>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="text-right"><a href="auth-forgot-password.html" class="card-link">Forgot Password?</a></div> -->
                                                    <!-- </div> -->
                                                    <!-- <a href="auth-register.html" class="btn btn-outline-primary float-left btn-inline">Register</a> -->
                                                    <button id="LogInButtonID" style="display:none;padding:7px;margin-top:-10px;" type="button" onclick="verifyMobileandOTP();" class="btn btn-primary float-right"><b>Login</b></button>
                                                <!-- </form> -->
                                            </div>
                                        </div>
                                        <div class="login-footer" style="margin-top:10px;">
                                            <div id="SuccessMSG" class="controls alert alert-success text-center" role="alert" style="display:none;padding:5px;"></div>
                                            <div id="FailedMSG" class="controls alert alert-danger text-center" role="alert" style="display:none;padding:5px;"></div>
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
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- END: Page JS-->
<script>
    
function checkUserMobileNo(){

    var umobile = document.getElementsByName('mobile')[0].value;

    if(umobile === '' ){
        alert("Please Enter Mobile No!");
    }else if(umobile.length < 10  ){
        alert("Mobile No should be 10 digits!");
    }else{
        $.ajax({
        type: "POST",
        url: 'chkUserLoginMobileDataSendOTP.php',
        data: {
            umobile: umobile
        },
        beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
            var element = document.getElementById("getButtonID");
            element.classList.add("loading");
            element.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
        },
        success: function(dataResult) {

                // console.log(dataResult);   
                var dataResult = JSON.parse(dataResult);

                if (dataResult.statusCode == 200) {
                    // alert(dataResult.msg);
                    $('#userPWDID').show();
                    $('#LogInButtonID').show();
                    $("#SuccessMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("SuccessMSG").append("");
                            // window.location.href = 'register.php'; 
                    }).delay(3000).fadeOut("fast");

                    var element = document.getElementById("getButtonID");
                    element.classList.remove("loading");
                    element.innerHTML = "<b>Resent OTP</b>";
                } else if (dataResult.statusCode == 203 || dataResult.statusCode == 204 || dataResult.statusCode == 404) {
                    // alert(dataResult.msg);
                    $("#FailedMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("FailedMSG").append("");
                            // window.location.href = 'register.php'; 
                    }).delay(3000).fadeOut("fast");

                    var element = document.getElementById("getButtonID");
                    element.classList.remove("loading");
                    element.innerHTML = "Get OTP";

                } else if (dataResult.statusCode == 206) {
                    $("#FailedMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("FailedMSG").append("");
                        // window.location.href = 'register.php'; 
                    }).delay(3000).fadeOut("fast");
                } 
                // return data;
        },
        complete: function() {
            var element = document.getElementById("getButtonID");
            element.classList.remove("loading");
            element.innerHTML = "<b>Resent OTP</b>";
        }  
        });
    }
}

  
function verifyMobileandOTP(){

    var umobile = document.getElementsByName('mobile')[0].value;
    var OTP_Pass = document.getElementsByName('password')[0].value;

    if(umobile === '' ){
        alert("Please Enter Mobile No!");
    }else if(umobile.length < 10  ){
        alert("Mobile No should be 10 digits!");
    }else if(OTP_Pass === '' ){
        alert("Please Enter OTP!");
    }else if(OTP_Pass.length < 4  && umobile !== '9324588400'){
        alert("OTP should be 4 digits!");
    }else{
        $.ajax({
        type: "POST",
        url: 'verifyOTPforLogin.php',
        data: {
            umobile: umobile,
            OTP_Pass: OTP_Pass
        },
        beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
            var element = document.getElementById("LogInButtonID");
            element.classList.add("loading");
            element.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
        },
        success: function(dataResult) {

                // console.log(dataResult);   
                var dataResult = JSON.parse(dataResult);

                if (dataResult.statusCode == 200) {
                    // alert(dataResult.msg);
                    $('#userPWDID').show();
                    $('#LogInButtonID').show();
                    $("#SuccessMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("SuccessMSG").append("");
                            window.location.href = 'index.php';
                    }).delay(3000).fadeOut("fast");

                    var element = document.getElementById("LogInButtonID");
                    element.classList.remove("loading");
                    element.innerHTML = "<b>Login</b>";
                } else if (dataResult.statusCode == 203 || dataResult.statusCode == 204 || dataResult.statusCode == 404) {
                    // alert(dataResult.msg);
                    $('#userPWDID').show();
                    $('#LogInButtonID').show();
                    $("#FailedMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("FailedMSG").append("");
                            
                    }).delay(3000).fadeOut("fast");

                    var element = document.getElementById("LogInButtonID");
                    element.classList.remove("loading");
                    element.innerHTML = "<b>Login</b>";

                } else if (dataResult.statusCode == 206) {
                    $("#FailedMSG").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                        $("FailedMSG").append("");
                        // window.location.href = 'register.php'; 
                    }).delay(3000).fadeOut("fast");
                } 
                // return data;
        },
        complete: function() {
            var element = document.getElementById("getButtonID");
            element.classList.remove("loading");
            element.innerHTML = "<b>Resent OTP</b>";
        }  
        });
    }
}

    var input = document.getElementById("user-password");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("LogInButtonID").click();
        }
    });

    var input = document.getElementById("mobile-field");
    input.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("getButtonID").click();
        }
    });
</script>

</body>
<!-- END: Body-->

</html>

