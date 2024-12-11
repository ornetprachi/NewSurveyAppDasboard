<?php  
ob_start();
session_start();

ini_set('max_execution_time', '1000'); // 5 min

$formLanguageArray = array('English','Marathi');
$formLanguageArray = array('English');
if(!isset($_SESSION['Form_Language'])){
    $_SESSION['Form_Language'] = 'English';
}


if(!isset($_SESSION['SurveyUA_Mobile']))
{
    header('Location:login.php');
}else{

   date_default_timezone_set('Asia/Kolkata');

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

$startTime = "00:00:00";
$endTime = "23:59:59";
$_SESSION['StartTime']=$startTime;
$_SESSION['EndTime']=$endTime;

// if(isset($_SESSION['SurveyUA_Election_Cd'])){
//     if($_SESSION['SurveyUA_Election_Cd'] == 0){
//       $_SESSION['SurveyUA_Election_Cd']=27;
//       $_SESSION['SurveyUA_ElectionName']='NMMC';
//     }
// }

    if(isset($_SESSION['SurveyUA_UserName']) && isset($_SESSION['SurveyUA_AppName']) ){
           //header('Location:index.php');  
    }else{
        header('Location:login.php');
    }
    
   
}
?>