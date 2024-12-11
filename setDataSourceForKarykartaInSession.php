<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if( 
                isset($_GET['DataS']) && !empty($_GET['DataS']) 
          ){
                // isset($_GET['siteName']) && !empty($_GET['siteName']) && 

            try  
            {  
             
                // $Site_Cd_Get = $_GET['siteName'];
                $DataS = $_GET['DataS'];

                $_SESSION['SurveyUA_DataSource_Karykarta'] = $DataS;
                $_SESSION['SurveyUA_Designation_Karykarta'] = 'ALL';
                $_SESSION['SurveyUA_AcNo_Karykarta'] = 'ALL';

                // $_SESSION['SurveyUA_SiteCd_For_Dashboard'] = $Site_Cd_Get;

                
            } 
            catch(Exception $e)  
            {  
                echo("Error!");  
            }
                                                              

        }
        elseif( 
                isset($_GET['Designation']) && !empty($_GET['Designation']) 
          ){
                // isset($_GET['siteName']) && !empty($_GET['siteName']) && 

            try  
            {  
             
                // $Site_Cd_Get = $_GET['siteName'];
                $Designation = $_GET['Designation'];

                $_SESSION['SurveyUA_Designation_Karykarta'] = $Designation;
                // $_SESSION['SurveyUA_SiteCd_For_Dashboard'] = $Site_Cd_Get;

                
            } 
            catch(Exception $e)  
            {  
                echo("Error!");  
            }
                                                              

        }
        elseif( 
                isset($_GET['AcNo']) && !empty($_GET['AcNo']) 
          ){
                // isset($_GET['siteName']) && !empty($_GET['siteName']) && 

            try  
            {  
             
                // $Site_Cd_Get = $_GET['siteName'];
                $AcNo = $_GET['AcNo'];

                $_SESSION['SurveyUA_AcNo_Karykarta'] = $AcNo;
                $_SESSION['SurveyUA_Designation_Karykarta'] = 'ALL';
                $_SESSION['SurveyUA_DataSource_Karykarta'] = 'ALL';

                
            } 
            catch(Exception $e)  
            {  
                echo("Error!");  
            }
                                                              

        } 
        else{
            //echo "ddd";
        }

    }
?>

