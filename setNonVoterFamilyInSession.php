<?php

    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        if(
            (isset($_GET['FamilyNo']) && !empty($_GET['FamilyNo'])) &&
            (isset($_GET['Ac_No']) && !empty($_GET['Ac_No'])) &&
            (isset($_GET['Voter_Cd']) && !empty($_GET['Voter_Cd'])) 
        ){
             
            try  
            {  
             
                $FamilyNo = $_GET['FamilyNo'];
                $Ac_No = $_GET['Ac_No'];
                $Voter_Cd = $_GET['Voter_Cd'];
                $DbName = $_GET['DbName'];


                $_SESSION['SurveyUA_FamilyNo_Details'] = $FamilyNo;
                $_SESSION['SurveyUA_AcNo_Details'] = $Ac_No;
                $_SESSION['SurveyUA_VoterCd_Details'] = $Voter_Cd;
                $_SESSION['SurveyUA_DbName_Details'] = $DbName;


                // include 'pages/Non-Voters-with-Society.php';

            } 
            catch(Exception $e)  
            {  
                echo("Error!");  
            }
                                                              

        }else{
            //echo "ddd";

        }

    }
    if(
        (isset($_SESSION['SurveyUA_FamilyNo_Details']) && !empty($_SESSION['SurveyUA_FamilyNo_Details'])) &&
        (isset($_SESSION['SurveyUA_AcNo_Details']) && !empty($_SESSION['SurveyUA_AcNo_Details'])) &&
        (isset($_SESSION['SurveyUA_VoterCd_Details']) && !empty($_SESSION['SurveyUA_VoterCd_Details']))
    ){


        $FamilyNo = $_SESSION['SurveyUA_FamilyNo_Details'];
        $Ac_No = $_SESSION['SurveyUA_AcNo_Details'];
        $Voter_Cd = $_SESSION['SurveyUA_VoterCd_Details'];
        $DbName = $_SESSION['SurveyUA_DbName_Details'];

        $FamilyTblConditionVariable = 1;

        

        unset($_SESSION['SurveyUA_FamilyNo_Details']);
        unset($_SESSION['SurveyUA_AcNo_Details']);
        // unset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']);
    }else{
        $FamilyTblConditionVariable = 0;
    }
    if($FamilyTblConditionVariable == 1){
        $query2 = "SELECT 
            COALESCE(Voter_Cd, 0) AS Voter_Cd, 
            COALESCE(Ac_No, 0) AS Ac_No, 
            COALESCE(Ward_No, 0) AS Ward_No, 
            COALESCE(List_No, 0) AS List_No, 
            COALESCE(Voter_Id, 0) AS Voter_Id, 
            COALESCE(FamilyNo, 0) AS FamilyNo, 
            COALESCE(SubLocation_Cd, 0) AS SubLocation_Cd, 
            COALESCE(SocietyName, '') AS SocietyName, 
            COALESCE(FullName, '') AS FullName, 
            COALESCE(RoomNo, '') AS RoomNo, 
            COALESCE(Sex, '') AS Sex, 
            COALESCE(Age, 0) AS Age, 
            COALESCE(MarNmar, '') AS MarNmar, 
            COALESCE(MarNmar_Det, '') AS MarNmar_Det, 
            COALESCE(SF, 0) AS SF, 
            COALESCE(MobileNo, '') AS MobileNo,  
            COALESCE(BirthDate, '') AS BirthDate, 
            COALESCE(Livingyear , '') AS Livingyear, 
            COALESCE(VidhanSabha, 0) AS VidhanSabha, 
            COALESCE(Occupation, '') AS Occupation, 
            COALESCE(Education, '') AS Education, 
            COALESCE(HStatus, '') AS HStatus, 
            COALESCE(SStatus, '') AS SStatus, 
            COALESCE(MajorIssues, '') AS MajorIssues, 
            COALESCE(OwnerName, '') AS OwnerName, 
            COALESCE(Remark, '') AS Remark, 
            COALESCE(LPNO, 0) AS LPNO, 
            COALESCE(Religion, '') AS Religion, 
            COALESCE(SubCaste, '') AS SubCaste, 
            COALESCE(LockedButSurvey, '') AS LockedButSurvey, 
            COALESCE(OwnerMobileNo, '') AS OwnerMobileNo, 
            COALESCE(District, '') AS District, 
            COALESCE(AnniversaryDate, ' ') AS AnniversaryDate 
            From $DbName..Dw_VotersInfo 
            where FamilyNo = $FamilyNo and Ac_No= $Ac_No
            ORDER BY Age DESC";

        // echo $query1;exit;
        $NonVoterFamilyList = $db->ExecutveQueryMultipleRowSALData($query2, $userName, $appName, $developmentMode);
    }else{
        $NonVoterFamilyList = []; 
    }
    if($FamilyTblConditionVariable == 1){
?>
<div class="row">
    <div class="col-md-12" style="align-items:center">
        <center>
            <div id='spinnerLoader3' style='display:none'>
                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
            </div>
        </center>
    </div>
</div>
<div class="row mt-1 p-1">
    <div class="col-md-12">
        <h5>Record with family members:</h5>
    </div>
    <div class="col-md-12">
        <div class="tblCustomHeight" id="tblCustomHeight" style="position: relative;text-align: center;align-items: center;height: 100%;">
            <table class="table table-hover" id="tblCustomCss">
                <thead>
                    <tr class="">
                        <th style=" width:30px;padding:5px 15px 15px 15px">&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input checkbox_All" type="checkbox" style=" width: 20px; height: 20px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setSurveyQCFamilyALLIds(this)"  >
                        </th>
                        <th >Sr No</th>
                        <th >SF</th>
                        <th >Voter ID</th>
                        <th >Full Name</th>
                        <th >Age</th>
                        <th >Sex</th>
                        <th >Birthdate</th>
                        <th >Mobile No</th>
                        <th >Room</th>
                        <th >Hstatus</th>
                        <th >Voted</th>
                        <th >Remark</th>
                        <th >SocietyName</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(sizeof($NonVoterFamilyList) > 0){
                        $srNo = 1;
                        foreach($NonVoterFamilyList AS $Key=>$value){  
                        ?>
                        <tr <?php if($Voter_Cd == $value['Voter_Cd']){ ?> style="color:#36abb9" <?php } ?> >
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input checkbox" type="checkbox" style="width:30px;padding:5px 15px 15px 15px" value="<?php echo $value['Voter_Cd']?>,<?php echo $value['FullName']?>" id="AssignCheckbox" onclick="setSurveyQCNonVoterFamilyIds()" <?php if($value['SF'] == '1'){ ?> disabled <?php }?>>
                            </td>
                            <td><?php echo $srNo++; ?></td>
                            <td><?php echo $value['SF'];?></td>
                            <td><?php echo $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id']; ?></td>
                            <td  style="width: 200px;word-wrap: break-word;" ><?php echo $value['FullName'];?></td>
                            <td><?php echo $value['Age'];?></td>
                            <td><?php echo $value['Sex']; ?></td>
                            <td><?php echo substr($value['BirthDate'], 0, 10);?></td>
                            <td><?php echo $value['MobileNo'];?></td>
                            <td><?php echo $value['RoomNo'];?></td>
                            <td title="<?php if($value['HStatus'] == 'O'){echo 'Owner';}elseif($value['HStatus'] == 'R'){echo 'Rented';}else{echo $value['HStatus'];} ?>" ><?php echo $value['HStatus']; ?></td>
                            <td><?php if($value['VidhanSabha'] == 1){echo "Yes";}elseif($value['VidhanSabha'] == 0){echo "No";}else{ echo $value['VidhanSabha']; }?></td>
                            <td style="width: 100px;word-wrap: break-word;" ><?php echo $value['Remark']?></td>
                            <td  style="width:250px;word-wrap:break-word;" ><?php echo $value['SocietyName'];?></td>
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
<?php 

    }
?>