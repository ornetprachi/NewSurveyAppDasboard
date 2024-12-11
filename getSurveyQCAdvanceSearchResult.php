<?php
session_start();
include 'api/includes/DbOperation.php'; 

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

$tbodyString = "";
$cond1 = "";

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {

    if( 
        (isset($_POST['DBName']) && !empty($_POST['DBName']))
    ){
        // && (isset($_POST['Ac_No']) && !empty($_POST['Ac_No']))
        $fullName = "";
        $FirstName = "";
        $MiddleName = "";
        $LastName = "";
        $IdCard_No = "";
        $List_No = "";
        $fuzzThreshold = 80;
        $Ac_No = 0;

        $Ac_No = $_POST['Ac_No'];
        $DBName = $_POST['DBName'];

        if(isset($_POST['fullName']) && !empty($_POST['fullName'])){
            $fullName =  $_POST['fullName']; 
        }else{
            $fullName = "";
        }

        if(isset($_POST['FirstName']) && !empty($_POST['FirstName'])){
            $FirstName =  $_POST['FirstName'];
            $FirstNameCond =  " AND Name LIKE '$FirstName%' "; 
        }else{
            $FirstNameCond = "";
        }

        if(isset($_POST['MiddleName']) && !empty($_POST['MiddleName'])){
            $MiddleName =  $_POST['MiddleName'];
            $MiddleNameCond =  " AND MiddleName LIKE '$MiddleName%' ";
        }else{
            $MiddleNameCond = "";
        }

        if(isset($_POST['LastName']) && !empty($_POST['LastName'])){
            $LastName =  $_POST['LastName'];
            $LastNameCond =  " AND Surname LIKE '$LastName%' ";
        }else{
            $LastNameCond = "";
        }

        if(isset($_POST['IdCard_No']) && !empty($_POST['IdCard_No'])){
            $IdCard_No =  $_POST['IdCard_No'];
            $IdCard_NoCond =  " AND IdCard_No LIKE '$IdCard_No%' ";
        }else{
            $IdCard_NoCond = "";
        }

        if(isset($_POST['fuzzThreshold']) && !empty($_POST['fuzzThreshold'])){
            $fuzzThreshold =  $_POST['fuzzThreshold'];
            // $List_NoCond =  " AND List_No LIKE '%$List_No%' ";
        }else{
            $fuzzThreshold = 80;
        }

        if($fullName != ""){
            if ($fullName == trim($fullName) && strpos($fullName, ' ') !== false) {
                
                $strArr = explode(" ", $fullName);
                foreach($strArr as $value){
                    $FullNameCond1 .= " FullName like '$value%' and ";
                }
    
            }else{
                $FullNameCond1 = " FullName like '$fullName%' and ";
            }
            $FullNameCond = substr($FullNameCond1, 0, -4);
        }else{
            $FullNameCond = "";
        }


        $ConditionString = $FullNameCond . $FirstNameCond . $MiddleNameCond . $LastNameCond . $IdCard_NoCond . $List_NoCond ."AND QC_Done <> 1";

        $pattern = '/^AND\s+/i';

        $modifiedString = preg_replace($pattern, '', trim($ConditionString), 1);

        if(empty(trim($modifiedString))){
            $where = "";
        }else{
            $where = " WHERE ";
        }

      

         $query1 = "SELECT top 20
            COALESCE(SF, 0) AS SF,
            COALESCE(Voter_Cd, 0) AS Voter_Cd,
            COALESCE(FamilyNo, 00) AS FamilyNo,
            COALESCE(Ac_No, 0) AS Ac_No,
            COALESCE(List_No, 0) AS List_No,
            COALESCE(Voter_Id, 0) AS Voter_Id,
            COALESCE(NewVoter_Id, 0) AS NewVoter_Id,
            COALESCE(FullName, '') AS FullName,
            COALESCE(FullNameMar, '') AS FullNameMar,
            COALESCE(SocietyName, '') AS SocietyName,
            COALESCE(SocietyNameM, '') AS SocietyNameM,
            COALESCE(Sex, '') AS sex,
            COALESCE(Age, 0) AS Age,
            COALESCE(RoomNo, '') AS RoomNo,
            COALESCE(Mobileno, '') AS MobileNo,
            COALESCE(BirthDate, '') AS DOB,
            COALESCE(Livingyear, '') AS Livingyear,
            COALESCE(HouseStatus_Cd, 0) AS HouseStatus_Cd,
            COALESCE(HStatus, '') AS HStatus,
            COALESCE(ShiftedStatus_Cd, 0) AS ShiftedStatus_Cd,
            COALESCE(SStatus, '') AS SStatus,
            COALESCE(VidhanSabha, 0) AS VidhanSabha,
            COALESCE(Occupation_Cd, 0) AS Occupation_Cd,
            COALESCE(Occupation, '') AS Occupation,
            COALESCE(Education, '') AS Education,
            COALESCE(MajorIssues, '') AS MajorIssues,
            COALESCE(OwnerName, '') AS OwnerName,
            COALESCE(MarNmar_Det, '') AS MarNmar_Det,
            COALESCE(MarNmar, '') AS MarNmar,
            COALESCE(Remark, '') AS Remark,
            COALESCE(Email, '') AS Email,
            COALESCE(Dead, 0) AS Dead
            from $DBName..DW_VotersInfo 
            $where 
            $modifiedString
            ";

        // echo $query1;die();
        $NonVoterSearchList = $db->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);


        // // Searching Nov Voter API Calling ---------------------------------------------->
        //     $postParameters = array(
        //         'memberlist' => 'AllMaharashtraVoters',
        //         'ac_no' => $Ac_No,
        //         'fuzzthreshold' => $fuzzThreshold,
        //         'name' => $FirstName,
        //         'middlename' => $MiddleName,
        //         'surname' => $LastName,
        //         'fullName' => $fullName,
        //         'sex' => '',
        //         'age' => '',
        //         'Idcardno' => $IdCard_No,
        //         'mobileno' => '',
        //         'VoterId' => '',
        //         'DistrictCd' => '',
        //         'fullname' => $fullName
        //     );
            
        //     $url = "http://151.106.39.186:1615/search";
        //     $req = curl_init($url);
        //     curl_setopt($req, CURLOPT_URL, $url);
        //     curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        //     // curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($req, CURLOPT_POST, true);
        //     // $headers = array( "Content-Type: application/x-www-form-urlencoded", );
        //     $headers = array( "Content-Type: multipart/form-data", );
        //     curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
        //     curl_setopt($req, CURLOPT_POSTFIELDS, $postParameters );
        //     $result = curl_exec($req);
        //     curl_close($req);
        //     $NonVoterSearchList = json_decode($result, TRUE);
        //     // print_r($jsonArr);
        // // Searching Nov Voter API Calling ---------------------------------------------->

     
        if(sizeof($NonVoterSearchList) > 0){
            // $srNo = 1;
            foreach($NonVoterSearchList AS $Key=>$value){  
                if($value['VidhanSabha'] == 1){$VidhanSabha = "Yes";}elseif($value['VidhanSabha'] == 0){$VidhanSabha = "No";}else{ $VidhanSabha = $value['VidhanSabha'];}
                if($value['HStatus'] == 'O'){
                    $HStatus =  "title='Owner'";
                }elseif($value['HStatus'] == 'R'){
                    $HStatus =  "title='Rented'";
                }else{
                    $HStatus =  "title='" . $value['HStatus'] . "'";
                }

                $inputDateString = $value['DOB'];
                $outputDateString = date("d/m/Y", strtotime($inputDateString));

                $tbodyString .= "
                <tr>
                    <td>
                        <a onclick='getSurveyQCNonVoterFamilyInSession(" . $value['FamilyNo'] . "," . $value['Ac_No'] . "," . $value['Voter_Cd'] . ")'>
                            <i class='fa fa-pencil-square-o'></i>
                        </a>
                        <a onclick='addFamilyList(". $value['Voter_Cd'] . ")'>
                            <i class='fa fa-plus-square-o'></i>
                        </a>
                    </td>
                    <td style='width: 120px;'>" . $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id'] . "</td>
                    <td style='width: 400px;word-wrap: break-word;'>" . $value['FullName'] . "</td>
                    <td>" . $value['Age'] . "</td>
                    <td>" . $value['sex'] . "</td>
                    <td>" . $outputDateString . "</td>
                    <td>" . $value['MobileNo'] . "</td>
                    <td>" . $value['RoomNo'] . "</td>
                    <td ". $HStatus ." >" . $value['HStatus'] . "</td>
                    <td style='width: 300px;word-wrap: break-word;'>" . $value['SocietyName'] . "</td>   
                </tr>";
            }

            // <td>" . substr($value['BirthDate'], 0, 10) . "</td>
            
            // <td>" . $VidhanSabha . "</td>
            // <td style='width: 100px;word-wrap: break-word;'>" . $value['Remark'] . "</td>
        }else{
            $tbodyString = "<tr><td colspan='14'>No Record Found</td></tr>";
        }

        echo json_encode(array('statusCode' => 200, 'msg' => $tbodyString));
    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
    }
}

?>

