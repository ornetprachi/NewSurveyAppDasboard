<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
// echo "<pre>"; print_r($_SESSION);exit;
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$FromDate = date('Y-m-d', strtotime('-7 days'));
$ToDate = date('Y-m-d');
$selExecutive = "All";

$BackButton = "<button class='btn btn-danger btn-block' title='Back' onclick='history.back()'>
                <i class='feather icon-arrow-left'></i>
            </button>";

$GetAc_No = 0;
$dataElectionName = $db->getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode);

if(
    (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName'])) 
){
    $electionName = $_SESSION['SurveyUA_ElectionName'];
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];

    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
}else{
    $DBName = "";
}

if(isset($_SESSION['VS_SurveyUA_UserName']) && !empty($_SESSION['VS_SurveyUA_UserName'])){
    $selExecutive = $_SESSION['VS_SurveyUA_UserName'];
}else{
    $_SESSION['VS_SurveyUA_UserName'] = $selExecutive;
}

if(isset($_SESSION['VS_SurveyUA_FromDate']) && !empty($_SESSION['VS_SurveyUA_FromDate'])){
    $FromDate = $_SESSION['VS_SurveyUA_FromDate'];
}else{
    $_SESSION['VS_SurveyUA_FromDate'] = $FromDate;
}

if(isset($_SESSION['VS_SurveyUA_ToDate']) && !empty($_SESSION['VS_SurveyUA_ToDate'])){
    $ToDate = $_SESSION['VS_SurveyUA_ToDate'];
}else{
    $_SESSION['VS_SurveyUA_ToDate'] = $ToDate;
}

$selSearchedNtVal = "All";
if(isset($_SESSION['VS_SurveyUA_SearchNotSearchVal']) && !empty($_SESSION['VS_SurveyUA_SearchNotSearchVal'])){
    $selSearchedNtVal = $_SESSION['VS_SurveyUA_SearchNotSearchVal'];
}

$dataExecutiveName = array();
    $queryExec = "SELECT DISTINCT(um.UserName), um.ExecutiveName
        FROM $DBName..Dw_VotersInfo as dw
        INNER JOIN Survey_Entry_Data..User_Master AS um ON (dw.OrnetUpdateByUser = um.UserName)
        ORDER BY um.ExecutiveName";

$dataExecutiveName = $db->ExecutveQueryMultipleRowSALData($ULB,$queryExec , $userName, $appName, $developmentMode);


?>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Corporation</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="electionName" onChange="setElectionNameInSession(this.value)" >
                                            <?php
                                            if (sizeof($dataElectionName)>0) 
                                            {
                                                foreach ($dataElectionName as $key => $value) 
                                                {
                                                    if($_SESSION['SurveyUA_Election_Cd'] == $value["Election_Cd"])
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Executive</label>
                                    <div class="controls">
                                        <select class="select2 form-control" name="ExecutiveVal" id="ExecutiveVal" onchange="getVoterSearchExecutiveSummary()">
                                            <option <?php echo $selExecutive == 'All' ? 'selected' : '' ; ?> value="All">ALL</option>

                                            <?php
                                            if (sizeof($dataExecutiveName)>0) 
                                            {
                                                foreach ($dataExecutiveName as $key => $value) 
                                                {
                                                    if($selExecutive == $value["UserName"])
                                                    {
                                            ?>
                                                        <option selected="true" value="<?php echo $value['UserName']; ?>"><?php echo $value["ExecutiveName"]; ?></option>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <option value="<?php echo $value["UserName"];?>"><?php echo $value["ExecutiveName"];?></option>
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
                                <label>From Date</label>
                                <div class="controls"> 
                                    <input type="date" name="FromDate" id="FromDate" value="<?php echo $FromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>" onchange="getVoterSearchExecutiveSummary()">
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>To Date</label>
                                <div class="controls"> 
                                    <input type="date" name="ToDate" id="ToDate" value="<?php echo $ToDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>" onchange="getVoterSearchExecutiveSummary()">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Searched / Not Search</label>
                                    <div class="controls">
                                        <select name="SearchNotSearchVal" id="SearchNotSearchVal" class="form-control form-select select2" onchange="getSearchAndNotSearchInSession(this.value)">
                                            <option <?php echo $selSearchedNtVal == 'All' ? 'Search' : '' ; ?> value="All">ALL</option>
                                            <option value="Search" <?php echo $selSearchedNtVal == 'Search' ? 'selected' : ''; ?>>Searched</option>
                                            <option value="NotSearch" <?php echo $selSearchedNtVal == 'NotSearch' ? 'selected' : ''; ?>>Not Searched</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <input type="hidden" name="electionName" id="electionName" value="<?php echo $electionName; ?>"  class="form-control">
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
            <div class="card-header">
                <div CLASS="row">
                    <h4 class="card-title ml-2">Voter Search Executive Wise List</h4>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-striped table-hover" id="getFilterVoterSearchExecutiveListTable">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;">Voter ID / List No / Ac No</th>
                                                        <th style="background-color:#36abb9;color: white;">Full Name</th>
                                                        <th style="background-color:#36abb9;color: white;">Mobile No</th>
                                                        <th style="background-color:#36abb9;color: white;">Birth Date</th>
                                                        <th style="background-color:#36abb9;color: white;">Sex</th>
                                                        <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function (){
    
    // $('#AcNo').change();

    var getFilterVoterSearchExecutiveListTable = $('#getFilterVoterSearchExecutiveListTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: "POST",
            url:"getFilterVoterSearchExecWiseTableData.php",
            data: function(data){
                data.page = $('#getFilterVoterSearchExecutiveListTable').DataTable().page.info().page + 1;
                data.electionName = $('#electionName').val();
                data.ExecutiveVal = $('#ExecutiveVal').val();
                data.FromDate = $('#FromDate').val();
                data.ToDate = $('#ToDate').val();
                data.SearchNotSearchVal = $('#SearchNotSearchVal').val();
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: 'text-center',
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.Voter_Id+' / '+row.List_No+' / '+row.Ac_No;
                }, 
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.FullName;
                }, 
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.MobileNo;
                }, 
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.BirthDate;
                }, 
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.Sex;
                }, 
                orderable: false // Disable ordering for this column
            },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.SocietyName;
                }, 
                orderable: false // Disable ordering for this column
            },
            
        ],
        order: [[0, 'asc']]
    });

});

$(document).keydown(function(event) {
    if (event.altKey && event.key === 's') {
        event.preventDefault(); 
        $('#searchbtn').click(); 
    }
});



function getVoterSearchExecutiveSummary(){
    
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
            location.reload();
        }
    }
    
    var ExecutiveVal = document.getElementsByName('ExecutiveVal')[0].value;
    var FromDate = document.getElementsByName('FromDate')[0].value;
    var ToDate = document.getElementsByName('ToDate')[0].value;
    // var SearchNotSearchVal = document.getElementsByName('SearchNotSearchVal')[0].value;

    var Date1 = FromDate.match(/(\d+)/g);
    var Date2 = ToDate.match(/(\d+)/g);
    FrDate = new Date(Date1[0], Date1[1]-1, Date1[2]);
    todate = new Date(Date2[0], Date2[1]-1, Date2[2]);

    if (ExecutiveVal === '') {
        alert("Please Select ExecutiveVal!");
    }else if (FromDate === '') {
        alert("Please Select From Date!");
    }else if (ToDate === '') {
        alert("Please Select To Date!");
    }else if(FrDate.getTime() > todate.getTime()){
        alert("Please Select To Date greater than From Date!");
    }else{
        queryString = "?ExecutiveVal="+ExecutiveVal+"&FromDate="+FromDate+"&ToDate="+ToDate;
        ajaxRequest.open("GET", "setVoterSearchExecutiveSumInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


function getSearchAndNotSearchInSession(SearchNotSearchVal){
    
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
            location.reload();
        }
    }
    
    if (SearchNotSearchVal === '') {
        alert("Please Select Search / Not Search!");
    }else{
        queryString = "?SearchNotSearchVal="+SearchNotSearchVal;
        ajaxRequest.open("GET", "setSearchAndNotSearchInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}

</script>