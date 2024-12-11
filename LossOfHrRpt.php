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

$qry = "SELECT ElectionName,DBName FROM Election_Master WHERE ElectionName = '$electionName'";
$EleWiseDb = $db->ExecutveQuerySingleRowSALData($qry, $userName, $appName, $developmentMode);
$DBName = $EleWiseDb['DBName'];

if(isset($_SESSION['SurveyUA_LossOfHour_Date']) && !empty($_SESSION['SurveyUA_LossOfHour_Date'])){
    $date = $_SESSION['SurveyUA_LossOfHour_Date'];
}else{
    $date = date('Y-m-d');
}
if(isset($_SESSION['SurveyUA_LossOfHour_SocietyName']) && !empty($_SESSION['SurveyUA_LossOfHour_SocietyName'])){
    $SocietyName = $_SESSION['SurveyUA_LossOfHour_SocietyName'];
    $SocietyCond = "AND SocietyName = '$SocietyName' ";
}else{
    $SocietyName = "";
    $SocietyCond = "";
}
if(isset($_SESSION['SurveyUA_LossOfHour_User']) && !empty($_SESSION['SurveyUA_LossOfHour_User'])){
    $UserNm = $_SESSION['SurveyUA_LossOfHour_User'];
        $UserCon ="AND UpdateByUser = '$UserNm'";
    
}else{
    $UserNm = "";
    $UserCon = "";
}
$Name = preg_replace('/\d+/', '', $UserNm);
// echo  $UserNm;
$userQuery = "SELECT DISTINCT(em.ExecutiveName) as ExecutiveName,tb.UpdateByUser FROM 
            (
                SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..Dw_VotersInfo 
                UNION
                SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..NewVoterRegistration 
                UNION
                SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..LockRoom  
            ) as tb
            LEFT JOIN [$ServerIP].Survey_Entry_Data.dbo.User_Master as um on (tb.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI)
            LEFT JOIN [$ServerIP].Survey_Entry_Data.dbo.Executive_Master as em on (um.Executive_Cd = em.Executive_Cd)
            WHERE tb.UpdatedDate IS NOT NULL AND CONVERT(varchar, tb.UpdatedDate, 23) = '$date' $SocietyCond";
$UserData = $db->ExecutveQueryMultipleRowSALData($userQuery, $userName, $appName, $developmentMode);

$SocietyQuery = "SELECT DISTINCT(SocietyName) as SocietyName  FROM 
                (
                    SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..Dw_VotersInfo 
                    UNION
                    SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..NewVoterRegistration 
                    UNION
                    SELECT UpdateByUser,UpdatedDate,SocietyName FROM [$DBName]..LockRoom  
                ) as tb
                WHERE UpdatedDate IS NOT NULL AND CONVERT(varchar, UpdatedDate, 23) = '$date'";
$SocietyData = $db->ExecutveQueryMultipleRowSALData($SocietyQuery, $userName, $appName, $developmentMode);
// print_r($SocietyData);
 if(!empty($UserNm)){
   $Query = "SELECT * FROM 
        (SELECT * ,Lag(CONVERT(varchar,UpdatedDate,25), 1) OVER(ORDER BY UpdatedDate ASC) AS EndDate,
        ABS(DATEDIFF(MINUTE, UpdatedDate, Lag(UpdatedDate, 1) 
                OVER(ORDER BY UpdatedDate ASC))) AS MinuteDiff
        FROM 
        (SELECT tb1.SocietyName,tb1.RoomNo,tb1.UpdateByUser,MAX(UpdatedDate) as UpdatedDate
                FROM
                    (SELECT SocietyName,RoomNo, UpdateByUser, CONVERT(varchar,UpdatedDate,25) as UpdatedDate FROM [$DBName]..Dw_VotersInfo 
                        WHERE UpdatedDate IS NOT NULL AND CONVERT(varchar, UpdatedDate, 23) = '$date' $SocietyCond $UserCon
                        UNION
                        SELECT SocietyName,Roomno AS RoomNo, UpdateByUser, CONVERT(varchar,UpdatedDate,25) as UpdatedDate FROM [$DBName]..NewVoterRegistration 
                        WHERE UpdatedDate IS NOT NULL AND CONVERT(varchar, UpdatedDate, 23) = '$date' $SocietyCond $UserCon
                        UNION
                        SELECT SocietyName,RoomNo, UpdateByUser, CONVERT(varchar,UpdatedDate,25) as UpdatedDate FROM [$DBName]..LockRoom  
                        WHERE UpdatedDate IS NOT NULL AND CONVERT(varchar, UpdatedDate, 23) = '$date' $SocietyCond $UserCon
                    ) as tb1 
                    GROUP BY tb1.SocietyName,tb1.RoomNo,tb1.UpdateByUser
                    ) as tb
                    ) as t
                    WHERE t.MinuteDiff > 10
                    ORDER BY EndDate";
$LossOfHourData = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);
 }else{

     $LossOfHourData = array();
 }
// print_r("<pre>");
// print_r($LossOfHourData);
// print_r("</pre>");

?>
<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <?php include 'dropdown-electionname.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="date" value="<?php echo $date; ?>"  class="form-control" placeholder="Date" onchange="setDate(this.value)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Society</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="SocietyName" onchange="setSocietyInSession(this.value)" >
                                        <option value="">--SELECT--</option>
                                                <?php
                                            if (sizeof($SocietyData)>0) 
                                            {
                                                foreach ($SocietyData as $key => $value) 
                                                    {
                                                        if($SocietyName == $value["SocietyName"])
                                                        {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['SocietyName']; ?>"><?php echo $value["SocietyName"]; ?></option>
                                            <?php
                                                        }
                                                        else 
                                                        {
                                            ?>
                                                        <option value="<?php echo $value["SocietyName"];?>"><?php echo $value["SocietyName"];?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <div class="form-group">
                                    <label>Executive</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="UserName" onchange="setUserInSession(this.value)" >
                                        <option value="">--SELECT--</option>
                                                <?php
                                            if (sizeof($UserData)>0) 
                                            {
                                                foreach ($UserData as $key => $value) 
                                                    {
                                                        if($UserNm == $value["UpdateByUser"])
                                                        {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['UpdateByUser']; ?>"><?php echo $value["ExecutiveName"]; ?></option>
                                            <?php
                                                        }
                                                        else 
                                                        {
                                            ?>
                                                        <option value="<?php echo $value["UpdateByUser"];?>"><?php echo $value["ExecutiveName"];?></option>
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?> 
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-header">
                <h4 class="card-title" style="padding:5px;margin-left:10px;"><?php echo $Name." -Loss Of Hour Report"  ?></h4>
            </div>
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover-animation table-striped table-hover" id="lossOfHourTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">Sr No</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">SocietyName</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">Room No</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">Executive</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">EndDate</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">MinuteDiff</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(sizeof($LossOfHourData) > 0 ){
                                            $srNo = 1;
                                            foreach ($LossOfHourData as $key => $value) {
                                            ?> 
                                                <tr style="padding-top:0px;">
                                                    <td><?php echo $srNo++; ?></td>
                                                    <td class="text-center"><?php echo $value["SocietyName"]; ?></td>
                                                    <td class="text-center"><?php echo $value["RoomNo"]; ?></td>
                                                    <td class="text-center"><?php echo $value["UpdateByUser"]; ?></td>
                                                    <td class="text-center"><?php echo $value["EndDate"]; ?></td>
                                                    <td class="text-center"><?php echo $value["MinuteDiff"]; ?></td>
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
    </div>
</div>
<script>
      function setSocietyInSession(Society) {
    // alert(date);
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
            }
        }
    
    if (Society === '') {
        alert("Please Select User !");
    } else {
        var queryString = "?SocietyName="+Society;
        ajaxRequest.open("POST", "setSocietyForLossOfHourReport.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function setUserInSession(User) {
    // alert(date);
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
            }
        }
    
    if (User === '') {
        alert("Please Select User !");
    } else {
        var queryString = "?User="+User;
        ajaxRequest.open("POST", "setUserForLossOfHourReport.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
</script>