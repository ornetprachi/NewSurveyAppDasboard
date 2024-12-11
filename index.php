<?php 

    include 'includes/header.php'; 

        $PageDir='pages';

        

        if (!empty($_GET['p'])){
            
            $pagesFolder=scandir($PageDir, 0);
            unset($pagesFolder[0], $pagesFolder[1]);
            // print_r($pages);
            $PageName=$_GET['p'];
            if (in_array($PageName.'.php', $pagesFolder)){
            include($PageDir.'/'.$PageName.'.php');
            }else {
            include($PageDir.'/auth-404.php');
            } 
        }else {
            if($_SESSION['SurveyUA_Mobile'] == "9082494701" || $_SESSION['SurveyUA_Mobile'] == "9004555991" || $_SESSION['SurveyUA_Mobile'] == "9653361535"
            || $_SESSION['SurveyUA_Mobile'] == "9833693359" || $_SESSION['SurveyUA_Mobile'] == "9324593425" || $_SESSION['SurveyUA_Mobile'] == "7977670173"
            || $_SESSION['SurveyUA_Mobile'] == "9359207143" || $_SESSION['SurveyUA_Mobile'] == "7208094300" || $_SESSION['SurveyUA_Mobile'] == "8369399798"
            || $_SESSION['SurveyUA_Mobile'] == "9702241332" || $_SESSION['SurveyUA_Mobile'] == "7666305649" || $_SESSION['SurveyUA_Mobile'] == "9834370767"
            || $_SESSION['SurveyUA_Mobile'] == "9167371520" || $_SESSION['SurveyUA_Mobile'] == "7738865997" || $_SESSION['SurveyUA_Mobile'] == "7588907945"
            || $_SESSION['SurveyUA_Mobile'] == "8779080013" || $_SESSION['SurveyUA_Mobile'] == "7977862730" || $_SESSION['SurveyUA_Mobile'] == "9987375822"
            || $_SESSION['SurveyUA_Mobile'] == "9833693359" || $_SESSION['SurveyUA_Mobile'] == "8779961010" || $_SESSION['SurveyUA_Mobile'] == "9096387818"
            || $_SESSION['SurveyUA_Mobile'] == "9220053424" || $_SESSION['SurveyUA_Mobile'] == "9323257432" || $_SESSION['SurveyUA_Mobile'] == "9833693359"
            || $_SESSION['SurveyUA_Mobile'] == "8850105508" || $_SESSION['SurveyUA_Mobile'] == "7208368819" || $_SESSION['SurveyUA_Mobile'] == "9420973282"
            || $_SESSION['SurveyUA_Mobile'] == "9403542671" || $_SESSION['SurveyUA_Mobile'] == "9594974907" || $_SESSION['SurveyUA_Mobile'] == "9137581843"){ 
                include($PageDir.'/survey-utility-pocket-assign.php');
            }elseif($_SESSION['SurveyUA_Mobile'] == "9324588400"){
                include($PageDir.'/Client-Dashboard.php');
            }else{
                include($PageDir.'/Survey_Summary_Report.php');
            }
        }
       
    include 'includes/footer.php';

?> 