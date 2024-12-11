<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

if(isset($_GET['Vibhag']) && !empty($_GET['Vibhag'])){
    $Vibhag = $_GET['Vibhag'];
    $vibhagCon = "WHERE vw.Vibhag_No = '$Vibhag'";
}else{
    $vibhagCon = "WHERE vw.Shakha_No <> '27'";
}
// if($ServerIP == "103.14.97.228"){
//     $ServerIP =".";
// }else{
//     $ServerIP ="103.14.97.228";
// }
if($ULB == 'KDMC_2023'){
    $VibhagQur = "SELECT DISTINCT(Vibhag_No) as Vibhag_No,Vibhagpramukh,Vidhansabha,Count(DISTINCT(vw.Shakha_No)) as Shakha 
    FROM [103.14.97.58].BMC_Details.dbo.VibhagWiseWard as vw
    INNER JOIN [103.14.97.58].BMC_Details.dbo.KDMC_Shakha_Details as msd on (vw.Shakha_No = msd.Shakha_No)
    WHERE vw.Vibhag_No IN ('138','142','143','144','141','140')
    GROUP By Vibhag_No,Vibhagpramukh,Vidhansabha";
}else{
$VibhagQur =" SELECT DISTINCT(Vibhag_No) as Vibhag_No,Vibhagpramukh,Vidhansabha,Count(DISTINCT(vw.Shakha_No)) as Shakha 
            FROM BMC_Details..VibhagWiseWard as vw
            INNER JOIN BMC_Details..Mumbai_Shakha_Details as msd on (vw.Shakha_No = msd.Shakha_No)
            $vibhagCon
            AND  vw.Vibhag_No NOT IN ('138','142','143','144','141','140')
            GROUP By Vibhag_No,Vibhagpramukh,Vidhansabha;";
}
$VibhagData = $db->ExecutveQueryMultipleRowSALData($VibhagQur, $userName, $appName, $developmentMode);

if($ULB == 'KDMC_2023'){
    $sql2 = "SELECT DISTINCT(sd.Shakha_No),Longitude,Lattitude,Address,Photo_1,vw.Shakha_No,vw.Vidhansabha_Details,vw.UpvibhagPranukh
    ,vw.Shakhapramukh,vw.Vibhag_No,vw.Vibhagpramukh
    FROM [103.14.97.58].BMC_Details.dbo.KDMC_Shakha_Details as sd
    INNER JOIN [103.14.97.58].BMC_Details.dbo.VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
    WHERE vw.Vibhag_No IN ('138','142','143','144')
    ORDER BY vw.Vibhag_No";
}else{
$sql2 = "SELECT DISTINCT(sd.Shakha_No),Longitude,Lattitude,Address,Photo_1,vw.Shakha_No,vw.Vidhansabha_Details,vw.UpvibhagPranukh
,vw.Shakhapramukh,vw.Vibhag_No,vw.Vibhagpramukh
FROM BMC_Details..Mumbai_Shakha_Details as sd
INNER JOIN BMC_Details..VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
WHERE  vw.Vibhag_No NOT IN ('138','142','143','144','141','140')
ORDER BY vw.Vibhag_No";
}

$CountListMain = $db->ExecutveQueryMultipleRowSALData($sql2, $userName, $appName, $developmentMode);
?>
<?php 
foreach($VibhagData as $key=>$val){
?>
<style>
@font-face {
  font-family: 'Laila-Regular';
  src: url('app-assets/fonts/font-awesome/fonts/Laila-Regular.ttf') format('truetype');
}
table{
    border: 0px;
}
</style>

<div class="row match-height " style = "margin-top:-15px">
    <div class="col-md-12">
        <div class="card" style="border:solid 1px #F58018;">
            <div class="card-header" style="margin-top:-8px;display: flex;align-items: center; text-align: center;display: inline;">
                <!-- <div> -->
                    <h3 class="card-title" style=" font-family: 'Gotu-Regular', Gotu-Regular;"><b><p style='float:left;'><?php echo "<b style='color:red;'>शाखा -</b> ".$val['Shakha']; ?></p><?php  echo "<b style='color:red;'>विभाग  क्र.- </b>". $val['Vibhag_No'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "<b style='color:red;'>विभागप्रमुख - </b>".$val['Vibhagpramukh']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "<b style='color:red;'>विधानसभा -</b> ".$val['Vidhansabha'];?></b></h3>
                <?php //} ?>
                <hr style="border-color:#F58018;">
            </div>
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row gx-5" Style="margin-top:-25px;align-items: center;">
                            <?php   
                            
                                foreach($CountListMain AS $Key1 => $Value1){
                                    if($val['Vibhag_No'] == $Value1['Vibhag_No']){
                            ?>
                                <div class="col-xs-12 col-xl-4 col-md-4 col-12 m-0 p-0">
                                    <div style="transition: transform 0.2s ease;margin: 9px 9px 9px 9px;padding:15px;border:solid 1px #F58018;border-radius: 10px; background-color:white">
                                        <table class="" width="100%" style="border-color:#F58018;">
                                            <tr style="background-color:#F58018;color:white;border-radius:5px;">
                                                <th colspan="2"><center>शाखा क्र. :<?php echo $Value1['Shakha_No']; ?>  <b style="float:right;"> <a title="Map View"  target="_blank" href="<?php echo 'https://www.google.com/maps/search/?api=1&query='.$Value1['Lattitude'].','.$Value1['Longitude'].'' ; ?>"><i style="color:white;" class="feather icon-map-pin"></i></a></b></center></th>
                                            </tr>
                                            <tr>
                                                <th rowspan="4"> <img src="<?php echo $Value1['Photo_1']; ?>" height="150px" width="120px" style="margin: 5px;"> </th>  
                                                <th style="margin-left:5px;">विधानसभा : <?php echo $Value1['Vidhansabha_Details'];?></th>
                                            </tr>
                                            <tr>
                                                <th style="margin-left:5px;">उपविभागप्रमुख : <?php echo $Value1['UpvibhagPranukh']; ?></th>
                                            </tr>
                                            <tr>
                                                <th style="margin-left:5px;">शाखाप्रमुख : <?php echo $Value1['Shakhapramukh']; ?></th>
                                            </tr>
                                            <tr>
                                                <th style="margin-left:5px;">पत्ता : <?php echo $Value1['Address']; ?></th>
                                            </tr>
                                        </table>
                                        <!-- <div class="Voter-Card" style="transition: transform 0.2s ease;margin: 9px 9px 9px 9px;padding:15px;border:solid 1px #F58018;border-radius: 10px; background-color:white">
                                            <div class="row">
                                                <?php 
                                                    // echo "<b>शाखा क्र. - " . $Value1['Shakha_No'] . "</b>";
                                                ?>
                                                <br>
                                                <div class="col-xl-3 col-md-3 col-12" >
                                                    <img src="<?php echo $Value1['Photo_1']; ?>" height="100"  width="100" style="border:solid 1px grey;border-radius:10px;">
                                                </div>
                                                <div class="col-xl-9 col-md-9 col-12" style="font-family:'Laila-Regular', Laila-Regular;">
                                                    
                                                    <br>विभागसभा - <?php echo $Value1['Vidhansabha']; ?>
                                                    <br>उपविभागप्रमुख - <?php echo $Value1['UpvibhagPranukh'];?>
                                                    <br>शाखाप्रमुख - <?php echo $Value1['Shakhapramukh']; ?>
                                                    <br>पत्ता - <?php echo $Value1['Address']; ?>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>

<?php }  ?>