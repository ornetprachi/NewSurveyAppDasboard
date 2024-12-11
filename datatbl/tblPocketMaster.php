

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pocket Master - List</h4>
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
                                                        <th>Sr No</th>
                                                        <th>Pocket Name</th>
                                                        <th>Pocket Name Marathi</th>
                                                        <th>Corporator Name</th>
                                                        <th>Site No</th>
                                                        <th>KML File</th>
                                                        <th>DeActive Date</th>
                                                        <th>Is Active</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $srNo = 1;
                                                        foreach ($PocketMasterListData as $key => $value) {
                                                        ?> 
                                                            <tr>
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo $value["PocketName"]; ?></td>
                                                                <td><?php echo $value["PocketNameM"]; ?></td>
                                                                <td><?php echo $value["Corporator_Name"]; ?></td>
                                                                <td><?php echo $value["SiteName"]; ?></td>
                                                                <td><?php if(filter_var($value["KMLFile_Url"], FILTER_VALIDATE_URL)){ echo "Uploaded"; } ?></td>
                                                                <td><?php if(!empty($value["DeActiveDate"])){ echo date('d/m/Y h:i a',strtotime($value["DeActiveDate"])); } ?></td>
                                                                <td><?php if($value["IsActive"]==1){ echo "Yes"; }else{ echo "No"; } ?></td>
                                                                <td>
                                                                    <a href="index.php?p=pocket-master&action=edit&Pocket_Cd=<?php echo $value["Pocket_Cd"]; ?>"><i style="color:#41bdcc;" class="feather icon-edit"></i></a>
                                                                    <a href="index.php?p=pocket-master&action=delete&Pocket_Cd=<?php echo $value["Pocket_Cd"]; ?>"><i style="color:#41bdcc;" class="feather icon-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Pocket Name</th>
                                                        <th>Pocket Name Marathi</th>
                                                        <th>Corporator Name</th>
                                                        <th>Ward No</th>
                                                        <th>KML File</th>
                                                        <th>DeActive Date</th>
                                                        <th>Is Active</th>
                                                        <th>Action</th>
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
