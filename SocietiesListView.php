<?php
session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
// $DBName = $_SESSION['SurveyUtility_DBName'];
$ExecutiveName = "";
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    
    if($ServerIP == "103.14.97.58"){
        $ServerIP =".";
    }else{
        $ServerIP = "103.14.97.58";
    }
$MobileNo = "";


if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Ac_No']) && !empty($_GET['Ac_No'])
    && isset($_GET['SocietyName']) && !empty($_GET['SocietyName'])
    || isset($_GET['PocketName'])  
)
   {
    
    try  
        {  
            $_SESSION['Society_Issue_Search_AcNo']  = $_GET['Ac_No'];
            $_SESSION['Society_Issue_Search_SocietyName']  = $_GET['SocietyName'];
            $_SESSION['Society_Issue_Search_PocketName']  = $_GET['PocketName'];
            $AcNo = $_SESSION['Society_Issue_Search_AcNo'];
            $SocietyName = $_GET['SocietyName'];
            $PocketName = $_GET['PocketName'];
            if(!empty($PocketName)){

                $PocketCond ="AND PocketName = '$PocketName'";
            }else{
                $PocketCond ="";
            }
            if(!empty($AcNo)){
                $AcCond ="AND Ac_No = '$AcNo'";
            }else{
                $AcCond ="";
            }
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
         
  }
}
     $qry = "SELECT SocietyDetail  FROM Survey_Entry_Data..Society_Issues
     WHERE  SocietyDetail is not null";
    $SocietyDetailData = $db->ExecutveQueryMultipleRowSALData($qry, $userName, $appName, $developmentMode);
    $Query ="SELECT Society_Cd,SocietyName,Ac_No,PocketName,Rooms,Latitude,Longitude,Building_Image 
                FROM Society_Master 
                WHERE SocietyName LIKE '$SocietyName%' $AcCond $PocketCond";
    $SocietyData = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);
    // print_r($SocietyData);
    // die();




// // First array with Society_Cd values
// $firstArray = [
//     ["Society_Cd" => "28127", "SocietyName" => "Patel Residency C Wing"],
//     ["Society_Cd" => "28131", "SocietyName" => "Patel Residency B Wing"],
//     // Add other Society_Cd values as needed
// ];

// // Second array with SocietyDetail JSON strings
// $secondArray = [
//     ["SocietyDetail" => '[{"SocietyCd":"28004","SocietyName":"Sajan Heights B Wing","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28004.jpg","Longitude":"73.08557085692883","Latitude":"19.23135034731846"},{"SocietyCd":"28005","SocietyName":"Sajan Heights A Wing","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28005.jpg","Longitude":"73.08539986610413","Latitude":"19.231354779249024"}]'],
//     ["SocietyDetail" => '[{"SocietyCd":"28093","SocietyName":"Panvelkar Campus Complex Wing - C/1","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28093.jpg","Longitude":"73.19591861218214","Latitude":"19.21863337989717"},{"SocietyCd":"28095","SocietyName":"Panvelkar Campus Complex Wing - C/2","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28095.jpg","Longitude":"73.19592565298082","Latitude":"19.218967066447693"},{"SocietyCd":"28096","SocietyName":"Panvelkar Campus Complex Wing - C/3","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28096.jpg","Longitude":"73.19600142538548","Latitude":"19.219497037811944"},{"SocietyCd":"28098","SocietyName":"Panvelkar Campus Complex Wing - C/4","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28098.jpg","Longitude":"73.19606680423021","Latitude":"19.21979748079528"},{"SocietyCd":"28101","SocietyName":"Panvelkar Campus Complex C/5","Building_Image":"http://103.14.97.58/UploadImagePhp/SurveyBuildingImage/BI_28101.jpg","Longitude":"73.1961352005601","Latitude":"19.220180869305135"}]'],
//     // Add other SocietyDetail JSON strings as needed
// ];

// Extract Society_Cd values from the second array
$secondSocietyCds = [];
foreach ($SocietyDetailData as $item) {
    $detailArray = json_decode($item['SocietyDetail'], true);
    foreach ($detailArray as $detail) {
        $secondSocietyCds[] = $detail['SocietyCd'];
    }
}

// Find Society_Cd values not present in the second array
$result = [];
foreach ($SocietyData as $item) {
    if (!in_array($item['Society_Cd'], $secondSocietyCds)) {
        $result[] = $item;
    }
}

// Output Society_Cd values not present in the second array
// print_r($notPresentSocietyCds);


//     // print_r($Query);
//     die();
?>
<div class="SocietyTable"  style="height: 300px;overflow:scroll;">
    <div class="card">
        <div class="card-header">
        <h4 class="card-title">Selected Society (<span id="SelectedSocietyCds"> 0 </span>) </h4>
        </div>
        <div class="card-body">
            <table class="table table-hover-animation table-hover table-striped" id="TableIdkdmc">
                <thead>
                    <tr>
                        <th style="background-color:#36abb9;color: white;">
                        </th>
                        <th style="background-color:#36abb9;color: white;">Society Name</th>
                        <th style="background-color:#36abb9;color: white;">Pocket</th>
                        <th style="background-color:#36abb9;color: white;">Rooms</th>
                        <th style="background-color:#36abb9;color: white;">Ac No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(sizeof($result)>0){
                        foreach($result as $key=>$value){
                    ?>
                    <tr>
                        <td>
                            <input  class="form-control form-control-sm flatpickr  flatpickr-input active form-check-input checkbox_All Voter-checkbox" type="checkbox" 
                            style="margin-left:10px;margin-bottom:10px;width: 15px; height: 15px;margin-top:-8px;" id="SocietyCheck"
                            onclick="getSelectedSocietiesList('<?php echo $value['Society_Cd'].'~'.$value['SocietyName'].'~'.$value['Building_Image'].'~'.$value['Longitude'].'~'.$value['Latitude']; ?>')" >
                        </td>
                        <td><?php echo $value['SocietyName']; ?></td>
                        <td><?php echo $value['PocketName']; ?></td>
                        <td><?php echo $value['Rooms']; ?></td>
                        <td><?php echo $value['Ac_No']; ?></td>
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
