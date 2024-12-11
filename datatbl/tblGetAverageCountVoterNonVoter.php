<?php
    session_start();
    include '../api/includes/DbOperation.php'; 

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];

    $cond = $_GET['cond'];

    $currentDate = date('Y-m-d');
    $previousdate = date('Y-m-d');
    // $previousdate = date('Y-m-d', strtotime('-1 days'));
    $fromDate = $currentDate." ".$_SESSION['StartTime'];
    $toDate =$currentDate." ".$_SESSION['EndTime'];


    if(isset($_SESSION['FromDate_Average_Count']) &&
    isset($_SESSION['ToDate_Average_Count'])){
        $previousdate = $_SESSION['FromDate_Average_Count'];
        $currentDate = $_SESSION['ToDate_Average_Count'];
    }

    if(isset($_SESSION['SurveyUA_Election_Cd']) &&
    isset($_SESSION['SurveyUA_ElectionName'])){
        $election_Cd_of_Dashboard = $_SESSION['SurveyUA_Election_Cd'];
        $electionName_of_Dashboard = $_SESSION['SurveyUA_ElectionName'];
    }else{
        $electionName_of_Dashboard = 'CHINCHWAD';
        $election_Cd_of_Dashboard = '68';
    }


    if(isset($_SESSION['SurveyUA_Election_Cd']) &&
    isset($_SESSION['SurveyUA_ElectionName'])){
        $election_Cd_AverageCount = $_SESSION['SurveyUA_Election_Cd'];
        $electionName_AverageCount = $_SESSION['SurveyUA_ElectionName'];
    }else{
        $electionName_AverageCount = $electionName_of_Dashboard;
        $election_Cd_AverageCount = $election_Cd_of_Dashboard;
    }


    $SiteName = "";
    $SiteNameCond = "";
    $SiteName_AverageCount = "";
    if(isset($_SESSION['SurveyUA_SiteCd_Average_Count']) &&
    isset($_SESSION['SurveyUA_SiteCd_Average_Count'])){

        if($_SESSION['SurveyUA_SiteCd_Average_Count'] == "ALL"){
            $SiteNameCond = "";
        }
        else{
            $Site_Cd_AverageCount = $_SESSION['SurveyUA_SiteCd_Average_Count'];
            $SiteCdQuery = "SELECT SiteName FROM Site_Master WHERE Site_Cd = $Site_Cd_AverageCount";
            $SiteName_AverageCount = $db->ExecutveQuerySingleRowSALData($ULB,$SiteCdQuery , $userName, $appName, $developmentMode);
            $SiteName = $SiteName_AverageCount["SiteName"];
            $SiteNameCond = "AND sm.SiteName = '$SiteName'";
        }
    }else{
        $SiteNameCond = "";
    }

    // GetDBName method is created in DbOperation by SWAPNIL, add this method while merging!
    $DBName = $db->GetDBName($ULB,$electionName_AverageCount, $election_Cd_AverageCount, $userName, $appName, $developmentMode);
            
    $DBName2 = $db->getSurveyUtilityAppDBNameByUser($userName, $appName, $developmentMode);
    if($DBName2['error'] == false ){
        $DBName2 = "[".$DBName2['DbName']."]";
    }else{
        echo $DBName2["message"];
    }

    if($cond == "TotalVoter"){

        $VoterTableDataTotalVoter = '';

        $VoterTotalQueryVoter = "SELECT vot.SiteName,vot.SocietyName,vot.FullName,vot.RoomNo
        FROM $DBName2..Site_Master AS sitemas
        INNER JOIN $DBName2..Society_Master AS sm
        ON sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster AS subloc
        ON sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..Dw_VotersInfo AS vot
        ON subloc.SubLocation_Cd = vot.SubLocation_Cd 
        WHERE sm.ElectionName ='$electionName_AverageCount' $SiteNameCond AND SF=1 
        AND CONVERT(VARCHAR,vot.UpdatedDate,23) BETWEEN '$previousdate' AND '$currentDate' and vot.SiteName is not null";

        $VoterTableDataTotalVoter = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryVoter , $userName, $appName, $developmentMode);
        // print_r($VoterTableDataTotalVoter);
        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Voter Count List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Android Form No</th>
                                                            <th>Room No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                            if(sizeof($VoterTableDataTotalVoter) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalVoter AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['FullName']?></td>
                                                                    <td><?php echo $value['RoomNo']?></td>
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
                    </section>
                </div>
            </div>
        </div>

        <?php
    }
    elseif($cond == "TotalNonVoter"){

        $VoterTableDataTotalNonVoter = '';

        $VoterTotalQueryNonVoter = "SELECT nvot.SiteName,nvot.SocietyName,nvot.FullName,nvot.RoomNo
        FROM $DBName2..Site_Master AS sitemas
        INNER JOIN $DBName2..Society_Master AS sm
        ON sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster AS subloc
        ON sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..NewVoterRegistration AS nvot
        ON subloc.SubLocation_Cd = nvot.Subloc_cd 
        WHERE sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        AND CONVERT(VARCHAR,nvot.UpdatedDate,23) BETWEEN '$previousdate' AND '$currentDate'";

        $VoterTableDataTotalNonVoter = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryNonVoter , $userName, $appName, $developmentMode); ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Non Voter Count List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Full Name</th>
                                                            <th>Room No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($VoterTableDataTotalNonVoter) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalNonVoter AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['FullName']?></td>
                                                                    <td><?php echo $value['RoomNo']?></td>
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
                    </section>
                </div>
            </div>
        </div>

        <?php
    }
    elseif($cond == "TotalLockRoom"){

        $VoterTableDataTotalLockRoom = '';

        $VoterTotalQueryLockRoom = "SELECT lr.SiteName,lr.SocietyName,lr.RoomNo
        from $DBName2..Site_Master as sitemas
        INNER JOIN $DBName2..Society_Master as sm
        on sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster as subloc
        on sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..LockRoom as lr
        on subloc.SubLocation_Cd = lr.SubLocation_Cd 
        where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        and CONVERT(varchar,lr.UpdatedDate,23) between '$previousdate' and '$currentDate'";

        $VoterTableDataTotalLockRoom = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryLockRoom , $userName, $appName, $developmentMode); ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Total Lock Room List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Room No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($VoterTableDataTotalLockRoom) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalLockRoom AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['RoomNo']?></td>
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
                    </section>
                </div>
            </div>
        </div>

        <?php
    }
    elseif($cond == "TotalRoom"){

        $VoterTableDataTotalTotalRoom = '';

        $VoterTotalQueryTotalRoom = "SELECT vot.SiteName,vot.SocietyName,AndroidFormNo,vot.RoomNo 
        from $DBName2..Site_Master as sitemas
        inner join $DBName2..Society_Master as sm
        on sm.SiteName = sitemas.SiteName
        inner join $DBName..SubLocationMaster as subloc
        on sm.Society_Cd = subloc.Survey_Society_Cd
        inner join $DBName..Dw_VotersInfo as vot
        on subloc.SubLocation_Cd = vot.SubLocation_Cd 
        where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' and vot.SiteName is not null
        group by vot.SiteName,vot.SocietyName,AndroidFormNo,vot.RoomNo 
    union
        select nvot.SiteName,nvot.SocietyName,AndroidFormNo,nvot.Roomno 
        from $DBName2..Site_Master as sitemas
        inner join $DBName2..Society_Master as sm
        on sm.SiteName = sitemas.SiteName
        inner join $DBName..SubLocationMaster as subloc
        on sm.Society_Cd = subloc.Survey_Society_Cd
        inner join $DBName..NewVoterRegistration as nvot
        on subloc.SubLocation_Cd = nvot.Subloc_cd 
        where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' and '$currentDate' and nvot.SiteName is not null
        group by nvot.SiteName,nvot.SocietyName,AndroidFormNo,nvot.RoomNo";

        $VoterTableDataTotalTotalRoom = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryTotalRoom , $userName, $appName, $developmentMode); ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Total Room Count List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Android Form No</th>
                                                            <th>Room No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($VoterTableDataTotalTotalRoom) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalTotalRoom AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['AndroidFormNo']?></td>
                                                                    <td><?php echo $value['RoomNo']?></td>
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
                    </section>
                </div>
            </div>
        </div>

        <?php
    }
    elseif($cond == "Birthdate"){

        $VoterTableDataTotalBirthdate = '';

        $VoterTotalQueryBirthdate = "SELECT SubTotals.SiteName, SubTotals.SocietyName, SubTotals.AndroidFormNo, SubTotals.Roomno,convert(varchar, SubTotals.BirthDate, 34) as BirthDate
            from
            (select vot.SiteName,vot.SocietyName,AndroidFormNo,vot.Roomno,vot.BirthDate
                from $DBName2..Site_Master as sitemas
                inner join $DBName2..Society_Master as sm
                on sm.SiteName = sitemas.SiteName
                inner join $DBName..SubLocationMaster as subloc
                on sm.Society_Cd = subloc.Survey_Society_Cd
                inner join $DBName..Dw_VotersInfo as vot
                on subloc.SubLocation_Cd = vot.SubLocation_Cd 
                where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond and SF=1 
                and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' and vot.BirthDate is not null
            UNION ALL
            select nvot.SiteName,nvot.SocietyName,AndroidFormNo,nvot.Roomno,nvot.Birthdate
                from $DBName2..Site_Master as sitemas
                inner join $DBName2..Society_Master as sm
                on sm.SiteName = sitemas.SiteName
                inner join $DBName..SubLocationMaster as subloc
                on sm.Society_Cd = subloc.Survey_Society_Cd
                inner join $DBName..NewVoterRegistration as nvot
                on subloc.SubLocation_Cd = nvot.Subloc_cd 
                where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
                and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' and '$currentDate' and nvot.Birthdate is not null
            ) SubTotals";

        $VoterTableDataTotalBirthdate = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryBirthdate , $userName, $appName, $developmentMode); ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Birthdate Count List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body card-dashboard">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Android Form No</th>
                                                            <th>Room No</th>
                                                            <th>Birthdate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($VoterTableDataTotalBirthdate) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalBirthdate AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['AndroidFormNo']?></td>
                                                                    <td><?php echo $value['Roomno']?></td>
                                                                    <td><?php echo $value['BirthDate']?></td>
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
                    </section>
                </div>
            </div>
        </div>

        <?php
    }
    elseif($cond == "MobileNo"){

        $VoterTableDataTotalMobileNo = '';

        $VoterTotalQueryMobileNo = "SELECT vot.SiteName,vot.SocietyName,vot.Roomno,vot.MobileNo
            from $DBName2..Site_Master as sitemas
            Inner join $DBName2..Society_Master as sm 
            -- on sm.SiteName = sitemas.SiteName
            on sm.Site_Cd = sitemas.Site_Cd
            Inner join $DBName..SubLocationMaster as subloc
            on sm.Society_Cd = subloc.Survey_Society_Cd
            Inner join $DBName..Dw_VotersInfo as vot
            on subloc.SubLocation_Cd = vot.SubLocation_Cd 
            where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond and SF=1 
            and sm.Site_Cd in (select distinct(Site_Cd) from $DBName2..Society_Master where ElectionName = '$electionName_AverageCount')
            and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' and vot.MobileNo <> '' and vot.SiteName is not null
        union
            select nvot.SiteName,nvot.SocietyName,nvot.Roomno,nvot.MobileNo
            from $DBName2..Site_Master as sitemas
            Inner join $DBName2..Society_Master as sm
            -- on sm.SiteName = sitemas.SiteName
            on sm.Site_Cd = sitemas.Site_Cd
            Inner join $DBName..SubLocationMaster as subloc
            on sm.Society_Cd = subloc.Survey_Society_Cd
            Inner join $DBName..NewVoterRegistration as nvot
            on subloc.SubLocation_Cd = nvot.Subloc_cd 
            where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond 
            and sm.Site_Cd in (select distinct(Site_Cd) from $DBName2..Society_Master where ElectionName = '$electionName_AverageCount')
            and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' and '$currentDate' and nvot.MobileNo <> '' and nvot.SiteName is not null" ;

        $VoterTableDataTotalMobileNo = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterTotalQueryMobileNo , $userName, $appName, $developmentMode);?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Mobile Number Count List</h4>
                </div>
                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Site Name</th>
                                                            <th>Society Name</th>
                                                            <th>Room No</th>
                                                            <th>Mobile No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            if(sizeof($VoterTableDataTotalMobileNo) > 0){
                                                                $srNo = 1;
                                                                foreach($VoterTableDataTotalMobileNo AS $Key=>$value){  
                                                                ?>
                                                                
                                                                <tr>
                                                                    <td><?php echo $srNo++; ?></td>
                                                                    <td><?php echo $value['SiteName']?></td>
                                                                    <td><?php echo $value['SocietyName']?></td>
                                                                    <td><?php echo $value['Roomno']?></td>
                                                                    <td><?php echo $value['MobileNo']?></td>
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
                    </section>
                </div>
            </div>
        </div>
        <?php
    }
?>