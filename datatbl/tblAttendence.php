<style>
.table{
    border: #6c757d; 
    border-collapse: collapse;
    overflow-y: scroll;
}

.tr{
    border: 1px solid black;
}
</style>

<?php
  $selectedMonth = date("M"); 
        $str = $year."-".$month."-";
        $a = date('t');
        $sundayArr=array();
            for($i=1; $i<$a; $i++)
            {
                $d = $str.$i;
                $sun_date = date('Y m D d', $time = strtotime($d) );
                    if(strpos($sun_date,'Sun') )
                    {
                        $date = DateTime::createFromFormat('Y m D d', $sun_date);
                        $formatted_date = $date->format('Y-m-d');
                        array_push($sundayArr, $formatted_date);
                    
                    }
      
            }
           
?>

<?php 


        if($designation == '')
            {
                $designationcond = '';
            }else{
                $designationcond = " AND em.Designation = ' $designation ' ";
                
            }


    $query1 = "SELECT * FROM (
    
           SELECT ed.ExecutiveName,em.Designation,ed.Executive_Cd,ed.Attendance,
                DAY(EOMONTH('$year'+'-'+'$month'+'-01')) as NoOfDays,
                DATEPART(DAY, ed.SurveyDate) as TheDayNum
              
            FROM Executive_Master em 
            INNER JOIN Executive_Details ed ON ed.Executive_Cd = em.Executive_Cd 
            WHERE em.EmpStatus = 'A' 
            AND DATEPART(YEAR, ed.SurveyDate) = $year 
            AND DATEPART(MONTH, ed.SurveyDate) =  $month
        ) as SourceTable 
        PIVOT(
            max(Attendance) FOR TheDayNum in (
                [1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12],[13],[14],[15],[16],[17],[18],[19],[20],[21],[22],[23],[24],[25],[26],[27],[28],[29],[30],[31]
            ) 

       
    ) AS PivotTable";
    
        $db1=new DbOperation();
        $dataExecutiveName = $db1->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);
//    echo"<pre>"; print_r($dataExecutiveName );

// Iterate through the array
// Iterate through the array


// Display the row count
        $presentcount = 0;
        $absentcount = 0;
        $wocount = 0;
        foreach ($dataExecutiveName as $innerArray) {
        //    echo"<pre>"; print_r( $innerArray);
            foreach ($innerArray as $value) {
                if ($value === 1) {
                    $presentcount++;
                }

                if ($value === 2) {
                    $absentcount++;
                }

                if ($value === 'wo') {
                    $wocount++;
                }
            }
        }
      
      
$noOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

?>
 <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" >Absent</h5>
                <p class="card-text"> <?php echo $absentcount ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Leave </h5>
                <p class="card-text"> 00</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"> Present</h5>
                <p class="card-text"> <?php echo $presentcount ?></p>
            </div>
        </div>
        <!-- <div class="card">
            <div class="card-body">
                <h5 class="card-title">Absenteeism</h5>
                <p class="card-text"> 00</p>
            </div>
        </div> -->
    </div>
<div class="table-responsive" id="AttendenceTable">
            
    <table id="html5-extension-report" class=" table zero-configuration table-hover-animation table-striped table-hover" style="width:100%;table-layout: fixed; ">
        <thead>
            <tr style="border: 1px solid black;">
                <th style="text-align: center;width:2%; color: #1b55e2;">SrNo</th>
                <th style="text-align: center;width:10%; color: #1b55e2;">Employee</th>
                <th style="text-align: center;width:9%; color: #1b55e2;">Department</th>
                
                <th style="text-align: center; color: #1b55e2;">P</th> 
                <th style="text-align: center; color: #1b55e2;">A</th>
                <th style="text-align: center; color: #1b55e2;">WO</th>     
                <?php 
                 for($day = 1; $day <= $noOfDaysInMonth; $day++) {
                ?>
                    <th style="text-align: center; color: #1b55e2;">
                    
                    <?php 
                     $dateString = sprintf('%02d',  $day);
                    //  print_r( $dateString);
                     $MonthAndDay = sprintf('%02d-%02d',$month, $day);
                     $dayName = date('D', strtotime($dateString));
                    ?>
                    
                         <?php echo $dayName . '<br>' . $dateString . '<br>'; ?>
                    </th>   
                <?php } ?> 
                  
            </tr>
        </thead>
        <tbody>
            <?php 
                $srNo = 0;
                $srNo = $srNo+1;
                foreach ($dataExecutiveName as $key => $value) {
        
                            $found = false;
                           
                    ?>
                <tr style="border: 1px solid black;">
                    <td style="text-align: center;width:2%;"><?php echo $srNo++; ?></td>  
                    <td><?php echo $value['ExecutiveName']; ?></td>
                    <td style="text-align: center;"><?php echo $value['Designation']; ?></td>
                    
                       
                    <?php 
                        $abDays = 0;
                        $pdDays = 0;
                        $woDays = 0;
                        $clDays = 0;
                        $plDays = 0;
                        $cfDaysAdd = 0;
                        $cfDaysMinus = 0;
                        $count = 0; 

                       
                    ?>
                    <?php
                     for($j=1; $j <= $noOfDaysInMonth; $j++) { 
                        if($value["$j"]==1){
                             $pdDays = $pdDays + 1;
                               
                            }else if($value["$j"]==2){
                               $abDays = $abDays + 1;
                               
                            }
                        }
                        ?>
                        
                             
                        <td style="text-align: center;"><?php echo $pdDays; ?></td>
                             <td style="text-align: center;"><?php echo $abDays; ?></td>
                             <td style="text-align: center;"><?php echo $woDays; ?></td>    
                    <?php
                      
                        for($i=1; $i <= $noOfDaysInMonth; $i++) { 
                            $formattedDate = sprintf("%04d-%02d-%02d", $year, $month, $i);
                            $dayName = date('D', strtotime($formattedDate));
                             $attendance = "";
                           
                                if($value["$i"]==1){
                                //  $pdDays = $pdDays + 1;
                                    $attendance = "<p style='color:#126837;'>P</p>";
                                }else if($value["$i"]==2){
                                //   $abDays = $abDays + 1;
                                    $attendance = "<p style='color:red;'>A</p>";
                                }else if($value["$i"]== ""){
                                    $attendance = "-";
                                }

                                if(in_array($formattedDate,$sundayArr) && ($value["$i"] != 1) && $value["$i"] != 2){

                                    $attendance ="<p>WO</p>";
                                }

                       
                    ?>
                        <td style="text-align: center;">
                        <a onclick="getattendenceDetail('<?php echo $value['Executive_Cd'];?>','<?php echo $formattedDate;?>')">
                        <?php echo $attendance; ?>
                        </a>
                    </td>
                       
                    
                            
                    <?php } ?>                      
                </tr>

                    <?php                    
                  }
                ?>
               
                        
        </tbody>
    </table>
    </div>
    <div  id="AttendenceView" class="AttendenceView">

    </div>


