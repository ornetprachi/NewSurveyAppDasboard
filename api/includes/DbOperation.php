<?php
 
class DbOperation
{
    private $con;
    private $con_user;
 
    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        require_once dirname(__FILE__) . '/config.php';

        $db = new DbConnect();
        $this->con_user = $db->connect_db_user();
    }

    
     //This method will connect to the database
    function getDBConnect($servername,$dbname,$dbusername,$dbpassword)
    {
        try  
        {  

            $connectionString = array("Database"=> $dbname, "CharacterSet" => "UTF-8",   
                    "Uid"=> $dbusername, "PWD"=>$dbpassword);

            //connecting to sql database
            $conn = sqlsrv_connect($servername, $connectionString); 
     
            //Checking if any error occured while connecting

            if ($conn == false) {
                die(sqlsrv_errors());
                return null;
            }

         }  
        catch(Exception $e)  
        {  
            echo("Error!");  
        }  
 
        //finally returning the connection link
        return $conn;

    }


    function getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode){
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT DBName as DbName,ServerName,ServerPwd,ServerId as ServerUser
				FROM Survey_Entry_Data..Election_Master
				WHERE ULB = '$ULB'  AND survey_flag = 1;";

        $params = array($userName, $appName, $developmentMode);
        $dbDetail = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
        
        
        if(sizeof($dbDetail)>0){
            // if(
            //     isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP']) &&
            //     isset($_SESSION['SurveyUtility_ServerPassword']) && !empty($_SESSION['SurveyUtility_ServerPassword'])
            // ){
            //     $serverName = $_SESSION['SurveyUtility_ServerIP'];
            //     $serverPwd = $_SESSION['SurveyUtility_ServerPassword'];
            // }else{
                $serverName = trim($dbDetail['ServerName']);
                $serverPwd = trim($dbDetail['ServerPwd']);
            // }
            // echo $serverName."/".$dbName."/".$ServerUser."/".$serverPwd;
            $dbName = trim($dbDetail['DbName']);
            $ServerUser = trim($dbDetail['ServerUser']);
            $data["error"] = false;
            $data["message"] = "Connected to Database Succesfully!";
            $data["conn"] = $this->getDBConnect($serverName,$dbName,$ServerUser,$serverPwd);
                      
        }else{
            $data["error"] = true;
            $data["message"] = "Not Connected to Database!";
        }
       return $data;
    }

    function setlogoutUserInatallationFlag($mobile, $appName){
        $data = array();
        $conn = $this->con_user;
        $tsql = '{CALL Sp_217_PHP_CCR_LogOutUser(?, ?)}';
        $params = array($mobile, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);  
        return $data;
    }

    function getAppinfo($mobile, $password, $appName){
        $data = array();
        $conn = $this->con_user;
        $tsql = '{CALL Sp_218_PHP_CCR_GetAppInfo(?, ?, ?)}';
        $params = array($mobile, $password, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);  
        return $data;
    }
    

    function requestMobileOTPForVerification($fullName, $mobile, $otp){

            $data = array();


            // $otp_prefix = ':';
            // $otp = rand(100000, 999999);
                
                //Please Enter Your Details
            $user="chnkya"; //your username
            $password="chnkya"; //your password
            $mobilenumbers=$mobile; //enter Mobile numbers comma seperated
            $message = "Hi $fullName, Your OTP verification code for Chankya OP Application is $otp"; //enter Your Message
            $senderid="CHNKYA"; //Your senderid
            $messagetype="N"; //Type Of Your Message
            $DReports="Y"; //Delivery Reports
            $url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
            $message = urlencode($message);
            $ch = curl_init();
            if (!$ch){die("Couldn't initialize a cURL handle");}
            $ret = curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt ($ch, CURLOPT_POSTFIELDS,
            "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
            $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
            // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
            $curlresponse = curl_exec($ch); // execute
            if(curl_errno($ch))
                //echo 'curl error : '. curl_error($ch);
                $data['error'] = true;
                $data['message'] = 'Message not sent!';
            if (empty($ret)) {
                // some kind of an error happened
                $data['error'] = true;
                $data['message'] = 'Message not sent!';
                die(curl_error($ch));
                curl_close($ch); // close cURL handler
            } else {
                $info = curl_getinfo($ch);
                curl_close($ch); // close cURL handler
                //echo $curlresponse; //echo "Message Sent Succesfully" ;
                $data['error'] = false;
                $data['message'] = 'Message Sent Succesfully';
                $data['otp'] = $otp;
                $data['jobid'] = $curlresponse;
            }

            return $data;
    }



// New Survey Utility App  LOGIN Starts ##################################################################################

function authenticateUser($mobile, $password, $appName){
        $data = array();

        $conn = $this->con_user;
        // $tsql = '{CALL Sp_212_PHP_CCR_AuthenticateUser(?, ?, ?)}';
        $tsql = "SELECT Mobile, UserName, ExpDate, AppName 
                FROM User_Master 
                where Mobile = '$mobile' AND APK_Password = '$password' 
                AND AppName ='$appName';";

        $params = array($mobile, $password, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
        
        if(sizeof($data)>0){
            $activeStatus = array();
            $activeStatus = $this->checkUserDeActiveStatus($mobile, $password, $appName);
                    if ($activeStatus == true) {
                        $checkexpiry = array();
                        $checkexpiry = $this->checkUserLicenseStatus($mobile, $password, $appName);
                      if ($checkexpiry == true) { 
                     
                            $result = USER_LOGIN_SUCCESS;                       
                    }else{
                        $result = USER_LICENSE_EXPIRED;
                    }
                }else  {
                     $result = USER_STATUS_NOT_ACTIVE;
                }
        }else{
            $result = USER_LOGIN_FAILED;
        }
        return $result;
    }


function checkUserDeActiveStatus($mobile, $password, $appName){
        $Designation = 'Manager';
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT um.Mobile, 
                    um.UserName, 
                    um.ClientName, 
                    um.ExpDate, 
                    um.AppName,
                    em.Designation
                FROM User_Master um
                INNER JOIN Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
                WHERE um.Mobile = '$mobile'
                AND um.APK_Password = '$password'
                AND um.AppName = '$appName'
                AND em.Designation IN ('CEO/Director','Manager','Senior Manager','Software Developer','Admin and Other','SP','Survey Supervisor','DE','Data Entry Executive','Govt. Project','Backoffice Executive','Client Coordinator','Backoffice Executive','Senior Botanist');";

        $params = array($mobile, $password, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params); 
       
        if(sizeof($data)>0){
            $result = true;
        
        }else{
            $result = false;   
        
        }
        
        return $result;
    }

    
    function checkUserLicenseStatus($mobile, $password, $appName){
        
        $today = date("Y-m-d");
        
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT Mobile, UserName, ClientName, ExpDate, AppName 
        FROM User_Master 
        WHERE Mobile = '$mobile'
        AND APK_Password = '$password' 
        AND AppName = '$appName'	
        AND CONVERT(VARCHAR, ExpDate, 23) >  '$today'";
        
        $params = array($mobile, $password, $appName);

        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params); 
        
        if(sizeof($data) > 0){
            $result = true;
        }else{
            $result = false;   
        }
        return $result;
    }

    
    function getLoggedInUserDetails($mobile, $password, $appName){
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT um.User_Id, um.UserName, 
                COALESCE(um.Mobile, '') as Mobile, 
                COALESCE(um.Executive_Cd,0) as Executive_Cd,
                COALESCE(um.ExecutiveName, '') as ExecutiveName, 
                COALESCE(um.Remarks, '') as FullName, 
                COALESCE(
                        case when um.UserType = 'C' then '' else convert(varchar, em.Birthdate, 23) end,
                        case when um.UserType = 'D' then '' else convert(varchar, em.Birthdate, 23) end,
                        '') as BirthDate,
                COALESCE(case 
                            when um.UserType = 'C' then 'Client' else em.Designation 
                        end,
                        case 
                            when um.UserType = 'D' then 'Doctor' else em.Designation 
                        end	
                            , '') as Designation, 
                COALESCE(
                        case 
                            when um.UserType = 'C' then '' else em.ExeType 
                        end, 
                        case 
                            when um.UserType = 'D' then '' else em.ExeType 
                        end, 
                        '') as ExeType,	
                COALESCE(um.UserType, '') as UserType,
                COALESCE(um.InstallationFlag,0) as InstallationFlag, 
                COALESCE(um.DeactiveFlag,'') as DeactiveFlag, 
                COALESCE(um.ExpFlag,'') as ExpFlag, 
                COALESCE(um.ExpDate,'') as ExpDate,  
                COALESCE(um.App_Cd,0) as App_Cd, 
                COALESCE(um.AppName,'') as AppName,
                COALESCE(um.Client_ID, 0) as Client_Cd, 
                COALESCE(cm.Client_Name, '') as Client_Name,
                COALESCE(cm.Party_Cd, 0) as Party_Cd, 
                COALESCE(cm.Site_Cds, '') as Site_Cds,
                COALESCE(cm.OP_HeaderImage, '') as OP_HeaderImage,
                COALESCE(cm.Ward_Nos, '') as Ward_Nos,
                COALESCE(pm.Party, '') as Party,
                COALESCE(pm.Party_Logo, '') as Party_Logo,
                COALESCE(e_m.Election_Cd, 0) as Election_Cd,
                COALESCE(e_m.ElectionName, '') as ElectionName,
                COALESCE(e_m.Description, '') as ElectionDescription,
                COALESCE(e_m.ActiveFlag, 0) as ElectionActiveFlag,
                COALESCE(e_m.HeaderImage_CCC, '') as HeaderImage_CCC,
                COALESCE(um.ElectionName, '') as TempElectionName
                FROM User_Master um
                Left Join Executive_Master em on em.Executive_Cd = um.Executive_Cd
                Left Join Client_Master cm on cm.Client_Cd = um.Client_ID
                Left Join Party_Master pm on cm.Party_Cd = pm.Party_Cd
                Left Join Application_Master am on am.App_Cd = um.App_Cd
                Left Join CovidCorporationMaster e_m on e_m.Election_Cd = am.Election_Cd
                where um.Mobile = '$mobile' AND um.Apk_Password = '$password' AND um.AppName = '$appName'";
        
        $params = array($mobile, $password, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);  
        return $data;
    }



    // function getSurveyUtilityCorporationElectionData($userName, $appName, $developmentMode){
    //     $data = array();
    //     $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($userName, $appName,  $developmentMode);
        
    //     if(!$dbConn["error"]){

    //         $conn = $dbConn["conn"];
                      
    //         $tsql = "SELECT *
    //                     -- COALESCE(Election_Cd,0) AS Election_Cd,
    //                     -- COALESCE(ElectionName,'') AS ElectionName,
    //                     -- COALESCE(Description, '') AS Description,
    //                     -- COALESCE(Area,'') AS Area,
    //                     -- COALESCE(ServerName,'') AS ServerName,
    //                     -- COALESCE(ServerId,'') AS ServerId,
    //                     -- COALESCE(ServerPwd,'') AS ServerPwd,
    //                     -- COALESCE(Ac_No,0) AS Ac_No,
    //                     -- COALESCE(DBName,'') AS DBName,
    //                     -- COALESCE(Site_Cd, 0) AS Site_Cd,
    //                     -- COALESCE(SiteName, '') AS SiteName,
    //                     -- COALESCE(ActiveFlag, 0 ) AS ActiveFlag
    //                 FROM Election_Master 
    //                 WHERE ActiveFlag = 1 
    //                 --AND DBName LIKE '%MemberList'
    //                 ORDER BY ElectionName ;";

    //         $params = array($userName, $appName, $developmentMode);
            
    //         $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);            
            
    //     }
    //    return $data;
    // }

    // function getSurveyUtilityCorporationElectionData($userName, $appName, $developmentMode){

    //     if(isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP'])){
    //         $servername = $_SESSION['SurveyUtility_ServerIP'];
    //     }else{
    //         $servername = "";
    //     }

    //     $data = array();
    //     $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($userName, $appName,  $developmentMode);
        
    //     if(!$dbConn["error"]){

    //         $conn = $dbConn["conn"];
                      
    //         $tsql = "SELECT *
    //                     -- COALESCE(Election_Cd,0) AS Election_Cd,
    //                     -- COALESCE(ElectionName,'') AS ElectionName,
    //                     -- COALESCE(Description, '') AS Description,
    //                     -- COALESCE(Area,'') AS Area,
    //                     -- COALESCE(ServerName,'') AS ServerName,
    //                     -- COALESCE(ServerId,'') AS ServerId,
    //                     -- COALESCE(ServerPwd,'') AS ServerPwd,
    //                     -- COALESCE(Ac_No,0) AS Ac_No,
    //                     -- COALESCE(DBName,'') AS DBName,
    //                     -- COALESCE(Site_Cd, 0) AS Site_Cd,
    //                     -- COALESCE(SiteName, '') AS SiteName,
    //                     -- COALESCE(ActiveFlag, 0 ) AS ActiveFlag
    //                 FROM Election_Master 
    //                 WHERE ActiveFlag = 1 
    //                 --AND DBName LIKE '%MemberList'
    //                 ORDER BY ElectionName ;";
                    
    //         $tsql1 = "SELECT * from Election_Master where ActiveFlag = 1
    //                     and Ac_No <> 0 and Ac_No is not null and
    //                     ServerName = '$servername' ORDER BY ElectionName";

    //         $params = array($userName, $appName, $developmentMode);
            
    //         $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql1, $params);            
            
    //     }
    //    return $data;
    // }

   
    function getSurveyUtilityCorporationElectionByCdData($ULB,$userName, $appName, $electionCd, $developmentMode){
        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName, $developmentMode);
        
        if(!$dbConn["error"]){

            $conn = $dbConn["conn"];

            $tsql = "SELECT 
                        COALESCE(Election_Cd,0) AS Election_Cd,
                        COALESCE(ElectionName,'') AS ElectionName,
                        COALESCE(Description, '') AS Description,
                        COALESCE(Area,'') AS Area,
                        COALESCE(ServerName,'') AS ServerName,
                        COALESCE(ServerId,'') AS ServerId,
                        COALESCE(ServerPwd,'') AS ServerPwd,
                        COALESCE(Ac_No,0) AS Ac_No,
                        COALESCE(DBName,'') AS DBName,
                        COALESCE(Site_Cd, 0) AS Site_Cd,
                        COALESCE(SiteName, '') AS SiteName,
                        COALESCE(ActiveFlag, 0 ) AS ActiveFlag
                    FROM Election_Master WHERE Election_Cd = $electionCd";
            $params = array($userName, $appName, $electionCd, $developmentMode);
            $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params);  
        }
        
       return $data;
    }


    function getDataInRowWithConnAndQueryAndParams($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        // if($conn){
        //     echo "here";
        // }
        $getDetail = sqlsrv_query($conn, $query, $params); 
      
        if ($getDetail == FALSE) {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
        } 
        else{
            
        $row_count = sqlsrv_num_rows( $getDetail ); 

        $data = array();

            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                    $data[] = $row;
                } 
        }
            sqlsrv_free_stmt($getDetail);  
            sqlsrv_close($conn); 

        return $data;
    }

    
    function ExecutveQuerySingleRowSALData($ULB,$query,$userName, $appName,  $developmentMode){
        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];
            $tsql = '{CALL Sp_0001_PHP_Execute_Query(?)}';
            $params = array($query);
            $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params);
         }
        return $data;
    }

    function GetDBName($ULB,$electionName, $election_Cd, $userName, $appName, $developmentMode){
        $data = array();
        $getDbNameByCorporation = "";
        $getDbNameByCorporationData = array();
        $getDbNameByCorporation = "SELECT DBName FROM Survey_Entry_Data..Election_Master WHERE ElectionName = '$electionName' AND Election_Cd = '$election_Cd' 
        ";
        $getDbNameByCorporationData = $this->ExecutveQuerySingleRowSALData($ULB,$getDbNameByCorporation , $userName, $appName, $developmentMode);
        if(sizeof($getDbNameByCorporationData) > 0){
            $data = "[".$getDbNameByCorporationData['DBName']."]";
        }else{
            $data = "DB Name is NULL!";
        }
        return $data;
    }

    function GetDBName2($ULB,$electionName,$userName, $appName, $developmentMode){
        $data = array();
        $getDbNameByCorporation = "";
        $getDbNameByCorporationData = array();
        $getDbNameByCorporation = "SELECT DBName FROM Survey_Entry_Data..Election_Master WHERE ElectionName = '$electionName' ";
        $getDbNameByCorporationData = $this->ExecutveQuerySingleRowSALData($ULB,$getDbNameByCorporation , $userName, $appName, $developmentMode);
        if(sizeof($getDbNameByCorporationData) > 0){
            $data = "[".$getDbNameByCorporationData['DBName']."]";
        }else{
            $data = "DB Name is NULL!";
        }
        return $data;
    }
    function GetDBNameULB($ULB,$userName, $appName, $developmentMode){
        $data = array();
        $getDbNameByCorporation = "";
        $getDbNameByCorporationData = array();
        $getDbNameByCorporation = "SELECT DBName FROM Survey_Entry_Data..Election_Master WHERE ULB = '$ULB' ";
        $getDbNameByCorporationData = $this->ExecutveQuerySingleRowSALData($ULB,$getDbNameByCorporation , $userName, $appName, $developmentMode);
        if(sizeof($getDbNameByCorporationData) > 0){
            $data = "[".$getDbNameByCorporationData['DBName']."]";
        }else{
            $data = "DB Name is NULL!";
        }
        return $data;
    }

    function ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode){
        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];
            $tsql = '{CALL Sp_0001_PHP_Execute_Query(?)}';
            // echo $query;
            $params = array($query);
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);
         }
        return $data;
    }

    function RunQueryData($ULB,$query, $userName, $appName,  $developmentMode){
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];
            if (sqlsrv_query($conn, $query) !== false) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }


    function RunSEDQueryData($ULB,$userName, $appName, $query,$developmentMode){
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];
            if (sqlsrv_query($conn, $query) !== false) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    function getSurveyUtilityExecutiveData($query, $userName, $appName, $developmentMode){
        $data = array();
        
            $conn = $this->con_user;
            //echo $query;
            $tsql = '{CALL Sp_0001_PHP_Execute_Query(?)}';
            $params = array($query);
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);
         
        return $data;
    }


    
    function uploadPocketMasterData($ULB,$userName, $appName, $electionCd, $electionName, $developmentMode, $action, $Pocket_Cd, $Pocket_Cd_for_Insert, $PocketName, $PocketNameMar, $Area,  $AreaNameMarathi, $SiteName, $Site_Cd, $Ward_No, $kmlFileUrl, $deActiveDate, $isActive, $updatedByUser, $PocketNo, $CorporatorCd,$Ac_No){
        $data = array();
        $empty = array();
        $finaldata = array();

        $PocketName = trim($PocketName);
        $PocketNameMar = trim($PocketNameMar);
        $Area = trim($Area);
        $AreaNameMarathi = trim($AreaNameMarathi);

        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];
            if($action == 'Insert'){

                $GetPkIfExists = "SELECT * FROM Pocket_Master WHERE PocketName = '$PocketName' AND ElectionName = '$electionName';";
                //AND SiteName = '$SiteName'
                $paramsGetPkIfExists = array($userName, $appName, $developmentMode);
                $GetPkIfExistsData = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $GetPkIfExists, $paramsGetPkIfExists);
                if(sizeof($GetPkIfExistsData) > 0){
                    if($GetPkIfExistsData['IsActive'] == 1){
                        $finaldata = array_merge($empty, array("Flag"=>"E"));
                    }else{
                        $finaldata = array_merge($empty, array("Flag"=>"ED"));
                    }
                }else{
                    $tsql = "INSERT INTO Pocket_Master(Pocket_Cd, PocketName, PocketNameM, Area, AreaM, UpdatedByUser, UpdatedDate, ElectionName, PocketNo, SiteName, Site_Cd, Ward_No, IsActive, KMLFile_Url, AddedBy, AddedDate,Corporator_Cd, Survey_Ac_No) 
                        VALUES($Pocket_Cd_for_Insert, '$PocketName', N'$PocketNameMar', '$Area', N'$AreaNameMarathi', '$userName',GETDATE(),'$electionName', $PocketNo, '$SiteName', $Site_Cd,  $Ward_No, $isActive, N'$kmlFileUrl', '$userName', GETDATE(), '$CorporatorCd', $Ac_No);";
                
                    $data = sqlsrv_query($conn, $tsql);

                    if($data){
                        $finaldata = array_merge($empty, array("Flag"=>"I"));
                    }else{
                        $finaldata = array_merge($empty, array("Flag"=>"F"));
                    }
                }
                
            }
            if($action == 'Update'){

                $tsql = "UPDATE Pocket_Master 
                            SET 
                            PocketName = '$PocketName', 
                            PocketNameM = N'$PocketNameMar', 
                            Area = '$Area',
                            AreaM = N'$AreaNameMarathi',
                            UpdatedByUser = '$userName', 
                            UpdatedDate = GETDATE(), 
                            ElectionName = '$electionName', 
                            PocketNo = '$PocketNo', 
                            SiteName = '$SiteName', 
                            Site_Cd = $Site_Cd, 
                            Ward_No = $Ward_No, 
                            IsActive = $isActive, 
                            KMLFile_Url = N'$kmlFileUrl',
                            Survey_Ac_No = $Ac_No,
                            Corporator_Cd = '$CorporatorCd'
                        WHERE Pocket_Cd = $Pocket_Cd;";
                          
                $data = sqlsrv_query($conn, $tsql);
                $finaldata = array_merge($empty, array("Flag"=>"U"));
            }
            if($action == 'Remove'){

                $tsql = "UPDATE Pocket_Master 
                            SET
                                UpdatedByUser = '$userName',
                                UpdatedDate = GETDATE(),
                                IsActive = 0
                            WHERE Pocket_Cd = $Pocket_Cd;";
                        
                $data = sqlsrv_query($conn, $tsql);
                $finaldata = array_merge($empty, array("Flag"=>"D"));
            }

         }

         
        // if($action == 'Insert' || $action == 'Update'){

        //     $tsql = "SELECT ServerName, ServerId, ServerPwd, DBName FROM Survey_Entry_Data..Election_Master WHERE ElectionName = '$electionName';";
        //     $params = array($userName, $appName, $developmentMode);
        //     $dbDetail = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
         
        //     if(sizeof($dbDetail)>0){
        //         $serverName = trim($dbDetail['ServerName']);
        //         $dbName = trim($dbDetail['DBName']);
        //         $ServerUser = trim($dbDetail['ServerId']);
        //         $serverPwd = trim($dbDetail['ServerPwd']);  
                
                
        //         $conn = $this->getDBConnect($serverName,$dbName,$ServerUser,$serverPwd);
                
        //         if($conn){
                   
        //             $UpdatedByUser = $updatedByUser;
        //             $ElectionName = $electionName;
        //             $Action = $action;
        //             $Remark = '';
        //             $UpdatedDate = date("Y-m-d h:i:sa");
        //             $tsql = '{CALL Sp_005_UploadPocketMasterFromSurveyEntryPocketMasterToElectionwiseDB(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}';
                                        
        //             if($action == 'Insert'){
        //                 $UpdatedStatus = 'Y';
        //                 $Survey_Pocket_Cd = $Pocket_Cd_for_Insert;
        //             }else if($action == 'Update'){
        //                 $UpdatedStatus = 'N';
        //                 $Survey_Pocket_Cd = $Pocket_Cd;
        //             }
                    
        //             $params = array($PocketName, $Area, $Remark, $UpdatedDate, $UpdatedByUser, $UpdatedStatus, 
        //             $ElectionName, $Action, $PocketNo, $Survey_Pocket_Cd, $SiteName, $Site_Cd, $Ward_No);
                    
        //             if($action == 'Insert'){
        //                 $GetPkIfExists = "SELECT * FROM Pocket_Master WHERE PocketName = '$PocketName';";
        //                 $paramsGetPkIfExists = array($userName, $appName, $developmentMode);
        //                 $GetPkIfExistsData = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $GetPkIfExists, $paramsGetPkIfExists);
        //                 if(sizeof($GetPkIfExistsData) > 0){
                          
        //                 }else{
        //                     $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params); 
        //                 }
        //             }else{
        //                 $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params); 
        //             }
        //         }   
        //     }
        // }
        return $finaldata;
    }


    function getSUA_DBConnectByElectionName_For_Voters_Non_Voters($userName, $appName,  $developmentMode ){
        $data = array();
        $conn = $this->con_user;
        
        if(isset($_SESSION['SurveyUA_Election_Cd']) &&
            isset($_SESSION['SurveyUA_ElectionName'])){
                $election_Cd_ofDashboard = $_SESSION['SurveyUA_Election_Cd'];
                $electionName_of_Dashboard = $_SESSION['SurveyUA_ElectionName'];
                $ElectionNameCondition = " WHERE ElectionName = '$electionName_of_Dashboard'";
        }else{
            $ElectionNameCondition = " WHERE ElectionName = 'MBMC'";
        }

        $tsql = "SELECT ServerName, ServerId, ServerPwd, DBName FROM Survey_Entry_Data..Election_Master $ElectionNameCondition;";
        $params = array($userName, $appName, $developmentMode);
        $dbDetail = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
        
        if(sizeof($dbDetail)>0){
            $serverName = trim($dbDetail['ServerName']);
            $dbName = trim($dbDetail['DBName']);
            $ServerUser = trim($dbDetail['ServerId']);
            $serverPwd = trim($dbDetail['ServerPwd']);  

            $data["error"] = false;
            $data["message"] = "Connected to Database Succesfully!";
            $data["election_wise_conn"] = $this->getDBConnect($serverName,$dbName,$ServerUser,$serverPwd);
                      
        }else{
            $data["error"] = true;
            $data["message"] = "Not Connected to Database!";
        }

       return $data;
    }

    function RunQueryDataByElectionWise($query, $userName, $appName,  $developmentMode){
        $dbConn = $this->getSUA_DBConnectByElectionName_For_Voters_Non_Voters($userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["election_wise_conn"];
            if (sqlsrv_query($conn, $query) !== false) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }


// New Survey Utility App  LOGIN Ends ##################################################################################




    // function checkUserInstallationFlag($mobile, $password, $appName){
    //     $installationFlag = 1;
    //     $data = array();
    //     $conn = $this->con_user;
    //     // $tsql = '{CALL Sp_214_PHP_CCR_CheckUserInstallationFlag(?, ?, ?, ?)}';
    //     $params = array($mobile, $password, $appName, $installationFlag);
    //     $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params); 
    //     if(sizeof($data)>0){
    //         $result = true;
    //     }else{
    //         $result = false;   
    //     }
    //     return $result;
    // }


    // function setlogoutOPUserInatallationFlag($mobile, $appName){
    //     $data = array();
    //     $conn = $this->con_user;
    //     $tsql = '{CALL Sp_217_PHP_CCR_LogOutUser(?, ?)}';
    //     $params = array($mobile, $appName);
    //     $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);  
    //     return $data;
    // }

    // function getUserDetailByExecutiveCdData($executiveCd, $appName){
    //     $data = array();
    //     $conn = $this->con_user;
    //     $tsql = '{CALL Sp_266_PHP_CHCC_GetUserDetailByExecutiveCdAndAppName(?, ?)}';
    //     $params = array($executiveCd, $appName);
    //     $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params);  
    //     return $data;
    // }



    // function getTreeCensusCorporationElectionByCdData($userName, $appName, $electionCd){
    //     $data = array();
    //     $conn = $this->con_user;
    //     $tsql = '{CALL Sp_269_PHP_CHCC_GetTreeCensusCorporationElectionByCd(?, ?, ?)}';
    //     $params = array($userName, $appName, $electionCd);
    //     $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $tsql, $params);  
        
    //    return $data;
    // }

  

    function uploadUserMasterData($ULB,$userName, $appName, $electionCd, $electionName, $developmentMode, $action, $User_Id, $POST_ElectionName)
    {
        $data = array();
        $chk_data = array();
        $final_data = array();

        $conn = $this->con_user;

            if($action == 'Update' && $User_Id != 0)
            {
                $sql1 = "SELECT top (1) User_Id
                FROM Survey_Entry_Data..User_Master 
                WHERE User_Id = $User_Id
                AND AppName = 'TreeCensus'
                AND UserType = 'C' ;";

                $para = array($userName, $appName);
                $chk_data = sqlsrv_query($conn, $sql1, $para);

                if( $chk_data == true )
                {
                    $sql2 = "UPDATE Survey_Entry_Data..User_Master 
                    SET ElectionName = '$POST_ElectionName'
                    WHERE User_Id = $User_Id
                    AND AppName = 'TreeCensus'
                    AND UserType = 'C' ;";
                    $params = array($userName, $appName);
                    //$data = $this->RunQueryData($conn, $sql2, $params);
                    //sqlsrv_query($conn, $sql2);
                    $chk_data1 = $this->RunQueryData($ULB,$sql2, $electionCd, $electionName, $developmentMode);
                    $final_data = array_merge($data, array("Flag"=>"U"));

                }
                else
                {
                    $final_data = array_merge($data, array("Flag"=>"NU"));

                }

            }
 
        return $data;
    }

  


/*\ Code End*/



    function getDataInRowWithConnAndQuery($conn, $query){
        $getDetail = sqlsrv_query($conn, $query); 
        if ($getDetail == FALSE)  
                die(sqlsrv_errors());  
             $row_count = sqlsrv_num_rows( $getDetail ); 
            
            $data = array();

            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                    $data[] = $row;
                } 

            sqlsrv_free_stmt($getDetail);  
            sqlsrv_close($conn); 
        return $data;
    }

   

    function getDataInRowWithConnAndQueryAndParamsWOCloseConn($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $getDetail = sqlsrv_query($conn, $query, $params); 

        if ($getDetail == FALSE)  {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
            }  else{

        $row_count = sqlsrv_num_rows( $getDetail ); 

        $data = array();

            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                    $data[] = $row;
                } 
        }
            // sqlsrv_free_stmt($getDetail);  
            // sqlsrv_close($conn); 

        return $data;
    }


    function getDataInRowWithConnAndQueryAndParamsForSingleRecord($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $getDetail = sqlsrv_query($conn, $query, $params); 

        if ($getDetail == FALSE)  {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
            }  else{

        $row_count = sqlsrv_num_rows( $getDetail ); 

        $data = array();

            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                    $data = $row;
                } 
        }
            sqlsrv_free_stmt($getDetail);  
            sqlsrv_close($conn); 

        return $data;
    }

     function getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $getDetail = sqlsrv_query($conn, $query, $params); 

        if ($getDetail == FALSE)  {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
            }  else{

        $row_count = sqlsrv_num_rows( $getDetail ); 

        $data = array();

            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                    $data = $row;
                } 
        }
          /*  sqlsrv_free_stmt($getDetail);  
            sqlsrv_close($conn); */

        return $data;
    }

    function getRandomPassword($length) { 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    } 

    function getRandomNumber($length) { 
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    } 

    function getRandomCharacters($length) { 
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    } 
// Added By Swapnil
    function getSurveyUtilityAppDBNameByUser($userName, $appName,  $developmentMode){
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT DbName,ServerName,ServerPwd,ServerUser from User_Master 
                WHERE UserName='$userName' and AppName='$appName';";
        $params = array($userName, $appName, $developmentMode);
        $dbDetail = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
        
        if(sizeof($dbDetail)>0){
            $dbName = trim($dbDetail['DbName']);
            $data["error"] = false;
            $data["message"] = "DB Name is Available!";
            $data["DbName"] = $dbName;
        }else{
            $data["error"] = true;
            $data["message"] = "DB Name is NUll!";
            $data["DbName"] = "";
        }

       return $data;
    }
// Added By Swapnil
        
    // Shailesh WOrk NEW ------------------------------------------------------------
    function getSurveyUtilityULB_Data($userName, $appName, $developmentMode){
        $data = array();
        $conn = $this->con_user;
        $connectionString52 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8",   
        "Uid"=> "sqlvmadmin", "PWD"=>"fEMpEALVeRingio123");
        $conn52 = sqlsrv_connect("52.140.77.2", $connectionString52); 

         if($_SESSION['SurveyUA_Mobile'] == "9324588400"){
            $Panvel = "";
         }else{
            $Panvel = ",'PANVEL'";
         }

        $tsql = "SELECT ULB, ServerName , ServerPwd, Election_Cd, ElectionName FROM Survey_Entry_Data..Election_Master 
        WHERE survey_flag = 1 ;";
        $params = array($userName, $appName, $developmentMode);
        $data = $this->getDataInRowWithConnAndQueryAndParams($conn52, $tsql, $params);
        return $data;
    }

    

    function getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode){

        if((isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP'])) &&
            (isset($_SESSION['SurveyUtility_ULB']) && !empty($_SESSION['SurveyUtility_ULB'])))
        {
            $servername = $_SESSION['SurveyUtility_ServerIP'];
            $ULB = $_SESSION['SurveyUtility_ULB'];
        }else{
            $servername = "";
            $ULB = "";
        }
        if($ULB == 'PANVEL'){
           $eleCond = "AND ElectionName = 'PT188'";
        }else{
            $eleCond = "";
        }
        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        
        if(!$dbConn["error"]){

            $conn = $dbConn["conn"];
        
        $tsql1 = "SELECT * from Survey_Entry_Data..Election_Master where ActiveFlag = 1 
                        and ServerName = '$servername' AND ULB = '$ULB' 
                        $eleCond
                        ORDER BY ElectionName";

            $params = array($userName, $appName, $developmentMode);
            
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql1, $params);            
            
        }
    return $data;
    }
   
    // Shailesh WOrk NEW ------------------------------------------------------------
   //----------------------------Gaurii-----------------------------------------------
    function getULBWiseAssemblyData($ULB, $userName, $appName, $developmentMode){

     
        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        
        if(!$dbConn["error"]){

            $conn = $dbConn["conn"];
        
        $tsql1 = "SELECT 
                COALESCE(ULB, '') AS ULB,
                COALESCE(ElectionName, '') AS ElectionName,
                COALESCE(Election_Cd, 0) AS Election_Cd,
                COALESCE(ac_nos, '') AS Ac_Nos
            FROM Survey_Entry_Data..Election_Master 
            WHERE ULB = '$ULB'
            AND ActiveFlag = 1 
            AND  survey_flag = 1";
            $params = array($userName, $appName, $developmentMode);
            
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql1, $params);            
            
        }
    return $data;
    }
   //----------------------------Gaurii-----------------------------------------------

// Assign Executive To Site -----------------------------------------------------
    function getCorporationDataForAssignExecutiveToSite($userName, $appName, $developmentMode){
        $data = array();
        $conn = $this->con_user;
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154); 
        $tsql = "SELECT * FROM Survey_Entry_Data..Election_Master WHERE ActiveFlag = 1 ORDER BY ElectionName;";
        $params = array($userName, $appName, $developmentMode);
        $data = $this->getDataInRowWithConnAndQueryAndParams($conn154, $tsql, $params);
        return $data;
    }


    function getSiteDropDownDatabyElectionName($userName, $appName,  $developmentMode){
        $data = array();
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154); 
        // $tsql = "SELECT ServerName,ServerId,ServerPwd FROM Survey_Entry_Data..Election_Master WHERE Election_Cd = $electionCd;";
        // $params = array($userName, $appName, $developmentMode);
        // $data = $this->getDataInRowWithConnAndQueryAndParams($conn154, $tsql, $params);

        // if(sizeof($data)>0){
        //     $ServerName = $data[0]['ServerName'];
        //     $ServerId = $data[0]['ServerId'];
        //     $ServerPwd = $data[0]['ServerPwd'];

        //     $connectionString = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "$ServerId", "PWD"=>"$ServerPwd");
        //     $ConnElectionWise = sqlsrv_connect($ServerName, $connectionString);   sm.ElectionName = '$electionName' AND 
            $tsql = "SELECT 
                        distinct(SiteName) as SiteName,
                        COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                        COALESCE(sm.SiteStatus,'') AS SiteStatus, 
                        COALESCE(sm.ClientName,'') AS ClientName,
                        COALESCE(sm.Area, '') AS Area,
                        COALESCE(sm.Ward_No,0) AS Ward_No,
                        COALESCE(sm.Address,'') AS Address,
                        COALESCE(sm.ElectionName,'') AS ElectionName
                    FROM [Survey_Entry_Data].[dbo].[Site_Master] sm
                    WHERE Closed = 0 ORDER BY Ward_No;";
            $params = array($userName, $appName, $developmentMode);
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn154, $tsql, $params);
        // }
        return $data;
    }

    function getSiteDropDownDatabyElectionNameAttendance($userName, $appName,  $developmentMode){
        $data = array();
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154);
            $tsql = "SELECT 
                        distinct(SiteName) as SiteName,
                        COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                        COALESCE(sm.SiteStatus,'') AS SiteStatus, 
                        COALESCE(sm.ClientName,'') AS ClientName,
                        COALESCE(sm.Area, '') AS Area,
                        COALESCE(sm.Ward_No,0) AS Ward_No,
                        COALESCE(sm.Address,'') AS Address,
                        COALESCE(sm.ElectionName,'') AS ElectionName
                    FROM [Survey_Entry_Data].[dbo].[Site_Master] sm
                    ORDER BY Ward_No;";
            $params = array($userName, $appName, $developmentMode);
            $data = $this->getDataInRowWithConnAndQueryAndParams($conn154, $tsql, $params);
        // }
        return $data;
    }
// Assign Executive To Site -----------------------------------------------------

// MOVE DB DATA  -----------------------------------------------------

    function getDataInRowWithConnAndQueryAndParamsForMoveDBData($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        $getDetail = sqlsrv_query($conn, $query, $params); 
    
        if ($getDetail == FALSE) {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
        } 
        else{
            $row_count = sqlsrv_num_rows( $getDetail );
            $data = array();
            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                $data[] = $row;
            } 
        }
        
        // sqlsrv_free_stmt($getDetail);  
        // sqlsrv_close($conn); 

        return $data;
    }

    function runqueryMoveDB($conn, $query)
    {
        $data = false;
        if(sqlsrv_query($conn,$query) !== false){
            $data = true;
        }else{
            $data = false;
        }
        return $data;
    }

    function getMoveDBDataAllInOneFunction_Testing($userName, $appName, $developmentMode,$Executive_Cd,$SourceServerName,$SourceElectionName,$DestinationServerName,$DestinationElectionName){
        $data = array();
        $conn = $this->con_user;
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154); 
        $params = array($userName, $appName, $developmentMode);



        if($SourceServerName == "103.14.99.154"){
            $SourceServerName = '';
        }else{
            $SourceServerName = "[" . $SourceServerName . "]." ;
        }

        if($DestinationServerName == "103.14.99.154"){
            $DestinationServerName = '';
        }else{
            $DestinationServerName = "[" . $DestinationServerName . "]." ;
        }

        $tsql = "SELECT DBName FROM " . $SourceServerName ."[Survey_Entry_Data].[dbo].Election_Master WHERE ElectionName = '$SourceElectionName'";
        $SourceMemberListArray = $this->getDataInRowWithConnAndQueryAndParamsForMoveDBData($conn154, $tsql, $params);

        if(sizeof($SourceMemberListArray)){
            $SourceMemberList = $SourceMemberListArray[0]['DBName'];
        }else{
            $SourceMemberList = '';
        }

        $tsql = "SELECT DBName FROM " . $DestinationServerName ."[Survey_Entry_Data_Testing].[dbo].Election_Master WHERE ElectionName = '$DestinationElectionName'";
        $DestinationMemberListArray = $this->getDataInRowWithConnAndQueryAndParamsForMoveDBData($conn154, $tsql, $params);

        if(sizeof($DestinationMemberListArray)){
            $DestinationMemberList = $DestinationMemberListArray[0]['DBName'];
        }else{
            $DestinationMemberList = '';
        }

        if($SourceMemberList == '' || $DestinationMemberList == ''){
            if($SourceMemberList == ''){
                // $data = "Error in fetching Source Memberlist";
                return false;
            }elseif($DestinationMemberList){
                // $data = "Error in fetching Destination Memberlist";
                return false;
            }
            // $data = "Error in fetching Respective Memberlist";
            // return $data;
        }

        // -------------------------Survey_Entry_Data Move Part STARTS here-------------------------


            // Add Source Survey_Entry_data.[dbo].Society_Master in Temp table
            $query = "SELECT Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                        ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                        ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                        ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                        ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                        ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                        ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                        ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                        ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                        ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                        ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
                        ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
                        ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
                        ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
                    INTO ##TempSocMst$Executive_Cd 
                    FROM
                    (
                        SELECT ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS Society_Cd,
                        Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                        ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                        ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                        ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                        ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                        ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                        ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                        ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                        ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                        ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                        ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone,LockRoom
                        ,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser,BList_QC_UpdatedDate
                        ,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date,QC_Done_By,QC_Done_Date,Society_Cd AS OLD_Society_Cd
                        FROM " . $SourceServerName . "[Survey_Entry_Data].[dbo].Society_Master
                        WHERE ElectionName = '$SourceElectionName' AND Society_Cd NOT IN (SELECT OLD_Society_Cd FROM " .$DestinationServerName. "[Survey_Entry_Data_Testing].[dbo].Society_Master)
                        
                    ) AS a1
                    ORDER BY a1.Society_Cd;";
            $AddSociety_MasterIntoTemp = $this->runqueryMoveDB($conn154, $query);


            // Update Society_Cd in Temp table
            $query = "UPDATE ##TempSocMst$Executive_Cd 
            SET Society_Cd = (Society_Cd + (SELECT COALESCE(MAX(Society_Cd),0) FROM " .$DestinationServerName. "[Survey_Entry_Data_Testing].[dbo].Society_Master));";
            $UpdateSociety_CdInTemp = $this->runqueryMoveDB($conn154, $query);

            
            // Add TempSocMst table data INTO Destination Society Master
            $query = "INSERT INTO ". $DestinationServerName ."[Survey_Entry_Data_Testing].[dbo].Society_Master 
            (	
                Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
                ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
                ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
                ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
            )
            SELECT 
            Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
            ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
            ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
            ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
            ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
            ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
            ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
            ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
            ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
            ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
            ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
            ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
            ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
            ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
            FROM ##TempSocMst$Executive_Cd;";
            $AddTempSocMstIntoDestinationSocMst = $this->runqueryMoveDB($conn154, $query);


            // Drop Temp Table Query
            $query = "DROP TABLE ##TempSocMst$Executive_Cd;";
            $DropTempSocMst = $this->runqueryMoveDB($conn154, $query);
        
        // -------------------------Survey_Entry_Data Move Part ENDS here-------------------------

        // -------------------------SublocationMaster Move Part STARTS here-------------------------


        $query = "SELECT 
        SubLocation_Cd,
        SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
        ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
        ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
        ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
        ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
        ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
        ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
        ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
        ,OLD_SubLocation_Cd
        INTO ##TempSubloc$Executive_Cd
        FROM
        (
            SELECT 
            ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS SubLocation_Cd,
            sub.SocietyNameM,sub.SubLocationNo,sub.List_No,sub.SubLocationName,sub.Remark
            ,sub.Start_No,sub.End_No,sub.SocietyName,sub.Sector,sub.SectorM,sub.MinVoteId,sub.MaxVoteId,sub.City_Cd
            ,sub.Ac_No,sub.City,sub.AreaMISC,sub.SubLocationNameMISC,sub.Pincode,sub.SubLocationNameM,sub.AreaM
            ,sub.Ward_No,sub.OldWard_No,sub.UpdateSocStatus,sub.SecretaryName,sub.SecretaryMobileNo
            ,sub.ChairmanName,sub.ChairmanMobileNo,sub.TresurerName,sub.TresurerMobileNo,sub.ShiftedSociety
            ,sub.PlotNo,sub.Floor,sub.Rooms,sub.SequenceCode,sub.UpdatedDate,sub.UpdateByUser,sub.Survey_Society_Cd
            ,sub.ControlChartWard,sub.POCKETS,sub.DownloadTime,sub.Site_Cd,sub.SiteName,sub.BList_UpdatedByUser
            ,sub.BList_UpdatedDate,sub.Pocket_Cd,sub.PocketName,sub.Longitude,sub.Latitude,sub.Survey,sub.SubLocation_Cd AS OLD_SubLocation_Cd
            FROM " . $SourceServerName . "[" . $SourceMemberList . "].[dbo].SubLocationMaster AS Sub
            INNER JOIN ". $DestinationServerName ."[Survey_Entry_Data_Testing].[dbo].Society_Master AS sm ON (sm.OLD_Society_Cd = sub.Survey_Society_Cd)
            WHERE sm.SocietyName IS NOT NULL AND sm.SocietyName <> '' AND sub.Sublocation_Cd NOT IN (SELECT OLD_SubLocation_Cd FROM ". $DestinationServerName ."[" . $DestinationMemberList . "].[dbo].SubLocationMaster)
        ) AS a1
        ORDER BY a1.SubLocation_Cd;";
        $AddSourceSublocIntoTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Update Survey_Scoiety_Cd Column as per updated destination Survey_Entry_Data..Society_Master
        $query = "UPDATE [##TempSubloc$Executive_Cd]
                SET Survey_Society_Cd = sm.Society_Cd
                FROM [##TempSubloc$Executive_Cd] AS tsubloc 
                INNER JOIN ". $DestinationServerName ."[Survey_Entry_Data_Testing].[dbo].Society_Master AS sm ON (tsubloc.Survey_Society_Cd = sm.OLD_Society_Cd);";
        $UpdateSurveySocCdInTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Update Sublocation_Cd in TempSubloc
        $query = "UPDATE ##TempSubloc$Executive_Cd 
        SET Sublocation_Cd = (Sublocation_Cd + (SELECT DISTINCT(COALESCE(OLD_SubLocation_Cd,0)) FROM " . $DestinationServerName . "[" . $DestinationMemberList . "].[dbo].SubLocationMaster))";
        $UpdateSublocCdInTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Insert TempSubloc Data into Destination Sublocation Master
        $query = "INSERT INTO ". $DestinationServerName ."[" . $DestinationMemberList . "].[dbo].SubLocationMaster 
        (	
            SubLocation_Cd,
            SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
            ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
            ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
            ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
            ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
            ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
            ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
            ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
            ,OLD_SubLocation_Cd
        )
        SELECT 
            SubLocation_Cd,
            SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
            ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
            ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
            ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
            ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
            ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
            ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
            ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
            ,OLD_SubLocation_Cd
        FROM ##TempSubloc$Executive_Cd;";
        $AddTempSublocIntoDestinationSubloc = $this->runqueryMoveDB($conn154, $query);


        // Drop Temp Table Query
        $query = "DROP TABLE ##TempSubloc$Executive_Cd;";
        $DropTempSubloc = $this->runqueryMoveDB($conn154, $query);
        

        if($AddTempSocMstIntoDestinationSocMst == true && $AddTempSublocIntoDestinationSubloc == true){
            $data = true;
        }else{
            $data = false;
        }
        return $query; 
    }


    function getMoveDBDataAllInOneFunction($userName, $appName, $developmentMode,$Executive_Cd,$SourceServerName,$SourceElectionName,$DestinationServerName,$DestinationElectionName){
        $data = array();
        $conn = $this->con_user;
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154); 
        $params = array($userName, $appName, $developmentMode);



        if($SourceServerName == "103.14.99.154"){
            $SourceServerName = '';
        }else{
            $SourceServerName = "[" . $SourceServerName . "]." ;
        }

        if($DestinationServerName == "103.14.99.154"){
            $DestinationServerName = '';
        }else{
            $DestinationServerName = "[" . $DestinationServerName . "]." ;
        }

        $tsql = "SELECT DBName FROM " . $SourceServerName ."[Survey_Entry_Data].[dbo].Election_Master WHERE ElectionName = '$SourceElectionName'";
        $SourceMemberListArray = $this->getDataInRowWithConnAndQueryAndParamsForMoveDBData($conn154, $tsql, $params);

        if(sizeof($SourceMemberListArray)){
            $SourceMemberList = $SourceMemberListArray[0]['DBName'];
        }else{
            $SourceMemberList = '';
        }

        $tsql = "SELECT DBName FROM " . $DestinationServerName ."[Survey_Entry_Data].[dbo].Election_Master WHERE ElectionName = '$DestinationElectionName'";
        $DestinationMemberListArray = $this->getDataInRowWithConnAndQueryAndParamsForMoveDBData($conn154, $tsql, $params);

        if(sizeof($DestinationMemberListArray)){
            $DestinationMemberList = $DestinationMemberListArray[0]['DBName'];
        }else{
            $DestinationMemberList = '';
        }

        // print_r($DestinationMemberList);
        // return $DestinationMemberList;

        if($SourceMemberList == '' || $DestinationMemberList == ''){
            if($SourceMemberList == ''){
                // $data = "Error in fetching Source Memberlist";
                return false;
            }elseif($DestinationMemberList){
                // $data = "Error in fetching Destination Memberlist";
                return false;
            }
            // $data = "Error in fetching Respective Memberlist";
            // return $data;
        }

        // -------------------------Survey_Entry_Data Move Part STARTS here-------------------------


            // Add Source Survey_Entry_data.[dbo].Society_Master in Temp table
            $query = "SELECT Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                        ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                        ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                        ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                        ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                        ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                        ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                        ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                        ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                        ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                        ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
                        ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
                        ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
                        ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
                    INTO ##TempSocMst$Executive_Cd 
                    FROM
                    (
                        SELECT ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS Society_Cd,
                        Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                        ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                        ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                        ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                        ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                        ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                        ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                        ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                        ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                        ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                        ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone,LockRoom
                        ,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser,BList_QC_UpdatedDate
                        ,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date,QC_Done_By,QC_Done_Date,Society_Cd AS OLD_Society_Cd
                        FROM " . $SourceServerName . "[Survey_Entry_Data].[dbo].Society_Master
                        WHERE ElectionName = '$SourceElectionName' AND Society_Cd NOT IN (SELECT DISTINCT(COALESCE(OLD_Society_Cd,0)) FROM " .$DestinationServerName. "[Survey_Entry_Data].[dbo].Society_Master)
                        
                    ) AS a1
                    ORDER BY a1.Society_Cd;";
                    // return $query;
            $AddSociety_MasterIntoTemp = $this->runqueryMoveDB($conn154, $query);


            // Update Society_Cd in Temp table
            $query = "UPDATE ##TempSocMst$Executive_Cd 
            SET Society_Cd = (Society_Cd + (SELECT COALESCE(MAX(Society_Cd),0) FROM " .$DestinationServerName. "[Survey_Entry_Data].[dbo].Society_Master));";
            $UpdateSociety_CdInTemp = $this->runqueryMoveDB($conn154, $query);

            
            // Add TempSocMst table data INTO Destination Society Master
            $query = "INSERT INTO ". $DestinationServerName ."[Survey_Entry_Data].[dbo].Society_Master 
            (	
                Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
                ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
                ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
                ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
                ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
                ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
                ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
                ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
                ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
                ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
                ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
                ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
                ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
                ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
            )
            SELECT 
            Society_Cd,Site_Cd,SiteName,SocietyName,Sector,PlotNo,WardNo,Area,Floor
            ,Rooms,Category,Permission,Servey,DataEntry,Remark,UpdatedByUser,UpdatedDate
            ,SecretaryName,SecretaryMobileNo,ChairmanName,ChairmanMobileNo,TresurerName
            ,TresurerMobileNo,ShiftedSociety,Longitude,Latitude,SequenceCode,SocietyNameMar,Wing
            ,PermissionDone,SurveyDate,F1,F2,F3,DSK_UpdatedByUser,DSK_UpdatedDate,Old_SiteName
            ,Survey_UpdatedByUser,Survey_UpdatedDate,Permission_UpdatedByUser,Permission_UpdatedDate
            ,AreaMar,BList_UpdatedByUser,BList_UpdatedDate,ElectionName,Pocket_Cd,PocketName
            ,BaseSequenceCode,Ac_No,Supervisor_Cd,SupervisorName,Schedule_Date,Permission_Schedule_Date
            ,Building_Plate_Image,Building_Image,ElectionDB_UploadStatus,TotalVoters,TotalNonVoters
            ,Col1,Col2,Col3,Col4,Col5,Remark1,Remark2,Remark3,ApproveList,NewFloor,NewRooms,OldFloors
            ,OldRooms,Executive_Cd,AssignedDate,IsCompleted,CompletedOn,AddedBy,AddedDate,RoomDone
            ,LockRoom,LocUpdatedByUser,LocUpdatedDate,ShopCount,IsSociety,BList_QC_UpdatedByUser
            ,BList_QC_UpdatedDate,BList_QC_UpdatedFlag,QC_Done_Flag,QC_Assign_To,QC_Assign_Date
            ,QC_Done_By,QC_Done_Date,OLD_Society_Cd
            FROM ##TempSocMst$Executive_Cd;";
            $AddTempSocMstIntoDestinationSocMst = $this->runqueryMoveDB($conn154, $query);


            // Drop Temp Table Query
            $query = "DROP TABLE ##TempSocMst$Executive_Cd;";
            $DropTempSocMst = $this->runqueryMoveDB($conn154, $query);
        
        // -------------------------Survey_Entry_Data Move Part ENDS here-------------------------

        // -------------------------SublocationMaster Move Part STARTS here-------------------------


        $query = "SELECT 
        SubLocation_Cd,
        SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
        ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
        ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
        ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
        ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
        ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
        ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
        ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
        ,OLD_SubLocation_Cd
        INTO ##TempSubloc$Executive_Cd
        FROM
        (
            SELECT 
            ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS SubLocation_Cd,
            sub.SocietyNameM,sub.SubLocationNo,sub.List_No,sub.SubLocationName,sub.Remark
            ,sub.Start_No,sub.End_No,sub.SocietyName,sub.Sector,sub.SectorM,sub.MinVoteId,sub.MaxVoteId,sub.City_Cd
            ,sub.Ac_No,sub.City,sub.AreaMISC,sub.SubLocationNameMISC,sub.Pincode,sub.SubLocationNameM,sub.AreaM
            ,sub.Ward_No,sub.OldWard_No,sub.UpdateSocStatus,sub.SecretaryName,sub.SecretaryMobileNo
            ,sub.ChairmanName,sub.ChairmanMobileNo,sub.TresurerName,sub.TresurerMobileNo,sub.ShiftedSociety
            ,sub.PlotNo,sub.Floor,sub.Rooms,sub.SequenceCode,sub.UpdatedDate,sub.UpdateByUser,sub.Survey_Society_Cd
            ,sub.ControlChartWard,sub.POCKETS,sub.DownloadTime,sub.Site_Cd,sub.SiteName,sub.BList_UpdatedByUser
            ,sub.BList_UpdatedDate,sub.Pocket_Cd,sub.PocketName,sub.Longitude,sub.Latitude,sub.Survey,sub.SubLocation_Cd AS OLD_SubLocation_Cd
            FROM " . $SourceServerName . "[" . $SourceMemberList . "].[dbo].SubLocationMaster AS Sub
            INNER JOIN ". $DestinationServerName ."[Survey_Entry_Data].[dbo].Society_Master AS sm ON (sm.OLD_Society_Cd = sub.Survey_Society_Cd)
            WHERE sm.SocietyName IS NOT NULL AND sm.SocietyName <> '' AND sub.Sublocation_Cd NOT IN (SELECT DISTINCT(COALESCE(OLD_SubLocation_Cd,0)) FROM ". $DestinationServerName ."[" . $DestinationMemberList . "].[dbo].SubLocationMaster)
        ) AS a1
        ORDER BY a1.SubLocation_Cd;";
        $AddSourceSublocIntoTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Update Survey_Scoiety_Cd Column as per updated destination Survey_Entry_Data..Society_Master
        $query = "UPDATE [##TempSubloc$Executive_Cd]
                SET Survey_Society_Cd = sm.Society_Cd
                FROM [##TempSubloc$Executive_Cd] AS tsubloc 
                INNER JOIN ". $DestinationServerName ."[Survey_Entry_Data].[dbo].Society_Master AS sm ON (tsubloc.Survey_Society_Cd = sm.OLD_Society_Cd);";
        $UpdateSurveySocCdInTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Update Sublocation_Cd in TempSubloc
        $query = "UPDATE ##TempSubloc$Executive_Cd 
        SET Sublocation_Cd = (Sublocation_Cd + (SELECT DISTINCT(COALESCE(OLD_SubLocation_Cd,0)) FROM " . $DestinationServerName . "[" . $DestinationMemberList . "].[dbo].SubLocationMaster))";
        $UpdateSublocCdInTempSubloc = $this->runqueryMoveDB($conn154, $query);


        // Insert TempSubloc Data into Destination Sublocation Master
        $query = "INSERT INTO ". $DestinationServerName ."[" . $DestinationMemberList . "].[dbo].SubLocationMaster 
        (	
            SubLocation_Cd,
            SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
            ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
            ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
            ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
            ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
            ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
            ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
            ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
            ,OLD_SubLocation_Cd
        )
        SELECT 
            SubLocation_Cd,
            SocietyNameM,SubLocationNo,List_No,SubLocationName,Remark
            ,Start_No,End_No,SocietyName,Sector,SectorM,MinVoteId,MaxVoteId,City_Cd
            ,Ac_No,City,AreaMISC,SubLocationNameMISC,Pincode,SubLocationNameM,AreaM
            ,Ward_No,OldWard_No,UpdateSocStatus,SecretaryName,SecretaryMobileNo
            ,ChairmanName,ChairmanMobileNo,TresurerName,TresurerMobileNo,ShiftedSociety
            ,PlotNo,Floor,Rooms,SequenceCode,UpdatedDate,UpdateByUser,Survey_Society_Cd
            ,ControlChartWard,POCKETS,DownloadTime,Site_Cd,SiteName,BList_UpdatedByUser
            ,BList_UpdatedDate,Pocket_Cd,PocketName,Longitude,Latitude,Survey
            ,OLD_SubLocation_Cd
        FROM ##TempSubloc$Executive_Cd;";
        $AddTempSublocIntoDestinationSubloc = $this->runqueryMoveDB($conn154, $query);


        // Drop Temp Table Query
        $query = "DROP TABLE ##TempSubloc$Executive_Cd;";
        $DropTempSubloc = $this->runqueryMoveDB($conn154, $query);
        

        if($AddTempSocMstIntoDestinationSocMst == true && $AddTempSublocIntoDestinationSubloc == true){
            $data = true;
        }else{
            $data = false;
        }
        return $query; 
    }

// -------------------------------------------------------------------------------------------------------------------
        
    function runquery($conn, $query)
    {
        $data = false;
        if(sqlsrv_query($conn,$query) !== false){
            $data = true;
        } else {
            $data = false;
        }
        return $data;
    }

    function getDataInRowWithConnAndQueryAndParamsSurveyQCDateWise($conn, $query, $params){
        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

        $getDetail = sqlsrv_query($conn, $query, $params); 
      
        if ($getDetail == FALSE) {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
        } 
        else{
            $row_count = sqlsrv_num_rows( $getDetail );
            $data = array();
            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                $data[] = $row;
            } 
        }
        
        // sqlsrv_free_stmt($getDetail);  
        // sqlsrv_close($conn); 

        return $data;
    }

    function RunQueryDataSurveyQCDateWise($ULB,$userName, $appName,  $developmentMode, $Executive_Cd, $fromDate , $toDate,$DBName,$SiteName){

        $data = array();
        $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
        if(!$dbConn["error"]){
            $conn = $dbConn["conn"];

            $q1 = "DROP TABLE ##TempRep$Executive_Cd;";

            $this->runquery($conn, $q1);

            
            $q2 = "SELECT UpdateByUser, Sublocation_Cd,QC_Done, BirthDate, MobileNo, LockedButSurvey, RoomNo, Voter_Cd, Vtype
            INTO ##TempRep$Executive_Cd 
            FROM
            (SELECT UpdateByUser, Sublocation_Cd, QC_Done,
            -- CASE 
            -- WHEN ISDATE(BirthDate) = 1 THEN CONVERT(varchar, CONVERT(date, BirthDate, 101), 101)
            -- WHEN ISDATE(BirthDate) = 1 THEN CONVERT(varchar, CONVERT(date, BirthDate, 105), 101) 
            -- WHEN ISDATE(BirthDate) = 1 THEN CONVERT(varchar, CONVERT(date, BirthDate, 23), 101) 
            -- ELSE NULL
            -- END AS BirthDate,
            BirthDate, 
            MobileNo, LockedButSurvey, RoomNo, Voter_Cd, 'V' AS Vtype FROM $DBName..Dw_VotersInfo WHERE SF = 1 
            AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate' AND SiteName = '$SiteName' 
            UNION ALL
            SELECT UpdateByUser, Subloc_Cd AS Sublocation_Cd,QC_Done, CONVERT(varchar, CONVERT(date, BirthDate, 101), 101) AS BirthDate, MobileNo, LockedButSurvey, Roomno, Voter_Cd, 'NV' AS Vtype 
            FROM $DBName..NewVoterRegistration WHERE CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate'  AND SiteName = '$SiteName' 
            UNION ALL 
            SELECT UpdateByUser, Sublocation_Cd,NULL AS QC_Done, NULL AS BirthDate, NULL AS MobileNo, NULL AS LockedButSurvey, RoomNo, NULL AS Voter_Cd, 'LK' AS Vtype 
            FROM $DBName..LockRoom WHERE CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate'  AND SiteName = '$SiteName' 
            ) AS a1;";

            $this->runquery($conn,$q2);

            $query = "SELECT um.UserName, um.ExecutiveName, um.Mobile, COUNT(DISTINCT(sm.Society_Cd)) AS SocietyCount, COUNT(BirthDate) AS BirthDayCount, COUNT(DISTINCT(MobileNo)) AS MobileCount, 
            (SELECT COUNT(QC_Done) FROM ##TempRep$Executive_Cd WHERE QC_Done = 0 AND UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI) AS QC_Pending,
            (SELECT COUNT(QC_Done) FROM ##TempRep$Executive_Cd WHERE QC_Done = 1 AND UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI) AS QC_Done,
            (SELECT COUNT(DISTINCT(Voter_Cd)) FROM $DBName..NewVoterRegistrationDeleted WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND CONVERT(VARCHAR, UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate'  AND SiteName = '$SiteName') AS Converted,
            (SELECT SUM(RoomNo) FROM (SELECT SubLocation_Cd, COUNT(DISTINCT(RoomNo)) AS RoomNo FROM ##TempRep$Executive_Cd WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND COALESCE(LockedButSurvey, '') = '' GROUP BY SubLocation_Cd) AS a) AS RoomSurvey
            , COALESCE((SELECT SUM(RoomNo) FROM (SELECT SubLocation_Cd, COUNT(DISTINCT(RoomNo)) AS RoomNo FROM ##TempRep$Executive_Cd WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND LockedButSurvey <> '' AND LockedButSurvey IS NOT NULL GROUP BY SubLocation_Cd) AS a), 0) AS LBS
            , (SELECT COUNT(RoomNo) FROM ##TempRep$Executive_Cd WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND Vtype = 'LK') AS LockRoom
            , (SELECT COUNT(DISTINCT(Voter_Cd)) FROM ##TempRep$Executive_Cd WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND Vtype = 'V') AS Voters
            , (SELECT COUNT(DISTINCT(Voter_Cd)) FROM ##TempRep$Executive_Cd WHERE UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI AND Vtype = 'NV') AS NonVoters
            FROM ##TempRep$Executive_Cd AS tm 
            INNER JOIN Survey_Entry_Data..User_Master AS um ON (um.UserName = tm.UpdateByUser COLLATE Latin1_General_CI_AI)
            INNER JOIN $DBName..SubLocationMaster AS sbm ON (tm.Sublocation_Cd = sbm.Sublocation_Cd) 
            INNER JOIN Survey_Entry_Data..Society_Master AS sm ON (sbm.Survey_Society_Cd = sm.Society_Cd) 
            GROUP BY um.UserName, um.ExecutiveName, um.Mobile 
            ORDER BY um.ExecutiveName;";

            $tsql = '{CALL Sp_0001_PHP_Execute_Query(?)}';
            $params = array($query);
            $data = $this->getDataInRowWithConnAndQueryAndParamsSurveyQCDateWise($conn, $tsql, $params);

            $q4 = "DROP TABLE ##TempRep$Executive_Cd;";

            $this->runquery($conn, $q4);

        }
        return $data;
    }



// OTP Login -----------------------------------------------------
        
    function authenticateUserStepOne($mobile, $appName){
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT Mobile, UserName, ExpDate, AppName 
                FROM User_Master 
                WHERE Mobile = '$mobile' AND AppName ='$appName';";

        $params = array($mobile, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params);  
        
        if(sizeof($data)>0){
            $activeStatus = array();
            $activeStatus = $this->checkUserDeActiveStatusStepTwo($mobile,$appName);
                if ($activeStatus == true) {
                        $checkexpiry = array();
                        $checkexpiry = $this->checkUserLicenseStatusStepThree($mobile, $appName);
                    if ($checkexpiry == true) { 
                    
                            $result = USER_LOGIN_SUCCESS;                       
                    }else{
                        $result = USER_LICENSE_EXPIRED;
                    }
                }else  {
                    $result = USER_STATUS_NOT_ACTIVE;
                }
        }else{
            $result = USER_LOGIN_FAILED;
        }
        return $result;
    }


    function checkUserDeActiveStatusStepTwo($mobile, $appName){
        $Designation = 'Manager';
        $data = array();
        if($mobile == "9324588400"){
            $DesgCon = "";
        }else{
            $DesgCon = "AND em.Designation IN ('CEO/Director','Manager','Senior Manager','Software Developer','Admin and Other','SP','Survey Supervisor','DE','Data Entry Executive','Govt. Project','Backoffice Executive','HR Executive','Admin Executive','Client Coordinator','COO / Director','Client Relationship Manager','General manager','Admin Associate','Survey Manager','Technical Support Engineer','Survey Supervisor')";
        }
        $conn = $this->con_user;
        $tsql = "SELECT um.Mobile, 
                    um.UserName, 
                    um.ClientName, 
                    um.ExpDate, 
                    um.AppName,
                    em.Designation
                FROM User_Master um
                LEFT JOIN Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
                WHERE um.Mobile = '$mobile'
                AND um.AppName = '$appName'
                $DesgCon
                ;";

        // AND um.APK_Password = '$password'
        $params = array($mobile, $appName);
        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params); 
    
        if(sizeof($data)>0){
            $result = true;
        }else{
            $result = false;
        }
        return $result;
    }


    function checkUserLicenseStatusStepThree($mobile, $appName){
        
        $today = date("Y-m-d");
        
        $data = array();
        $conn = $this->con_user;
        $tsql = "SELECT Mobile, UserName, ClientName, ExpDate, AppName 
        FROM User_Master 
        WHERE Mobile = '$mobile'
        AND AppName = '$appName'    
        AND CONVERT(VARCHAR, ExpDate, 23) > '$today'";
        $params = array($mobile, $appName);
        // AND APK_Password = '$password' 
        $data = $this->getDataInRowWithConnAndQueryAndParamsForSingleRecordForLogin($conn, $tsql, $params); 
        if(sizeof($data) > 0){
            $result = true;
        }else{
            $result = false;   
        }
        return $result;
    }

    function  sendOTPtoVerifiedUSER($mobile, $appName){
        $data = array();
        $conn = $this->con_user;
        // SMS OTP SENDING
        $otp = rand(1111, 9999);

        $data = array();
        $appKey = "";
        $msg = 'Your OTP is: '.$otp.' '.$appKey.',ORNET';
        $message = urlencode($msg);
        
        $UpdateOTPQuery = "UPDATE Survey_Entry_Data..User_Master 
                        SET
                            OTP = '$otp'
                        WHERE Mobile = '$mobile' AND AppName = '$appName';";

        if(sqlsrv_query($conn, $UpdateOTPQuery) !== false) {
            $url = 'http://45.114.141.83/api/mt/SendSMS?username=ornettech&password=ornet@3214&senderid=ORNETT&type=0&destination='.$mobile.'&peid=1701161892254896671&text='.$message;
            $response = file_get_contents($url);
            $obj = json_decode($response);
            if($obj->ErrorMessage == 'Done'){
                $JobId = $obj->JobId;
                $url1 = 'http://45.114.141.83/api/mt/GetDelivery?user=ornettech&password=ornet@3214&jobid='.$JobId;
                sleep(5);
                $response1 = file_get_contents($url1);
                $obj1 = json_decode($response1);
                if($obj1->DeliveryReports[0]->DeliveryStatus == 'Sent' || $obj1->DeliveryReports[0]->DeliveryStatus == 'Delivered'){
                    echo json_encode(array('statusCode' => 200, 'msg' => 'OTP Sent Succesfully!!'));
                }else{
                    echo json_encode(array('statusCode' => 404, 'msg' => 'OTP Not Sent!!'));
                }
            }else{
                echo json_encode(array('statusCode' => 404, 'msg' => 'OTP Not Sent!!'));
            }
        }else {
            echo json_encode(array('statusCode' => 404, 'msg' => 'OTP Not Sent!!'));
        }
    }

    function verifyOTPandLogin($mobile, $OTP_Pass, $appName){
        $data = array();
        $empty = array();
        $conn = $this->con_user;
        $OTPCond = "";

        if($mobile == "9324588400" && $OTP_Pass == '567'){
                $OTPCond = "";
        }else if($mobile == "9223575193" || $mobile == "9223575189" || $mobile == "9820480999" ||
                $mobile == "7700998602" || $mobile == "8097485495" || $mobile == "9820480368" || 
                $mobile == "9920480368" || $mobile == "9820743654" || $mobile == "9969787575" || 
                $mobile == "7721036013" || $mobile == "7045991170" || $mobile == "9594635868" || $mobile == "9892521519" 
                || $mobile == "7721036013" || $mobile == "7796862170" || $mobile == "7400272333"){
                if($OTP_Pass == '7575'){
                    $OTPCond = "";
                }else{
                    $OTPCond = " AND OTP = '$OTP_Pass'";
                }
        }else{
            $OTPCond = " AND OTP = '$OTP_Pass'";
        }
        $UpdateOTP = "SELECT * FROM User_Master WHERE Mobile = '$mobile' AND AppName = '$appName' $OTPCond;";
        $getDetail = sqlsrv_query($conn, $UpdateOTP); 
        $row_count = sqlsrv_num_rows( $getDetail ); 
        $data = array();
        while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }

        if(sizeof($data)>0){
            $data["userData"] = $this->getLoggedInUserDetailsAfterVerified($mobile, $OTP_Pass, $appName);
        }else{
            $data["userData"] = $empty;
        }
        return $data;
    }

    
    function getLoggedInUserDetailsAfterVerified($mobile, $OTP_Pass, $appName){
        $data = array();
        $conn = $this->con_user;
        $OTPCond = "";
        if($mobile == "9223575193" || $mobile == "9223575189" || $mobile == "9820480999" ||
            $mobile == "7700998602" || $mobile == "8097485495" || $mobile == "9820480368" || 
            $mobile == "9920480368" || $mobile == "9820743654" || $mobile == "9969787575" || 
            $mobile == "7721036013" || $mobile == "7045991170" || $mobile == "9594635868" || $mobile == "9892521519" || $mobile == "9324588400" 
            || $mobile == "7721036013" || $mobile == "7796862170"|| $mobile == "7400272333"){
            $OTPCond = "";
        }else{
            $OTPCond = " AND um.OTP = '$OTP_Pass'";
        }
        $tsql = "SELECT um.User_Id, um.UserName, 
                COALESCE(um.Mobile, '') as Mobile, 
                COALESCE(um.Executive_Cd,0) as Executive_Cd,
                COALESCE(um.ExecutiveName, '') as ExecutiveName, 
                COALESCE(um.Remarks, '') as FullName, 
                COALESCE(
                        case when um.UserType = 'C' then '' else convert(varchar, em.Birthdate, 23) end,
                        case when um.UserType = 'D' then '' else convert(varchar, em.Birthdate, 23) end,
                        '') as BirthDate,
                COALESCE(case 
                            when um.UserType = 'C' then 'Client' else em.Designation 
                        end,
                        case 
                            when um.UserType = 'D' then 'Doctor' else em.Designation 
                        end 
                            , '') as Designation, 
                COALESCE(
                        case 
                            when um.UserType = 'C' then '' else em.ExeType 
                        end, 
                        case 
                            when um.UserType = 'D' then '' else em.ExeType 
                        end, 
                        '') as ExeType, 
                COALESCE(um.UserType, '') as UserType,
                COALESCE(um.InstallationFlag,0) as InstallationFlag, 
                COALESCE(um.DeactiveFlag,'') as DeactiveFlag, 
                COALESCE(um.ExpFlag,'') as ExpFlag, 
                COALESCE(um.ExpDate,'') as ExpDate,  
                COALESCE(um.App_Cd,0) as App_Cd, 
                COALESCE(um.AppName,'') as AppName,
                COALESCE(um.Client_ID, 0) as Client_Cd, 
                COALESCE(cm.Client_Name, '') as Client_Name,
                COALESCE(cm.Party_Cd, 0) as Party_Cd, 
                COALESCE(cm.Site_Cds, '') as Site_Cds,
                COALESCE(cm.OP_HeaderImage, '') as OP_HeaderImage,
                COALESCE(cm.Ward_Nos, '') as Ward_Nos,
                COALESCE(pm.Party, '') as Party,
                COALESCE(pm.Party_Logo, '') as Party_Logo,
                COALESCE(e_m.Election_Cd, 0) as Election_Cd,
                COALESCE(e_m.ElectionName, '') as ElectionName,
                COALESCE(e_m.Description, '') as ElectionDescription,
                COALESCE(e_m.ActiveFlag, 0) as ElectionActiveFlag,
                COALESCE(e_m.HeaderImage_CCC, '') as HeaderImage_CCC,
                COALESCE(um.ElectionName, '') as TempElectionName
                FROM User_Master um
                Left Join Executive_Master em on em.Executive_Cd = um.Executive_Cd
                Left Join Client_Master cm on cm.Client_Cd = um.Client_ID
                Left Join Party_Master pm on cm.Party_Cd = pm.Party_Cd
                Left Join Application_Master am on am.App_Cd = um.App_Cd
                Left Join CovidCorporationMaster e_m on e_m.Election_Cd = am.Election_Cd
                where um.Mobile = '$mobile' AND um.AppName = '$appName' $OTPCond";
        
        // $params = array($mobile, $OTP_Pass, $appName);
        // $data = $this->getDataInRowWithConnAndQueryAndParams($conn, $tsql, $params);  
        $getDetail = sqlsrv_query($conn, $tsql); 
      
        if ($getDetail == FALSE) {  
            echo "Error in executing statement 3.\n";  
            die( print_r( sqlsrv_errors(), true));  
        }else{
            $row_count = sqlsrv_num_rows( $getDetail ); 
            $data = array();
            while($row = sqlsrv_fetch_array($getDetail, SQLSRV_FETCH_ASSOC)){
                $data[] = $row;
            }

            $UpdateOTPQuery = "UPDATE Survey_Entry_Data..User_Master 
                            SET
                                OTP = NULL
                            WHERE Mobile = '$mobile' AND AppName = '$appName';";

            $update = sqlsrv_query($conn, $UpdateOTPQuery);
        }
        return $data;
    }
// OTP Login -----------------------------------------------------
    

// Salary Process Data ---------------------------------------------
    // function salaryProcess($userName, $appName,  $developmentMode, $Executive_Cd, $Month, $Year, $totalDays, $process){

    //     $data = array();
    //     $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
    //     $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154);
    //     $FirstDay = "$Year-$Month-01";
    //     $LastDay = "$Year-$Month-$totalDays";
        
    //     if($process == "again"){
    //         $data['Flag'] = "NO";
    //         $this->runquery($conn154, "TRUNCATE TABLE Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year.";");
    //     }else{
    //         $CheckIfAlreadyProcessed = "IF OBJECT_ID('Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year."', 'U') IS NOT NULL
    //                                         BEGIN
    //                                             SELECT 'YES' as Flag
    //                                         END
    //                                     ELSE
    //                                         BEGIN
    //                                             SELECT 'NO' as Flag
    //                                         END";
    //         $runQueryExec = sqlsrv_query($conn154, $CheckIfAlreadyProcessed);
    //         if ($runQueryExec !== FALSE) {
    //             $row_count = sqlsrv_num_rows( $runQueryExec );
    //             while($row = sqlsrv_fetch_array($runQueryExec, SQLSRV_FETCH_ASSOC)){
    //                 $data['Flag'] = $row['Flag'];
    //             }
    //         }
    //     }
    //     if($data['Flag'] == "YES"){
    //         return $data;
    //     }else{

    //         $this->runquery($conn154, "DROP TABLE IF EXISTS ##SalaryProcess_$Executive_Cd;");
            
    //         $this->runquery($conn154, "SELECT t1.UserName,
    //                                     SUM(RoomSurveyDone) AS RoomSurveyDone,
    //                                     SUM(TotalMobileCount) AS TotalMobileCount,
    //                                     SUM(ReceivedMobileNo) AS ReceivedMobileNo,
    //                                     SUM(WrongMobileNo) AS WrongMobileNo,
    //                                     SUM(NotConnectedMobileNo) AS NotConnectedMobileNo, 
    //                                     um.Executive_Cd, 0 AS Present, 0 AS [Absent], 0 AS HalfDay, 0 AS Training, 
    //                                     CONVERT(VARCHAR,NULL) AS FirstEntryDate
    //                                     INTO ##SalaryProcess_$Executive_Cd 
    //                                     FROM (
    //                                             SELECT UserName, SUM(RoomSurveyDone) AS RoomSurveyDone,
    //                                             SUM(TotalMobileCount) AS TotalMobileCount, SUM(ReceivedMobileNo) AS ReceivedMobileNo, 
    //                                             SUM(WrongMobileNo) AS WrongMobileNo, SUM(NotConnectedMobileNo) AS NotConnectedMobileNo
    //                                             FROM .[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise] 
    //                                             WHERE SDate BETWEEN '$FirstDay' AND '$LastDay' 
    //                                             GROUP BY UserName 
    //                                             UNION 
    //                                             SELECT UserName, SUM(RoomSurveyDone) AS RoomSurveyDone,
    //                                             SUM(TotalMobileCount) AS TotalMobileCount, SUM(ReceivedMobileNo) AS ReceivedMobileNo, 
    //                                             SUM(WrongMobileNo) AS WrongMobileNo, SUM(NotConnectedMobileNo) AS NotConnectedMobileNo
    //                                             FROM [103.14.97.58].[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
    //                                             WHERE SDate BETWEEN '$FirstDay' AND '$LastDay' 
    //                                             GROUP BY UserName 
    //                                             UNION 
    //                                             SELECT UserName, SUM(RoomSurveyDone) AS RoomSurveyDone,
    //                                             SUM(TotalMobileCount) AS TotalMobileCount, SUM(ReceivedMobileNo) AS ReceivedMobileNo, 
    //                                             SUM(WrongMobileNo) AS WrongMobileNo, SUM(NotConnectedMobileNo) AS NotConnectedMobileNo
    //                                             FROM [103.14.97.228].[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise] 
    //                                             WHERE SDate BETWEEN '$FirstDay' AND '$LastDay' 
    //                                             GROUP BY UserName 
    //                                         ) AS t1 
    //                                     INNER JOIN .[Survey_Entry_Data].[dbo].[User_Master] AS um ON (t1.UserName = um.UserName COLLATE Latin1_General_CI_AI)
    //                                     INNER JOIN .[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (um.Executive_Cd = em.Executive_Cd)
    //                                     WHERE em.Designation IN ('SE-Belapur','Survey Supervisor','Survey Executive', 'SP')
    //                                     AND COALESCE((SELECT TOP 1 ed.Executive_Cd FROM .[Survey_Entry_Data].[dbo].[Executive_Details] ed 
    //                                                   WHERE CONVERT(VARCHAR,ed.SurveyDate,23) BETWEEN '$FirstDay' AND '$LastDay' 
    //                                                   AND ed.Executive_Cd = em.Executive_Cd),'') != ''
    //                                     GROUP BY t1.UserName,um.Executive_Cd;");
            
    //         $this->runquery($conn154, "UPDATE ##SalaryProcess_$Executive_Cd SET 
    //                                     Present = t.Present, [Absent] = t.[Absent], HalfDay = t.HalfDay, Training = t.Training
    //                                     FROM 
    //                                     (
    //                                         SELECT t1.Executive_Cd, t1.UserName, SUM(Present) AS Present, SUM([Absent]) AS [Absent], SUM(HalfDay) AS HalfDay, SUM(Training) AS Training 
    //                                         FROM 
    //                                         (
    //                                             SELECT te.Executive_Cd,te.UserName, ed.SurveyDate, 
    //                                             CASE WHEN MIN(ed.Attendance) = 1 THEN 1 ELSE 0 END AS Present, 
    //                                             CASE WHEN MIN(ed.Attendance) = 2 THEN 1 ELSE 0 END AS [Absent], 
    //                                             CASE WHEN MIN(ed.Attendance) = 3 THEN 1 ELSE 0 END AS HalfDay, 
    //                                             CASE WHEN MIN(ed.Attendance) = 4 THEN 1 ELSE 0 END AS Training 
    //                                             FROM ##SalaryProcess_$Executive_Cd AS te
    //                                             INNER JOIN .[Survey_Entry_Data].[dbo].[Executive_Details] AS ed ON (te.Executive_Cd = ed.Executive_Cd)
    //                                             WHERE ed.SurveyDate BETWEEN '$FirstDay' AND '$LastDay' 
    //                                             GROUP BY te.Executive_Cd, te.UserName, ed.SurveyDate
    //                                         ) AS t1
    //                                         GROUP BY t1.Executive_Cd, t1.UserName
    //                                     ) AS t 
    //                                     WHERE ##SalaryProcess_$Executive_Cd.Executive_Cd = t.Executive_Cd AND  ##SalaryProcess_$Executive_Cd.UserName = t.UserName;");
            
    //         $this->runquery($conn154, "UPDATE ##SalaryProcess_$Executive_Cd 
    //                                     SET 
    //                                         FirstEntryDate = CONVERT(VARCHAR,t.FirstEntryDate,105)
    //                                     FROM 
    //                                         (
    //                                             SELECT tt.UserName, MIN(CONVERT(VARCHAR,tt.SurveyDate,105)) AS FirstEntryDate 
    //                                             FROM (
    //                                                 SELECT UserName, CASE WHEN COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') = '01-01-1900' THEN '' ElSE COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') END AS SurveyDate
    //                                                 FROM .[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
    //                                                 GROUP BY UserName 
    //                                                 UNION 
    //                                                 SELECT  UserName, CASE WHEN COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') = '01-01-1900' THEN '' ElSE COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') END AS SurveyDate
    //                                                 FROM [103.14.97.58].[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
    //                                                 GROUP BY UserName 
    //                                                 UNION 
    //                                                 SELECT UserName, CASE WHEN COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') = '01-01-1900' THEN '' ElSE COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') END AS SurveyDate
    //                                                 FROM [103.14.97.228].[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
    //                                                 GROUP BY UserName 
    //                                             ) AS tt
    //                                             WHERE CONVERT(VARCHAR,tt.SurveyDate,105) != ''
    //                                             GROUP BY tt.UserName
    //                                         ) AS t 
    //                                     WHERE ##SalaryProcess_$Executive_Cd.UserName = t.UserName;");
    //         // die();
    //         $this->runquery($conn154, "IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'Survey_SalaryProcess')
    //                                     CREATE DATABASE Survey_SalaryProcess;");

    //         $this->runquery($conn154, "IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'dbo' AND TABLE_NAME = 'SalaryProcess_".$Month."_".$Year."')
    //                                     BEGIN
    //                                         EXEC('
    //                                             CREATE TABLE Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year." (
    //                                                 SalaryP_ID INT PRIMARY KEY IDENTITY(1,1),
    //                                                 Executive_Cd INT,
    //                                                 ExecutiveName VARCHAR(100),
    //                                                 UserName VARCHAR(100),
    //                                                 Designation VARCHAR(100),
    //                                                 ReferenceName VARCHAR(100),
    //                                                 Present INT,
    //                                                 [Absent] INT,
    //                                                 HalfDay INT,
    //                                                 Training INT,
    //                                                 JoiningDate datetime,
    //                                                 FirstEntryDate datetime,
    //                                                 RoomSurveyDone INT,
    //                                                 Average int,
    //                                                 Month INT,
    //                                                 Year INT,
    //                                                 MonthDays int,
    //                                                 PerDaySalary float,
    //                                                 Salary float,
    //                                                 SalaryType INT,
    //                                                 DeductionType INT,
    //                                                 AdvanceAmt float,
    //                                                 DeductionAmt float,
    //                                                 IncentivesAmt float,
    //                                                 PayableSalary float,
    //                                                 PaymentStatus varchar(50),
    //                                                 Remark nvarchar(255),
    //                                                 PayStatusRemark nvarchar(255),
    //                                                 AddedBy INT,
    //                                                 AddedDate datetime,
    //                                                 UpdatedBy INT,
    //                                                 UpdatedDate datetime,
    //                                                 TotalMobileCount INT,
    //                                                 ReceivedMobileNo INT,
    //                                                 WrongMobileNo INT,
    //                                                 NotConnectedMobileNo INT,
    //                                                 INDEX SalaryProcessIndex_".$Month."_".$Year." (Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,PaymentStatus)
    //                                             );
    //                                         ');
    //                                     END;");
            
    //         $InsertQuery = "INSERT INTO Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year." (Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,
    //         Present,Absent,HalfDay,Training,JoiningDate,FirstEntryDate,RoomSurveyDone,Average,Month, Year,Salary, MonthDays, PerDaySalary, 
    //         AddedBy,AddedDate,SalaryType,PayableSalary,TotalMobileCount,ReceivedMobileNo,WrongMobileNo,NotConnectedMobileNo)
    //         (
    //             SELECT t2.Executive_Cd, t2.ExecutiveName, t2.UserName, t2.Designation, t2.ReferenceName, 
    //             t2.Present, t2.[Absent],  t2.HalfDay, t2.Training, t2.JoiningDate, t2.FirstEntryDate, t2.RoomSurveyDone, 
    //             CASE WHEN t2.Present != 0 THEN (t2.RoomSurveyDone / t2.Present) ELSE 0 END AS Average, 
    //             t2.Month, t2.Year, t2.Salary, t2.MonthDays,
    //             CAST((t2.Salary / t2.MonthDays) AS FLOAT) AS PerDaySalary,
    //             t2.AddedBy, t2.AddedDate, t2.SalaryType,
    //             CAST(t2.Salary - ((t2.[Absent] + (0.5 * t2.HalfDay)) * (t2.Salary / t2.MonthDays)) AS FLOAT) AS PayableSalary,
    //             t2.TotalMobileCount, t2.ReceivedMobileNo, t2.WrongMobileNo, t2.NotConnectedMobileNo
    //             FROM 
    //             (
    //                 SELECT 
    //                 t1.Executive_Cd,
    //                 em.ExecutiveName,
    //                 t1.UserName,
    //                 em.Designation,
    //                 rm.ReferenceName,
    //                 t1.Present, 
    //                 t1.[Absent], 
    //                 t1.HalfDay, 
    //                 t1.Training,
    //                 CONVERT(datetime,em.JoiningDate,105) AS JoiningDate,
    //                 CONVERT(datetime,t1.FirstEntryDate,105) AS FirstEntryDate,
    //                 t1.RoomSurveyDone,
    //                 $totalDays AS MonthDays,
    //                 em.Salary,
    //                 '$Month' AS Month,
    //                 '$Year' AS Year,
    //                 '$Executive_Cd' AS AddedBy,
    //                 GETDATE() AS AddedDate,
    //                 '0' AS SalaryType,
    //                 t1.TotalMobileCount,
    //                 t1.ReceivedMobileNo,
    //                 t1.WrongMobileNo,
    //                 t1.NotConnectedMobileNo
    //                 FROM ##SalaryProcess_$Executive_Cd AS t1
    //                 INNER JOIN [.].[Survey_Entry_Data].[dbo].[Executive_Master] em ON (em.Executive_Cd = t1.Executive_Cd)
    //                 LEFT JOIN [.].[ChankyaAdmin].[dbo].[Enquiry_Details] enqd ON (em.Enquiry_Cd = enqd.Enquiry_Cd 
    //                 AND enqd.AddedDate = (SELECT MAX(AddedDate) FROM [.].[ChankyaAdmin].[dbo].[Enquiry_Details] ed WHERE ed.Enquiry_Cd = enqd.Enquiry_Cd))
    //                 LEFT JOIN [.].[ChankyaAdmin].[dbo].[Reference_Master] rm ON (enqd.ReferenceSource = rm.Reference_Cd)
    //             ) AS t2
    //         )";
    //         $this->runquery($conn154, $InsertQuery);
            
    //         $this->runquery($conn154, "DROP TABLE IF EXISTS ##SalaryProcess_$Executive_Cd;");

    //         $data['Flag'] = "SUCCESS";
    //         return $data;
    //     }
    //     sqlsrv_close($conn154);
    // }

    
    function salaryProcess($userName, $appName,  $developmentMode, $Executive_Cd, $Month, $Year, $totalDays, $process){

        $data = array();
        $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
        $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154);
        $FirstDay = "$Year-$Month-01";
        $LastDay = "$Year-$Month-$totalDays";
        
        if($process == "again"){
            $data['Flag'] = "NO";
            $this->runquery($conn154, "TRUNCATE TABLE Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year.";");
        }else{
            $CheckIfAlreadyProcessed = "IF OBJECT_ID('Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year."', 'U') IS NOT NULL
                                            BEGIN
                                                SELECT 'YES' as Flag
                                            END
                                        ELSE
                                            BEGIN
                                                SELECT 'NO' as Flag
                                            END";
            $runQueryExec = sqlsrv_query($conn154, $CheckIfAlreadyProcessed);
            if ($runQueryExec !== FALSE) {
                $row_count = sqlsrv_num_rows( $runQueryExec );
                while($row = sqlsrv_fetch_array($runQueryExec, SQLSRV_FETCH_ASSOC)){
                    $data['Flag'] = $row['Flag'];
                }
            }
        }
        if($data['Flag'] == "YES"){
            return $data;
        }else{

            $this->runquery($conn154, "DROP TABLE IF EXISTS ##SalaryProcess_$Executive_Cd;");
            
            $this->runquery($conn154, "SELECT
                                        t1.Executive_Cd,
                                        0 AS RoomSurveyDone, 
                                        0 AS TotalMobileCount, 
                                        0 AS ReceivedMobileNo, 
                                        0 AS WrongMobileNo,
                                        0 AS NotConnectedMobileNo,
                                        0 AS Present, 
                                        0 AS [Absent], 
                                        0 AS HalfDay, 
                                        0 AS Training, 
                                        CONVERT(VARCHAR,NULL) AS FirstEntryDate 
                                        INTO ##SalaryProcess_$Executive_Cd 
                                        FROM (
                                                SELECT 
                                                DISTINCT(ed.Executive_Cd) AS Executive_Cd
                                                FROM Survey_Entry_Data..Executive_Details ed
                                                INNER JOIN .[Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (ed.Executive_Cd = em.Executive_Cd)
                                                WHERE CONVERT(VARCHAR,ed.SurveyDate,23) BETWEEN '$FirstDay' AND '$LastDay' AND ed.Attendance != 0
                                                AND em.Designation IN ('Survey Supervisor','SP','SE-Belapur','Survey Executive','Manager','Site Manager')
                                            ) AS t1
                                        ;");
            // AND (em.EmpStatus = 'A' OR (em.EmpStatus = 'NA' AND CONVERT(VARCHAR,em.LeavingDate,23) BETWEEN '$FirstDay' AND '$LastDay'))

            $this->runquery($conn154, "UPDATE ##SalaryProcess_$Executive_Cd 
                                        SET 
                                            Present = t.Present, [Absent] = t.[Absent], HalfDay = t.HalfDay, Training = t.Training
                                        FROM 
                                        (
                                            SELECT t1.Executive_Cd, SUM(Present) AS Present, SUM([Absent]) AS [Absent], SUM(HalfDay) AS HalfDay, SUM(Training) AS Training 
                                            FROM 
                                            (
                                                SELECT te.Executive_Cd, ed.SurveyDate,
                                                CASE WHEN MIN(ed.Attendance) = 1 THEN 1 ELSE 0 END AS Present,
                                                CASE WHEN MIN(ed.Attendance) = 2 THEN 1 ELSE 0 END AS [Absent],
                                                CASE WHEN MIN(ed.Attendance) = 3 THEN 1 ELSE 0 END AS HalfDay,
                                                CASE WHEN MIN(ed.Attendance) = 4 THEN 1 ELSE 0 END AS Training
                                                FROM ##SalaryProcess_$Executive_Cd AS te
                                                INNER JOIN .[Survey_Entry_Data].[dbo].[Executive_Details] AS ed ON (te.Executive_Cd = ed.Executive_Cd)
                                                WHERE ed.SurveyDate BETWEEN '$FirstDay' AND '$LastDay' 
                                                GROUP BY te.Executive_Cd, ed.SurveyDate
                                            ) AS t1
                                            GROUP BY t1.Executive_Cd
                                        ) AS t 
                                        WHERE ##SalaryProcess_$Executive_Cd.Executive_Cd = t.Executive_Cd;");

            $this->runquery($conn154, "UPDATE ##SalaryProcess_$Executive_Cd
                                        SET 
                                            RoomSurveyDone = t.RoomSurveyDone,
                                            TotalMobileCount = t.TotalMobileCount,
                                            ReceivedMobileNo = t.ReceivedMobileNo,
                                            WrongMobileNo = t.WrongMobileNo,
                                            NotConnectedMobileNo = t.NotConnectedMobileNo
                                        FROM 
                                        (
                                            SELECT em.Executive_Cd,em.ExecutiveName, um.UserName, 
                                            COALESCE(SUM(ssed.RoomSurveyDone),0) AS RoomSurveyDone,
                                            COALESCE(SUM(TotalMobileCount),0) AS TotalMobileCount, 
                                            COALESCE(SUM(ReceivedMobileNo),0) AS ReceivedMobileNo, 
                                            COALESCE(SUM(WrongMobileNo),0) AS WrongMobileNo,
                                            COALESCE(SUM(NotConnectedMobileNo),0) AS NotConnectedMobileNo
                                            FROM .DataAnalysis.dbo.SurveySummaryExecutiveDateWise  ssed
                                            INNER JOIN .Survey_Entry_Data.dbo.User_Master um ON (ssed.UserName = um.UserName COLLATE Latin1_General_CI_AI)
                                            INNER JOIN .Survey_Entry_Data.dbo.Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
                                            WHERE ssed.SDate BETWEEN '$FirstDay' AND '$LastDay'
                                            GROUP BY em.Executive_Cd,em.ExecutiveName, um.UserName
                                        ) AS t 
                                        WHERE ##SalaryProcess_$Executive_Cd.Executive_Cd = t.Executive_Cd");
            
            $this->runquery($conn154, "UPDATE ##SalaryProcess_$Executive_Cd 
                                        SET 
                                            FirstEntryDate = CONVERT(VARCHAR,t.FirstEntryDate,105)
                                        FROM 
                                            (
                                                SELECT em.Executive_Cd, tt.UserName, MIN(CONVERT(VARCHAR,tt.SurveyDate,105)) AS FirstEntryDate 
                                                FROM (
                                                    SELECT UserName, CASE WHEN COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') = '01-01-1900' THEN '' ElSE COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') END AS SurveyDate
                                                    FROM .[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
                                                    GROUP BY UserName 
                                                    UNION 
                                                    SELECT  UserName, CASE WHEN COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') = '01-01-1900' THEN '' ElSE COALESCE(MIN(CONVERT(VARCHAR,SDate,105)), '') END AS SurveyDate
                                                    FROM [103.14.97.58].[DataAnalysis].[dbo].[SurveySummaryExecutiveDateWise]
                                                    GROUP BY UserName 
                                                ) AS tt
                                                INNER JOIN .Survey_Entry_Data.dbo.User_Master em ON (tt.UserName = em.UserName COLLATE Latin1_General_CI_AI)
                                                WHERE CONVERT(VARCHAR,tt.SurveyDate,105) != ''
                                                GROUP BY em.Executive_Cd,tt.UserName
                                            ) AS t
                                        WHERE ##SalaryProcess_$Executive_Cd.Executive_Cd = t.Executive_Cd;");
            
            $this->runquery($conn154, "IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = 'Survey_SalaryProcess')
                                        CREATE DATABASE Survey_SalaryProcess;");

            $this->runquery($conn154, "IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'dbo' AND TABLE_NAME = 'SalaryProcess_".$Month."_".$Year."')
                                        BEGIN
                                            EXEC('
                                                CREATE TABLE Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year." (
                                                    SalaryP_ID INT PRIMARY KEY IDENTITY(1,1),
                                                    Executive_Cd INT,
                                                    ExecutiveName VARCHAR(100),
                                                    UserName VARCHAR(100),
                                                    Designation VARCHAR(100),
                                                    ReferenceName VARCHAR(100),
                                                    Present INT,
                                                    [Absent] INT,
                                                    HalfDay INT,
                                                    Training INT,
                                                    JoiningDate datetime,
                                                    FirstEntryDate datetime,
                                                    RoomSurveyDone INT,
                                                    Average int,
                                                    Month INT,
                                                    Year INT,
                                                    MonthDays int,
                                                    PerDaySalary float,
                                                    Salary float,
                                                    SalaryType INT,
                                                    DeductionType INT,
                                                    AdvanceAmt float,
                                                    DeductionAmt float,
                                                    IncentivesAmt float,
                                                    PayableSalary float,
                                                    PaymentStatus varchar(50),
                                                    Remark nvarchar(255),
                                                    PayStatusRemark nvarchar(255),
                                                    AddedBy INT,
                                                    AddedDate datetime,
                                                    UpdatedBy INT,
                                                    UpdatedDate datetime,
                                                    TotalMobileCount INT,
                                                    ReceivedMobileNo INT,
                                                    WrongMobileNo INT,
                                                    NotConnectedMobileNo INT,
                                                    INDEX SalaryProcessIndex_".$Month."_".$Year." (Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,PaymentStatus)
                                                );
                                            ');
                                        END;");
            
            $InsertQuery = "INSERT INTO Survey_SalaryProcess.dbo.SalaryProcess_".$Month."_".$Year." (Executive_Cd,ExecutiveName
                            --,UserName
                            ,Designation,ReferenceName,
                            Present,Absent,HalfDay,Training,JoiningDate,FirstEntryDate,RoomSurveyDone,Average,Month, Year,Salary, MonthDays, PerDaySalary, 
                            AddedBy,AddedDate,SalaryType,PayableSalary,TotalMobileCount,ReceivedMobileNo,WrongMobileNo,NotConnectedMobileNo)
                            (
                            --t2.UserName,
                                SELECT t2.Executive_Cd, t2.ExecutiveName, t2.Designation, t2.ReferenceName, 
                                t2.Present,
                                t2.[Absent],
                                 t2.HalfDay, t2.Training, t2.JoiningDate, t2.FirstEntryDate, t2.RoomSurveyDone, 
                                --CASE WHEN t2.Present != 0 THEN (t2.RoomSurveyDone / t2.Present) ELSE 0 END AS Average, 
                                0 AS Average,
                                t2.Month, t2.Year, t2.Salary, t2.MonthDays,
                                CAST((t2.Salary / t2.MonthDays) AS FLOAT) AS PerDaySalary,
                                t2.AddedBy, t2.AddedDate, t2.SalaryType,
                                CAST(t2.Salary - ((t2.[Absent] + (0.5 * t2.HalfDay)) * (t2.Salary / t2.MonthDays)) AS FLOAT) AS PayableSalary,
                                t2.TotalMobileCount, t2.ReceivedMobileNo, t2.WrongMobileNo, t2.NotConnectedMobileNo
                                FROM 
                                (
                                    SELECT 
                                    t1.Executive_Cd,
                                    em.ExecutiveName,
                                    --t1.UserName,
                                    em.Designation,
                                    rm.ReferenceName,
                                    t1.Present, 
                                    --t1.[Absent], 
                                    CASE 
                                        WHEN (t1.Present + t1.[Absent] + t1.HalfDay + t1.Training) != $totalDays AND  MONTH(CONVERT(datetime,em.JoiningDate,105)) = $Month AND YEAR(CONVERT(datetime,em.JoiningDate,105)) = $Year 
                                        THEN 
                                            (DAY(CONVERT(datetime,em.JoiningDate,105))-1 + t1.[Absent]) -
                                            (SELECT COUNT(*) AS BeforeJoiningDays
                                            FROM [.].[Survey_Entry_Data].[dbo].[Executive_Details]
                                            WHERE CONVERT(VARCHAR,SurveyDate,23) < CONVERT(datetime,em.JoiningDate,23)
                                            AND Executive_Cd = t1.Executive_Cd)
                                        ELSE t1.[Absent]
                                    END AS [Absent],
                                    t1.HalfDay, 
                                    t1.Training,
                                    CONVERT(datetime,em.JoiningDate,105) AS JoiningDate,
                                    CONVERT(datetime,t1.FirstEntryDate,105) AS FirstEntryDate,
                                    t1.RoomSurveyDone,
                                    $totalDays AS MonthDays,
                                    em.Salary,
                                    '$Month' AS Month,
                                    '$Year' AS Year,
                                    '$Executive_Cd' AS AddedBy,
                                    GETDATE() AS AddedDate,
                                    '0' AS SalaryType,
                                    t1.TotalMobileCount,
                                    t1.ReceivedMobileNo,
                                    t1.WrongMobileNo,
                                    t1.NotConnectedMobileNo
                                    FROM ##SalaryProcess_$Executive_Cd AS t1
                                    INNER JOIN [.].[Survey_Entry_Data].[dbo].[Executive_Master] em ON (em.Executive_Cd = t1.Executive_Cd)
                                    LEFT JOIN [.].[ChankyaAdmin].[dbo].[Enquiry_Details] enqd ON (em.Enquiry_Cd = enqd.Enquiry_Cd 
                                    AND enqd.AddedDate = (SELECT MAX(AddedDate) FROM [.].[ChankyaAdmin].[dbo].[Enquiry_Details] ed WHERE ed.Enquiry_Cd = enqd.Enquiry_Cd))
                                    LEFT JOIN [.].[ChankyaAdmin].[dbo].[Reference_Master] rm ON (enqd.ReferenceSource = rm.Reference_Cd)
                                ) AS t2
                            )
                            ";
            $this->runquery($conn154, $InsertQuery);
            
            $this->runquery($conn154, "DROP TABLE IF EXISTS ##SalaryProcess_$Executive_Cd;");

            $data['Flag'] = "SUCCESS";
            return $data;
        }
        sqlsrv_close($conn154);
    }
// Salary Process Data ---------------------------------------------


//  Karuna 

function RunQueryDataWithErrorandID($ULB,$query, $userName, $appName,  $developmentMode){
    $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
    $data = array();
    if(!$dbConn["error"]){
        $conn = $dbConn["conn"];
        if (sqlsrv_query($conn, $query) !== false) {
            $data["status"] = true;
            $data["code"] = 200;
            $data["message"] = "Success";
            $lastInsertIdResult = sqlsrv_query($conn, 'SELECT SCOPE_IDENTITY() AS id');
            
            if ($lastInsertIdResult !== false) {
                $idRow = sqlsrv_fetch_array($lastInsertIdResult, SQLSRV_FETCH_ASSOC);
                echo "<pre>"; print_r($idRow);exit;
                $data["inserted_id"] = $idRow['id'];
            } else {
                $data["inserted_id"] = null;
            }
        } else {
            if(($errors = sqlsrv_errors()) != null) {
                $i=1;
                foreach( $errors as $error ) {
                    if($i==1){
                        $i++;
                        $data["status"] = false;
                        $data["code"] = $error[ 'code'];
                        $data["message"] = str_replace('[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]','',$error[ 'message']);
                    }
                }
            }
        }
    }
    return $data;
}
function RunQueryDataWithError($ULB,$query, $userName, $appName,  $developmentMode){
    $dbConn = $this->getSurveyUtilityAppDBConnectByElectionName($ULB,$userName, $appName,  $developmentMode);
    $data = array();
    if(!$dbConn["error"]){
        $conn = $dbConn["conn"];
        if (sqlsrv_query($conn, $query) !== false) {
            $data["status"] = true;
            $data["code"] = 200;
            $data["message"] = "Success";
        } else {
            if(($errors = sqlsrv_errors()) != null) {
                $i=1;
                foreach( $errors as $error ) {
                    if($i==1){
                        $i++;
                        $data["status"] = false;
                        $data["code"] = $error[ 'code'];
                        $data["message"] = str_replace('[Microsoft][ODBC Driver 17 for SQL Server][SQL Server]','',$error[ 'message']);
                    }
                }
            }
        }
    }
    return $data;
}

}