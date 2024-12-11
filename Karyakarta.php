<section id="row-grouping">
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
        }else{
            $ServerIP ="103.14.99.154";
        }
        if(isset($_SESSION['SurveyUA_DataSource_Karykarta'])&& !empty($_SESSION['SurveyUA_DataSource_Karykarta'])){

            $DataS = $_SESSION['SurveyUA_DataSource_Karykarta'];
        }else{
            $DataS = 'ALL';
        }

    if(isset($_SESSION['SurveyUA_Designation_Karykarta'])&& !empty($_SESSION['SurveyUA_Designation_Karykarta'])){

            $Designation = $_SESSION['SurveyUA_Designation_Karykarta'];
        }else{
            $Designation = 'ALL';
        }
        if($Designation == 'ALL'){
            $DesignationCon = "";
        }else{
            $DesignationCon = "AND dm.Designation_Cd LIKE  '%$Designation%'";
        }
        if($DataS == 'ALL'){
            $DataSCon = "where km.DataSource is not null";
            $ddDataSCon = "";
        }else{
            $DataSCon = "where km.DataSource is not null AND km.DataSource = '$DataS'"; 
            $ddDataSCon = "WHERE DataSource = '$DataS'"; 
        }
    if(isset($_SESSION['SurveyUA_WardNo_ForKarykarta'])&& !empty($_SESSION['SurveyUA_WardNo_ForKarykarta'])){

        $ward = $_SESSION['SurveyUA_WardNo_ForKarykarta'];
        }else{
            $ward = 'ALL';
        }
        if($ward == 'ALL'){
            $WardCon = "";
        }else{
            $WardCon = "AND km.Ward_No = '$ward'";
        }
    if(isset($_SESSION['SurveyUA_AcNo_Karykarta'])&& !empty($_SESSION['SurveyUA_AcNo_Karykarta'])){

    $AcNo = $_SESSION['SurveyUA_AcNo_Karykarta'];
        }else{
            $AcNo = 'ALL';
        }
        if($AcNo == 'ALL'){
            $AcNoCon = "";
            $DDAcNoCon = "";
        }else{
            $AcNoCon = "WHERE Ac_No is not null AND AC_No = '$AcNo'";
            $DDAcNoCon = "WHERE km.AC_No = '$AcNo'";
            $DataSCon = "";
        }

$TableQuery = " SELECT 
                km.KK_Cd,KK_Name
                ,STRING_AGG(dm.Designation_Name, ', ') as Designation, STRING_AGG(kd.Designation_Cd, ', ') as DesignationCd
                ,Mobile_No_1,km.VidhansabhaNumber,Ward_No,Area,km.VibhagName,Age, Gender,
                km.DataSource,
                CONVERT(varchar,BirthDate,34) as BirthDate,List_No,Voter_Id  
                ,(SELECT COUNT(KK_DET_Cd)  FROM [$ServerIP].[MH_CH_WarRoom].dbo.Karyakarta_Details where KK_Cd = km.KK_Cd) as Hitchintak
                FROM [$ServerIP].[MH_CH_WarRoom].dbo.Karyakarta_Master as km 
                INNER JOIN [$ServerIP].[MH_CH_WarRoom].dbo.Karyakarta_Desig_Details as kd on (km.KK_Cd = kd.KK_Cd)
                INNER JOIN [$ServerIP].[MH_CH_WarRoom].dbo.Designation_Master as dm on (kd.Designation_Cd = dm.Designation_Cd)
                 $DataSCon $AcNoCon $DesignationCon
                GROUP BY km.KK_Cd,KK_Name, Mobile_No_1,km.VidhansabhaNumber,Ward_No,Area,km.VibhagName,Age, Gender,
                km.DataSource,CONVERT(varchar,BirthDate,34),List_No,Voter_Id 
                order by Hitchintak DESC;";

$KarykartaData = $db->ExecutveQueryMultipleRowSALData($TableQuery, $userName, $appName, $developmentMode);


$Query = "SELECT DISTINCT(Ward_No) as Ward_No FROM [".$ServerIP."].[MH_CH_WarRoom].dbo.Karyakarta_Master  order by Ward_No";
$dataWard = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);

$QueryDS = "SELECT DISTINCT(DataSource) as DataSource FROM [".$ServerIP."].[MH_CH_WarRoom].dbo.Karyakarta_Master WHERE DataSource is not null";
$DSData = $db->ExecutveQueryMultipleRowSALData($QueryDS, $userName, $appName, $developmentMode);

$QueryDesg = "SELECT  DISTINCT(kd.Designation_Cd) as Designation_Cd,dm.Designation_Name
            FROM [".$ServerIP."].[MH_CH_WarRoom].dbo.Karyakarta_Master As km
            LEFT JOIN [".$ServerIP."].[MH_CH_WarRoom].dbo.Karyakarta_Desig_Details as kd on (km.KK_Cd = kd.KK_Cd)
            LEFT JOIN [".$ServerIP."].[MH_CH_WarRoom].dbo.Designation_Master as dm on (kd.Designation_Cd = dm.Designation_Cd)
            $ddDataSCon $DDAcNoCon ";
$DesignationData = $db->ExecutveQueryMultipleRowSALData($QueryDesg, $userName, $appName, $developmentMode);

$QueryAC = "SELECT DISTINCT(Ac_No) as Ac_No 
            FROM [$ServerIP].[MH_CH_WarRoom].dbo.Karyakarta_Master 
            where Ac_No <> '' AND Ac_No NOT IN ('6','188')  order by Ac_No";
$AcNoData = $db->ExecutveQueryMultipleRowSALData($QueryAC, $userName, $appName, $developmentMode);

?>
<style>
     /* .table td {
    padding: 3px;
    vertical-align: left;
} */
table.dataTable th, table.dataTable td {
    border-bottom: 1px solid #F8F8F8;
    border-top: 0;
    padding: 5PX;
}

.form-control {
    display: block;
    width: 100%;
    height: 30px;
    padding: 3px;
    font-size: 0.96rem;
    font-weight: 400;
    line-height: 1.25;
    color: #4E5154;
    background-color: #FFFFFF;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    -webkit-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition-duration: 0.15s, 0.15s;
    transition-timing-function: ease-in-out, ease-in-out;
    transition-delay: 0s, 0s;
    transition-property: border-color, box-shadow;
}

</style>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body" style="margin-top: -12px;margin-bottom:-30px;">
                        <div class="row">
                                  
                            <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Data Source</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="DataSource"  onchange="SetDataSourceForKarykarta(this.value)">
                                            <option value="ALL">ALL</option>
                                            <?php
                                            if (sizeof($DSData)>0) 
                                            {
                                                foreach ($DSData as $key => $value) 
                                                {
                                                    if($DataS == $value["DataSource"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value["DataSource"];?>"><?php echo "<b>".$value["DataSource"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value["DataSource"];?>"><?php echo "<b>".$value["DataSource"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Assembly</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="Ac_No"  onchange="SetACNoDataSourceForKarykarta(this.value)">
                                            <option value="ALL">ALL</option>
                                            <?php
                                            if (sizeof($AcNoData)>0) 
                                            {
                                                foreach ($AcNoData as $key => $value) 
                                                {
                                                    if($AcNo == $value["Ac_No"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value["Ac_No"];?>"><?php echo "<b>".$value["Ac_No"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value["Ac_No"];?>"><?php echo "<b>".$value["Ac_No"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="Designation"  onchange="SetDesignationForKarykarta(this.value)">
                                            <option value="ALL">ALL</option>
                                            <?php
                                            if (sizeof($DesignationData)>0) 
                                            {
                                                foreach ($DesignationData as $key => $value) 
                                                {
                                                    if($Designation == $value["Designation_Cd"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value["Designation_Cd"];?>"><?php echo "<b>".$value["Designation_Name"]."</b>"; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value["Designation_Cd"];?>"><?php echo "<b>".$value["Designation_Name"]."</b>" ; ?></option>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?> 
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xs-2 col-xl-2 col-md-2 col-12">
                                <div id='spinnerLoaderKarykarta' style='display:none;margin-top:15px;'>
                                    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Karyakarta Detail - (<?php echo sizeof($KarykartaData); ?>)</h4>
                    
                </div>
                
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-hover-animation table-hover table-striped" id="KarykartaTable">
                                <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;">Sr</th>
                                        <th style="background-color:#36abb9;color: white;">Name</th>
                                        <th style="background-color:#36abb9;color: white;">Ward</th>
                                        <th style="background-color:#36abb9;color: white;">Mobile</th>
                                        <th style="background-color:#36abb9;color: white;" title="VidhanSabha">VS</th>
                                        <th style="background-color:#36abb9;color: white;">Area</th>
                                        <th style="background-color:#36abb9;color: white;" title="VibhagName">VbN</th>
                                        <th style="background-color:#36abb9;color: white;" title="Designation">Desg</th>
                                        <th style="background-color:#36abb9;color: white;">Age</th>
                                        <th style="background-color:#36abb9;color: white;">Gender</th>
                                        <th style="background-color:#36abb9;color: white;" title="Birthdate">Birdt</th>
                                        <th style="background-color:#36abb9;color: white;" title="">ListNo</th>
                                        <th style="background-color:#36abb9;color: white;" title="">VoterId</th>
                                        <th style="background-color:#36abb9;color: white;" title="">Hitchintak</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                $SR = 1;
                                foreach($KarykartaData as $key=>$value){

                                
                                ?>
                                    <tr>
                                        <td><?php echo $SR++; ?></td>
                                        <td><?php echo $value['KK_Name']; ?></td>
                                        <td><?php echo $value['Ward_No']; ?></td>
                                        <td><?php echo $value['Mobile_No_1']; ?></td>
                                        <td><?php echo $value['VidhansabhaNumber']; ?></td>
                                        <td><?php echo $value['Designation']; ?></td>
                                        <td><?php echo $value['Area']; ?></td>
                                        <td><?php echo $value['VibhagName']; ?></td>
                                        <td><?php echo $value['Age']; ?></td>
                                        <td><?php echo $value['Gender']; ?></td>
                                        <td><?php echo $value['BirthDate']; ?></td>
                                        <td><?php echo $value['List_No']; ?></td>
                                        <td><?php echo $value['Voter_Id']; ?></td>
                                        <td><a onclick="getHitchintakData('<?php echo $value['KK_Cd']; ?>')"><?php echo $value['Hitchintak']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="HitchintakDataTable">

    </div>
</section>
<script>
    function SetACNoDataSourceForKarykarta(AcNo) {
            var ajaxRequest; // The variable that makes Ajax possible!
        
            try {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
            } catch (e) {
                // Internet Explorer Browsers
                try {
                    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                    }
                }
            }
        
            ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState == 4) {
                        location.reload(true);
                        $('#spinnerLoaderKarykarta').show();
                        // window.location.href='index.php?p=MapClientDashboard';
                    }
                }
            
            if (AcNo === '') {
                alert("Please Select Data Source!");
            } else {
                // alert(SiteName);
                // alert(ElectionName);
                var queryString = "?AcNo="+AcNo;
                ajaxRequest.open("POST", "setDataSourceForKarykartaInSession.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        
        }
    function getHitchintakData(KKCD) {
            var ajaxRequest; // The variable that makes Ajax possible!
        
            try {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
            } catch (e) {
                // Internet Explorer Browsers
                try {
                    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                    }
                }
            }
        
            ajaxRequest.onreadystatechange = function() {
                    if (ajaxRequest.readyState == 4) {
                        var ajaxDisplay = document.getElementById('HitchintakDataTable');
                        ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        $('#HitchintakTableDetail').show(); 
                        $('html, body').animate({
                        scrollTop: $("#HitchintakDataTable").offset().top
                    }, 500); 
                     $(document).ready(function () {
                        $('#HitchintakTable').DataTable({
                        "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                        });
                    });
                    }
                }
            
            if (KKCD === '') {
                alert("Please Select Data Source!");
            } else {
                // alert(SiteName);
                // alert(ElectionName);
                var queryString = "?KKCD="+KKCD;
                ajaxRequest.open("POST", "setKKCdForKarykartaInSession.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        
        }
</script>