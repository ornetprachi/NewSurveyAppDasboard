<div class="card-header">
    <div class="row">
        <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Site Wise</h4>
        <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1"
            id="showCountBtn">Count</button>
    </div>
    <?php if ($ExcelExportButton == "show") { ?>
        <button id="exportBtn1" class="btn btn-primary"
            onclick="ExportToExcel('xlsx','SiteWiseSurveySummary')">Excel</button>
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
                                    id="SiteWiseSurveySummary" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="background-color:#36abb9;color: white;">No</th>
                                            <th style="background-color:#36abb9;color: white;">View</th>
                                            <th style="background-color:#36abb9;color: white;">Client</th>
                                            <th style="background-color:#36abb9;color: white;" Title="Assembly Number">
                                                AcNo</th>
                                            <th style="background-color:#36abb9;color: white;">Ward</th>
                                            <th style="background-color:#36abb9;color: white;">%</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">
                                                Voting</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;">
                                                Listing</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Survey Society">SurSoc</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Pending Society">PenSoc</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Total Rooms">TotalRo</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Rooms">Ro
                                            </th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="LockRoom">
                                                LR</th>
                                            <th style="background-color:#36abb9;color: white;padding-left:20px;"
                                                Title="Voters">V</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="NonVoters">NV</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Locked But Survey">LBS</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Mobile">
                                                Mob</th>
                                            <th class="text-center" style="background-color:#36abb9;color: white;"
                                                Title="Birthdate">BirtDt</th>
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

    let swtbl;
    $(document).ready(function () {
        swtbl = $('#SiteWiseSurveySummary').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: 'SiteWiseSurveySummaryTblQry.php',
                type: 'POST',
                data: function (d) {
                    d.search.value = $('#SiteWiseSurveySummary_filter input[type=search]').val();
                },
                beforeSend: function () {

                },
                complete: function (json) {
                    // var totalRecords = json.responseJSON.recordsTotal;
                    // $('#mobileWiseCount').text('(' + totalRecords + ')');
                }
            },
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'SiteName',
                    render: function (data, type, row) {
                        return `<a id="openModalButton" onclick="getSiteWiseAllDetail('${data}')"><i class="fa fa-building" style = "color: #36abb9;"></i></a>&nbsp;
                    <a id="openModalButton" onclick="getSiteWiseDetail('${data}')"><i class="fa fa-eye" style = "color: #36abb9;"></i></a>&nbsp;
                    <a id="openModalButton" onclick="getSiteNameForMap('${data}')"><i class="feather icon-map-pin" style = "color: #36abb9;"></i></a>`;
                    }
                },
                {
                    data: 'ClientName',
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
                    data: 'Ac_No',
                },
                {
                    data: 'Ward_No'
                },
                {
                    data: 'Result',
                    render: function (data, type, row) {
                        return data && data != 0 ? Math.ceil((row.voters / data) * 100) + "%" : "0%";
                    }
                },
                {
                    data: 'Result',
                    className: 'text-center'
                },
                {
                    data: 'Listing',
                    className: 'text-center'
                },
                {
                    data: 'Survey',
                    className: 'text-center',
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return (row.Listing - row.Survey);
                    },
                    className: 'text-center',
                },
                {
                    data: 'TotalRooms',
                    className: 'text-center',
                },
                {
                    data: 'Rooms',
                    className: 'text-center',
                },
                {
                    data: 'lockroom',
                    className: 'text-center',
                },
                {
                    data: 'voters',
                    className: 'text-center',
                },
                {
                    data: 'nonvoter',
                    className: 'text-center',
                },
                {
                    data: 'LBS',
                    className: 'text-center',
                },
                {
                    data: 'Mobile',
                    className: 'text-center',
                },
                {
                    data: 'Bday',
                    className: 'text-center',
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.Listing !== 0 ? Math.ceil((row.Survey / row.Listing) * 100) + "%" : "0%";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return (row.voters + row.nonvoter) !== 0 ? Math.ceil((row.voters / (row.voters + row.nonvoter)) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return (row.voters + row.nonvoter) !== 0 ? Math.ceil((row.nonvoter / (row.voters + row.nonvoter)) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.Rooms !== 0 ? Math.ceil((row.lockroom / row.Rooms) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.Rooms !== 0 ? Math.ceil((row.LBS / row.Rooms) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return (row.voters + row.nonvoter) !== 0 ? Math.ceil((row.Bday / (row.voters + row.nonvoter)) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return row.Rooms !== 0 ? Math.ceil((row.Mobile / row.Rooms) * 100) + "%" : "0";
                    },
                    className: 'text-center',
                    orderable: false 
                },
            ],
            order: [[1, 'asc']],
            pageLength: 20,
            lengthMenu: [[20, 40, 50, -1], [20, 40, 50, "All"]],
            columnDefs: [
                {
                    visible: false,
                    targets: [12, 13, 14, 15, 16, 17]
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

        $('#showCountBtn').click(function () {
            var columnIndexes = [12, 13, 14, 15, 16, 17];
            columnIndexes.forEach(function (index) {
                swtbl.column(index).visible(true);
            });
        });
    });
</script>