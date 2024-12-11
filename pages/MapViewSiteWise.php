
<?php
// session_start();
// include 'api/includes/DbOperation.php';
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];

        $fullName = $_SESSION['SurveyUA_FullName'];
        $Mapdata = array();
        $currentDate = date('Y-m-d');
        $previousdate = date('Y-m-d', strtotime('-7 days'));
        $fromDate = $currentDate." ".$_SESSION['StartTime'];
        $toDate =$currentDate." ".$_SESSION['EndTime'];
        
        // $electionName_of_Dashboard = 'PT188'; 

        if(
            (isset($_SESSION['SurveyUA_SiteName_For_ClientDashboaard']) && !empty($_SESSION['SurveyUA_SiteName_For_ClientDashboaard'])) 
        ) 
        {
            $SiteName = $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'];
            $ElectionName = $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'];
        }else{
            $SiteName = 'Detail';
        }
        if($SiteName == 'Detail'){
            $ElectionCon = "WHERE sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB' ";
            $SiteCon = "AND pm.SiteName = '$SiteName'";
            $SiteCond = "";
            $ElectionKMLCon = "WHERE pm.ElectionName <> 'CHANKYATEST' AND pm.SiteName not like 'SSK%' AND elm.ULB = '$ULB'";
            $ElectionSiteDropKMLCon = "WHERE sm.ElectionName <> 'CHANKYATEST' AND sm.SiteName not like 'SSK%'  AND elm.ULB = '$ULB'";
        }
        elseif($SiteName == 'All'){
            $ElectionCon = "WHERE sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB' ";
            $SiteCon = "AND pm.SiteName = '$SiteName'";
            $SiteCond = "";
            $ElectionKMLCon = "WHERE pm.ElectionName <> 'CHANKYATEST' AND pm.SiteName not like 'SSK%' AND elm.ULB = '$ULB'";
            $ElectionSiteDropKMLCon = "WHERE sm.ElectionName <> 'CHANKYATEST' AND sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB'";
        }
        else {
        $ElectionCon = "WHERE sm.ElectionName  = '$ElectionName'";
        $ElectionKMLCon = "WHERE pm.ElectionName  = '$ElectionName'";
        $ElectionSiteDropKMLCon = "WHERE sm.ElectionName  = '$ElectionName'";
        $SiteCon = " AND pm.SiteName = '$SiteName'";
        $SiteCond = " AND sm.SiteName = '$SiteName'";
        }
        //KML For site //
        $MapData = array();

        $MapQuery = "SELECT 
            sm.Society_Cd,sm.SocietyName,sm.Latitude,sm.Longitude,sm.Building_Image,sm.SiteName,
            CASE 
                WHEN sm.Permission = 1 and sm.PermissionDone = 1 THEN 0
                WHEN sm.Permission = 0 THEN 0
                WHEN sm.Permission = 1 AND sm.PermissionDone = 0 THEN 1
                ELSE 0
            END AS Permission,
            CASE 
                WHEN ((SELECT COUNT(*) FROM Dw_VotersInfo WHERE Society_Cd = sm.Society_Cd) > 0) THEN 1
                WHEN ((SELECT COUNT(*) FROM NewVoterRegistration WHERE Society_Cd = sm.Society_Cd) > 0) THEN 1
                WHEN ((SELECT COUNT(*) FROM LockRoom WHERE Society_Cd = sm.Society_Cd) > 0) THEN 1
                ELSE 0
            END AS HasData, 
            (SELECT 
                dd.divyang_det_id,CONCAT(dd.first_name,' ',dd.middle_name,' ',dd.last_name) as FullName,dd.flat_no,dd.added_date,dd.added_by,em.ExecutiveName,
                dd.disability_type_id,ddm.disability_eng,ddm.disability_mar
                FROM divyang_details as dd
                LEFT JOIN Survey_Entry_Data..Executive_Master as em on(em.Executive_Cd = dd.added_by)
                INNER JOIN Survey_Entry_Data..divyang_disability_master as ddm on (ddm.disability_id = dd.disability_type_id)
                WHERE dd.society_cd = sm.Society_Cd
                FOR JSON PATH
            ) AS Records
                FROM Society_Master as sm
                INNER JOIN Site_Master as ss on (sm.SiteName = ss.SiteName)
                INNER JOIN Survey_Entry_Data..Election_Master as elm on (sm.ElectionName = elm.ElectionName)
            $ElectionCon $SiteCond
            ORDER BY Records DESC";
        $MapData = $db->ExecutveQueryMultipleRowSALData($ULB, $MapQuery , $userName, $appName, $developmentMode);
        // print_r($MapData);
        // die();
        $SiteMData = array();
        $SiteMQuery = " SELECT sm.SiteName,ss.ClientName,COUNT(DISTINCT(Society_Cd)) As Societies FROM Society_Master as sm
        INNER JOIN Site_Master As ss on (sm.SiteName = ss.SiteName)
        INNER JOIN Survey_Entry_Data..Election_Master as elm on (sm.ElectionName = elm.ElectionName)
        $ElectionSiteDropKMLCon
        GROUP BY sm.SiteName,ss.ClientName";
        $SiteMData = $db->ExecutveQueryMultipleRowSALData($ULB, $SiteMQuery , $userName, $appName, $developmentMode);
       
       

        $SiteKmlData = array();
        $SiteKMLQuery = "SELECT sm.SiteName,sm.KMLFile_Url FROM Site_Master sm
                            INNER JOIN Society_Master soc
                            ON sm.SiteName = soc.SiteName
                            INNER JOIN Survey_Entry_Data..Election_Master as elm on (sm.ElectionName = elm.ElectionName)
                            $ElectionSiteDropKMLCon $SiteCond  
                            GROUP BY sm.SiteName , sm.KMLFile_Url
                             ORDER BY sm.SiteName DESC";
        $SiteKmlData = $db->ExecutveQueryMultipleRowSALData($ULB, $SiteKMLQuery , $userName, $appName, $developmentMode);
        // print_r("<pre>");
        // print_r($SiteKmlData);
        // print_r("</pre>");
        $PocketKmlData = array();
        // $PocketKMLQuery = "SELECT pm.Pocket_Cd,pm.SiteName,KMLFile_Url 
        //                     FROM Pocket_Master as pm
        //                     INNER JOIN Survey_Entry_Data..Election_Master as elm  on (pm.ElectionName = elm.ElectionName) 
        //                     $ElectionKMLCon  $SiteCon ";
        // $PocketKmlData = $db->ExecutveQueryMultipleRowSALData($ULB, $PocketKMLQuery , $userName, $appName, $developmentMode);

        //END KML For site //
       $sitedropdownquery = "SELECT COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                            COALESCE(sm.ClientName,'') AS ClientName, 
                            COALESCE(sm.SiteName,'') AS SiteName, 
                            COALESCE(sm.ElectionName,'') AS ElectionName
                            FROM Site_Master as sm
                            inner join Society_Master as soc
                            ON sm.SiteName = soc.SiteName
                            INNER JOIN Survey_Entry_Data..Election_Master as elm 
                            on (sm.ElectionName = elm.ElectionName)
                            $ElectionSiteDropKMLCon
                            GROUP BY sm.Site_Cd,sm.ClientName,sm.SiteName,sm.ElectionName";

        $dataSite = $db->ExecutveQueryMultipleRowSALData($ULB, $sitedropdownquery, $userName, $appName, $developmentMode);
        $MapMarkerColorIcons = array("https://maps.google.com/mapfiles/ms/icons/orange-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/yellow-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/pink-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/purple-dot.png",
        "http://maps.google.com/mapfiles/ms/icons/ltblue-dot.png");
        $SizeofMapMarkerColors = sizeof($MapMarkerColorIcons);
// print_r("<pre>");
// print_r($PocketKmlData);
// print_r("</pre>");
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<style>
    .login-button
{
    background-color: #41bdcc;
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 5px 25px;
    font-size: 16px;
    box-shadow: 0px 0px 2px 0px #000;
}
.icon
{
    margin-right: 5px;
    display: none;
}
.loading
{
    background-color: #41bdcc;
    color: #eee;
}
.loading .icon
{
    display: inline-block;
    color: #eee;
    animation: spin 2s linear infinite;
}
@keyframes spin
{
    0%
    {
    transform: rotate(0deg);
    }
    100%
    {
    transform: rotate(360deg);
    }
}
</style>
<body>
    <div class = "row">
<div class="col-3">
    <div class="form-group">
         <label>Site</label>
        <div class="controls">
            <select class="select2 form-control"  name="siteName" onchange="setSiteForClientDashboardInSession(this.value)">
            <!-- onChange="setSiteForDashboardInSession(this.value)" -->
            <option value="All">ALL</option>
         
                 <?php
                if (sizeof($dataSite)>0) 
                {
                    foreach ($dataSite  as $key => $value) 
                      {
                          if($SiteName == $value["SiteName"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['SiteName']; ?>"><?php echo $value['ClientName']."(".$value["SiteName"].")"; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["SiteName"];?>"><?php echo $value['ClientName']."(".$value["SiteName"].")";?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
</div>
<div class="col-2">
    <div class="form-group" style="padding-top:30px;">
        <div class="controls">
            <button class="login-button" id="DetailButton" onclick="getAllSiteData('Detail')">Detail</button>
        </div>
    </div>
</div>
<script>
     document.getElementById('DetailButton').addEventListener("click", function(){
                    this.classList.add("loading");
                    this.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
                });
</script>
    </div>
    <div id= "map" style="height:600px;width:97%;">
    </div>
</body>

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap"></script>

<script>
    document.getElementById('DetailButton').addEventListener("click", function() {
        this.classList.add("loading");
        this.innerHTML = "<i class='fa fa-refresh fa-spin'></i> Loading..";
    });

    function initMap() {
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 18.9931182, lng: 73.1205893 },
            zoom: 12,
            mapTypeId: 'satellite'
        });

        const infowindow = new google.maps.InfoWindow();

        function addMarker(lat, lng, SocietyName, Building_Image, Detail, image) {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                map: map,
                icon: image
            });

            google.maps.event.addListener(marker, "click", () => {
                infowindow.setContent(
                    `<table>
                        <tr><th rowspan="3"><img src="${Building_Image}" height="90px"></th></tr>
                        <tr><th>${SocietyName}</th></tr>
                        <tr><th>${Detail}</th></tr>
                    </table>`
                );
                infowindow.open(map, marker);
            });
        }

        <?php
    
                foreach ($MapData as $key => $value){
                    $MapSite = $value['SiteName'];
                    $Permission = $value['Permission'];
                    $Survey = $value['HasData'];
                    if(!empty($value['Records'])){
                        $DivyangData = json_decode($value['Records'], true);
                    }else{
                        
                        $DivyangData = '';
                    }
                    if($Permission == 0){
                        if($Survey == 1){
                            if(!empty($DivyangData)){
                                $flagColor = 'blue';
                            }else{
                                $flagColor = 'green';  
                            }
                        }
                        else{ 
                            if(!empty($DivyangData)){
                                $flagColor = 'blue';
                            }else{
                                $flagColor = 'orange'; 
                            } 
                        }
                    }else{
                        $flagColor = 'red';
                    }
                    
                    $details = '';
                    if(!empty($DivyangData)){
                        foreach ($DivyangData as $record) {
                            $details .= "Room No.: " . $record['flat_no'] . "<br>";
                            $details .= "Divyang Name: " . $record['FullName'] . "<br>";
                            $details .= "Category: " . $record['disability_eng'] . "(". $record['disability_mar'] .")<br>";
                            if($record['added_date'] != NULL){
                                $details .= "Date: " . $record['added_date'] . "<br>";
                            }
                            $details .= "Survey By: " . $record['ExecutiveName'] . "<br>";
                        }
                    }
         
            
               
                
                if($value["Latitude"] != '0' && $value["Latitude"] != NULL && $value["Latitude"] != ''){

            ?>
                addMarker('<?php echo $value["Latitude"]; ?>','<?php echo $value["Longitude"]; ?>','<?php echo $value["SocietyName"]; ?>','<?php echo $value["Building_Image"]; ?>','<?php  echo $details;?>','<?php echo "https://maps.google.com/mapfiles/ms/icons/".$flagColor."-dot.png";?>');
            <?php
                }
            }         
    ?>
    <?php if(sizeof($SiteKmlData) > 0){ ?>

    function addKMLMarkerWithTimeout(src, timeout){
        var kmlLayer = new google.maps.KmlLayer(src, {
            suppressInfoWindows: true,
            preserveViewport: false,
            map: map
        });

        google.maps.event.addListener(kmlLayer, 'click', function(event) {
            var content = "<div>" + event.featureData.infoWindowHtml + "</div>";
            infowindowKML.setPosition(event.latLng);
            infowindowKML.setOptions({
                pixelOffset: event.pixelOffset,
                content: content
            });
            infowindowKML.open(map);
        });
    }

    <?php
    if(sizeof($SiteKmlData) > 0){
        $srNoKML = 0;
        if(sizeof($SiteKmlData) == "1"){
            $KMLFile_Url =  $SiteKmlData[0]['KMLFile_Url'];

            $srNoKML = $srNoKML+1;
            if(filter_var($KMLFile_Url, FILTER_VALIDATE_URL)){
    ?>
                addKMLMarkerWithTimeout('<?php echo $KMLFile_Url; ?>', '<?php echo ($srNoKML*1000); ?>');
    <?php
                }
        }else{
            // foreach ($SiteKmlData as $key => $value){
                $srNoKML = $srNoKML+1;
               
    ?>
                addKMLMarkerWithTimeout('http://103.14.99.154/SurveyUtilityAppApi/upload/<?php echo $ULB; ?>.kml', '<?php echo ($srNoKML*1000); ?>');
    <?php

                // }
                    
            // }
        }
    }
    
   
    }
    if(sizeof($PocketKmlData) > 0){ ?>

        function addKMLMarkerWithTimeoutP(src, timeout){
            var kmlPLayer = new google.maps.KmlLayer(src, {
                suppressInfoWindows: true,
                preserveViewport: false,
                map: map
            });

            google.maps.event.addListener(kmlPLayer, 'click', function(event) {
                var content = "<div>" + event.featureData.infoWindowHtml + "</div>";
                infowindowKML.setPosition(event.latLng);
                infowindowKML.setOptions({
                    pixelOffset: event.pixelOffset,
                    content: content
                });
                infowindowKML.open(map);
            });
        }
<?php
    if(sizeof($PocketKmlData) > 0){
        $srNoPKML = 0;
        if(sizeof($PocketKmlData) == "1"){
            $KMLPFile_Url =  $PocketKmlData[0]['KMLFile_Url'];

            $srNoPKML = $srNoPKML+1;
            if(filter_var($KMLPFile_Url, FILTER_VALIDATE_URL)){
    ?>
                addKMLMarkerWithTimeoutP('<?php echo $KMLPFile_Url; ?>', '<?php echo ($srNoPKML*1000); ?>');
    <?php
                }
        }else{
            if(sizeof($PocketKmlData) >= '15'){
                if($SiteName == 'KL160'){
                    $site = 'KL_160';
                }else{
                    $site =$SiteName;
                }
                ?>
                addKMLMarkerWithTimeoutP('http://103.14.99.154/SurveyUtilityAppApi/upload/PocketKml/<?php echo $site; ?>.kml');
            <?php }else{
            foreach ($PocketKmlData as $key => $valueP){
                $srNoPKML = $srNoPKML+1;
                if(filter_var($valueP["KMLFile_Url"], FILTER_VALIDATE_URL)){
    ?>
                addKMLMarkerWithTimeoutP('<?php echo $valueP["KMLFile_Url"]; ?>', '<?php echo ($srNoPKML*1000); ?>');
    <?php

                }
                  
            }
        }
        }
    }
}
    ?>

    }
</script>
<!-- Local Link -->
<!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=map" async defer ></script> -->
<!-- Local Link -->

<!-- Live Link -->
<!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=map"  ></script> -->
<!-- Live Link -->
</html>
