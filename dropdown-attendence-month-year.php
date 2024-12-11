<style>
    label {
    display: block;
    font: 1rem 'Fira Sans', sans-serif;
}

input,
label {
    margin: 0.4rem 0;
}

</style>
<?php

    $selectedYear = date('Y');
    $already_selected_value =  $selectedYear;
    $earliest_year = 1999;
    $selectedMonth = date("m"); 
    $selectDay = date('D');
    $query1 = "SELECT 
                number,
                    DATENAME(MONTH, '$selectedYear-' + CAST(number as varchar(2)) + '-1') monthname
                FROM master..spt_values
                WHERE Type = 'P' and number between 1 and 12
                ORDER BY Number";

    $db1=new DbOperation();
    $monthList = $db1->getSurveyUtilityExecutiveData($query1, $userName, $appName, $developmentMode);
// echo"<pre>";
// print_r($monthList);

?>
 
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="selectYear">Year</label>
            <select class="select2 form-control" id="selectYear" name="selectYear">
            <?php
                  foreach (range(date('Y'), $earliest_year) as $x) {
                    if($x == $year){
                 ?>
                    <option selected  value="<?php echo $x; ?>"><?php echo $x ?></option>
                <?php
                    }  else{
                        ?>
                        <option  value="<?php echo $x; ?>"><?php echo $x ?></option>
                    <?php 
                    }                   
                } 
                ?>
                   
            
        </select>

          
          

        </div>

      
    </div>
    <div class="col-sm-6">
        <label for="selectMonth">Month</label>
        <select class="select2 form-control" id="selectMonth" name="selectMonth">
            <?php
                foreach ($monthList as $key => $valueMonth) {
                    if($valueMonth['number'] ==$month){
                ?>
                    <option Selected value="<?php echo $valueMonth['number']; ?>"><?php echo $valueMonth["monthname"]; ?></option>
                <?php
                    }  else{
                        ?>
                    <option value="<?php echo $valueMonth['number']; ?>"><?php echo $valueMonth["monthname"]; ?></option>
                <?php
                    }                   
                } 
                ?>
        </select>
    </div>
</div>

    



