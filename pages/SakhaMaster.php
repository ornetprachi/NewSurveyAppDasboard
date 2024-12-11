<div id = "SakhaMap">
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


        // if($ServerIP == "103.14.97.228"){
        //     $ServerIP =".";
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
            $MapQuery = "SELECT 
                COALESCE(sd.Shakha_No,0) AS Shakha_No ,
                COALESCE(Longitude,0) AS Longitude,
                COALESCE(Lattitude,0) AS Lattitude,
                COALESCE(Address,'NILL') AS Address,
                COALESCE(Photo_1,'NILL') AS Photo_1,
                COALESCE(vw.Vidhansabha_Details,'NILL') AS Vidhansabha_Details,
                COALESCE(vw.UpvibhagPranukh,'NILL') AS UpvibhagPranukh,
                COALESCE(vw.Shakhapramukh,'NILL') AS Shakhapramukh,
                COALESCE(vw.Vibhag_No,0) AS Vibhag_No,
                COALESCE(vw.Vibhagpramukh,'NILL') AS Vibhagpramukh
                FROM [103.14.97.58].BMC_Details.dbo.KDMC_Shakha_Details as sd
                INNER JOIN [103.14.97.58].BMC_Details.dbo.VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
                WHERE vw.Vibhag_No IN ('138','142','143','144')
                ORDER BY vw.Vibhag_No;";
        }else{
        $MapQuery = "SELECT 
                COALESCE(sd.Shakha_No,0) AS Shakha_No ,
                COALESCE(Longitude,0) AS Longitude,
                COALESCE(Lattitude,0) AS Lattitude,
                COALESCE(Address,'NILL') AS Address,
                COALESCE(Photo_1,'NILL') AS Photo_1,
                COALESCE(vw.Vidhansabha_Details,'NILL') AS Vidhansabha_Details,
                COALESCE(vw.UpvibhagPranukh,'NILL') AS UpvibhagPranukh,
                COALESCE(vw.Shakhapramukh,'NILL') AS Shakhapramukh,
                COALESCE(vw.Vibhag_No,0) AS Vibhag_No,
                COALESCE(vw.Vibhagpramukh,'NILL') AS Vibhagpramukh
                FROM BMC_Details..Mumbai_Shakha_Details as sd
                INNER JOIN BMC_Details..VibhagWiseWard as vw on (sd.Shakha_No = vw.Shakha_No)
                $vibhagCon
                ORDER BY vw.Vibhag_No;";
        }
        $MapData = $db->ExecutveQueryMultipleRowSALData($MapQuery , $userName, $appName, $developmentMode);
    //    print_r("<pre>");
    //    print_r($MapData);
    //    print_r("</pre>");
       

        $MapMarkerColorIcons = array(
        "https://maps.google.com/mapfiles/ms/icons/orange-dot.png",
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

    
<?php 
$FlagType ='';
    if(isset($_GET['Url']) && !empty($_GET['Url'])){
        $FlagUrl = $_GET['Url'];
    }else{
        $FlagUrl = "";
    }
    if(isset($_SESSION['Survey_Utility_MarkerType']) && !empty($_SESSION['Survey_Utility_MarkerType'])){
        $FlagType = $_SESSION['Survey_Utility_MarkerType'];
    }else{
        $FlagType = "HideFlag";
    }
    // echo $FlagType;
    ?>
<body>
    <div class = "row">
        <button class = 'btn btn-outline-info mr-1 mb-1 waves-effect waves-light' style="margin-left:15px;" id="redrawButton" 
        onclick="ShowFlagMap('<?php echo $FlagType; ?>')"><?php if($FlagType == "HideFlag"){echo "Show Flag ";}else{ echo "Hide Flag";} ?><i class="fa fa-flag-o" style="color:red"></i></button>
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
            if(!empty($MapData[0]["Lattitude"]) && $MapData[0]["Longitude"] != '0'){
            $centerLat  = $MapData[0]["Lattitude"];
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
      
        function addMarkerWithTimeout(lat, lng, Shakha_No,Photo_1,Vidhansabha_Details,Vibhag_No,Vibhagpramukh,UpvibhagPranukh,Shakhapramukh,image) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    icon: image,
                    Shakha_No: Shakha_No,
                    Photo_1: Photo_1,
                    Vidhansabha_Details: Vidhansabha_Details,
                    Vibhag_No: Vibhag_No,
                    UpvibhagPranukh: UpvibhagPranukh,
                    Shakhapramukh: Shakhapramukh,
                    Vibhagpramukh: Vibhagpramukh
                });

                google.maps.event.addListener(marker, "click", () => {
                    infowindow.setContent(`<table> <tr style='background-color:#F58018;color:white;'><th colspan=2><span style="margin-left:150px;">शाखा क्र.-`+Shakha_No+`</span></th></tr><tr><th rowspan=4><img src=`+Photo_1+` height="90px" onClick="getPopUpModaltoViewImg('`+Photo_1+`')"></th><th>विभाग नं.:`+Vibhag_No+`&nbsp;&nbsp;विभागप्रमुख: `+Vibhagpramukh+`</tr><tr><th>विभागसभा :` + Vidhansabha_Details + `</th></tr><tr><th>उपविभागप्रमुख :`+UpvibhagPranukh+`</th>
                    </tr><tr><th>शाखाप्रमुख :`+Shakhapramukh+`</th></tr></table>`);
                    infowindow.open(map, marker);
                });
                
        }
        
        <?php 
                $MapsrNo = 0;
                $MapSite = '';
                $SecondMapSite = '';
                foreach ($MapData as $key => $value){
                    $MapSite = $value['Vibhag_No'];
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
                if($value["Lattitude"] != '0' && $value["Lattitude"] != 'null' && $value["Lattitude"] != ''){
            if($FlagType == 'ShowFlag'){?>
                addMarkerWithTimeout('<?php echo $value["Lattitude"]; ?>', '<?php echo $value["Longitude"]; ?>','<?php echo $value["Shakha_No"]; ?>','<?php echo $value["Photo_1"]; ?>','<?php  echo $value["Vidhansabha_Details"]; ?>','<?php echo $value['Vibhag_No']; ?>','<?php echo $value['Vibhagpramukh']; ?>','<?php echo $value['UpvibhagPranukh']; ?>','<?php echo $value['Shakhapramukh']; ?>','http://103.14.99.154/SurveyUtilityDashboardNew/uploads/ShivsenaFlag.svg');
          <?php  }else{
            ?>
                addMarkerWithTimeout('<?php echo $value["Lattitude"]; ?>', '<?php echo $value["Longitude"]; ?>','<?php echo $value["Shakha_No"]; ?>','<?php echo $value["Photo_1"]; ?>','<?php  echo $value["Vidhansabha_Details"]; ?>','<?php echo $value['Vibhag_No']; ?>','<?php echo $value['Vibhagpramukh']; ?>','<?php echo $value['UpvibhagPranukh']; ?>','<?php echo $value['Shakhapramukh']; ?>','<?php echo $MapMarkerColorIcons[$MapsrNo]; ?>');
            <?php
          }
                    $MapsrNo = $MapsrNo+1;
                    $SecondMapSite = $MapSite;
                }
            }   
                  
    ?>
        // var redrawButton = document.getElementById('redrawButton');
        //     redrawButton.addEventListener('click', function () {
        //         // Modify marker data for the redraw
        //         <?php 
        //         foreach ($MapData as $key => $value){
        //         ?>
        //         addMarkerWithTimeout('<?php echo $value["Lattitude"]; ?>', '<?php echo $value["Longitude"]; ?>','<?php echo $value["Shakha_No"]; ?>','<?php echo $value["Photo_1"]; ?>','<?php  echo $value["Vidhansabha_Details"]; ?>','<?php echo $value['Vibhag_No']; ?>','<?php echo $value['Vibhagpramukh']; ?>','<?php echo $value['UpvibhagPranukh']; ?>','<?php echo $value['Shakhapramukh']; ?>','http://103.14.99.154/SurveyUtilityDashboardNew/uploads/ShivsenaFlag.svg');
        //     <?php //} ?>
        //     });

     
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
           <?php  if($ULB == 'KDMC_2023'){ ?>
                addKMLMarkerWithTimeout('http://92.204.137.146/KDMCTreeCensus/uploads/KDMC_wardboundries.kml', '1000');
          <?php  } else {?>
                addKMLMarkerWithTimeout('http://103.14.99.154/SurveyUtilityAppApi/upload/Prabhag_Boundary_Final.kml', '1000');
            <?php } ?>
            // var kmlUrl = "http://103.14.99.154/SurveyUtilityAppApi/upload/Prabhag_Boundary_Final.kml"; // Replace with the path to your KML file
            // var kmlLayer = new google.maps.KmlLayer({
            //     url: kmlUrl,
            //     map: map,
            // });
    }

</script>
<!-- Local Link -->
<!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=map" async defer ></script> -->
<!-- Local Link -->

<!-- Live Link -->
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=map"  ></script>
<!-- Live Link -->
<center>
    <div id="ImageViewPopUp">
        
    </div>
</center>
</div>
</html>
<script>
    function ShowFlagMap(FlagType){
        var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4){
        location.reload(true);      
        
        }
    }
if(FlagType == 'HideFlag'){
    var Flag = 'ShowFlag';
}else{
    Flag = 'HideFlag';
}
    
        var queryString = "?MarkerType="+Flag;
        ajaxRequest.open("POST", "setMarkerforMap.php" + queryString, true);
        ajaxRequest.send(null); 
    }
</script>