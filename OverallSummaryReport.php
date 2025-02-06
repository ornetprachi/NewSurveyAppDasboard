<div class="card-header">
    <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report </h4>

    <?php if ($ExcelExportButton == "show") { ?>
        <button id="exportBtn1" class="btn btn-primary" onclick="ExportToExcel('xlsx','OverallSummaryTable')">Excel</button>
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
                                    id="OverallSummaryTable">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color: white;">No</th>
                                            <th style="background-color:#36abb9;color: white;">Date</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Society">
                                                Soc</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Executive">Exec
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="Rooms">Ro
                                            </th>
                                            <th style="background-color:#36abb9;color: white;padding-left:10px;"
                                                Title="Voters">V</th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                            <th style="background-color:#36abb9;color: white;" Title="LockRoom">
                                                LR</th>
                                            <th style="background-color:#36abb9;color: white;"
                                                Title="Locked But survey">LBS</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Mobile">
                                                Mob</th>
                                            <th style="background-color:#36abb9;color: white;" Title="BirthDate">BirDt
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="Average">
                                                Avg</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Voters Ratio">V %
                                            </th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters Ratio">
                                                NV %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters Ratio">
                                                LR %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="NonVoters Ratio">
                                                LBS %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="BirthDate Ratio">
                                                BirDt %</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Mobile Ratio">Mob
                                                %</th>
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
    let OASummarytbl;
    $(document).ready(function () {
        OASummarytbl = $('#OverallSummaryTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: 'OverallSummaryTblQry.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#OverallSummaryTable_filter input[type=search]').val();
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
                        return meta.row + 1 + meta.settings._iDisplayStart; 
                    }
                },
                {
                    data: 'SurvyeDate',
                    render: function (data) {
                        return "<b>" + data + "</b>";
                    }
                },
                {
                    data: 'SurvyeSociety'
                },
                {
                    data: 'SurveyBy'
                },
                {
                    data: 'RoomSurveyDone'
                },
                {
                    data: 'TotalVoters'
                },
                {
                    data: 'TotalNonVoters'
                },
                {
                    data: 'LockRoom'
                },
                {
                    data: 'LBS'
                },
                {
                    data: 'TotalMobileCount'
                },
                {
                    data: 'BirthdaysCount'
                },
                {
                    data: 'RoomSurveyDone',
                    render: function (data, type, row) {
                        return row.SurveyBy !== 0 ? Math.ceil(data / row.SurveyBy) : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'TotalVoters',
                    render: function (data, type, row) {
                        const totalVoters = row.TotalVoters + row.TotalNonVoters;
                        return totalVoters !== 0 ? Math.ceil((data / totalVoters) * 100) + "%" : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'TotalNonVoters',
                    render: function (data, type, row) {
                        const totalVoters = row.TotalVoters + row.TotalNonVoters;
                        return totalVoters !== 0 ? Math.ceil((data / totalVoters) * 100) + "%" : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'LockRoom',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'LBS',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'BirthdaysCount',
                    render: function (data, type, row) {
                        const totalVoters = row.TotalVoters + row.TotalNonVoters;
                        return totalVoters !== 0 ? Math.ceil((data / totalVoters) * 100) + "%" : "0";
                    },
                    orderable: false 
                },
                {
                    data: 'TotalMobileCount',
                    render: function (data, type, row) {
                        return row.RoomSurveyDone !== 0 ? Math.ceil((data / row.RoomSurveyDone) * 100) + "%" : "0";
                    },
                    orderable: false 
                }
            ],
            order: [[1, 'desc']],
            pageLength: 20,
            lengthMenu: [[20, 40, 50, -1], [20, 40, 50, "All"]],
            language: {
                emptyTable: 'No data available',
                processing: 'Loading data...',
            }
        });

        $(window).on('resize', function () {
            OASummarytbl.columns.adjust().responsive.recalc();
        });
    });
</script>