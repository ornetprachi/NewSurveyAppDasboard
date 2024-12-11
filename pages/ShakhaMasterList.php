<section id="dashboard-analytics">



<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #F6E8DE;
}
@font-face {
  font-family: 'Gotu-Regular';
  src: url('app-assets/fonts/font-awesome/fonts/Gotu-Regular.ttf') format('truetype');
}

th,td {
  font-family: 'Gotu-Regular', Gotu-Regular;
}
</style>

<?php 
if(isset($_SESSION['Survey_Utility_MarkerType']) && !empty($_SESSION['Survey_Utility_MarkerType'])){
    $Div = 'Map';
}else{
    $Div ='Summary';
}
?>

<ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
    <li class="nav-item">
        <a class="nav-link <?php if($Div == 'Summary'){echo "active";}else{ echo "";} ?>" id="Summary-tab" data-toggle="tab" href="#Summary" aria-controls="Summary" role="tab" aria-selected="flase">Summary</a>
    </li>
    <li class="nav-item">
        <a class="nav-link " id="List-tab" data-toggle="tab" href="#List" aria-controls="List" role="tab" aria-selected="flase">List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Grid-tab" data-toggle="tab" href="#Grid" aria-controls="Grid" role="tab" aria-selected="true">Grid View</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if($Div == 'Map'){echo "active";}else{ echo "";} ?>" id="Map-tab" data-toggle="tab" href="#Map" aria-controls="Map" role="tab" aria-selected="false">Map View</a>
    </li>
</ul>
<div class="tab-content">  
    <div class="tab-pane <?php if($Div == 'Summary'){echo "active";}else{ echo "";} ?>" id="Summary" aria-labelledby="Summary-tab" role="tablist">
        <?php 
        include 'ShakhamasterSummary.php';
        ?>
    </div>          
    <div class="tab-pane " id="List" aria-labelledby="List-tab" role="tablist">
        <?php
        include 'pages/ShakhaList.php';
        ?>
    </div>
    <div class="tab-pane" id="Grid" aria-labelledby="Grid-tab" role="tablist">
        <?php 
        include 'ShakhaMasterGridView.php';
        ?>
    </div>
    <div class="tab-pane <?php if($Div == 'Map'){echo "active";}else{ echo "";} ?>" id="Map" aria-labelledby="Map-tab" role="tablist">
        <?php include 'pages/SakhaMaster.php'; ?>
    </div>
</div>  


</section>
<script>
    
</script>