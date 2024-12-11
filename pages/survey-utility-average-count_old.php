
<section id="dashboard-analytics">

<?php
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        $fullName = $_SESSION['SurveyUA_FullName'];

        
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

// ================================================SWAPNIL=========================================================
        
        if(isset($_SESSION['SurveyUA_Election_Cd']) &&
        isset($_SESSION['SurveyUA_ElectionName'])){
            $election_Cd_AverageCount = $_SESSION['SurveyUA_Election_Cd'];
            $electionName_AverageCount = $_SESSION['SurveyUA_ElectionName'];
        }else{
            $electionName_AverageCount = $electionName_of_Dashboard;
            $election_Cd_AverageCount = $election_Cd_of_Dashboard;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd_AverageCount;
            $_SESSION['SurveyUA_ElectionName'] = $electionName_AverageCount;
        }
        $SurveyUA_Election_Cd_Average_Count = "";
        $SurveyUA_Election_Cd_Average_Count = $_SESSION['SurveyUA_Election_Cd'];

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
                $SiteName_AverageCount = $db->ExecutveQuerySingleRowSALData($SiteCdQuery , $userName, $appName, $developmentMode);
                $SiteName = $SiteName_AverageCount["SiteName"];
                $SiteNameCond = "AND sm.SiteName = '$SiteName'";
            }
        }else{
            $SiteNameCond = "";
        }



// ================================================SWAPNIL Count Queries=========================================================

        // GetDBName method is created in DbOperation by SWAPNIL, add this method while merging!
        $DBName = $db->GetDBName($electionName_AverageCount, $election_Cd_AverageCount, $userName, $appName, $developmentMode);
        
        $DBName2 = $db->getSurveyUtilityAppDBNameByUser($userName, $appName, $developmentMode);
        // echo $DBName;
        if($DBName2['error'] == false ){
            $DBName2 = "[".$DBName2['DbName']."]";
        }else{
            $DBName2 = $DBName2["message"];
        }
 

        // print_r($electionName_AverageCount);
        // print_r($DBName);
        // print_r($DBName2);
        // if(isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP'])){
        //     $servername1 = $_SESSION['SurveyUtility_ServerIP'];
        // }else{
        //     $servername1 = "";
        // }
        // print_r($servername1);

        // Voter Count Single Query
        $VoterCountDataTotalVoter = '';

        $VoterCountQueryVoter = "SELECT COUNT(*) AS cnt
        FROM $DBName2..Site_Master AS sitemas
        INNER JOIN $DBName2..Society_Master AS sm
        ON sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster AS subloc
        ON sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..Dw_VotersInfo AS vot
        ON subloc.SubLocation_Cd = vot.SubLocation_Cd 
        WHERE sm.ElectionName ='$electionName_AverageCount' AND SF=1 $SiteNameCond
        AND CONVERT(VARCHAR,vot.UpdatedDate,23) BETWEEN '$previousdate' AND '$currentDate' and vot.SiteName is not null";

        $VoterCountDataTotalVoter = $db->ExecutveQuerySingleRowSALData($VoterCountQueryVoter , $userName, $appName, $developmentMode);
        

        // Non Voter Count Single Query
        $VoterCountDataTotalNonVoter = '';

        $VoterCountQueryNonVoter = "SELECT count(*) AS cnt
        FROM $DBName2..Site_Master AS sitemas
        INNER JOIN $DBName2..Society_Master AS sm
        ON sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster AS subloc
        ON sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..NewVoterRegistration AS nvot
        ON subloc.SubLocation_Cd = nvot.Subloc_cd 
        WHERE sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        AND CONVERT(VARCHAR,nvot.UpdatedDate,23) BETWEEN '$previousdate' AND '$currentDate' ";

        $VoterCountDataTotalNonVoter = $db->ExecutveQuerySingleRowSALData($VoterCountQueryNonVoter , $userName, $appName, $developmentMode);


        // Lock Room Count Single Query
        $VoterCountDataTotalLockRoom = '';

        $VoterCountQueryLockRoom = "SELECT count(*) AS cnt
        FROM $DBName2..Site_Master AS sitemas
        INNER JOIN $DBName2..Society_Master AS sm
        ON sm.SiteName = sitemas.SiteName
        INNER JOIN $DBName..SubLocationMaster AS subloc
        ON sm.Society_Cd = subloc.Survey_Society_Cd
        INNER JOIN $DBName..LockRoom AS lr
        ON subloc.SubLocation_Cd = lr.SubLocation_Cd 
        WHERE sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
        AND CONVERT(VARCHAR,lr.UpdatedDate,23) BETWEEN '$previousdate' AND '$currentDate'";

        $VoterCountDataTotalLockRoom = $db->ExecutveQuerySingleRowSALData($VoterCountQueryLockRoom , $userName, $appName, $developmentMode);


        // Total Room (form) Count 
        $VoterCountDataTotalTotalRoom = '';

        $VoterCountQueryTotalRoom = "SELECT count(*) as TOTROOMS from
        (
        select vot.SiteName,vot.SocietyName,AndroidFormNo,vot.RoomNo
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
            group by nvot.SiteName,nvot.SocietyName,AndroidFormNo,nvot.RoomNo 
        ) ab1";

        $VoterCountDataTotalTotalRoom = $db->ExecutveQuerySingleRowSALData($VoterCountQueryTotalRoom , $userName, $appName, $developmentMode);


        // Mobile No Count
        $VoterCountDataTotalMobileNo = '';

        $VoterCountQueryMobileNo = "SELECT count(*) AS MobileNoCount
             from
			(select vot.SiteName,vot.SocietyName,vot.Roomno,vot.MobileNo
					from $DBName2..Site_Master as sitemas
                    inner join $DBName2..Society_Master as sm
                    --on sm.SiteName = sitemas.SiteName
					on sm.Site_Cd = sitemas.Site_Cd
                    inner join $DBName..SubLocationMaster as subloc
                    on sm.Society_Cd = subloc.Survey_Society_Cd
                    inner join $DBName..Dw_VotersInfo as vot
                    on subloc.SubLocation_Cd = vot.SubLocation_Cd 
					where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
                    and sm.Site_Cd in (select distinct(Site_Cd) from $DBName2..Society_Master where ElectionName = '$electionName_AverageCount')
					and SF=1 
					and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
					and vot.MobileNo <> '' and vot.SiteName is not null
                union
					select nvot.SiteName,nvot.SocietyName,nvot.Roomno,nvot.MobileNo
					from $DBName2..Site_Master as sitemas 
                    inner join $DBName2..Society_Master as sm
                    --on sm.SiteName = sitemas.SiteName
					  on sm.Site_Cd = sitemas.Site_Cd
                    inner join $DBName..SubLocationMaster as subloc
                    on sm.Society_Cd = subloc.Survey_Society_Cd
                    inner join $DBName..NewVoterRegistration as nvot
                    on subloc.SubLocation_Cd = nvot.Subloc_cd 
					where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
                    and sm.Site_Cd in (select distinct(Site_Cd) from $DBName2..Society_Master where ElectionName = '$electionName_AverageCount')
					and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
					and nvot.MobileNo <> '' and nvot.SiteName is not null) tb ;" ;

        $VoterCountDataTotalMobileNo = $db->ExecutveQuerySingleRowSALData($VoterCountQueryMobileNo , $userName, $appName, $developmentMode);


        // Birthdate Count
        $VoterCountDataTotalBirthdate = array();

        $VoterCountQueryBirthdate = "SELECT sum(SubTotals.BirthDateCount) as BirthDateCount
        from
        (select count(BirthDate) as BirthDateCount
                            from $DBName2..Site_Master as sitemas
                            inner join $DBName2..Society_Master as sm
                            on sm.SiteName = sitemas.SiteName
                            inner join $DBName..SubLocationMaster as subloc
                            on sm.Society_Cd = subloc.Survey_Society_Cd
                            inner join $DBName..Dw_VotersInfo as vot
                            on subloc.SubLocation_Cd = vot.SubLocation_Cd 
                            where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond and SF=1 
                            and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
        UNION ALL
        select count(BirthDate) as BirthDateCount
                            from $DBName2..Site_Master as sitemas
                            inner join $DBName2..Society_Master as sm
                            on sm.SiteName = sitemas.SiteName
                            inner join $DBName..SubLocationMaster as subloc
                            on sm.Society_Cd = subloc.Survey_Society_Cd
                            inner join $DBName..NewVoterRegistration as nvot
                            on subloc.SubLocation_Cd = nvot.Subloc_cd 
                            where sm.ElectionName ='$electionName_AverageCount' $SiteNameCond
                            and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
        ) SubTotals";

        $VoterCountDataTotalBirthdate = $db->ExecutveQuerySingleRowSALData($VoterCountQueryBirthdate , $userName, $appName, $developmentMode);


// ================================================/SWAPNIL Count Queries=========================================================

function IND_money_format($number){
        $decimal = (string)($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for($i=0;$i<$length;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                $delimiter .=',';
            }
            $delimiter .=$money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if( $decimal != '0'){
            $result = $result.$decimal;
        }

        return $result;
    }

?>

    <div class="row match-height">
        <div class="col-md-12">
            <div class="card"> 
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <?php include 'dropdown-electionname-average-count.php'; ?>
                                </div>
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <?php include 'dropdown-site-average-count.php'; ?>
                                </div>
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="fromdate" value="<?php echo $previousdate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="todate" value="<?php echo $currentDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row-reverse mt-2">
                                <div class="controls text-center">
                                    <button type="button" class="btn btn-primary" onclick="getAverageCountDatesAndSetSession()" >
                                            Refresh 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row match-height">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card card-congratulation-medal">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $electionName_AverageCount.' '; ?> Summary</h5>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0"></p>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('TotalVoter')">
                                <div class="media">
                                    <div class="bg-light-primary p-50  mr-3" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/votersvg.svg" alt="Sites" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($VoterCountDataTotalVoter["cnt"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Total Voter</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('TotalNonVoter')">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-3" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/NonVoter.svg" alt="Pockets" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto">
                                        <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($VoterCountDataTotalNonVoter["cnt"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Total Non-Voter</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('TotalLockRoom')">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/pendingsvg.svg" alt="Societies" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0">
                                            <?php echo IND_money_format($VoterCountDataTotalLockRoom["cnt"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Total Lock Room</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('TotalRoom')">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/socitetiessvg.svg" alt="Executives" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($VoterCountDataTotalTotalRoom["TOTROOMS"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Total Room (Form)</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('Birthdate')">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/Birthday.svg" alt="Executives" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($VoterCountDataTotalBirthdate["BirthDateCount"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Birthdate Count</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                            <a href="#" onclick="sendCondAndShowtblData('MobileNo')">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/MobileNo.svg" alt="Executives" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($VoterCountDataTotalMobileNo["MobileNoCount"]); ?></h4>
                                        <p class="card-text font-small-4 mb-0">Mobile Number Count</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row match-height" id="tblAverageCountDetail">
    </div>
