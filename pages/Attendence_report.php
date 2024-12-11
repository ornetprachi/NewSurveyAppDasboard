<style>
    .card-body {
    -webkit-box-flex: 1;
    -webkit-flex: 1 1 auto;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 5px;
}

        .container {
            display: flex;
        }

        .card {
            flex: 1;
            margin: 10px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .card:nth-child(1) {
            border: 1px solid red;
        }

        .card:nth-child(2) {
            border: 1px solid blue;
        }

        .card:nth-child(3) {
            border: 1px solid green;
        }

        .card:nth-child(4) {
            border: 1px solid orange;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            margin-top: 0;
        }
    </style>
<?php 

$list=array();

// $noOfDaysInMonth = 31;

if(isset($_SESSION['OR_ADMIN_SELECT_YEAR']) && !empty($_SESSION['OR_ADMIN_SELECT_YEAR']) || isset($_SESSION['OR_ADMIN_SELECT_MONTH']) && !empty($_SESSION['OR_ADMIN_SELECT_MONTH'])
|| isset($_SESSION['OR_ADMIN_SELECT_DESIGNATON']) && !empty($_SESSION['OR_ADMIN_SELECT_DESIGNATON']) 
|| isset($_SESSION['OR_ADMIN_SELECT_SITE']) && !empty($_SESSION['OR_ADMIN_SELECT_SITE']) ){
    // echo "Set";
    $year = $_SESSION['OR_ADMIN_SELECT_YEAR'];
    $month = $_SESSION['OR_ADMIN_SELECT_MONTH'];
    $designation = $_SESSION['OR_ADMIN_SELECT_DESIGNATON'];
    $site = $_SESSION['OR_ADMIN_SELECT_SITE'];
}else
{
    // echo "NotSet";
    // $year = "";
    // $month = "";
    $designation ="";
    $site = "";
    $month = date('m');
   
    
   
    $year = date('Y');

    $day = date('D');
    

// for($d=1; $d<=31; $d++)
// {
//     $time=mktime(12, 0, 0, $month, $d, $year);          
//     if (date('m', $time)==$month)       
//         $list[]=date('M-d', $time);
// }
}
?>
<section id="dashboard-analytics">

    <div class="row match-height">
        <div class="col-md-12">     
            <div class="card1">
                <div class="content-body">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                        
                                <div class="col-xs-12 col-xl-4 col-md-6 col-12">
                                    <?php include 'dropdown-attendence-month-year.php'; ?>
                                </div>

                                
                                <div class="col-xs-12 col-xl-4 col-md-6 col-12">
                                    <?php include 'dropdown-designation.php'; ?>
                                </div>
                              


                                <div class="col-xs-12 col-xl-2 col-md-12 col-12" style="display: none;">
                                <!-- style="display: none;" -->
                                    <label>Selected </label>
                                    <input class="form-control" type="text" name="society_cds">
                                </div>

                                
                                <div class="col-md-2 text-right" style="margin-top: 0px;">
                                
                                <label>&nbsp; </label>
                                    <button class="btn btn-primary" type="button" onclick="getMonthlyReports('attendance-summary')">Refresh</button>
                                </div>
                                <div class="col-xs-12 col-md-4 col-xl-6" >
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <br>
                                    <img id="loaderId2" style="display:none;" src="app-assets/loader/loading.gif" width="60" height="60">
                                    <span id="idAssignSocietyMsg" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignSocietyMsgSuccess" class="btn btn-success" style="display: none;"></span>
                                    <span id="idAssignSocietyMsgFailure" class="btn btn-danger" style="display: none;"></span>
                                </div>

                                <!-- <div class="col-xs-12 col-md-6 col-xl-6 text-right">                    
                                    <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <div class="controls text-right">
                                        <button id="submitSocietyAssignDataId"  type="submit" class="btn btn-primary">Attendence </button>
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>

            
    </div>

    
  

   
 

    
    <div id='spinnerLoader1' style='display:none'>
        <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
    </div>
    <div class="row match-height">
            <?php include 'datatbl/tblAttendence.php'; ?>
    </div>

</section>


