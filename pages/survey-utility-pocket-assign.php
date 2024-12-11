
<section id="dashboard-analytics">

<?php
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $dataElectionName = $db->getULBWiseAssemblyData($ULB, $userName, $appName, $developmentMode);
        // print_r($electionName);
        $from_Date = date('Y-m-d', strtotime('-7 days'));
        $to_Date = date('Y-m-d');
        $assignDate = date('Y-m-d');

        if(isset($_SESSION['SurveyUA_AcNo_Cd'])){
            $Ac_No = $_SESSION['SurveyUA_AcNo_Cd'];
        }else{
    
            $Ac_No = '';
        }
        
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
?>

   
   

    <div class="row match-height">
        <div class="col-md-12">
             <div class="card">
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                        
                                <!--<div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                    <?php //include 'dropdown-electionname.php'; ?>
                                </div>-->

                                <input type="hidden" name="executive_Name" value="All">

                                <div class="col-xl-2 col-md-2 col-12">
                                    <?php //include 'dropdown-electionname.php'; ?>
                                    <div class="form-group">
                                        <label>AcNo</label>
                                        <div class="controls">
                                            <select class="select2 form-control" name="Ac_No" id="Ac_No" onchange="setAssemblyInSession(this.value)" >
                                            <option selected="true" value="">Select</option>
                                                <?php
                                                if (sizeof($dataElectionName)>0) 
                                                {
                                                    foreach ($dataElectionName as $key) 
                                                    {
                                                        $acNos = json_decode($key['Ac_Nos'], true);
                                                        if(is_array($acNos))
                                                        {
                                                            foreach ($acNos as $key => $value) {
                                                                if($Ac_No == $value){
                                                ?>
                                                        <option selected value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                <?php
                                                            }else{
                                                ?>
                                                        <option  value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                <?php
                                                                }
                                                            }
                                                        }
                                                ?>
                                                <?php
                                                    }
                                                }
                                                ?> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                    <?php include 'dropdown-site.php'; ?>
                                </div>
                                <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                    <?php include 'dropdown-assign-pocket-name.php'; ?>
                                </div>

                                <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                    <div class="form-group">
                                        <label>Assign Date</label>
                                        <div class="controls"> 
                                            <input type="date" name="assignDate" value="<?php echo $assignDate; ?>"  class="form-control" placeholder="Assign Date" >
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-12 col-md-12 col-xl-12" >
                                    <span id="idAssignPocketMsg" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignPocketMsgSuccess" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignPocketMsgFailure" class="btn btn-danger" style="display: none;"></span>
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
            <?php include 'datatbl/tblGetAssignPocketExecSummaryData.php'; ?>
    </div>

</section>
<script>
   function setAssemblyInSession(acno) {
    // alert(acno);
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

    if (acno === '') {
        alert("Please Select acno!");
    } else {
        var queryString = "?assembly="+acno;
        ajaxRequest.open("POST", "setAcNoInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
</script>