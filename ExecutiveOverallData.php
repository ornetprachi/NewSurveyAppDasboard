<?php
$Filter = '';
if(isset($_SESSION['SurveyUA_Status'])){
    $Filter = $_SESSION['SurveyUA_Status'];
}

?>
<div class="card-header" style="margin-top: -15px;">
    <div class="row">
        <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Executive Wise</h4>
        <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1"
            id="showExeCountBtn">Count</button>
    </div>
    <?php if ($ExcelExportButton == "show") { ?>
        <button id="exportBtn1" style="padding:10px;" class="btn btn-primary"
            onclick="ExportToExcel('xlsx','SurveySummaryExecutiveList')">Excel</button>
    <?php } ?>
</div>
<div class="card-header" style="margin-top: -15px;">
    <h6>Total Executive - <span id="totalExecutiveCount">0</span></h6>
</div>
&nbsp;&nbsp;
<div class="row" style="margin-top: -20px;margin-bottom: -20px;">
    <button type="button" class="btn btn-flat-success mr-1 mb-1" onclick="getExeFilter('ACTIVE')" id="activeBtn" style="padding:10px;margin-left:15px;">
        Active(<span id="activeCount">0</span>)
    </button>

    <!-- Inactive Button -->
    <button type="button" class="btn btn-flat-danger mr-1 mb-1" onclick="getExeFilter('INACTIVE')" id="inactiveBtn" style="padding:10px;">
        InActive(<span id="inActiveCount">0</span>)
    </button>

    <div class="row" style="margin-left:10px;">
        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
            <div class="form-group">
                <label>Working Days</label>
                <div class="row">
                    <div class="col-md-5 col-12" style="margin-left:12px;padding:3px;">
                        <div class="controls">
                        <input type="search" name="WorkingDaysExec" class="form-control" placeholder="From" value="<?php echo isset($_SESSION['SurveyUA__WorkingDaysExec_For_SummaryReport']) ? $_SESSION['SurveyUA__WorkingDaysExec_For_SummaryReport'] : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-5 col-12" style="padding:3px;">
                        <div class="controls">
                            <input type="Search" name="ToWorkingDaysExec" class="form-control" placeholder="To" value="<?php echo isset($_SESSION['SurveyUA__ToWorkingDaysExec_For_SummaryReport']) ? $_SESSION['SurveyUA__ToWorkingDaysExec_For_SummaryReport'] : ''; ?>"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-4 col-md-4 col-xl-4 col-12">
            <div class="controls" style="padding-top:20px;">
                <button type="button" class="btn btn-primary" onclick="GetWorkingDateFilter()" id="SearchBtn">
                    Refresh
                </button>
            </div>
            <script>
                document.getElementById('SearchBtn').addEventListener("click", function () {
                    this.classList.add("loading");
                    this.innerHTML = "<i class='fa fa-refresh fa-spin'></i>  Loading..";
                });
            </script>
        </div>
    </div>
</div>
<div class="content-body">
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-hover-animation table-hover" id="SurveySummaryExecutiveList"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color: white;">No</th>
                                            <th style="background-color:#36abb9;color: white;">View</th>
                                            <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Designation">Desig
                                            </th>
                                            <!-- <th style="background-color:#36abb9;color: white;" Title = "Reference">Ref</th> -->
                                            <th style="background-color:#36abb9;color: white;" Title="Joining Date">JOD
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="Working Days">WD
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="Society">Soc</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Rooms">Ro</th>
                                            <!-- <th style="background-color:#36abb9;color: white;">Total Rooms</th> -->
                                            <th style="background-color:#36abb9;color: white;padding-left:10px;"
                                                Title="Voters">V</th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Lockroom">LR</th>
                                            <th style="background-color:#36abb9;color: white;"
                                                Title="Locked But Survey">LBS</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Birthday">BirtDt
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="Mobile">Mob</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Voters Ratio">V %
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters Ratio">
                                                NV %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="LockRoom Ratio">LR
                                                %</th>
                                            <th style="background-color:#36abb9;color: white;"
                                                Title="Locked But Survey Ratio">LBS %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Birthdate Ratio">
                                                BirtDt %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">Mob
                                                %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Average">Avg</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 7px;
    }

    .table td,
    .table th {
        padding: 0.75px;
        margin: 0;
    }

    .table th {
        background-color: #36abb9;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .table tr {
        padding: 0;
        margin: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td {
        border: 1px solid grey;
        padding: 8px;
    }
</style>

<script>
   let exectbl;
   let Status = "";
   let WorkingDaysExec = '';
   let ToWorkingDaysExec = '';

    $(document).ready(function () {
        // if (!Status) {
        //     Status = 'ACTIVE';  
        // }
        exectbl = $('#SurveySummaryExecutiveList').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: 'action/ExecutiveOverAllDataTableData.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#SurveySummaryExecutiveList_filter input[type=search]').val();
                    d.Status = Status;
                    d.WorkingDaysExec = document.getElementsByName('WorkingDaysExec')[0].value;
                    d.ToWorkingDaysExec = document.getElementsByName('ToWorkingDaysExec')[0].value;
                },
                beforeSend: function () {
                },
                complete: function (json) {
                    var ActiveCount = json.responseJSON.Active; 
                    var InactiveCount = json.responseJSON.Inactive; 
                    var TotalExecutiveCount = json.responseJSON.TotalExecutive; 
                    if (ActiveCount > 0) {
                        $('#activeCount').text(ActiveCount);  
                    } else {
                        $('#activeCount').text('0'); 
                    }
                    if (InactiveCount > 0) {
                        $('#inActiveCount').text(InactiveCount);  
                    } else {
                        $('#inActiveCount').text('0'); 
                    }

                    if(TotalExecutiveCount > 0){
                        $('#totalExecutiveCount').text(TotalExecutiveCount);
                    }
                    else{
                        $('#totalExecutiveCount').text('0');
                    }
                    $('#SearchBtn').removeClass('loading');
                    $('#SearchBtn').html('Refresh');
                }
            },
            columns: [
                
                { data: null, render: (data, type, row, meta) => meta.row + 1 },  
                { data: 'ExecutiveName', render: function(data, type, row) {
                        return `<a href="#" onclick="getExecutiveData('${data}', ${row.Excutive_cd})"><span style ="color: #36abb9;"><i class="fa fa-eye"></i></span></a>`;
                    }
                },
                {
                    data: 'ExecutiveName',
                    render: function(data, type, row) {
                        return `<b>${data}</b>`;
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                    var totalVotersNonVoters = rowData.TotalVoters + rowData.TotalNonVoters;
                    var totalRoomSurveyDone = rowData.RoomSurveyDone;
                    var votersPercentage = (totalVotersNonVoters > 0) ? Math.ceil((rowData.TotalVoters / totalVotersNonVoters) * 100) : 0;
                    var birthdaysPercentage = (totalVotersNonVoters > 0) ? Math.ceil((rowData.BirthdaysCount / totalVotersNonVoters) * 100) : 0;
                    var roomSurveyDonePercentage = (totalRoomSurveyDone > 0) ? Math.ceil((rowData.TotalMobileCount / totalRoomSurveyDone) * 100) : 0;
                        $(cell).css('background-color', '#FFD6D6');
                        $(cell).attr('title', rowData.MobileNo);
                    }
                },
                { data: 'Designation' },
                { data: 'JoiningDate' },
                { data: 'WorkingDays' },
                { data: 'SocietyCount' },
                { data: 'RoomSurveyDone' },
                { data: 'TotalVoters' },
                { data: 'TotalNonVoters' },
                { data: 'LockRoom' },
                { data: 'LBS' },
                { data: 'BirthdaysCount' },
                { data: 'TotalMobileCount' },
                {
                    data: 'TotalVoters',
                    render: function(data, type, row) {
                        var totalVoters = data;
                        var totalVotersNonVoters = row.TotalVoters + row.TotalNonVoters;
                        var votersPercentage = calculatePercentage(totalVoters, totalVotersNonVoters);
                        return votersPercentage + ' %';
                    },
                    orderable: false
                },
                {
                    data: 'TotalNonVoters',
                    render: function(data, type, row) {
                        var nonVotersPercentage = calculatePercentage(data, row.TotalVoters + row.TotalNonVoters);
                        return `${nonVotersPercentage} %`;
                    },
                    orderable: false
                },
                {
                    data: 'LockRoom',
                    render: function(data, type, row) {
                        var lockRoomPercentage = calculatePercentage(data, row.RoomSurveyDone);
                        return lockRoomPercentage + ' %';
                    },
                    orderable: false
                },
                {
                    data: 'LBS',
                    render: function(data, type, row) {
                        var lbsPercentage = calculatePercentage(data, row.RoomSurveyDone);
                        return `${lbsPercentage} %`;
                    },
                    orderable: false
                },
                {
                    data: 'BirthdaysCount',
                    render: function(data, type, row) {
                        var birthdaysPercentage = calculatePercentage(data, row.TotalVoters + row.TotalNonVoters);
                        return birthdaysPercentage + ' %';
                    },
                    orderable: false
                },
                {
                    data: 'TotalMobileCount',
                    render: function(data, type, row) {
                        var mobilePercentage = calculatePercentage(data, row.RoomSurveyDone);
                        return mobilePercentage + ' %';
                    },
                    orderable: false
                },
                {
                    data: 'RoomSurveyDone',
                    render: function(data, type, row) {
                        var roomSurveyDonePercentage = 0;
                        if (row.WorkingDays !== 0 && row.WorkingDays !== '') {
                            roomSurveyDonePercentage = Math.ceil((data / row.WorkingDays)); // Calculate percentage
                        } 
                        return `${roomSurveyDonePercentage}`;
                    },
                    orderable: false
                }
            ],
            order: [[1, 'asc']],
            pageLength: 20,
            lengthMenu: [[20, 40, 50, -1], [20, 40, 50, "All"]],
            columnDefs: [
                {
                    visible: false,
                    targets: [11, 12, 13, 14]
                }
            ],
            language: {
                emptyTable: 'No data available',
                processing: 'Loading data...',
            }
        });

        $(window).on('resize', function () {
            swtbl.columns.adjust().responsive.recalc();
        });

        $('#showExeCountBtn').click(function () {
            var columnIndexes = [11, 12, 13, 14];
            columnIndexes.forEach(function (index) {
                exectbl.column(index).visible(true);
            });
        });
    });

    function calculatePercentage(numerator, denominator) {
        if (denominator === 0) return 0;
        return Math.ceil((numerator / denominator) * 100);
    }
    function getExeFilter(clickStatus){
        if (clickStatus === 'ACTIVE') {
            document.getElementById('activeBtn').style.backgroundColor = '#28C76F';
            document.getElementById('activeBtn').style.color = 'white';
            
            document.getElementById('inactiveBtn').style.backgroundColor = '';  
            document.getElementById('inactiveBtn').style.color = '';
        } else if (clickStatus === 'INACTIVE') {
            document.getElementById('inactiveBtn').style.backgroundColor = '#EA5455';
            document.getElementById('inactiveBtn').style.color = 'white';
            
            document.getElementById('activeBtn').style.backgroundColor = ''; 
            document.getElementById('activeBtn').style.color = '';
        }
        Status = clickStatus;
        exectbl.ajax.reload(); 
    }

    function GetWorkingDateFilter(){
        exectbl.ajax.reload(); 
    }
    

</script>