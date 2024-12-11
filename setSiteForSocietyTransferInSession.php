<?php
session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SiteName']) && !empty($_GET['SiteName']) ){

    try  
    {  

        $SiteName=$_GET['SiteName'];

        $_SESSION['From_SocietyTransfer_SiteName'] = $SiteName;
        $_SESSION['From_SocietyTransfer_SocietyCd'] = "" ;
        $_SESSION['To_SocietyTransfer_SocietyCd'] = "" ;
        $_SESSION['SocietyTransfer_UpdatedDate'] = "" ;
        $_SESSION['SocietyTransfer_UpdatedBy'] = "" ;
    } 
    catch(Exception $e)  
    {  
        echo("Error!");  
    }
                                                          

  }
  if(isset($_GET['SocietyCd']) && !empty($_GET['SocietyCd']) ){
    //echo "ddd";
    try  
    {  

        $SocietyCd=$_GET['SocietyCd'];

        $_SESSION['From_SocietyTransfer_SocietyCd'] = $SocietyCd;
        $_SESSION['To_SocietyTransfer_SocietyCd'] = "" ;
        $_SESSION['SocietyTransfer_UpdatedDate'] = "" ;
        $_SESSION['SocietyTransfer_UpdatedBy'] = "" ;

    } 
    catch(Exception $e)  
    {  
        echo("Error!");  
    }
  }
  if(isset($_GET['ToSocietyCd']) && !empty($_GET['ToSocietyCd']) ){
    //echo "ddd";
    try  
    {  

        $SocietyCd=$_GET['ToSocietyCd'];

        $_SESSION['To_SocietyTransfer_SocietyCd'] = $SocietyCd;

    } 
    catch(Exception $e)  
    {  
        echo("Error!");  
    }
  }
  if(isset($_GET['UpdatedDate']) && !empty($_GET['UpdatedDate']) ){
    //echo "ddd";
    try  
    {  

        $UpdtaedDate=$_GET['UpdatedDate'];

        $_SESSION['SocietyTransfer_UpdatedDate'] = $UpdtaedDate;

    } 
    catch(Exception $e)  
    {  
        echo("Error!");  
    }
  }
  if(isset($_GET['UpdatedBy']) && !empty($_GET['UpdatedBy']) ){
    //echo "ddd";
    try  
    {  

        $UpdtaedBy=$_GET['UpdatedBy'];

        $_SESSION['SocietyTransfer_UpdatedBy'] = $UpdtaedBy;

    } 
    catch(Exception $e)  
    {  
        echo("Error!");  
    }
  }

}
?>

