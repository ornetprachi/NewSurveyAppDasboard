
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


        // SITENAME for Vot, Nvot, Lock Room, Total Room, Birthday, Mobile
        $SiteName = "";
        $SiteNameCond = "";
        $SiteName_AverageCount = "";
        if(isset($_SESSION['SurveyUA_SiteCd_Average_Count']) &&
        isset($_SESSION['SurveyUA_SiteCd_Average_Count'])){

            if($_SESSION['SurveyUA_SiteCd_Average_Count'] == "ALL"){
                $SiteNameCond = "";
                $SiteNameCondCRVot = "";
                $SiteNameCondCRNovt = "";
            }
            else{
                $Site_Cd_AverageCount = $_SESSION['SurveyUA_SiteCd_Average_Count'];
                $SiteCdQuery = "SELECT SiteName FROM Site_Master WHERE Site_Cd = $Site_Cd_AverageCount";
                $SiteName_AverageCount = $db->ExecutveQuerySingleRowSALData($ULB,$SiteCdQuery , $userName, $appName, $developmentMode);
                $SiteName = $SiteName_AverageCount["SiteName"];
                $SiteNameCond = "AND sm.SiteName = '$SiteName'";
                $SiteNameCondCRVot = "AND vot.SiteName = '$SiteName'";
                $SiteNameCondCRNovt = "AND nvot.SiteName = '$SiteName'";
            }
        }else{
            $SiteNameCond = "";
            $SiteNameCondCRVot = "";
            $SiteNameCondCRNovt = "";
        }


// ================================================SWAPNIL Count Queries=========================================================

        // GetDBName method is created in DbOperation by SWAPNIL, add this method while merging!
        $DBName = $db->GetDBName($ULB,$electionName_AverageCount, $election_Cd_AverageCount, $userName, $appName, $developmentMode);
        
        $DBName2 = $db->getSurveyUtilityAppDBNameByUser($userName, $appName, $developmentMode);
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

        $VoterCountDataTotalVoter = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryVoter , $userName, $appName, $developmentMode);
        

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

        $VoterCountDataTotalNonVoter = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryNonVoter , $userName, $appName, $developmentMode);


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

        $VoterCountDataTotalLockRoom = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryLockRoom , $userName, $appName, $developmentMode);


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

        $VoterCountDataTotalTotalRoom = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryTotalRoom , $userName, $appName, $developmentMode);


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

        $VoterCountDataTotalMobileNo = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryMobileNo , $userName, $appName, $developmentMode);


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

        $VoterCountDataTotalBirthdate = $db->ExecutveQuerySingleRowSALData($ULB,$VoterCountQueryBirthdate , $userName, $appName, $developmentMode);


        // Room Wise Condolidated Report
        $VoterCountDataTotalRoomWiseConsolidated = array();

        $VoterCountQueryRoomWiseConsolidated = "SELECT COALESCE(tb3.SiteName,'') as SiteName,sum(tb3.tot) as Total,Sum(tb3.UpdateByUser) as ExeName, (sum(tb3.tot) / Sum(tb3.UpdateByUser)) as Results from
        (select sum(tb2.Counts) as tot, count(tb2.UpdateByUser) as UpdateByUser,tb2.SiteName from
        (select count(*) as Counts,tb1.SiteName,tb1.UpdateByUser from
        (
        select vot.SiteName,vot.SocietyName,AndroidFormNo,vot.RoomNo,vot.UpdateByUser 
        from $DBName2..Site_Master as sitemas
                inner join $DBName2..Society_Master as sm
                on sm.Site_Cd = sitemas.Site_Cd
                inner join $DBName..SubLocationMaster as subloc
                on sm.Society_Cd = subloc.Survey_Society_Cd
                inner join $DBName..Dw_VotersInfo as vot
                on subloc.SubLocation_Cd = vot.SubLocation_Cd 
                where sm.ElectionName ='$electionName_AverageCount' $SiteNameCondCRVot 
                and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
                and vot.SiteName <> '' and vot.SiteName is not null
                group by vot.SiteName,
                vot.AndroidFormNo,
                vot.SocietyName,CONVERT(VARCHAR,vot.UpdatedDate,23),
                vot.RoomNo,
                vot.UpdateByUser
            union
                select nvot.SiteName,nvot.SocietyName,AndroidFormNo,nvot.Roomno,nvot.UpdateByUser 
                from $DBName2..Site_Master as sitemas
                    inner join $DBName2..Society_Master as sm
                    on sm.Site_Cd = sitemas.Site_Cd
                    inner join $DBName..SubLocationMaster as subloc
                    on sm.Society_Cd = subloc.Survey_Society_Cd
                    inner join $DBName..NewVoterRegistration as nvot
                    on subloc.SubLocation_Cd = nvot.Subloc_cd 
                    where sm.ElectionName ='$electionName_AverageCount' $SiteNameCondCRNovt 
                    and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' 
                    and '$currentDate' and nvot.SiteName <> '' and nvot.SiteName is not null
                    group by nvot.SiteName,nvot.AndroidFormNo,
                    nvot.SocietyName,CONVERT(VARCHAR,nvot.UpdatedDate,23),
                    nvot.RoomNo,
                    nvot.UpdateByUser ) as tb1
                group by tb1.SiteName,tb1.UpdateByUser) as tb2
                group by tb2.SiteName,tb2.UpdateByUser) as tb3
                group by tb3.SiteName;";

        $VoterCountDataTotalRoomWiseConsolidated = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterCountQueryRoomWiseConsolidated , $userName, $appName, $developmentMode);
        // print_r($VoterCountDataTotalRoomWiseConsolidated);
        
        $RoomWiseConsolidatedReportSumString = "";
        if($SiteNameCond == ""){
            // echo "In here if loop";
            // $RoomWiseConsolidatedReportSum = array_sum(array_column($VoterCountDataTotalRoomWiseConsolidated, 'Results'));

            // $TotalRW = array_sum(array_column($VoterCountDataTotalRoomWiseConsolidated, 'Total'));
            // $ExeNameRW = array_sum(array_column($VoterCountDataTotalRoomWiseConsolidated, 'ExeName'));
            $ResultsRW = array_sum(array_column($VoterCountDataTotalRoomWiseConsolidated, 'Results'));

            // $RoomWiseConsolidatedReportSumString = $TotalRW . " / " . $ExeNameRW . " = " . $ResultsRW;

        }else{
            // echo "In here else loop";
            // $RoomWiseConsolidatedReportSum = $VoterCountDataTotalRoomWiseConsolidated[0]["Results"];

            // $TotalRW = $VoterCountDataTotalRoomWiseConsolidated[0]["Total"];
            // $ExeNameRW = $VoterCountDataTotalRoomWiseConsolidated[0]["ExeName"];
            if(sizeof($VoterCountDataTotalRoomWiseConsolidated) > 0){
                $ResultsRW = $VoterCountDataTotalRoomWiseConsolidated[0]["Results"];
            }else{
                $ResultsRW = 0;
            }
            

            // $RoomWiseConsolidatedReportSumString = $TotalRW . " / " . $ExeNameRW . " = " . $ResultsRW;

        }

        // echo $RoomWiseConsolidatedReportSumString;



        // Voter Non Voter Wise Condolidated Report
        $VoterCountDataTotalVotNvotConsolidated = array();

        $VoterCountQueryVotNvotConsolidated = "SELECT count(UpdateByUser) as ExeCount,SUM(VNVCount) as Total from
        (select UpdatedDate, UpdateByUser, count(UpdatedDate) as VNVCount from
        (select CONVERT(VARCHAR,vot.UpdatedDate,23) AS UpdatedDate,vot.UpdateByUser
        from $DBName2..Site_Master as sitemas
            inner join $DBName2..Society_Master as sm
            on sm.Site_Cd = sitemas.Site_Cd
            inner join $DBName..SubLocationMaster as subloc
            on sm.Society_Cd = subloc.Survey_Society_Cd
            inner join $DBName..Dw_VotersInfo as vot
            on subloc.SubLocation_Cd = vot.SubLocation_Cd 
            where vot.SF=1 and sm.ElectionName ='$electionName_AverageCount' $SiteNameCondCRVot 
            and vot.UpdateByUser is not null
            and CONVERT(varchar,vot.UpdatedDate,23) between '$previousdate' and '$currentDate' 
            and vot.SiteName <> '' and vot.SiteName is not null
        union all
        select CONVERT(VARCHAR,nvot.UpdatedDate,23) AS UpdatedDate,nvot.UpdateByUser
        from $DBName2..Site_Master as sitemas
            inner join $DBName2..Society_Master as sm
            on sm.Site_Cd = sitemas.Site_Cd
            inner join $DBName..SubLocationMaster as subloc
            on sm.Society_Cd = subloc.Survey_Society_Cd
            inner join $DBName..NewVoterRegistration as nvot
            on subloc.SubLocation_Cd = nvot.Subloc_cd 
            where sm.ElectionName ='$electionName_AverageCount' $SiteNameCondCRNovt
            and nvot.UpdateByUser is not null
            and CONVERT(varchar,nvot.UpdatedDate,23) between '$previousdate' 
            and '$currentDate' and nvot.SiteName <> '' and nvot.SiteName is not null ) as tb1 
        group by UpdatedDate, UpdateByUser) as tb2";

        $VoterCountDataTotalVotNvotConsolidated = $db->ExecutveQueryMultipleRowSALData($ULB,$VoterCountQueryVotNvotConsolidated , $userName, $appName, $developmentMode);
        // print_r($VoterCountDataTotalVotNvotConsolidated);
        // $RoomWiseConsolidatedReportSum = array_sum(array_column($VoterCountDataTotalRoomWiseConsolidated, 'Results'));
        // print_r($VoterCountDataTotalVotNvotConsolidated[0]);
        
        $VNVTotal = $VoterCountDataTotalVotNvotConsolidated[0]['Total'];
        $VNVExeCount = $VoterCountDataTotalVotNvotConsolidated[0]['ExeCount'];
        if($VNVExeCount > 0 && $VNVTotal > 0){
            $VotNVotWiseConsolidatedReportSum = IND_money_format($VNVTotal/$VNVExeCount);
        }else{
            $VotNVotWiseConsolidatedReportSum = 0;
        }
        // echo $VotNVotWiseConsolidatedReportSum = IND_money_format($VoterCountDataTotalVotNvotConsolidated[0]["Total"]/$VoterCountDataTotalVotNvotConsolidated[0]["ExeCount"]);
        // $VotNVotWiseConsolidatedReportSumString = $VNVTotal . " / " . $VNVExeCount . " = " . $VotNVotWiseConsolidatedReportSum;
        // $VotNVotWiseConsolidatedReportSumString = $VNVTotal . " (Total) / " . $VNVExeCount . " (ExeCount) = " . $VotNVotWiseConsolidatedReportSum;
        // echo $VotNVotWiseConsolidatedReportSumString;
        



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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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

                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                            <a href="#">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/Report1.png" alt="Societies" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0">
                                            <?php echo $ResultsRW; ?></h4>
                                        <p class="card-text font-small-4 mb-0">Room Wise Consolidated Report</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
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
                        <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                            <a href="#">
                                <div class="media">
                                    <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                        <div class="avatar-content">
                                        <img src="app-assets/images/Report2.png" alt="Executives" width="60" height="60">
                                        </div>
                                    </div>
                                    <div class="media-body my-auto" style="margin-left: 30px;">
                                        <h4 class="font-weight-bolder mb-0"><?php echo $VotNVotWiseConsolidatedReportSum; ?></h4>
                                        <p class="card-text font-small-4 mb-0">Voter Non-Voter Wise Consolidated Report</p>
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
