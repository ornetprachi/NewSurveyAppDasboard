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

$sql2 = "SELECT 
        COALESCE(sm.SiteName, '') AS SiteName,
        COALESCE(sm.ElectionName, '') AS ElectionName,
        COALESCE(sm.Site_Cd, 0) AS Site_Cd,
        sm.ClientName
		,(SELECT COUNT(DISTINCT(SocietyName)) FROM Society_Master as s WHERE s.SiteName = sm.SiteName ) as Society
		,(SELECT COUNT(dd.divyang_det_id) FROM divyang_details as dd 
		INNER JOIN Society_Master as ssm on (ssm.Society_Cd = dd.society_cd)
		WHERE ssm.SiteName =  sm.SiteName) as divyang
        FROM Site_Master as  sm
        INNER JOIN Survey_Entry_Data..Election_Master as elm on (sm.ElectionName = elm.ElectionName)
        WHERE  elm.ULB = '$ULB'
        GROUP BY sm.SiteName, sm.site_Cd, sm.ElectionName,sm.ClientName
        ORDER BY Society DESC;";


$CountListMain = $db->ExecutveQueryMultipleRowSALData($ULB, $sql2, $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #e6f4f4;
}
</style>


<!-- <div class="row match-height">
    <div class="col-md-6">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

    
<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>

<div class="row" id="">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Building Listing Summary Report
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
                                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Client Name</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Society</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Divyang</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo $value["ClientName"] . " (" . $value["SiteName"] . ")"; ?></td>
                                                                <td><?php echo $value["Society"]; ?></td>
                                                                <td><?php echo $value["divyang"]; ?></td>
                                                                <td> 
                                                                    <a href="<?php echo 'index.php?p=building-listing-grid&SiteName='.$value['SiteName']; ?>">
                                                                        <i style="color:#41bdcc;cursor:pointer;" class="feather icon-grid"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a href="<?php echo 'index.php?p=building-listing-list&SiteName='.$value['SiteName']; ?>">
                                                                        <i style="color:#41bdcc;cursor:pointer;" class="feather icon-list"></i>
                                                                    </a>
                                                                    &nbsp;&nbsp;
                                                                    <a onclick="getSiteName('<?php echo $value['SiteName'];?>','<?php echo $value['ElectionName'];?>');"><i style="color:#41bdcc;" class="feather icon-map-pin"></i></a>
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
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Building Listing Summary Report
                </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <!-- <div class="table-responsive">
                                        <div class="card-body text-center"> -->
                                        <script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>
                                            <div id='myDiv'></div>
                                            <script>
                                                var data = [{
                                                    type: "pie",
                                                    values: [<?php
                                                        if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                            echo $value['TotalCount'].",";
                                                    }
                                                }?>],
                                                    labels: [<?php
                                                        if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                            echo "'".$value['SiteName']."',";
                                                    }
                                                }?>],
                                                    // textinfo: "label+percent",
                                                    insidetextorientation: "radial"
                                                    }]

                                                    var layout = [{
                                                    height: 700,
                                                    width: 800
                                                    }]

                                                    Plotly.newPlot('myDiv', data, layout)
                                                </script>	
                                        <!-- </div>
                                        </div> -->
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