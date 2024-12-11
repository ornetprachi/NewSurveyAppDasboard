<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$Designation = $_SESSION['SurveyUA_Designation'];

$FromServerName = '';
$ToServerName = '';
$FromElectionName = '';
$ToElectionName = '';
$FromElectionNameData = array();
$ToElectionNameData = array();


// echo $electionCd . " - " . $electionName;

if(isset($_SESSION['From_Servername_MoveDBData']) && !empty($_SESSION['From_Servername_MoveDBData'])){
    $FromServerName = $_SESSION['From_Servername_MoveDBData'];

    $query = "SELECT ElectionName FROM Survey_Entry_Data..Election_Master WHERE ServerName = '$FromServerName' order by ElectionName ;";
    $FromElectionNameData = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

    // echo "<pre>";
    // print_r($FromElectionNameData);
}

if(isset($_SESSION['To_Servername_MoveDBData']) && !empty($_SESSION['To_Servername_MoveDBData'])){
    $ToServerName = $_SESSION['To_Servername_MoveDBData'];

    $query = "SELECT ElectionName FROM Survey_Entry_Data..Election_Master WHERE ServerName = '$ToServerName' order by ElectionName ;";
    $ToElectionNameData = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
    
    // $query = "SELECT ElectionName FROM Survey_Entry_Data_Testing..Election_Master WHERE ServerName = '$ToServerName' order by ElectionName ;";
    // $ToElectionNameData = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

    // echo "<pre>";
    // print_r($FromElectionNameData);
}

?>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Move From</h4>
                                    </div>
                                </div>
                                <hr class="mt-0 p-0">
                                <div class="row">
                                    <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                        <?php include 'dropdown-from-servername-moveDBData.php'; ?>
                                    </div>
                                    <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Election</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="FromElectionName">
                                                    <option value="">--SELECT--</option>
                                                        <?php
                                                    if (sizeof($FromElectionNameData)>0) 
                                                    {
                                                        foreach ($FromElectionNameData as $key => $value) 
                                                            {
                                                                if($FromElectionName == $value["ElectionName"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['ElectionName']; ?>"><?php echo $value["ElectionName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["ElectionName"];?>"><?php echo $value["ElectionName"];?></option>
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
                            <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                <button class="btn btn-primary btn-block" style="margin-top:70px" id="MoveBtn" onclick="moveDBDataToAnotherServer()" title="Move">
                                    <i class="feather icon-arrow-right-circle"></i>
                                </button>
                            </div>
                            <div class="col-xs-12 col-xl-4 col-md-4 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <h4>Move To</h4>
                                    </div>
                                </div>
                                <hr class="mt-0 p-0">
                                <div class="row">
                                    <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                        <?php include 'dropdown-to-servername-moveDBData.php'; ?>
                                    </div>
                                    <div class="col-xs-12 col-xl-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Election</label>
                                            <div class="controls">
                                                <select class="select2 form-control" name="ToElectionName">
                                                <option value="">--SELECT--</option>

                                                        <?php
                                                    if (sizeof($ToElectionNameData)>0) 
                                                    {
                                                        foreach ($ToElectionNameData as $key => $value) 
                                                            {
                                                                if($ToElectionName == $value["ElectionName"])
                                                                {
                                                    ?>
                                                                <option selected="true" value="<?php echo $value['ElectionName']; ?>"><?php echo $value["ElectionName"]; ?></option>
                                                    <?php
                                                                }
                                                                else
                                                                {
                                                    ?>
                                                                <option value="<?php echo $value["ElectionName"];?>"><?php echo $value["ElectionName"];?></option>
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
                        <div class="row">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('MoveBtn').addEventListener("click", function(){
        this.classList.add("loading");
        this.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
    });

</script>

</section>