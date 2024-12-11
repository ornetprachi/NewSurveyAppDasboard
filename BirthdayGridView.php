
<?php 
$VoterCd = $_SESSION['SurveyUtility_VoterCd'];
$DBName = $_SESSION['SurveyUtility_DBName'];
 $AddreessQuery = "SELECT
                COALESCE(dw.Voter_Cd,0) AS Voter_Cd,
                COALESCE(socm.Latitude, '') AS Latitude,
                COALESCE(socm.Longitude, '') AS Longitude,
                COALESCE(socm.SocietyName, '') AS SocietyName,
                COALESCE(socm.Building_Plate_Image, '') AS Building_Plate_Image,
                COALESCE(socm.Building_Image, '') AS Building_Image
                FROM $DBName..Dw_VotersInfo dw
                INNER JOIN Survey_Entry_Data..Site_Master sm ON (sm.SiteName = dw.SiteName)
                LEFT JOIN $DBName..SubLocationMaster subm ON (subm.SubLocation_Cd = dw.SubLocation_Cd)
                LEFT JOIN Survey_Entry_Data..Society_Master socm ON (subm.Survey_Society_Cd = socm.Society_Cd)
                WHERE dw.SF = 1 
                -- AND CONVERT(varchar,CONVERT(date,dw.BirthDate,101),23) BETWEEN '1999-01-01' AND '2023-06-20'
                AND dw.Voter_Id = $VoterCd";


$SiteWise = $db->ExecutveQuerySingleRowSALData($AddreessQuery, $userName, $appName, $developmentMode);
?>
<!-- <div id="MODAL" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId"> -->
        <div class="modal-content" style="width: 80%;">
            <span id= "closeModalBtn1" class="close">&times;</span>
                <div class="card-header">
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWise')">Excel</button>
                </div>
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-hover-animation table-hover" id="SiteWise">
                                            <tbody> 
                                                        <tr colspan=3>
                                                            <td> <img src="<?php echo $SiteWise['Building_Image']; ?>" width="400px" height="300px"> </td>
                                                            <td> Address : <?php echo $SiteWise['SocietyName']; ?> </td>
                                                        </tr>
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    <!-- </div>
</div> -->
  <script>
    
    $('#closeModalBtn').click(function() {
                // Hide the modal
                $('#myModal').modal('hide');
              });
</script>