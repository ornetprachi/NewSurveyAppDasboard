<?php 
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

    if(isset($_GET['FamilyNo']) && isset($_GET['DBName']) ){
        $FamilyNo = $_GET['FamilyNo'];
        $DBName = $_GET['DBName'];
    }

    $SiteWiseQuery = "SELECT
                            COALESCE(FullName,'') AS FullName,
                            COALESCE(FullNameMar,'') AS FullNameMar,
                            COALESCE(Age,'') AS Age,
                            COALESCE(Sex,'') AS Sex,
                            COALESCE(BirthDate,'') AS BirthDate,
                            COALESCE(SocietyName,'') AS SocietyName,
                            COALESCE(MobileNo,'') AS MobileNo,
                            COALESCE(Ac_No,0) AS Ac_No,
                            COALESCE(List_No,0) AS List_No,
                            COALESCE(Voter_Id,0) AS Voter_Id,
                            COALESCE(Voter_Cd,0) AS Voter_Cd,
                            COALESCE(Ward_no,0) AS Ward_no
                        FROM
                        Dw_VotersInfo
                        where FamilyNo = $FamilyNo AND SF=1";
    $CountListMain = $db->ExecutveQueryMultipleRowSALData($ULB,$SiteWiseQuery, $userName, $appName, $developmentMode);

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
        background-color: #e6f4f4;
    }
</style>


<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="margin-bottom:-10px">
                <h4 class="card-title">
                    Family Members - <?php echo sizeof($CountListMain);?>
                </h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table zero-configuration  table-hover-animation table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="background-color:#36abb9;color: white;">Sr No</th>
                                    <th style="background-color:#36abb9;color: white;">Full Name</th>
                                    <th style="background-color:#36abb9;color: white;">Birthdate</th>
                                    <th style="background-color:#36abb9;color: white;">Age</th>
                                    <th style="background-color:#36abb9;color: white;">Gender</th>
                                    <th style="background-color:#36abb9;color: white;">Mobile</th>
                                    <th style="background-color:#36abb9;color: white;width:100px">Corp No</th>
                                    <th style="background-color:#36abb9;color: white;">Ward No</th>
                                    <th style="background-color:#36abb9;color: white;">SocietyName</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(sizeof($CountListMain) > 0){
                                        $srNo = 1;
                                        foreach($CountListMain AS $Key=>$value){  
                                        ?>
                                        <tr >
                                            <td><?php echo $srNo++ ;?></td>
                                            <td><?php echo $value['FullName'] . "<br>" . $value['FullNameMar']; ?></td>
                                            <td><?php echo $value['BirthDate']?></td>
                                            <td><?php echo $value['Age']?></td>
                                            <td><?php echo $value['Sex']?></td>
                                            <td><?php echo $value['MobileNo']?></td>
                                            <td><?php echo $value['Ac_No'] . " / " . $value['List_No'] . " / " .$value['Voter_Id']; ?></td>
                                            <td><?php echo $value['Ward_no']?></td>
                                            <td><?php echo $value['SocietyName']?></td>
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

<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>
</section>