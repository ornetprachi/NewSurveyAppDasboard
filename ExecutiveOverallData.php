<?php

 $sqlQuery = "SELECT * FROM 
                    (SELECT 
                    COALESCE(em.ExecutiveName,'') AS ExecutiveName , 
                    COALESCE(em.MobileNo,'') AS MobileNo , 
                    COALESCE(em.Designation,'') AS Designation , 
                    CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag,
                    CASE WHEN CONVERT(varchar, ExpDate, 103) >= CONVERT(varchar, GETDATE(), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired, 
                    COALESCE(CONVERT(varchar,em.JoiningDate,34),'') AS JoiningDate,
                    COALESCE(count(ss.Society_Cd),0) AS SocietyCount,
                    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
                    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
                    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
                    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
                    COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
                    COALESCE(sum(ss.LBS),0) AS LBS,
                    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
                    (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
                    WHERE SurveyBy = um.UserName COLLATE Latin1_General_CI_AI
                    GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays 
                    FROM DataAnalysis..SurveySummaryExecutiveDateWise AS  ss 
                    INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
                    INNER JOIN [$ServerIP].Survey_Entry_Data.dbo.User_Master as um on (ss.SurveyBy = um.UserName COLLATE Latin1_General_CI_AI) 
                    INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd )  
                    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                    WHERE elm.ULB = '$ULB' $cond
                    GROUP BY em.ExecutiveName, um.UserName,CONVERT(varchar,em.JoiningDate,34) ,um.DeactiveFlag,um.ExpDate,em.MobileNo,em.Designation
                    ) as tb1
                    $WorkForFilter
                    ORDER BY tb1.ExecutiveName;";


$ExecutiveWiseCount = $db->ExecutveQueryMultipleRowSALData($sqlQuery, $userName, $appName, $developmentMode);

$Query1 = "SELECT COALESCE(em.ExecutiveName,'') AS ExecutiveName , 
            CASE WHEN um.DeactiveFlag IS NOT NULL AND um.DeactiveFlag = 'D' THEN 'INACTIVE' ELSE 'ACTIVE' END AS DeactiveFlag,
            CASE WHEN CONVERT(varchar, ExpDate, 103) >= CONVERT(varchar, FORMAT(SYSUTCDATETIME() AT TIME ZONE 'UTC' AT TIME ZONE 'India Standard Time', 'yyyy-MM-dd HH:mm:ss.fff'), 103) THEN 'ACTIVE' ELSE 'INACTIVE' END AS Expired, 
            COALESCE(CONVERT(varchar,em.JoiningDate,34),'') AS JoiningDate,
            COALESCE(count(ss.Society_Cd),0) AS SocietyCount,
            COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
            COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
            COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
            COALESCE(sum(ss.LockRoom),0) AS LockRoom,
            COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
            COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
            COALESCE(sum(ss.LBS),0) AS LBS,
            COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
            (SELECT COUNT(*) FROM (SELECT SurveyBy, CONVERT(VARCHAR, SDate, 23) AS SurveyDate FROM DataAnalysis..SurveySummaryExecutiveDateWise 
            WHERE SurveyBy = um.UserName COLLATE Latin1_General_CI_AI
            GROUP BY SurveyBy, CONVERT(VARCHAR, SDate, 23)) AS t1) AS WorkingDays 
            FROM DataAnalysis..SurveySummaryExecutiveDateWise AS  ss 
            INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
            INNER JOIN [$ServerIP].Survey_Entry_Data.dbo.User_Master as um on (ss.SurveyBy = um.UserName COLLATE Latin1_General_CI_AI) 
            INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
            INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
            WHERE elm.ULB = '$ULB'
            AND em.Designation IN ('SE-Belapur','Survey Executive','Survey Supervisor','SP')
            GROUP BY em.ExecutiveName, um.UserName,CONVERT(varchar,em.JoiningDate,34) ,um.DeactiveFlag,um.ExpDate 
            ORDER BY em.ExecutiveName;";


$Count = $db->ExecutveQueryMultipleRowSALData($Query1, $userName, $appName, $developmentMode);
$TotalExecutive = sizeof($Count);
$ACount = 0;
$IACount = 0;
foreach($Count as $key=>$value){
    if($value['DeactiveFlag'] == 'ACTIVE'){
        $ACount++;
       
    }else{
        $IACount ++;

    }
}
$Active =$ACount;
$Inactive =  $IACount;
?>
                    <div class="card-header" style="margin-top: -15px;">
                        <div class="row">
                            <h4 class="card-title" style="padding:5px;margin-left:10px;">Summary Report - Executive Wise</h4>
                            <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showExeCountBtn" >Count</button>
                        </div>
                        <?php if($ExcelExportButton == "show"){ ?>
                            <button id="exportBtn1" style="padding:10px;" class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveySummaryExecutiveList')">Excel</button>
                        <?php } ?>
                    </div>
                    <div class="card-header" style="margin-top: -15px;">
                       <h6>Total Executive - <?php echo $TotalExecutive;?></h6>
                    </div>                     
                    &nbsp;&nbsp;
                    <div class = "row" style="margin-top: -20px;margin-bottom: -20px;">
                        <button type="button" class="btn btn-flat-success mr-1 mb-1" onclick="getExeFilter('ACTIVE')" style="padding:10px;margin-left:15px;<?php if($Filter == 'ACTIVE'){ echo "background-color:#28C76F;color: white;";} ?>">Active<?php echo "(".$Active.")" ;?></button>
                        <button type="button" class="btn btn-flat-danger mr-1 mb-1" onclick="getExeFilter('INACTIVE')" style="padding:10px;<?php if($Filter == 'INACTIVE'){ echo "background-color:#EA5455;color: white;";} ?>">InActive<?php echo "(".$Inactive.")" ; ?></button>
                        <div class="row" style="margin-left:10px;">
                            <div class="col-xs-4 col-xl-4 col-md-4 col-12">
                                <div class="form-group">
                                    <label>Working Days</label>
                                    <div class="row">
                                        <div class="col-md-5 col-12" style="margin-left:12px;padding:3px;"> 
                                            <div class="controls"> 
                                                <input type="Search" name="WorkingDaysExec"  class="form-control" placeholder="From">
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-12" style="padding:3px;"> 
                                            <div class="controls"> 
                                                <input type="Search" name="ToWorkingDaysExec"  class="form-control" placeholder="To">
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
                                    <button type="button" class="btn btn-primary" onclick="GetWorkingDateFilter()"  id="SearchBtn">
                                            Refresh 
                                    </button>
                                </div>
                                <script>
                                    document.getElementById('SearchBtn').addEventListener("click", function(){
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
                                                    <table class="table table-hover-animation table-hover" id="SurveySummaryExecutiveList" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color:#36abb9;color: white;">No</th>
                                                                <th style="background-color:#36abb9;color: white;">View</th>
                                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "Designation">Desig</th>
                                                                <!-- <th style="background-color:#36abb9;color: white;" Title = "Reference">Ref</th> -->
                                                                <th style="background-color:#36abb9;color: white;" Title = "Joining Date">JOD</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "Working Days">WD</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "Society">Soc</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "Rooms">Ro</th>
                                                                <!-- <th style="background-color:#36abb9;color: white;">Total Rooms</th> -->
                                                                <th style="background-color:#36abb9;color: white;padding-left:10px;" Title = "Voters">V</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "NonVoters">NV</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Lockroom">LR</th>
                                                                <th style="background-color:#36abb9;color: white;" Title  = "Locked But Survey">LBS</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Birthday">BirtDt</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Mobile">Mob</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Voters Ratio">V %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="NonVoters Ratio">NV %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="LockRoom Ratio">LR %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Locked But Survey Ratio">LBS %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Birthdate Ratio">BirtDt %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title ="Mobile Ratio">Mob %</th>
                                                                <th style="background-color:#36abb9;color: white;" Title = "Average">Avg</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if(sizeof($ExecutiveWiseCount) > 0 ){
                                                                $srNo = 1;
                                                                foreach ($ExecutiveWiseCount as $key => $value) {
                                                                ?> 
                                                                    <tr style="padding-top:0px;">
                                                                        <td><?php echo $srNo++; ?></td>
                                                                        <td style="color: #36abb9;">
                                                                            <a class="" onclick="getExecutiveData('<?php echo $value['ExecutiveName']?>')">
                                                                                <i class="fa fa-eye"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td Title="<?php echo $value['MobileNo']; ?>" style="cursor:pointer;<?php if(((CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '60') || 
                                                                                ((CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '70') || ((CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)) < '70' )){echo "background:#FFD6D6;" ;}else{echo "";} ?>"><?php echo "<b>" . $value["ExecutiveName"] . "</b>" ?></td>
                                                                        <td><?php if($value["Designation"] == 'SE-Belapur'){ echo "Survey Executive";}elseif($value["Designation"] == 'SP'){ echo "Survey Supervisor";}else{ echo $value["Designation"];} ?></td>
                                                                        <!-- <td><?php //echo $value["ReferenceName"]; ?></td> -->
                                                                        <td><?php echo $value["JoiningDate"]; ?></td>
                                                                        <td><?php echo $value["WorkingDays"]; ?></td>
                                                                        <td><?php echo $value["SocietyCount"]; ?></td>
                                                                        <td><?php echo $value["RoomSurveyDone"]; ?></td>
                                                                        <td><?php echo $value["TotalVoters"]; ?></td>
                                                                        <td><?php echo $value["TotalNonVoters"]; ?></td>
                                                                        <td><?php echo $value["LockRoom"]; ?></td>
                                                                        <td><?php echo $value["LBS"]; ?></td>
                                                                        <td><?php echo $value["BirthdaysCount"]; ?></td>
                                                                        <td><?php echo $value["TotalMobileCount"]; ?></td>
                                                                        <td style="<?php if((CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '60' ){echo "background:#FFD6D6" ; }else{echo "";} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)." %"; }else{echo "0";}?></td>
                                                                        <td><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)." %";}else{echo "0";} ?></td>
                                                                        <td><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)." %"; }else{echo "0";}?></td>
                                                                        <td><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)." %"; }else{echo "0";}?></td>
                                                                        <td style="<?php if((CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)) < '70' ){echo "background:#FFD6D6" ; }else{echo "";} ?>"><?php if(($value["TotalVoters"]+$value["TotalNonVoters"]) != '') { echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)." %";}else{echo "0";} ?></td>
                                                                        <td style="<?php if((CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)) < '90' ){echo "background:#FFD6D6" ; }else{echo "";} ?>"><?php if($value["RoomSurveyDone"] != '') { echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)." %";}else{echo "0";} ?></td>
                                                                        <td><?php if($value["WorkingDays"] != '') { echo  CEIL($value["RoomSurveyDone"]/$value["WorkingDays"]);}else{echo "0";} ?></td>
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
                            background-color:#36abb9 ;
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