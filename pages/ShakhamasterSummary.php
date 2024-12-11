<section id="dashboard-analytics">

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
//     $ServerIP =".";
// }else{
//     $ServerIP ="103.14.97.228";
// }
if($ULB == 'KDMC_2023'){
    $vibgQuery = "SELECT DISTINCT(Vibhag_No) as Vibhag_No,Vibhagpramukh,Vidhansabha,Count(DISTINCT(vw.Shakha_No)) as Shakha 
    FROM [103.14.97.58].BMC_Details.dbo.VibhagWiseWard as vw
    INNER JOIN [103.14.97.58].BMC_Details.dbo.KDMC_Shakha_Details as msd on (vw.Shakha_No = msd.Shakha_No)
    WHERE vw.Vibhag_No IN ('138','142','143','144','141','140')
    GROUP By Vibhag_No,Vibhagpramukh,Vidhansabha";
}else{
$vibgQuery = "SELECT DISTINCT(Vibhag_No) as Vibhag_No,Vibhagpramukh,Vidhansabha,Count(DISTINCT(vw.Shakha_No)) as Shakha 
FROM BMC_Details..VibhagWiseWard as vw
INNER JOIN BMC_Details..Mumbai_Shakha_Details as msd on (vw.Shakha_No = msd.Shakha_No)
WHERE vw.Shakha_No <> '27'
AND  vw.Vibhag_No NOT IN ('138','142','143','144','141','140')
GROUP By Vibhag_No,Vibhagpramukh,Vidhansabha;";
}

$VibhagData = $db->ExecutveQueryMultipleRowSALData($vibgQuery, $userName, $appName, $developmentMode);
$VibhagCount = sizeof($VibhagData);
$totalShakha = array_sum(array_column($VibhagData, 'Shakha'));
// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #F6E8DE;
}
@font-face {
  font-family: 'Gotu-Regular';
  src: url('app-assets/fonts/font-awesome/fonts/Gotu-Regular.ttf') format('truetype');
}

th,td {
  font-family: 'Gotu-Regular', Gotu-Regular;
}
</style>

<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>

<div class="row" id="">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" style="font-family: 'Gotu-Regular', Gotu-Regular;">
                  <b>   <?php if($ULB == 'KDMC_2023' ) { ?> कल्याण <?php }else{ ?> मुंबई <?php } ?> विभाग (<?php echo $VibhagCount; ?>) - एकुण शाखा (<?php echo $totalShakha ; ?>)</b>
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
                                            <table class="table table-hover-animation table-striped table-hover" id="VibhagSummaryTable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <td style="background-color:#F58018;color: white;width:33px;">अ.क्र.</td>
                                                        <td style="background-color:#F58018;color: white;width:33px;">विभाग</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">विधानसभा </td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">विभागप्रमुख</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">शाखा</td>
                                                        <td style="background-color:#F58018;color: white;width:45px;">Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($VibhagData) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($VibhagData as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo $value["Vibhag_No"]; ?></td>
                                                                <td><?php echo $value["Vidhansabha"]; ?></td>
                                                                <td><?php echo $value["Vibhagpramukh"]; ?></td>
                                                                <td><?php echo $value["Shakha"]; ?></td>
                                                                <td> 
                                                                    <a href="<?php echo 'index.php?p=ShakhaMasterGridView&Vibhag='.$value['Vibhag_No']; ?>">
                                                                        <i style="color:#F58018;cursor:pointer;" class="feather icon-grid"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a href="<?php echo 'index.php?p=ShakhaList&Vibhag='.$value['Vibhag_No']; ?>">
                                                                        <i style="color:#F58018;cursor:pointer;" class="feather icon-list"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a href="<?php echo 'index.php?p=SakhaMaster&Vibhag='.$value['Vibhag_No']; ?>"><i style="color:#F58018;" class="feather icon-map-pin"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th style="background-color:#F58018;color: white;width:33px;"></th>
                                                        <th style="background-color:#F58018;color: white;width:33px;"></th>
                                                        <th style="background-color:#F58018;color: white;width:33px;"></th>
                                                        <th style="background-color:#F58018;color: white;width:33px;"></th>
                                                        <th style="background-color:#F58018;color: white;width:33px;"><?php echo $totalShakha; ?></th>
                                                        <th style="background-color:#F58018;color: white;width:33px;"></th>
                                                    </tr>
                                                </tfoot>
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
  


</section>
<script>
    
</script>