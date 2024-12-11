
<link rel="apple-touch-icon" href="app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/core/colors/palette-gradient.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
<style>
    .table-bordered thead th, .table-bordered thead td {
    border-bottom-width: 2px;
    color: #0a7e7e;
}
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
    border-bottom-width: 0;
    padding: 8px;
}
table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    /* padding-right: 30px; */
    padding: 8px;
}
</style>
<section id="ElectionWiseSummary">
<div id="ElectionWiseSummary">
    <?php
    // die();
    $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $ServerName=$_SESSION['SurveyUtility_ServerIP'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        $fullName = $_SESSION['SurveyUA_FullName'];
        $OverallEdata = array();
        $OverallExecutivedata = array();
        $OverallSitedata = array();
        $OverallSocietydata = array();
        $Buildingcountdata = array();
        $ExecutiveCount = array();
        

        $TotalSites = 0 ;
        $TotalPockets = 0 ;
        $TotalSocieties = 0 ;
        $TotalExecutive = 0 ;
        $TotalVoters = 0 ;
        $TotalNonVoters = 0 ;
        $TotalLockRooms = 0 ;
        $currentDate = date('Y-m-d');
        // $fromdate = $currentDate;
        // $todate =$currentDate;

        if(isset($_SESSION['SurveyUA_ElectionName_For_Summary'])){
          $Election = $_SESSION['SurveyUA_ElectionName_For_Summary'];
        }else{
            // echo "Enot set";
            $Election = '';
        }
        if(isset($_SESSION['SurveyUA_ExecutiveName_For_Summary'])){
           $Executive = $_SESSION['SurveyUA_ExecutiveName_For_Summary'];
        }else{
            // echo "Exnot set";
            $Executive = '';
        }
        if(isset($_SESSION['SurveyUA_SiteName_For_Summary'])){
           $Site_Nmae = $_SESSION['SurveyUA_SiteName_For_Summary'];
        }else{
            // echo "Snot set";
            $Site_Nmae = '';
        }
        if(isset($_SESSION['SurveyUA_SocietyName_for_Summary'])){
            $Society_Name = $_SESSION['SurveyUA_SocietyName_for_Summary'];
        }else{
            // echo "Snot set";
            $Society_Name = '';
        }
        if(isset($_SESSION['SurveyUA__FromDate_For_Summary']) && isset($_SESSION['SurveyUA__ToDate_For_Summary'])){
            $fromdate = $_SESSION['SurveyUA__FromDate_For_Summary'];
            $todate = $_SESSION['SurveyUA__ToDate_For_Summary'];
        }else{
            $fromdate = $currentDate;
            $todate =$currentDate;
        }
        // echo $Election = $_GET['Election'];
        if(isset($_SESSION['SurveyUA_SiteName']) ){
            $SiteName = $_SESSION['SurveyUA_SiteName'];
        }
      $EelectionListData = array();
      $Query = " SELECT Election_Cd,ElectionName,ServerName,ServerId,ServerPwd,DBName FROM Election_Master WHERE ActiveFlag = 1
    --   and Ac_No <> 0 and Ac_No is not null
      ";
      $EelectionListData = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);
        $MainTableDataArray = array();
      foreach($EelectionListData as $key=>$value){
        $Election_Name = $value['ElectionName'];
        $serverName = $value['ServerName'];
        $databaseName = $value['DBName'];
        $userName = $value['ServerId'];
        $password = $value['ServerPwd'];

            $connectionString = array("Database"=> $databaseName, "CharacterSet" => "UTF-8",   
                    "Uid"=> $userName, "PWD"=>$password);

            // print_r($connectionString);
            //connecting to sql database
            $Conect = sqlsrv_connect($serverName, $connectionString); 
            if($Conect){
// ------------------------------------------------All Election Data------------------------------------------------------//
                $Q ="SELECT
                (SELECT '$Election_Name' as Election) AS ElectionName,
                (SELECT COUNT(*)
                        FROM
                        (
                        SELECT SiteName FROM Dw_VotersInfo 
                        WHERE CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate' 
                        Union
                        SELECT SiteName FROM NewVoterRegistration
                        WHERE CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate') as t2)AS TotalSites,
                (SELECT COUNT(Pocket_Cd) FROM Survey_Entry_Data..Pocket_Master WHERE ElectionName = '$Election_Name') AS TotalPockets ,
                (SELECT COUNT(Society_Cd) FROM Survey_Entry_Data..Society_Master WHERE ElectionName = '$Election_Name' 
                    AND CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate')AS Listing,
                (SELECT COUNT(*)
                    from
                    (Select DISTINCT(UpdateByUser) AS Executive FROM Dw_VotersInfo 
                    where CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate'
                    Union 
                    SELECT DISTINCT(UpdateByUser) AS Executive FROM NewVoterRegistration 
                    WHERE CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate') as t1) AS TotalExecutive,
                (SELECT COUNT(Voter_Cd) FROM Dw_VotersInfo WHERE SF = 1 
                    AND CONVERT(varchar,UpdatedDate,23) BETWEEN '$fromdate' AND '$todate') AS TotalVoters,
                (SELECT COUNT(Voter_Cd) FROM NewVoterRegistration 
                    WHERE CONVERT(varchar,UpdatedDate,23) BETWEEN '$fromdate' AND '$todate') AS TotalNonVoters,
                (SELECT COUNT(LR_Cd) FROM LockRoom WHERE CONVERT(varchar,UpdatedDate,23) BETWEEN '$fromdate' AND '30-05-2023') AS TotalLockRooms;";
                $getData = sqlsrv_query($Conect, $Q); 
                $Overalldata = array();
                if ($getData == TRUE) { 
                    $row_count = sqlsrv_num_rows( $getData ); 
                  
                    while($row = sqlsrv_fetch_array($getData, SQLSRV_FETCH_ASSOC)){
                        $Overalldata[] = $row;
                        $TotalSites += $row['TotalSites'];
                        $TotalPockets += $row['TotalPockets'];
                        $TotalSocieties += $row['Listing'];
                        $TotalExecutive += $row['TotalExecutive'];
                        $TotalVoters += $row['TotalVoters'];
                        $TotalNonVoters += $row['TotalNonVoters'];
                        $TotalLockRooms += $row['TotalLockRooms'];
                    }
                }
                $MainTableDataArray = array_merge($MainTableDataArray,$Overalldata);
// ------------------------------------------------End All Election Data------------------------------------------------------//

                if($Election_Name == $Election){
// ------------------------------------------------Election Wise Executive Data------------------------------------------------------//

                $query ="SELECT  Edate, SiteName,  Name, SUM(Rooms) AS Rooms,SUM(Voters) as Voters,SUM(NonVoters) As NonVoters, SUM(Mob) AS Mob, SUM(WMob) AS WMob, SUM(Locked) AS Locked, 
                SUM(BirthDate) AS BirthDate,  sum(LBS) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob  
                FROM 
                ( SELECT CONVERT(date, Edate, 102) AS Edate, SiteName, UPPER(UpdateByUser) AS Name,  
                ((COUNT(DISTINCT AndroidFormNo) - SUM(DISTINCT CASE WHEN AndroidFormNo = 0 THEN 1 ELSE 0 END))- sum(SForms)) -  
                max(LockedButSurvey)   AS Rooms,SUM(Voters) as Voters,SUM(NonVoters) As NonVoters,  COUNT(DISTINCT MobileNo) - SUM(DISTINCT CASE WHEN MobileNo IS NULL OR  MobileNo = '' THEN 1 
                ELSE 0 END) AS Mob,  SUM(CASE WHEN WMob = 1 AND Locked = 0 THEN 1 ELSE 0 END) AS WMob, SUM(Locked) AS 
                Locked, SUM(BirthDate) AS BirthDate,  max(LockedButSurvey) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob  
                FROM 
                ( SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate, AndroidFormNo,0 as Voters,0 As NonVoters,  MobileNo, 0 AS Locked, 
                (CASE WHEN (MobileNo IS NULL OR  MobileNo = '') THEN 1 ELSE 0 END) AS WMob,0 AS BirthDate, 0 AS LockedButSurvey, 
                0 AS SForms ,0 as ExMob FROM Dw_VotersInfo WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N')  
                UNION  SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate, AndroidFormNo,0 as Voters,0 As NonVoters, Mobileno, 0 AS Locked,  
                (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
                0 as ExMob  FROM NewVoterRegistration WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N')  
                UNION ALL  
                SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate, 0 AS AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 1 AS Locked, 
                0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey , 0 AS SForms ,0 as ExMob FROM LockRoom WHERE (Locked = 1)  
				UNION ALL 
			 SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate , AndroidFormNo,COUNT(Voter_Cd) as Voters,0 As NonVoters , '' AS MobileNo, 0 AS Locked, 
				0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 
				0 AS SForms ,0 as ExMob  
				FROM Dw_VotersInfo WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  
				group by SiteName, UpdateByUser, UpdatedDate , AndroidFormNo
			UNION ALL
			SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 AS Voters,COUNT(Voter_Cd) AS NonVoters, '' AS MobileNo, 
				0 AS Locked, 0 AS WMob,  0 AS BirthDate, 
				0 AS LockedButSurvey, 0 AS SForms ,0 as ExMob  
				FROM NewVoterRegistration WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  
				GROUP BY SiteName, UpdateByUser, UpdatedDate,  AndroidFormNo
                UNION ALL  
                SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 0 AS Locked, 
                0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 0 AS SForms ,
                0 as ExMob  FROM Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N') and SurveyDate_2018 is null  
                group by  SiteName, UpdateByUser,AndroidFormNo,UpdatedDate  
                UNION ALL  SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 
                0 AS Locked, 0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 
                0 AS SForms ,0 as ExMob  FROM NewVoterRegistration  WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N') 
                and SurveyDate_2018 is null group by  SiteName, UpdateByUser,AndroidFormNo,UpdatedDate  
                UNION ALL  
                SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 0 AS Locked, 
                0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 0 AS LockedButSurvey, 
                0 AS SForms ,0 as ExMob  FROM Dw_VotersInfo WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N')  
                UNION ALL  SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 
                0 AS Locked, 0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 
                0 AS LockedButSurvey, 0 AS SForms ,0 as ExMob  FROM NewVoterRegistration WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N')  
                UNION ALL  select SiteName, UpdateByUser, Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate,
                0 AS LockedButSurvey, 0 AS SForms, count(dd.ExMob) - 1 as ExMob 
                from (  select SiteName, UpdateByUser, Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 
                0 AS LockedButSurvey, 0 AS SForms, ExMob from (  SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  
                AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  
                FROM Dw_VotersInfo WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')  
                UNION ALL  
                SELECT SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate,  AndroidFormNo,0 as Voters,0 As NonVoters, '' AS MobileNo, 
                0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  FROM NewVoterRegistration  
                WHERE (UpdatedStatus = 'Y') OR (UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')) as d  
                where (d.ExMob IS not NULL and  d.ExMob <> '')  
                group by d.AndroidFormNo,d.SiteName,d.UpdateByUser,d.Edate,d.ExMob,d.Locked,d.WMob,d.BirthDate,d.LockedButSurvey,d.SForms  ) 
                as dd group by dd.AndroidFormNo,dd.SiteName,dd.UpdateByUser,dd.Edate,dd.Locked,dd.WMob,dd.BirthDate,dd.LockedButSurvey,
                dd.SForms  UNION ALL  select SiteName, UpdateByUser,MAX(Edate) as Edate,MAX(AndroidFormNo) as AndroidFormNo,0 as Voters,0 As NonVoters,MAX(MobileNo) 
                as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,MAX(LockedButSurvey) as LockedButSurvey,
                count(SForms) as SForms ,sum(ExMob) as  ExMob from (  select SiteName, UpdateByUser,MAX(Edate) as Edate,MAX(AndroidFormNo) 
                as AndroidFormNo,MAX(MobileNo) as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,
                MAX(LockedButSurvey) as LockedButSurvey,count(SForms) as SForms, sum(ExMob) as  ExMob 
                from (  select SiteName, UpdateByUser, CONVERT(date, UpdatedDate, 102) AS Edate, '' As AndroidFormNo, '' AS MobileNo, 
                0 AS Locked, 0 AS WMob,  0 AS BirthDate,0 AS LockedButSurvey,AndroidFormNo as SForms ,0 as ExMob  
                from Dw_VotersInfo where  SurveyDate_2018 is not null and SF = 1 and CONVERT(date, UpdatedDate, 102) BETWEEN '$fromdate' 
                AND '$todate' union All select SiteName,UpdateByUser,CONVERT(date, UpdatedDate, 102) AS [Edate],'' As [AndroidFormNo],
                '' AS [MobileNo], 0 AS [Locked],0 AS [WMob],  0 AS [BirthDate],0 AS [LockedButSurvey],AndroidFormNo as [SForms] ,0 as ExMob  
                from NewVoterRegistration where SurveyDate_2018 is not null and 
                CONVERT(date, UpdatedDate, 102) BETWEEN '$fromdate' AND '$todate' ) as D group by SiteName,UpdateByUser,SForms)as D1 
                group by SiteName,UpdateByUser ) AS D2 WHERE Edate BETWEEN '$fromdate' AND '$todate' 
                GROUP BY SiteName, UpdateByUser, Edate,AndroidFormNo) as D3 group by Edate, SiteName, Name";
                $getEData = sqlsrv_query($Conect, $query); 
                
                if ($getData == TRUE) { 
                    $row_count = sqlsrv_num_rows( $getEData ); 
                
                    while($rowE = sqlsrv_fetch_array($getEData, SQLSRV_FETCH_ASSOC)){
                        $OverallEdata[] = $rowE;
                    }
                }
                // print_r($OverallEdata);
// ------------------------------------------------End Election Wise Executive Data------------------------------------------------------//
// ------------------------------------------------Election Wise Site Data------------------------------------------------------//

        $Buildingquery ="SELECT   SiteName,   SUM(Rooms) AS Rooms, SUM(Mob) AS Mob, SUM(WMob) AS WMob, SUM(Locked) AS Locked, 
            SUM(BirthDate) AS BirthDate,  sum(LBS) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob , sum(Voter) AS Voter, 
            sum(NonVoter) AS NonVoter, SUM(Societies) AS Societies  
            FROM 
                ( 
            SELECT  SiteName,   
            ((COUNT(DISTINCT AndroidFormNo) - SUM(DISTINCT CASE WHEN AndroidFormNo = 0 THEN 1 ELSE 0 END))- sum(SForms)) -  
            max(LockedButSurvey)   AS Rooms,  COUNT(DISTINCT MobileNo) - SUM(DISTINCT CASE WHEN MobileNo IS NULL OR  MobileNo = '' THEN 1 
            ELSE 0 END) AS Mob,  SUM(CASE WHEN WMob = 1 AND Locked = 0 THEN 1 ELSE 0 END) AS WMob, SUM(Locked) AS 
            Locked, SUM(BirthDate) AS BirthDate,  max(LockedButSurvey) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob , sum(Voter) AS Voter, 
            sum(NonVoter) AS NonVoter, SUM(Societies) AS Societies 
            FROM 
            ( 
                            
            SELECT SiteName,  AndroidFormNo,  MobileNo, 0 AS Locked, 
            (CASE WHEN (MobileNo IS NULL OR  MobileNo = '') THEN 1 ELSE 0 END) AS WMob,0 AS BirthDate, 0 AS LockedButSurvey, 
            0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies FROM Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' 
                            
            UNION ALL 
                            
            SELECT SiteName,   AndroidFormNo, Mobileno, 0 AS Locked,  
            (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
            0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM NewVoterRegistration 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                    
            UNION ALL  
                    
            SELECT SiteName,   0 AS AndroidFormNo, '' AS MobileNo, 1 AS Locked, 
            0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey , 0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies 
            FROM LockRoom WHERE (Locked = 1)   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                    
            UNION ALL  
                    
            SELECT SiteName,  AndroidFormNo, '' AS MobileNo, 0 AS Locked, 
            0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 0 AS SForms ,
            0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and SurveyDate_2018 is null   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
            group by  SiteName, AndroidFormNo,UpdatedDate  
                    
            UNION ALL  
                            
            SELECT SiteName,    AndroidFormNo, '' AS MobileNo, 
            0 AS Locked, 0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 
            0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM NewVoterRegistration  
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
            and SurveyDate_2018 is null group by  SiteName, AndroidFormNo,UpdatedDate  
                    
            UNION ALL  
                    
            SELECT SiteName,   AndroidFormNo, '' AS MobileNo, 0 AS Locked, 
            0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 0 AS LockedButSurvey, 
            0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null  AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                    
            UNION ALL  
                            
            SELECT SiteName,   AndroidFormNo, '' AS MobileNo, 
            0 AS Locked, 0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 
            0 AS LockedButSurvey, 0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies
            FROM NewVoterRegistration 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' 
                            
            UNION ALL  
                            
            select SiteName,   AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate,
            0 AS LockedButSurvey, 0 AS SForms, count(dd.ExMob) - 1 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies 
            from (  select SiteName,    AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 
            0 AS LockedButSurvey, 0 AS SForms, ExMob from (  SELECT SiteName,   
            AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  
            FROM Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                            
            UNION ALL  
                            
            SELECT SiteName,    AndroidFormNo, '' AS MobileNo, 
            0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  FROM NewVoterRegistration  
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' ) as d   
            where (d.ExMob IS not NULL and  d.ExMob <> '')  
            group by d.AndroidFormNo,d.SiteName,d.ExMob,d.Locked,d.WMob,d.BirthDate,d.LockedButSurvey,d.SForms  ) 
            as dd group by dd.AndroidFormNo,dd.SiteName,dd.Locked,dd.WMob,dd.BirthDate,dd.LockedButSurvey,
            dd.SForms 
                            
            UNION ALL  
                            
            select SiteName,MAX(AndroidFormNo) as AndroidFormNo,MAX(MobileNo) 
            as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,MAX(LockedButSurvey) as LockedButSurvey,
            count(SForms) as SForms ,sum(ExMob) as  ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies from (  select SiteName,MAX(AndroidFormNo) 
            as AndroidFormNo,MAX(MobileNo) as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,
            MAX(LockedButSurvey) as LockedButSurvey,count(SForms) as SForms, sum(ExMob) as  ExMob 
            from (  select SiteName,  '' As AndroidFormNo, '' AS MobileNo, 
            0 AS Locked, 0 AS WMob,  0 AS BirthDate,0 AS LockedButSurvey,AndroidFormNo as SForms ,0 as ExMob  
            from Dw_VotersInfo where  SurveyDate_2018 is not null and SF = 1 AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                            
            union All 
                            
            select SiteName,'' As [AndroidFormNo],
            '' AS [MobileNo], 0 AS [Locked],0 AS [WMob],  0 AS [BirthDate],0 AS [LockedButSurvey],AndroidFormNo as [SForms] ,0 as ExMob  
            from NewVoterRegistration 
            where SurveyDate_2018 is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' ) as D group by SiteName,SForms)as D1 
            group by SiteName 
                    
            UNION ALL
                    
            SELECT SiteName,   AndroidFormNo, Mobileno, 0 AS Locked,  
            (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
            0 as ExMob, 1 AS Voter, 0 AS NonVoter, 0 AS Societies FROM Dw_VotersInfo 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                    
            UNION ALL
                    
            SELECT SiteName,   AndroidFormNo, Mobileno, 0 AS Locked,  
            (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
            0 as ExMob, 0 AS Voter, 1 AS NonVoter, 0 AS Societies FROM NewVoterRegistration 
            WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
            and SurveyDate_2018 is null
                    
            UNION ALL
                    
            SELECT SiteName,  '' AS AndroidFormNo, '' AS Mobileno, 0 AS Locked,  
            '' AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
            0 as ExMob, 0 AS Voter, 0 AS NonVoter, 1 AS Societies FROM Survey_Entry_Data..Society_Master 
            WHERE SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                    
        ) AS D2 
        WHERE COALESCE(SiteName,'') != ''
        GROUP BY SiteName,AndroidFormNo) as D3 
        group by SiteName";
                $getBData = sqlsrv_query($Conect, $Buildingquery); 
                
                if ($getBData == TRUE) { 
                    $row_count = sqlsrv_num_rows( $getBData ); 
                
                    while($rowB = sqlsrv_fetch_array($getBData, SQLSRV_FETCH_ASSOC)){
                        $Buildingcountdata[] = $rowB;
                    }
                }
                // print_r($Buildingcountdata);
                $ExecutiveCount= array();

            foreach($Buildingcountdata as $Key=>$v){
                if($v['Voter'] != 0 || $v['Rooms'] != 0){
                $site = $v['SiteName'];
                $ExeQuery = "SELECT t1.SiteName,COUNT(*) As Executive
                    from
                    (Select DISTINCT(UpdateByUser) AS Executive,SiteName FROM Dw_VotersInfo 
                    where CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate'  AND SiteName = '$site' 
                    Union 
                    SELECT DISTINCT(UpdateByUser) AS Executive,SiteName FROM NewVoterRegistration 
                    WHERE CONVERT(varchar,UpdatedDate,23)  BETWEEN '$fromdate' AND '$todate'AND SiteName = '$site') as t1
                    GROUP BY t1.SiteName";
                 $getExeData = sqlsrv_query($Conect, $ExeQuery); 
                
                 if ($getExeData == TRUE) { 
                     $row_count = sqlsrv_num_rows( $getExeData ); 
                 
                     while($rowExe = sqlsrv_fetch_array($getExeData, SQLSRV_FETCH_ASSOC)){

                         $ExecutiveCount[] = $rowExe;
                     }
                 }
            }
            }
            // print_r($ExecutiveCount);
// ------------------------------------------------End Election Wise Site Data------------------------------------------------------//
// ------------------------------------------------Execuitve Wise Society Data------------------------------------------------------//
    $ExecutiveWiseQuery = "SELECT Edate,Name,soc_Cd,SocietyName + '(F-'+CONVERT(varchar,Floor)+'/R-'+CONVERT(varchar,Rooms) +')' as SocietyName,
                V,NV,Locked  ,BirthDate,LBS,RLG,SubLocation_Cd,survey_soc,Servey 
                from 
                    (SELECT CONVERT(date, Edate, 102) AS Edate, UPPER(UpdateByUser)  as Name,soc_Cd,SocietyName, SUM(Voter) AS V, 
                SUM(NVoter) AS NV , SUM(Locked) AS Locked, SUM(BirthDate) AS BirthDate, SUM(LockedButSurvey) AS LBS, 
                Sum(Religion) as RLG 
                FROM 
                (SELECT UpdateByUser,SubLocation_Cd as soc_Cd , SocietyName, 
                CONVERT(date, UpdatedDate, 102) AS Edate, 1 As Voter, 0 As NVoter, 0 AS Locked, 
                (CASE WHEN (BirthDate IS NOT NULL AND BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 
                (CASE WHEN (LockedButSurvey IS NOT NULL AND LockedButSurvey <> '') THEN 1 ELSE 0 END) AS LockedButSurvey, 
                (CASE WHEN (religion IS NOT NULL AND religion <> '') THEN 1 ELSE 0 END) AS Religion  
                FROM  Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') AND UpdateByUser = '$Executive' AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' 
                UNION ALL 
                SELECT UpdateByUser, Subloc_cd as soc_Cd,SocietyName, CONVERT(date, UpdatedDate, 102) AS Edate, 0 As Voter, 
                1 As NVoter, 0 AS Locked, (CASE WHEN (BirthDate IS NOT NULL AND BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 
                (CASE WHEN (LockedButSurvey IS NOT NULL AND LockedButSurvey <> '') THEN 1 ELSE 0 END) AS LockedButSurvey, 
                (CASE WHEN (religion IS NOT NULL AND religion <> '') THEN 1 ELSE 0 END) AS Religion  
                FROM NewVoterRegistration 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') AND UpdateByUser = '$Executive'AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                UNION ALL 
                SELECT UpdateByUser,Sublocation_Cd as soc_Cd,SocietyName, CONVERT(date, UpdatedDate, 102) AS Edate, 0 As Voter, 
                0 As NVoter, 1 AS Locked, '' AS BirthDate,'' AS LockedButSurvey,'' AS  Religion 
                FROM LockRoom WHERE (Locked = 1) AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate')D 
                WHERE UpdateByUser = '$Executive'
                GROUP BY UpdateByUser, soc_Cd,SocietyName, Edate ) as d 
                left join 
                (select s.SubLocation_Cd,s.SocietyName as survey_soc,ss.Servey,ss.Floor,ss.Rooms 
                from SubLocationMaster as s 
                inner join [Survey_Entry_Data]..Society_Master as ss on s.Survey_Society_Cd = ss.Society_Cd) 
                as dd on d.soc_Cd = dd.SubLocation_Cd 
                ORDER BY d.Edate Desc, d.Name";
                $getExecutiveData = sqlsrv_query($Conect, $ExecutiveWiseQuery); 
                
                if ($getExecutiveData == TRUE) { 
                    $row_count = sqlsrv_num_rows( $getExecutiveData ); 
                
                    while($rowExecutive = sqlsrv_fetch_array($getExecutiveData, SQLSRV_FETCH_ASSOC)){
                        $OverallExecutivedata[] = $rowExecutive;
                    }
                }
// ------------------------------------------------End Execuitve Wise Society Data------------------------------------------------------//  
// ------------------------------------------------Site Wise Society Data------------------------------------------------------//              
                    $SiteWiseQuery = "SELECT * FROM (
                        SELECT   D3.SiteName, D3.SocietyName, MAX(sm.Rooms) AS TotalRoom,  SUM(D3.Rooms) AS Rooms, SUM(Mob) AS Mob, SUM(WMob) AS WMob, 
                        SUM(D3.Locked) AS Locked, 
                            SUM(BirthDate) AS BirthDate,  sum(LBS) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob , sum(Voter) AS Voter, 
                            sum(NonVoter) AS NonVoter, SUM(Societies) AS Societies  
                            FROM 
                                ( 
                                SELECT  SiteName, SocietyName, MAX(SubLocation_Cd) AS SubLocation_Cd, 
                        ((COUNT(DISTINCT AndroidFormNo) - SUM(DISTINCT CASE WHEN AndroidFormNo = 0 THEN 1 ELSE 0 END))- sum(SForms)) -  
                        max(LockedButSurvey)   AS Rooms,  COUNT(DISTINCT MobileNo) - SUM(DISTINCT CASE WHEN MobileNo IS NULL OR  MobileNo = '' THEN 1 
                        ELSE 0 END) AS Mob,  SUM(CASE WHEN WMob = 1 AND Locked = 0 THEN 1 ELSE 0 END) AS WMob, SUM(Locked) AS 
                        Locked, SUM(BirthDate) AS BirthDate,  max(LockedButSurvey) AS LBS,sum(SForms) as SForms , sum(ExMob) as ExMob , sum(Voter) AS Voter, 
                        sum(NonVoter) AS NonVoter, SUM(Societies) AS Societies 
                        FROM 
                        ( 
                                        
                        SELECT SiteName, SocietyName, SubLocation_Cd, AndroidFormNo,  MobileNo, 0 AS Locked, 
                        (CASE WHEN (MobileNo IS NULL OR  MobileNo = '') THEN 1 ELSE 0 END) AS WMob,0 AS BirthDate, 0 AS LockedButSurvey, 
                        0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies FROM Dw_VotersInfo 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' 
                                        
                        UNION ALL 
                                        
                        SELECT SiteName,SocietyName, Subloc_cd AS SubLocation_Cd,  AndroidFormNo, Mobileno, 0 AS Locked,  
                        (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
                        0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM NewVoterRegistration 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                
                        UNION ALL  
                                
                        SELECT SiteName,SocietyName, SubLocation_Cd,   0 AS AndroidFormNo, '' AS MobileNo, 1 AS Locked, 
                        0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey , 0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies 
                        FROM LockRoom WHERE (Locked = 1)   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                
                        UNION ALL  
                                
                        SELECT SiteName,SocietyName, SubLocation_Cd,  AndroidFormNo, '' AS MobileNo, 0 AS Locked, 
                        0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 0 AS SForms ,
                        0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM Dw_VotersInfo 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and SurveyDate_2018 is null   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                        group by  SiteName,SocietyName, SubLocation_Cd, AndroidFormNo,UpdatedDate  
                                
                        UNION ALL  
                                        
                        SELECT SiteName, SocietyName, Subloc_cd AS SubLocation_Cd,   AndroidFormNo, '' AS MobileNo, 
                        0 AS Locked, 0 AS WMob, 0 AS BirthDate,  (CASE WHEN (MAX(LEN (LockedButSurvey)) > 0 ) THEN 1 ELSE 0 END) AS LockedButSurvey, 
                        0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM NewVoterRegistration  
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                        and SurveyDate_2018 is null group by  SiteName,SocietyName, Subloc_cd, AndroidFormNo,UpdatedDate  
                                
                        UNION ALL  
                                
                        SELECT SiteName,SocietyName, SubLocation_Cd,   AndroidFormNo, '' AS MobileNo, 0 AS Locked, 
                        0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 0 AS LockedButSurvey, 
                        0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies  FROM Dw_VotersInfo 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null  AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                
                        UNION ALL  
                                        
                        SELECT SiteName, SocietyName, Subloc_cd AS SubLocation_Cd,  AndroidFormNo, '' AS MobileNo, 
                        0 AS Locked, 0 AS WMob,  (CASE WHEN (BirthDate IS NOT NULL AND  BirthDate <> '') THEN 1 ELSE 0 END) AS BirthDate, 
                        0 AS LockedButSurvey, 0 AS SForms ,0 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies
                        FROM NewVoterRegistration 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' 
                                        
                        UNION ALL  
                                        
                        select SiteName,SocietyName, SubLocation_Cd,   AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate,
                        0 AS LockedButSurvey, 0 AS SForms, count(dd.ExMob) - 1 as ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies 
                        from (  select SiteName, SocietyName, SubLocation_Cd,   AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 
                        0 AS LockedButSurvey, 0 AS SForms, ExMob from (  SELECT SiteName, SocietyName, SubLocation_Cd,  
                        AndroidFormNo, '' AS MobileNo, 0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  
                        FROM Dw_VotersInfo 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                        
                        UNION ALL  
                                        
                        SELECT SiteName, SocietyName, Subloc_cd AS SubLocation_Cd,   AndroidFormNo, '' AS MobileNo, 
                        0 AS Locked, 0 AS WMob,  0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms,MobileNo as ExMob  FROM NewVoterRegistration  
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') and (MobileNo IS not NULL and  MobileNo <> '')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' ) as d   
                        where (d.ExMob IS not NULL and  d.ExMob <> '')  
                        group by d.AndroidFormNo,d.SiteName,d.SocietyName, SubLocation_Cd,d.ExMob,d.Locked,d.WMob,d.BirthDate,d.LockedButSurvey,d.SForms  ) 
                        as dd group by dd.AndroidFormNo,dd.SiteName,dd.SocietyName,SubLocation_Cd,dd.Locked,dd.WMob,dd.BirthDate,dd.LockedButSurvey,
                        dd.SForms 
                                        
                        UNION ALL  
                                        
                        select SiteName,SocietyName, SubLocation_Cd,MAX(AndroidFormNo) as AndroidFormNo,MAX(MobileNo) 
                        as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,MAX(LockedButSurvey) as LockedButSurvey,
                        count(SForms) as SForms ,sum(ExMob) as  ExMob, 0 AS Voter, 0 AS NonVoter, 0 AS Societies from (  select SiteName,SocietyName, SubLocation_Cd,MAX(AndroidFormNo) 
                        as AndroidFormNo,MAX(MobileNo) as MobileNo,MAX(Locked) as Locked,  MAX(WMob) as WMob,MAX(BirthDate) as BirthDate,
                        MAX(LockedButSurvey) as LockedButSurvey,count(SForms) as SForms, sum(ExMob) as  ExMob 
                        from (  select SiteName,SocietyName, SubLocation_Cd,  '' As AndroidFormNo, '' AS MobileNo, 
                        0 AS Locked, 0 AS WMob,  0 AS BirthDate,0 AS LockedButSurvey,AndroidFormNo as SForms ,0 as ExMob  
                        from Dw_VotersInfo where  SurveyDate_2018 is not null and SF = 1 AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                        
                        union All 
                                        
                        select SiteName,SocietyName, Subloc_cd AS SubLocation_Cd,'' As [AndroidFormNo],
                        '' AS [MobileNo], 0 AS [Locked],0 AS [WMob],  0 AS [BirthDate],0 AS [LockedButSurvey],AndroidFormNo as [SForms] ,0 as ExMob  
                        from NewVoterRegistration 
                        where SurveyDate_2018 is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate' ) as D group by SiteName,SocietyName, SubLocation_Cd,SForms)as D1 
                        group by SiteName ,SocietyName, SubLocation_Cd
                                
                        UNION ALL
                                
                        SELECT SiteName,SocietyName, SubLocation_Cd,   AndroidFormNo, Mobileno, 0 AS Locked,  
                        (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
                        0 as ExMob, 1 AS Voter, 0 AS NonVoter, 0 AS Societies FROM Dw_VotersInfo 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')   AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                
                        UNION ALL
                                
                        SELECT SiteName, SocietyName, Subloc_cd AS SubLocation_Cd,  AndroidFormNo, Mobileno, 0 AS Locked,  
                        (CASE WHEN (MobileNo IS NULL OR MobileNo = '') THEN 1 ELSE 0 END) AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
                        0 as ExMob, 0 AS Voter, 1 AS NonVoter, 0 AS Societies FROM NewVoterRegistration 
                        WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')  AND SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                        and SurveyDate_2018 is null
                                
                        UNION ALL
                                
                        SELECT SiteName, SocietyName, 0 SubLocation_Cd, '' AS AndroidFormNo, '' AS Mobileno, 0 AS Locked,  
                        '' AS WMob, 0 AS BirthDate, 0 AS LockedButSurvey, 0 AS SForms ,
                        0 as ExMob, 0 AS Voter, 0 AS NonVoter, 1 AS Societies FROM Survey_Entry_Data..Society_Master 
                        WHERE SiteName is not null AND CONVERT(varchar, UpdatedDate, 23) BETWEEN '$fromdate' AND '$todate'
                                
                    ) AS D2 
                    WHERE SiteName = '$Site_Nmae'
                    GROUP BY SiteName,SocietyName,AndroidFormNo
        
                    ) as D3 
                    INNER JOIN SubLocationMaster AS sbm ON (sbm.SubLocation_Cd = D3.SubLocation_Cd)
                    INNER JOIN Survey_Entry_Data..Society_Master AS sm ON (sm.Society_Cd = sbm.Survey_Society_Cd)
                    group by D3.SiteName, D3.SocietyName
                    ) AS D4
                    WHERE D4.Rooms <> 0;";
                $getSiteData = sqlsrv_query($Conect, $SiteWiseQuery); 
                
                if ($getSiteData == TRUE) { 
                    $row_count = sqlsrv_num_rows( $getSiteData ); 
                
                    while($rowSite = sqlsrv_fetch_array($getSiteData, SQLSRV_FETCH_ASSOC)){
                        $OverallSitedata[] = $rowSite;
                    }
                }
// ------------------------------------------------End Site Wise Society Data------------------------------------------------------//  
// ------------------------------------------------Society Data------------------------------------------------------//              
               $SocietyQuery = "SELECT ROW_NUMBER() OVER(ORDER BY Edate Desc) AS SrNo, Sitename, UPPER(UpdateByUser)  as Name, Edate  as Date, 
                CONVERT(nvarchar(50),Ac_No) + ' / ' + CONVERT(nvarchar(50),List_No) + ' / ' + CONVERT(nvarchar(50),Voter_Id) AS CorpNo, SocietyName, RoomNo, FullName, Age, 
                Sex, Birthdate, MobileNo, FormNo, District, Religion, LBS 
                FROM 
                (SELECT Sitename, UpdateByUser, Ac_No, List_No, Voter_Id, SocietyName, UpdatedDate AS Edate, 
                RoomNo, FullName, Age, Sex, Birthdate, MobileNo, AndroidFormNo as FormNo, District, Religion, LockedButSurvey AS LBS 
                FROM  Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') AND SocietyName = '$Society_Name' 
                UNION ALL 
                SELECT Sitename, UpdateByUser, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, SocietyName, UpdatedDate AS Edate, RoomNo, FullName, Age, Sex, Birthdate, MobileNo, 
                AndroidFormNo as FormNo, District, Religion, LockedButSurvey AS LBS 
                FROM NewVoterRegistration 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') AND SocietyName = '$Society_Name' 
                UNION ALL 
                SELECT Sitename, UpdateByUser, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, SocietyName, UpdatedDate AS Edate, RoomNo, 'LOCKED' AS FullName, '' AS Age, '' AS Sex, 
                '' AS Birthdate, '' AS MobileNo, '' AS FormNo, '' AS District, '' AS Religion, '' AS LBS 
                FROM LockRoom 
                WHERE (Locked = 1) AND SocietyName = '$Society_Name' )D 
                WHERE CONVERT(date, Edate, 23) BETWEEN '$fromdate' AND '$todate'
                ORDER BY Edate Desc, UpdateByUser, SocietyName, RoomNo";
            $getSocietyData = sqlsrv_query($Conect, $SocietyQuery); 
            
            if ($getSocietyData == TRUE) { 
                $row_count = sqlsrv_num_rows( $getSocietyData ); 
            
                while($rowS = sqlsrv_fetch_array($getSocietyData, SQLSRV_FETCH_ASSOC)){
                    $OverallSocietydata[] = $rowS;
                }
            }
// ------------------------------------------------End Society Data------------------------------------------------------//               

                }             
            }
      }
    //   print_r($OverallSocietydata);
      ?>
      <!-- --------------------------------------------------------Election Data------------------------------------------------------ -->
      <div class="row match-height">
        <div class="col-md-12">
            <div class="card"> 
                <!-- <div class="card-header">
                    <h4 class="card-title"></h4>
                </div> -->
             <div class="content-body">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="fromdate" value="<?php echo $fromdate; ?>"  class="form-control" placeholder="From Date" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="todate" value="<?php echo $todate; ?>"  class="form-control" placeholder="To Date" >
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-3 col-md-3 col-xl-3 text-center">
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="controls text-center">
                                        <button type="button" class="btn btn-primary" onclick="GetFromAndToDate()" >
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
    </div>
    <div id='DateLoaderDiv' style='display:none'>
                <center>
                    <img src='app-assets/images/loader/loading.gif' width="80" height="70"/>
                </center>
            </div>  
      <div class="row" id="OverallSummry">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Election Summary</h5>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ElectionSummaryTable')">Excel</button>
                </div>
                <div class="card-content" style="padding:5px;">
                    <div class="table-responsive">
                        <table class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id="ElectionSummaryTable">
                            <thead>
                                <tr>
                                    <th style="background-color:#36abb9;color:white;">SrNo</th>
                                    <th style="background-color:#36abb9;color:white;">ElectionName</th>
                                    <th style="background-color:#36abb9;color:white;">Sites (<?php echo $TotalSites; ?>)</th>
                                    <th style="background-color:#36abb9;color:white;">Executive</th>
                                    <th style="background-color:#36abb9;color:white;">Listing (<?php echo $TotalSocieties; ?>)</th>
                                    <th style="background-color:#36abb9;color:white;">Voters (<?php echo $TotalVoters; ?>)</th>
                                    <th style="background-color:#36abb9;color:white;">NonVoters (<?php echo $TotalNonVoters; ?>)</th>
                                    <th style="background-color:#36abb9;color:white;">Lockroom (<?php echo $TotalLockRooms; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $SrNo = 0;
                                foreach($MainTableDataArray as $key=>$val){
                                    $SrNo++;
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $SrNo; ?></th>
                                    <th style="color:blue;"><a onclick="getelectionData('<?php echo $val['ElectionName']; ?>')"><?php echo $val['ElectionName']; ?></a></th>
                                    <td><?php echo $val['TotalSites']; ?></td>
                                    <td><?php echo $val['TotalExecutive']; ?></td>
                                    <td><?php echo $val['Listing']; ?></td>
                                    <!-- <td><?php //echo $val['TotalSocieties']; ?></td> -->
                                    <td><?php echo $val['TotalVoters']; ?></td>
                                    <td><?php echo $val['TotalNonVoters']; ?></td>
                                    <td><?php echo $val['TotalLockRooms']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="ElectionwiseDataMainDiv" <?php if($Election != ''){ echo "style='display:block;'";}else{ echo "style='display:none;'";} ?>>
    <div id='LoaderDiv' style='display:none;item-align:center;'>
        <center>
            <img src='app-assets/images/loader/loading.gif' width="80" height="70"/>
        </center>
    </div>  
    <br>
    <div class="card" >
            <div class="card-header" style="padding:8px;">
                <h5 class="card-title"><?php echo $Election." Summary Report"?></h5>
            </div>
        </div>
        <button class="btn btn-outline-info mr-1 mb-1" onclick="loadBuildingdiv()" style="padding:5px;margin-left:10px;">Building</button> &nbsp;&nbsp;<button class="btn btn-outline-info mr-1 mb-1" style="padding:5px;"  onclick="loadExecutivediv()">Executive</button>
        <!-- <div class="col-md-12 mb-md-4 mb-3"> -->
<!-- --------------------------------------------------------Election Wise Site Data------------------------------------------------------ -->
            <div class="row" id="BuildingwiseData" >
                <div class="col-12">
                    <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Building Summary</h5>
                                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','BuildingSummaryTable')">Excel</button>
                                </div>
                        <div class="card-content" style="padding:5px;">
                            <div class="table-responsive">
                                <table class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id="BuildingSummaryTable">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color:white;">SrNo</th>
                                            <th style="background-color:#36abb9;color:white;">SiteName</th>
                                            <th style="background-color:#36abb9;color:white;">Listing</th>
                                            <th style="background-color:#36abb9;color:white;">Execuitve</th>
                                            <th style="background-color:#36abb9;color:white;">Voters</th>
                                            <th style="background-color:#36abb9;color:white;">Nonvoters</th>
                                            <th style="background-color:#36abb9;color:white;">Lockroom</th>
                                            <th style="background-color:#36abb9;color:white;">Birthday</th>
                                            <th style="background-color:#36abb9;color:white;">MobileNo</th>
                                            <th style="background-color:#36abb9;color:white;">WMobNo</th>
                                            <th style="background-color:#36abb9;color:white;">LBS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $SrNo = 0;
                                        foreach($Buildingcountdata as $key=>$valB){
                                            if($valB['Voter'] != 0 || $valB['Rooms'] != 0){
                                            $SrNo++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $SrNo; ?></th>
                                            <th scope="row" style="color:blue;"><a onclick="GetSiteWiseData('<?php echo $valB['SiteName']; ?>')"><?php echo $valB['SiteName']; ?></a></th>
                                            <td><?php echo $valB['Societies']; ?></td>
                                            <td><?php foreach($ExecutiveCount As $key=>$valExe){
                                                if($valExe['SiteName'] == $valB['SiteName']){
                                                echo $valExe['Executive'];
                                                } 
                                            } 
                                            ?></td>
                                            <td><?php echo $valB['Voter']; ?></td>
                                            <td><?php echo $valB['NonVoter']; ?></td>
                                            <td><?php echo $valB['Locked']; ?></td>
                                            <td><?php echo $valB['BirthDate']; ?></td>
                                            <td><?php echo $valB['Mob']; ?></td>
                                            <td><?php echo $valB['WMob']; ?></td>
                                            <td><?php echo $valB['LBS']; ?></td>
                                        </tr>
                                        <?php }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
        <!-- <div class="col-md-12 mb-md-4 mb-3"> -->
<!-- --------------------------------------------------------Election Wise Executive Data------------------------------------------------------ -->
            <div class="row" id="ElectionwiseData" style="display:none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Executive Summary</h5>
                            <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ExecutiveSummaryTable')">Excel</button>
                        </div>
                        <div class="card-content" style="padding:5px;">
                            <div class="table-responsive">
                                <table class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id="ExecutiveSummaryTable">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color:white;">SrNo</th>
                                            <th style="background-color:#36abb9;color:white;">SiteName</th>
                                            <th style="background-color:#36abb9;color:white;">ExecutiveName</th>
                                            <th style="background-color:#36abb9;color:white;">RoomsDone</th>
                                            <th style="background-color:#36abb9;color:white;">Voters</th>
                                            <th style="background-color:#36abb9;color:white;">Nonvoters</th>
                                            <th style="background-color:#36abb9;color:white;">Lockroom</th>
                                            <th style="background-color:#36abb9;color:white;">Birthday</th>
                                            <th style="background-color:#36abb9;color:white;">MobileNo</th>
                                            <th style="background-color:#36abb9;color:white;">WMobNo</th>
                                            <th style="background-color:#36abb9;color:white;">LBS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $SrNo = 0;
                                            foreach($OverallEdata as $key=>$val1){
                                                $SrNo++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $SrNo; ?></th>
                                            <th scope="row"><?php echo $val1['SiteName']; ?></th>
                                            <td style="color:blue;"><a onclick="GetExecutiveWiseData('<?php echo $val1['Name']; ?>')"><?php echo $val1['Name']; ?></a></td>
                                            <td><?php echo $val1['Rooms']; ?></td>
                                            <td><?php echo $val1['Voters']; ?></td>
                                            <td><?php echo $val1['NonVoters']; ?></td>
                                            <td><?php echo $val1['Locked']; ?></td>
                                            <td><?php echo $val1['BirthDate']; ?></td>
                                            <td><?php echo $val1['Mob']; ?></td>
                                            <td><?php echo $val1['WMob']; ?></td>
                                            <td><?php echo $val1['LBS']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    <!-- </div> -->
        <!-- <div class="col-md-12 mb-md-4 mb-3"> -->
<!-- --------------------------------------------------------Executive Wise Society Data------------------------------------------------------ -->
            <div id='ExecutiveLoaderDiv' style='display:none'>
                <center>
                    <img src='app-assets/images/loader/loading.gif' width="80" height="70"/>
                </center>
            </div>   
            <div class="row" id="ExecutiviWise" <?php if($Executive != ''){echo "style='display:block;'";}else{echo "style='display:none;'";} ?>>
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                            <h5 class="card-title">Executive Wise</h5>
                            <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ExecutiveWiseTable')">Excel</button>
                        </div>
                        <div class="card-content"  style="padding:5px;">
                            <div class="table-responsive">
                                <table id="ExecutiviWiseTable" class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id="ExecutiveWiseTable">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color:white;">SrNo</th>
                                            <th style="background-color:#36abb9;color:white;">Date</th>
                                            <th style="background-color:#36abb9;color:white;">ExecutiveName</th>
                                            <th style="background-color:#36abb9;color:white;">SocietyName</th>
                                            <th style="background-color:#36abb9;color:white;">Voters</th>
                                            <th style="background-color:#36abb9;color:white;">Nonvoters</th>
                                            <th style="background-color:#36abb9;color:white;">LockRoom</th>
                                            <th style="background-color:#36abb9;color:white;">BirthDate</th>
                                            <th style="background-color:#36abb9;color:white;">LBS</th>
                                            <!-- <th style="background-color:#36abb9;color:white;">Lockroom</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $SrNo = 0;
                                        foreach($OverallExecutivedata as $key=>$valExe){
                                            $SrNo++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $SrNo; ?></th>
                                            <th scope="row"><?php echo date_format($valExe['Edate'],"Y/m/d"); ?></th>
                                            <th scope="row"><?php echo $valExe['Name']; ?></th>
                                            <td style="color:blue;"><a onclick="GetSocietyWiseData('<?php echo $valExe['survey_soc']; ?>')"><?php echo $valExe['SocietyName']; ?></a></td>
                                            <td><?php echo $valExe['V']; ?></td>
                                            <td><?php echo $valExe['NV']; ?></td>
                                            <td><?php echo $valExe['Locked']; ?></td>
                                            <td><?php echo $valExe['BirthDate']; ?></td>
                                            <td><?php echo $valExe['LBS']; ?></td>
                                            <!-- <td><?php //echo $valExe['Locked']; ?></td> -->
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
<!-- --------------------------------------------------------Site Wise Society Data------------------------------------------------------ -->
        <div class="row" id="SocietyWiseData" <?php if($Site_Nmae != ''){echo "style='display:block;'";}else{echo "style='display:none;'";} ?>>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Site Detail</h5>
                        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteDetailTable')">Excel</button>
                    </div>
                    <div class="card-content"  style="padding:5px;">
                        <div class="table-responsive">
                            <table class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id="SiteDetailTable">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>SiteName</th>
                                        <th>SocietyName</th>
                                        <th>Rooms</th>
                                        <th>RoomDone</th>
                                        <th>Lockroom</th>
                                        <th>Voters</th>
                                        <th>NonVoters</th>
                                        <th>Mob</th>
                                        <th>WMob</th>
                                        <th>BirthDate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $SrNo = 0;

                                    foreach($OverallSitedata as $key=>$valS){
                                        $SrNo++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $SrNo; ?></th>
                                        <th scope="row"><?php echo $valS['SiteName']; ?></th>
                                        <th scope="row" style="color:blue;"><a onclick="GetSocietyWiseData('<?php echo $valS['SocietyName']; ?>')"><?php echo $valS['SocietyName']; ?></a></th>
                                        <td><?php echo $valS['TotalRoom']; ?></td>
                                        <td><?php echo $valS['Rooms']; ?></td>
                                        <td><?php echo $valS['Locked']; ?></td>
                                        <td><?php echo $valS['Voter']; ?></td>
                                        <td><?php echo $valS['NonVoter']; ?></td>
                                        <td><?php echo $valS['Mob']; ?></td>
                                        <td><?php echo $valS['WMob']; ?></td>
                                        <td><?php echo $valS['BirthDate']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- --------------------------------------------------------Society Data------------------------------------------------------ -->
        <div id='SocietyLoaderDiv' style='display:none'>
            <center>
                <img src='app-assets/images/loader/loading.gif' width="80" height="70"/>
            </center>
        </div>           
        <div class="row" id="SocietyNameWise" <?php if($Society_Name != ''){echo "style='display:block;'";}else{echo "style='display:none;'";} ?>>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Society Detail</h5>
                        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SocietyDetailTable')">Excel</button>
                    </div>
                    <div class="card-content" style="padding:5px;">
                        <div class="table-responsive">
                            <table class="table zero-configuration table-striped table-bordered complex-headers" style="padding:10px;" id ="SocietyDetailTable">
                                <thead>
                                    <tr>
                                        <th>SrNo</th>
                                        <th>ExecutiveName</th>
                                        <th>Date</th>
                                        <th>SocietyName</th>
                                        <th>RoomNo</th>
                                        <th>FullName</th>
                                        <th>BirthDate</th>
                                        <th>Mobile</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $SrNo = 0;

                                    foreach($OverallSocietydata as $key=>$valS){
                                        $SrNo++;
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $SrNo; ?></th>
                                        <th scope="row"><?php echo $valS['Name']; ?></th>
                                        <td><?php echo date_format($valS['Date'],"Y/m/d H:i"); ?></td>
                                        <th scope="row"><?php echo $valS['SocietyName']; ?></th>
                                        <td><?php echo $valS['RoomNo']; ?></td>
                                        <td><?php echo $valS['FullName']; ?></td>
                                        <td><?php if($valS['Birthdate'] != ''){ echo date_format($valS['Birthdate'],"Y/m/d");}else{
                                            echo $valS['Birthdate'];
                                        } ?></td>
                                        <td><?php echo $valS['MobileNo']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</section>        
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
        <script>
            function ExportToExcel(type,TableID) {
                var fn = "";
                var dl = "";
                var elt = document.getElementById(TableID);
                var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
                return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                    XLSX.writeFile(wb, fn || (TableID+'.'+ (type || 'xlsx')));
            }
            </script>
            <?php
             sqlsrv_close($Conect); ?>