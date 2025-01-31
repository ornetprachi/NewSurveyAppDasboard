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
    <h6>Total Executive - <span id="totalExecutiveCount"></span></h6>
</div>
&nbsp;&nbsp;
<div class="row" style="margin-top: -20px;margin-bottom: -20px;">
    <button type="button" class="btn btn-flat-success mr-1 mb-1" onclick="getExeFilter('ACTIVE')"
        style="padding:10px;margin-left:15px;<?php if ($Filter == 'ACTIVE') {
            echo "background-color:#28C76F;color: white;";
        } ?>">Active(<span
            id="activeCount"></span>)</button>
    <button type="button" class="btn btn-flat-danger mr-1 mb-1" onclick="getExeFilter('INACTIVE')"
        style="padding:10px;<?php if ($Filter == 'INACTIVE') {
            echo "background-color:#EA5455;color: white;";
        } ?>">InActive(<span
            id="inActiveCount"></span>)</button>
    <div class="row" style="margin-left:10px;">
        <div class="col-xs-4 col-xl-4 col-md-4 col-12">
            <div class="form-group">
                <label>Working Days</label>
                <div class="row">
                    <div class="col-md-5 col-12" style="margin-left:12px;padding:3px;">
                        <div class="controls">
                            <input type="Search" name="WorkingDaysExec" class="form-control" placeholder="From">
                        </div>
                    </div>
                    <div class="col-md-5 col-12" style="padding:3px;">
                        <div class="controls">
                            <input type="Search" name="ToWorkingDaysExec" class="form-control" placeholder="To">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="controls"> 
                                        <input type="Search" name="ToWorkingDaysExec"  class="form-control" placeholder="WorkingDays">
                                    </div>
                                </div>
                            </div> -->

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
                                    <tbody id="executiveTableBody">
                                        <!-- Data rows will be inserted dynamically here -->
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