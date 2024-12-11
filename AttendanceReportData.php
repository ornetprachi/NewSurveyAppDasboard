
<?php

$TableULBQuery = "SELECT ULB,CONVERT(varchar,ed.SurveyDate,23) as SurveyDate,
(SELECT COUNT(Attendance) as P FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details  as t
Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
WHERE Attendance = 1 AND t1.ULB = elm.ULB AND CONVERT(varchar,SurveyDate,23)  BETWEEN '$fromdate' AND '$todate') as Present ,
(SELECT COUNT(Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t
Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
WHERE Attendance = 2  AND t1.ULB = elm.ULB AND CONVERT(varchar,SurveyDate,23)  BETWEEN '$fromdate' AND '$todate')  as Absent,
(SELECT COUNT(Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t
Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
WHERE Attendance = 0  AND t1.ULB = elm.ULB AND CONVERT(varchar,SurveyDate,23)  BETWEEN '$fromdate' AND '$todate')  as Assign,
(SELECT COUNT(Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t
Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
WHERE Attendance = 3 AND t1.ULB = elm.ULB AND CONVERT(varchar,SurveyDate,23)  BETWEEN '$fromdate' AND '$todate')  as HalfDay,
(SELECT COUNT(Attendance) as A FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as t
Left join [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as t1 on (t.ElectionName = t1.ElectionName)
WHERE Attendance = 4  AND t1.ULB = elm.ULB AND CONVERT(varchar,SurveyDate,23)  BETWEEN '$fromdate' AND '$todate')  as Training
FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details   as ed
LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Site_Master as sm on (ed.SiteName = sm.SiteName)
LEFT JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as elm on (ed.ElectionName = elm.ElectionName)
LEFT JOIN [Survey_Entry_Data]..Executive_Master as em on (sm.SupervisorName = em.ExecutiveName COLLATE SQL_Latin1_General_CP1_CI_AS)
WHERE CONVERT(varchar,ed.SurveyDate,23) BETWEEN '$fromdate' AND '$todate'
GROUP BY ULB,CONVERT(varchar,ed.SurveyDate,23);";
$TableULBData = $db->ExecutveQueryMultipleRowSALData($TableULBQuery, $userName, $appName, $developmentMode);

$Present = array_sum(array_column($TableULBData, 'Present'));
$Absent = array_sum(array_column($TableULBData, 'Absent'));
$Training = array_sum(array_column($TableULBData, 'Training'));
$HalfDay = array_sum(array_column($TableULBData, 'HalfDay'));
// print_r("<pre>");
?>
<div class="card-header">
    <div class="row">
        <h4 class="card-title" style="padding:5px;margin-left:10px;"> <b>Present - (<?php echo $Present; ?>)  Absent - (<?php echo $Absent; ?>) Training - (<?php echo $Training; ?>)</b> </h4>
        <!-- <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showDateSiteCountBtn" >Count</button> -->
    </div>
    <?php if($ExcelExportButton == "show"){ ?>
        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','AttendanceReportTable')">Excel</button>
    <?php } ?>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">  
        <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                        
                            <table class="table table-hover-animation table-striped table-hover" id="AttendanceReportTable" width="100%">
                                <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;">SrNo </th>
                                        <!-- <th style="background-color:#36abb9;color: white;">View </th> -->
                                        <th style="background-color:#36abb9;color: white;">Corporation</th>
                                        <th style="background-color:#36abb9;color: white;">Survey Date</th>
                                        <th style="background-color:#36abb9;color: white;" title="Present">Total</th>
                                        <th style="background-color:#36abb9;color: white;" title="Present">Present</th>
                                        <th style="background-color:#36abb9;color: white;" title="Absent">Absent</th>
                                        <th style="background-color:#36abb9;color: white;" title="Assign">Assign</th>
                                        <th style="background-color:#36abb9;color: white;" title="Training">Training</th>
                                        <th style="background-color:#36abb9;color: white;" title="HalfDay">HalfDay</th>
                                        <!-- <th style="background-color:#36abb9;color: white;">Assign Status</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if(sizeof($TableULBData) > 0){
                                            $srNo = 1;
                                            foreach($TableULBData AS $KeyAtt=>$valueAtt){  
                                            ?>
                                            <tr>
                                                <td><?php echo $srNo++; ?></td>
                                                <!-- <td><a id="openModalButton" onclick="getSiteWiseExecutiveDetail('<?php echo $valueAtt['SiteName']; ?>')"><i class="fa fa-eye"></i></a></td> -->
                                                <td><?php echo $valueAtt['ULB']?></td>
                                                <td><?php echo $valueAtt['SurveyDate']?></td>
                                                <td><b><?php echo $valueAtt['Present']+$valueAtt['Absent']+$valueAtt['Assign']+$valueAtt['Training']+$valueAtt['HalfDay']; ?></b></td>
                                                <td><?php echo $valueAtt['Present']?></td>
                                                <td><?php echo $valueAtt['Absent']?></td>
                                                <td><?php echo $valueAtt['Assign']?></td>
                                                <td><?php echo $valueAtt['Training']?></td>
                                                <td><?php echo $valueAtt['HalfDay']?></td>
                                            </tr>
                                            <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>