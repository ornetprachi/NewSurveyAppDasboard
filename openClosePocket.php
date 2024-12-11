<?php
if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    session_start();
    include 'api/includes/DbOperation.php'; 
    if(isset($_GET['usrId']) && !empty($_GET['usrId']) ){

        try  
        {  
            
            $usrId = $_GET['usrId'];
            $exeCd = $_GET['exeCd'];
            $exeName = $_GET['exeName'];
            $pcktCd = $_GET['pcktCd'];
            $pcktName = $_GET['pcktName'];
            $pcktAssgnCd = $_GET['pcktAssgnCd'];

            $setPocketOpenCloseRemark ="";
        ?>

<div class="card">
    <div class="card-content">
        <div class="card-body">
            <div class="row" >

                <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                    <h4> Open / Close - (   <?php echo $pcktName; ?>    ) </h4>
                        <div class="form-group">
                            <label>Pocket Open / Close Remark</label>
                            <div class="controls"> 
                                <input type="text" name="PocketOpenCloseRemark" value="<?php echo $setPocketOpenCloseRemark; ?>"  class="form-control" placeholder="Pocket Opening / Closing Remark" required >

                            </div>
                        </div>
                </div>

                <div class="col-xs-12 col-xl-5 col-md-5 col-12">
                    <h4>  </h4></br>
                        <div class="form-group">
                            <label>Pocket Status</label>
                            <div class="controls">

                                <select class="select2 form-control" name="PocketOpenCloseStatus" required>
                                <option value="">--Select Status--</option>
                                    <option value="open">Open</option>
                                    <option value="close">Close</option>   
                                </select>

                            </div>
                        </div>
                </div>

                <div class="col-xs-12 col-md-2 col-xl-2 text-right">
                     <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                     <div class="controls text-right"style="margin-top: 30px;">
                        <input type="hidden" name="usrId" value="<?php echo $usrId; ?>"  class="form-control">
                        <input type="hidden" name="exeCd" value="<?php echo $exeCd; ?>"  class="form-control">
                        <input type="hidden" name="pcktCd" value="<?php echo $pcktCd; ?>"  class="form-control">
                        <input type="hidden" name="pcktAssgnCd" value="<?php echo $pcktAssgnCd; ?>"  class="form-control">
                        <button  type="button" class="btn btn-success" onclick="setOpenClosePocketStatus()" >
                                SAVE 
                        </button>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-xl-12" >
                    <span id="idAssignPocketMsgSuccess" class="btn btn-success" style="display: none;"></span>
                    <span id="idAssignPocketMsgFailure" class="btn btn-danger" style="display: none;"></span>
                </div>

            </div>
        </div>
    </div>
</div>

    <?php    
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

    }else{
        //echo "ddd";
    }

}
?>