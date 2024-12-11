                
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; <?php echo date('Y'); ?><a class="text-bold-800 grey darken-2" href="http://ornettech.com" target="_blank" style="color:#41bdcc;">ORNET Technologies Pvt. Ltd.,</a>All rights Reserved</span>
            <!-- <span class="float-md-right d-none d-md-block"><i class="feather icon-heart pink"></i> &nbsp;&nbsp;<?php //echo $_SESSION['SurveyUA_ElectionName']; ?></span> -->
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->

 

    <!-- BEGIN: Vendor JS-->
    <script src="app-assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
        <script src="app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
      
    <?php
       // if(isset($_GET['p']) && $_GET['p'] == 'home-dashboard' ){ ?> 
            <script src="app-assets/vendors/js/charts/apexcharts.min.js"></script>
            <script src="app-assets/js/scripts/charts/chart-apex.js"></script>
    <?php //}?>
    <script src="app-assets/vendors/js/extensions/tether.min.js"></script>
    <script src="app-assets/vendors/js/extensions/shepherd.min.js"></script>

    <script src="app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="app-assets/vendors/js/pickers/pickadate/legacy.js"></script>

    <script src="app-assets/vendors/js/forms/select/select2.full.min.js"></script>

    <script src="app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>

    <script src="app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>

    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo"></script>
    <script src="app-assets/vendors/js/charts/gmaps.min.js"></script> -->
    <?php
        if(isset($_GET['p']) && ($_GET['p'] == 'tree-census-map')){ ?> 
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgjNW0WA93qphgZW-joXVR6VC3IiYFjfo&callback=initMap&v=weekly" async></script>
              <?php 
                if(sizeof($dataTree)>0){
            ?>
                <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap"  ></script> -->
                <!-- <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC0s06YL85Wn8zd527iZ90NB1goqW4Hxc4&callback=initMap"  ></script> -->
            <?php
                }
            ?>

            <script type="text/javascript">
                // Google Maps
                function initMap() {

<?php  
    $recordsSize = sizeof($dataTree);
    $centerLat = 19.01050332388753;
    $centerLng = 73.02787038981887;
  
        if(sizeof($dataTree)>0){
            if(!empty($dataTree[0]["Latitude"]) && $dataTree[0]["Latitude"] != '0'){
               $centerLat  = $dataTree[0]["Latitude"];
               $centerLng  = $dataTree[0]["Longitude"];
            }
        }
    
?>
    var marker;
    var markers = [];
    var infowindow = new google.maps.InfoWindow();

    const map = new google.maps.Map(document.getElementById("mapTreesSurvey"), {
        center: { lat: <?php echo $centerLat; ?>, lng: <?php echo $centerLng; ?> }, 
        mapTypeId: google.maps.MapTypeId.SATELLITE,
        zoom: 16,
    });

    function addMarkerWithTimeout(lat, lng, isHrtg, treeCenssusCd) {
        // window.setTimeout(function() {
             if(isHrtg == 'Yes'){ 
                     var   image = "app-assets/images/tree-heritage.png";
                    }else{
                       var image = "app-assets/images/tree-32.png";
                    }
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
                icon: image
            });
            google.maps.event.addListener(marker, 'click', function(e) {
                    var electionCd = document.getElementsByName('electionName')[0].value;
                    if (electionCd === '') {
                        alert("Select Corporation!!");
                    } else {
                        $.ajax({
                            type: "POST",
                            url: 'setTreeCensusMapContent.php',
                            data: {
                                electionCd: electionCd,
                                treeCensusCd: treeCenssusCd
                            },
                            beforeSend: function() { 
                               
                            },
                            success: function(dataResult) {
                                var content = dataResult;
                                infowindow.setContent(content);
                                infowindow.open(map, marker);
                               
                                // return data;
                            },
                            complete: function() {
                                    
                                }
                                //,
                                // error: function () {
                                //    alert('Error occured');
                                // }
                        });
                    }
                
            });
            // markers.push(marker);
        // }, timeout);
    }

    <?php 
    if( isset($_GET['p']) && $_GET['p'] == 'tree-census-map' &&  isset($_GET['filter_date']) && !isset($_GET['filter_type'])){
      $filterDate = $_GET['filter_date'];
          if($filterDate == "All" && $pocket_Cd != "All" ){

              if (file_exists($mapFileName)) {
                   // echo $mapFileName;
                  include $mapFileName;
              }
          }else{
              include "maps/61_AllDate.php";
              include "maps/60_AllDate.php";
              include "maps/59_AllDate.php";
              include "maps/57_AllDate.php";
              include "maps/62_AllDate.php";
              include "maps/56_AllDate.php";
              include "maps/53_AllDate.php";
              include "maps/55_AllDate.php";
              include "maps/58_AllDate.php";
              include "maps/42_AllDate.php";
              include "maps/47_AllDate.php";
              include "maps/43_AllDate.php";
              include "maps/45_AllDate.php";
              include "maps/40_AllDate.php";
              include "maps/41_AllDate.php";
              include "maps/39_AllDate.php";
              include "maps/51_AllDate.php";
          }

  
  }else{
    
    $srNo=0;
                            foreach ($dataTree as $key => $value){
                                $srNo = $srNo+1;
                            // icon: 'app-assets/images/logo/favicon.ico'
                                if($value['Latitude'] != '0' && $value['Latitude'] != 'null'){


                                    // if($value['IsHeritageTree'] == 'Yes'){ 
                                    //     $image = "app-assets/images/tree-heritage.png";
                                    // }else{
                                    //     $image = "app-assets/images/tree-32.png";
                                    // }

                                    // $executive = "";
                                    // if(strpos($value["AddedBy"], "_") !== false){
                                    //     $executiveArr = explode("_", $value["AddedBy"]);
                                    //     $executive = "  |  Survey By : ".$executiveArr[0];
                                    // }else{
                                    //     $executive = "  |  Survey By : ".$value["AddedBy"];
                                    // }

                                    // $treeUID = "";
                                    // if(!empty($value["Tree_UID"])){
                                    //     $treeUID = " : ".$value["Tree_UID"];
                                    // }
                            ?>
                                addMarkerWithTimeout('<?php echo $value["Latitude"]; ?>', '<?php echo $value["Longitude"]; ?>','<?php echo $value["IsHeritageTree"]; ?>',<?php echo $value["TreeCensusCd"]; ?>);

                                    // addMarkerWithTimeout(new google.maps.LatLng("<?php //echo $value["Latitude"]; ?>", "<?php //echo $value["Longitude"]; ?>"),'<?php //echo ($srNo * 300); ?>','<?php //echo $image; ?>','<?php //echo "<table class=\'table-bordered\'><tr><td rowspan=\'5\' ><img src=\'".$value['TreePhoto']."\' height=\'100\' width=\'80\' /></td><th> ".$value['LocalName']."  ".$treeUID."  </th></tr><tr><th> Height : ".$value["Height"]." ft.  |   Girth : ".$value["Girth"]." inch   |   Canopy : ".$value["Canopy"]." ft. </th></tr><tr><th>  ".$value['Specie']."   |  Age : ".$value["MinAge"]." - ".$value["MaxAge"]." yrs</th></tr><tr><th>Latitude : ".$value['Latitude']."  | Longitude : ".$value['Longitude']." </th></tr><tr><th>Survey Date : ".date('d/m/Y h:i a',strtotime($value['AddedDate']))."  ".$executive." </th></tr></table>"; ?>');
                            <?php
                                 
                                }
                            } }
?>
    

            }
            </script>

    <?php } ?>
    


    <!-- Data List View -->
    <script src="app-assets/vendors/js/extensions/dropzone.min.js"></script>
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script> -->
    <!-- <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script> -->
    <script src="app-assets/vendors/js/tables/datatable/dataTables.select.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    
    <!-- End Data List View -->

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="app-assets/js/core/app-menu.js"></script>
    <script src="app-assets/js/core/app.js"></script>
    <script src="app-assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="app-assets/js/scripts/pages/dashboard-analytics.js"></script> -->
    <script src="app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="app-assets/js/scripts/datatables/datatable.js"></script>

    <script src="app-assets/js/scripts/pages/app-todo.js"></script>
    
    <!-- <script src="app-assets/js/scripts/charts/gmaps/maps.js"></script> -->

    <!-- BEGIN: Page JS-->
   
    <!-- END: Page JS-->
    
    <script src="app-assets/js/scripts/pages/app-user.js"></script>
    <script src="app-assets/js/scripts/navs/navs.js"></script>

    <script src="app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>

    <script src="app-assets/js/scripts/forms/validation/form-validation.js"></script>

    <!-- Data List View -->
    <!-- <script src="app-assets/js/scripts/ui/data-list-view2.js"></script> -->
    <!-- End Data List View -->


    <!-- END: Page JS-->

    <script>
       $('.zero-configuration').DataTable();
    </script>

    <script type="text/javascript">
            
            $(document).ready(function() {
                "use strict"
                var dataListView = $('#data-list-view1').DataTable({
                    responsive: true,
                    columnDefs: [
                    {
                        orderable: false,
                        targets: 0,
                    }
                    ],
                    oLanguage: {
                    sLengthMenu: "_MENU_",
                    sSearch: ""
                    },  
                    bInfo: false,
                    pageLength: 25,
                    paging:false
                });
            });
            
    </script>

    <script type="text/javascript">
            
            $(document).ready(function() {
                "use strict"
                var dataListView = $('#dataTreeListView').DataTable({
                    responsive: true,
                    columnDefs: [
                    {
                        orderable: false,
                        targets: 0,
                    }
                    ],
                    bInfo: false,
                    pageLength: 25,
                    paging:false
                });
            });
            
    </script>

    <script type="text/javascript">
            
            $(document).ready(function() {
                "use strict"
                $('#dataTreeQCList').DataTable({
                    responsive: true,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: 0,
                        }
                    ],
                    ordering:false,
                    bInfo: false,
                    lengthChange:false,
                    pageLength: 25,
                    paging:true
                });
            });
            
    </script>
    
   <script>
        $(document).ready(function () {
            $('#BuildingSurveyWithNoOrdering').DataTable({
            // ordering: false
              columnDefs: [
              { targets: [0,1,9,10,11,12,13,14], orderable: false } // Columns 0 (Name) and 2 (Country) will not be orderable
            ]
            });
        });
    </script>
    
   <script>
        $(document).ready(function () {
            $('#SurveyQCAssignWithNoOrdering').DataTable({
             "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
             ordering: false
            });
        });
    </script>

   <script>
      $(document).ready(function() {
          "use strict"
          $('#BuildingListingQCList').DataTable({
              responsive: true,
              columnDefs: [
                  {
                      orderable: false,
                      targets: 0,
                  }
              ],
              ordering:false,
              bInfo: false,
              lengthChange:false,
              pageLength: 10,
              paging:true
          });
      });
    </script>

      <script>
      $(document).ready(function() {
          "use strict"
          $('#BuildingListingQCListGRID').DataTable({
              responsive: true,
              columnDefs: [
                  {
                      orderable: false,
                      targets: 0,
                  }
              ],
              ordering:false,
              bInfo: false,
              lengthChange:true,
              pageLength: 20,
              paging:true
          });
      });
    </script>

    <script>
      $(document).ready(function() {
          "use strict"
          $('#SurveyQCList').DataTable({
              ordering:true,
              paging:false
          });
      });
    </script>

<script type="text/javascript">
             var optionsShopSurveyStatusAll = {
          series: [{
          name: 'Verified',
          color: '#546E7A',
          data: [0.4, 5, 4.1, 6.7, 2.2, 4.3, 5.0, 2.0, 2.1, 1.0, 1.3, 2.3, 2.7, 3.4]
        }, {
          name: 'Pending',
          color: '#E91E63',
          data: [1.3, 2.3, 2.0, 0.8, 1.3, 2.7, 2.3, 5.0, 4.3, 2.3, 3.4, 3.5, 3.2, 1.2]
        }, {
          name: 'In-Review',
          color: '#00CFE8',
          data: [1.1, 1.7, 1.6, 1.1, 1.3, 1.4, 1.1, 2.1, 2.2, 1.2, 2.1, 1.9, 2.3, 1.9]
        }, {
          name: 'Rejected',
          color: '#EA5455',
          data: [1.1, 1.7, 1.5, 1.5, 2.1, 1.4, 1.1, 1.2, 0.2, 3.2, 2.1, 0.9, 2.3, 1.9]
        }],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        title: {
          text: 'Nature of Business Shop Survey Status Summary',
          align: 'center'
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],
        yaxis: [
          {
            title: {
              text: "Shops in Thousand"
            },
          }
        ],
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 30
          },
        },
        xaxis: {
          // type: 'datetime',
          // categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
          //   '01/05/2011 GMT', '01/06/2011 GMT'
          // ],
          type: 'text',
          categories: ['Agricultural', 'Automobile', 'Infrastructure', 'Energy',
            'Health', 'Farma','Chemical','Banking (BFS)', 'FMCG', 'Retail', 'Telecom', 
            'Textile', 'Transport', 'Hospitality'
          ],
        },
        legend: {
          position: 'right',
          offsetY: 60
        },
        fill: {
          opacity: 2
        }
        };

        var chartShopSurveyStatusAll = new ApexCharts(document.querySelector("#chartShopSurveyStatusAll"), optionsShopSurveyStatusAll);
        chartShopSurveyStatusAll.render();


</script>
            

<?php if(isset($_GET['p']) && ($_GET['p'] == 'zone-wise-tree-census-reports')){ ?> 
   <?php if(sizeof($dataZoneSurveyReport1)>0){ ?> 
    <script type="text/javascript">
        // var colors = ['#008FFB', '#66DA26', '#546E7A', '#E91E63', '#FF9800','#9C27B0', '#F44336'];
        var optionZoneSurveyReport1ColumnChart = {
              series: [{
                name: 'Trees Survey',
                data: [
                        <?php
                            foreach ($dataZoneSurveyReport1 as $key => $value){
                        ?>
                            <?php echo $value["TreesCount"]; ?>,
                        <?php 
                            }
                        ?>
                    ]
                }],
              chart: {
              height: 350,
              type: 'bar',
              events: {
                click: function(chart, w, e) {
                  // console.log(chart, w, e)
                }
              }
            },
            // colors: colors,
            plotOptions: {
              bar: {
                columnWidth: '45%',
                distributed: true,
              }
            },
            dataLabels: {
              enabled: false
            },
            legend: {
              show: false
            },
            xaxis: {
              categories: [
                    <?php
                        foreach ($dataZoneSurveyReport1 as $key => $value){
                    ?>
                        '<?php echo $value["NodeName"]; ?>',
                    <?php 
                        }
                    ?>
              ],
              labels: {
                style: {
                  // colors: colors,
                  fontSize: '12px'
                }
              }
            },
            title: {
              text: 'Node Wise Trees Survey Summary',
              align: 'center'
            }
            };

            var chartZoneSurveyReport1 = new ApexCharts(document.querySelector("#zoneSurveyReport1"), optionZoneSurveyReport1ColumnChart);
            chartZoneSurveyReport1.render();
    </script>
<?php } ?>


<?php if(sizeof($dataZoneTreesSpeciesSurveyReport2)>0){ ?> 
    <script type="text/javascript">
        var optionsZoneSurveySpecieReport2 = {
          series: [{
              name: 'Indiginious',
              data: [
                <?php 
                    foreach ($dataZoneTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        <?php echo $value["IndiginiousTreeCount"]; ?>,
                <?php
                    }
                ?>
              ]
            },{
              name: 'Non-Indiginious',
              data: [
                <?php 
                    foreach ($dataZoneTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        <?php echo $value["NonIndiginiousTreeCount"]; ?>,
                <?php
                    }
                ?>
              ]
            }
        ],
          chart: {
          height: 350,
          type: 'bar',
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: false,
          formatter: function (val) {
            return val + "";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: [
                <?php 
                    foreach ($dataZoneTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        "<?php echo $value["NodeName"]; ?>",
                <?php
                    }
                ?>
          ],
          position: 'bottom',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: true,
            formatter: function (val) {
              return val + "";
            }
          }
        
        },
        legend: {
          position: 'right',
          offsetY: 60
        },
        title: {
          text: 'Node Wise Trees Species Survey Summary',
          align: 'center',
        }
        };

        var chartZoneSurveyReport2ColumnChart = new ApexCharts(document.querySelector("#zoneSurveyTreesSpecieReport2"), optionsZoneSurveySpecieReport2);
        chartZoneSurveyReport2ColumnChart.render();
   </script>
<?php } ?>

<?php  } ?>


<?php if(isset($_GET['p']) && ($_GET['p'] == 'ward-wise-tree-census-reports')){ ?> 
   <?php if(sizeof($dataWardSurveyReport1)>0){ ?> 
    <script type="text/javascript">
        // var colors = ['#008FFB', '#66DA26', '#546E7A', '#E91E63', '#FF9800','#9C27B0', '#F44336'];
        var optionWardSurveyReport1ColumnChart = {
              series: [{
                name: 'Trees Survey',
                data: [
                        <?php
                            foreach ($dataWardSurveyReport1 as $key => $value){
                        ?>
                            <?php echo $value["TreesCount"]; ?>,
                        <?php 
                            }
                        ?>
                    ]
                }],
              chart: {
              height: 350,
              type: 'bar',
              events: {
                click: function(chart, w, e) {
                  // console.log(chart, w, e)
                }
              }
            },
            // colors: colors,
            plotOptions: {
              bar: {
                columnWidth: '45%',
                distributed: true,
              }
            },
            dataLabels: {
              enabled: false
            },
            legend: {
              show: false
            },
            xaxis: {
              categories: [
                    <?php
                        foreach ($dataWardSurveyReport1 as $key => $value){
                    ?>
                        '<?php echo $value["WardNameOrNum"]; ?>',
                    <?php 
                        }
                    ?>
              ],
              labels: {
                style: {
                  // colors: colors,
                  fontSize: '12px'
                }
              }
            },
            title: {
              text: 'Ward Wise Trees Survey Summary',
              align: 'center'
            }
            };

            var chartWardSurveyReport1 = new ApexCharts(document.querySelector("#wardSurveyReport1"), optionWardSurveyReport1ColumnChart);
            chartWardSurveyReport1.render();
    </script>
<?php } ?>


<?php if(sizeof($dataWardTreesSpeciesSurveyReport2)>0){ ?> 
    <script type="text/javascript">
        var optionsWardSurveySpecieReport2 = {
          series: [{
              name: 'Indiginious',
              data: [
                <?php 
                    foreach ($dataWardTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        <?php echo $value["IndiginiousTreeCount"]; ?>,
                <?php
                    }
                ?>
              ]
            },{
              name: 'Non-Indiginious',
              data: [
                <?php 
                    foreach ($dataWardTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        <?php echo $value["NonIndiginiousTreeCount"]; ?>,
                <?php
                    }
                ?>
              ]
            }
        ],
          chart: {
          height: 350,
          type: 'bar',
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
        },
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        dataLabels: {
          enabled: false,
          formatter: function (val) {
            return val + "";
          },
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        
        xaxis: {
          categories: [
                <?php 
                    foreach ($dataWardTreesSpeciesSurveyReport2 as $key => $value) {
                ?>
                        "<?php echo $value["WardNameOrNum"]; ?>",
                <?php
                    }
                ?>
          ],
          position: 'bottom',
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          crosshairs: {
            fill: {
              type: 'gradient',
              gradient: {
                colorFrom: '#D8E3F0',
                colorTo: '#BED1E6',
                stops: [0, 100],
                opacityFrom: 0.4,
                opacityTo: 0.5,
              }
            }
          },
          tooltip: {
            enabled: true,
          }
        },
        yaxis: {
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false,
          },
          labels: {
            show: true,
            formatter: function (val) {
              return val + "";
            }
          }
        
        },
        legend: {
          position: 'right',
          offsetY: 60
        },
        title: {
          text: 'Ward Wise Trees Species Survey Summary',
          align: 'center',
        }
        };

        var chartWardSurveyReport2ColumnChart = new ApexCharts(document.querySelector("#wardSurveyTreesSpecieReport2"), optionsWardSurveySpecieReport2);
        chartWardSurveyReport2ColumnChart.render();
   </script>
<?php } ?>

<?php  } ?>


    
<script>
//   $(document).ready(function () {
//     $('#SurveySummaryList').DataTable({ 
    
//       "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
//       $('#showCountBtn').click(function() {
//     var columnIndexes = [12,13,14,15,16,17]; // Indexes of the columns to show

//     var columns = table.columns().visible(true); // Hide all columns initially 

//     // columnIndexes.forEach(function(index) {
//     //   columns.column(index).visible(true);
//     // });

//     table.columns.adjust().draw(); // Adjust and redraw the DataTable after showing columns
//   });
//     });
// });
$(document).ready(function() {
  var table = $('#SurveySummaryList').DataTable({
    lengthMenu: [ [-1,20, 40, 50], ["All",20, 40, 50] ],
    columnDefs: [
      { targets: [12,13,14,15,16,17], visible: false } // Initially hide Columns 3 and 4 (indexes 2 and 3)
    ]
  });

  $('#showCountBtn').click(function() {
    var columnIndexes = [12,13,14,15,16,17]; // Indexes of the columns to show

    var columns = table.columns().visible(true); // Hide all columns initially 

    // columnIndexes.forEach(function(index) {
    //   columns.column(index).visible(true);
    // });

    table.columns.adjust().draw(); // Adjust and redraw the DataTable after showing columns
  });
});

</script>
<script>
  
  $(document).ready(function () {
    var table = $('#SurveySummaryExecutiveList').DataTable({

      lengthMenu: [ [-1,20, 40, 50], ["All",20, 40, 50] ],
      columnDefs : [
        //hide the second & fourth column
        { visible: false, targets: [11,12,13,14] }
    ]
    });
    $('#showExeCountBtn').click(function() {
    var columnIndexes = [11,12,13,14]; // Indexes of the columns to show

    var columns = table.columns().visible(true); // Hide all columns initially 
    table.columns.adjust().draw(); // Adjust and redraw the DataTable after showing columns
  });
});
</script>
<script>
  
  $(document).ready(function () {
    $('#OverallSummaryTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  
//   $(document).ready(function () {
//     $('#DateWiseSiteSurveySummaryList').DataTable({
//       "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
//       'columnDefs' : [
//         //hide the second & fourth column
//         { 'visible': false, 'targets': [11,12,13] }
//     ]
//     });
// });
$(document).ready(function () {
    var table = $('#DateWiseSiteSurveySummaryList').DataTable({

      lengthMenu: [ [-1,20, 40, 50], ["All",20, 40, 50] ],
      columnDefs : [
        //hide the second & fourth column
        { visible: false, targets: [11,12,13] }
    ]
    });
    $('#showDateSiteCountBtn').click(function() {
    var columnIndexes = [11,12,13]; // Indexes of the columns to show

    var columns = table.columns().visible(true); // Hide all columns initially 
    table.columns.adjust().draw(); // Adjust and redraw the DataTable after showing columns
  });
});
</script>
<script>
  
  $(document).ready(function () {
    $('#SiteWiseSociety').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  
</script>


<script>
  
  $(document).ready(function () {
    $('#SupervisorSummary').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});

</script>
<script>
  $(document).ready(function () {
    $('#SurveyQCDetailView').DataTable({
    //   columnDefs : [
    //     //hide the second & fourth column
    //     { visible: false, targets: [2] }
    // ],
      "lengthChange": false,
      paging:false,
      "bInfo" : false
    });
});
</script>
<script>
  $(document).ready(function () {
    $('#SurveySummaryExecutiveListClient').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  
  $(document).ready(function () {
    $('#AttendanceTable').DataTable({
      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
    });
});

</script>
<script>
  
  $(document).ready(function () {
    $('#AttendanceReportTable').DataTable({
      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
    });
});

</script>
<script>
  
  $(document).ready(function () {
    $('#SurveyQCLiveSiteWise').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
  });
</script>
<script>
  
  $(document).ready(function () {
    $('#BListTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>



<script>
    $(document).ready(function () {
        $('#AssignExecutiveToSiteTableID').DataTable({
          "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
          columns: [
            { searchable: false }, // Column 2 (not searchable)
            { searchable: false }, // Column 2 (not searchable)
            { searchable: true },  // Column 1 (searchable)
            { searchable: true },  // Column 3 (searchable)
            { searchable: true },  // Column 3 (searchable)
            { searchable: true },  // Column 3 (searchable)
            { searchable: true },  // Column 3 (searchable)
            { searchable: true },  // Column 3 (searchable)
            // Add more columns as needed
          ]
        });
    });

    $(document).ready(function() {
      var dataTable = $('#AssignExecutiveToSiteTableID').DataTable();
      $('#column1Search').on('keyup', function() {
        dataTable.column(2).search(this.value).draw();
      });
      $('#column3Search').on('keyup', function() {
        dataTable.column(3).search(this.value).draw();
      });
      $('#column4Search').on('keyup', function() {
        dataTable.column(4).search(this.value).draw();
      });
    });

    $(document).ready(function () {
        $('#AssignExecutiveToSiteTableReportID').DataTable({
          "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
        });
    });
    
     $(document).ready(function () {
        $('#AssignExecutiveToSiteTableReportIDTransfer').DataTable({
          "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
        });
    });
</script>
<script>
  
  $(document).ready(function () {
    $('#KarykartaTable').DataTable({
      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
    });
});

</script>
<script>
  $(document).ready(function () {
    $('#SPAttendanceTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  $(document).ready(function () {
    $('#DateWiseAttendanceList').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>


<!-- Survey Salary Process -->
  <script>
      $(document).ready(function () {
          $('#SalaryProcessUpdateTable').DataTable({
            "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
          columns: [
            { searchable: false },
            { searchable: true },
            { searchable: true },
            { searchable: true },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
            { searchable: false },
          ]
          });
      });
      
      $(document).ready(function() {
        var dataTable = $('#SalaryProcessUpdateTable').DataTable();
        // $('#column1SearchMain').on('keyup', function() {
        //   dataTable.column(0).search(this.value).draw();
        // });
        $('#column3SearchMain').on('keyup', function() {
          dataTable.column(1).search(this.value).draw();
        });
        $('#column4SearchMain').on('keyup', function() {
          dataTable.column(3).search(this.value).draw();
        });
        $('#column5SearchMain').on('keyup', function() {
          dataTable.column(2).search(this.value).draw();
        });
      });

      $(document).ready(function () {
        $('#referenceWiseTable').DataTable({
          "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
        });
      });

      $(document).ready(function () {
        $('.SalaryProcessedInnerList').DataTable({
          "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
        });
      });
 
     $(document).ready(function () {
        $('#ExecutiveNameWiseTable').DataTable({
          "lengthMenu": [ [20, 40, 50, -1], [20, 40, 50,"All",] ]
        });
      });
      
      $(document).ready(function () {
        $('#MobileNoWiseTable').DataTable({
          "lengthMenu": [ [20, 40, 50, -1], [20, 40, 50,"All",] ]
        });
      });

  </script>
      
<script>
  $(document).ready(function(){
      $('#show').click(function() {
        $('.displayDiv').toggle("slide");
      });
  });
</script>
<script>
</script>
<!-- Survey Salary Process -->

<script>
  $(document).ready(function () {
    $('#VibhagSummaryTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  $(document).ready(function () {
    $('#ShakhaLisstTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
  $(document).ready(function () {
    $('#SurveyQCExecutiveSiteWise').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
  $(document).ready(function () {
    $('#SocietyIssueTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
  $(document).ready(function () {
    $('#ClientListView').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
  $(document).ready(function () {
    $('#lossOfHourTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
  $(document).ready(function () {
    $('#SiteNameWiseSurveySociety').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
$(document).ready(function () {
    $('#SelectedSocietyData').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
      "columnDefs": [
        { "orderable": false, "targets": 1 } // Disable ordering for column index 2
      ]
    });
});

$(document).ready(function () {
    $('#SiteWiseSocietyIssue').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>
<script>
  

  $(document).ready(function () {
    $('#SiteNameWiseSurveyTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>  
    <script>
        $(document).ready(function() {
            "use strict"
            $('#OnClickModalView').DataTable({
                "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
            });
        });
        $(document).ready(function() {
            "use strict"
            $('#ExecutiveWiseMobileReport').DataTable({
                // "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                paging:false,
                searching:false,
                "bInfo" : false,
                // "columnDefs": [
                //   { "orderable": false, "targets": 1 },
                //   { "orderable": false, "targets": 2 }
                // ]
            });
        });
        $(document).ready(function() {
            "use strict"
            $('#MobileWiseReport').DataTable({
                // "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                paging:false,
                searching:false,
                "bInfo" : false,
                // "columnDefs": [
                //   { "orderable": false, "targets": 1 } // Disable ordering for column index 2
                // ]
            });
        });
    </script>

</body>
<!-- END: Body-->

</html>