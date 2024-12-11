<?php

    $db = new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
    $ULB = $_SESSION['SurveyUtility_ULB'];

    $electionCd_SocietyAssign = '';
    $electionName_SocietyAssign = '';
    $Site_Cd = '';
    $pocket_Cd = '';
    $SocietyListquery = '';
    if(
        isset($_SESSION['SurveyUA_Election_Cd']) && !empty($_SESSION['SurveyUA_Election_Cd']) && 
        isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']) &&
        isset($_SESSION['SurveyUA_SiteCd_Society_Assign']) && !empty($_SESSION['SurveyUA_SiteCd_Society_Assign']) && 
        isset($_SESSION['SurveyUA_Pocket_Cd_Society_Assign']) && !empty($_SESSION['SurveyUA_Pocket_Cd_Society_Assign'])
    ){

        $electionCd_SocietyAssign = $_SESSION['SurveyUA_Election_Cd'];
        $electionName_SocietyAssign = $_SESSION['SurveyUA_ElectionName'];
        $Site_Cd = $_SESSION['SurveyUA_SiteCd_Society_Assign'];
        if($Site_Cd == 'All'){
            $siteCondition = "";
        }else{
            $siteCondition = " AND sm.Site_Cd = $Site_Cd";
        }
        
        $pocket_Cd = $_SESSION['SurveyUA_Pocket_Cd_Society_Assign'];

        if($pocket_Cd == 'All'){
            $pocketCondition = "";
        }else{
            $pocketCondition = " AND sm.Pocket_Cd = $pocket_Cd";
        }

        $SocietyListquery = "SELECT COALESCE(sm.Society_Cd, 0 ) AS Society_Cd, 
        COALESCE(sm.SocietyName, '' ) AS SocietyName, 
        COALESCE(sm.Sector, '') AS Sector, 
        COALESCE(sm.Plot_No, '') AS PlotNo, 
        COALESCE(sm.Area , '' ) AS Area, 
        COALESCE(sm.Floor, '') AS Floor, 
        COALESCE(sm.Rooms, '') AS Rooms, 
        COALESCE(sm.TresurerName,'') AS TresurerName, 
        COALESCE(sm.TresurerMobileNo, '' ) AS TresurerMobileNo, 
        COALESCE(sm.Wing, '') AS Wing, 
        COALESCE(sm.Building_Image, '') AS Building_Image, 
        COALESCE(CONVERT(VARCHAR,sm.AssignedDate,105),'') AS AssignedDate, 
        COALESCE(sm.Executive_Cd,0) AS Executive_Cd, 
        COALESCE(sm.IsCompleted,0) AS IsCompleted,
        COALESCE(um.ExecutiveName,'') AS ExecutiveName
        FROM Society_Master sm 
        LEFT JOIN Survey_Entry_Data..Executive_Master um ON (sm.Executive_Cd = um.Executive_Cd)
        WHERE  BList_QC_UpdatedFlag = 1
        $siteCondition
        $pocketCondition
        AND (sm.IsCompleted IS NULL OR sm.IsCompleted = 0) 
        ;";
            
    // echo $SocietyListquery;  sm.ElectionName = '$electionName_SocietyAssign'  --AND COALESCE(sm.DSK_UpdatedByUser,'') != '' AND          

    $SocietyListData = $db->ExecutveQueryMultipleRowSALData($ULB, $SocietyListquery, $userName, $appName, $developmentMode);

    }else{

        $electionCd_SocietyAssign = "";
        $electionName_SocietyAssign = "";
        $Site_Cd = "";
        $siteCondition = "";
        $pocketCondition = "";
        $SocietyListData = array();

    }


?>


    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Select Societies From List</h4>
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
                                                    <!-- <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input class="form-check-input checkbox_All" type="checkbox" style=" width: 20px;
                                                        height: 20px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setSocietyCdtoAssignExecutive(this)">
                                                    </th> -->
                                                        <th>Select</th>
                                                        <th>Society Name</th>
                                                        <th>Sector</th>
                                                        <th>Plot No</th>
                                                        <th>Area</th>
                                                        <th>Rooms</th>
                                                        <th>Tresurer Name</th>
                                                        <th>Tresurer Mobile No</th>
                                                        <th>Assign Society</th>
                                                        <th>Assigned To</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($SocietyListData) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($SocietyListData as $key => $value) {
                                                        ?> 
                                                            <tr>
                                                                <!-- <td><?php echo $srNo++; ?></td> -->
                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input class="form-check-input checkbox" type="checkbox" style=" width: 20px;
                                                                height: 20px;"
                                                                value="<?php echo $value["Society_Cd"];?>" id="AssignCheckbox" onclick="setSocietyCdtoAssignExecutive()">
                                                                </td>
                                                                <td><?php echo $value["SocietyName"]; ?></td>
                                                                <td><?php echo $value["Sector"]; ?></td>
                                                                <td><?php echo $value["PlotNo"]; ?></td>
                                                                <td><?php echo $value["Area"]; ?></td>
                                                                <td><?php echo $value["Rooms"]; ?></td>
                                                                <td><?php echo $value["TresurerName"]; ?></td>
                                                                <td><?php echo $value["TresurerMobileNo"]; ?></td>
                                                                <td>
                                                                <?php 
                                                                    if($value["Executive_Cd"] == 0 && $value["IsCompleted"] == 0 ){ ?>
                                                                        <span class="badge badge-danger">Not Assigned</span>
                                                                <?php  }else if($value["Executive_Cd"] <> 0 && $value["IsCompleted"] == 0 ){ ?>
                                                                        <span class="badge badge-warning">Assigned</span>
                                                                <?php  }else if( ($value["Executive_Cd"] <> 0 || $value["Executive_Cd"] == 0) && $value["IsCompleted"] == 1 ){  ?>
                                                                        <span class="badge badge-success">Completed</span>
                                                                <?php  } ?>
                                                                </td>
                                                                <td><?php echo $value["ExecutiveName"]; ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Society Name</th>
                                                        <th>Sector</th>
                                                        <th>Plot No</th>
                                                        <th>Area</th>
                                                        <th>Rooms</th>
                                                        <th>Tresurer Name</th>
                                                        <th>Tresurer Mobile No</th>
                                                        <th>Assign Society</th>
                                                        <th>Assigned To</th>
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
