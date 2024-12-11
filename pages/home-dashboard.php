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
        $previousdate = date('Y-m-d', strtotime('-7 days'));
        $fromDate = $currentDate." ".$_SESSION['StartTime'];
        $toDate =$currentDate." ".$_SESSION['EndTime'];


        $election_Cd_ofDashboard = $electionCd;
        $electionName_of_Dashboard = $electionName;
		// $dataElectionName = $db->getSurveyUtilityCorporationElectionData($userName, $appName, $developmentMode);
	  
		//   if(isset($_SESSION['SurveyUA_Election_Cd']) &&
		//   isset($_SESSION['SurveyUA_ElectionName'])){
		// 	$election_Cd_ofDashboard = $_SESSION['SurveyUA_Election_Cd'];
		// 	$electionName_of_Dashboard = $_SESSION['SurveyUA_ElectionName'];
        //     $_SESSION['SurveyUA_Election_Cd'] = $_SESSION['SurveyUA_Election_Cd'];
        //     $_SESSION['SurveyUA_ElectionName'] = $_SESSION['SurveyUA_ElectionName'];
		//   }else{
		// 	$election_Cd_ofDashboard = $dataElectionName[0]['Election_Cd'];
		// 	$electionName_of_Dashboard = $dataElectionName[0]['ElectionName'];
		// 	$_SESSION['SurveyUA_Election_Cd'] = $election_Cd_ofDashboard;
		// 	$_SESSION['SurveyUA_ElectionName'] = $electionName_of_Dashboard;
        //     $_SESSION['SurveyUA_Election_Cd'] = $_SESSION['SurveyUA_Election_Cd'];
        //     $_SESSION['SurveyUA_ElectionName'] = $_SESSION['SurveyUA_ElectionName'];
		//   }

        if(isset($_SESSION['FromDate_Society_Summary_Dashboard']) &&
        isset($_SESSION['ToDate_Society_Summary_Dashboard'])){
            $previousdate = $_SESSION['FromDate_Society_Summary_Dashboard'];
            $currentDate = $_SESSION['ToDate_Society_Summary_Dashboard'];
        }

        
        $CountDataTotal = array();
        $CountquerySitePocketSocieties = "SELECT
            (SELECT COUNT(Site_Cd) FROM Site_Master WHERE ElectionName = '$electionName_of_Dashboard')AS TotalSites,
            (SELECT COUNT(Pocket_Cd) FROM Pocket_Master WHERE ElectionName = '$electionName_of_Dashboard') AS TotalPockets ,
            (SELECT COUNT(Society_Cd) FROM Society_Master WHERE ElectionName = '$electionName_of_Dashboard')AS TotalSocieties,
            (SELECT COUNT(Executive_Cd) FROM User_Master WHERE AppName = '$appName' AND DbName = 'Survey_Entry_Data'
                AND DeactiveFlag IS NULL OR DeactiveFlag = '' AND Upload_SyncFlag = 1 AND Download_SyncFlag = 1 ) AS TotalExecutive";

        $CountDataTotalSitePocketSocieties = $db->ExecutveQuerySingleRowSALData($CountquerySitePocketSocieties , $userName, $appName, $developmentMode);
        // print_r($CountDataTotalSitePocketSocieties);

        $SocietyCountDataTotal = array();
        $SocietiesCountquery = "SELECT
                    (SELECT COUNT(Society_Cd) 
                    FROM Society_Master 
                    WHERE Executive_Cd IS NOT NULL AND Executive_Cd <> 0 
                    AND AssignedDate IS NOT NULL AND CONVERT(VARCHAR,AssignedDate ,105) = CONVERT(VARCHAR,GETDATE(),105)
                    AND ElectionName = '$electionName_of_Dashboard') AS TotalAssignedSocieties,
                    (SELECT COUNT(Society_Cd)
                    FROM Society_Master 
                    WHERE IsCompleted = 1
                    AND CompletedOn IS NOT NULL
                    AND (Executive_Cd IS NOT NULL OR Executive_Cd <> 0 )
                    AND AssignedDate IS NOT NULL AND CONVERT(VARCHAR,AssignedDate ,105) = CONVERT(VARCHAR,GETDATE(),105)
                    AND ElectionName = '$electionName_of_Dashboard') AS CompletedSocieties,
                    (SELECT COUNT(Society_Cd)
                    FROM Society_Master 
                    WHERE Executive_Cd IS NOT NULL AND Executive_Cd <> 0 
                    AND AssignedDate IS NOT NULL AND CONVERT(VARCHAR,AssignedDate ,105) = CONVERT(VARCHAR,GETDATE(),105)
                    AND (IsCompleted IS NULL OR IsCompleted <> 1 )
                    AND ElectionName = '$electionName_of_Dashboard') AS PendingSocieties;";

        $SocietyCountDataTotal = $db->ExecutveQuerySingleRowSALData($SocietiesCountquery , $userName, $appName, $developmentMode);

        $dbConn = $db->getSUA_DBConnectByElectionName_For_Voters_Non_Voters($userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["election_wise_conn"];
            if($conn){
               // echo "Connection Established";
            }
         
            $VoterNonVoterCount = array();
            $VoterNonVoterCountQuery = "SELECT
                    (SELECT COUNT(Voter_Cd) FROM Dw_VotersInfo WHERE SF = 1 AND CONVERT(VARCHAR,UpdatedDate,105) = CONVERT(VARCHAR,GETDATE(),105)) AS TotalVoters,
                    (SELECT COUNT(Voter_Cd) FROM NewVoterRegistration WHERE CONVERT(VARCHAR,UpdatedDate,105) = CONVERT(VARCHAR,GETDATE(),105)) AS TotalNonVoters,
                    (SELECT COUNT(LR_Cd) FROM LockRoom WHERE CONVERT(VARCHAR,UpdatedDate,105) = CONVERT(VARCHAR,GETDATE(),105)) AS TotalLockRooms";

        $getDetail = sqlsrv_query($conn, $VoterNonVoterCountQuery); 
        if ($getDetail == TRUE)  
            $row_count = sqlsrv_num_rows( $getDetail ); 
            $data = array();
            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                $TotalVoters =  $row['TotalVoters'];
                $TotalNonVoters =  $row['TotalNonVoters'];
                $TotalLockRooms =  $row['TotalLockRooms'];
                $data[] = $row;
                } 

    }



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

<!-- <div class="row match-height" id='LoaderBeforeLoadMainDataDIV' style="display:none;">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group  col-md-12 has-info" id="loaderId" style="text-align:center;">
                    <img src="app-assets/loader/loading.gif" width="50" height="30">
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="row match-height">
    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
		<div class="form-group">
			<label>Corporation</label>
			<div class="controls">
				<select class="select2 form-control" name="electionName" onChange="setElectionNameForDashboardInSession(this.value)" >
				<option value="">--Select--</option>
					 <?php
					if (sizeof($dataElectionName)>0) 
					{
						foreach ($dataElectionName as $key => $value) 
						  {
							  if($election_Cd_ofDashboard == $value["Election_Cd"])
							  {
					?>
								<option selected="true" value="<?php echo $value['Election_Cd']; ?>"><?php echo $value["ElectionName"]; ?></option>
					<?php
							  }
							  else
							  {
					?>
								<option value="<?php echo $value["Election_Cd"];?>"><?php echo $value["ElectionName"];?></option>
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


    <div class="row match-height">
        <div class="col-xl-6 col-md-6 col-12">
            <div class="card card-congratulation-medal">
                <div class="card-header">
                    <h5 class="card-title">Welcome <?php echo $_SESSION['SurveyUA_FullName'].' '; ?> !</h5>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0"></p>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-xl-0">
                            <div class="media">
                                <div class="bg-light-primary p-50  mr-3" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/sitiessvg.svg" alt="Sites" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($CountDataTotalSitePocketSocieties["TotalSites"]); ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Total Sites</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-3" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/pocketsvg.svg" alt="Pockets" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto">
                                    <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($CountDataTotalSitePocketSocieties["TotalPockets"]); ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Total Pockets</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0" style="margin-top: 30px;">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/socitetiessvg.svg" alt="Societies" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 30px;">
                                    <h4 class="font-weight-bolder mb-0">
                                        <?php echo IND_money_format($CountDataTotalSitePocketSocieties["TotalSocieties"]); ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Total Societies</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0" style="margin-top: 30px;">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/Executivessvg.svg" alt="Executives" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 30px;">
                                    <h4 class="font-weight-bolder mb-0"><?php echo IND_money_format($CountDataTotalSitePocketSocieties["TotalExecutive"]); ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Total Executives</p>
                                </div>
                            </div>
                        </div>



                  </div>
                </div>
            </div>
        </div>
      
   
        <div class="col-xl-6 col-md-6 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h5 class="card-title">Today's Summary</h5>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0"></p>
                    </div>
                </div>
                <div class="card-body statistics-body">
                    <div class="row"> 

                       
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/assignsvg.svg" alt="Assigned" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 20px;">
                                    <h4 class="font-weight-bolder mb-0"><?php echo $SocietyCountDataTotal["TotalAssignedSocieties"]; ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Assigned Societies</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/donesvg.svg" alt="Survey Done" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 20px;">
                                    <h4 class="font-weight-bolder mb-0">
                                        <?php echo $SocietyCountDataTotal["CompletedSocieties"]; ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Done Societies</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0" style="margin-top: 30px;">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/pendingsvg.svg" alt="Survey Pending" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 20px;">
                                    <h4 class="font-weight-bolder mb-0"><?php echo $SocietyCountDataTotal["PendingSocieties"]; ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Pending Societies</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 col-sm-12 col-12 mb-2 mb-sm-0" style="margin-top: 30px;">
                            <div class="media">
                                <div class="bg-light-danger p-50  mr-2" style="background-color:white;">
                                    <div class="avatar-content">
                                    <img src="app-assets/images/votersvg.svg" alt="Voters, Non- Voters, LockRooms" width="60" height="60">
                                    </div>
                                </div>
                                <div class="media-body my-auto" style="margin-left: 30px;">
                                    <h4 class="font-weight-bolder mb-0"><?php echo $TotalVoters.' | '.$TotalNonVoters.' | '.$TotalLockRooms; ?></h4>
                                    
                                    <p class="card-text font-small-4 mb-0">Voters | Non-Voters | Lockroom </p>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>


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

                                <!-- <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    
                                </div> -->
                                
                                <!-- <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <?php 
                                       // include 'dropdown-site-dropdown-dashboard.php'; 
                                    ?>
                                </div> -->

                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="fromdate" value="<?php echo $previousdate; ?>"  class="form-control" placeholder="From Date" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-3 col-xl-3 col-md-3 col-12">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="todate" value="<?php echo $currentDate; ?>"  class="form-control" placeholder="To Date" >
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-3 col-md-3 col-xl-3 text-center">
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="controls text-center">
                                        <button type="button" class="btn btn-primary" onclick="getAssignedSocietyData()" >
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
   
    <div id='spinnerLoader1' style='display:none'>
        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
    </div>
    <div class="row match-height" id="societySurveyExecutiveData">
        <?php include 'datatbl/tblGetAssignedSocietyDatabySite.php'; ?>
    </div>

<!-- 
    <div id='spinnerLoader' style='display:none'>
        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
    </div>
    <div class="row match-height" id="executiveLossofHrsData">
        
    </div>
 -->


    <?php

            //  $queryPkt = "SELECT
            //     ISNULL(pm.PocketCd,0) as PocketCd,
            //     ISNULL(pm.PocketName,'') as PocketName,
            //     ISNULL(pm.PocketNameMar,'') as PocketNameMar,
            //     ISNULL(pm.KMLFile_Url,'') as KMLFile_Url,
            //     ISNULL(pm.WardCd,0) as WardCd,
            //     ISNULL(pm.SRExecutiveCd,0) as SRExecutiveCd,
            //     ISNULL(convert(varchar,pm.SRAssignedDate,121),'') as SRAssignedDate,
            //     ISNULL(pm.IsCompleted,0) as IsCompleted,
            //     ISNULL(convert(varchar,pm.CompletedOn,121),'') as CompletedOn,
            //     ISNULL(wm.WardNameOrNum,'') as WardNameOrNum,
            //     ISNULL(wm.NodeName,'') as NodeName,
            //     ISNULL(wm.NodeAcronym,'') as NodeAcronym,
            //     ISNULL(em.ExecutiveName,'') as ExecutiveName,
            //     ISNULL(em.MobileNo,'') as MobileNo
            //     FROM PocketMaster pm
            //     LEFT JOIN WardMaster wm on wm.WardCd = pm.WardCd
            //     LEFT JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = pm.SRExecutiveCd
            //     WHERE pm.IsActive = 1
            // ";
            // $dbPktSummary=new DbOperation();
            
            // $dbPktAssgnSummary = $dbPktSummary->ExecutveQueryMultipleRowSALData($queryPkt, $electionCd, $electionName, $developmentMode);

    ?>
    <!-- <div class="row match-height" >
        
        <div class="col-xl-12 col-md-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                         Pocket Assign Status 
                    </h4>
                </div>
                    
                <div class="card-content">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Pocket</th>
                                        <th>Ward</th>
                                        <th>Node</th>
                                        <th>KML File</th>
                                        <th>Executive</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $srNo = 1;
                                        
                                        foreach ($dbPktAssgnSummary as $key => $value) {
                                        ?> 
                                            <tr>
                                                <td><?php echo $srNo++; ?></td>
                                                <td><?php echo $value["PocketName"]; ?></td>
                                                <td><?php echo $value["WardNameOrNum"]; ?></td>
                                                <td><?php echo $value["NodeName"]; ?></td>
                                                <td>
                                                    <?php 
                                                        if(!empty($value["KMLFile_Url"])){ echo "File Found!"; }else{ echo "Files Not Found!"; }
                                                    ?>
                                                </td>
                                                <td><?php echo $value["ExecutiveName"]."<br>".$value["MobileNo"]; ?></td>
                                                <td>
                                                    <?php 
                                                        if($value["SRExecutiveCd"] == 0 && ($value["IsCompleted"] == 0 || $value["IsCompleted"] == 1) ){ 
                                                            echo "";
                                                        }else if($value["SRExecutiveCd"] <> 0 && $value["IsCompleted"] == 0 ){ 
                                                            echo date('d/m/Y h:i a', strtotime($value["SRAssignedDate"]));
                                                        }else if($value["SRExecutiveCd"] <> 0 && $value["IsCompleted"] == 1 ){ 
                                                            echo date('d/m/Y h:i a', strtotime($value["CompletedOn"]));
                                                        } 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($value["SRExecutiveCd"] == 0 && $value["IsCompleted"] == 0 ){ ?>
                                                            <span class="badge badge-danger">Not Assigned</span>
                                                       <?php  }else if($value["SRExecutiveCd"] <> 0 && $value["IsCompleted"] == 0 ){ ?>
                                                            <span class="badge badge-warning">Assigned</span>
                                                       <?php  }else if( ($value["SRExecutiveCd"] <> 0 || $value["SRExecutiveCd"] == 0) && $value["IsCompleted"] == 1 ){  ?>
                                                            <span class="badge badge-success">Completed</span>
                                                      <?php  } ?>
                                                </td>
                                            </tr>
                                        <?php
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

    <div id='spinnerLoaderPckt' style='display:none'>
        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
    </div>
    <div class="row match-height" id="pocketAssignHistory">
            
    </div>

</section> -->
