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
// echo "here innnnnn";
    if( 
        (isset($_POST['fullName']) && !empty($_POST['fullName']))  &&
        (isset($_POST['DBName']) && !empty($_POST['DBName']))
    ){
        $fullName = $_POST['fullName'];
        $DBName = $_POST['DBName'];

        if(isset($_POST['FirstName']) && !empty($_POST['FirstName'])){
            $FirstName =  $_POST['FirstName'];
            $FirstNameCond =  " AND Name = '$FirstName' "; 
        }else{
            $FirstNameCond = "";
        }

        if(isset($_POST['MiddleName']) && !empty($_POST['MiddleName'])){
            $MiddleName =  $_POST['MiddleName'];
            $MiddleNameCond =  " AND MiddleName = '$MiddleName' ";
        }else{
            $MiddleNameCond = "";
        }

        if(isset($_POST['LastName']) && !empty($_POST['LastName'])){
            $LastName =  $_POST['LastName'];
            $LastNameCond =  " AND Surname = '$LastName' ";
        }else{
            $LastNameCond = "";
        }

        if ($fullName == trim($fullName) && strpos($fullName, ' ') !== false) {
            
            $strArr = explode(" ", $fullName);
            foreach($strArr as $value){
                $cond1 .= " FullName like '%$value%' and ";
            }

        }else{
            $cond1 = " FullName like '%$fullName%' and ";
        }

        $cond1 = substr($cond1, 0, -4);

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
            COALESCE(Sex, '') AS Sex,
            COALESCE(Age, 0) AS Age,
            COALESCE(RoomNo, '') AS RoomNo,
            COALESCE(Mobileno, '') AS Mobileno,
            COALESCE(BirthDate, '') AS BirthDate,
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
            where 
            $cond1
            $FirstNameCond
            $MiddleNameCond
            $LastNameCond
            ";

        // echo $query1;die();
        $NonVoterSearchList = $db->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);

        // echo json_encode($NonVoterSearchList);

        if(sizeof($NonVoterSearchList) > 0){
            // $srNo = 1;
            foreach($NonVoterSearchList AS $Key=>$value){  
                $tbodyString .= "
                <tr>
                    <td>
                        <a onclick='getSurveyQCNonVoterFamilyInSession(" . $value['FamilyNo'] . "," . $value['Ac_No'] . "," . $value['Voter_Cd'] . ")'>
                            <i class='fa fa-pencil-square-o'></i>
                        </a>
                    </td>
                    <td>" . $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id'] . "</td>
                    <td style='width: 200px;word-wrap: break-word;'>" . $value['FullName'] . "</td>
                    <td>" . $value['Age'] . "</td>
                    <td>" . $value['Sex'] . "</td>
                    <td>" . substr($value['BirthDate'], 0, 10) . "</td>
                    <td>" . $value['Mobileno'] . "</td>
                    <td>" . $value['RoomNo'] . "</td>
                    <td style='width: 250px;word-wrap: break-word;'>" . $value['SocietyName'] . "</td>   
                </tr>";
            }
        }else{
            $tbodyString = "<tr><td colspan='11'>No Record Found</td></tr>";
        }

        echo json_encode(array('statusCode' => 200, 'msg' => $tbodyString));
    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
    }
}
?>
