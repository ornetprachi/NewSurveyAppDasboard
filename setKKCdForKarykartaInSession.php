<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        
        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];
        $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
        
            if($ServerIP == "103.14.99.154"){
                $ServerIP =".";
                }else{
                    $ServerIP ="103.14.99.154";
                }

        if( 
            isset($_GET['KKCD']) && !empty($_GET['KKCD']) 
            ){
                  // isset($_GET['siteName']) && !empty($_GET['siteName']) && 
  
              try  
              {  
               
                  // $Site_Cd_Get = $_GET['siteName'];
                  $KKCD = $_GET['KKCD'];
  
                  $_SESSION['SurveyUA_KKCD_Karykarta'] = $KKCD;
  
                  
              } 
              catch(Exception $e)  
              {  
                  echo("Error!");  
              }
                                                                
  
          }
        else{
            //echo "ddd";
        }

    }
    if(isset($_SESSION['SurveyUA_KKCD_Karykarta'])&& !empty($_SESSION['SurveyUA_KKCD_Karykarta'])){

        $KKCd = $_SESSION['SurveyUA_KKCD_Karykarta'];
        if($KKCd != ''){
            $KKCDCon = "where KK_Cd = $KKCd";
        }else{
            $KKCDCon = "";
        }
    }else{
        $KKCd = '';
    }
    $Qury = "  SELECT  KK_DET_Cd,FullName,Mobile_No_1,Ward_No,Area,Designation_Cd,Age,Gender,CONVERT(varchar,BirthDate,23) AS BirthDate,List_No,Voter_Id 
                        FROM [$ServerIP].[MH_CH_WarRoom].dbo.Karyakarta_Details 
                        $KKCDCon;";

    $Hitchintak = $db->ExecutveQueryMultipleRowSALData($Qury, $userName, $appName, $developmentMode);

?>     
<div class="row" id="HitchintakTableDetail">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hitchintak Detail - (<?php echo sizeof($Hitchintak); ?>)</h4>
                
            </div>
            
            <div class="card-content">
                <div class="card-body card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-hover-animation table-hover table-striped" id="HitchintakTable">
                            <thead>
                                <tr>
                                    <th style="background-color:#36abb9;color: white;">Sr</th>
                                    <th style="background-color:#36abb9;color: white;">Name</th>
                                    <th style="background-color:#36abb9;color: white;">Mobile</th>
                                    <th style="background-color:#36abb9;color: white;">Ward</th>
                                    <th style="background-color:#36abb9;color: white;">Area</th>
                                    <th style="background-color:#36abb9;color: white;">Age</th>
                                    <th style="background-color:#36abb9;color: white;">Gender</th>
                                    <th style="background-color:#36abb9;color: white;" title="Birthdate">Birdt</th>
                                    <th style="background-color:#36abb9;color: white;" title="">ListNo</th>
                                    <th style="background-color:#36abb9;color: white;" title="">VoterId</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            if(sizeof($Hitchintak) > 0){
                            $SR = 1;
                            foreach($Hitchintak as $key=>$value){
                            ?>
                                <tr>
                                    <td><?php echo $SR++; ?></td>
                                    <td><?php echo $value['FullName']; ?></td>
                                    <td><?php echo $value['Mobile_No_1']; ?></td>
                                    <td><?php echo $value['Ward_No']; ?></td>
                                    <td><?php echo $value['Area']; ?></td>
                                    <td><?php echo $value['Age']; ?></td>
                                    <td><?php echo $value['Gender']; ?></td>
                                    <td><?php echo $value['BirthDate']; ?></td>
                                    <td><?php echo $value['List_No']; ?></td>
                                    <td><?php echo $value['Voter_Id']; ?></td>
                                </tr>
                                <?php } 
                                }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>