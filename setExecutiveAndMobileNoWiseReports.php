<style type="text/css">
    .collapse-simple>.card>.card-header>*::before {
        content: '\f2f9';
        font: normal normal normal 14px/1 'Material-Design-Iconic-Font';
        font-size: 1.25rem;
        text-rendering: auto;
        position: absolute;
        top: 8px;
        right: 0;
        color: black;
    }

    th {
        background: lightgrey;
    }

    .dot {
        height: 15px;
        width: 15px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }

    table.dataTable th,
    table.dataTable td {
        border-bottom: 1px solid #F8F8F8;
        border-top: 0;
        padding: 5PX;
    }

    .element {
        cursor: default;
    }

    /* Custom cursor on hover */
    .element:hover {
        cursor: pointer;
    }
</style>


<div class="tab-pane" id="home" aria-labelledby="home-tab" role="tabpanel" style="margin-top: -12px;">
    <ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
        <li class="nav-item" style="font-size: 15px;">
            <a class="nav-link active" id="ExecutiveWise-tab" data-toggle="tab" href="#ExecutiveWise"
                aria-controls="ExecutiveWise" role="tab" aria-selected="true">Executive Wise</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="MobileWise-tab" data-toggle="tab" href="#MobileWise" aria-controls="MobileWise"
                role="tab" aria-selected="true">Mobile No Wise</a>
        </li>
    </ul>
    <div class="tab-content">

        <div id='TabLoading' style='display:none'>
            <center>
                <img src='app-assets/images/loader/loading.gif' width="80" height="70" />
            </center>
        </div>
        <div class="tab-pane active" id="ExecutiveWise" aria-labelledby="ExecutiveWise-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="margin-top: -25px;">
                                    <h4 class="card-title">Executive Wise <span id="excutiveWiseCount">(0)</span></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="row match-height" style="margin-top:-5px;">
                                            <div class="col-md-12" style="margin-bottom: -30px;">
                                                <div class="card">
                                                    <div class="content-body">
                                                        <table
                                                            class="table table-hover-animation table-hover table-striped"
                                                            id="ExecutiveWiseMobileReport" style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Sr No</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Executive Name</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Mobile No</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        DB Name</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Family Nos</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Mobile No Repeat</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        View</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="tab-pane" id="MobileWise" aria-labelledby="MobileWise-tab" role="tabpanel">
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="margin-top: -25px;">
                                    <h4 class="card-title">Mobile No wise <span id="mobileWiseCount">(0)</span></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">

                                        <div class="row match-height" style="margin-top:-5px;">
                                            <div class="col-md-12" style="margin-bottom: -30px;">
                                                <div class="card">
                                                    <div class="content-body">
                                                        <table
                                                            class="table table-hover-animation table-hover table-striped"
                                                            style="width:100%" id="MobileWiseReport">
                                                            <thead>
                                                                <tr>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Sr No</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;font-size:14px;">
                                                                        Mobile No</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        DB Name</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Family Nos</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        Mobile No Repeat</th>
                                                                    <th
                                                                        style="background-color:#36abb9;color: white;vertical-align: middle;font-size:14px;">
                                                                        View</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
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

<script>
    let ewtbl;
    $(document).ready(function () {
        ewtbl = $('#ExecutiveWiseMobileReport').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: 'EWMobileReportTblQry.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#ExecutiveWiseMobileReport_filter input[type=search]').val();
                    d.flag = 'EW';
                },
                beforeSend: function () {

                },
                complete: function (json) {
                    var totalRecords = json.responseJSON.recordsTotal;
                    $('#excutiveWiseCount').text('(' + totalRecords + ')');
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<span style="font-weight: bold;">${row.ExecutiveName}</span>`;
                    }
                },
                {
                    data: 'MobileNo',
                },
                {
                    data: 'DBName',
                },
                {
                    data: 'FamilyNos',
                },
                {
                    data: 'datacnt',
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<a onclick="getExecutiveWiseDataInForm(\'' + row.ExecutiveName + '\', \'' + row.MobileNo + '\', \'' + row.DBName + '\', \'' + row.FamilyNos + '\', \'' + data + '\', \'EW\')"><i class="feather icon-eye" style="font-size: 1.5rem;color:#70ccd4;"></i></a>';
                    }
                }
            ],
            order: [[1, 'asc']],
            pageLength: 20,
            lengthMenu: [[20, 40, 50, -1], [20, 40, 50, "All"]],
            language: {
                emptyTable: 'No data available',
                processing: 'Loading data...',
            }
        });

        $(window).on('resize', function () {
            ewtbl.columns.adjust().responsive.recalc();
        });
    });

    let mwbl;
    $(document).ready(function () {
        mwbl = $('#MobileWiseReport').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: 'EWMobileReportTblQry.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#MobileWiseReport_filter input[type=search]').val();
                    d.flag = 'MW';
                },
                beforeSend: function () {

                },
                complete: function (json) {
                    var totalRecords = json.responseJSON.recordsTotal;
                    $('#mobileWiseCount').text('(' + totalRecords + ')');
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart;
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<span span style="font-weight: bold;">${row.MobileNo}</span>`;
                    }
                },
                {
                    data: 'DBName',
                },
                {
                    data: 'FamilyNos',
                },
                {
                    data: 'datacnt',
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<a onclick="getExecutiveWiseDataInForm(\'' + row.ExecutiveName + '\', \'' + row.MobileNo + '\', \'' + row.DBName + '\', \'' + row.FamilyNos + '\', \'' + data + '\', \'MW\')"><i class="feather icon-eye" style="font-size: 1.5rem;color:#70ccd4;"></i></a>';
                    }
                }
            ],
            order: [[1, 'asc']],
            pageLength: 20,
            lengthMenu: [[20, 40, 50, -1], [20, 40, 50, "All"]],
            language: {
                emptyTable: 'No data available',
                processing: 'Loading data...',
            }
        });

        $(window).on('resize', function () {
            mwbl.columns.adjust().responsive.recalc();
        });
    });
</script>