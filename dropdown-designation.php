<?php

//$query1 = "select Designation from  Executive_Master where ";

$designationLists ="SELECT  Designation
FROM Executive_Master
GROUP BY Designation";

$db1=new DbOperation();
$designationList = $db1->getSurveyUtilityExecutiveData($designationLists, $userName, $appName, $developmentMode);
// echo"<pre>";
// print_r($designationList);

$siteLists ="SELECT Site_Cd,SiteName
FROM Site_Master
";

$db1=new DbOperation();
$siteList = $db1->getSurveyUtilityExecutiveData($siteLists, $userName, $appName, $developmentMode);
// echo"<pre>";
// print_r($designationList);

?>

<!-- <div class="col-sm-12"> -->
<div class="row">
    <div class="col-sm-6">
            <div class="form-group">
                    <label>Department</label>
                    <div class="controls">
                    <!-- <select class="select2 form-control" id="selectdesignation" name="selectdesignation"> -->
                    <select class="select2 form-control" name="selectdesignation" <?php echo $designationList ?> >

                    <option value="ALL" selected>ALL</option>

                        <?php

                              if (sizeof($designationList)>0) 
                            {
                              
                                foreach ($designationList as $key => $value) 
                                  {
                                      if( $designation == $value["Designation"])
                                      {
                            ?>
                                        <option selected="true" value="<?php echo $value['Designation']; ?>"><?php echo $value["Designation"]; ?></option>
                            <?php
                                      }
                                      else
                                      {
                            ?>
                                        <option value="<?php echo $value["Designation"];?>"><?php echo $value["Designation"];?></option>
                            <?php
                                      }
                                  }
                              }
                        ?>
                              
                        
                    </select>
                    </div>
                  

                </div>
            </div>

            <div class="col-sm-6">
            <div class="form-group">
                    <label>Sites</label>
                    <div class="controls">
                    <!-- <select class="select2 form-control" id="selectdesignation" name="selectdesignation"> -->
                    <select class="select2 form-control" name="selectsite" <?php echo $siteList ?> >

                    <option value="ALL" selected>ALL</option>

                        <?php

                              if (sizeof($siteList)>0) 
                            {
                              
                                foreach ($siteList as $key => $value) 
                                  {
                                      if($site == $value["SiteName"])
                                      {
                            ?>
                                        <option selected="true" value="<?php echo $value['SiteName']; ?>"><?php echo $value["SiteName"]; ?></option>
                            <?php
                                      }
                                      else
                                      {
                            ?>
                                        <option value="<?php echo $value["SiteName"];?>"><?php echo $value["SiteName"];?></option>
                            <?php
                                      }
                                  }
                              }
                        ?>
                              
                        
                    </select>
                    </div>
                  

                </div>
            </div>
            </div>
<!-- </div> -->