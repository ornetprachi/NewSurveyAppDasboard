<div class="card-header">
    <h4 class="card-title" style="padding:5px;margin-left:10px;">Supervisor Summary Report</h4>
    <?php if ($ExcelExportButton == "show") { ?>
        <button id="exportBtn1" class="btn btn-primary" onclick="ExportToExcel('xlsx','SupervisorSummary')">Excel</button>
    <?php } ?>
</div>
<div class="content-body">
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-hover-animation table-striped table-hover"
                                    id="SupervisorSummary">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color: white;">Sr No</th>
                                            <th style="background-color:#36abb9;color: white;">Supervisor</th>
                                            <th style="background-color:#36abb9;color: white;">Site Manager</th>
                                            <th class="text-center"
                                                style="background-color:#36abb9;color: white;visible:flase;">
                                                Site</th>
                                            <th class="text-center"
                                                style="background-color:#36abb9;color: white;visible:flase;"
                                                title="Executive">Exe</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">
                                                Listing</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Survey Society">Survey Soc</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Pending Society">Pending Soc</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Rooms">Ro
                                            </th>
                                            <th style="background-color:#36abb9;color: white;padding-left:20px;"
                                                Title="Voters">V</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="NonVoters">NV</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="LockRoom">
                                                LR</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Locked But Survey">LBS</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Mobile">
                                                Mob</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Birthdate">BirtDt</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Average">
                                                Avg</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Society Ratio">Soc %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Voters Ratio">V %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="NonVoters Ratio">NV %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="NonVoters Ratio">LR %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="NonVoters Ratio">LBS %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="BirthDate Ratio">BirDt %</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Mobile Ratio">Mob %</th>
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

<script>
    let SuperVisortbl;
    $(document).ready(function () {
        SuperVisortbl = $('#SupervisorSummary').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: 'SupervisorSummaryTblQry.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#SupervisorSummary_filter input[type=search]').val();
                },
                beforeSend: function () {

                },
                complete: function () {
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1 + meta.settings._iDisplayStart; // Serial Number
                    }
                },
                {
                    data: 'SupervisorName',
                    render: function (data, type, row) {
                        return `<b>${data}</b>`;
                    },
                    createdCell: function (cell, cellData, rowData, rowIndex, colIndex) {
                        var status = rowData.SiteStatus;
                        var statusClass = '';

                        if (status === 'Done') statusClass = '#AEEAB7';
                        else if (status === 'On Going') statusClass = '#E9EC5C';
                        else if (status === 'Hold') statusClass = '#F8956A';

                        $(cell).css('background-color', statusClass);
                        $(cell).attr('title', rowData.SiteStatus);
                    }
                },
                {
                    data: 'SiteManager',
                    render: function (data, type, row) {
                        return `<td style="cursor:pointer;" title="${row.MobileNo1}"><b>${data}</b></td>`;
                    }
                },
                {
                    data: 'Sites',
                    className: 'text-center'
                },
                {
                    data: 'Executive',
                    className: 'text-center'
                },
                {
                    data: 'Listing',
                    className: 'text-center'
                },
                {
                    data: 'SocietyCount',
                    className: 'text-center'
                },
                {
                    data: 'Listing',
                    render: function (data, type, row) {
                        return `<a id="openModalButton" onclick="getSiteWisePendingSocDetail('')">${data - row.SocietyCount}</a>`;
                    },
                    className: 'text-center'
                },
                {
                    data: 'RoomSurveyDone',
                    className: 'text-center'
                },
                {
                    data: 'TotalVoters',
                    className: 'text-center'
                },
                {
                    data: 'TotalNonVoters',
                    className: 'text-center'
                },
                {
                    data: 'LockRoom',
                    className: 'text-center'
                },
                {
                    data: 'LBS',
                    className: 'text-center'
                },
                {
                    data: 'TotalMobileCount',
                    className: 'text-center'
                },
                {
                    data: 'BirthdaysCount',
                    className: 'text-center'
                },
                {
                    data: 'RoomSurveyDone',
                    render: function (data, type, row) {
                        return row.Executive !== 0 ? Math.ceil(data / row.Executive) : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'SocietyCount',
                    render: function (data, type, row) {
                        return row.Listing !== 0 ? Math.ceil((data / row.Listing) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'TotalVoters',
                    render: function (data, type, row) {
                        let total = row.TotalVoters + row.TotalNonVoters;
                        return total !== 0 ? Math.ceil((data / total) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'TotalNonVoters',
                    render: function (data, type, row) {
                        let total = row.TotalVoters + row.TotalNonVoters;
                        return total !== 0 ? Math.ceil((data / total) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'LockRoom',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'LBS',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'BirthdaysCount',
                    render: function (data, type, row) {
                        let total = row.TotalVoters + row.TotalNonVoters;
                        return total !== 0 ? Math.ceil((data / total) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: 'TotalMobileCount',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : '0';
                    },
                    className: 'text-center',
                    orderable: false 
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
            SuperVisortbl.columns.adjust().responsive.recalc();
        });
    });
</script>