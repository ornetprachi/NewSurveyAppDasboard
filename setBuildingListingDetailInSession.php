<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if((isset($_GET['Society_Cd']) && !empty($_GET['Society_Cd']))){

    try  
        {  
            $_SESSION['Society_Cd_BL'] = $_GET['Society_Cd'];
            $_SESSION['election_Cd_BL'] = $_GET['election_Cd'];
            $_SESSION['ElectionName_BL'] = $_GET['ElectionName'];
            $_SESSION['Site_Cd_BL'] = $_GET['Site_Cd'];
            $_SESSION['SiteName_BL'] = $_GET['SiteName'];
            $_SESSION['SocietyName_BL'] = $_GET['SocietyName'];
            $_SESSION['SocietyNameMar_BL'] = $_GET['SocietyNameMar'];
            $_SESSION['Area_BL'] = $_GET['Area'];
            $_SESSION['AreaMar_BL'] = $_GET['AreaMar'];
            $_SESSION['Floor_BL'] = $_GET['Floor'];
            $_SESSION['Rooms_BL'] = $_GET['Rooms'];
            $_SESSION['Sector_BL'] = $_GET['Sector'];
            $_SESSION['PlotNo_BL'] = $_GET['PlotNo'];
            $_SESSION['Pocket_Cd_BL'] = $_GET['Pocket_Cd'];
            $_SESSION['Latitude_BL'] = $_GET['Latitude'];
            $_SESSION['Longitude_BL'] = $_GET['Longitude'];
            $_SESSION['Building_Image_BL'] = $_GET['Building_Image'];
            $_SESSION['Building_Plate_Image_BL'] = $_GET['Building_Plate_Image'];            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }else{
    //echo "ddd";
  }

}
?>