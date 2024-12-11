<?php

        $db1=new DbOperation();
        $userName=$_SESSION['TREE_UserName'];
        $appName=$_SESSION['TREE_AppName'];
        $electionCd=$_SESSION['TREE_Election_Cd'];
        $electionName=$_SESSION['TREE_ElectionName'];
        $developmentMode=$_SESSION['TREE_DevelopmentMode'];
        $nodeName = "";
        if(isset($_SESSION['TREE_NodeName'])){
           $nodeName = $_SESSION['TREE_NodeName']; 
        }
        $query = "SELECT  
        ISNULL(NodeName,'') as NodeName   
        FROM WardMaster
        WHERE ISNULL(NodeName,'') <> ''
        GROUP BY ISNULL(NodeName,'') ";
        // echo $query;
        $nodeNameData = $db1->ExecutveQueryMultipleRowSALData($query, $electionCd, $electionName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Node Name</label>
        <div class="controls">
            <select class="select2 form-control" name="node_Name" onChange="setNodeNameInSession(this.value)" >
                <?php 
                    if( isset($_GET['p']) &&
                        (   
                            $_GET['p'] == 'tree-census-list'
                            ||  $_GET['p'] == 'tree-census-map'
                            ||  $_GET['p'] == 'tree-health-survey-reports'
                            ||  $_GET['p'] == 'tree-survey-pocket-assign'
                        )
                    ){  
                ?>
                    <option <?php echo $nodeName == 'All' ? 'selected=true' : '';
                            if($nodeName == 'All'){
                                $_SESSION['TREE_NodeName'] = $nodeName;     
                            }
                ?> value="All">All</option>
                <?php 
                    }else{
                ?>
                    <option value="">--Select--</option>
                <?php
                    } 
                ?>

                 <?php
                if (sizeof($nodeNameData)>0) 
                {
                    // if(!isset($_SESSION['TREE_NodeName'])){
                    //     $_SESSION['TREE_NodeName'] = $nodeNameData[0]["NodeName"];
                    // }
                    foreach ($nodeNameData as $key => $value) 
                      {
                          if( $nodeName == $value["NodeName"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['NodeName']; ?>"><?php echo $value["NodeName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["NodeName"];?>"><?php echo $value["NodeName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->