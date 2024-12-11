<?php
    
    $nodeName = "";
    
    $executive_Cd = "";
    $pocket_Cd = "";


    $Site_Cd = "";
    if(isset($_SESSION['SurveyUA_SiteCd'])){
        $Site_Cd = $_SESSION['SurveyUA_SiteCd'];
    }else{
        $Site_Cd = "All";
    }

    if(isset($_SESSION['SurveyUA_PocketCd'])){
        $pocket_Cd = $_SESSION['SurveyUA_PocketCd'];
    }else{
        $pocket_Cd = "All";
    }

    if(isset($_SESSION['SurveyUA_Executive_Cd_Login'])){
        $executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];
    }else{
        $executive_Cd = "All";
    }

    $dateCondition = "";
    $executiveCondition = "";
    $nodeCondition = "";
    $siteCondition = "";
    $pocketCondition = "";

    

    if(isset($_GET['filter_date'])){
        $filterDate = $_GET['filter_date'];
        if($filterDate == "All"){
            $dateCondition = "";
        }
    }else{
        $dateCondition = " AND CONVERT(VARCHAR,tc.QC_DoneDate,120) BETWEEN '$fromDate' AND '$toDate'  ";
    }

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
        $dataExecutiveName = $db1->getSurveyUtilityExecutiveData($query1, $userName, $appName, $developmentMode);
        

        if(sizeof($dataExecutiveName)>0){
            $addedBy = $dataExecutiveName[0]["UserName"];
        }
        $executiveCondition = " AND tc.AddedBy = '$addedBy' ";
    }

    if($nodeName == "All"){
        // $nodeCondition = " AND wm.NodeName <> '' ";
    }else{
        // $nodeCondition = " AND wm.NodeName = '$nodeName' ";
    }

    if($Site_Cd == "All"){
        $siteCondition = " AND pm.Site_Cd <> '' ";
    }else{
        $siteCondition = " AND pm.Site_Cd = '$Site_Cd' ";
    }

    if($pocket_Cd == "All"){
        $pocketCondition = " AND tc.PocketCd <> '' ";
    }else{
        $pocketCondition = " AND tc.PocketCd = '$pocket_Cd' ";
    }

    $queryPkt = "SELECT
                COALESCE(pm.Pocket_Cd,0) as Pocket_Cd,
                COALESCE(pm.PocketName,'') as PocketName,
                COALESCE(pm.PocketNameM,'') as PocketNameM,
                COALESCE(pm.KMLFile_Url,'') as KMLFile_Url,
                COALESCE(pm.Ward_No,0) as Ward_No,
                COALESCE(pm.Survey_Ac_No,0) as Survey_Ac_No,
                COALESCE(pm.Site_Cd,0) AS Site_Cd
                FROM Pocket_Master pm
                LEFT JOIN Site_Master sm on sm.Site_Cd = pm.Site_Cd
                WHERE pm.IsActive = 1
                AND COALESCE(pm.Executive_Cd,0) = 0 
                AND COALESCE(pm.IsCompleted,0) = 0
                ";
    $dbPktSummary=new DbOperation();
    // echo $query1;
    $dataPktSummary = $dbPktSummary->ExecutveQueryMultipleRowSALData($ULB,$queryPkt, $userName, $appName, $developmentMode);

    $query1 = "SELECT
    ISNULL(um.User_Id,0) as User_Id,
    -- ISNULL(um.UserName,'') as UserName,
    ISNULL(um.Executive_Cd,0) as Executive_Cd,
    ISNULL(um.ElectionName,'') as ElectionName,
    ISNULL(em.ExecutiveName,'') as ExecutiveName,
    ISNULL(em.MobileNo,'') as MobileNo,
    ISNULL(um.DeactiveFlag,'') as DeactiveFlag,
    ISNULL((SELECT top (1)
        pm.Executive_Cd
        FROM Pocket_Master pm 
        WHERE pm.Executive_Cd = em.Executive_Cd
        AND pm.IsActive = 1 
        ORDER By pm.UpdatedDate DESC
    ),0) as ExecutiveCd,
    ISNULL((SELECT top (1)
        pm.IsCompleted
        FROM Pocket_Master pm 
        WHERE pm.Executive_Cd = em.Executive_Cd
        AND pm.IsActive = 1 
        ORDER By pm.UpdatedDate DESC
    ),0) as IsCompleted,
    ISNULL((SELECT top (1)
        pm.Pocket_Cd 
        FROM Pocket_Master pm 
        WHERE pm.Executive_Cd = em.Executive_Cd
        AND pm.IsActive = 1 
        ORDER By pm.UpdatedDate DESC
    ),0) as Pocket_Cd,
    ISNULL((SELECT top (1)
        pm.PocketName
        FROM Pocket_Master pm 
        WHERE pm.Executive_Cd = em.Executive_Cd
        AND pm.IsActive = 1 
        ORDER By pm.UpdatedDate DESC
    ),'') as PocketName,
    ISNULL((SELECT top (1)
        convert(varchar,pm.AssignedDate,121)
        FROM Pocket_Master pm 
        WHERE pm.Executive_Cd = em.Executive_Cd
        AND pm.IsActive = 1 
        ORDER By pm.UpdatedDate DESC
    ),'') as AssignedDate,
    ISNULL((SELECT top (1)
        PocketAssignCd
        FROM PocketAssign pa 
        WHERE pa.SRExecutiveCd = em.Executive_Cd
        AND pa.PocketCd = (
            SELECT top (1)
            pm.Pocket_Cd
            FROM Pocket_Master pm 
            WHERE pm.Executive_Cd = em.Executive_Cd
            AND pm.IsActive = 1 
            ORDER By pm.UpdatedDate DESC
        )
        ORDER By pa.UpdatedDate DESC
    ),'') as PocketAssignCd
    FROM Survey_Entry_Data..User_Master um 
    LEFT JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = um.Executive_Cd
    WHERE um.AppName = 'SurveyUtilityApp' AND um.Executive_Cd <> 0
    --AND um.DeactiveFlag IS NULL 
    AND COALESCE(um.DeactiveFlag,'') = ''
    -- AND UserType = 'A' 
    AND ISNULL(em.Designation,'') IN ('SP','Survey Supervisor')
    ORDER BY ElectionName DESC";
    $db1=new DbOperation();
    // echo $query1;
    $dataAssignPocketExecSummary = $db1->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);

?> 
        <div class="col-xl-12 col-md-12 col-xs-12" id="removePocketFromExecutive">
            
        </div>

        <div class="col-xl-12 col-md-12 col-xs-12" id="openClosePocket">
            
        </div>
    
        <div class="col-xl-12 col-md-12 col-xs-12">
            <div class="card">
                
                <div class="row">
                    <div class="col-xl-11 col-md-11 col-xs-11">
                        <div class="card-header">
                            <h4 class="card-title">
                                Assign Pocket Executive Summary 
                            </h4>
                        </div>
                    </div>
                    <div class="col-xl-1 col-md-1 col-xs-1">
                        <a class="nav-link dropdown-toggle" id="dropdown-flag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="feather icon-more-vertical"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-flag">
                             <?php 
                                $srNo1 = 1;
                                    foreach ($dataPktSummary as $key => $value) {
                                        $kmlFilePresent="";
                                        if(!empty($value["KMLFile_Url"])){ $kmlFilePresent = "File Found!"; }else{ $kmlFilePresent = "Files Not Found!"; }
                                ?> 
                                     <a class="dropdown-item" href="index.php?p=pocket-master&action=edit&Pocket_Cd=<?php echo $value["Pocket_Cd"]; ?>" > <?php echo $srNo1++.") Pocket : ".$value["PocketName"]."\n KML File : ".$kmlFilePresent; ?></a>
                                <?php
                                    }
                                ?>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Pocket Open / Close</th>
                                        <th>Executive</th>
                                        <th>Mobile</th>
                                        <th>Corporation</th>
                                        <th>Pocket Name</th>
                                        <th>AssignDate</th>
                                        <th>Assign Pocket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $srNo = 1;
                                        
                                        foreach ($dataAssignPocketExecSummary as $key => $value) {
                                        ?> 
                                            <tr>
                                                <td><?php echo $srNo++; ?></td>
                                                <td>

                                                    <?php if($value["PocketName"] != '') { ?>
                                                    <div class="custom-control custom-switch switch-md custom-switch-success mr-2 mb-1">
                                                        <p class="mb-0"></p></br>
                                                        <input type="checkbox" class="custom-control-input" id="customSwitch<?php echo $value['Pocket_Cd'];?>" 
                                                        <?php if($value['IsCompleted'] == 0)
                                                        {
                                                            echo "checked";
                                                        } ?> 
                                                        
                                                        onchange="openClosePocket('<?php echo $value['User_Id']; ?>','<?php echo $value['Executive_Cd']; ?>','<?php echo $value['ExecutiveName']; ?>','<?php echo $value['Pocket_Cd']; ?>','<?php echo $value['PocketName']; ?>','<?php echo $value['PocketAssignCd']; ?>')">
                                                        <label class="custom-control-label" for="customSwitch<?php echo $value['Pocket_Cd'];?>">
                                                            <span class="switch-text-left">Open</span>
                                                            <span class="switch-text-right">Close</span>
                                                        </label>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $value["ExecutiveName"]; ?></td>
                                                <td><?php echo $value["MobileNo"]; ?></td>
                                                <td><?php echo $value["ElectionName"]; ?></td>
                                                <td><?php echo $value["PocketName"]; ?>
                                            
                                                <?php 
                                                        if($value["ExecutiveCd"] == 0 && $value["IsCompleted"] == 0 ){ ?>
                                                            <span class="badge badge-danger">Not Assigned</span>
                                                       <?php  }else if($value["ExecutiveCd"] <> 0 && $value["IsCompleted"] == 0 ){ ?>
                                                            <span class="badge badge-warning">Assigned</span>
                                                       <?php  }else if( ($value["ExecutiveCd"] <> 0 || $value["ExecutiveCd"] == 0) && $value["IsCompleted"] == 1 ){  ?>
                                                            <span class="badge badge-success">Completed</span>
                                                      <?php  } ?>
                                                    
                

                                                    </td>
                                                <td>
                                                    <?php 
                                                        if( ($value["ExecutiveCd"] <> 0 && $value["IsCompleted"] == 0 && $value["Pocket_Cd"] != 0 ) ){ 
                                                            echo date('d/m/Y h:i a', strtotime($value["AssignedDate"]));
                                                        } 
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php  if( ( $value["ExecutiveCd"] == 0 && ( $value["IsCompleted"] == 0 && $value["Pocket_Cd"] == 0 ) ||  ($value["ExecutiveCd"] <> 0 && $value["IsCompleted"] == 1)  ) ){  ?>  
                                                        <a onclick="setAssignPocketToExecutive('<?php echo $value['User_Id']; ?>','<?php echo $value['Executive_Cd']; ?>')" ><i class="feather icon-layers" style="font-size: 1.5rem;color:#41bdcc;" title="Assign Pocket"></i></a>
                                                    <?php }else if( ($value["ExecutiveCd"] <> 0 && $value["IsCompleted"] == 0) ){    //echo $value["PocketName"]; ?>
                                                        <a onclick="setRemovePocketFromExecutiveForm('<?php echo $value['User_Id']; ?>','<?php echo $value['Executive_Cd']; ?>','<?php echo $value['ExecutiveName']; ?>','<?php echo $value['Pocket_Cd']; ?>','<?php echo $value['PocketName']; ?>','<?php echo $value['PocketAssignCd']; ?>')" ><i class="feather icon-trash-2" style="font-size: 1.5rem;color:red;" title="Remove Pocket"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>