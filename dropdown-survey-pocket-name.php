<?php

        $db=new DbOperation();
        $userName=$_SESSION['TREE_UserName'];
        $appName=$_SESSION['TREE_AppName'];
        $electionCd=$_SESSION['TREE_Election_Cd'];
        $electionName=$_SESSION['TREE_ElectionName'];
        $developmentMode=$_SESSION['TREE_DevelopmentMode'];
        $nodeName = "";
        $ward_Cd = "";
        $executive_Cd = "";
        $pocket_Cd = "";
        if(isset($_SESSION['TREE_NodeName'])){
            $nodeName = $_SESSION['TREE_NodeName'];
        }else{
            $nodeName = "All";
        }
        if(isset($_SESSION['TREE_WardCd'])){
            $ward_Cd = $_SESSION['TREE_WardCd'] ;
        }else{
            $ward_Cd = "All";
        }
        if(isset($_SESSION['TREE_PocketCd'])){
            $pocket_Cd = $_SESSION['TREE_PocketCd'];
        }else{
            $pocket_Cd = "All";
        }

        if(isset($_SESSION['TREE_Executive_Cd'])){
            $executive_Cd = $_SESSION['TREE_Executive_Cd'];
        }else{
            $executive_Cd = "All";
        }

        $executiveCondition = "";
        $wardCondition = "";

        if($executive_Cd == "All"){
            $executiveCondition = " AND tc.AddedBy <> '' ";
        }else{
            $addedBy = "";
            $query1 = "SELECT top (1) 
            ISNULL(um.UserName,'') as UserName
            FROM Survey_Entry_Data..User_Master um
            INNER JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = um.Executive_Cd
            WHERE um.AppName = '$appName'
            AND ISNULL(um.Executive_Cd,0) = '$executive_Cd' 
            ";
            
            $db1=new DbOperation();
            $dataExecutiveName = $db1->getTreeCensusExecutiveData($query1, $userName, $appName, $electionCd, $electionName, $developmentMode);
            if(sizeof($dataExecutiveName)>0){
                $addedBy = $dataExecutiveName[0]["UserName"];
            }
            $executiveCondition = " AND tc.AddedBy = '$addedBy' ";
        }

        if($ward_Cd == "All"){
            $wardCondition = " AND pm.WardCd <> '' ";
        }else{
            $wardCondition = " AND pm.WardCd = '$ward_Cd' ";
        }

        if(isset($_GET['filter_date']) && $_GET['filter_date'] == "All" ){
            $dateCondition = "";
        }else{
            $dateCondition = " AND CONVERT(VARCHAR,tc.AddedDate,120) BETWEEN '$fromDate' AND '$toDate' ";
        }
        
        $query = "SELECT ISNULL(tc.PocketCd,0) as PocketCd,
        ISNULL(pm.PocketName,'') as PocketName,
        ISNULL(pm.PocketNameMar,'') as PocketNameMar,
        ISNULL(wm.WardNameOrNum,'') as WardNameOrNum
        FROM PocketMaster pm
        INNER JOIN TreeCensus tc  on pm.PocketCd = tc.PocketCd
        INNER JOIN WardMaster wm on wm.WardCd = pm.WardCd
        WHERE ISNULL(pm.IsActive,0) = 1 
        $dateCondition
        $executiveCondition 
        $wardCondition 
        GROUP By tc.PocketCd, pm.PocketName, 
        pm.PocketNameMar, wm.WardNameOrNum
        ORDER By cast (wm.WardNameOrNum as int) 
        ";
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
                 <option <?php echo $pocket_Cd == 'All' ? 'selected=true' : '';  
                            if($pocket_Cd == 'All'){
                                $_SESSION['TREE_PocketCd'] = $pocket_Cd;     
                            }
            ?> value="All">All</option> 
                 <?php
                if (sizeof($dataPocket)>0) 
                {
                     if(!isset($_SESSION['TREE_PocketCd'])){
                        $_SESSION['TREE_PocketCd'] = $dataPocket[0]["PocketCd"];
                        $pocket_Cd = $_SESSION['TREE_PocketCd'];
                     }
                    foreach ($dataPocket as $key => $value) 
                      {
                          if($pocket_Cd == $value["PocketCd"])
                          {
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