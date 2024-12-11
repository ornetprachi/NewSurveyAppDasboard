<?php

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];

    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    
    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
    $SiteMasterTableData = array();
    $DataTableQuery = "SELECT 
                COALESCE(Site_Cd, 0) AS Site_Cd
                ,COALESCE(ClientName, '') AS ClientName
                ,COALESCE(SiteName, '') AS SiteName
                ,COALESCE(Area, '') AS Area
                ,COALESCE(Ward_No, 0) AS Ward_No
                ,COALESCE(Ac_No, 0) AS Ac_No
                ,CONVERT(VARCHAR,Site_Start_Date,23) AS Site_Start_Date
                ,CONVERT(VARCHAR,Site_End_Date,23) AS Site_End_Date
                ,COALESCE(UpdateByUser, '') AS UpdateByUser
                ,COALESCE(UpdatedDate, '') AS UpdatedDate
                ,COALESCE(SupervisorName, '') AS SupervisorName
                ,COALESCE(ManagerName, '') AS ManagerName
                ,COALESCE(Manager2, '') AS Manager2
                ,COALESCE(ClientNameM, '') AS ClientNameM
                ,COALESCE(MobileNo, '') AS MobileNo
                ,COALESCE(Remark, '') AS Remark
                ,COALESCE(KMLFile_Url, '') AS KMLFile_Url 
                ,COALESCE(ElectionName, '') AS ElectionName
                ,COALESCE(SiteStatus, '') AS SiteStatus
                FROM [Site_Master]
                ORDER BY Site_Cd DESC;";
    $SiteMasterTableData = $db->ExecutveQueryMultipleRowSALData($ULB,$DataTableQuery, $userName, $appName, $developmentMode);
  
?>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Site Master - List</h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th> Sr No </th>
                                                        <th> Site Status </th>
                                                        <th> Client Name </th>
                                                        <th> Site Name </th>
                                                        <th> Area </th>
                                                        <th> Ward No </th>
                                                        <th> Ac No </th>
                                                        <th> Site Start Date </th>
                                                        <th> Site End Date </th>
                                                        <th> Supervisor Name </th>
                                                        <th> Manager Name </th>
                                                        <th> Manager 2 </th>
                                                        <th> Election Name </th>
                                                        <th> Client Name M </th>
                                                        <th> Mobile No </th>
                                                        <th> Remark </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $srNo = 1;
                                                    if(sizeof($SiteMasterTableData) >0){
                                                        foreach($SiteMasterTableData AS $key=>$value){

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $srNo++; ?></td>
                                                        <td> 
                                                            <a href="index.php?p=site-master&Site_Cd='<?php echo $value['Site_Cd']; ?>'&action=edit">
                                                                <i style="color:#41bdcc;font-size:20px;" class="feather icon-edit"></i>
                                                            </a>
                                                            <?php if($value['SiteStatus'] == "Done"){
                                                                echo "<span class='badge badge-success' style='font-size:14px;font-weight:bold;'>".$value['SiteStatus']."</span>";
                                                            }else if($value['SiteStatus'] == "On Going"){
                                                                echo "<span class='badge badge-warning' style='font-size:14px;font-weight:bold;'>".$value['SiteStatus']."</span>";
                                                            }else if($value['SiteStatus'] == "Hold"){
                                                                echo "<span class='badge badge-danger' style='font-size:14px;font-weight:bold;'>".$value['SiteStatus']."</span>";
                                                            } ?>
                                                        </td>
                                                        <td><?php echo $value['ClientName']; ?></td>
                                                        <td><?php echo $value['SiteName']; ?></td>
                                                        <td><?php echo $value['Area']; ?></td>
                                                        <td><?php echo $value['Ward_No']; ?></td>
                                                        <td><?php echo $value['Ac_No']; ?></td>
                                                        <td><?php echo $value['Site_Start_Date']; ?></td>
                                                        <td><?php echo $value['Site_End_Date']; ?></td>
                                                        <td><?php echo $value['SupervisorName']; ?></td>
                                                        <td><?php echo $value['ManagerName']; ?></td>
                                                        <td><?php echo $value['Manager2']; ?></td>
                                                        <td><?php echo $value['ElectionName']; ?></td>
                                                        <td><?php echo $value['ClientNameM']; ?></td>
                                                        <td><?php echo $value['MobileNo']; ?></td>
                                                        <td><?php echo $value['Remark']; ?></td>
                                                    </tr>
                                                    <?php 
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th> Sr No </th>
                                                        <th> Site Status </th>
                                                        <th> Client Name </th>
                                                        <th> Site Name </th>
                                                        <th> Area </th>
                                                        <th> Ward No </th>
                                                        <th> Ac No </th>
                                                        <th> Site Start Date </th>
                                                        <th> Site End Date </th>
                                                        <th> Supervisor Name </th>
                                                        <th> Manager Name </th>
                                                        <th> Manager 2 </th>
                                                        <th> Election Name </th>
                                                        <th> Client Name M </th>
                                                        <th> Mobile No </th>
                                                        <th> Remark </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
