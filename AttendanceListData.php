<?php 
$AttListQc = "SELECT ULB,CONVERT(varchar,ed.SurveyDate,23) as SurveyDate,em.Designation,ed.SiteName, 
                ed.ExecutiveName,ed.Attendance 
                FROM [$ServerIP].[Survey_Entry_Data].[dbo].Executive_Details as ed 
                INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Site_Master as sm on (ed.SiteName = sm.SiteName) 
                INNER JOIN [$ServerIP].[Survey_Entry_Data].[dbo].Election_Master as elm on (ed.ElectionName = elm.ElectionName) 
                INNER JOIN [Survey_Entry_Data]..Executive_Master as em on (ed.Executive_Cd = em.Executive_Cd ) 
                WHERE CONVERT(varchar,ed.SurveyDate,23) BETWEEN '$fromdate' AND '$todate' 
                GROUP BY ed.Attendance,ULB,ed.SiteName,CONVERT(varchar,ed.SurveyDate,23),em.Designation,ed.ExecutiveName;";
$AttListData = $db->ExecutveQueryMultipleRowSALData($AttListQc, $userName, $appName, $developmentMode);
$SE = 0;
$SP = 0;
$SM = 0;
foreach($AttListData as $key=>$Val)
{
    if($Val["Designation"] == 'SE-Belapur' || $Val["Designation"] == 'Survey Executive'){
        $SE++;
    }
    if($Val["Designation"] == 'Survey Supervisor' || $Val["Designation"] == 'SP'){
        $SP++;
    }
    if($Val["Designation"] == 'Manager' || $Val["Designation"] == 'Site Manager'){
        $SM++;
    }
}
?>
<div class="card-header">
    <div class="row">
        <h4 class="card-title" style="padding:5px;margin-left:10px;">Attendance Report</h4>
    </div>
<?php if($ExcelExportButton == "show"){ ?>
    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','DateWiseAttendanceList')">Excel</button>
    <?php } ?>
</div>
<h4 class="card-title" style="padding:5px;margin-left:10px;"> <b>Total -(<?php echo sizeof($AttListData); ?>) Execuitve - (<?php echo $SE; ?>)  Supervisor - (<?php echo $SP; ?>) Site Manager - (<?php echo $SM; ?>)</b> </h4>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">  
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-hover-animation table-striped table-hover" id="DateWiseAttendanceList" width="100%">
                            <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;">No</th>
                                        <th style="background-color:#36abb9;color: white;">Survey Date</th>
                                        <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                        <th style="background-color:#36abb9;color: white;">Deignation</th>
                                        <th style="background-color:#36abb9;color: white;">Corporation</th>
                                        <th style="background-color:#36abb9;color: white;">Site Name</th>
                                        <th style="background-color:#36abb9;color: white;" Title= "Attendance">Att</th>
                                    </tr>
                                </thead>
            
                                <tbody>
                                    <?php
                                    if(sizeof($AttListData) > 0 ){
                                        $srNo = 1;
                                        foreach ($AttListData as $key => $value) {
                                        ?> 
                                            <tr style="padding-top:0px;">
                                                <td style="align:center;"><?php echo $srNo++; ?></td>
                                                <td style="align:center;"><?php echo $value["SurveyDate"]; ?></td>
                                                <td style="align:center;"><?php echo $value["ExecutiveName"]; ?></td>
                                                <td style="align:center;"><?php if($value["Designation"] == 'SE-Belapur' || $value["Designation"] == 'Survey Executive'){ echo "Survey Executive";}
                                                elseif($value["Designation"] == 'Survey Supervisor' || $value["Designation"] == 'SP'){ echo "Supervisor";}
                                                elseif($value["Designation"] == 'Manager' || $value["Designation"] == 'Site Manager'){ echo "Site Manager";}
                                                else{ echo $value["Designation"];} ?></td>
                                                <td style="align:center;"><?php echo $value["ULB"]; ?></td>
                                                <td style="align:center;"><?php echo $value["SiteName"]; ?></td>
                                                <td style="align:center;"><?php 
                                                if($value["Attendance"] == 1){ echo "P";}
                                                elseif($value["Attendance"] == 2){echo "A";}
                                                elseif($value["Attendance"] == 3){echo "HF";}
                                                elseif($value["Attendance"] == 4){echo "T";}
                                                elseif($value["Attendance"] == 0){echo "Assign";}
                                                else{ echo $value["Attendance"];}
                                                ; ?></td>
                                                
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