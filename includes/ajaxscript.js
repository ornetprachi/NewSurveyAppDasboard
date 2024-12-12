var Executive_CdArray = [];
function setElectionNameInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionNameInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function setSiteInSession(siteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setExecutiveNameInSession(executiveName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (executiveName === '') {
        alert("Please Select Executive !");
    } else {
        var queryString = "?executiveCd="+executiveName;
        ajaxRequest.open("POST", "setExecutiveNameInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setQCReportFilterInSession(qcReportFilter) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (qcReportFilter === '') {
        alert("Please Select QC Report Filter !");
    } else {
        var queryString = "?qcReportFilter="+qcReportFilter;
        ajaxRequest.open("POST", "setQCReportFilterInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setQCTypeInSession(QCType) {
    var ajaxRequest; // The variable that makes Ajax possible!

    if(QCType == 'TreeName') 
    {
    var ajaxDisplay = document.getElementById('TreeNameDiv');
    ajaxDisplay.style.display='block';
    }
    else
    {
    var ajaxDisplay = document.getElementById('TreeNameDiv');
    ajaxDisplay.style.display='none';

    }

    if(QCType == 'HealthCondition') 
    {
    var ajaxDisplay = document.getElementById('HealthConditionDiv');
    ajaxDisplay.style.display='block';
    }
    else
    {
    var ajaxDisplay = document.getElementById('HealthConditionDiv');
    ajaxDisplay.style.display='none';

    }


    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

}

function setPaginationInSession(pageNo) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (pageNo === '') {
        alert("Please Select PageNo !");
    } else {
        var queryString = "?pageNo="+pageNo;
        ajaxRequest.open("POST", "setPaginationInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}





function setTreeNameForQCValidation(TreeCd_ForQC) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() 
    {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200)
        {

            var text = ajaxRequest.responseText;
            const myArray = text.split(" ");

            var ajaxDisplay = document.getElementsByName('girth_label')[0];
            ajaxDisplay.innerHTML = 'Approx 3.94 - ' +myArray[0]+ ' inches';

            document.getElementsByName('TreeMaxGirth')[0].value = myArray[0];

            var GirthVal = document.getElementsByName('Girth')[0].value;
            if(parseFloat(GirthVal) < 3.94 || parseFloat(GirthVal) > myArray[0])
            {
                document.getElementById("girth_div").className = document.getElementById("girth_div").className + " error";

            }
            else
            {
                document.getElementById("girth_div").className = document.getElementById("girth_div").className.replace(" error", "");

            }

               
            var ajaxDisplay1 = document.getElementsByName('height_label')[0];
            ajaxDisplay1.innerHTML = 'Approx 6 - ' +myArray[1]+ ' feet';

            document.getElementsByName('TreeMaxHeight')[0].value = myArray[1];

            var HeightVal = document.getElementsByName('Height')[0].value;
            if(parseInt(HeightVal) < 6 || parseInt(HeightVal) > myArray[1])
            {
                document.getElementById("height_div").className = document.getElementById("height_div").className + " error";

            }
            else
            {
                document.getElementById("height_div").className = document.getElementById("height_div").className.replace(" error", "");

            }


        }
    }

    var electionName = document.getElementsByName('electionName')[0].value;
    
    if (electionName === '') {
        alert("Please Select Corporation !");
    } else if (TreeCd_ForQC === '') {
        alert("Please Select Tree Name !");
    } else {

        var queryString = "?electionCd="+electionName+"&TreeCd_ForQC="+TreeCd_ForQC;
        console.log(queryString);
        ajaxRequest.open("POST", "setTreeNameForQCValidation.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function getQCSummaryByQCDate(qcDate) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('executiveQCByDateData');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#spinnerLoader').hide(); 
                $('#executiveQCByDateData').show(); 
                 $('.zero-configuration').DataTable();
                $('html, body').animate({
                   scrollTop: $("#executiveQCByDateData").offset().top
               }, 500);
        }
    }
    
    var electionName = document.getElementsByName('electionName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveName = document.getElementsByName('executive_Name')[0].value;
    var nodeName = document.getElementsByName('node_Name')[0].value;
    var wardName = document.getElementsByName('wardName')[0].value;
    var pocketName = document.getElementsByName('pocketName')[0].value;
    if (electionName === '') {
        alert("Please Select Corporation!");
    }else if (qcDate === '') {
        alert("Please Select QC Date!");
    } else {
        var queryString = "?electionCd=" + electionName+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveName=" + executiveName+"&nodeName=" + nodeName+"&wardName=" + wardName+"&pocketName=" + pocketName+"&qcDate="+qcDate;
        ajaxRequest.open("POST", "setQCSummaryByQCDate.php" + queryString, true);
        ajaxRequest.send(null);
    }

}


function getQCExecSummaryByQCExec(qcDoneBy) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('executiveQCByExecData');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#spinnerLoader').hide(); 
                $('#executiveQCByExecData').show(); 
                 $('.zero-configuration').DataTable();
                $('html, body').animate({
                   scrollTop: $("#executiveQCByExecData").offset().top
               }, 500);
        }
    }
    
    var electionName = document.getElementsByName('electionName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveName = document.getElementsByName('executive_Name')[0].value;
    var nodeName = document.getElementsByName('node_Name')[0].value;
    var wardName = document.getElementsByName('wardName')[0].value;
    var pocketName = document.getElementsByName('pocketName')[0].value;
    if (electionName === '') {
        alert("Please Select Corporation!");
    }else if (qcDoneBy === '') {
        alert("Please Select QC Executive!");
    } else {
        var queryString = "?electionCd=" + electionName+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveName=" + executiveName+"&nodeName=" + nodeName+"&wardName=" + wardName+"&pocketName=" + pocketName+"&qcDoneBy="+qcDoneBy;
        ajaxRequest.open("POST", "setQCSummaryByQCExec.php" + queryString, true);
        ajaxRequest.send(null);
    }

}

function getTreeQCSurveyData(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // location.reload(true);
                window.location.href='index.php?p=tree-census-qc';
            }
        }

    var electionName = document.getElementsByName('electionName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveName = document.getElementsByName('executive_Name')[0].value;
    var nodeName = document.getElementsByName('node_Name')[0].value;
    var wardName = document.getElementsByName('wardName')[0].value;
    var pocketName = document.getElementsByName('pocketName')[0].value;
    var qcType = document.getElementsByName('qcType')[0].value;
    var TreeCd = document.getElementsByName('TreeName')[0].value;
    var HealthCondition = document.getElementsByName('HealthCondition')[0].value;

    var Date1 = fromDate.match(/(\d+)/g);
    var Date2 = toDate.match(/(\d+)/g);

    FrDate = new Date(Date1[0], Date1[1]-1, Date1[2]);
    ToDate = new Date(Date2[0], Date2[1]-1, Date2[2]);

    if (electionName === '') {
        alert("Please Select Corporation!");
    }else if (nodeName === '') {
        alert("Please Select Ward Name!");
    }
    else if(FrDate.getTime() > ToDate.getTime()){
        alert("Please Select To Date greater than From Date!");
    }
     else {
        var queryString = "?electionCd=" + electionName+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveName=" + executiveName+"&nodeName=" + nodeName+"&wardName=" + wardName+"&pocketName=" + pocketName+"&qcType=" + qcType
        +"&TreeCd=" + TreeCd+"&HealthCondition=" + HealthCondition;
        // console.log(queryString);
        ajaxRequest.open("POST", "setTreeQCSurveyData.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


function getTreeSurveyData(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }

    var electionName = document.getElementsByName('electionName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveName = document.getElementsByName('executive_Name')[0].value;
    var nodeName = document.getElementsByName('node_Name')[0].value;
    var wardName = document.getElementsByName('wardName')[0].value;
    var pocketName = document.getElementsByName('pocketName')[0].value;

    var Date1 = fromDate.match(/(\d+)/g);
    var Date2 = toDate.match(/(\d+)/g);

    FrDate = new Date(Date1[0], Date1[1]-1, Date1[2]);
    ToDate = new Date(Date2[0], Date2[1]-1, Date2[2]);

    if (electionName === '') {
        alert("Please Select Corporation!");
    }else if (nodeName === '') {
        alert("Please Select Ward Name!");
    } 
    else if(FrDate.getTime() > ToDate.getTime()){
        alert("Please Select To Date greater than From Date!");
    }
    else {
        var queryString = "?electionCd=" + electionName+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveName=" + executiveName+"&nodeName=" + nodeName+"&wardName=" + wardName+"&pocketName=" + pocketName;
        // console.log(queryString);
        ajaxRequest.open("POST", "setTreeSurveyData.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


function treeCensusQCSingleData(TreeCensusCd,SrNo){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
        var ajaxDisplay = document.getElementById('TreeCensusQCDataId');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader1').hide(); 
            $('#TreeCensusQCDataId').show(); 

            $('html, body').animate({
               scrollTop: $("#TreeCensusQCDataId").offset().top
           }, 500);
        }
    }

    var electionCd = document.getElementsByName('electionName')[0].value;

    if (electionCd === '') {
        alert("Select Corporation!!");
    } else{
        $('#spinnerLoader1').show(); 
        $('#TreeCensusQCDataId').hide(); 
        var queryString = "?TreeCensusCd=" + TreeCensusCd+"&SrNo="+SrNo+"&electionCd="+electionCd;
        ajaxRequest.open("GET", "setTreeCensusCdForQC.php" + queryString, true);
        ajaxRequest.send(null); 
    }

}

function getTreeDetailsForQCTreeCdWise(){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
        var ajaxDisplay = document.getElementById('TreeCensusQCDataId');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader1').hide(); 
            $('#TreeCensusQCDataId').show(); 

            $('html, body').animate({
               scrollTop: $("#TreeCensusQCDataId").offset().top
           }, 500);
        }
    }

    var electionCd = document.getElementsByName('electionName')[0].value;
    var TreeCensusCd = document.getElementsByName('TreeCensusCdForSearch')[0].value;
    var SrNo = 1;

    if (electionCd === '') {
        alert("Select Corporation!!");
    }
    else if (TreeCensusCd === '') {
        alert("Select TreeCensus Cd!!");
    } else{
        $('#spinnerLoader1').show(); 
        $('#TreeCensusQCDataId').hide(); 
        var queryString = "?TreeCensusCd=" + TreeCensusCd+"&SrNo="+SrNo+"&electionCd="+electionCd;
        ajaxRequest.open("GET", "setTreeCensusCdForQC.php" + queryString, true);
        ajaxRequest.send(null); 
    }

}


function treeCensusQCPhotoData(TreeCensusCd,SrNo){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
        var ajaxDisplay = document.getElementById('TreeCensusQCDataId');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader1').hide(); 
            $('#TreeCensusQCDataId').show(); 

            $('html, body').animate({
               scrollTop: $("#TreeCensusQCDataId").offset().top
           }, 500);
        }
    }

    var electionCd = document.getElementsByName('electionName')[0].value;

    if (electionCd === '') {
        alert("Select Corporation!!");
    } else{
        $('#spinnerLoader1').show(); 
        $('#TreeCensusQCDataId').hide(); 
        var queryString = "?TreeCensusCd=" + TreeCensusCd+"&SrNo="+SrNo+"&electionCd="+electionCd;
        ajaxRequest.open("GET", "setTreeCensusCdForQCPhoto.php" + queryString, true);
        ajaxRequest.send(null); 
    }

}


function treeCensusQCMultipleLocationData(TreeCensusCds,SrNo,latitude,longitude){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
        var ajaxDisplay = document.getElementById('TreeCensusQCDataId');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader1').hide(); 
            $('#TreeCensusQCDataId').show(); 

            $('html, body').animate({
               scrollTop: $("#TreeCensusQCDataId").offset().top
           }, 500);
        }
    }

    var electionCd = document.getElementsByName('electionName')[0].value;

    if (electionCd === '') {
        alert("Select Corporation!!");
    }else{
        $('#spinnerLoader1').show(); 
        $('#TreeCensusQCDataId').hide(); 
        var queryString = "electionCd="+electionCd+"&TreeCensusCds=" + TreeCensusCds+"&SrNo="+SrNo+"&latitude="+latitude+"&longitude="+longitude;
        
        ajaxRequest.open("POST", "setTreeCensusCdsMultipleLocationForQC.php", true);
        ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajaxRequest.send(queryString);
    }

}

function deleteTreeCensusTree(TreeCensusCd){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
    
    // Create a function that will receive data 
    // sent from the server and will update
    // div section in the same page.
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
          //var ajaxDisplay = document.getElementById('addTreeCensusQCDataId');
          //ajaxDisplay.innerHTML = ajaxRequest.responseText;
          location.reload(true);
       }
    }

    var electionCd = document.getElementsByName('electionName')[0].value;

    if (electionCd === '') {
        alert("Select Corporation!!");
    }else{
        var result = confirm("Are you sure! Want to delete?");
        if (result) {
            var queryString = "?TreeCensusCd=" + TreeCensusCd+"&electionCd="+electionCd;
            console.log(queryString);
            ajaxRequest.open("GET", "deleteTreeCensusTreeCd.php" + queryString, true);
            ajaxRequest.send(null);
        }
    }

}

function setAgeByGirth()
{

    var girth = document.getElementsByName('Girth')[0].value;
    var TreeMaxGirth = document.getElementsByName('TreeMaxGirth')[0].value;
    console.log(TreeMaxGirth); 
    var heritageTree = document.getElementsByName('heritageTree')[0].value;
    var TreeIsHeritageTree = document.getElementsByName('TreeIsHeritageTree')[0].value;

    if( girth >= 3.94 && girth <= parseFloat(TreeMaxGirth) )
    {

    if( girth >= 0.0 && girth < 3.93 )
    {
        alert("Girth of current tree is less than required!\nSmall trees are not allowed for survey!")
        document.getElementsByName('girth')[0].value = girth;
    }
    else if( girth >= 3.9 && girth < 15.8 )
    {
        document.getElementsByName('minAge')[0].value = 5;
        document.getElementsByName('maxAge')[0].value = 7;

        document.getElementsByName('heritageTree')[0].value = 'No';

       
    }
    else if( girth >= 15.8 && girth < 19.68 )
    {
        document.getElementsByName('minAge')[0].value = 7;
        document.getElementsByName('maxAge')[0].value = 8;

        document.getElementsByName('heritageTree')[0].value = 'No';
       
    }
    else if( girth >= 19.68 && girth < 35.5 )
    {
        document.getElementsByName('minAge')[0].value = 8;
        document.getElementsByName('maxAge')[0].value = 12;

        document.getElementsByName('heritageTree')[0].value = 'No';
       
    }
    else if( girth >= 35.5 && girth < 39.3 )
    {
        document.getElementsByName('minAge')[0].value = 12;
        document.getElementsByName('maxAge')[0].value = 15;

        document.getElementsByName('heritageTree')[0].value = 'No';
       
    }
    else if( girth >= 39.3 && girth < 66.93 )
    {
        document.getElementsByName('minAge')[0].value = 15;
        document.getElementsByName('maxAge')[0].value = 30;

        document.getElementsByName('heritageTree')[0].value = 'No';
        

       
    }
    else if( girth >= 66.93 && girth < 70 )
    {
        document.getElementsByName('minAge')[0].value = 30;
        document.getElementsByName('maxAge')[0].value = 40;

        document.getElementsByName('heritageTree')[0].value = 'No';
       
    }
    else if( girth >= 70 && girth < 80 )
    {
        document.getElementsByName('minAge')[0].value = 40;
        document.getElementsByName('maxAge')[0].value = 49;

        document.getElementsByName('heritageTree')[0].value = 'No';
       
    }
    else if( girth >= 80 )
    {
        document.getElementsByName('minAge')[0].value = 50;
        document.getElementsByName('maxAge')[0].value = 60;

       document.getElementsByName('heritageTree')[0].value = 'Yes';
       NewHeritage = document.getElementsByName('heritageTree')[0].value;

        if(NewHeritage == 'Yes' && TreeIsHeritageTree == 1)
        {
            document.getElementsByName('heritageTree')[0].value = 'Yes';
        }
        else
        {
            document.getElementsByName('heritageTree')[0].value = 'No';
        }
    }
    else
    {
        document.getElementsByName('minAge')[0].value = 0;
        document.getElementsByName('maxAge')[0].value = 0;

        document.getElementsByName('heritageTree')[0].value = 'No';

    }

    setCanopyByCrown();
    document.getElementById("girth_div").className = document.getElementById("girth_div").className.replace(" error", "");

   }

   else
   {
       alert("Tree's girth should be between " + 3.94 + " to "+ TreeMaxGirth + " inches! ");
       document.getElementById("girth_div").className = document.getElementById("girth_div").className + " error";
       
   }
}

function setCanopyByCrown()
{
    var Girth = document.getElementsByName('Girth')[0].value;
    var Height = document.getElementsByName('Height')[0].value;
    var CrownA = document.getElementsByName('CrownA')[0].value;
    var CrownB = document.getElementsByName('CrownB')[0].value;
    var TreeMaxGirth = document.getElementsByName('TreeMaxGirth')[0].value;
    var TreeMaxHeight = document.getElementsByName('TreeMaxHeight')[0].value;

    
            if(Girth != '' && Height != '' && CrownA != '' && CrownB != '')
            {
                if(Girth >= 3.94 && Girth <= parseFloat(TreeMaxGirth) )
                {

                            if(Height >= 6 && Height <= parseInt(TreeMaxHeight) )
                            {
                
                                console.log("Girth:"+Girth);
                                console.log("Height:"+Height);
                                console.log("CrownA:"+CrownA);
                                console.log("CrownB:"+CrownB);
                                var girtVal = (parseFloat(Girth) * 0.0833);
                                console.log("GirthVal:"+girtVal);
                                var crownDivVal = ( ( parseFloat(CrownA) + parseFloat(CrownB) ) / 2);
                                var crownVal = (0.25 * parseFloat(crownDivVal) );
                                console.log("CrownVal:"+crownVal);
                                //var CanopyValue = (parseFloat(girtVal) + parseFloat(Height)+ parseFloat(crownVal));
                                var CanopyValue = parseFloat(CrownA) * parseFloat(CrownB);
                                console.log(CanopyValue);
                                console.log(parseFloat(parseFloat(CanopyValue,4)).toFixed(4));
                                //document.getElementsByName('Canopy')[0].value = parseFloat(parseFloat(CanopyValue,4)).toFixed(4);
                                document.getElementsByName('Canopy')[0].value = parseFloat(CanopyValue);

                                document.getElementById("height_div").className = document.getElementById("height_div").className.replace(" error", "");

                            }
                            else
                            {
                                alert("Tree's height should be between " + 6 + " to "+ TreeMaxHeight + " feets! ");
                                document.getElementById("height_div").className = document.getElementById("height_div").className + " error";
                            }

                    document.getElementById("girth_div").className = document.getElementById("girth_div").className.replace(" error", "");

                }
                else
                {
                    alert("Tree's girth should be between " + 3.94 + " to "+ TreeMaxGirth + " inches! ");
                    document.getElementById("girth_div").className = document.getElementById("girth_div").className + " error";
                }
                            
            }
            else
            {
                alert('Please Enter Girth, Height, Crown Values!');
            }  
}

function addTreeCensusQCData(TreeCensusCd){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
    
    // Create a function that will receive data 
    // sent from the server and will update
    // div section in the same page.
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
          var ajaxDisplay = document.getElementById('addTreeCensusQCDataId');
          ajaxDisplay.innerHTML = ajaxRequest.responseText;
          //  scrollToPatientReportData();
    
       }
    }
    
    $.ajax({
        url: "setTreeCensusCdForQC.php",
         type: "POST",
         data : {'TreeCensusCd' : TreeCensusCd },
        success: function(html){
      
        }
      });

    ajaxRequest.open("GET", "modalAddTreeCensusQCData.php", true);
    ajaxRequest.send(null); 

}


function setTreeQCData(TreeCensusCd) {
    var ElectionCd = document.getElementsByName('electionName')[0].value;
    var TreeCd = document.getElementsByName('TreeName_QC')[0].value;
    var CheckStatus = document.getElementsByName('CheckStatus')[0].value;
    var Girth = document.getElementsByName('Girth')[0].value;
    var Height = document.getElementsByName('Height')[0].value;
    var minAge = document.getElementsByName('minAge')[0].value;
    var maxAge = document.getElementsByName('maxAge')[0].value;
    var heritageTree = document.getElementsByName('heritageTree')[0].value;
    var CrownA = document.getElementsByName('CrownA')[0].value;
    var CrownB = document.getElementsByName('CrownB')[0].value;
    var Canopy = document.getElementsByName('Canopy')[0].value;
    var HealthCondition = document.getElementsByName('HealthCondition_QC')[0].value;
    var LocationType = document.getElementsByName('LocationType_QC')[0].value;
    var OwnershipOfLand = document.getElementsByName('OwnershipOfLand_QC')[0].value;
    
   
    if (CheckStatus === '') {
        alert("Select Check Status!!");
    }else if (TreeCd === '') {
        alert("Select Tree Name!!");
    }else if (Girth === '') {
        alert("Enter Girth!!");
    }
    else if (Height === '') {
        alert("Enter Height!!");
    }
    else if (minAge === '') {
        alert("Enter Min Age!!");
    }
    else if (maxAge === '') {
        alert("Enter Max Age!!");
    }
    else if (heritageTree === '') {
        alert("Enter Is Heritage Tree!!");
    }
    else if (CrownA === '') {
        alert("Enter CrownA!!");
    }
    else if (CrownB === '') {
        alert("Enter CrownB!!");
    }
    else if (Canopy === '') {
        alert("Enter Canopy!!");
    }
    else if (HealthCondition === '') {
        alert("Enter Health Condition!!");
    }
    else if (LocationType === '') {
        alert("Enter Location Type!!");
    }
    else if (OwnershipOfLand === '') {
        alert("Enter Ownership Of Land!!");
    }
    else {


                        $.ajax({
                            type: "POST",
                            url: 'action/saveTreeCensusQCData.php',
                            data: {
                                ElectionCd : ElectionCd,
                                TreeCensusCd: TreeCensusCd,
                                TreeCd: TreeCd,
                                CheckStatus: CheckStatus,
                                Girth: Girth,
                                Height: Height,
                                minAge: minAge,
                                maxAge: maxAge,
                                heritageTree: heritageTree,
                                CrownA: CrownA,
                                CrownB: CrownB,
                                Canopy: Canopy,
                                HealthCondition: HealthCondition,
                                LocationType: LocationType,
                                OwnershipOfLand: OwnershipOfLand
                                
                            },
                            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                                $('#submitTreeQCDataID').attr("disabled", true);
                                $('html').addClass("ajaxLoading");
                            },
                            success: function(dataResult) {

                                //alert('yes');

                                $('#TreeCensusQCDataId').modal('hide');
                                window.location.reload();
                                var dataResult = JSON.parse(dataResult);
                                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                                   
                                    $("#submitmsgsuccess").html(dataResult.msg)
                                        .hide().fadeIn(800, function() {
                                            $("submitmsgsuccess").append("");
                                            window.location.href = 'index.php?p=tree-census-qc';
                                        }).delay(3000).fadeOut("fast");

                                } else if (dataResult.statusCode == 203) {
                                    $("#submitmsgfailed").html(dataResult.msg)
                                        .hide().fadeIn(800, function() {
                                            $("submitmsgfailed").append("");
                                            window.location.href = 'index.php?p=tree-census-qc';
                                        }).delay(3000).fadeOut("fast");

                                } else if (dataResult.statusCode == 404 ) {
                                    $("#submitmsgfailed").html(dataResult.msg)
                                        .hide().fadeIn(800, function() {
                                            $("submitmsgfailed").append("");
                                        }).delay(3000).fadeOut("fast");

                                }
                               
                                // return data;
                            },
                            complete: function() {
                                    $('#submitTreeQCDataID').attr("disabled", false);
                                    $('html').removeClass("ajaxLoading");
                                }
                                // error: function () {
                                //    alert('Error occured');
                                // }
                        });

    }
}



function submitWardMasterFormData() {
    var electionCd = document.getElementsByName('electionName')[0].value;
    var wardCd = document.getElementsByName('wardCd')[0].value;
    var wardNameOrNumber = document.getElementsByName('wardNameOrNumber')[0].value;
    var wardNameOrNumberMar = document.getElementsByName('wardNameOrNumberMar')[0].value;
    var nodeName = document.getElementsByName('nodeName')[0].value;
    var deActiveDate = document.getElementsByName('deActiveDate')[0].value;
    var isActive = document.getElementsByName('isActive')[0].value;
    var action = document.getElementsByName('action')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (wardNameOrNumber === '') {
        alert("Enter Ward !!");
    } 
    // else if (nodeName === '') {
    //     alert("Enter Node !!");
    // } 
    else {
        $.ajax({
            type: "POST",
            url: 'action/saveWardMasterFormData.php',
            data: {
                electionCd: electionCd,
                wardCd: wardCd,
                wardNameOrNumber: wardNameOrNumber,
                wardNameOrNumberMar: wardNameOrNumberMar,
                nodeName: nodeName,
                deActiveDate: deActiveDate,
                isActive: isActive,
                action: action
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#submitWardMasterBtnId').attr("disabled", true);
                $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                   
                    $("#submitmsgsuccess").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgsuccess").append("");
                            window.location.href = 'index.php?p=ward-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 203) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                            window.location.href = 'index.php?p=ward-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 404 ) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                        }).delay(3000).fadeOut("fast");

                }
               
                // return data;
            },
            complete: function() {
                    $('#submitWardMasterBtnId').attr("disabled", false);
                    $('html').removeClass("ajaxLoading");
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}

function submitPopUpMasterFormData() {
    var electionCd = document.getElementsByName('electionName')[0].value;
    var DropDownCd = document.getElementsByName('DropDownCd')[0].value;
    var DType = document.getElementsByName('dtype')[0].value;
    var DValue = document.getElementsByName('dvalue')[0].value;
    var DescriptionValue = document.getElementsByName('descriptionValue')[0].value;
    var deActiveDate = document.getElementsByName('deActiveDate')[0].value;
    var isActive = document.getElementsByName('isActive')[0].value;
    var action = document.getElementsByName('action')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (DType === '') {
        alert("Enter DType !!");
    } else if (DValue === '') {
        alert("Enter DValue !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/savePopUpMasterFormData.php',
            data: {
                electionCd: electionCd,
                DropDownCd: DropDownCd,
                DType: DType,
                DValue: DValue,
                DescriptionValue: DescriptionValue,
                deActiveDate: deActiveDate,
                isActive: isActive,
                action: action
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#submitPopUpMasterBtnId').attr("disabled", true);
                $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                   
                    $("#submitmsgsuccess").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgsuccess").append("");
                            window.location.href = 'index.php?p=dropdown-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 203) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                            window.location.href = 'index.php?p=dropdown-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 404 ) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                        }).delay(3000).fadeOut("fast");

                }
               
                // return data;
            },
            complete: function() {
                    $('#submitPopUpMasterBtnId').attr("disabled", false);
                    $('html').removeClass("ajaxLoading");
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}

//Tree Master Functions Starts from Here   

function onlyNumberKey(evt) {
          
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}

function isNumberKey(evt, obj) {

    var charCode = (evt.which) ? evt.which : event.keyCode
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains)
        if (charCode == 46) return false;
    if (charCode == 46) return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function fileValidation() {
    var fileInput = 
        document.getElementById('file');
      
    var filePath = fileInput.value;
  
    // Allowing file type
    var allowedExtensions = 
            /(\.jpg|\.jpeg|\.png)$/i;


    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type!!!\njpg, jpeg & png allowed only');
        fileInput.value = '';
        return false;
    }
    if (typeof (fileInput.files) != "undefined") {

        var size = parseFloat(fileInput.files[0].size / 1024).toFixed(2); 

        if(size > 80) {

            alert('Please select image size less than 80 KB');
            fileInput.value = '';
            return false;
        }
    }
    else 
    {
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(
                    'imagePreview').innerHTML = 
                    '<img src="' + e.target.result
                    + '"/>';
            };
              
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

function PreviewImage() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("uploadPreview").src = oFREvent.target.result;
    };
};

function fileValidation1() {
    var fileInput = 
        document.getElementById('file1');
      
    var filePath = fileInput.value;
    // Allowing file type
    var allowedExtensions = 
            /(\.jpg|\.jpeg|\.png)$/i;

    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type!!!\njpg, jpeg & png allowed only');
        fileInput.value = '';
        return false;
    } 
    if (typeof (fileInput.files) != "undefined") {

        var size = parseFloat(fileInput.files[0].size / 1024).toFixed(2); 

        if(size > 80) {

            alert('Please select image size less than 80 KB');
            fileInput.value = '';
            return false;
        }
    }
    else 
    {
      
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(
                    'imagePreview').innerHTML = 
                    '<img src="' + e.target.result
                    + '"/>';
            };
              
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

function PreviewImage1() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file1").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("uploadPreview1").src = oFREvent.target.result;
    };
};

function fileValidation2() {
    var fileInput = 
        document.getElementById('file2');
      
    var filePath = fileInput.value;
  
    // Allowing file type
    var allowedExtensions = 
            /(\.jpg|\.jpeg|\.png)$/i;


    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type!!!\njpg, jpeg & png allowed only');
        fileInput.value = '';
        return false;
    } 
    if (typeof (fileInput.files) != "undefined") {

        var size = parseFloat(fileInput.files[0].size / 1024).toFixed(2); 

        if(size > 80) {

            alert('Please select image size less than 80 KB');
            fileInput.value = '';
            return false;
        }
    }
    else 
    {
      
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(
                    'imagePreview').innerHTML = 
                    '<img src="' + e.target.result
                    + '"/>';
            };
              
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

function PreviewImage2() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file2").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("uploadPreview2").src = oFREvent.target.result;
    };
};

function fileValidation3() {

    var fileInput = 
        document.getElementById('file3');
      
    var filePath = fileInput.value;
  
    // Allowing file type
    var allowedExtensions = 
            /(\.jpg|\.jpeg|\.png)$/i;


    if (!allowedExtensions.exec(filePath)) {
        alert('Invalid file type!!!\njpg, jpeg & png allowed only');
        fileInput.value = '';
        return false;
    }
    
    if (typeof (fileInput.files) != "undefined") {

        var size = parseFloat(fileInput.files[0].size / 1024).toFixed(2); 

        if(size > 80) {

            alert('Please select image size less than 80 KB');
            fileInput.value = '';
            return false;
        }
    }
         
    else 
    {
        // Image preview
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(
                    'imagePreview').innerHTML = 
                    '<img src="' + e.target.result
                    + '"/>';
            };
              
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

function PreviewImage3() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("file3").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("uploadPreview3").src = oFREvent.target.result;
    };
};

function submitTreeMasterFormData() {

    var electionCd = document.getElementsByName('electionName')[0].value;
    var TreeCd = document.getElementsByName('TreeCd')[0].value;
    var localName = document.getElementsByName('localName')[0].value;
    var scientificName = document.getElementsByName('scientificName')[0].value;
    var descriptionValue = document.getElementsByName('descriptionValue')[0].value;
    var remark = document.getElementsByName('remark')[0].value;
    var deActiveDate = document.getElementsByName('deActiveDate')[0].value;
    var isActive = document.getElementsByName('isActive')[0].value;
    var action = document.getElementsByName('action')[0].value;
    var Specie = document.getElementsByName('Specie')[0].value;
    var Family = document.getElementsByName('Family')[0].value;
    var Phenology = document.getElementsByName('Phenology')[0].value;
    var EconomicImp = document.getElementsByName('EconomicImp')[0].value;
    var FloweringSeasons = document.getElementsByName('FloweringSeasons')[0].value;
    var ColourOfFlower = document.getElementsByName('ColourOfFlower')[0].value;
    var Genera = document.getElementsByName('Genera')[0].value;
    var FruitSeason = document.getElementsByName('FruitSeason')[0].value;
    var ColourOfFruit = document.getElementsByName('ColourOfFruit')[0].value;
    var medicinalusage = document.getElementsByName('medicinalusage')[0].value;
    var MinGirth = document.getElementsByName('MinGirth')[0].value;
    var MaxGirth = document.getElementsByName('MaxGirth')[0].value;
    var MinHeight = document.getElementsByName('MinHeight')[0].value;
    var MaxHeight = document.getElementsByName('MaxHeight')[0].value;
    var MinCanopy = document.getElementsByName('MinCanopy')[0].value;
    var MaxCanopy = document.getElementsByName('MaxCanopy')[0].value;
    var isHeritageTree = document.getElementsByName('isHeritageTree')[0].value;
    var MinCrownRadius = document.getElementsByName('MinCrownRadius')[0].value;
    var MaxCrownRadius = document.getElementsByName('MaxCrownRadius')[0].value;
    var TreePhoto1 = document.getElementsByName("file")[0].value; 
    var TreePhoto = document.getElementsByName("file")[0].files; 
    var FruitPhoto = document.getElementsByName("file1")[0].files;
    var FlowerPhoto = document.getElementsByName("file2")[0].files;
    var LeafPhoto = document.getElementsByName("file3")[0].files;

    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (localName === '') {
        alert("Enter Local Name !!");
    } 
    else if (scientificName === '') {
        alert("Enter Scientific Name !!");
    }
    else if (action === 'Insert' && TreePhoto1 === '') {
        alert("Choose Tree Photo !!");
    }
    
    else {

            var formData = new FormData();
            formData.append("file", TreePhoto[0]);
            formData.append("file1", FruitPhoto[0]);
            formData.append("file2", FlowerPhoto[0]);
            formData.append("file3", LeafPhoto[0]);
            formData.append("electionCd", electionCd);
            formData.append("TreeCd", TreeCd);
            formData.append("localName", localName);
            formData.append("scientificName", scientificName);
            formData.append("descriptionValue", descriptionValue);
            formData.append("remark", remark);
            formData.append("deActiveDate", deActiveDate);
            formData.append("isActive", isActive);
            formData.append("action", action);
            formData.append("Specie", Specie);
            formData.append("Family", Family);
            formData.append("Phenology", Phenology);
            formData.append("EconomicImp", EconomicImp);
            formData.append("FloweringSeasons", FloweringSeasons);
            formData.append("ColourOfFlower", ColourOfFlower);
            formData.append("Genera", Genera);
            formData.append("FruitSeason", FruitSeason);
            formData.append("ColourOfFruit", ColourOfFruit);
            formData.append("medicinalusage", medicinalusage);
            formData.append("MinGirth", MinGirth);
            formData.append("MaxGirth", MaxGirth);
            formData.append("MinHeight", MinHeight);
            formData.append("MaxHeight", MaxHeight);
            formData.append("MinCanopy", MinCanopy);
            formData.append("MaxCanopy", MaxCanopy);
            formData.append("isHeritageTree", isHeritageTree);
            formData.append("MinCrownRadius", MinCrownRadius);
            formData.append("MaxCrownRadius", MaxCrownRadius);
            
            formData.append("TreePhoto2", TreePhoto2);
            formData.append("FruitPhoto2", FruitPhoto2);
            formData.append("FlowerPhoto2", FlowerPhoto2);
            formData.append("LeafPhoto2", LeafPhoto2);
        

// Send request with data
//xhttp.send(formData);

        $.ajax({

            type: "POST",
            enctype: 'multipart/form-data',
            url: 'action/saveTreeMasterFormData.php',
            data : formData,
            mimeTypes:"multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                console.log("Before Sending");

                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#submitTreeMasterBtnId').attr("disabled", true);
                $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {

                console.log(dataResult);

                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                   
                    $("#submitmsgsuccess").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgsuccess").append("");
                            window.location.href = 'index.php?p=tree-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 203) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                            window.location.href = 'index.php?p=tree-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 404 ) {

                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                        }).delay(3000).fadeOut("fast");

                }
               
                // return data;
            },
            complete: function() {

                    $('#submitTreeMasterBtnId').attr("disabled", false);
                    $('html').removeClass("ajaxLoading");
                }
                 //error: function () {
                 //  alert('Error occured');
                 //}
        });
    }
}

//End of Functions of Tree Master Page



function submitPocketMasterFormData() {

    
    var electionCd = document.getElementsByName('electionName')[0].value;
    var PocketCd = document.getElementsByName('PocketCd')[0].value;
    var PocketName = document.getElementsByName('PocketName')[0].value;
    var PocketNameMar = document.getElementsByName('PocketNameMar')[0].value;
    var wardName = document.getElementsByName('wardName')[0].value;
    var KMLFile_Url = document.getElementsByName('KMLFile_Url')[0].value;
    var deActiveDate = document.getElementsByName('deActiveDate')[0].value;
    var isActive = document.getElementsByName('isActive')[0].value;
    var action = document.getElementsByName('action')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (PocketName === '') {
        alert("Enter Pocket Name !!");
    } else if (PocketNameMar === '') {
        alert("Enter Pocket Name in Marathi!!");
    } else if (wardName === '') {
        alert("Select Ward!!");
    } else {
        $.ajax({

            type: "POST",
            enctype: 'multipart/form-data',
            url: 'action/savePocketMasterFormData.php',
            data: {
                electionCd: electionCd,
                PocketCd: PocketCd,
                PocketName: PocketName,
                PocketNameMar: PocketNameMar,
                wardName: wardName,
                KMLFile_Url: KMLFile_Url,
                deActiveDate: deActiveDate,
                isActive: isActive,
                action: action
            },
            beforeSend: function() {

                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPocketMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {

                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                   
                    $("#submitmsgsuccess").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgsuccess").append("");
                            window.location.href = 'index.php?p=pocket-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 203) {
                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                            window.location.href = 'index.php?p=pocket-master';
                        }).delay(3000).fadeOut("fast");

                } else if (dataResult.statusCode == 404 ) {

                    $("#submitmsgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("submitmsgfailed").append("");
                        }).delay(3000).fadeOut("fast");

                }
               
                // return data;
            },
            complete: function() {

                    // $('#submitPocketMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                }
                 //error: function () {
                 //  alert('Error occured');
                 //}
        });
    }
}

function submitTreeQCPhotoData(TreeCensusCd,TreeCensusCdCopyFrom, qcPhotoType){
    var electionCd = document.getElementsByName('electionName')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (TreeCensusCd === '') {
        alert("Select Tree Surveyed!!");
    } else if (TreeCensusCdCopyFrom === '') {
        alert("Select Tree Surveyed to be Copied From !!");
    } else if (qcPhotoType === '') {
        alert("Select QC Photo Type !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/saveTreeCensusQCPhotoData.php',
            data: {
                electionCd: electionCd,
                TreeCensusCd: TreeCensusCd,
                TreeCensusCdCopyFrom: TreeCensusCdCopyFrom,
                qcPhotoType: qcPhotoType
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                $("#"+TreeCensusCd+TreeCensusCdCopyFrom+"spinnerLoader").show();
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 || dataResult.statusCode == 204 || dataResult.statusCode == 206 ) {
                    if(dataResult.photo !== null && dataResult.photo !== '') {
                        var htmlResponse = '<img src="'+dataResult.photo+'" class="rounded" width="100%" height="220" alt="Avatar" title="Tree Photo" />';
                        $("#"+TreeCensusCd+"treePhotoQCId").html(htmlResponse);
                    }
                    $("#"+TreeCensusCd+"treePhotoQCMSGSuccess").html(dataResult.msg);
                       

                } else if (dataResult.statusCode == 404 ) {
                    $("#"+TreeCensusCd+"treePhotoQCMSGFailed").html(dataResult.msg);
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                    $("#"+TreeCensusCd+TreeCensusCdCopyFrom+"spinnerLoader").hide();
                     $('html, body').animate({
                       scrollTop: $("#"+TreeCensusCd+"treePhotoQCId").offset().top
                   }, 500);
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


function submitTreeQCMapLocationData(increment,TreeCensusCd,latitude,longitude){
    var electionCd = document.getElementsByName('electionName')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (TreeCensusCd === '') {
        alert("Select Tree Surveyed!!");
    } else if (latitude === '') {
        alert("Select Location - latitude !!");
    } else if (longitude === '') {
        alert("Select Location - longitude !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/saveTreeCensusQCLocationData.php',
            data: {
                electionCd: electionCd,
                TreeCensusCd: TreeCensusCd,
                latitude: latitude,
                longitude: longitude
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200  ) {
                   
                    $("#trCnCdSaveSuc"+TreeCensusCd).html("<h4> Location for "+TreeCensusCd+" Updated!</h4>");

                } else if (dataResult.statusCode == 204 ) {
                    $("#trCnCdSaveErr"+TreeCensusCd).html("Saving "+dataResult.msg);
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


function setTreeQCAllMapLocation(srrNo){
    var electionCd = document.getElementsByName('electionName')[0].value;
    var AllData = document.getElementsByName(srrNo)[0].value;
    console.log(AllData);
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (AllData === '') {
        alert("Please Select Tree's Data!!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/saveTreeCensusQCAllLocationData.php',
            data: {
                electionCd: electionCd,
                AllData: AllData
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200  ) {
                   
                    $("#trCnDataSaveSuc"+srrNo).html("<h4> Location Updated!</h4>");
                    //$("#trCnDataAllData"+srrNo).html(dataResult);

                } else if (dataResult.statusCode == 204 ) {
                    
                    $("#trCnDataSaveErr"+srrNo).html("Saving "+dataResult.msg);
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}



function removeTreeQCMapLocationData(increment,TreeCensusCd,latitude,longitude){
    var electionCd = document.getElementsByName('electionName')[0].value;
   
    if (electionCd === '') {
        alert("Select Corporation!!");
    } else if (TreeCensusCd === '') {
        alert("Select Tree Surveyed!!");
    } else if (latitude === '') {
        alert("Select Location - latitude !!");
    } else if (longitude === '') {
        alert("Select Location - longitude !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/removeTreeCensusQCLocationData.php',
            data: {
                electionCd: electionCd,
                TreeCensusCd: TreeCensusCd,
                latitude: latitude,
                longitude: longitude
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 ) {
                   
                    $("#trCnCdRemoveSuc"+TreeCensusCd).html("<h4> Location for "+TreeCensusCd+" Updated!</h4>");

                } else if (dataResult.statusCode == 204 ) {
                    $("#trCnCdRemoveErr"+TreeCensusCd).html("Removing "+dataResult.msg);
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


function setAssignPocketToExecutive(userId,executiveCd){
    // var electionCd = document.getElementsByName('electionName')[0].value;
    var pocketName = document.getElementsByName('pocketName')[0].value;
    var Ac_No = document.getElementsByName('Ac_No')[0].value;
    var assignDate = document.getElementsByName('assignDate')[0].value;
   
   if (pocketName === '') {
        alert("Select Pocket!!");
    } else if (assignDate === '') {
        alert("Enter Assign Date !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/setAssignPocketToExecutiveData.php',
            data: {
                // electionCd: electionCd,
                pocketName: pocketName,
                Ac_No: Ac_No,
                assignDate: assignDate,
                userId: userId,
                executiveCd:executiveCd
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                
                $("#idAssignPocketMsg").html("<h5> Please wait... </h5>")
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 ) {
                    // $("#idAssignPocketMsgSuccess").style.display='block';
                    $("#idAssignPocketMsgSuccess").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                } else if (dataResult.statusCode == 204 ) {
                    // $("#idAssignPocketMsgFailure").style.display='block';
                    $("#idAssignPocketMsgFailure").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            // window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


function setRemovePocketFromExecutiveForm(usrId,exeCd,exeName,pcktCd,pcktName,pcktAssgnCd) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('removePocketFromExecutive');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('html, body').animate({
                   scrollTop: $("#removePocketFromExecutive").offset().top - 100
               }, 500);
        }
    }


    var queryString = "?usrId="+usrId+"&exeCd="+exeCd+"&exeName="+exeName+"&pcktCd="+pcktCd+"&pcktName="+pcktName+"&pcktAssgnCd="+pcktAssgnCd;
    ajaxRequest.open("POST", "setRemovePocketFromExecutiveForm.php" + queryString, true);
    ajaxRequest.send(null);

}

function openClosePocket(usrId,exeCd,exeName,pcktCd,pcktName,pcktAssgnCd) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('openClosePocket');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('html, body').animate({
                   scrollTop: $("#openClosePocket").offset().top - 100
               }, 500);
        }
    }


    var queryString = "?usrId="+usrId+"&exeCd="+exeCd+"&exeName="+exeName+"&pcktCd="+pcktCd+"&pcktName="+pcktName+"&pcktAssgnCd="+pcktAssgnCd;
    ajaxRequest.open("POST", "openClosePocket.php" + queryString, true);
    ajaxRequest.send(null);

}


function setRemovePocketFromExecutiveData(){
    // var electionCd = document.getElementsByName('electionName')[0].value;
    var usrId = document.getElementsByName('usrId')[0].value;
    var exeCd = document.getElementsByName('exeCd')[0].value;
    var pcktCd = document.getElementsByName('pcktCd')[0].value;
    var pcktAssgnCd = document.getElementsByName('pcktAssgnCd')[0].value;
    var srPocketRemoveRemark = document.getElementsByName('srPocketRemoveRemark')[0].value;
   
    if (usrId === '') {
        alert("Select User!!");
    } else if (exeCd === '') {
        alert("Select Executive !!");
    } else if (srPocketRemoveRemark === '') {
        alert("Enter Remark !!");
    } else {
        $.ajax({
            type: "POST",
            url: 'action/setRemovePocketFromExecutiveData.php',
            data: {
                // electionCd: electionCd,
                usrId: usrId,
                exeCd: exeCd,
                pcktCd: pcktCd,
                pcktAssgnCd: pcktAssgnCd,
                srPocketRemoveRemark:srPocketRemoveRemark
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                // $("#idAssignPocketMsg").style.display='block';
                // $("#idAssignPocketMsg").html("<h5> Please wait... </h5>")
            },
            success: function(dataResult) {
                // alert(dataResult);
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 ) {
                    // $("#idAssignPocketMsgSuccess").style.display='block';
                    $("#idAssignPocketMsgSuccess").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                } else if (dataResult.statusCode == 204 ) {
                    // $("#idAssignPocketMsgFailure").style.display='block';
                    $("#idAssignPocketMsgFailure").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            // window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


function setOpenClosePocketStatus(){
    // var electionCd = document.getElementsByName('electionName')[0].value;
    var usrId = document.getElementsByName('usrId')[0].value;
    var exeCd = document.getElementsByName('exeCd')[0].value;
    var pcktCd = document.getElementsByName('pcktCd')[0].value;
    var pcktAssgnCd = document.getElementsByName('pcktAssgnCd')[0].value;
    var PocketOpenCloseRemark = document.getElementsByName('PocketOpenCloseRemark')[0].value;
    var PocketOpenCloseStatus = document.getElementsByName('PocketOpenCloseStatus')[0].value;
   
    // if (electionCd === '') {
    //     alert("Select Corporation!!");
    // } else 
    if (usrId === '') {
        alert("Select User!!");
    } else if (exeCd === '') {
        alert("Select Executive !!");
    } else if (PocketOpenCloseRemark === '') {
        alert("Enter Remark !!");
    }
    else if (PocketOpenCloseStatus === '') {
        alert("Select Status !!");
    } else {
        // electionCd: electionCd,
        $.ajax({
            type: "POST",
            url: 'action/setOpenClosePocketStatus.php',
            data: {
                usrId: usrId,
                exeCd: exeCd,
                pcktCd: pcktCd,
                pcktAssgnCd: pcktAssgnCd,
                PocketOpenCloseRemark:PocketOpenCloseRemark,
                PocketOpenCloseStatus:PocketOpenCloseStatus
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#submitPopUpMasterBtnId').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
                // $("#idAssignPocketMsg").style.display='block';
                // $("#idAssignPocketMsg").html("<h5> Please wait... </h5>")
               
            },
            success: function(dataResult) {
                
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200 ) {
                    // $("#idAssignPocketMsgSuccess").style.display='block';
                    $("#idAssignPocketMsgSuccess").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                } else if (dataResult.statusCode == 204 ) {
                    // $("#idAssignPocketMsgFailure").style.display='block';
                    $("#idAssignPocketMsgFailure").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");
                            // window.location.href = 'index.php?p=survey-utility-pocket-assign';
                        }).delay(3000).fadeOut("fast");
                }
               
                // return data;
            },
            complete: function() {
                    // $('#submitPopUpMasterBtnId').attr("disabled", false);
                    // $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}


// Society Assign Functions Starts Here

function setElectionNameSocietyAssignInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionSocietyAssignInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setSiteSocietyAssignInSession(siteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteSocietyAssignInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setPocketSocietyAssignInSession(PocketName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (PocketName === '') {
        alert("Please Select Pocket!");
    } else {
        var queryString = "?PocketName="+PocketName;
        ajaxRequest.open("POST", "setPocketSocietyAssignInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function setAssignSocietyToExecutive(){
    var siteCd = document.getElementsByName('siteName')[0].value;
    var pocketCd = document.getElementsByName('pocketName')[0].value;
    var assignDate = document.getElementsByName('assignDate')[0].value;
    var executiveCd = document.getElementsByName('executiveName')[0].value;
    var society_cds = document.getElementsByName('society_cds')[0].value;
    
    
   
    if(siteCd === '') {
        alert("Select Site!!");
    } 
    else if(pocketCd === '') {
        alert("Select Pocket!!");
    }
    else if(assignDate === '') {
        alert("Enter Assign Date !!");
    } 
    else if(executiveCd === '') {
        alert("Please Select Executive !!");
    }
    else if(society_cds === '') {
        alert("Please Select Society !!");
    }
    else {
        $.ajax({
            type: "POST",
            url: 'action/setAssignSocietyToExecutiveData.php',
            data: {
                siteCd: siteCd,
                pocketCd: pocketCd,
                assignDate: assignDate,
                executiveCd: executiveCd,
                society_cds: society_cds
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#loaderId2').show();
                $('#submitSocietyAssignDataId').attr("disabled", true);
                // $("#idAssignSocietyMsg").html("<h5> Please wait... </h5>")
            },
            success: function(dataResult) {
            
                var dataResult = JSON.parse(dataResult);              
                
                if (dataResult.statusCode == 200 ) {
                    console.log(dataResult);
                    // $("#idAssignSocietyMsgSuccess").style.display='block';
                    $("#idAssignSocietyMsgSuccess").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignPocketMsgFailure").append("");

                            window.location.href = 'index.php?p=survey-utility-society-assign';

                        }).delay(3000).fadeOut("fast");
                } else if (dataResult.statusCode == 204 ) {
                    // $("#idAssignSocietyMsgFailure").style.display='block';
                    $("#idAssignSocietyMsgFailure").html("<h5> "+dataResult.msg+" </h5>")
                        .hide().fadeIn(800, function() {
                            // $("idAssignSocietyMsgFailure").append("");
                            window.location.href = 'index.php?p=survey-utility-society-assign';
                        }).delay(3000).fadeOut("fast");
                }
               
                // return data;
            },
            complete: function() {
                    $('#loaderId2').hide();
                    $('#submitSocietyAssignDataId').attr("disabled", false);
                    $('html').removeClass("ajaxLoading");
                   
                }
                // error: function () {
                //    alert('Error occured');
                // }
        });
    }
}



function setSocietyCdtoAssignExecutive() {
    var input = document.getElementsByClassName("checkbox");
    
    var selected = 0;
    var chkAllCDS = "";
    //var chkAllNames = "";
    var chkAllAssignedCount = 0;
 
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
          var splits = input[i].value.split(",");
          var CD_Val = '';
          var Name_Val = '';
          //var AssCount = 0;
 
          CD_Val += ""+splits[0]+"";
          chkAllCDS += CD_Val+",";  
          console.log(chkAllCDS);
 
          selected ++;
        }
      
    }
    document.getElementsByName("society_cds")[0].value = "" + chkAllCDS;
  }

// Society Assign functions Ends Here

// Dashboard Function Starts Here
function setElectionNameForDashboardInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionNameInSessionForDashboard.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setSiteForDashboardInSession(siteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteForDashboardInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}




function getAssignedSocietyData() {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('societySurveyExecutiveData');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;

                $('#spinnerLoader1').hide(); 
                $('#societySurveyExecutiveData').show(); 

                $('.zero-configuration').DataTable();
                
                $('html, body').animate({
                   scrollTop: $("#societySurveyExecutiveData").offset().top
               }, 500);
        }
    }

    // var siteName = document.getElementsByName('siteName')[0].value;
    var fromdate = document.getElementsByName('fromdate')[0].value;
    var todate = document.getElementsByName('todate')[0].value;
    
    // if (siteName === '') {
    //     alert("Please Select Site !");
    // } else 
    if (fromdate === '') {
        alert("Please Enter From Date !");
    }else if (todate === '') {
        alert("Please Enter To Date !");
    }  else {
        $('#spinnerLoader1').show(); 
        $('#societySurveyExecutiveData').hide();    

        // var queryString = "?siteName="+siteName+"&fromdate="+fromdate+"&todate="+todate;
        var queryString = "?fromdate="+fromdate+"&todate="+todate;
        ajaxRequest.open("POST", "setAssignedSocietiesExecutiveData.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

// Dashboard Function Ends Here

// Average Count Functions Starts Here

function setElectionNameAverageCountInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    // alert ("electionName");
    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionAverageCountInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function setSiteAverageCountInSession(SiteName) {

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (SiteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setSiteAverageCountInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function getAssignedAverageCountData() {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('AverageCountVoterNonVoter');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;

                $('#spinnerLoader1').hide(); 
                $('#AverageCountVoterNonVoter').show(); 

                $('.zero-configuration').DataTable();
                
                $('html, body').animate({
                   scrollTop: $("#AverageCountVoterNonVoter").offset().top
               }, 500);
        }
    }

    // var siteName = document.getElementsByName('siteName')[0].value;
    var fromdate = document.getElementsByName('fromdate')[0].value;
    var todate = document.getElementsByName('todate')[0].value;
    
    // if (siteName === '') {
    //     alert("Please Select Site !");
    // } else 
    if (fromdate === '') {
        alert("Please Enter From Date !");
    }else if (todate === '') {
        alert("Please Enter To Date !");
    }  else {
        $('#spinnerLoader1').show(); 
        $('#AverageCountVoterNonVoter').hide();    

        // var queryString = "?siteName="+siteName+"&fromdate="+fromdate+"&todate="+todate;
        var queryString = "?fromdate="+fromdate+"&todate="+todate;
        ajaxRequest.open("POST", "setAssignedSocietiesExecutiveData.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function getAverageCountDatesAndSetSession() {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            // var ajaxDisplay = document.getElementById('');
            // ajaxDisplay.innerHTML = ajaxRequest.responseText;
            location.reload(true);
        }
    }

    var fromdate = document.getElementsByName('fromdate')[0].value;
    var todate = document.getElementsByName('todate')[0].value;

    // alert(type of )
    
    // if (siteName === '') {
    //     alert("Please Select Site !");
    // } else 
    if (fromdate === '') {
        alert("Please Enter From Date !");
    }else if (todate === '') {
        alert("Please Enter To Date !");
    }
    else if(fromdate > todate){
        alert("Please Select To Date greater than From Date!");
    }
    else {

        // var queryString = "?siteName="+siteName+"&fromdate="+fromdate+"&todate="+todate;
        var queryString = "?fromdate="+fromdate+"&todate="+todate;
        ajaxRequest.open("POST", "setAverageCountDatesInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function sendCondAndShowtblData(cond){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('tblAverageCountDetail');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#spinnerLoader').hide(); 
                $('#tblAverageCountDetail').show(); 
                $('html, body').animate({
                   scrollTop: $("#tblAverageCountDetail").offset().top
               }, 500);
               $('.zero-configuration').DataTable();
        }
    }

    // if (Executive_Cd === '') {
    //     alert("Please Select Executive_Cd!!");
    // }else{
        var queryString = "?cond="+cond;
        ajaxRequest.open("POST", "datatbl/tblGetAverageCountVoterNonVoter.php" + queryString, true);
        ajaxRequest.send(null); 
    // }
}

// Average Count Functions End Here

// // Building Listing -----------------------------------------------------------------------------------------------------------
// function setElectionNameBuildingListingInSession(electionName) {
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }
//     // alert ("electionName");
//     // ajaxRequest.onreadystatechange = function() {
//     //         if (ajaxRequest.readyState == 4) {
//     //             location.reload(true);
//     //         }
//     // }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             // $('.zero-configuration').DataTable();
//             // $('#BuildingSurveyWithNoOrdering').DataTable();
//             $(document).ready(function () {
//                 $('#BuildingSurveyWithNoOrdering').DataTable({
//                   ordering: false
//                 });
//             });
//             $('.select2').select2();
//         }
//     }
    
//     if (electionName === '') {
//         alert("Please Select Corporation!");
//     } else {
//         var queryString = "?electionName="+electionName;
//         ajaxRequest.open("POST", "setElectionBuildingListingInSession.php" + queryString, true);
//         ajaxRequest.send(null);

//     }

// }

// function setSiteBuildingListingInSession(siteName) {
//     // alert(siteName);
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     // ajaxRequest.onreadystatechange = function() {
//     //     if (ajaxRequest.readyState == 4) {
//     //         location.reload(true);
//     //     }
//     // }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             // $('.zero-configuration').DataTable();
//             // $('#BuildingSurveyWithNoOrdering').DataTable();
//             $(document).ready(function () {
//                 $('#BuildingSurveyWithNoOrdering').DataTable({
//                   ordering: false
//                 });
//             });
//             $('.select2').select2();
//         }
//     }

    
//     if (siteName === '') {
//         alert("Please Select Site!");
//     } else {
//         var queryString = "?siteName="+siteName;
//         ajaxRequest.open("POST", "setSiteBuildingListingInSession.php" + queryString, true);
//         ajaxRequest.send(null);

//     }

// }


// function getBuildingListingTableFilterData() {

//     var electionName = document.getElementsByName('electionName')[0].value;
//     var SiteCd = document.getElementsByName('SiteName')[0].value;
//     var pocketCd = document.getElementsByName('pocketName')[0].value;
//     var fromDate = document.getElementsByName('fromDate')[0].value;
//     var toDate = document.getElementsByName('toDate')[0].value;
//     var executiveCd = document.getElementsByName('executiveName')[0].value;
//     var QCStatus = document.getElementsByName('QCStatus')[0].value;
//     // alert("inherethrough");

//     // alert(electionName);
//     // alert(SiteCd);
//     // alert(pocketCd);
//     // alert(fromDate);
//     // alert(toDate);
//     // alert(executiveCd);
//     // alert(QCStatus);

//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     // ajaxRequest.onreadystatechange = function() {
//     //     if (ajaxRequest.readyState == 4) {
//     //         location.reload(true);
//     //     }
//     // }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             // $('.zero-configuration').DataTable();
//             // $('#BuildingSurveyWithNoOrdering').DataTable();
//             $(document).ready(function () {
//                 $('#BuildingSurveyWithNoOrdering').DataTable({
//                   ordering: false
//                 });
//             });
//             $('.select2').select2();
//         }
//     }
    
//     if (electionName === '') {
//         alert("Please Select Corporation");
//     } else if(fromDate > toDate){
//         alert("Please Select From Date greater than To date");
//     } else {
//         var queryString = "?electionName="+electionName+"&SiteCd="+SiteCd+"&pocketCd="+pocketCd+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveCd="+executiveCd+"&QCStatus="+QCStatus;
//         // alert(queryString)
//         ajaxRequest.open("POST", "setBuildingListingTableFilterDataInSession.php" + queryString, true);
//         ajaxRequest.send(null);

//     }
// }


// function getBuildingListingDataInForm(election_Cd,ElectionName,Society_Cd,Site_Cd,SiteName,SocietyName,SocietyNameMar,Area,AreaMar,Floor,Rooms,Sector,PlotNo,Pocket_Cd,Latitude,Longitude,Building_Image,Building_Plate_Image){
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             // location.reload(true);
//             window.location.href='index.php?p=BuildingSurvey';
//             // $('#BuildingListingEditForm').show(); 
//         }
//     }

//     var queryString = "?election_Cd="+election_Cd+"&ElectionName="+ElectionName+"&Society_Cd="+Society_Cd+"&Site_Cd="+Site_Cd+"&SiteName="+SiteName+"&SocietyName="+SocietyName+"&SocietyNameMar="+SocietyNameMar+"&Area="+Area+"&AreaMar="+AreaMar+"&Floor="+Floor+"&Rooms="+Rooms+"&Sector="+Sector+"&PlotNo="+PlotNo+"&Pocket_Cd="+Pocket_Cd+"&Latitude="+Latitude+"&Longitude="+Longitude+"&Building_Image="+Building_Image+"&Building_Plate_Image="+Building_Plate_Image;
//     console.log(queryString);
//     ajaxRequest.open("POST", "setBuildingListingDetailInSession.php" + queryString, true);
//     ajaxRequest.send(null);
// }



// function cancelBtnForBuildingListingQC(){



//     window.location.href='index.php?p=building-listing-qc';
//     // getBuildingListingTableFilterData();
// }


// function saveBuildingListingQCData() {

//     var election_Cd = document.getElementsByName('election_Cd')[0].value;
//     var electionName = document.getElementsByName('electionName')[0].value;
    
//     var Society_Cd = document.getElementsByName('Society_Cd')[0].value;
//     var Site_Cd = document.getElementsByName('SiteName')[0].value;
//     // var SiteName = document.getElementsByName('SiteNameSave')[0].value;
//     var society = document.getElementsByName('society')[0].value;
//     var societyMar = document.getElementsByName('societyMar')[0].value;
//     var Area = document.getElementsByName('area')[0].value;
//     var AreaMar = document.getElementsByName('areaMar')[0].value;
//     var Floor = document.getElementsByName('floor')[0].value;
//     var Rooms = document.getElementsByName('room')[0].value;
//     // var address = document.getElementsByName('address')[0].value;
//     var Sector = document.getElementsByName('sector')[0].value;
//     var PlotNo = document.getElementsByName('plotNo')[0].value;
//     var newLat = document.getElementsByName('newLat')[0].value;
//     var newLng = document.getElementsByName('newLng')[0].value;
//     var pocketString = document.getElementsByName('pocketName')[0].value;

//     var buildingImg_OLD_URL = document.getElementsByName('buildingImg_OLD_URL')[0].value;
//     var buildingPlateImg_OLD_URL = document.getElementsByName('buildingPlateImg_OLD_URL')[0].value;
    
//     var buildingImg= document.getElementsByName('buildingImg')[0].files;
//     var buildingPlateImg= document.getElementsByName('buildingPlateImg')[0].files;


//     var formData = new FormData();
//     formData.append('buildingImg', buildingImg[0]);
//     formData.append('buildingPlateImg', buildingPlateImg[0]);

//     formData.append('Society_Cd', Society_Cd);
//     formData.append('election_Cd', election_Cd);
//     formData.append('electionName', electionName);
//     formData.append('Site_Cd', Site_Cd);
//     // formData.append('SiteName', SiteName);
//     formData.append('society', society);
//     formData.append('societyMar', societyMar);
//     formData.append('Area', Area);
//     formData.append('AreaMar', AreaMar);
//     formData.append('Floor', Floor);
//     formData.append('Rooms', Rooms);
//     // formData.append('address', address);
//     formData.append('Sector', Sector);
//     formData.append('PlotNo', PlotNo);
//     formData.append('newLat', newLat);
//     formData.append('newLng', newLng);
//     formData.append('pocketString', pocketString);
//     formData.append('buildingImg_OLD_URL', buildingImg_OLD_URL);
//     formData.append('buildingPlateImg_OLD_URL', buildingPlateImg_OLD_URL);


//     if (Site_Cd === '') {
//         alert("Please Select Site");
//     }
//     else if(Society_Cd === '' && society == ''){
//         alert("Please Enter Society Name");
//     }
//     else if(Area === ''){
//         alert("Please Enter Area");
//     }
//     else if(Floor === ''){
//         alert("Please Enter Floor");
//     }
//     else if(Rooms === ''){
//         alert("Please Enter Room");
//     }
//     // else if(newLat === ''){
//     //     alert("Please Enter Society Name");
//     // }
//     // else if(newLng === ''){
//     //     alert("Please Enter Society Name");
//     // }
//     // else if(pocketCd === ''){
//     //     alert("Please Select Pocket");
//     // }
//     else {
//         $.ajax({

//             type: "POST",
//             url: 'action/saveBuildingListingQCFormData.php',
//             data: formData,
//             enctype: 'multipart/form-data',
//             processData: false,
//             contentType: false,
//             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
//                 // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
//                 // $('html').addClass("ajaxLoading");
//             },
//             success: function(dataResult) {
//                 // alert('in success');
//                 // console.log(dataResult);
//                 // alert(dataResult);

//                 var dataResult = JSON.parse(dataResult);
//                 if(dataResult.statusCode == 200){
//                     $("#msgsuccess").html(dataResult.msg)
//                         .hide().fadeIn(1000, function() {
//                             $("msgsuccess").append("");
//                             window.location.href = 'index.php?p=building-listing-qc';
//                         }).delay(3000).fadeOut("fast");
//                 }else{
//                     $("#msgfailed").html(dataResult.msg)
//                         .hide().fadeIn(800, function() {
//                             $("msgfailed").append("");
//                         }).delay(4000).fadeOut("fast");
//                 }
//             }
//             // ,
//             // complete: function() {
//             //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
//             //         $('html').removeClass("ajaxLoading");
//             //     }
//         });
//     }
// }

// function setSiteBuildingListingSavePageInSession(siteName) {
//     // alert(siteName);
//     // return;
    
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             location.reload(true);
//         }
//     }

//     // ajaxRequest.onreadystatechange = function() {
//     //     if (ajaxRequest.readyState == 4) {
//     //         var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//     //         ajaxDisplay.innerHTML = ajaxRequest.responseText;
//     //         $('.zero-configuration').DataTable();
//     //     }
//     // }

    
//     if (siteName === '') {
//         alert("Please Select Site!");
//     } else {
//         var queryString = "?siteName="+siteName;
//         ajaxRequest.open("POST", "setSiteBuildingListingSavePageInSession.php" + queryString, true);
//         ajaxRequest.send(null);

//     }

// }

// function setBuildingListingIds() {
//     var input = document.getElementsByClassName("checkbox");
    
//     var selected = 0;
//     var chkAllCDS = "";
//     var chkAllNames = "";
//     var chkAllAssignedCount = 0;
 
//     for (var i = 0; i < input.length; i++) {
//       if (input[i].checked) {
//           var splits = input[i].value.split(",");
//           var CD_Val = '';
//           var Name_Val = '';
//           //var AssCount = 0;
 
//           CD_Val += ""+splits[0]+"";
//           Name_Val += ""+splits[1]+"";
//           chkAllCDS += CD_Val+",";  
//           chkAllNames += Name_Val+", ";  
//           console.log(chkAllCDS);
//           console.log(chkAllNames);
 
//           selected ++;
//         }
      
//     }
   
//     document.getElementsByName("society_cds")[0].value = "" + chkAllCDS;
//     document.getElementsByName("societyNames")[0].value = "" + chkAllNames;
//     document.getElementsByName("societyNames")[0].title = "" + chkAllNames;
 
// }

// function setBuildingListingALLIds(ele) {
    
    
//     var checkboxes = document.getElementsByClassName('checkbox');
//     if (ele.checked) {
//         for (var i = 0; i < checkboxes.length; i++) {
//             if (checkboxes[i].type == 'checkbox') {
//                 checkboxes[i].checked = true;
//             }
//         }
//     } else {
//         for (var i = 0; i < checkboxes.length; i++) {
//             console.log(i)
//             if (checkboxes[i].type == 'checkbox') {
//                 checkboxes[i].checked = false;
//             }
//         }
//     }

//     setBuildingListingIds();

// }

// function saveBuildingListingQCcheckbox() {
 
//     var society_cds = document.getElementsByName('society_cds')[0].value;


//     if(society_cds === ''){
//         alert("Please select Societies!");
//     }
//     else if (confirm("Are you Sure you want to get QC done of selected socities?") == true) 
//     {
//         $.ajax({

//             type: "POST",
//             url: 'action/saveBuildingListingQCcheckbox.php',
//             data: { 
//                 society_cds: society_cds
//             },
//             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
//                 // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
//                 // $('html').addClass("ajaxLoading");
//             },
//             success: function(dataResult) {
//                 // alert('in success');
//                 // console.log(dataResult);
//                 // alert(dataResult);

//                 var dataResult = JSON.parse(dataResult);
//                 if(dataResult.statusCode == 200){
//                     $("#msgsuccess").html(dataResult.msg)
//                         .hide().fadeIn(1000, function() {
//                             $("msgsuccess").append("");
//                             location.reload(true);
//                         }).delay(3000).fadeOut("fast");
//                 }else{
//                     $("#msgfailed").html(dataResult.msg)
//                         .hide().fadeIn(800, function() {
//                             $("msgfailed").append("");
//                         }).delay(4000).fadeOut("fast");
//                 }
//             }
//             // ,
//             // complete: function() {
//             //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
//             //         $('html').removeClass("ajaxLoading");
//             //     }
//         });
//     }
// }


//  // Get the file input element
//  var buildingImg = document.getElementById('buildingImg');

//  // Add an event listener to the file input element
//  buildingImg.addEventListener('change', function(event) {
//          var file = event.target.files[0];
         
//          // Get the file extension
//          var fileExtension = file.name.split('.').pop().toLowerCase();
         
//          // Specify the allowed file extensions
//         //  var allowedExtensions = ['csv'];
//          var allowedExtensions = ['jpg', 'jpeg', 'png'];
 
//          // Check if the file extension is allowed
//          if (allowedExtensions.indexOf(fileExtension) === -1) {
//                  alert('Invalid file extension. Only jpg, jpeg & png files are allowed.');
//                  // Clear the file input field
//                  buildingImg.value = '';
//          }
//  });

//  var buildingPlateImg = document.getElementById('buildingPlateImg');

//  // Add an event listener to the file input element
//  buildingPlateImg.addEventListener('change', function(event) {
//          var file = event.target.files[0];
         
//          // Get the file extension
//          var fileExtension = file.name.split('.').pop().toLowerCase();
         
//          // Specify the allowed file extensions
//         //  var allowedExtensions = ['csv'];
//          var allowedExtensions = ['jpg', 'jpeg', 'png'];
 
//          // Check if the file extension is allowed
//          if (allowedExtensions.indexOf(fileExtension) === -1) {
//                  alert('Invalid file extension. Only jpg, jpeg & png files are allowed.');
//                  // Clear the file input field
//                  buildingPlateImg.value = '';
//          }
//  });
// // BUILDING LISTING ENDS--------------------------------------------------------------------------------------------



// Building Listing -----------------------------------------------------------------------------------------------------------
function setElectionNameBuildingListingInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#tblBuildingListingQCtbl').show(); 
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
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#tblBuildingListingQCtbl').hide(); 
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionBuildingListingInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setSiteBuildingListingInSession(siteName) {
    // alert(siteName);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#tblBuildingListingQCtbl').show(); 
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
            $('.select2').select2();
        }
    }

    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#tblBuildingListingQCtbl').hide(); 
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteBuildingListingInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function getBuildingListingTableFilterData() {

    var electionName = document.getElementsByName('electionName')[0].value;
    var SiteCd = document.getElementsByName('SiteName')[0].value;
    var pocketCd = document.getElementsByName('pocketName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveCd = document.getElementsByName('executiveName')[0].value;
    var QCStatus = document.getElementsByName('QCStatus')[0].value;

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#tblBuildingListingQCtbl').show(); 
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
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation");
    } else if(fromDate > toDate){
        alert("Please Select From Date greater than To date");
    } else {
        $('#spinnerLoader2').show(); 
        $('#tblBuildingListingQCtbl').hide(); 
        var queryString = "?electionName="+electionName+"&SiteCd="+SiteCd+"&pocketCd="+pocketCd+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveCd="+executiveCd+"&QCStatus="+QCStatus;
        // alert(queryString)
        ajaxRequest.open("POST", "setBuildingListingTableFilterDataInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }
}

// function getBuildingListingDataInFormNew(election_Cd,ElectionName,Society_Cd,Site_Cd,SiteName,SocietyName,SocietyNameMar,Area,AreaMar,Floor,Rooms,Sector,PlotNo,Pocket_Cd,Latitude,Longitude,Building_Image,Building_Plate_Image){

//     var ajaxRequest;  // The variable that makes Ajax possible!
    
//     try {
//        // Opera 8.0+, Firefox, Safari
//        ajaxRequest = new XMLHttpRequest();
//     }catch (e) {
//        // Internet Explorer Browsers
//        try {
//           ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//        }catch (e) {
//           try{
//              ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//           }catch (e){
//              // Something went wrong
//              alert("Your browser broke!");
//              return false;
//           }
//        }
//     }
  
//     ajaxRequest.onreadystatechange = function(){
//       if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
//             var ajaxDisplay = document.getElementById('BuildingListingQCDataId');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             $('#spinnerLoader1').hide(); 
//             $('#BuildingListingMapDIV').show(); 
//             $('html, body').animate({
//                scrollTop: $("#BuildingListingMapDIV").offset().top
//            }, 500);
//         }
//     }

//         $('#spinnerLoader1').show(); 
//         $('#BuildingListingMapDIV').hide(); 
//         var queryString = "?election_Cd="+election_Cd+"&ElectionName="+ElectionName+"&Society_Cd="+Society_Cd+"&Site_Cd="+Site_Cd+"&SiteName="+SiteName+"&SocietyName="+SocietyName+"&SocietyNameMar="+SocietyNameMar+"&Area="+Area+"&AreaMar="+AreaMar+"&Floor="+Floor+"&Rooms="+Rooms+"&Sector="+Sector+"&PlotNo="+PlotNo+"&Pocket_Cd="+Pocket_Cd+"&Latitude="+Latitude+"&Longitude="+Longitude+"&Building_Image="+Building_Image+"&Building_Plate_Image="+Building_Plate_Image;
//         ajaxRequest.open("POST", "BuildingSurvey.php" + queryString, true);
//         ajaxRequest.send(null); 
//     // }
// }


function getBuildingListingDataInFormNew(election_Cd,ElectionName,Society_Cd,Site_Cd,SiteName,SocietyName,SocietyNameMar,Area,AreaMar,Floor,Rooms,Sector,PlotNo,Pocket_Cd,Latitude,Longitude,Building_Image,Building_Plate_Image,Remark,Category){

    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function(){
      if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('BuildingListingQCDataId');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader1').hide(); 
            $('#BuildingListingMapDIV').show(); 
            $('html, body').animate({
               scrollTop: $("#BuildingListingMapDIV").offset().top
           }, 500);
        }
    }

        $('#spinnerLoader1').show(); 
        $('#BuildingListingMapDIV').hide(); 
        var queryString = "?election_Cd="+election_Cd+"&ElectionName="+ElectionName+"&Society_Cd="+Society_Cd+"&Site_Cd="+Site_Cd+"&SiteName="+SiteName+"&SocietyName="+SocietyName+"&SocietyNameMar="+SocietyNameMar+"&Area="+Area+"&AreaMar="+AreaMar+"&Floor="+Floor+"&Rooms="+Rooms+"&Sector="+Sector+"&PlotNo="+PlotNo+"&Pocket_Cd="+Pocket_Cd+"&Latitude="+Latitude+"&Longitude="+Longitude+"&Building_Image="+Building_Image+"&Building_Plate_Image="+Building_Plate_Image+"&Remark="+Remark+"&Category="+Category;
        ajaxRequest.open("POST", "BuildingSurvey.php" + queryString, true);
        ajaxRequest.send(null); 
    // }
}



function cancelBtnForBuildingListingQC(){
  window.location.href='index.php?p=building-listing-qc';
}


// function saveBuildingListingQCData() {

//     var election_Cd = document.getElementsByName('election_CdBLSave')[0].value;
//     var electionName = document.getElementsByName('electionNameBLSave')[0].value;
    
//     var Society_Cd = document.getElementsByName('Society_CdBLSave')[0].value;
//     var Site_Cd = document.getElementsByName('SiteNameBLSave')[0].value;
//     // var SiteName = document.getElementsByName('SiteNameSave')[0].value;
//     var society = document.getElementsByName('society')[0].value;
//     var societyMar = document.getElementsByName('societyMar')[0].value;
//     var Area = document.getElementsByName('area')[0].value;
//     var AreaMar = document.getElementsByName('areaMar')[0].value;
//     var Floor = document.getElementsByName('floor')[0].value;
//     var Rooms = document.getElementsByName('room')[0].value;
//     // var address = document.getElementsByName('address')[0].value;
//     var Sector = document.getElementsByName('sector')[0].value;
//     var PlotNo = document.getElementsByName('plotNo')[0].value;
//     var newLat = document.getElementsByName('newLat')[0].value;
//     var newLng = document.getElementsByName('newLng')[0].value;
//     var pocketString = document.getElementsByName('pocketNameBLSave')[0].value;

//     var buildingImg_OLD_URL = document.getElementsByName('buildingImg_OLD_URL')[0].value;
//     var buildingPlateImg_OLD_URL = document.getElementsByName('buildingPlateImg_OLD_URL')[0].value;
    
//     var buildingImg= document.getElementsByName('buildingImg')[0].files;
//     var buildingPlateImg= document.getElementsByName('buildingPlateImg')[0].files;


//     var formData = new FormData();
//     formData.append('buildingImg', buildingImg[0]);
//     formData.append('buildingPlateImg', buildingPlateImg[0]);

//     formData.append('Society_Cd', Society_Cd);
//     formData.append('election_Cd', election_Cd);
//     formData.append('electionName', electionName);
//     formData.append('Site_Cd', Site_Cd);
//     // formData.append('SiteName', SiteName);
//     formData.append('society', society);
//     formData.append('societyMar', societyMar);
//     formData.append('Area', Area);
//     formData.append('AreaMar', AreaMar);
//     formData.append('Floor', Floor);
//     formData.append('Rooms', Rooms);
//     // formData.append('address', address);
//     formData.append('Sector', Sector);
//     formData.append('PlotNo', PlotNo);
//     formData.append('newLat', newLat);
//     formData.append('newLng', newLng);
//     formData.append('pocketString', pocketString);
//     formData.append('buildingImg_OLD_URL', buildingImg_OLD_URL);
//     formData.append('buildingPlateImg_OLD_URL', buildingPlateImg_OLD_URL);


//     if (Site_Cd === '') {
//         alert("Please Select Site");
//     }
//     else if(Society_Cd === '' && society == ''){
//         alert("Please Enter Society Name");
//     }
//     else if(Area === ''){
//         alert("Please Enter Area");
//     }
//     else if(Floor === ''){
//         alert("Please Enter Floor");
//     }
//     else if(Rooms === ''){
//         alert("Please Enter Room");
//     }else {
//         $.ajax({

//             type: "POST",
//             url: 'action/saveBuildingListingQCFormData.php',
//             data: formData,
//             enctype: 'multipart/form-data',
//             processData: false,
//             contentType: false,
//             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
//                 // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
//                 // $('html').addClass("ajaxLoading");
//             },
//             success: function(dataResult) {
//                 // alert('in success');
//                 // console.log(dataResult);
//                 // alert(dataResult);

//                 var dataResult = JSON.parse(dataResult);
//                 if(dataResult.statusCode == 200){
//                     $("#msgsuccessBS").html(dataResult.msg)
//                         .hide().fadeIn(1000, function() {
//                             $("msgsuccessBS").append("");
//                             window.location.href = 'index.php?p=building-listing-qc';
//                         }).delay(3000).fadeOut("fast");
//                 }else{
//                     $("#msgfailedBS").html(dataResult.msg)
//                         .hide().fadeIn(800, function() {
//                             $("msgfailedBS").append("");
//                         }).delay(4000).fadeOut("fast");
//                 }
//             }
//             // ,
//             // complete: function() {
//             //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
//             //         $('html').removeClass("ajaxLoading");
//             //     }
//         });
//     }
// }


function saveBuildingListingQCData() {

    var election_Cd = document.getElementsByName('election_CdBLSave')[0].value;
    var electionName = document.getElementsByName('electionNameBLSave')[0].value;
    
    var Society_Cd = document.getElementsByName('Society_CdBLSave')[0].value;
    var Site_Cd = document.getElementsByName('SiteNameBLSave')[0].value;
    // var SiteName = document.getElementsByName('SiteNameSave')[0].value;
    var society = document.getElementsByName('society')[0].value;
    var societyMar = document.getElementsByName('societyMar')[0].value;
    var Area = document.getElementsByName('area')[0].value;
    var AreaMar = document.getElementsByName('areaMar')[0].value;
    var Floor = document.getElementsByName('floor')[0].value;
    var Rooms = document.getElementsByName('room')[0].value;
    // var address = document.getElementsByName('address')[0].value;
    var Remark = document.getElementsByName('Remark')[0].value;
    var Sector = document.getElementsByName('sector')[0].value;
    var PlotNo = document.getElementsByName('plotNo')[0].value;
    var newLat = document.getElementsByName('newLat')[0].value;
    var newLng = document.getElementsByName('newLng')[0].value;
    var pocketString = document.getElementsByName('pocketNameBLSave')[0].value;

    var buildingImg_OLD_URL = document.getElementsByName('buildingImg_OLD_URL')[0].value;
    var buildingPlateImg_OLD_URL = document.getElementsByName('buildingPlateImg_OLD_URL')[0].value;
    
    var buildingImg1= document.getElementsByName('buildingImg')[0].value;
    var buildingPlateImg1= document.getElementsByName('buildingPlateImg')[0].value;
    var buildingImg= document.getElementsByName('buildingImg')[0].files;
    var buildingPlateImg= document.getElementsByName('buildingPlateImg')[0].files;


    var formData = new FormData();
    formData.append('buildingImg', buildingImg[0]);
    formData.append('buildingPlateImg', buildingPlateImg[0]);

    formData.append('Society_Cd', Society_Cd);
    formData.append('election_Cd', election_Cd);
    formData.append('electionName', electionName);
    formData.append('Site_Cd', Site_Cd);
    // formData.append('SiteName', SiteName);
    formData.append('society', society);
    formData.append('societyMar', societyMar);
    formData.append('Area', Area);
    formData.append('AreaMar', AreaMar);
    formData.append('Floor', Floor);
    formData.append('Rooms', Rooms);
    formData.append('Remark', Remark);
    // formData.append('address', address);
    formData.append('Sector', Sector);
    formData.append('PlotNo', PlotNo);
    formData.append('newLat', newLat);
    formData.append('newLng', newLng);
    formData.append('pocketString', pocketString);
    formData.append('buildingImg_OLD_URL', buildingImg_OLD_URL);
    formData.append('buildingPlateImg_OLD_URL', buildingPlateImg_OLD_URL);


    if (Site_Cd === '') {
        alert("Please Select Site");
    }
    else if(Society_Cd === '' && society == ''){
        alert("Please Enter Society Name");
    }
    else if(Area === ''){
        alert("Please Enter Area");
    }
    else if(Floor === ''){
        alert("Please Enter Floor");
    }
    else if(Rooms === ''){
        alert("Please Enter Room");
    }else {
        $.ajax({

            type: "POST",
            url: 'action/saveBuildingListingQCFormData.php',
            data: formData,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccessBS").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccessBS").append("");
                            window.location.href = 'index.php?p=building-listing-qc';
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailedBS").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailedBS").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}



// function saveBuildingListingQCRejectedData() {

//     var election_Cd = document.getElementsByName('election_CdBLSave')[0].value;
//     var electionName = document.getElementsByName('electionNameBLSave')[0].value;
    
//     var Society_Cd = document.getElementsByName('Society_CdBLSave')[0].value;

//     var RejectedFlag = 'RejectedFlag';

//     var formData = new FormData();
//     formData.append('Society_Cd', Society_Cd);
//     formData.append('election_Cd', election_Cd);
//     formData.append('electionName', electionName);
//     formData.append('RejectedFlag', RejectedFlag);


//     if(Society_Cd === ''){
//         alert("Please Enter Society Name");
//     }else {
//         $.ajax({

//             type: "POST",
//             url: 'action/saveBuildingListingQCFormData.php',
//             data: formData,
//             enctype: 'multipart/form-data',
//             processData: false,
//             contentType: false,
//             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
//                 // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
//                 // $('html').addClass("ajaxLoading");
//             },
//             success: function(dataResult) {
//                 // alert('in success');
//                 // console.log(dataResult);
//                 // alert(dataResult);

//                 var dataResult = JSON.parse(dataResult);
//                 if(dataResult.statusCode == 200){
//                     $("#msgsuccessBS").html(dataResult.msg)
//                         .hide().fadeIn(1000, function() {
//                             $("msgsuccessBS").append("");
//                             window.location.href = 'index.php?p=building-listing-qc';
//                         }).delay(3000).fadeOut("fast");
//                 }else{
//                     $("#msgfailedBS").html(dataResult.msg)
//                         .hide().fadeIn(800, function() {
//                             $("msgfailedBS").append("");
//                         }).delay(4000).fadeOut("fast");
//                 }
//             }
//             // ,
//             // complete: function() {
//             //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
//             //         $('html').removeClass("ajaxLoading");
//             //     }
//         });
//     }
// }


function saveBuildingListingQCRejectedData() {

    var election_Cd = document.getElementsByName('election_CdBLSave')[0].value;
    var electionName = document.getElementsByName('electionNameBLSave')[0].value;
    
    var Society_Cd = document.getElementsByName('Society_CdBLSave')[0].value;
    var Remark = document.getElementsByName('Remark')[0].value;

    var RejectedFlag = 'RejectedFlag';

    var formData = new FormData();
    formData.append('Society_Cd', Society_Cd);
    formData.append('election_Cd', election_Cd);
    formData.append('electionName', electionName);
    formData.append('Remark', Remark);
    formData.append('RejectedFlag', RejectedFlag);


    if(Society_Cd === ''){
        alert("Please Enter Society Name");
    }else {
        $.ajax({

            type: "POST",
            url: 'action/saveBuildingListingQCFormData.php',
            data: formData,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccessBS").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccessBS").append("");
                            window.location.href = 'index.php?p=building-listing-qc';
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailedBS").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailedBS").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}


function setSiteBuildingListingSavePageInSession(siteName) {

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            location.reload(true);
        }
    }

    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteBuildingListingSavePageInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }
}

function setBuildingListingIds() {
    var input = document.getElementsByClassName("checkbox");
    
    var selected = 0;
    var chkAllCDS = "";
    var chkAllNames = "";
    var chkAllAssignedCount = 0;
 
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
          var splits = input[i].value.split(",");
          var CD_Val = '';
          var Name_Val = '';
          //var AssCount = 0;
 
          CD_Val += ""+splits[0]+"";
          Name_Val += ""+splits[1]+"";
          chkAllCDS += CD_Val+",";  
          chkAllNames += Name_Val+", ";  
          console.log(chkAllCDS);
          console.log(chkAllNames);
 
          selected ++;
        }
      
    }
   
    document.getElementsByName("society_cds")[0].value = "" + chkAllCDS;
    document.getElementsByName("societyNames")[0].value = "" + chkAllNames;
    document.getElementsByName("societyNames")[0].title = "" + chkAllNames;
 
}

function setBuildingListingALLIds(ele) {
    
    
    var checkboxes = document.getElementsByClassName('checkbox');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            console.log(i)
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }

    setBuildingListingIds();

}

function saveBuildingListingQCcheckbox() {
 
    var society_cds = document.getElementsByName('society_cds')[0].value;


    if(society_cds === ''){
        alert("Please select Societies!");
    }
    else if (confirm("Are you Sure you want to get QC done of selected socities?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveBuildingListingQCcheckbox.php',
            data: { 
                society_cds: society_cds
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            location.reload(true);
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}


 // Get the file input element
 var buildingImg = document.getElementById('buildingImg');

 // Add an event listener to the file input element
 buildingImg.addEventListener('change', function(event) {
         var file = event.target.files[0];
         
         // Get the file extension
         var fileExtension = file.name.split('.').pop().toLowerCase();
         
         // Specify the allowed file extensions
        //  var allowedExtensions = ['csv'];
         var allowedExtensions = ['jpg', 'jpeg', 'png'];
 
         // Check if the file extension is allowed
         if (allowedExtensions.indexOf(fileExtension) === -1) {
                 alert('Invalid file extension. Only jpg, jpeg & png files are allowed.');
                 // Clear the file input field
                 buildingImg.value = '';
         }
 });

 var buildingPlateImg = document.getElementById('buildingPlateImg');

 // Add an event listener to the file input element
 buildingPlateImg.addEventListener('change', function(event) {
         var file = event.target.files[0];
         
         // Get the file extension
         var fileExtension = file.name.split('.').pop().toLowerCase();
         
         // Specify the allowed file extensions
        //  var allowedExtensions = ['csv'];
         var allowedExtensions = ['jpg', 'jpeg', 'png'];
 
         // Check if the file extension is allowed
         if (allowedExtensions.indexOf(fileExtension) === -1) {
                 alert('Invalid file extension. Only jpg, jpeg & png files are allowed.');
                 // Clear the file input field
                 buildingPlateImg.value = '';
         }
 });
// BUILDING LISTING ENDS--------------------------------------------------------------------------------------------


// QC ASSIGN STARTS--------------------------------------------------------------------------------------------

function setElectionNameQCAssignInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#QCAssignTblDataHideDiv').show();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#QCAssignTblDataHideDiv').hide();
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionQCAssignInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setSiteQCAssignInSession(siteName) {
    // alert(siteName);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }


    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#QCAssignTblDataHideDiv').show();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }

    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#QCAssignTblDataHideDiv').hide();
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteQCAssignInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function getQCAssignTableFilterData() {

    var electionName = document.getElementsByName('electionName')[0].value;
    var SiteCd = document.getElementsByName('SiteName')[0].value;
    var pocketCd = document.getElementsByName('pocketName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var QcAssigned = document.getElementsByName('QCAssigned')[0].value;
    var SurveyStatus = document.getElementsByName('SurveyStatus')[0].value;
    var QCStatus = document.getElementsByName('QCStatus')[0].value;

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#QCAssignTblDataHideDiv').show();
			$(document).ready(function () {
				$('#SurveyQCAssignWithNoOrdering').DataTable({
				 "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ],
				 ordering: false
				});
			});
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation");
    } else {
        $('#spinnerLoader2').show(); 
        $('#QCAssignTblDataHideDiv').hide();
        var queryString = "?electionName="+electionName+"&SiteCd="+SiteCd+"&pocketCd="+pocketCd+"&fromDate="+fromDate+"&toDate="+toDate+"&QCStatus="+QCStatus+"&QCAssigned="+QcAssigned+"&SurveyStatus="+SurveyStatus;
        // alert(queryString)
        ajaxRequest.open("POST", "setQCAssignTableFilterDataInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


// function setQCAssignIds() {
//     var input = document.getElementsByClassName("checkbox");
  
//     var selected = 0;
//     var chkAllCDS = "";
//     var chkAllNames = "";
//     var chkAllAssignedCount = 0;
//     var voter_val = 0;
//     var nvoter_val = 0;
//     var lockroom_val = 0;
//     var roomdone_val = 0;
//     var totalroom_val = 0;
//     var pending_val = 0;
  
//     for (var i = 0; i < input.length; i++) {
//       if (input[i].checked) {
//         var splits = input[i].value.split(",");
//         var CD_Val = '';
//         var Name_Val = '';
//         var voter = 0;
//         var nvoter = 0;
//         var lockroom = 0;
//         var roomdone = 0;
//         var totalroom = 0;
//         var pending = 0;
  
//         CD_Val += "" + splits[0] + "";
//         Name_Val += "" + splits[1] + "";
//         chkAllCDS += CD_Val + ",";
//         chkAllNames += Name_Val + ", ";
  
//         voter = parseInt(splits[2]);
//         nvoter = parseInt(splits[3]);
//         lockroom = parseInt(splits[4]);
//         roomdone = parseInt(splits[5]);
//         totalroom = parseInt(splits[6]);
//         pending = parseInt(splits[7]);
  
//         selected++;
//         chkAllAssignedCount++;
//         voter_val += voter;
//         nvoter_val += nvoter;
//         lockroom_val += lockroom;
//         roomdone_val += roomdone;
//         totalroom_val += totalroom;
//         pending_val += pending;
//       }
//     }
  
//     document.getElementsByName("society_cds")[0].value = "" + chkAllCDS;

//     // Display the count in the specified div
//     var societyCountElement = document.getElementById("society_cnt");
//     if (societyCountElement) {
//       societyCountElement.value = chkAllAssignedCount;
//     }

//     // Display the count in the specified div
//     var VNVLRElement = document.getElementById("VNVLR");
//     if (VNVLRElement) {
//         VNVLRElement.value = voter_val + " / " + nvoter_val + " / " +  lockroom_val;
//     }

//     // Display the count in the specified div
//     var RDTRPENElement = document.getElementById("RDTRPEN");
//     if (RDTRPENElement) {
//         RDTRPENElement.value = roomdone_val + " / " + totalroom_val + " / " +  pending_val;
//     }
  
// }

function setQCAssignIds() {
    var input = document.getElementsByClassName("checkbox");
  
    var selected = 0;
    var chkAllCDS = "";
    var chkAllNames = "";
    var chkAllAssignedCount = 0;
    var voter_val = 0;
    var nvoter_val = 0;
    var lockroom_val = 0;
    var roomdone_val = 0;
    var totalroom_val = 0;
    var pending_val = 0;
  
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
        var splits = input[i].value.split("~");

        var CD_Val = '';
        var Name_Val = '';
        var voter = 0;
        var nvoter = 0;
        var lockroom = 0;
        var roomdone = 0;
        var totalroom = 0;
        var pending = 0;
  
        Name_Val += "" + splits[0] + "";
        CD_Val += "" + splits[1] + "";
        chkAllCDS += CD_Val + ",";
        chkAllNames += Name_Val + ", ";
  
        voter = parseInt(splits[2]);
        nvoter = parseInt(splits[3]);
        lockroom = parseInt(splits[4]);
        roomdone = parseInt(splits[5]);
        totalroom = parseInt(splits[6]);
        pending = parseInt(splits[7]);
  
        selected++;
        chkAllAssignedCount++;
        voter_val += voter;
        nvoter_val += nvoter;
        lockroom_val += lockroom;
        roomdone_val += roomdone;
        totalroom_val += totalroom;
        pending_val += pending;
      }
    }
  
    document.getElementsByName("society_cds")[0].value = "" + chkAllCDS;

    // Display the count in the specified div
    var societyCountElement = document.getElementById("society_cnt");
    if (societyCountElement) {
      societyCountElement.value = chkAllAssignedCount;
    }

    // Display the count in the specified div
    var VNVLRElement = document.getElementById("VNVLR");
    if (VNVLRElement) {
        VNVLRElement.value = voter_val + " / " + nvoter_val + " / " +  lockroom_val;
    }

    // Display the count in the specified div
    var RDTRPENElement = document.getElementById("RDTRPEN");
    if (RDTRPENElement) {
        RDTRPENElement.value = roomdone_val + " / " + totalroom_val + " / " +  pending_val;
    }
}
  

function setQCAssignALLIds(ele) {
    
    
    var checkboxes = document.getElementsByClassName('checkbox');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            console.log(i)
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }

    setQCAssignIds();

}

function saveQCAssigncheckbox() {
 
    var society_cds = document.getElementsByName('society_cds')[0].value;
    var ExecutiveCd = document.getElementsByName('executiveName')[0].value;

    

    if(ExecutiveCd === ''){
        alert("Please select Executive!");
    }
    else if(society_cds === ''){
        alert("Please select Societies!");
    }
    else if (confirm("Are you Sure you want to assign these societies to selected executive?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveQCAssigncheckbox.php',
            data: { 
                society_cds: society_cds,
                ExecutiveCd: ExecutiveCd
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            location.reload(true);
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}

// QC ASSIGN ENDS---------------------------------------------------------------------------------------------
// SURVEY QC STARTS---------------------------------------------------------------------------------------------

function setElectionNameSurveyQCInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            $('#spinnerLoader2').hide(); 
            $('#SurveyQCTblDataHideDiv').show();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#SurveyQCTblDataHideDiv').hide();
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionSurveyQCInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


function setSiteSurveyQCInSession(siteName) {
    // alert(siteName);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            $('#spinnerLoader2').hide(); 
            $('#SurveyQCTblDataHideDiv').show();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }

    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        $('#spinnerLoader2').show(); 
        $('#SurveyQCTblDataHideDiv').hide();
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteSurveyQCInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }
}

function getSurveyQCTableFilterData() {

    var electionName = document.getElementsByName('electionName')[0].value;
    var SiteCd = document.getElementsByName('SiteName')[0].value;
    var pocketCd = document.getElementsByName('pocketName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var executiveCd = document.getElementsByName('executiveName')[0].value;
    var QCAssignedTo = document.getElementsByName('QCAssignedTo')[0].value;
    var QCStatus = document.getElementsByName('QCStatus')[0].value;
    var SurveyStatus = document.getElementsByName('SurveyStatus')[0].value;
    // alert(QCStatus);

    // alert(QCAssignedTo);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            $('#SurveyQCTblDataHideDiv').show();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation");
    } else {
        $('#spinnerLoader2').show(); 
        $('#SurveyQCTblDataHideDiv').hide();
        var queryString = "?electionName="+electionName+"&SiteCd="+SiteCd+"&pocketCd="+pocketCd+"&fromDate="+fromDate+"&toDate="+toDate+"&executiveCd="+executiveCd+"&QCAssignedTo="+QCAssignedTo+"&QCStatus="+QCStatus+"&SurveyStatus="+SurveyStatus;
        ajaxRequest.open("POST", "setSurveyQCTableFilterDataInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


// Survey Summary Report


function GetSummaryDataInSession(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            $('#SurveySummaryList').DataTable();
            $('.select2').select2();


        }
    }

    var electionName = document.getElementsByName('electionName')[0].value;
    var siteName = document.getElementsByName('siteName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;

    var Date1 = fromDate.match(/(\d+)/g);
    var Date2 = toDate.match(/(\d+)/g);

    FrDate = new Date(Date1[0], Date1[1]-1, Date1[2]);
    ToDate = new Date(Date2[0], Date2[1]-1, Date2[2]);
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    }else if(FrDate.getTime() > ToDate.getTime()){
        alert("Please Select To Date greater than From Date!");
    }
    else {
        var queryString = "?electionCd="+ electionName+"&siteName="+siteName+"&fromDate="+fromDate+"&toDate="+toDate;
        // console.log(queryString);
        ajaxRequest.open("POST", "SetSummaryDataInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}



function GetBuildingListData(Type,QC_Done_Flag,Executive_Cd,QC_Assign_Date){
    // alert(QC_Done_Flag+'/'+Executive_Cd+'/'+QC_Assign_Date);
    // var Executive_Cd = document.getElementsByName('Executive_Cd')[0].value;
    // alert(Executive_Cd);
    // alert(Executive_Cd); 
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('SurveySummaryDataLoad');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            $('#SurveySummaryList').DataTable();
            $('.select2').select2();
            $('html, body').animate({
                scrollTop: $("#SurveySummaryDataLoad").offset().top
            }, 500);

        }
    }


    // if (Executive_Cd === '') {
    //     alert("Please Select Executive_Cd!!");
    // }else{
        var queryString = "?QC_Done_Flag="+QC_Done_Flag+"&Type="+Type+"&Executive_Cd="+Executive_Cd+"&QC_Assign_Date="+QC_Assign_Date;
        ajaxRequest.open("POST", "tblGetBuildingListData.php" + queryString, true);
        ajaxRequest.send(null); 
    // }
}


function setSiteSummaryReportInSession(siteName){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
       // Opera 8.0+, Firefox, Safari
       ajaxRequest = new XMLHttpRequest();
    }catch (e) {
       // Internet Explorer Browsers
       try {
          ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
       }catch (e) {
          try{
             ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
          }catch (e){
             // Something went wrong
             alert("Your browser broke!");
             return false;
          }
       }
    }
  
    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            // $('#SurveySummaryList').DataTable();
            $('.select2').select2();


        }
    }

    var queryString = "?siteName="+siteName;
    ajaxRequest.open("POST", "setSiteSummaryReportInSession.php" + queryString, true);
    ajaxRequest.send(null); 
}


// function getSurveyQCNonVoterNameInSession(){

//     var FirstName = document.getElementsByName('FirstName')[0].value;
//     var MiddleName = document.getElementsByName('MiddleName')[0].value;
//     var LastName = document.getElementsByName('LastName')[0].value;
//     var FullName = document.getElementsByName('FullName')[0].value;

//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }


//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             $('#spinnerLoader2').hide();
//         }
//     }

//     $('#spinnerLoader2').show();
//     var queryString = "?FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&FullName="+FullName;
//     ajaxRequest.open("POST", "setSurveyQCNonVoterNamesInSession.php" + queryString, true);
//     ajaxRequest.send(null);

// }


// function getSurveyQCNonVoterFamilyInSession(FamilyNo,Ac_No,Voter_Cd){

//     var FirstName = document.getElementsByName('FirstName')[0].value;
//     var MiddleName = document.getElementsByName('MiddleName')[0].value;
//     var LastName = document.getElementsByName('LastName')[0].value;
//     var FullName = document.getElementsByName('FullName')[0].value;

//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             $('#spinnerLoader3').hide();
//         }
//     }
    

//     $('#spinnerLoader3').show();
//     var queryString = "?FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&FullName="+FullName+"&FamilyNo="+FamilyNo+"&Ac_No="+Ac_No+"&Voter_Cd="+Voter_Cd;
//     ajaxRequest.open("POST", "setSurveyQCNonVoterFamilyInSession.php" + queryString, true);
//     ajaxRequest.send(null);
// }


// function getSurveyQCNonVoterFamilyInSession(FamilyNo,Ac_No,Voter_Cd){

//     var FirstName = document.getElementsByName('FirstName')[0].value;
//     var MiddleName = document.getElementsByName('MiddleName')[0].value;
//     var LastName = document.getElementsByName('LastName')[0].value;
//     var FullName = document.getElementsByName('FullName')[0].value;

//     var AdvanceSearch = document.getElementsByName('AdvanceSearch')[0].value;

//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//         if (ajaxRequest.readyState == 4) {
//             var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
//             ajaxDisplay.innerHTML = ajaxRequest.responseText;
//             $('#spinnerLoader3').hide();
//             surveyQCAdvanceSearch(AdvanceSearch)
//         }
//     }
    

//     $('#spinnerLoader3').show();
//     var queryString = "?FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&FullName="+FullName+"&AdvanceSearch="+AdvanceSearch+"&FamilyNo="+FamilyNo+"&Ac_No="+Ac_No+"&Voter_Cd="+Voter_Cd;
//     ajaxRequest.open("POST", "setSurveyQCNonVoterFamilyInSession.php" + queryString, true);
//     ajaxRequest.send(null);
// }


// var debounceTimer; 
// function surveyQCAdvanceSearch(fullName){

//     var FirstName = document.getElementsByName('FirstName')[0].value;
//     var MiddleName = document.getElementsByName('MiddleName')[0].value;
//     var LastName = document.getElementsByName('LastName')[0].value;
//     var DBName = document.getElementsByName('DBName')[0].value;

//     if(fullName != ""){
//         // alert(fullName);
        
//         clearTimeout(debounceTimer); // Clear any existing timer

//         debounceTimer = setTimeout(function() {
        
//             $.ajax({
//                 type: "POST",
//                 url: 'getSurveyQCAdvanceSearchResult.php',
//                 data: { 
//                     fullName: fullName,
//                     FirstName: FirstName,
//                     MiddleName: MiddleName,
//                     LastName: LastName,
//                     DBName: DBName
//                 },
//                 success: function(dataResult) {
//                     // alert('in success');
//                     // console.log(dataResult);
//                     // alert(dataResult);

//                     var dataResult = JSON.parse(dataResult);
//                     if(dataResult.statusCode == 200){
//                         var tbody = document.getElementById("tbodydiv");
//                         tbody.innerHTML = dataResult.msg;
//                     }else{
//                         alert(dataResult.msg);
//                     }
//                 }
//             });
//         }, 500);
//     }
// }


/*
function getSurveyQCNonVoterFamilyInSession(FamilyNo,Ac_No,Voter_Cd){

    var FirstName = document.getElementsByName('FirstName')[0].value;
    var MiddleName = document.getElementsByName('MiddleName')[0].value;
    var LastName = document.getElementsByName('LastName')[0].value;
    var FullName = document.getElementsByName('FullName')[0].value;
    var IdCard_No = document.getElementsByName('IdCard_No')[0].value;
    var List_No = document.getElementsByName('List_No')[0].value;

    var AdvanceSearch = document.getElementsByName('AdvanceSearch')[0].value;

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            surveyQCAdvanceSearch(AdvanceSearch)
            $('#spinnerLoader3').hide();
        }
    }
    

    $('#spinnerLoader3').show();
    var queryString = "?FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&FullName="+FullName+"&AdvanceSearch="+AdvanceSearch+"&FamilyNo="+FamilyNo+"&Ac_No="+Ac_No+"&Voter_Cd="+Voter_Cd+"&IdCard_No="+IdCard_No+"&List_No="+List_No;
    // alert(queryString);
    ajaxRequest.open("POST", "setSurveyQCNonVoterFamilyInSession.php" + queryString, true);
    ajaxRequest.send(null);
}
*/


var debounceTimer; 
function surveyQCAdvanceSearch(){

    var FirstName = document.getElementsByName('FirstName')[0].value;
    var MiddleName = document.getElementsByName('MiddleName')[0].value;
    var LastName = document.getElementsByName('LastName')[0].value;
    var fullName = document.getElementsByName('AdvanceSearch')[0].value;
    var DBName = document.getElementsByName('DBName')[0].value;
    var IdCard_No = document.getElementsByName('IdCard_No')[0].value;
    var List_No = document.getElementsByName('List_No')[0].value;

    if(DBName != ""){
        // alert(fullName);
        
        clearTimeout(debounceTimer); // Clear any existing timer

        debounceTimer = setTimeout(function() {
        
            $.ajax({
                type: "POST",
                url: 'getSurveyQCAdvanceSearchResult.php',
                data: { 
                    fullName: fullName,
                    FirstName: FirstName,
                    MiddleName: MiddleName,
                    LastName: LastName,
                    IdCard_No: IdCard_No,
                    List_No: List_No,
                    DBName: DBName
                },
                beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#spinnerLoader2').show();
                },
                success: function(dataResult) {
                    // alert('in success');
                    // console.log(dataResult);
                    // alert(dataResult);

                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        var tbody = document.getElementById("tbodydiv");
                        tbody.innerHTML = dataResult.msg;
                    }else{
                        alert(dataResult.msg);
                    }
                },
                complete: function() {
                    $('#spinnerLoader2').hide();
                }
            });
        }, 500);
    }
}


function SurveyQCNonVoterEdit(DBName,Voter_Cd,SubLocation_Cd,RoomNo,FirstName,MiddleName,LastName){

    var str = DBName;
    var DBName = str.replace("[", "").replace("]", "");

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            window.location.href='index.php?p=Survey-QC-NonVoter-Edit';
        }
    }

    var queryString = "?Voter_Cd="+Voter_Cd+"&SubLocation_Cd="+SubLocation_Cd+"&RoomNo="+RoomNo+"&FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&DBName="+DBName;
    // alert(queryString);
    ajaxRequest.open("POST", "setSurveyQCEditInSession.php" + queryString, true);
    ajaxRequest.send(null);
}


// function saveSurveyQCSocietyData(Society_Cd) {
 
//     if (confirm("Are you Sure you want to get QC done of selected society?") == true) 
//     {
//         $.ajax({

//             type: "POST",
//             url: 'action/saveSurveyQCSocietyData.php',
//             data: { 
//                 Society_Cd: Society_Cd
//             },
//             beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
//                 // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
//                 // $('html').addClass("ajaxLoading");
//             },
//             success: function(dataResult) {
//                 // alert('in success');
//                 // console.log(dataResult);
//                 // alert(dataResult);

//                 var dataResult = JSON.parse(dataResult);
//                 if(dataResult.statusCode == 200){
//                     $("#msgsuccess").html(dataResult.msg)
//                         .hide().fadeIn(1000, function() {
//                             $("msgsuccess").append("");
//                             // location.reload(true);
//                             window.location.href='index.php?p=Survey_QC';
//                         }).delay(3000).fadeOut("fast");
//                 }else{
//                     $("#msgfailed").html(dataResult.msg)
//                         .hide().fadeIn(800, function() {
//                             $("msgfailed").append("");
//                         }).delay(4000).fadeOut("fast");
//                 }
//             }
//             // ,
//             // complete: function() {
//             //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
//             //         $('html').removeClass("ajaxLoading");
//             //     }
//         });
//     }
// }


function saveSurveyQCSocietyData(Society_Cd,DBName,SubLocation_Cd) {
 
    if (confirm("Are you Sure you want to get QC done of selected society?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveSurveyQCSocietyData.php',
            data: { 
                Society_Cd: Society_Cd,
                DBName: DBName,
                SubLocation_Cd: SubLocation_Cd

            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            // location.reload(true);
                            window.location.href='index.php?p=Survey_QC';
                        }).delay(6000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(9000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}

function setAssignedToSurveyQCInSession(AssignedTo) {
    // alert(siteName);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('.zero-configuration').DataTable();
            // $('#BuildingSurveyWithNoOrdering').DataTable();
            $(document).ready(function () {
                $('#BuildingSurveyWithNoOrdering').DataTable({
                  ordering: false
                });
            });
            $('.select2').select2();
        }
    }
 
    if (AssignedTo === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?AssignedTo="+AssignedTo;
        ajaxRequest.open("POST", "setAssignedToSurveyQCInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}

/*
function saveSurveyQCNonVoterToVoter(NonvVoterVoter_Cd,Ac_No,List_No,Voter_Id) {
 
    if (confirm("Are you Sure you want save this Non Voter as Voter?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveSurveyQCNonVoterToVoter.php',
            data: { 
                NonvVoterVoter_Cd: NonvVoterVoter_Cd,
                Ac_No: Ac_No,
                List_No: List_No,
                Voter_Id: Voter_Id
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            // location.reload(true);
                            window.location.href='index.php?p=Survey-QC-Details' + dataResult.url;
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}*/



function saveSurveyQCNonVoterToVoter(NonvVoterVoter_Cd) {

    var VoterCds = document.getElementsByName('VoterCds')[0].value;
 
    if (confirm("Are you Sure you want save this Non Voter as Voter?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveSurveyQCNonVoterToVoter.php',
            data: { 
                NonvVoterVoter_Cd: NonvVoterVoter_Cd,
                VoterCds: VoterCds
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            // location.reload(true);
                            window.location.href='index.php?p=Survey-QC-Details' + dataResult.url;
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}





function setSurveyQCNonVoterFamilyIds() {
    var input = document.getElementsByClassName("checkbox");
    
    var selected = 0;
    var chkAllCDS = "";
    var chkAllNames = "";
    // var chkAllAssignedCount = 0;
 
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
          var splits = input[i].value.split(",");
          var CD_Val = '';
          var Name_Val = '';
          //var AssCount = 0;
 
          CD_Val += ""+splits[0]+"";
          Name_Val += ""+splits[1]+"";
          chkAllCDS += CD_Val+",";  
          chkAllNames += Name_Val+", ";  
        //   console.log(chkAllCDS);
        //   console.log(chkAllNames);
 
          selected ++;
        }
      
    }
   
    document.getElementsByName("VoterCds")[0].value = "" + chkAllCDS;
    // document.getElementsByName("societyNames")[0].value = "" + chkAllNames;
    // document.getElementsByName("societyNames")[0].title = "" + chkAllNames;
 
}

function setSurveyQCFamilyALLIds(ele) {
    
    
    var checkboxes = document.getElementsByClassName('checkbox');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            console.log(i)
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }

    setSurveyQCNonVoterFamilyIds();

}



// ULB and Server Name
function setULDandServerNameIsSession(ULB,ServerName,Election,ElectionCd){
    
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            location.reload(true);
        }
    }

    if (ULB === '') {
        alert("Please Select ULB!");
    } else {
		
        document.getElementById("LoaderBeforeLoadMainDataDIV").style.display = "block";
        var queryString = "?ULB="+ULB+"&ServerName="+ServerName+"&Election="+Election+"&ElectionCd="+ElectionCd;
        ajaxRequest.open("POST", "setULBandServerNameInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }
}
// ULB and Server Name


function loadBuildingdiv() {
    // alert(DivId);
        var x = document.getElementById("BuildingwiseData");
        var y = document.getElementById("ElectionwiseData");
        var z = document.getElementById("ElectionwiseData");
      if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
      }else{
        // x.style.display = "none";
        // y.style.display = "block";
      }
    }
    function loadExecutivediv() {
    // alert(DivId);
        var y = document.getElementById("BuildingwiseData");
        var x = document.getElementById("ElectionwiseData");
        var z = document.getElementById("SocietyWiseData");
      if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
      }else{
        // x.style.display = "none";
        // y.style.display = "block";
      }
    }
	
    function getelectionData(Election) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    // alert(Election);
                    // var ajaxDisplay = document.getElementById('ElectionwiseData');
                    // ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        // $('#OverallSummry').hide();
                    $( "#ElectionWiseSummary" ).load(window.location.href + " #ElectionWiseSummary" );
                    $('#ElectionwiseDataMainDiv').show(); 
                    $('#BuildingwiseData').show(); 
                    $('#LoaderDiv').show();
                    $('#ExecutiviWise').hide(); 
                    $('.zero-configuration').DataTable();
                        $('html, body').animate({
                           scrollTop: $("#ElectionwiseDataMainDiv").offset().top
                       }, 300);
                        // $('#OverallSummry').hide(); 
                        // $('#ElectionwiseData').show(); 
                    // location.reload(true);        
                }
            }
        // alert(DivId);
        if (Election === '') {
            alert("Please Select Ward!");
        } else {
            $('#LoaderDiv').show();
            var queryString = "?Election="+Election;
            ajaxRequest.open("POST", "setElectionInSessionForElectionSummary.php" + queryString, true);
            ajaxRequest.send(null);
            // $( "#HomeDashboardMainDivID" ).load(window.location.href + " #HomeDashboardMainDivID" );
        }
    
    }
	
    function GetExecutiveWiseData(Executive) {
        var ajaxRequest; // The variable that makes Ajax possible!
    // alert(Executive);
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    $( "#ElectionWiseSummary" ).load(window.location.href + " #ElectionWiseSummary" );
                    // var x = document.getElementById("ExecutiviWise");
                    //  x.style.display = "block";
                    $('#ExecutiviWise').show(); 
                    $('#SocietyWiseData').hide(); 
                    $('#ExecutiveLoaderDiv').show();
                    $('.zero-configuration').DataTable();
                        $('html, body').animate({
                           scrollTop: $("#ExecutiveLoaderDiv").offset().top
                       }, 200);      
                }
            }
        // alert(DivId);
        if (Executive === '') {
            alert("Please Select Ward!");
        } else {
            $('#ExecutiveLoaderDiv').show();
            var queryString = "?Executive="+Executive;
            ajaxRequest.open("POST", "setExecutiveNameForElectionSummaryInSession.php" + queryString, true);
            ajaxRequest.send(null);
            // $( "#HomeDashboardMainDivID" ).load(window.location.href + " #HomeDashboardMainDivID" );
        }
    
    }
	
    function GetSiteWiseData(SiteName) {
        var ajaxRequest; // The variable that makes Ajax possible!
    // alert(SiteName);
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    $( "#ElectionWiseSummary" ).load(window.location.href + " #ElectionWiseSummary" );
                    $('#SocietyWiseData').show(); 
                    $('#ExecutiveLoaderDiv').show();
                    $('.zero-configuration').DataTable();
                        $('html, body').animate({
                           scrollTop: $("#SocietyWiseData").offset().top
                       }, 300);    
                }
            }
        // alert(DivId);
        if (SiteName === '') {
            alert("Please Select Ward!");
        } else {
            $('#ExecutiveLoaderDiv').show();
            var queryString = "?SiteName="+SiteName;
            ajaxRequest.open("POST", "setSiteNameForElectionSummaryInSession.php" + queryString, true);
            ajaxRequest.send(null);
            // $( "#HomeDashboardMainDivID" ).load(window.location.href + " #HomeDashboardMainDivID" );
        }
    
    }
	
    function GetSocietyWiseData(SocietyName) {
        var ajaxRequest; // The variable that makes Ajax possible!
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    $( "#ElectionWiseSummary" ).load(window.location.href + " #ElectionWiseSummary" );
                    $('#SocietyNameWise').show(); 
                    $('#SocietyLoaderDiv').show();
                    $('.zero-configuration').DataTable();
                        $('html, body').animate({
                           scrollTop: $("#SocietyNameWise").offset().top
                       }, 300);   
                }
            }
        // alert(DivId);
        if (SocietyName === '') {
            alert("Please Select Ward!");
        } else {
            $('#SocietyLoaderDiv').show();
            var queryString = "?SocietyName="+SocietyName;
            ajaxRequest.open("POST", "SetSocietyNameInSessionForElectionSummary.php" + queryString, true);
            ajaxRequest.send(null);
            // $( "#HomeDashboardMainDivID" ).load(window.location.href + " #HomeDashboardMainDivID" );
        }
    
    }
// 	function GetFromAndToDate(){
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//             if (ajaxRequest.readyState == 4) {
//                 $('#DateLoaderDiv').show();
//                 $( "#ElectionWiseSummary" ).load(window.location.href + " #ElectionWiseSummary" );
//                 // window.location.href='index.php?p=ElectionSummry';
//                 $('.zero-configuration').DataTable();
//             }
//         }
   
//     var fromdate = document.getElementsByName('fromdate')[0].value;
//     var todate = document.getElementsByName('todate')[0].value;

//     if (fromdate === '') {
//         alert("Please Select ward!");
//     } else {
//         $('#DateLoaderDiv').show();
//         var queryString = "?fromdate="+fromdate+"&todate="+todate;
//         ajaxRequest.open("POST", "SetFromAndToDateInSession.php" + queryString, true);
//         ajaxRequest.send(null);

//     }
// }


// BLIST SUMMARY REPORT START---------------------------------------------------------------------------------------------------------------------------------------------------------

function getBLExecutiveWiseDetailedData(ElectionName,Username,ExecutiveName,fromDate,toDate){
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    }catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    
    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            var ajaxDisplay = document.getElementById('BLExecutiveWiseDetailedRecord');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#spinnerLoader').hide(); 
                $('.zero-configuration').DataTable();
                $('#BLExecutiveWiseDetailedRecord').show(); 
                $('html, body').animate({
                    scrollTop: $("#BLExecutiveWiseDetailedRecord").offset().top
                }, 500);
        }
    }

    if (Username === '') {
        alert("Please Select User");
    }else{
        $('#spinnerLoader').show(); 
        $('#BLExecutiveWiseDetailedRecord').hide();
        var queryString = "?Username="+Username+"&fromDate="+fromDate+"&toDate="+toDate+"&ExecutiveName="+ExecutiveName+"&ElectionName="+ElectionName;
        ajaxRequest.open("POST", "getBLExecutiveWiseDetailedData.php" + queryString, true);
        ajaxRequest.send(null); 
    }
}


function getDatesForBLSummaryReport() {

    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    // ajaxRequest.onreadystatechange = function() {
    //     if (ajaxRequest.readyState == 4) {
    //         location.reload(true);
    //     }
    // }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader').hide(); 
            $('.zero-configuration').DataTable();
            $('.select2').select2();
        }
    }
    
    if (fromDate === '') {
        alert("Please Enter From Date !");
    }else if (toDate === '') {
        alert("Please Enter To Date !");
    }
    else if(fromDate > toDate){
        alert("Please Select To Date greater than From Date!");
    } else {
        $('#spinnerLoader').show(); 
        var queryString = "?fromDate="+fromDate+"&toDate="+toDate;
        ajaxRequest.open("POST", "setDatesForBLSummaryReportInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


// BLIST SUMMARY REPORT END---------------------------------------------------------------------------------------------------------------------------------------------------------

function getSiteName(SiteName,ElectionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                window.location.href='index.php?p=MapClientDashboard';
            }
        }
    
    if (SiteName === '') {
        alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName+"&ElectionName="+ElectionName;
        ajaxRequest.open("POST", "setSiteNameInMapInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
// function setSiteForClientDashboardInSession(SiteName) {
//     var ajaxRequest; // The variable that makes Ajax possible!

//     try {
//         // Opera 8.0+, Firefox, Safari
//         ajaxRequest = new XMLHttpRequest();
//     } catch (e) {
//         // Internet Explorer Browsers
//         try {
//             ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
//         } catch (e) {
//             try {
//                 ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
//             } catch (e) {
//                 // Something went wrong
//                 alert("Your browser broke!");
//                 return false;
//             }
//         }
//     }

//     ajaxRequest.onreadystatechange = function() {
//             if (ajaxRequest.readyState == 4) {
//                 location.reload(true);
//                 // window.location.href='index.php?p=MapClientDashboard';
//             }
//         }
    
//     if (SiteName === '') {
//         alert("Please Select SiteName!");
//     } else {
//         // alert(SiteName);
//         // alert(ElectionName);
//         var queryString = "?SiteName="+SiteName;
//         ajaxRequest.open("POST", "setSiteNameInMapInSessionForClientDashboard.php" + queryString, true);
//         ajaxRequest.send(null);

//     }

// }

function SurveyStatusChange(SurveyStatus){

    $('#QCStatus').attr("disabled", false);
    $('#fromDate').attr("disabled", false);
    $('#toDate').attr("disabled", false);

    if(SurveyStatus == '2'){
        // $('#QCStatus').attr("disabled", true);
        $('#fromDate').attr("disabled", true);
        $('#toDate').attr("disabled", true);
    }else if(SurveyStatus == '0'){
        $('#fromDate').attr("disabled", true);
        $('#toDate').attr("disabled", true);
    }
}



function DeleteExtraVoter(Voter_Cd){

    var DeleteExtraVoter = 'DeleteExtraVoter';
 
    if (confirm("Are you Sure you want to delete selected Voter?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveSurveyQCNonVoterToVoter.php',
            data: { 
                Voter_Cd: Voter_Cd,
                DeleteExtraVoter: DeleteExtraVoter
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    alert(dataResult.msg);
                    location.reload(true);
                    // window.location.href='index.php?p=Survey-QC-Details' + dataResult.url;
                }else{
                    alert(dataResult.msg);
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}


function DeleteExtraLockRoom(Society_Cd,RoomNo){

    var DeleteExtraLockRoom = 'DeleteExtraLockRoom';
 
    if (confirm("Are you Sure you want to delete this LockRoom?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/saveSurveyQCNonVoterToVoter.php',
            data: { 
                Sublocation_Cd: Society_Cd,
                RoomNo: RoomNo,
                DeleteExtraLockRoom: DeleteExtraLockRoom
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    alert(dataResult.msg);
                    location.reload(true);
                    // window.location.href='index.php?p=Survey-QC-Details' + dataResult.url;
                }else{
                    alert(dataResult.msg);
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}


function setSiteForClientDashboardInSession(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
                // window.location.href='index.php?p=MapClientDashboard';
            }
        }
    
    if (SiteName === '') {
        alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setSiteNameInMapInSessionForClientDashboard.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function getAllSiteData(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
                // window.location.href='index.php?p=MapClientDashboard';
            }
        }
    
    if (SiteName === '') {
        alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setAllSiteNameDataInMapInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function getExecutiveData(ExecutiveName){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            $('#SurveySummaryExecutiveDataLoad').show();
            $('html, body').animate({
                scrollTop: $("#SurveySummaryExecutiveDataLoad").offset().top
            }, 500);
            
            var ajaxDisplay = document.getElementById('SurveySummaryExecutiveDataLoad');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#executiveDetailTable').show();
            $(document).ready(function () {
              $('#ExecutiveWiseDetail').DataTable({
                "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
              });
          });
            // $( "#Executivedetail" ).load(window.location.href + " #Executivedetail" );
        }
    }
    // alert(ExecutiveName);

    var queryString = "?ExecutiveName="+ExecutiveName;
    // alert(queryString);
    ajaxRequest.open("POST", "setExecutiveNammeInSessionForExecutiveDetail.php" + queryString, true);
    ajaxRequest.send(null);
}
function getExeFilter(Status){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
                window.location.href='index.php?p=Survey_Summary_Report';
        }
    }
    // alert(Status);

    var queryString = "?Status="+Status;
    // alert(queryString);
    ajaxRequest.open("POST", "setExecutiveActiveInActiveInSession.php" + queryString, true);
    ajaxRequest.send(null);
}
function GetFromAndToDate(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            // $( "#DateWise" ).load(window.location.href + " #DateWise" );
            // window.location.href='index.php?p=ElectionSummry';
            location.reload(true);
        }
    }
   
    var fromdate = document.getElementsByName('fromdate')[0].value;
    var todate = document.getElementsByName('todate')[0].value;
    var Site = document.getElementsByName('SiteSearch')[0].value;
    var WorkingDays = document.getElementsByName('Workingdays')[0].value;
    var ToWorkingdays = document.getElementsByName('ToWorkingdays')[0].value;
    // var div = 'DateWise';
    // alert(WorkingDays);
    // alert(ToWorkingdays);
    // alert(Site);
    if (fromdate === '') {
        alert("Please Select ward!");
    } else {
        var queryString = "?fromdate="+fromdate+"&todate="+todate+"&SiteName="+Site+"&WorkingDays="+WorkingDays+"&ToWorkingdays="+ToWorkingdays;
        ajaxRequest.open("POST", "SetFromAndToDateForSummaryInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}


// Birthday Count Functions ------------------------------------------------------------------------------------

function getVoctersDetail(VoterCd,DBName){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            // $( "#BdayGridView" ).load(window.location.href + " #BdayGridView" );
            var ajaxDisplay = document.getElementById('BdayGridView');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#MODAL_VIEW').modal('show');
        }
    }
    // alert(Status);
    //    var div ='profile';
    var queryString = "?VoterCd="+VoterCd+"&DBName="+DBName;
    // alert(VoterCd);
    // alert(DBName);
    ajaxRequest.open("POST", "SetVoterCdDBForBirthdateReportInSession.php" + queryString, true);
    ajaxRequest.send(null);
}

function getDatesForBirthdayReport(SiteName) {

    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    var ajaxRequest; 
    // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            // ajaxDisplay.innerHTML = ajaxRequest.responseText;
            // $('#spinnerLoader2').hide(); 
            // $('.zero-configuration').DataTable();
            // $('.select2').select2();
            location.reload(true);
        }
    }
    
    if (fromDate === '') {
        alert("Please Enter From Date !");
    }else if (toDate === '') {
        alert("Please Enter To Date !");
    }else if(fromDate > toDate){
        alert("Please Select To Date greater than From Date!");
    } else {
        $('#spinnerLoader2').show(); 
        var queryString = "?fromDate="+fromDate+"&toDate="+toDate+"&SiteName="+SiteName;
        ajaxRequest.open("POST", "setDatesForBirthdayReportInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}
function GetBdayCount(BdayDate) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
            $( "#DateBirthday" ).load(window.location.href + " #DateBirthday" );
                $('#BdayListTable').show(); 
        }
    }
    
    if (BdayDate == '') {
        alert("Please Select birthdate!");
    } else {
        $('#BdayListTable').hide(); 
        var queryString = "?BdayDate=" + BdayDate;
        ajaxRequest.open("POST", "SetBirthdateInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }

}
function getMonthForBday(Date) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
                $('#BdayListTable').show(); 
                location.reload(true);
        }
    }
    
    if (Date == '') {
        alert("Please Select birthdate!");
    } else {
        $('#BdayListTable').hide(); 
        var queryString = "?Date=" + Date;
        ajaxRequest.open("POST", "SetBirthdateFilterInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }

}
// Birthday Count Functions ------------------------------------------------------------------------------------


function convertToUpperCase(input) {
    // Remove numbers and special characters
    let sanitizedValue = input.value.replace(/[^a-zA-Z ]/g, '');

    // Convert to uppercase
    let upperCaseValue = sanitizedValue.toUpperCase();

    // Update the input value
    input.value = upperCaseValue;
  }

  function convertToUpperCaseClientName(input) {
    // Remove numbers and special characters
    let sanitizedValue = input.value.replace(/[^a-zA-Z /]/g, '');

    // Convert to uppercase
    let upperCaseValue = sanitizedValue.toUpperCase();

    // Update the input value
    input.value = upperCaseValue;
  }


  function convertToUpperCaseAndNumbersOnly(input) {
    // Remove special characters
    let sanitizedValue = input.value.replace(/[^a-zA-Z0-9]/g, '');

    // Convert to uppercase
    let upperCaseValue = sanitizedValue.toUpperCase();

    // Update the input value
    input.value = upperCaseValue;
  }
  
  

function setSiteSurveyQCDateWiseInSession(siteName) {
    // alert(siteName);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            // $('.zero-configuration').DataTable();
            $('#spinnerLoader2').hide(); 
            // $('#SurveyQCTblDataHideDiv').show();
            $(document).ready(function () {
                $('#SurveyQCLiveSiteWise').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
              });
              $(document).ready(function () {
                $('#SurveyQCExecutiveSiteWise').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }

    
    if (siteName === '') {
        alert("Please Select Site!");
    } else {
        $('#spinnerLoader2').show(); 
        // $('#SurveyQCTblDataHideDiv').hide();
        var queryString = "?siteName="+siteName;
        ajaxRequest.open("POST", "setSiteSurveyQCDateWiseInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function getSurveyQCDateWiseTableFilterData() {

    var SiteCd = document.getElementsByName('SiteName')[0].value;
    var fromDate = document.getElementsByName('fromDate')[0].value;
    var toDate = document.getElementsByName('toDate')[0].value;
    // alert(QCStatus);

    // alert(QCAssignedTo);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#spinnerLoader2').hide(); 
            // $('#SurveyQCTblDataHideDiv').show();
            // $('.zero-configuration').DataTable();
            $(document).ready(function () {
                $('#SurveyQCLiveSiteWise').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
              });
              $(document).ready(function () {
                $('#SurveyQCExecutiveSiteWise').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (SiteCd === '') {
        alert("Please Select Site");
    } else {
        $('#spinnerLoader2').show(); 
        // $('#SurveyQCTblDataHideDiv').hide();
        var queryString = "?SiteCd="+SiteCd+"&fromDate="+fromDate+"&toDate="+toDate;
        // alert(queryString);
        ajaxRequest.open("POST", "setSurveyQCDateWiseDataInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}
function getSiteWiseDetail(Site){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SiteWiseDetail');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            // $('#MODAL_VIEW').modal('show');
            $('#SiteData').show();   
            $(document).ready(function () {
                $('#SiteNameWiseSurveyTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('html, body').animate({
                scrollTop: $("#SiteWiseDetail").offset().top
            }, 500); 
        }
    }
    // alert(Status);
    //    var div ='profile';
    var queryString = "?Site="+Site;
    // alert(Site);
    ajaxRequest.open("POST", "SetSiteNameForModalInSession.php" + queryString, true);
    ajaxRequest.send(null);
}   
function getSiteNameForMap(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                window.location.href='index.php?p=MapForDashboard';
            }
        }
    
    if (SiteName === '') {
        // alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setSiteNameInMapForDashboardInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function setSiteForDashboardInSession(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
                // window.location.href='index.php?p=MapClientDashboard';
            }
        }
    
    if (SiteName === '') {
        alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setSiteNameInMapInSessionForDashboard.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function getAllDetailSiteData(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
               
                // window.location.href='index.php?p=MapClientDashboard';
            }
        }
    
    if (SiteName === '') {
        alert("Please Select SiteName!");
    } else {
        // alert(SiteName);
        // alert(ElectionName);
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setAllSiteNameDataInMapForDashboardInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function rotateimage(filePathAndName) {
    var rotation = 0;
    rotation = (rotation + 90) % 360;
    var degree = rotation;

    if(filePathAndName === ''){
        alert('File URL is Null');
    }else{
        $.ajax({
            url: 'image-rotate.php',
            type: 'GET',
            data: {
                degree: degree,
                filePathAndName: filePathAndName
            },
            // success: function(response) {
            success: function() {
                // alert(response);
                // console.log(response)
                
                $("#previewImg2").attr('src', filePathAndName);
                $('#previewImg2').load('#Building_Image');
            }
        });
    }
}

function CloseModal() {
    //    var x = document.getElementById("SiteWiseDetail");
    //    x.style.display = "none";
       $('#MODAL_VIEW').modal('hide');
    }
function getMonthlyReports(pageid) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
        selectYear = document.getElementsByName('selectYear')[0].value;
        selectMonth = document.getElementsByName('selectMonth')[0].value;
        selectdesignation = document.getElementsByName('selectdesignation')[0].value;
        selectsite = document.getElementsByName('selectsite')[0].value;

        // if(pageid=='tasks-summary'){
             
        // }
    
        if (selectYear === '') {
            alert("Please Select Year!");
        } else if (selectMonth === '') {
            alert("Please Select Month!");
        } 
        // else if (selectdesignation === '') {
        //     alert("Please Select Designation!");
        // } 
        else {
            // alert(selectYear);
            // alert(selectMonth);
            // alert(selectdesignation);
            var queryString = "?pageid="+pageid+"&selectYear="+selectYear+"&selectMonth="+selectMonth+"&selectdesignation="+selectdesignation+"&selectsite="+selectsite;
            ajaxRequest.open("POST", "setMonthlyReportsFilters.php" + queryString, true);
            ajaxRequest.send(null);
        }

}

function insertAttendanceData(Executive_Cd,SurveyDate,action){
  
    ExecutiveName = document.getElementsByName('ExecutiveName')[0].value;
    SurveyDate = document.getElementsByName('SurveyDate')[0].value;
    Attendance= document.getElementsByName('Attendance')[0].value;
    AbsentRemark= document.getElementsByName('AbsentRemark')[0].value;
    SiteName= document.getElementsByName('SiteName')[0].value;
   
    $.ajax({

        type: "POST",
        url: 'action/InsertAttendenceData.php',
        data: { 
            Executive_Cd: Executive_Cd,
            ExecutiveName:ExecutiveName,
            SurveyDate:SurveyDate,
            Attendance:Attendance,
        
            action:action,
            AbsentRemark:AbsentRemark,
            SiteName:SiteName
          

        },
        success: function(dataResult) {
           //alert(dataResult);
           var dataResult = JSON.parse(dataResult);
          
           if(dataResult.statusCode == 200){
               
           
               $("#msgsuccess").html(dataResult.msg)
                   .hide().fadeIn(1000, function() {
                       $("msgsuccess").append("");
                   
                       location.reload();
                   }).delay(3000).fadeOut("fast");
           }else{
             window.location.href="index.php?p=Attendence_report";
               $("#msgfailed").html(dataResult.msg)
                  .hide().fadeIn(800, function() {
                       $("msgfailed").append("");
                   }).delay(4000).fadeOut("fast");
           }
           location.reload(true);
       }
    });

}

function updateAttendanceData(Executive_Cd,SurveyDate,Doc_No,action) {

    ExecutiveName = document.getElementsByName('ExecutiveName')[0].value;
    SurveyDate = document.getElementsByName('SurveyDate')[0].value;
    Attendance= document.getElementsByName('Attendance')[0].value;
   // InTime= document.getElementsByName('InTime')[0].value;
    AbsentRemark= document.getElementsByName('AbsentRemark')[0].value;
    SiteName= document.getElementsByName('SiteName')[0].value;
        $.ajax({

            type: "POST",
            url: 'action/updateAttendenceData.php',
            data: { 
                Executive_Cd: Executive_Cd,
                ExecutiveName:ExecutiveName,
                SurveyDate:SurveyDate,
                Attendance:Attendance,
                Doc_No:Doc_No,
                action:action,
                AbsentRemark:AbsentRemark,
                SiteName:SiteName
              

            },

          
            success: function(dataResult) {
                //  console.log(dataResult);
                //  alert(dataResult);
                
                var dataResult = JSON.parse(dataResult);
                 if(dataResult.statusCode == 200){
            
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            location.reload(true);

                        // window.location.href="index.php?p=Attendence_report";
                        }).delay(3000).fadeOut("fast");
                }else{
                      alert(dataResult.msg);
                    $("#msgfailed").html(dataResult.msg)
                       .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
                location.reload(true);
            }
           
        });
    }
    function getattendenceDetail(Executive_Cd,Day){

        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // $( "#AttendenceTable" ).load(window.location.href + "#AttendenceTable" );
                var ajaxDisplay = document.getElementById('AttendenceView');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#MODAL_VIEW1').modal('show');
                
               
            }
        }
        // alert(Status);
        //    var div ='profile';"?fromdate="+fromdate+"&todate="+todate;
       var queryString = "?Executive_Cd="+Executive_Cd+"&Day="+Day;
      // +"&SurveyDate="+SurveyDate+"&Doc_No="+Doc_No
        //alert(queryString);
        // alert(DBName);
        ajaxRequest.open("POST","OpenAttendenceDetails.php" + queryString, true);
        ajaxRequest.send(null);
    }

    function GetWorkingDateFilter(){
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // $( "#DateWise" ).load(window.location.href + " #DateWise" );
                // window.location.href='index.php?p=ElectionSummry';
                location.reload(true);
            }
        }
       
        var WorkingDaysExec = document.getElementsByName('WorkingDaysExec')[0].value;
        var ToWorkingDaysExec = document.getElementsByName('ToWorkingDaysExec')[0].value;
        // var div = 'DateWise';
        // alert(WorkingDays);
        // if (WorkingDaysExec === '') {
        //     alert("Please Select ward!");
        // } else {
            var queryString = "?WorkingDaysExec="+WorkingDaysExec+"&ToWorkingDaysExec="+ToWorkingDaysExec;
            ajaxRequest.open("POST", "SetWorkingDaysExecForSummaryInSession.php" + queryString, true);
            ajaxRequest.send(null);
        // }
    }
    
	
	
// Assign Executive To Site ------------------------------------------------------------------------------------------------------------------------

    function setElectionNameInSessionFromAETS(electionName) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                document.getElementById("spinnerLoader2").style.display = "none";
                $(document).ready(function () {
                    $('#AssignExecutiveToSiteTableID').DataTable({
                        "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                    $('#AttendanceTable').DataTable({
                      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                    });

                    $('#AssignExecutiveToSiteTableReportID').DataTable({
                      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                });
                $('.select2').select2();
            }
        }
        
        if (electionName === '') {
            alert("Please Select Corporation!");
        } else {
            document.getElementById("spinnerLoader2").style.display = "block";
            var queryString = "?electionName="+electionName;
            ajaxRequest.open("POST", "setElectionNameinsessionAssignExecToSite.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    }

    function setFilterTypeInSessionFromAETS(filter) {
        var ajaxRequest; // The variable that makes Ajax possible!
        Executive_CdArray = [];
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                document.getElementById("spinnerLoader2").style.display = "none";
                $(document).ready(function () {
                    $('#AssignExecutiveToSiteTableID').DataTable({
                        "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                    $('#AttendanceTable').DataTable({
                      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                    });

                    $('#AssignExecutiveToSiteTableReportID').DataTable({
                      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                });
                $('.select2').select2();
            }
        }
        
        if (filter === '') {
            alert("Please Select filter!");
        } else {
            document.getElementById("spinnerLoader2").style.display = "block";
            var queryString = "?filter="+filter;
            ajaxRequest.open("POST", "setElectionNameinsessionAssignExecToSite.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    }

    function setDateInSessionFromAETS(Date) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                document.getElementById("spinnerLoader2").style.display = "none";
                $(document).ready(function () {
                    $('#AssignExecutiveToSiteTableID').DataTable({
                        "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                    $('#AttendanceTable').DataTable({
                      "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                    });

                    $('#AssignExecutiveToSiteTableReportID').DataTable({
                      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                    });
                });
                $('.select2').select2();
            }
        }
        
        if (Date === '') {
            alert("Please Select Date!");
        } else {
            document.getElementById("spinnerLoader2").style.display = "block";
            var queryString = "?Date="+Date;
            ajaxRequest.open("POST", "setElectionNameinsessionAssignExecToSite.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    }

    
    
    function setAssignExecutiveToSite() {
        var input = document.getElementsByClassName("checkbox");
        var selected = 0;
        var chkAllCDS = "";
        for (var i = 0; i < input.length; i++) {
          if (input[i].checked) {
            var splits = input[i].value;
            
            var CD_Val = '';
            CD_Val += "" + splits + "";
            chkAllCDS += splits + ",";
            // chkAllNames += Name_Val + ", ";
            selected++;
          }
        }
        document.getElementsByName("ExecutiveCds")[0].value = "" + chkAllCDS;
        document.getElementById("SelectedExecutiveCds").innerHTML = selected;
    }
    

function AssignExecutiveToSite() {
 
    //var electionName = document.getElementsByName('electionName')[0].value;
    var SiteName = document.getElementsByName('SiteName')[0].value;
    var FilterType = document.getElementsByName('FilterType')[0].value;
    var Date = document.getElementsByName('Date')[0].value;
    var Supervisor = document.getElementsByName('Supervisor')[0].value;
    var ExecutiveCds = document.getElementsByName('ExecutiveCds')[0].value;
    var AttendanceFilter = document.getElementsByName('AttendanceFilter')[0].value;

    if(SiteName === ''){
        alert("Please select Site!");
    }else if(Date === ''){
        alert("Please select Date!");
    }else if(Supervisor === ''){
        alert("Please select Supervisor!");
    }else if(ExecutiveCds === ''){
        alert("Please select Executives!");
    }else if(AttendanceFilter === ''){
        alert("Please select Attendance!");
    }else{
        $.ajax({

            type: "POST",
            url: 'action/assignExecutiveToSite.php',
            data: { 
                SiteName: SiteName,
                FilterType: FilterType,
                Date: Date,
                Supervisor: Supervisor,
                ExecutiveCds: ExecutiveCds,
                AttendanceFilter: AttendanceFilter
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#UpdateButton').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "block";
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                                location.reload(true);
                        }).delay(6000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                                location.reload(true);
                        }).delay(6000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdateButton').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "none";
            }
        });
    }
}

    // Transfer ----------------------------------------------------------------------------------------------------------------

function setElectionNameInSessionFromAETSTransfer(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#AssignExecutiveToSiteTableID').DataTable({
                    "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setInSessionAssignedExecToSiteTransfer.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function setDateInSessionFromAETSTransfer(Date) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#AssignExecutiveToSiteTableID').DataTable({
                    "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Date === '') {
        alert("Please Select Date!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Date="+Date;
        ajaxRequest.open("POST", "setInSessionAssignedExecToSiteTransfer.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function AssignedExecutiveToSiteTransfer() {
 
    var RecordDate = document.getElementsByName('Date')[0].value;
    var TransferDate = document.getElementsByName('TransferDate')[0].value;
    var SupervisorCds = document.getElementsByName('SupervisorCds')[0].value;

    if(TransferDate === ''){
        alert("Please select Date!");
    }else if(SupervisorCds === ''){
        alert("Please select Records!");
    }else{
        $.ajax({

            type: "POST",
            url: 'action/assignedExecutiveToSiteTransfer.php',
            data: { 
                TransferDate: TransferDate,
                RecordDate: RecordDate,
                SupervisorCds: SupervisorCds
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#UpdateButton').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "block";
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                                location.reload(true);
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                                location.reload(true);
                        }).delay(4000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdateButton').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "none";
            }
        });
    }
}

function setAssignedExecutiveToSiteToTransfer() {
    var input = document.getElementsByClassName("checkbox");
    var selected = 0;
    var chkAllCDS = "";
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
        var splits = input[i].value;
        
        var CD_Val = '';
        CD_Val += "" + splits + "";
        chkAllCDS += splits + ",";
        // chkAllNames += Name_Val + ", ";
        selected++;
      }
    }
    document.getElementsByName("SupervisorCds")[0].value = "" + chkAllCDS;
    document.getElementById("SelectedExecutiveCds").innerHTML = selected;
}


function setAssignedExecutiveToSiteToTransferAll(ele) {
    
    
    var checkboxes = document.getElementsByClassName('checkbox');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            console.log(i)
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }

    setAssignedExecutiveToSiteToTransfer();

}


function setSupervisorNameOnChangeofSite(Site_Cd) {

    // $('#Supervisor').find('option').not(':first').remove();
    // AJAX request
    $.ajax({
        url: 'setSupervisorOnchangeOfSite.php',
        type: 'post',
        data: {Site_Cd:Site_Cd},
        dataType: 'json',
        success: function(response){
            var len = response.length;
            // alert(len);
            for( var i = 0; i<len; i++){
                var Executive_Cd = response[i]['Executive_Cd'];
                var ExecutiveName = response[i]['ExecutiveName'];
                $("#Supervisor").append("<option selected=true value='"+Executive_Cd+"~"+ExecutiveName+"'>"+ExecutiveName+"</option>");
            }
        }
    });
}

// Assign Executive To Site ------------------------------------------------------------------------------------------------------------------------



// Move DB Data Starts here-------------------------------------------------------------------------------------------------

function setFromServerNameInSession(servername) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (servername === '') {
        alert("Please Select Server!");
    } else {
        var queryString = "?servername="+servername;
        ajaxRequest.open("POST", "setFromServernameMoveDBInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function setToServerNameInSession(servername) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (servername === '') {
        alert("Please Select Server!");
    } else {
        var queryString = "?servername="+servername;
        ajaxRequest.open("POST", "setToServernameMoveDBInSession.php" + queryString, true);
        ajaxRequest.send(null);
    }
}

function moveDBDataToAnotherServer() {
 
    var FromServerName = document.getElementsByName('FromServerName')[0].value;
    var ToServerName = document.getElementsByName('ToServerName')[0].value;
    var FromElectionName = document.getElementsByName('FromElectionName')[0].value;
    var ToElectionName = document.getElementsByName('ToElectionName')[0].value;


    if(FromServerName === ''){
        alert("Please select Server from you want to move data!");
    }else if(FromElectionName === ''){
        alert("Please select Election from you want to move data");
    }else if(ToServerName === ''){
        alert("Please select Server to you want to move data");
    }else if(ToElectionName === ''){
        alert("Please select Election to you want to move data");
    }else if(FromServerName == ToServerName){
        alert("Please select two different servers");
    }else if (confirm("Are you Sure you want to move data of selected database?") == true) 
    {
        $.ajax({

            type: "POST",
            url: 'action/moveDBDataToAnotherServer.php',
            data: { 
                FromServerName: FromServerName,
                ToServerName: ToServerName,
                FromElectionName: FromElectionName,
                ToElectionName: ToElectionName
            },
            beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                // $('#saveKaryakartaMasterFormDatabtn').attr("disabled", true);
                // $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                // alert('in success');
                // console.log(dataResult);
                // alert(dataResult);

                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            location.reload(true);
                        }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                        }).delay(4000).fadeOut("fast");
                }
            }
            // ,
            // complete: function() {
            //         $('#saveKaryakartaMasterFormDatabtn').attr("disabled", false);
            //         $('html').removeClass("ajaxLoading");
            //     }
        });
    }
}




// Transfer Data FROm One Screen ------------------------------------------------------------------------------------------------------

function AssignedExecutiveToSiteTransferFunction() {
 
    var Date = document.getElementsByName('Date')[0].value;

    const originalDateStr = Date;
    const [year, month, day] = originalDateStr.split('-');
    const formattedDate = `${day}-${month}-${year}`;

    if(Date === ''){
        alert("Please select Date!");
    }else{
        var confirmMSG = confirm("Are you sure you want to transfer data from " + formattedDate + " to Next Date ?");
        if(confirmMSG){
            $.ajax({
                type: "POST",
                url: 'action/assignedExecutiveToSiteTransferSingle.php',
                data: { 
                    Date: Date
                },
                beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#UpdateButton').attr("disabled", true);
                    $('html').addClass("ajaxLoading");
                    document.getElementById("spinnerLoader2").style.display = "block";

                    $("#waitMSG").html("It will take some time. Please Wait!!!")
                        .hide().fadeIn(1000, function() {
                            $("waitMSG").append("");
                                
                    }).delay(3000);
                },
                success: function(dataResult) {
                    $("#waitMSG").hide();
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        $("#msgsuccess").html(dataResult.msg)
                            .hide().fadeIn(1000, function() {
                                $("msgsuccess").append("");
                                    location.reload(true);
                            }).delay(3000).fadeOut("fast");
                    }else{
                        $("#msgfailed").html(dataResult.msg)
                            .hide().fadeIn(800, function() {
                                $("msgfailed").append("");
                                    location.reload(true);
                            }).delay(4000).fadeOut("fast");
                    }
                },
                complete: function() {
                    $('#UpdateButton').attr("disabled", false);
                    $('html').removeClass("ajaxLoading");
                    document.getElementById("spinnerLoader2").style.display = "none";
                }
            });
        }
    }
}



function getSiteSupervisorWiseDetail(Date,Site,SupervisorName,totalexecutives,ElectionName){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SiteSupervisorWiseDetail');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#MODAL_VIEW').modal('show');
            $('.select2').select2();
        }
    }
    // alert(Status);
    //    var div ='profile';
    var queryString = "?Site="+Site+"&Date="+Date+"&SupervisorName="+SupervisorName+"&totalexecutives="+totalexecutives+"&ElectionName="+ElectionName;
    // alert(Site);
    ajaxRequest.open("POST", "setModalDetailedViewAssignedExecutives.php" + queryString, true);
    ajaxRequest.send(null);
}   



function getExecutiveCdsToTransfer() {
    var input = document.getElementsByClassName("checkboxALL");
    var selected = 0;
    var chkAllCDS = "";
    for (var i = 0; i < input.length; i++) {
      if (input[i].checked) {
        var splits = input[i].value;
        
        var CD_Val = '';
        CD_Val += "" + splits + "";
        chkAllCDS += splits + ",";
        selected++;
      }
    }
    if(chkAllCDS != ""){
        $("#ShowTransfer").show();
    }else{
        $("#ShowTransfer").hide();
    }
    document.getElementsByName("SelectedExecutiveCds")[0].value = "" + chkAllCDS;
}

function getExecutiveCdsToTransferALL(ele) {
    var checkboxes = document.getElementsByClassName('checkboxALL');
    if (ele.checked) {
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else {
        for (var i = 0; i < checkboxes.length; i++) {
            console.log(i)
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }
    getExecutiveCdsToTransfer();
}


function ExecutiveTransferToNewSite() {
 
    var Date = document.getElementsByName('Date')[0].value;
    var SelectedExecutiveCds = document.getElementsByName('SelectedExecutiveCds')[0].value;
    var OldSite = document.getElementsByName('Site')[0].value;
    var SiteName = document.getElementsByName('SiteNameNew')[0].value;
    var Supervisor = document.getElementsByName('SupervisorName')[0].value;

    if(SiteName === ''){
        alert("Please select Site!");
    }else if(SelectedExecutiveCds === ''){
        alert("Please select Executives!");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/assignedExecutiveToNewSiteTransfer.php',
            data: {
                Date: Date,
                SelectedExecutiveCds: SelectedExecutiveCds,
                OldSite: OldSite,
                SiteName: SiteName,
                Supervisor: Supervisor
            },
            beforeSend: function() {
                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#UpdateButton').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "block";
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccessTR").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccessTR").append("");
                                location.reload(true);
                                //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailedTR").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailedTR").append("");
                                //location.reload(true);
                                //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(4000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdateButton').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "none";
            }
        });
    }
}

function uncheckAllCheckboxes() {
    // Get all checkboxes using a CSS selector
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    // Iterate through each checkbox and uncheck it
    checkboxes.forEach(checkbox => {
      checkbox.checked = false;
    });
    document.getElementById("SelectedExecutiveCds").innerHTML = "0";
  }
  
  
//   Remove Executives 

function ExecutiveRemoveFromCurrentSite() {
 
    var Date = document.getElementsByName('Date')[0].value;
    var SelectedExecutiveCds = document.getElementsByName('SelectedExecutiveCds')[0].value;
    var OldSite = document.getElementsByName('Site')[0].value;
    var Supervisor = document.getElementsByName('SupervisorName')[0].value;

    if(SelectedExecutiveCds === ''){
        alert("Please select Executives!");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/assignedExecutiveRemoveFromCurrentSite.php',
            data: {
                Date: Date,
                SelectedExecutiveCds: SelectedExecutiveCds,
                OldSite: OldSite,
                Supervisor: Supervisor
            },
            beforeSend: function() {
                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#UpdateButton').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "block";
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccessTR").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccessTR").append("");
                                location.reload(true);
                                //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailedTR").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailedTR").append("");
                                // location.reload(true);
                                $( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdateButton').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "none";
            }
        });
    }
}
// Transfer Data FROm One Screen ------------------------------------------------------------------------------------------------------

// Attendance ----------------------------------------------------------------
    function setDateInSessionForAttendance(Date) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                // ajaxDisplay.innerHTML = ajaxRequest.responseText;
            location.reload(true);
                document.getElementById("spinnerLoader2Attendance").style.display = "none";
                // $(document).ready(function () {
                //     $('#AssignExecutiveToSiteTableID').DataTable({
                //         "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                //     });
                // });
                $('.select2').select2();
            }
        }
        
        if (Date === '') {
            alert("Please Select Date!");
        } else {
            document.getElementById("spinnerLoader2Attendance").style.display = "block";
            var queryString = "?Date="+Date;
            ajaxRequest.open("POST", "setElectionNameinsessionAttendanceToSite.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    }
    function SetDataSourceForKarykarta(DataS) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    location.reload(true);
                    $('#spinnerLoaderKarykarta').show(); 
                    // window.location.href='index.php?p=MapClientDashboard';
                }
            }
        
        if (DataS === '') {
            alert("Please Select Data Source!");
        } else {
            // alert(SiteName);
            // alert(ElectionName);
            var queryString = "?DataS="+DataS;
            ajaxRequest.open("POST", "setDataSourceForKarykartaInSession.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    
    }
    
    function SetDesignationForKarykarta(Designation) {
        var ajaxRequest; // The variable that makes Ajax possible!
    
        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }
    
        ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    location.reload(true);
                    $('#spinnerLoaderKarykarta').show();
                    // window.location.href='index.php?p=MapClientDashboard';
                }
            }
        
        if (Designation === '') {
            alert("Please Select Data Designation!");
        } else {
            // alert(SiteName);
            // alert(ElectionName);
            var queryString = "?Designation="+Designation;
            ajaxRequest.open("POST", "setDataSourceForKarykartaInSession.php" + queryString, true);
            ajaxRequest.send(null);
    
        }
    
    }


        function setDesignationInSessionForAttendance(designation) {
            var ajaxRequest; // The variable that makes Ajax possible!
        
            try {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
            } catch (e) {
                // Internet Explorer Browsers
                try {
                    ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {
                        // Something went wrong
                        alert("Your browser broke!");
                        return false;
                    }
                }
            }
        
            ajaxRequest.onreadystatechange = function() {
                if (ajaxRequest.readyState == 4) {
                    // var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
                    // ajaxDisplay.innerHTML = ajaxRequest.responseText;
                    location.reload(true);
                    document.getElementById("spinnerLoader2Attendance").style.display = "none";
                    $('.select2').select2();
                }
            }
            // alert(designation);
            if (designation === '') {
                alert("Please Select designation!");
            } else {
                document.getElementById("spinnerLoader2Attendance").style.display = "block";
                var queryString = "?designation="+designation;
                ajaxRequest.open("POST", "setElectionNameinsessionAttendanceToSite.php" + queryString, true);
                ajaxRequest.send(null);
        
            }
        }


// Attendance ----------------------------------------------------------------


// Salary Process -------------------------------------------------------------------

function setMonthInSessionFromSalaryProcess(Month){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Month === '') {
        alert("Please Select Month!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Month="+Month;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function setYearInSessionFromSalaryProcess(Year){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Year === '') {
        alert("Please Select Year!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Year="+Year;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function setDesignationInSessionFromSalaryProcess(Designation){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Designation === '') {
        alert("Please Select Designation!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Designation="+Designation;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}

function setPaymentStatusInSessionFromSalaryProcess(PaymentStatus){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (PaymentStatus === '') {
        alert("Please Select Payment Status!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?PaymentStatus="+PaymentStatus;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function setReferenceInSessionFromSalaryProcess(Reference){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Reference === '') {
        alert("Please Select Reference!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Reference="+Reference;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function searchedNameCdOrMobileInSessionFromSalaryProcess(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    var ExecutiveCdOrNameOrMobile = document.getElementsByName('ExecutiveCdOrNameOrMobile')[0].value;

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (ExecutiveCdOrNameOrMobile === '') {
        alert("Please Enter ExecutiveCd / Name / Mobile !!!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?ExecutiveCdOrNameOrMobile="+ExecutiveCdOrNameOrMobile;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function CorporationNameInSessionFromSalaryProcess(Electionname){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }


    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $(document).ready(function () {
                $('#SalaryProcessUpdateTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
                
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
            $('.select2').select2();
        }
    }
    
    if (Electionname === '') {
        alert("Please Enter Corporation !!!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Electionname="+Electionname;
        ajaxRequest.open("POST", "setSessionValuesFromSalaryProcess.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


function getModalForEditSalaryProcessData(SalaryP_ID,Month,Year){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SiteSupervisorWiseDetail');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            $('#MODAL_VIEW').modal('show');
            // $('.select2').select2();
        }
    }
    document.getElementById("spinnerLoader2").style.display = "block";
    var queryString = "?SalaryP_ID="+SalaryP_ID+"&Month="+Month+"&Year="+Year;
    ajaxRequest.open("POST", "setModalEditformForSalaryProcess.php" + queryString, true);
    ajaxRequest.send(null);
}   


function UpdatePaymentDetailes() {
 
    var PayStatus = document.getElementsByName('PayStatus')[0].value;
    var SelectedExecutives = document.getElementsByName('SelectedExecutives')[0].value;
    var Remark = document.getElementsByName('Remark')[0].value;
    var TableName = document.getElementsByName('TableName')[0].value;

    if(SelectedExecutives === ''){
        alert("Please Select Executives!");
    }else if(PayStatus === ''){
        alert("Please Select Status!");
    }else if(PayStatus !== '' && PayStatus === "Hold" && Remark === ""){
        alert("Please Enter Remark!");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/updateProcessedSalaryPaymentStatus.php',
            data: {
                PayStatus: PayStatus,
                SelectedExecutives: SelectedExecutives,
                Remark: Remark,
                TableName: TableName
            },
            beforeSend: function() {
                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#UpdatePaymentStatus').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "block";
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                                location.reload(true);
                                //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                                // location.reload(true);
                                // $( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdatePaymentStatus').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "none";
            }
        });
    }
}

function setRemarkinputbyPayStatus(value){
    if(value === "Hold"){
        document.getElementById("RemarkDiv").style.display = "block";
    }else{
        document.getElementById("RemarkDiv").style.display = "none";
    }
}

function processSalary(Executive_Cd,Month,Year,totalDays) {
 
    if(Month === ''){
        alert("Please Select Month!");
    }else if(Year === ''){
        alert("Please Select Year!");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/processSalary.php',
            data: {
                    Executive_Cd: Executive_Cd,
                    Month: Month,
                    Year: Year,
                    totalDays: totalDays
            },
            beforeSend: function() {
                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#ProcessButton').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "block";
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                                location.reload(true);
                    }).delay(3000).fadeOut("fast");
                }else if(dataResult.statusCode == 204){
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                                // location.reload(true);
                    }).delay(3000).fadeOut("fast");
                }else if(dataResult.statusCode == 203){
                    var processAgain = confirm(dataResult.msg);
                    if(processAgain){
                        processSalaryAgain(Executive_Cd,Month,Year,totalDays);
                    }else{
                        $('#ProcessButton').attr("disabled", false);
                        $('html').removeClass("ajaxLoading");
                        document.getElementById("spinnerLoader2").style.display = "none";
                        exit();
                    }
                }
            },
            complete: function() {
                $('#ProcessButton').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2").style.display = "none";
            }
        });
    }
}


function processSalaryAgain(Executive_Cd,Month,Year,totalDays) {
    var process = "again";
    $.ajax({
        type: "POST",
        url: 'action/processSalary.php',
        data: {
                Executive_Cd: Executive_Cd,
                Month: Month,
                Year: Year,
                totalDays: totalDays,
                process: process
        },
        beforeSend: function() {
            // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
            $('#ProcessButton').attr("disabled", true);
            $('html').addClass("ajaxLoading");
            document.getElementById("spinnerLoader2").style.display = "block";
        },
        success: function(dataResult) {
            var dataResult = JSON.parse(dataResult);
            if(dataResult.statusCode == 200){
                $("#msgsuccess").html(dataResult.msg)
                    .hide().fadeIn(1000, function() {
                        $("msgsuccess").append("");
                            location.reload(true);
                }).delay(3000).fadeOut("fast");
            }else if(dataResult.statusCode == 204){
                $("#msgfailed").html(dataResult.msg)
                    .hide().fadeIn(800, function() {
                        $("msgfailed").append("");
                            // location.reload(true);
                }).delay(3000).fadeOut("fast");
            }else if(dataResult.statusCode == 203){
                var processAgain = confirm(dataResult.msg);
                if(processAgain){
                    alert('Process Again');
                }else{
                    exit();
                }
            }
        },
        complete: function() {
            $('#ProcessButton').attr("disabled", false);
            $('html').removeClass("ajaxLoading");
            document.getElementById("spinnerLoader2").style.display = "none";
        }
    });
}

function CalculateSalary(){
    var Advance = parseFloat(document.getElementsByName('Advance')[0].value);
    var Deduction = parseFloat(document.getElementsByName('Deduction')[0].value);
    var Incentive = parseFloat(document.getElementsByName('Incentives')[0].value);
    // var PayableAmt = parseFloat(document.getElementsByName('PayableAmt')[0].value);
    var TotalSalary = parseFloat(document.getElementsByName('TotalSalary')[0].value);
    var AbsentAndHalfDays = parseFloat(document.getElementsByName('AbsentAndHalfDays')[0].value);
    
    if(Advance === ""){
        Advance = 0;
        document.getElementsByName('Advance')[0].value = 0;
    }
    
    if(Deduction === ""){
        Deduction = 0;
        document.getElementsByName('Deduction')[0].value = 0;
    }

    if(Incentive === ""){
        Incentive = 0;
        document.getElementsByName('Incentives')[0].value = 0;
    }
    var AdvanceAmtElement = document.getElementById('AdvanceAmt');
    AdvanceAmtElement.innerText = Advance;
    var DeductionAmtElement = document.getElementById('DeductionAmt');
    DeductionAmtElement.innerText = Deduction;
    var IncentiveAmtElement = document.getElementById('IncentiveAmt');
    IncentiveAmtElement.innerText = Incentive;

    var Deduct = Advance+Deduction;

    var SalaryAfterDeductionAbsentAndHD = TotalSalary - AbsentAndHalfDays;
    SalaryAfterDeductionAbsentAndHD = SalaryAfterDeductionAbsentAndHD - Deduct;
    var AfterAllPayable = SalaryAfterDeductionAbsentAndHD + Incentive;
    // console.log(AfterAllPayable);
    if(Advance !== "" && Deduction !== "" && Incentive !== ""){
        document.getElementsByName('PayableAmtChange')[0].value = AfterAllPayable;
    }
}

function isNumberKey(evt, obj) {
    
    var charCode = (evt.which) ? evt.which : event.keyCode
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains)
        if (charCode == 46) return false;
    if (charCode == 46) return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}


function updateProcessedSalary(TableName, SalaryP_ID) {
    
    var Advance = document.getElementsByName('Advance')[0].value;
    var Deduction = document.getElementsByName('Deduction')[0].value;
    var Incentives = document.getElementsByName('Incentives')[0].value;
    var PayableAmtChange = document.getElementsByName('PayableAmtChange')[0].value;
    var Remark = document.getElementsByName('RemarkDeduction')[0].value;

    
    if(PayableAmtChange === ''){
        alert("Payable Amount can not be Zero !");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/updateProcessedSalary.php',
            data: {
                    TableName : TableName,
                    SalaryP_ID : SalaryP_ID,
                    Advance: Advance,
                    Deduction: Deduction,
                    Incentives: Incentives,
                    PayableAmtChange: PayableAmtChange,
                    Remark: Remark
            },
            beforeSend: function() {
                $('#UpdateButtonProcessedSalary').attr("disabled", true);
                $('html').addClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "block";
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccessSalaryProcessed").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccessSalaryProcessed").append("");
                                location.reload(true);
                    }).delay(3000).fadeOut("fast");
                }else if(dataResult.statusCode == 204){
                    $("#msgfailedSalaryProcessed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailedSalaryProcessed").append("");
                                // location.reload(true);
                    }).delay(3000).fadeOut("fast");
                }
            },
            complete: function() {
                $('#UpdateButtonProcessedSalary').attr("disabled", false);
                $('html').removeClass("ajaxLoading");
                document.getElementById("spinnerLoader2Modal").style.display = "none";
            }
        });
    }
}

function showAndHideInnerDiv(SrNo) {
    
    var div = document.getElementById("InnerDiv_"+SrNo);
    div.colSpan = 10;

    if (div.style.display === "none") {
      div.style.display = "block";
    } else {
      div.style.display = "none";
    }
}

function SurveySalaryProcessTab(TabName, Month, Year){
    //ULBCondJoin2, ULBCondJoin, searchCondition3, DesignationCond, ReferenceCond, PaymentStatusCond, UBLwhereCond, searchCondition2,
    
    var ULBCondJoin2query  = "";
    var searchCondition2query = "";
    var UBLwhereCondquery = "";
    var PaymentStatusCondquery = "";
    var ReferenceCondquery = "";
    var DesignationCondquery = "";
    var searchCondition3query  = "";
    var ULBCondJoinquery = "";

    // if(ULBCondJoin2 !== ""){
    //     ULBCondJoin2query = "&ULBCondJoin2="+ULBCondJoin2;
    // }
    // if(ULBCondJoin !== ""){
    //     ULBCondJoinquery = "&ULBCondJoin="+ULBCondJoin;
    // }
    // if(searchCondition3 !== ""){
    //     searchCondition3query = "&searchCondition3="+searchCondition3;
    // }
    // if(DesignationCond !== ""){
    //     DesignationCondquery = "&DesignationCond="+DesignationCond;
    // }
    // if(ReferenceCond !== ""){
    //     ReferenceCondquery = "&ReferenceCond="+ReferenceCond;
    // }
    // if(PaymentStatusCond !== ""){
    //     PaymentStatusCondquery = "&PaymentStatusCond="+PaymentStatusCond;
    // }
    // if(UBLwhereCond !== ""){
    //     UBLwhereCondquery = "&UBLwhereCond="+UBLwhereCond;
    // }
    // if(searchCondition2 !== ""){
    //     searchCondition2query = "&searchCondition2="+searchCondition2;
    // }
    
    var ajaxRequest; // The variable that makes Ajax possible!
    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    
    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            document.getElementById("spinnerLoader2Tab").style.display = "none";
            var ajaxDisplay = document.getElementById('ReferenceWiseReport');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#ReferenceWiseReport').show();
                    
            $(document).ready(function () {
                $('#referenceWiseTable').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
        }
    }
    document.getElementById("spinnerLoader2Tab").style.display = "block";
    var queryString = "?TabName="+TabName+"&Month="+Month+"&Year="+Year;
    ajaxRequest.open("POST", "setTabDIVsalaryProcess.php" + queryString, true);
    // + ULBCondJoin2query + searchCondition2query + UBLwhereCondquery + PaymentStatusCondquery + ReferenceCondquery + DesignationCondquery + searchCondition3query + ULBCondJoinquery
    ajaxRequest.send(null);
}
// Salary Process -------------------------------------------------------------------

//QcTab-------------------------------------------------------------------

function GetSiteDetailQc(Site){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('QcSiteWiseView');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            $('#SiteQcdata').show();
            $('html, body').animate({
                scrollTop: $("#QcSiteWiseView").offset().top
            }, 500); 
            $('.select2').select2();
            $(document).ready(function () {
                $('#SiteWiseSociety').DataTable({
                  "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
                });
            });
        }
    }
    // alert(Status);
    //    var div ='profile';
    var queryString = "?Site="+Site;
    // alert(Site);
    ajaxRequest.open("POST", "setSiteWiseSocietyQcDataInSession.php" + queryString, true);
    ajaxRequest.send(null);
}   
//QcTab-------------------------------------------------------------------



//Executive And Mobile Wise ---------------------------------------------------------

    function getExecutiveWiseDataInForm(ExecutiveName,MobileNo,DBName,FamilyNos,datacnt,flag){

        var ajaxRequest; // The variable that makes Ajax possible!

        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }

        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // $( "#AttendenceTable" ).load(window.location.href + "#AttendenceTable" );
                var ajaxDisplay = document.getElementById('ExecutiveAndMobileWiseModal');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                $('#loading').show();
                $('#ExecutiveMobileDiv').show();
                 $(document).ready(function() {
                      "use strict"
                      $('#OnClickModalView').DataTable({
                          "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                      });
                  });
                $('html, body').animate({
                    scrollTop: $("#ExecutiveAndMobileWiseModal").offset().top
                }, 500);
                
            
            }
        }
    var queryString = "?ExecutiveName="+ExecutiveName+"&MobileNo="+MobileNo+"&DBName="+DBName+"&FamilyNos="+FamilyNos+"&datacnt="+datacnt+"&flag="+flag;
        ajaxRequest.open("POST","ExecutiveAndMobileWiseModalView.php" + queryString, true);
        ajaxRequest.send(null);
    }



    function getMobileNoWiseDataInForm(MobileNo,ExecutiveName,DBName,FamilyNos,datacnt,flag){

        var ajaxRequest; // The variable that makes Ajax possible!

        try {
            // Opera 8.0+, Firefox, Safari
            ajaxRequest = new XMLHttpRequest();
        } catch (e) {
            // Internet Explorer Browsers
            try {
                ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    // Something went wrong
                    alert("Your browser broke!");
                    return false;
                }
            }
        }

        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                // $( "#AttendenceTable" ).load(window.location.href + "#AttendenceTable" );
                var ajaxDisplay = document.getElementById('ExecutiveAndMobileWiseModal');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                
                    $(document).ready(function() {
                        "use strict"
                        $('#OnClickModalView').DataTable({
                          "lengthMenu": [ [20, 40, 50,-1], [20, 40, 50,"All"] ]
                        });
                    });
                  $('#ExecutiveMobileDiv').show();
                  $('html, body').animate({
                      scrollTop: $("#ExecutiveAndMobileWiseModal").offset().top
                  }, 500);
            
            }
        }
    var queryString = "?MobileNo="+MobileNo+"&ExecutiveName="+ExecutiveName+"&DBName="+DBName+"&FamilyNos="+FamilyNos+"&datacnt="+datacnt+"&flag="+flag;
        ajaxRequest.open("POST","ExecutiveAndMobileWiseModalView.php" + queryString, true);
        ajaxRequest.send(null);
    }


    
function setPaginationNoInSession(pageNo) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
                // getExecutiveWiseDataInForm(ExecutiveName,MobileNo,DBName,FamilyNos,datacnt,flag);
            }
        }
    
    if (pageNo === '') {
        alert("Please Select PageNo!");
    } else {
        var queryString = "?pageNo="+pageNo;
        ajaxRequest.open("POST", "setPaginationNoInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}


function setPaginationNoInSessionModal(pageNo) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('ExecutiveAndMobileWiseModal');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                
                 
                  $('#ExecutiveMobileDiv').show();
                  $('html, body').animate({
                      scrollTop: $("#ExecutiveAndMobileWiseModal").offset().top
                  }, 500);
              
            }
        }
    
    if (pageNo === '') {
        alert("Please Select PageNo!");
    } else {
        var queryString = "?pageNo="+pageNo;
        ajaxRequest.open("POST", "setPaginationNoInSessionModal.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
//Executive And Mobile Wise ---------------------------------------------------------
function getMobileWiseData(){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                var ajaxDisplay = document.getElementById('MobileWise');
                ajaxDisplay.innerHTML = ajaxRequest.responseText;
                  $('#Mobiledata').show();
            document.getElementById("TabLoading").style.display = "none";
            }
        }
    
        document.getElementById("TabLoading").style.display = "Block";
        var queryString = '';
        ajaxRequest.open("POST", "MobileNoWiseData.php" + queryString, true);
        ajaxRequest.send(null);
}



// Attendance Report ---------------------------------------------------------
function setMonthInSessionFromAttedanceReport(Month){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            location.reload(true);
            // $(document).ready(function () {
            //     $('#AttendanceReportTableID').DataTable({
            //       "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
            //     });
            // });
            // $(document).ready(function() {
            //     var dataTable = $('#AttendanceReportTableID').DataTable();
            //     $('#column1SearchName').on('keyup', function() {
            //         dataTable.column(1).search(this.value).draw();
            //     });
            //     $('#column2SearchULB').on('keyup', function() {
            //         dataTable.column(2).search(this.value).draw();
            //     });
            // });
            $('.select2').select2();
        }
    }
    
    if (Month === '') {
        alert("Please Select Month!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Month="+Month;
        ajaxRequest.open("POST", "setSessionValuesFromAttendanceReport.php" + queryString, true);
        ajaxRequest.send(null);

    }
}



function setYearInSessionFromAttendanceReport(Year){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            location.reload(true);
            // $(document).ready(function () {
            //     $('#AttendanceReportTableID').DataTable({
            //       "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
            //     });
            // });
            // $(document).ready(function() {
            //     var dataTable = $('#AttendanceReportTableID').DataTable();
            //     $('#column1SearchName').on('keyup', function() {
            //         dataTable.column(1).search(this.value).draw();
            //     });
            //     $('#column2SearchULB').on('keyup', function() {
            //         dataTable.column(2).search(this.value).draw();
            //     });
            // });
            $('.select2').select2();
        }
    }
    
    if (Year === '') {
        alert("Please Select Year!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Year="+Year;
        ajaxRequest.open("POST", "setSessionValuesFromAttendanceReport.php" + queryString, true);
        ajaxRequest.send(null);

    }
}
// Attendance Report ---------------------------------------------------------

// Allow Only Marathi Text -----------------------------------------------
function validateMarathiTextPocketName(inputField) {
    /*const marathiPattern = /^[ऀ-ॿ\s]+$/; // Range of Marathi Unicode characters and space

    if (marathiPattern.test(inputField.value)) {
        document.getElementById('validationMessage').textContent = '';
    } else {
        document.getElementById('validationMessage').textContent = 'Only Marathi text is allowed';
        // inputField.value = ''; // Clear the input field if it contains invalid characters
    }*/
}

function validateMarathiTextAreaName(inputField) {
    /*const marathiPattern = /^[ऀ-ॿ\s]+$/; // Range of Marathi Unicode characters and space

    if (marathiPattern.test(inputField.value)) {
        document.getElementById('validationMessageArea').textContent = '';
    } else {
        document.getElementById('validationMessageArea').textContent = 'Only Marathi text is allowed';
        // inputField.value = ''; // Clear the input field if it contains invalid characters
    }*/
}
// Allow Only Marathi Text -----------------------------------------------


// Active InActive Tab of Attendance Report ---------------------------------
function setActiveInActiveTabSessionFromAttedanceReport(Tab){
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            document.getElementById("spinnerLoader2").style.display = "none";
            location.reload(true);
            $('.select2').select2();
        }
    }
    
    if (Tab === '') {
        alert("Please Select Tab!");
    } else {
        document.getElementById("spinnerLoader2").style.display = "block";
        var queryString = "?Tab="+Tab;
        ajaxRequest.open("POST", "setSessionValuesFromAttendanceReport.php" + queryString, true);
        ajaxRequest.send(null);

    }
}


// ---------------------------Society Transfer---------------------------------
function SetSiteNameforTransfer(SiteName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (SiteName === '') {
        alert("Please Select Site!");
    } else {
        var queryString = "?SiteName="+SiteName;
        ajaxRequest.open("POST", "setSiteForSocietyTransferInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function SetfromSociety(SocietyCd) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (SocietyCd === '') {
        alert("Please Select Societyy!");
    } else {
        var queryString = "?SocietyCd="+SocietyCd;
        ajaxRequest.open("POST", "setSiteForSocietyTransferInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function SetToSociety(SocietyCd) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
            }
        }
    
    if (SocietyCd === '') {
        alert("Please Select Societyy!");
    } else {
        var queryString = "?ToSocietyCd="+SocietyCd;
        ajaxRequest.open("POST", "setSiteForSocietyTransferInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
function SetUpdatedDate(UpdDate) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
   
        var queryString = "?UpdatedDate="+UpdDate;
        ajaxRequest.open("POST", "setSiteForSocietyTransferInSession.php" + queryString, true);
        ajaxRequest.send(null);
}
function SetUpdatedBy(User) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
   
        var queryString = "?UpdatedBy="+User;
        ajaxRequest.open("POST", "setSiteForSocietyTransferInSession.php" + queryString, true);
        ajaxRequest.send(null);
}

function TransferData(DBName) {
 
    var Site = document.getElementsByName('FromSite')[0].value;
    var FromSociety = document.getElementsByName('FromSociety')[0].value;
    var ToSociety = document.getElementsByName('ToSociety')[0].value;
    var UpdatedDate = document.getElementsByName('UpdateDate')[0].value;
    var UpdatedBy = document.getElementsByName('UpdatedBy')[0].value;
    var VoterCds = document.getElementsByName('SocietyCds')[0].value;
    
    
    if(Site === ''){
        alert("Please select Site!");
    }else if(FromSociety === ''){
        alert("Please select From Societyy!");
    }else if(ToSociety === ''){
        alert("Please select To Societyy!");
    }else if(VoterCds === ''){
        alert("Please select To Records!");
    }else{
        var confirmMSG = confirm("Are you sure you want to transfer data?");
        if(confirmMSG){
            $.ajax({
                type: "POST",
                url: 'action/TransferSocietyData.php',
                data: { 
                    Site: Site,
                    FromSociety: FromSociety,
                    ToSociety: ToSociety,
                    UpdatedDate: UpdatedDate,
                    UpdatedBy: UpdatedBy,
                    DBName: DBName,
                    VoterCds: VoterCds

                },
                beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#UpdateButton').attr("disabled", true);
                    document.getElementById("Loader2").style.display = "block";
                    $('html').addClass("ajaxLoading");

                    $("#waitMSG").html("It will take some time. Please Wait!!!")
                        .hide().fadeIn(3000, function() {
                            $("waitMSG").append("");
                                
                    }).delay(3000);
                },
                success: function(dataResult) {
                    $("#waitMSG").hide();
                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        $("#msgsuccess").html(dataResult.msg)
                            .hide().fadeIn(3000, function() {
                                $("msgsuccess").append("");
                                    location.reload(true);
                            }).delay(3000).fadeOut("fast");
                    }else{
                        $("#msgfailed").html(dataResult.msg)
                            .hide().fadeIn(800, function() {
                                $("msgfailed").append("");
                                    location.reload(true);
                            }).delay(4000).fadeOut("fast");
                    }
                },
                complete: function() {
                    document.getElementById("Loader2").style.display = "none";
                    $('html').removeClass("ajaxLoading");
                    
                }
            });
        }
    }
}



function getSurveyQCNonVoterFamilyInSession(FamilyNo,Ac_No,Voter_Cd){

    var FirstName = document.getElementsByName('FirstName')[0].value;
    var MiddleName = document.getElementsByName('MiddleName')[0].value;
    var LastName = document.getElementsByName('LastName')[0].value;
    var FullName = document.getElementsByName('FullName')[0].value;
    var IdCard_No = document.getElementsByName('IdCard_No')[0].value;
    // var List_No = document.getElementsByName('List_No')[0].value;
    var fuzzThreshold = document.getElementsByName('fuzzThreshold')[0].value;

    var AdvanceSearch = document.getElementsByName('AdvanceSearch')[0].value;

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('SurveyUtilityKMLDashboardMainScreen');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            surveyQCAdvanceSearchNew()
            $('#spinnerLoader3').hide();
        }
    }
    

    $('#spinnerLoader3').show();
    var queryString = "?FirstName="+FirstName+"&MiddleName="+MiddleName+"&LastName="+LastName+"&FullName="+FullName+"&AdvanceSearch="+AdvanceSearch+"&FamilyNo="+FamilyNo+"&Ac_No="+Ac_No+"&Voter_Cd="+Voter_Cd+"&IdCard_No="+IdCard_No+"&fuzzThreshold="+fuzzThreshold;
    // alert(queryString);
    ajaxRequest.open("POST", "setSurveyQCNonVoterFamilyInSession.php" + queryString, true);
    ajaxRequest.send(null);
}


var debounceTimerNew; 
function surveyQCAdvanceSearchNew(){

    var FirstName = document.getElementsByName('FirstName')[0].value;
    var MiddleName = document.getElementsByName('MiddleName')[0].value;
    var LastName = document.getElementsByName('LastName')[0].value;
    var fullName = document.getElementsByName('AdvanceSearch')[0].value;
    var DBName = document.getElementsByName('DBName')[0].value;
    var Ac_No = document.getElementsByName('GetAc_No')[0].value;
    var IdCard_No = document.getElementsByName('IdCard_No')[0].value;
    var fuzzThreshold = document.getElementsByName('fuzzThreshold')[0].value;

    if(DBName != ""){
        // alert(fullName);
        
        clearTimeout(debounceTimerNew); // Clear any existing timer

        debounceTimerNew = setTimeout(function() {
        
            $.ajax({
                type: "POST",
                url: 'getSurveyQCAdvanceSearchResult.php',
                data: { 
                    fullName: fullName,
                    FirstName: FirstName,
                    MiddleName: MiddleName,
                    LastName: LastName,
                    IdCard_No: IdCard_No,
                    fuzzThreshold: fuzzThreshold,
                    DBName: DBName,
                    Ac_No: Ac_No
                },
                beforeSend: function() { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                    $('#spinnerLoader2').show();
                },
                success: function(dataResult) {
                    // alert('in success');
                    // console.log(dataResult);
                    // alert(dataResult);

                    var dataResult = JSON.parse(dataResult);
                    if(dataResult.statusCode == 200){
                        var tbody = document.getElementById("tbodydiv");
                        tbody.innerHTML = dataResult.msg;
                    }else{
                        alert(dataResult.msg);
                    }
                },
                complete: function() {
                    $('#spinnerLoader2').hide();
                }
            });
        }, 500);
    }
}

// -----------------------------Issue Enrtry----------------------------------------
function getSocietyIssue() {
 
    var Ac_No = document.getElementsByName('Ac_No')[0].value;
    var Ward_No = document.getElementsByName('Ward_No')[0].value;
    var SocietyName = document.getElementsByName('SocietyName')[0].value;
    var Corporator = document.getElementsByName('Corporator')[0].value;
    var Rooms = document.getElementsByName('Rooms')[0].value;
    var Pocket = document.getElementsByName('Pocket')[0].value;
    var Chairman_Name = document.getElementsByName('Chairman_Name')[0].value;
    var Chairman_No = document.getElementsByName('Chairman_No')[0].value;
    var Secretory_Name = document.getElementsByName('Secretory_Name')[0].value;
    var Secretory_No = document.getElementsByName('Secretory_No')[0].value;
    var Issue = document.getElementsByName('Issue')[0].value;
    var IssueSolve = document.getElementsByName('IssueSolve')[0].value;
    var SocietyCd = document.getElementsByName('SctCds')[0].value;
    var SctJsonCds = document.getElementsByName('SctJsonCds')[0].value;
    var action = document.getElementsByName('action')[0].value;
    // alert(IssueSolve);
// die();
    if(Ac_No === ''){
        alert("Please Select Assembly!");
    }else if(Issue === ''){
        alert("Please Select Issue!");
    }else if(SocietyName === '' ){
        alert("Please Enter Society Name!");
    }else{
        $.ajax({
            type: "POST",
            url: 'action/UpdateSocietyIssue.php',
            data: {
                Ac_No: Ac_No,
                Ward_No: Ward_No,
                SocietyName: SocietyName,
                Corporator: Corporator,
                Rooms: Rooms,
                Pocket: Pocket,
                Chairman_Name: Chairman_Name,
                Chairman_No: Chairman_No,
                Secretory_Name: Secretory_Name,
                Secretory_No: Secretory_No,
                Issue: Issue,
                IssueSolve: IssueSolve,
                SocietyCd: SocietyCd,
                SctJsonCds: SctJsonCds,
                action: action
            },
            beforeSend: function() {
                // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('html').addClass("ajaxLoading");
            },
            success: function(dataResult) {
                var dataResult = JSON.parse(dataResult);
                if(dataResult.statusCode == 200){
                    $("#msgsuccess").html(dataResult.msg)
                        .hide().fadeIn(1000, function() {
                            $("msgsuccess").append("");
                            window.location.href='index.php?p=IssueEntry';
                                //$( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }else{
                    $("#msgfailed").html(dataResult.msg)
                        .hide().fadeIn(800, function() {
                            $("msgfailed").append("");
                            window.location.href='index.php?p=IssueEntry';
                                // $( "#ModalSection" ).load(window.location.href + " #ModalSection" );
                    }).delay(3000).fadeOut("fast");
                }
            },
            complete: function() {
            }
        });
    }
}


function getsearchSocieties(){

    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState == 4) {
            $( "#AttendenceTable" ).load(window.location.href + "#SocietyList" );
            var ajaxDisplay = document.getElementById('SocietyList');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            
        
        }
    }

    if (ajaxRequest.readyState == 4) {
        var ajaxDisplay = document.getElementById('SocietyList');
        ajaxDisplay.innerHTML = ajaxRequest.responseText;
        // $('#MODAL_VIEW').modal('show');
        $('#SocietyTable').show();   
        $(document).ready(function () {
            $('#TableIdkdmc').DataTable({
              "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
            });
        });
        $('html, body').animate({
            scrollTop: $("#SocietyList").offset().top
        }, 500); 
    }
    var Ac_No = document.getElementsByName('Ac_NoS')[0].value;
    var SocietyName = document.getElementsByName('Societynm')[0].value;
    var PocketName = document.getElementsByName('PocketS')[0].value;

    if(Ac_No === ''){
        alert("Please Select Assembly!");
    }else if(PocketName === ''){
        alert("Please Select PocketName!");
    }else if(SocietyName === '' ){
        alert("Please Enter Society Name!");
    }else{
    var queryString = "?Ac_No="+Ac_No+"&SocietyName="+SocietyName+"&PocketName="+PocketName;
    ajaxRequest.open("POST","SocietiesListView.php" + queryString, true);
    ajaxRequest.send(null);
    }
  

function setElectionNameInSession(electionName) {
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (electionName === '') {
        alert("Please Select Corporation!");
    } else {
        var queryString = "?electionName="+electionName;
        ajaxRequest.open("POST", "setElectionNameInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}

function setDate(date) {
    alert(date);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }
    
    if (date === '') {
        alert("Please Select Date !");
    } else {
        var queryString = "?Date="+date;
        ajaxRequest.open("POST", "setDateLossOfHourReport.php" + queryString, true);
        ajaxRequest.send(null);

    }
}
//------------------------------Gaurii------------------------------
function setAssemblyInSession(acno) {
    alert(acno);
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState == 4) {
                location.reload(true);
            }
        }

    if (acno === '') {
        alert("Please Select acno!");
    } else {
        var queryString = "?assembly="+acno;
        ajaxRequest.open("POST", "setAcNoInSession.php" + queryString, true);
        ajaxRequest.send(null);

    }

}
//------------------------------Gauriii-------------------------------
}
// -----------------------------Issue Enrtry----------------------------------------
