
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
            (isset($_SESSION['SurveyUA_SiteName_For_Dashboaard']) && !empty($_SESSION['SurveyUA_SiteName_For_Dashboaard'])) 
        ) 
        {
            $SiteName = $_SESSION['SurveyUA_SiteName_For_Dashboaard'];
        }else{
            $SiteName = 'Detail';
        }
        if($SiteName == 'Detail'){
            $ElectionCon = "WHERE sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB' AND sm.SiteName <> 'PRT100'";
            $SiteCon = " AND pm.SiteName = '$SiteName'";
            $SiteCond = "";
            $ElectionKMLCon = "WHERE pm.ElectionName <> 'CHANKYATEST' AND pm.SiteName not like 'SSK%' AND elm.ULB = '$ULB'";
            if($ULB == 'PANVEL'){
                $ElectionSiteDropKMLCon = "WHERE sm.ElectionName = 'PT188' AND sm.SiteName not like 'SSK%'  AND elm.ULB = '$ULB'  AND sm.SiteName <> 'PRT100'";
            }else{
                $ElectionSiteDropKMLCon = "WHERE sm.ElectionName <> 'CHANKYATEST' AND sm.SiteName not like 'SSK%'  AND elm.ULB = '$ULB'  ";
            }
        }
        elseif($SiteName == 'All'){
            $ElectionCon = "WHERE sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB' AND sm.SiteName <> 'PRT100'  ";
            $SiteCon = " AND pm.SiteName = '$SiteName'";
            $SiteCond = "";
            $ElectionKMLCon = "WHERE pm.ElectionName <> 'CHANKYATEST' AND pm.SiteName not like 'SSK%' AND elm.ULB = '$ULB'";
            if($ULB == 'PANVEL'){
                $ElectionSiteDropKMLCon = "WHERE sm.ElectionName = 'PT188' AND sm.SiteName not like 'SSK%'  AND elm.ULB = '$ULB'  AND sm.SiteName <> 'PRT100'";
            }else{
            $ElectionSiteDropKMLCon = "WHERE sm.ElectionName <> 'CHANKYATEST' AND sm.SiteName not like 'SSK%' AND elm.ULB = '$ULB' ";
            }
        }
        else {
        $ElectionCon = "WHERE elm.ULB  = '$ULB' AND sm.SiteName <> 'PRT100'";
        $ElectionKMLCon = "WHERE elm.ULB  = '$ULB'";
        if($ULB == 'PANVEL'){
            $ElectionSiteDropKMLCon = "WHERE elm.ULB  = '$ULB' AND sm.SiteName <> 'PRT100' AND sm.ElectionName = 'PT188'";
        }else{
        $ElectionSiteDropKMLCon = "WHERE elm.ULB  = '$ULB' ";
        }
        $SiteCon = " AND pm.SiteName = '$SiteName'";
        $SiteCond = " AND sm.SiteName = '$SiteName'";
        }
        //KML For site //
        $MapData = array();
        // $MapQuery = " SELECT Society_Cd,sm.ElectionName,Building_Image,SocietyName,sm.SiteName,em1.ExecutiveName As SurveyBy,em.ExecutiveName as ListingBy,
        // CONVERT(varchar,sm.BList_UpdatedDate,103) AS ListingDate,CONVERT(varchar,sm.Survey_UpdatedDate,103) AS SurveyDate,Longitude,Latitude 
        // FROM Society_Master as sm
        // LEFT JOIN Executive_Master as em on (sm.BList_UpdatedByUser = em.UserName)
        // LEFT JOIN Executive_Master as em1 on (sm.Survey_UpdatedByUser = em1.UserName) $ElectionCon $SiteCond
        // ORDER BY sm.SiteName";

    
        $MapQuery = "  SELECT Society_Cd,sm.ElectionName,RoomSurveyDone,ss.ClientName,Building_Image,SocietyName,sm.SiteName,sm.SurveyBy, sm.ListedBy,
        COALESCE(CONVERT(varchar,sm.ListedDate,29),'') AS ListingDate,CONVERT(varchar,sm.SurveyDate,27) AS SurveyDate,Longitude,Latitude 
        FROM DataAnalysis..SurveySummary as sm
        LEFT JOIN Survey_Entry_Data..Executive_Master as em on (sm.ListedBy = em.UserName)
        LEFT JOIN Survey_Entry_Data..Executive_Master as em1 on (sm.SurveyBy = em1.UserName) 
        INNER JOIN Survey_Entry_Data..Site_Master as ss on (sm.SiteName = ss.SiteName)
        INNER JOIN Election_Master as elm on (sm.ElectionName = elm.ElectionName)
        $ElectionCon $SiteCond
        ORDER BY sm.SiteName";
        $MapData = $db->ExecutveQueryMultipleRowSALData($MapQuery , $userName, $appName, $developmentMode);
        $SiteMData = array();
         $SiteMQuery = " SELECT  sm.SiteName,ss.ClientName,COUNT(DISTINCT(Society_Cd)) As Societies FROM Society_Master as sm
        INNER JOIN Site_Master As ss on (sm.SiteName = ss.SiteName)
        INNER JOIN Election_Master as elm on (sm.ElectionName = elm.ElectionName)
       $ElectionSiteDropKMLCon
        GROUP BY sm.SiteName,ss.ClientName";
        $SiteMData = $db->ExecutveQueryMultipleRowSALData($SiteMQuery , $userName, $appName, $developmentMode);
       
       

        $SiteKmlData = array();
          $SiteKMLQuery = "SELECT sm.SiteName,sm.KMLFile_Url FROM Site_Master sm
                            INNER JOIN Society_Master soc
                            ON sm.SiteName = soc.SiteName
                            INNER JOIN Election_Master as elm on (sm.ElectionName = elm.ElectionName)
                            $ElectionSiteDropKMLCon $SiteCond  
                            GROUP BY sm.SiteName , sm.KMLFile_Url";
        $SiteKmlData = $db->ExecutveQueryMultipleRowSALData($SiteKMLQuery , $userName, $appName, $developmentMode);
        
        $PocketKmlData = array();
        $PocketKMLQuery = "SELECT TOP(23)pm.Pocket_Cd,pm.SiteName,KMLFile_Url 
                            FROM Pocket_Master as pm
                            INNER JOIN Election_Master as elm  on (pm.ElectionName = elm.ElectionName) 
                            $ElectionKMLCon  $SiteCon ";
        $PocketKmlData = $db->ExecutveQueryMultipleRowSALData($PocketKMLQuery , $userName, $appName, $developmentMode);

        //END KML For site //
       $sitedropdownquery = "SELECT COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                            COALESCE(sm.ClientName,'') AS ClientName, 
                            COALESCE(sm.SiteName,'') AS SiteName, 
                            COALESCE(sm.ElectionName,'') AS ElectionName
                            FROM Site_Master as sm
                            inner join Society_Master as soc
                            ON sm.SiteName = soc.SiteName
                            INNER JOIN Election_Master as elm 
                            on (sm.ElectionName = elm.ElectionName)
                            $ElectionSiteDropKMLCon 
                            GROUP BY sm.Site_Cd,sm.ClientName,sm.SiteName,sm.ElectionName";
        
        $dataSite = $db->ExecutveQueryMultipleRowSALData($sitedropdownquery, $userName, $appName, $developmentMode);
        $MapMarkerColorIcons = array("https://maps.google.com/mapfiles/ms/icons/orange-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/yellow-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/pink-dot.png",
        "https://maps.google.com/mapfiles/ms/icons/purple-dot.png",
        "http://maps.google.com/mapfiles/ms/icons/ltblue-dot.png");
        $SizeofMapMarkerColors = sizeof($MapMarkerColorIcons);
// print_r("<pre>");
// print_r($MapData);
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
            <select class="select2 form-control"  name="siteName" onchange="setSiteForDashboardInSession(this.value)">
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
            <button class="login-button" id="DetailButton" onclick="getAllDetailSiteData('Detail')">Detail</button>
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
    <div id= "map1" style="height:600px;width:97%;">
    </div>
</body>
<script type = "text/javascript">
   function map(){
        <?php 
        $recordsSize = sizeof($MapData);
        $centerLat = 19.0016;
        $centerLng = 73.1202;

        if(sizeof($MapData)>0){
            if(!empty($MapData[0]["Latitude"]) && $MapData[0]["Longitude"] != '0'){
            $centerLat  = $MapData[0]["Latitude"];
            $centerLng  = $MapData[0]["Longitude"];
            }
        }

    ?>
        var marker;
        var markers = [];
        var infowindow = new google.maps.InfoWindow();

        var map = new google.maps.Map(document.getElementById('map1'),{
            center: { lat: <?php echo $centerLat; ?>, lng: <?php echo $centerLng; ?> }, 
            // center :{lat : 19.0016, lng : 73.1202},
            zoom : 12,
            mapTypeId: 'satellite'

        });
        <?php
        if($SiteName == 'All'){
            ?>
            function addMarkerWithTimeout(lat, lng,ClientName, SiteName,SocietyCount,image) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    icon: image,
                    ClientName: ClientName,
                    SiteName: SiteName,
                    SocietyCount: SocietyCount
                });

                google.maps.event.addListener(marker, "click", () => {
                    // var infowindow = new google.maps.InfoWindow();
                    // infowindow.setContent(`<table> <tr><th rowspan=7><img src=`+Building_Image+` height="90px"></th><th colspan = 3>` + Society_Cd + `</th></tr><tr><th colspan = 3>` + SocietyName + `</th></tr> <tr><th colspan = 3>` + SocietyNameMar + `</th></tr><tr><th colspan = 3>Survye By :` + SurveyBy + `</th></tr><tr><th colspan = 3> Survey Date:` + SurveyDate + `</th></tr> <tr><th>Voters:`+ Vot +`</th><th colspan = 2>NonVoters:`+NVot+`</ty> <tr><th> Karyakarta:` + Karyakarta + `</th><th>Hitchintak ` + Hitchintak + `</th><th> Adharghatak:` + Aadharghatak + `</th></tr></table>`);
                    infowindow.setContent(`<table> <tr></th><th>` + ClientName + `</th></tr><tr><th>Site Name:` + SiteName + `</th></tr><tr><th>Society Count:` + SocietyCount + `</th></tr></table>`);
                    infowindow.open(map, marker);
                });
                
        }

        <?php
        }
else{?>
        function addMarkerWithTimeout(lat, lng, SocietyName,Building_Image,SurveyBy,SurveyDate,Client,image) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    icon: image,
                    SocietyName: SocietyName,
                    Building_Image: Building_Image,
                    SurveyBy: SurveyBy,
                    SurveyDate: SurveyDate,
                    Client:Client
                });

                google.maps.event.addListener(marker, "click", () => {
                    // var infowindow = new google.maps.InfoWindow();
                    // infowindow.setContent(`<table> <tr><th rowspan=7><img src=`+Building_Image+` height="90px"></th><th colspan = 3>` + Society_Cd + `</th></tr><tr><th colspan = 3>` + SocietyName + `</th></tr> <tr><th colspan = 3>` + SocietyNameMar + `</th></tr><tr><th colspan = 3>Survye By :` + SurveyBy + `</th></tr><tr><th colspan = 3> Survey Date:` + SurveyDate + `</th></tr> <tr><th>Voters:`+ Vot +`</th><th colspan = 2>NonVoters:`+NVot+`</ty> <tr><th> Karyakarta:` + Karyakarta + `</th><th>Hitchintak ` + Hitchintak + `</th><th> Adharghatak:` + Aadharghatak + `</th></tr></table>`);
                    infowindow.setContent(`<table> <tr><th rowspan=4><img src=`+Building_Image+` height="90px"></th><th>`+Client+`</tr><tr><th>` + SocietyName + `</th></tr><tr><th>` + SurveyBy + `</th></tr><tr><th>` + SurveyDate + `</th></tr></table>`);
                    infowindow.open(map, marker);
                });
                
        }
        
        <?php 
        }
if($SiteName == 'All'){
    $SsrNo=0;
    $Site = '';
    $SecondSiteName = '';
    foreach ($SiteMData as $key => $val){

        $Site = $val['SiteName'];
        if($Site == $SecondSiteName){
            if($SsrNo == 0){
                $SsrNo = $SizeofMapMarkerColors-1;
            }else{
                $SsrNo = $SsrNo-1;
            }
    }

    if($SsrNo == $SizeofMapMarkerColors){
            $SsrNo = 0;
    }
            $MarkerQuery = "  SELECT Society_Cd,ElectionName,Building_Image,SocietyName,SiteName,UpdatedByUser As SurveyBy,CONVERT(varchar,SurveyDate,103) AS SurveyDate,Longitude,Latitude 
            FROM Society_Master  WHERE SiteName = '$Site' 
            order by SiteName";
            $MData = $db->ExecutveQueryMultipleRowSALData($MarkerQuery , $userName, $appName, $developmentMode);
        ?>
            addMarkerWithTimeout('<?php echo $MData[0]["Latitude"]; ?>', '<?php echo $MData[0]["Longitude"]; ?>','<?php echo $val["ClientName"]; ?>','<?php echo $val["SiteName"]; ?>','<?php echo $val["Societies"]; ?>','<?php echo $MapMarkerColorIcons[$SsrNo]; ?>');
        <?php
        $SsrNo = $SsrNo+1;
        $SecondSiteName = $Site;
        } 
        }else{
                $MapsrNo = 0;
                $MapSite = '';
                $SecondMapSite = '';
                foreach ($MapData as $key => $value){
                    $MapSite = $value['SiteName'];
                    if($MapSite == $SecondMapSite){
                        if($MapsrNo == 0){
                            $MapsrNo = $SizeofMapMarkerColors-1;
                        }else{
                            $MapsrNo = $MapsrNo-1;
                        }
                }
            
                if($MapsrNo == $SizeofMapMarkerColors){
                        $MapsrNo = 0;
                }
                if($value["Latitude"] != '0' && $value["Latitude"] != 'null' && $value["Latitude"] != ''){

            ?>
                addMarkerWithTimeout('<?php echo $value["Latitude"]; ?>', '<?php echo $value["Longitude"]; ?>','<?php echo $value["SocietyName"]; ?>','<?php echo $value["Building_Image"]; ?>','<?php if(!empty($value["SurveyBy"])){ echo "Survey By: ".$value["SurveyBy"];}else{ echo "Listing By: ".$value["ListedBy"];} ?>','<?php  echo "Date: ". substr($value["ListingDate"], 0 , 16); ?>', '<?php echo $value["ClientName"]; ?>','<?php if(!empty($value["RoomSurveyDone"])){ echo "https://maps.google.com/mapfiles/ms/icons/green-dot.png";}else{echo $MapMarkerColorIcons[$MapsrNo];} ?>');
            <?php
                    $MapsrNo = $MapsrNo+1;
                    $SecondMapSite = $MapSite;
                }
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
                    if($ULB == 'BMC'){
                        if($SiteName == 'KL160'){
                            $site = 'KL_160';
                        }else if($SiteName == 'SSK117'){
                            $site = 'SSK_117';
                        }else if($SiteName == 'UP118'){
                            $site = 'UP_118';
                        }else{
                            $site =$SiteName;
                        }
                        ?>
                        addKMLMarkerWithTimeoutP('http://103.14.99.154/SurveyUtilityAppApi/upload/PocketKml/<?php echo $site; ?>.kml','1000');
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
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=map"  ></script>
<!-- Live Link -->
</html>
