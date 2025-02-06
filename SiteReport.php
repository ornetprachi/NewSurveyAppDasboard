<ul class="nav nav-tabs" role="tablist" style="margin-left:8px;">
    <li class="nav-item">
        <a class="nav-link active" id="site-summary" data-toggle="tab" href="#Site" aria-controls="Site" role="tab"
            aria-selected="true">Site Wise Summary</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Overall-tab" data-toggle="tab" href="#Overall" aria-controls="Overall" role="tab"
            aria-selected="false">Overall Summary</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="Supervisor-tab" data-toggle="tab" href="#Supervisor" aria-controls="Supervisor"
            role="tab" aria-selected="false">Supervisor</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="Site" aria-labelledby="site-summary" role="tabpanel">
        <?php 
            include 'SiteWiseSummaryReport.php';
        ?>
    </div>

    <div class="tab-pane" id="Overall" aria-labelledby="Overall-tab" role="tabpanel">
        <?php
            include 'OverallSummaryReport.php';
        ?>
    </div>


    <div class="tab-pane" id="Supervisor" aria-labelledby="Supervisor-tab" role="tabpanel">
        <?php
            include 'SupervisorSummaryReport.php';
        ?>
    </div>
</div>