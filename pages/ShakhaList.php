<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$fromDate = '2023-05-25';
$toDate = date('Y-m-d');

// if($ServerIP == "103.14.97.228"){
//     $ServerIP = '.';
// }else{
//     $ServerIP ="103.14.97.228";
// }

if(isset($_GET['Vibhag']) && !empty($_GET['Vibhag'])){
    $Vibhag = $_GET['Vibhag'];
    $vibhagCon = "WHERE vw.Vibhag_No = '$Vibhag'";
}else{
    $vibhagCon = "WHERE vw.Shakha_No <> '27'";
}
if($ULB == 'KDMC_2023'){

    $sql2 = "SELECT DISTINCT(sd.Shakha_No),Longitude,Lattitude,Address,Photo_1,vw.Shakha_No,vw.Vidhansabha_Details,vw.UpvibhagPranukh
    ,vw.Shakhapramukh,vw.Vibhag_No,vw.Vibhagpramukh
    FROM [103.14.97.58].BMC_Details.dbo.KDMC_Shakha_Details as sd
    INNER JOIN [103.14.97.58].BMC_Details.dbo.VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
    WHERE vw.Vibhag_No IN ('138','142','143','144','141','140')
    ORDER BY vw.Vibhag_No";
}else{
$sql2 = "SELECT DISTINCT(sd.Shakha_No),Longitude,Lattitude,Address,Photo_1,vw.Shakha_No,vw.Vidhansabha_Details,vw.UpvibhagPranukh
,vw.Shakhapramukh,vw.Vibhag_No,vw.Vibhagpramukh
FROM BMC_Details..Mumbai_Shakha_Details as sd
INNER JOIN BMC_Details..VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
$vibhagCon
AND  vw.Vibhag_No NOT IN ('138','142','143','144','141','140')
ORDER BY vw.Vibhag_No";
}

$CountListMain = $db->ExecutveQueryMultipleRowSALData($ULB,$sql2, $userName, $appName, $developmentMode);

$ShakhaCount = sizeof($CountListMain);
// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>
<style>
    @font-face {
  font-family: 'Gotu-Regular';
  src: url('app-assets/fonts/font-awesome/fonts/Gotu-Regular.ttf') format('truetype');
}

th,td {
  font-family: 'Gotu-Regular', Gotu-Regular;
}
</style>
<div class="row" id="">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="font-family: 'Gotu-Regular', Gotu-Regular;">
                

                <b> <?php if($ULB == 'KDMC_2023' ) { ?> कल्याण <?php }else{ ?> मुंबई <?php } ?> शाखा तपशील (<?php echo $ShakhaCount; ?>)</b>
                </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-striped table-hover" id="ShakhaLisstTable" width=100%>
                                                <thead>
                                                    <tr>
                                                        <td style="background-color:#F58018;color: white;width:33px;">अ.क्र.</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">विभाग क्र.</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">विभागप्रमुख</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">शाखा क्र.</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">शाखाप्रमुख</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">विधानसभा</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">उपविभागप्रमुख</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">पत्ता</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">Photo</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td style="font-weight:bold;"><?php echo $srNo++; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Vibhag_No"]; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Vibhagpramukh"] ; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Shakha_No"]; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Shakhapramukh"] ; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Vidhansabha_Details"] ; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["UpvibhagPranukh"] ; ?></td>
                                                                <td style="font-weight:bold;"><?php echo $value["Address"] ; ?></td>
                                                                <td> 
                                                                <img src="<?php echo $value['Photo_1']?>" class="docimg" height="80" width="90" style="border:1px solid #F58018;border-radius:12px;" <?php if($value['Photo_1'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                                                
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>