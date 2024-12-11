<?php 
session_start();
include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
ini_set('max_execution_time', 600);
$ElectionList = array();


    $query = "SELECT DISTINCT sm.SiteName as SiteName, em.ElectionName, em.ServerName
    FROM Survey_Entry_Data..Site_Master as sm
    INNER JOIN Survey_Entry_Data..Election_Master as em on (em.ElectionName = sm.ElectionName)
    WHERE sm.Closed = 0
    ORDER BY sm.SiteName";

$ElectionList = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
// $uniqueULBs = array_unique($ElectionList);

    foreach ($ElectionList as $key=>$val) {
        if($val['ServerName'] == '103.14.97.58'){
            $ServerIP =".";
        }else{
            $ServerIP =$val['ServerName'];
        }

    $kmlFiles = array(); 
    $site = $val['SiteName'];
     $query1="SELECT DISTINCT SM.SiteName, KMLFile_Url
        FROM [$ServerIP].Survey_Entry_Data.dbo.Pocket_Master sm
        WHERE SM.SiteName ='$site' AND KMLFile_Url IS NOT NULL AND KMLFile_Url <> ''";
    
    $kmlFiles = $db->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);

    if(sizeof($kmlFiles)>0){
        // $targetPath = 'uploads/PocketKml/';
        $targetPath = '../SurveyUtilityAppApi/upload/PocketKml/';
        $mergedDocument = new DOMDocument('1.0', 'UTF-8');
        $mergedDocument->formatOutput = true;
        
        // Create the KML root element
        $kmlRoot = $mergedDocument->createElementNS('http://www.opengis.net/kml/2.2', 'kml');
        $kmlRoot->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.opengis.net/kml/2.2');
        $mergedDocument->appendChild($kmlRoot);
        
        // Create the Document element
        $documentElement = $mergedDocument->createElement('Document');
        $kmlRoot->appendChild($documentElement);
        
        // Iterate through each KML file
        foreach ($kmlFiles as $kmlFileValues) {
            // $kml = $kmlFileValues['KMLFile_Url'];
            $kmlContent = file_get_contents($kmlFileValues['KMLFile_Url']);
            
            if ($kmlContent !== false) {

            $kmlDom = new DOMDocument();
            $kmlDom->loadXML($kmlContent);
        
            // Find all Placemark elements in the loaded KML
            $placemarks = $kmlDom->getElementsByTagName('Placemark');
            
            // Iterate through each Placemark element and modify/add color property
            foreach ($placemarks as $placemark) {
                $colorValue = 'ff0000'; // Replace with your desired color value
                $styleElement = $kmlDom->createElement('Style');
                $iconStyleElement = $kmlDom->createElement('IconStyle');
                $colorElement = $kmlDom->createElement('color', $colorValue);
                
                $iconStyleElement->appendChild($colorElement);
                $styleElement->appendChild($iconStyleElement);
                $placemark->appendChild($styleElement);
            }
            
            // Import the modified Placemark elements into the merged KML
            foreach ($kmlDom->documentElement->childNodes as $node) {
                $importedNode = $mergedDocument->importNode($node, true);
                $documentElement->appendChild($importedNode);
            }
        } else {
            echo "Failed to retrieve KML content from: " . $kmlFileValues['KMLFile_Url'] . "<br>";
        }
        }
        
        // Save the merged KML to a file
        $mergedFilename = $targetPath . $site . '.kml';
        $urlEncode = urlencode($mergedFilename);
        // Check if the file already exists
        if (file_exists($urlEncode)) {
            // Delete the existing file
            unlink($urlEncode);
        }
        $mergedDocument->save($urlEncode);
        echo "Merged KML saved to $urlEncode";
    }
    
}
 ?>
