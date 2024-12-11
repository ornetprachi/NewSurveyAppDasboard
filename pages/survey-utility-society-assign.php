
<section id="dashboard-analytics">

<?php
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];
        
        $from_Date = date('Y-m-d', strtotime('-7 days'));
        $to_Date = date('Y-m-d');
        $assignDate = date('Y-m-d');

        
        $Site_Cd = "";
        
        if(!isset($_SESSION['SurveyUA_FromDate'])){
            $_SESSION['SurveyUA_FromDate'] = $from_Date ;
        }else{
            $from_Date  = $_SESSION['SurveyUA_FromDate'];
        }

        if(!isset($_SESSION['SurveyUA_ToDate'])){
            $_SESSION['SurveyUA_ToDate'] = $to_Date;
        }else{
            $to_Date = $_SESSION['SurveyUA_ToDate'];
        }

        $fromDate = $from_Date." ".$_SESSION['StartTime'];
        $toDate =$to_Date." ".$_SESSION['EndTime'];

        $qcReportFilter="DateWise";
        if(isset($_SESSION['SurveyUA_QCReportFilter'])){
            $qcReportFilter=$_SESSION['SurveyUA_QCReportFilter'];
        }


        if(isset($_SESSION['SurveyUA_SiteCd_Society_Assign'])){
            $Site_Cd = $_SESSION['SurveyUA_SiteCd_Society_Assign'];
        }else{
            $Site_Cd = "";
            $_SESSION['SurveyUA_SiteCd_Society_Assign'] = $Site_Cd;
        }
?>


    <div class="row match-height">
        <div class="col-md-12">
             <div class="card">
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                        
                                <!-- <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                    <?php //include 'dropdown-electionname-society-assign.php'; ?>
                                </div> -->

                                
                                <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                    <?php include 'dropdown-site-society-assign.php'; ?>
                                </div>
                                <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                    <?php include 'dropdown-pocket-society-assign.php'; ?>
                                </div>

                                <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                    <div class="form-group">
                                        <label>Assign Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="assignDate" value="<?php echo $assignDate; ?>"  class="form-control" placeholder="Assign Date" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                    <?php include 'dropdown-executiveList-society-assign.php'; ?>
                                </div>

                                <div class="col-xs-12 col-xl-12 col-md-12 col-12" style="display: none;">
                                <!-- style="display: none;" -->
                                <label>Selected Societies</label>
                                    <input class="form-control" type="text" name="society_cds">
                                </div>

                                <div class="col-xs-12 col-md-2 col-xl-2 text-right">                    
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="controls text-right">
                                        <button id="submitSocietyAssignDataId" onclick="setAssignSocietyToExecutive()" type="submit" class="btn btn-primary">Assign </button>
                                    </div>
                                </div>
								<div class="col-xs-12 col-md-6 col-xl-6" >
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <br>
                                    <img id="loaderId2" style="display:none;" src="app-assets/loader/loading.gif" width="60" height="60">
                                    <span id="idAssignSocietyMsg" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignSocietyMsgSuccess" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignSocietyMsgFailure" class="btn btn-danger" style="display: none;"></span>
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
    <div class="row match-height">
            <?php include 'datatbl/tblSocietyAssign.php'; ?>
    </div>

</section>
