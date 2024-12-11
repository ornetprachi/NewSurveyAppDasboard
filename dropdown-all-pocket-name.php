<?php

        $db=new DbOperation();
        $userName=$_SESSION['TREE_UserName'];
        $appName=$_SESSION['TREE_AppName'];
        $electionCd=$_SESSION['TREE_Election_Cd'];
        $electionName=$_SESSION['TREE_ElectionName'];
        $developmentMode=$_SESSION['TREE_DevelopmentMode'];
        
        
        $query = "SELECT  pm.PocketName,pm.PocketNameMar,
tc.PocketCd,
count(tc.PocketCd) as Count, 
min(convert(varchar,tc.addedDate,23)) as StartDate, 
max(convert(varchar,tc.addedDate,23)) as EndDate,
        ISNULL(wm.WardNameOrNum,'') as WardNameOrNum
FROM PocketMaster pm
LEFT JOIN TreeCensus tc on pm.PocketCd = tc.PocketCd
INNER JOIN WardMaster wm on wm.WardCd = pm.WardCd
WHERE tc.IsActive = 1
AND pm.IsActive = 1
AND ISNULL(wm.WardNameOrNum,'') <> ''
group by pm.PocketName,pm.PocketNameMar,tc.PocketCd, wm.WardNameOrNum
order by cast (wm.WardNameOrNum as int), min(tc.addedDate) desc ";
        // echo $query;
    $dataPocket = $db->ExecutveQueryMultipleRowSALData($query, $electionCd, $electionName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>
         <?php if(isset($_SESSION['Form_Language']) && $_SESSION['Form_Language'] == 'English')
                                            {  
                                                echo "Wards";
                                            }
                                            else if(isset($_SESSION['Form_Language']) && $_SESSION['Form_Language'] == 'Marathi')
                                            { 
                                                echo "प्रभाग";
                                            }  
                                            ?>
                                                
        </label>
        <div class="controls">
            <select class="select2 form-control"  name="pocketName"

            >
                  
                 <?php
                if (sizeof($dataPocket)>0) 
                {
                     if(!isset($_SESSION['TREE_PocketCd'])){
                        $_SESSION['TREE_PocketCd'] = $dataPocket[0]["PocketCd"];
                        $pocket_Cd = $_SESSION['TREE_PocketCd'];
                     }else{
                        $pocket_Cd = $_SESSION['TREE_PocketCd'];
                     }
                     // if($_SESSION['TREE_PocketCd'] == "All"){
                     //    $_SESSION['TREE_PocketCd'] = $dataPocket[0]["PocketCd"];
                     //    $pocket_Cd = $_SESSION['TREE_PocketCd'];
                     // }
                    foreach ($dataPocket as $key => $value) 
                      {
                          if($pocket_Cd == $value["PocketCd"])
                          {
                            $from_Date = $value["StartDate"];
                            $to_Date = $value["EndDate"];
                            $_SESSION['TREE_FromDate'] = $from_Date ;
                            $_SESSION['TREE_ToDate'] = $to_Date;
                            $fromDate = $from_Date." ".$_SESSION['StartTime'];
                            $toDate =$to_Date." ".$_SESSION['EndTime'];

                ?>
                            <option selected="true" value="<?php echo $value['PocketCd']; ?>"><?php echo $value["PocketName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["PocketCd"];?>"><?php echo $value["PocketName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->