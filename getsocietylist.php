<?php 
ini_set('memory_limit', '1G');

session_start();
include 'api/includes/DbOperation.php'; 

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];


$tbodyString = "";
$cond1 = "";
// echo "<pre>"; print_r($_POST);exit;
if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    // echo "<pre>"; print_r($_POST);exit;
    if(isset($_POST['DBName']) && !empty($_POST['DBName'])){
        $DBName = $_POST['DBName'];
        if(isset($_POST['task']) && !empty($_POST['task'])){
            $task = $_POST['task'];
            switch($task){
    
                case 'fetchSocietyList':
                    $db1 = new DbOperation();
                    $EleName = $_POST['EleName'];
                    $query = "SELECT Society_Cd,SocietyName  FROM Survey_Entry_Data..Society_Master
                    WHERE ElectionName = '$EleName'";
                    $searchTerm = $_POST['search'];
                    if (!empty($searchTerm)) {
                        $searchTerm = "%$searchTerm%"; 
                        $query .= " AND SocietyName LIKE '$searchTerm'";
                    }
                    // echo $query;
                    $societyData =  $db1->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);
                    echo json_encode($societyData);
                break;

                case 'votersList':
                    $db2 = new DbOperation();
                    $Data = array();
                    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
                    $start = isset($_POST["start"]) ? intval($_POST["start"]) : 0;
                    $rowperpage = isset($_POST["length"]) ? intval($_POST["length"]) : 10;
                    $columnIndex_arr = isset($_POST['order']) ? $_POST['order'] : [];
                    $columnName_arr = isset($_POST['columns']) ? $_POST['columns'] : [];
                    $order_arr = isset($_POST['order']) ? $_POST['order'] : [];
                    $search_arr = isset($_POST['search']) ? $_POST['search'] : [];
                    $columnIndex = isset($columnIndex_arr[0]['column']) ? intval($columnIndex_arr[0]['column']) : 0; 
                    $columnName = isset($columnName_arr[$columnIndex]['data']) ? $columnName_arr[$columnIndex]['data'] : 'Voter_Cd';
                    
                    $FirstName = isset($_POST["FirstName"]) ? $_POST["FirstName"] : '';
                    $FirstName = strtolower($FirstName);
                    $MiddleName = isset($_POST["MiddleName"]) ? $_POST["MiddleName"] : '';
                    $MiddleName = strtolower($MiddleName);
                    $LastName = isset($_POST["LastName"]) ? $_POST["LastName"] : '';
                    $LastName = strtolower($LastName);
                    
                    $MobileNo = isset($_POST["MobileNo"]) ? $_POST["MobileNo"] : '';
                    $societyName = isset($_POST["societyName"]) ? $_POST["societyName"] : '';
                    $IdCard_No = isset($_POST["IdCard_No"]) ? $_POST["IdCard_No"] : '';

                 


                    if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)  && !empty($IdCard_No) && !empty($MobileNo)){
                        $Cond = " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'  AND IdCard_No ='$IdCard_No' AND MobileNo = $MobileNo";
                    }else if(!empty($FirstName) && !empty($MiddleName) && empty($LastName)){
                        $Cond = " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%'";
                    }else if(!empty($FirstName) && empty($MiddleName) && !empty($LastName)){
                        $Cond = " WHERE Name LIKE '$FirstName%' AND Surname LIKE '$LastName%'";
                    }else if(empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                        $Cond = " WHERE MiddleName LIKE '$MiddleName%' AND Surname LIKE '$LastName%'";
                    }else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                        $Cond = " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%' ";
                        if(!empty($MobileNo)){
                            $Cond .= " AND MobileNo = '$MobileNo'";
                        }
                    }else if(!empty($IdCard_No)){
                        $Cond = " WHERE IdCard_No ='$IdCard_No'";
                    }else if(!empty($MobileNo)){
                        $Cond = " WHERE MobileNo = '$MobileNo'";
                    }else{
                        $Cond = " ";
                    }
                    $Query = "SELECT tb.Voter_Cd as Voter_Cd, tb.Voter_Id as Voter_Id,tb.List_No as List_No,tb.Age as Age,tb.Sex as Sex,
                            CONVERT(varchar,tb.BirthDate,23)as BirthDate,tb.FamilyNo as FamilyNo,tb.SubLocation_Cd as SubLocation_Cd,tb.FullName as FullName,tb.Name as Name,
                            tb.MiddleName as MiddleName,tb.Surname as Surname,tb.Ac_No as Ac_No,tb.RoomNo as RoomNo,tb.Ward_no as Ward_no,
                            tb.MobileNo as MobileNo,tb.SF as SF,tb.SocietyName as SocietyName,tb.IdCard_No as IdCard_No,Sector,Survey_Society_Cd,type 
                            FROM (
                                SELECT Voter_Cd,Voter_Id,List_No,Age,Sex,BirthDate,FamilyNo,SubLocation_Cd,FullName,Name,MiddleName,Surname,Ac_No,RoomNo,Ward_no,MobileNo,SF,SocietyName,IdCard_No,Sector,Survey_Society_Cd,'voter' as type
                                FROM $DBName..Dw_VotersInfo 
                                UNION 
                                SELECT  Voter_Cd,Voter_Id,List_No,Age,Sex,Birthdate as BirthDate,FamilyNo,Subloc_cd as SubLocation_Cd,Fullname as FullName,Name,Middlename as MiddleName,Surname,Ac_No,Roomno as RoomNo,Ward_No as Ward_no,Mobileno as MobileNo,1 as SF,Societyname as SocietyName, '' as IdCard_No,'' as Sector,Survey_Society_Cd,'nonvoter' as type
                                FROM $DBName..NewVoterRegistration ) as tb
                            $Cond
                            ORDER BY tb.Voter_Id DESC ";
                    $Data = $db2->ExecutveQueryMultipleRowSALData($ULB,$Query,  $userName, $appName, $developmentMode);

                    $TotalQuery = "SELECT COUNT(*) AS total FROM 
                                    (SELECT Voter_Id,List_No,Age,FamilyNo,SubLocation_Cd,FullName,Name,MiddleName,Surname,Ac_No,RoomNo,Ward_no,MobileNo,SF,SocietyName,IdCard_No
                                    FROM $DBName..Dw_VotersInfo 
                                    UNION 
                                    SELECT  Voter_Id,List_No,Age,FamilyNo,Subloc_cd as SubLocation_Cd,Fullname as FullName,Name,Middlename as MiddleName,Surname,Ac_No,Roomno as RoomNo,Ward_No,Mobileno as MobileNo,1 as SF,Societyname as SocietyName, '' as IdCard_No
                                    FROM $DBName..NewVoterRegistration ) as tb
                                    $Cond"; 
                    $TotalRecordsQuery = $db2->ExecutveQueryMultipleRowSALData($ULB,$TotalQuery,  $userName, $appName, $developmentMode);
                    $TotalRecords = $TotalRecordsQuery[0]['total'];
                //     if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName) && !empty($societyName) && !empty($IdCard_No) && !empty($MobileNo)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $VotersQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%' AND SubLocation_Cd = $societyName AND IdCard_No ='$IdCard_No' AND MobileNo = $MobileNo";
                //     }else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $VotersQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //         if(!empty($MobileNo)){
                //             $VotersQuery .= " AND MobileNo = '$MobileNo'";
                //         }else if(!empty($IdCard_No)){
                //             $VotersQuery .= " AND IdCard_No ='$IdCard_No'";
                //         }else if(!empty($societyName)){
                //             $VotersQuery .= " SubLocation_Cd = $societyName";
                //         }
                //     }else if(!empty($IdCard_No)){
                //         $VotersQuery .= " WHERE IdCard_No ='$IdCard_No'";
                       
                //     }else if(!empty($MobileNo)){
                //         $VotersQuery .= " WHERE MobileNo = '$MobileNo'";
                //         if(!empty($IdCard_No)){
                //             $VotersQuery .= " AND IdCard_No ='$IdCard_No'";
                //         }else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //             $FirstName = substr($FirstName, 0, 3);
                //             $MiddleName = substr($MiddleName, 0, 3);
                //             $LastName = substr($LastName, 0, 3);
                //             $VotersQuery .= " AND Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //         }else if(!empty($societyName)){
                //             $VotersQuery .= " AND SubLocation_Cd = $societyName";
                //         }
                //     }
                   
                //     $VotersQuery .= " ORDER BY Voter_Cd DESC";
                //     $VotersQuery .= " OFFSET " . intval($start) . " ROWS FETCH NEXT " . intval($rowperpage) . " ROWS ONLY";
                    
                //     print_r($VotersQuery);
                //     $VotersData = $db2->ExecutveQueryMultipleRowSALData($VotersQuery,  $userName, $appName, $developmentMode);

                //     $TotalQuery = "SELECT COUNT(*) AS total FROM $DBName..Dw_VotersInfo";

                //    if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName) && !empty($societyName) && !empty($IdCard_No) && !empty($MobileNo)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $TotalQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%' AND SubLocation_Cd = $societyName AND IdCard_No ='$IdCard_No' AND MobileNo = $MobileNo";
                //     } else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $TotalQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //     } else if(!empty($IdCard_No)){
                //         $TotalQuery .= " WHERE IdCard_No ='$IdCard_No'";
                //     }else if(!empty($MobileNo)){
                //         $TotalQuery .= " WHERE MobileNo = '$MobileNo'";
                //     }
                //     $TotalVotersRecords = $db2->ExecutveQueryMultipleRowSALData($TotalQuery,  $userName, $appName, $developmentMode);
                //     $TotalVotersRecords = $TotalVotersRecords[0]['total'];
                    
                    
                //     $NonVotersQuery = "SELECT *, Fullname as FullName, Mobileno as MobileNo, Roomno as RoomNo, Societyname as SocietyName, Birthdate as BirthDate FROM $DBName..NewVoterRegistration";
                    
                //     if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName) && !empty($societyName) && !empty($IdCard_No) && !empty($MobileNo)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $NonVotersQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%' AND Subloc_cd = $societyName AND IdCard_No ='$IdCard_No' AND MobileNo = $MobileNo";
                //     }else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $NonVotersQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //         if(!empty($MobileNo)){
                //             $NonVotersQuery .= " AND MobileNo = '$MobileNo'";
                //         }else if(!empty($IdCard_No)){
                //             $NonVotersQuery .= " AND IdCard_No ='$IdCard_No'";
                //         }else if(!empty($societyName)){
                //             $NonVotersQuery .= " Subloc_cd = $societyName";
                //         }
                //     }else if(!empty($MobileNo)){
                //         $NonVotersQuery .= " WHERE MobileNo = '$MobileNo'";
                //         if(!empty($IdCard_No)){
                //             $NonVotersQuery .= " AND IdCard_No ='$IdCard_No'";
                //         }else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //             $FirstName = substr($FirstName, 0, 3);
                //             $MiddleName = substr($MiddleName, 0, 3);
                //             $LastName = substr($LastName, 0, 3);
                //             $NonVotersQuery .= " AND Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //         }else if(!empty($societyName)){
                //             $NonVotersQuery .= " AND Subloc_cd = $societyName";
                //         }
                //     }
                   
                //     $NonVotersQuery .= " ORDER BY Voter_Cd DESC";
                //     $NonVotersQuery .= " OFFSET " . intval($start) . " ROWS FETCH NEXT " . intval($rowperpage) . " ROWS ONLY";
                    
                //     // print_r($NonVotersQuery);
                //     $NonVotersData = $db2->ExecutveQueryMultipleRowSALData($NonVotersQuery,  $userName, $appName, $developmentMode);

                //     $TotalNonQuery = "SELECT COUNT(*) AS total FROM $DBName..NewVoterRegistration";

                //    if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName) && !empty($societyName) && !empty($IdCard_No) && !empty($MobileNo)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $TotalNonQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%' AND SubLocation_Cd = $societyName AND IdCard_No ='$IdCard_No' AND MobileNo = $MobileNo";
                //     } else if(!empty($FirstName) && !empty($MiddleName) && !empty($LastName)){
                //         $FirstName = substr($FirstName, 0, 3);
                //         $MiddleName = substr($MiddleName, 0, 3);
                //         $LastName = substr($LastName, 0, 3);
                //         $TotalNonQuery .= " WHERE Name LIKE '$FirstName%' AND MiddleName LIKE '$MiddleName%' AND Surname LIKE'$LastName%'";
                //     } else if(!empty($MobileNo)){
                //         $TotalNonQuery .= " WHERE MobileNo = '$MobileNo'";
                //     }
                //     $TotalNonVotersRecords = $db2->ExecutveQueryMultipleRowSALData($TotalNonQuery,  $userName, $appName, $developmentMode);
                //     $TotalNonVotersRecords = $TotalNonVotersRecords[0]['total'];


                //     $Data  = array_merge($NonVotersData, $VotersData);
                //    if(!empty($IdCard_No) && empty($FirstName) && empty($MiddleName) && empty($LastName) && empty($societyName) && empty($IdCard_No) && empty($MobileNo)){
                //     $TotalRecords =$TotalVotersRecords;
                //    }else{

                //        $TotalRecords = $TotalVotersRecords + $TotalNonVotersRecords;
                //    }

                    // Prepare response
                    $response = [
                        "draw" => intval($draw),
                        "recordsTotal" => intval($TotalRecords),
                        "recordsFiltered" => intval($TotalRecords),
                        "data" => $Data
                    ];
                    echo json_encode($response);
                break;

                case 'addUpdateVoter':
                    // echo "<pre>";print_r($_POST);exit;
                    $db3 = new DbOperation();
                    $data = array();
                    $VoterCd = $_POST['VoterCd'];
                    $list_no = $_POST['list_no'];
                    $age = $_POST['age'];
                    $room_no = $_POST['room_no'];
                    $sector = $_POST['sector'];
                    $Ward_no = $_POST['Ward_no'];
                    $mobile_no = $_POST['mobile_no'];
                    $IdCard_No = $_POST['IdCard_No'];
                    $MiddleName = $_POST['MiddleName'];
                    $LastName = $_POST['LastName'];
                    $first_name = $_POST['first_name'];
                    $Survey_Society_Cd = $_POST['Sublocation_Cd'];
                    $SocietyNew = $_POST['SocietyNew'];
        
                    $Ac_No = $_POST['Ac_No'];
                    $VoterCds = $_POST['VoterCds'];
                    $type = $_POST['type'];
 
                    if(!empty($MiddleName)){
                        $FullName = $first_name." ".$MiddleName." ".$LastName;
                    }else{
                        $FullName = $first_name." ".$LastName;
                    }

                    if(!empty($Survey_Society_Cd) && ($Survey_Society_Cd != '00') ){
                        $getSocietyQuery = "SELECT * FROM $DBName..SubLocationMaster WHERE Survey_Society_Cd = $Survey_Society_Cd";
                        $getSociety = $db3->ExecutveQueryMultipleRowSALData($ULB,$getSocietyQuery,  $userName, $appName, $developmentMode);
                        $Sublocation_Cd = $getSociety[0]['SubLocation_Cd'];
                        $SocietyName = $getSociety[0]['SocietyName'];
                        $SocietyNameM = $getSociety[0]['SocietyNameM'];
                    }else  if(!empty($Survey_Society_Cd) && ($Survey_Society_Cd == '00') ){
                        $MaxCd ="SELECT (MAX(SubLocation_Cd) + 1) AS SubLocation_Cd FROM $DBName..SubLocationMaster";
                        $getMaxCd = $db3->ExecutveQuerySingleRowSALData($ULB,$MaxCd,  $userName, $appName, $developmentMode);
                        $Subloc_Cd = $getMaxCd['SubLocation_Cd'];
                        $InsertSociety  =  "INSERT INTO $DBName..SubLocationMaster(SubLocation_Cd,SubLocationName,SocietyName,Sector,Ward_No,List_No)
                                            VALUES($Subloc_Cd, N'$SocietyNew', N'$SocietyNew', N'$sector', $Ward_no, $list_no)";
                        $insertNewSocietyData = $db3->RunQueryDataWithError($ULB,$InsertSociety,  $userName, $appName, $developmentMode);
                        if($insertNewSocietyData){
                            $Sublocation_Cd = $getMaxCd['SubLocation_Cd'];
                            $SocietyName = $SocietyNew;
                            $SocietyNameM = '';
                        }
                    }
                    
                    if(!empty($VoterCd)){ 
                      
                        $updateQuery = "";
                        if(!empty($Sublocation_Cd)){
                            $SocCond = ", Survey_Society_Cd = $Survey_Society_Cd, SubLocation_Cd = $Sublocation_Cd, SocietyName = '$SocietyName', SocietyNameM = N'$SocietyNameM'";
                            // $updateQuery .= ", SubLocation_Cd = $Sublocation_Cd, SocietyName = '$SocietyName', SocietyNameM = '$SocietyNameM'";
                        }else{
                            $SocCond = "";
                        }
                        if(!empty($MiddleName)){
                            $MnameCond = ", MiddleName = '$MiddleName'";
                            // $updateQuery .= ", MiddleName = '$MiddleName'";
                        }else{
                            $MnameCond = "";
                        }
                        if(!empty($Ward_no)){
                            $WardCond = ", Ward_no = $Ward_no";
                            // $updateQuery .= ", Ward_no = $Ward_no";
                        }else{
                            $WardCond = "";
                        }
                        if(!empty($sector)){
                            $secCond = ", Sector = '$sector'";
                            // $updateQuery .= ", Sector = $sector";
                        }else{
                            $secCond = "";
                        }
                        if(!empty($room_no)){
                            $RoomCond = ", RoomNo = '$room_no'";
                            // $updateQuery .= ", RoomNo = '$room_no'";
                        }else{
                            $RoomCond = ""; 
                        }
                        if(!empty($IdCard_No)){
                            $idCardCond = ", IdCard_No = '$IdCard_No'";
                            // $updateQuery .= ", IdCard_No = '$IdCard_No'";
                        }else{
                            $idCardCond = "";
                        }
                        if(!empty($age)){
                            $ageCond = ", Age = $age";
                            // $updateQuery .= ", Age = $age";
                        }else{
                            $ageCond = "";
                        }
                        if(!empty($mobile_no)){
                            $MobCond = ", MobileNo = $mobile_no";
                            // $updateQuery .= ", MobileNo = $mobile_no";
                        }else{
                            $MobCond = "";
                        }
                        if(!empty($list_no)){
                            $listCond = ", List_No = $list_no";
                            // $updateQuery .= ", List_No = $list_no";
                        }else{
                            $listCond = "";
                        }
                        If($type == 'voter'){
                            $updateQuery = "UPDATE $DBName..Dw_VotersInfo 
                                            SET 
                                                UpdatedStatus = 'Y',
                                                Name = '$first_name', 
                                                Surname = '$LastName', 
                                                FullName = '$FullName', 
                                                OrnetUpdateByUser = '$userName', 
                                                OrnetUpdatedDate = GETDATE() 
                                                $SocCond
                                                $MnameCond
                                                $WardCond
                                                $secCond
                                                $RoomCond
                                                $idCardCond
                                                $ageCond
                                                $MobCond
                                                $listCond
                                            WHERE Voter_Cd = $VoterCd";
                            // echo $updateQuery; exit;
                            $updateData = $db3->RunQueryDataByElectionWise($updateQuery, $userName, $appName, $developmentMode);
                            if($updateData == true || $updateData == 1){
                                if(!empty($VoterCds)){
                                    if (substr($VoterCds, -1) === ',') { // Check if the last character is a comma
                                        $VoterCds = substr($VoterCds, 0, -1); // Remove the last character (i.e. the comma)
                                    }
                                        // --------------FAMILY UPDATE------------------------------------------------------------------------
                                            $sql2 = "UPDATE $DBName.[dbo].[DW_VotersInfo] SET 
                                            UpdatedStatus = 'Y',
                                            SF='1'
                                            $SocCond
                                            $MnameCond
                                            $WardCond
                                            $secCond
                                            $RoomCond
                                            $idCardCond
                                            $ageCond
                                            $MobCond
                                            $listCond
                                            ,OrnetUpdatedDate = FORMAT(SYSUTCDATETIME() AT TIME ZONE 'UTC' AT TIME ZONE 'India Standard Time', 'yyyy-MM-dd HH:mm:ss.fff')
                                            ,OrnetUpdateByUser ='$userName'
                                        WHERE Voter_Cd IN ($VoterCds) AND (SF = '0' OR SF is NULL) ; ";

                                $DwVotersFamilyInfoUpdate = $db->RunQueryDataByElectionWise($sql2, $userName, $appName, $developmentMode);
                                    if($DwVotersFamilyInfoUpdate == true ||$DwVotersFamilyInfoUpdate == 1){
                                        $data["error"] = false;
                                        $data["statusCode"] = 200;
                                        $data["message"] = "Success";
                                    }else{
                                        $data["error"] = true;
                                        $data["statusCode"] = 204;
                                        $data["message"] = "Error occured please try again";
                                    }
                                }else{
                                    $data["error"] = false;
                                    $data["statusCode"] = 200;
                                    $data["message"] = "Success";
                                }
                            }else{
                                
                                $data["error"] = true;
                                $data["statusCode"] = 204;
                                $data["message"] = "Error occured please try again";
                            }
                        }else if($type == 'nonvoter'){
                            $updateQuery = "UPDATE $DBName..NewVoterRegistration SET Subloc_cd = $Sublocation_Cd, Name = '$first_name',Surname = '$LastName',OrnetUpdateByUser = '$userName', OrnetUpdatedDate = GETDATE(), SocietyName = '$SocietyName', SocietyNameM = N'$SocietyNameM', Survey_Society_Cd = $Survey_Society_Cd
                            ,UpdatedStatus = 'Y'";
                                    if(!empty($MiddleName)){
                                        $updateQuery .= ", Middlename = '$MiddleName'";
                                    }
        
                                    if(!empty($Ward_no)){
                                        $updateQuery .= ", Ward_no = $Ward_no";
                                    }
                        
                                    if(!empty($room_no)){
                                        $updateQuery .= ", RoomNo = '$room_no'";
                                    }

                                    if(!empty($age)){
                                        $updateQuery .= ", Age = $age";
                                    }
                                    if(!empty($mobile_no)){
                                        $updateQuery .= ", MobileNo = '$mobile_no'";
                                    }
                                    if(!empty($list_no)){
                                        $updateQuery .= ", List_No = $list_no";
                                    }
        
                                    $updateQuery .= " WHERE Voter_Cd = $VoterCd";
                                    
                                    // print_r($updateQuery);
                                    $updateData = $db3->RunQueryDataByElectionWise($updateQuery, $userName, $appName, $developmentMode);
                                     if($updateData == true || $updateData == 1){
                                        if(!empty($VoterCds)){
                                            if (substr($VoterCds, -1) === ',') { // Check if the last character is a comma
                                                $VoterCds = substr($VoterCds, 0, -1); // Remove the last character (i.e. the comma)
                                            }
                                                // --------------FAMILY UPDATE------------------------------------------------------------------------
                                                    $sql2 = "UPDATE $DBName.[dbo].[DW_VotersInfo] SET 
                                                    UpdatedStatus = 'Y',
                                                    SF='1'
                                                    $SocCond
                                                    $MnameCond
                                                    $WardCond
                                                    $secCond
                                                    $RoomCond
                                                    $idCardCond
                                                    $ageCond
                                                    $MobCond
                                                    $listCond
                                                    ,OrnetUpdatedDate = FORMAT(SYSUTCDATETIME() AT TIME ZONE 'UTC' AT TIME ZONE 'India Standard Time', 'yyyy-MM-dd HH:mm:ss.fff')
                                                    ,OrnetUpdateByUser ='$userName'
                                                WHERE Voter_Cd IN ($VoterCds) AND (SF = '0' OR SF is null); ";

                                        $DwVotersFamilyInfoUpdate = $db->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);
                                            if($DwVotersFamilyInfoUpdate == true || $DwVotersFamilyInfoUpdate == 1){
                                                $data["error"] = false;
                                                $data["statusCode"] = 200;
                                                $data["message"] = "Success";
                                            }else{
                                                $data["error"] = true;
                                                $data["statusCode"] = 204;
                                                $data["message"] = "Error occured please try again";
                                            }
                                        }else{
                                            $data["error"] = false;
                                            $data["statusCode"] = 200;
                                            $data["message"] = "Success";  
                                        }
                                    }else{
                                        $data["error"] = true;
                                        $data["statusCode"] = 204;
                                        $data["message"] = "Error occured please try again";
                                    }

                        }
                    }else{
                            $query = "SELECT * FROM $DBName..NewVoterRegistration WHERE Name = '$first_name' AND Surname = '$LastName' AND Subloc_cd = $Sublocation_Cd AND Ac_No = $Ac_No";
                            
                                    // print_r($query);
                                    // die();
                            // echo $query;exit;
                            $isExist = $db3->ExecutveQueryMultipleRowSALData($ULB,$query,  $userName, $appName, $developmentMode);
                            if(!empty($isExist)){
                                $voter_id = $isExist[0]["Voter_Cd"];
                                $updateQuery = "UPDATE $DBName..NewVoterRegistration SET Subloc_cd = $Sublocation_Cd, Name = '$first_name',Surname = '$LastName',OrnetUpdateByUser = '$userName', OrnetUpdatedDate = GETDATE(), Ac_No = $Ac_No,SocietyName = '$SocietyName', SocietyNameM = N'$SocietyNameM', Survey_Society_Cd = $Survey_Society_Cd
                                ,UpdatedStatus = 'Y'";
                                    if(!empty($MiddleName)){
                                        $updateQuery .= ", Middlename = '$MiddleName'";
                                    }
        
                                    if(!empty($Ward_no)){
                                        $updateQuery .= ", Ward_no = $Ward_no";
                                    }
                        
                                    if(!empty($room_no)){
                                        $updateQuery .= ", RoomNo = '$room_no'";
                                    }

                                    if(!empty($age)){
                                        $updateQuery .= ", Age = $age";
                                    }
                                    if(!empty($mobile_no)){
                                        $updateQuery .= ", MobileNo = '$mobile_no'";
                                    }
                                    if(!empty($list_no)){
                                        $updateQuery .= ", List_No = $list_no";
                                    }
        
                                    $updateQuery .= " WHERE Voter_Cd = $voter_id";
                                    
                                    // print_r($updateQuery);
                                    $updateData = $db3->RunQueryData($ULB,$updateQuery, $userName, $appName, $developmentMode);
                                    // $updateData = $db3->RunQueryDataWithError($updateQuery, $userName, $appName, $developmentMode);
                                    $data =  $updateData;

                            }else{
                                $VoterCdQuery = "SELECT MAX(Voter_Cd) as Voter_Cd FROM $DBName..NewVoterRegistration";
                                $VoterCd = $db3->ExecutveQueryMultipleRowSALData($ULB,$VoterCdQuery, $userName, $appName, $developmentMode);
                                $MaxCd = $VoterCd[0]['Voter_Cd'];
                                $MaxCd = $MaxCd + 1;
        
                                $insertQuery = "INSERT INTO $DBName..NewVoterRegistration (Name, Surname, Middlename,Fullname, Voter_Cd, Ac_No,Age,Mobileno,Societyname,Subloc_cd,Roomno,OrnetUpdateByUser,OrnetUpdatedDate,List_No,
                                                UpdatedStatus, Survey_Society_Cd) 
                                                VALUES('$first_name', '$LastName', '$MiddleName','$FullName', $MaxCd, $Ac_No,$age,'$mobile_no','$SocietyName',$Sublocation_Cd,'$room_no','$userName',GETDATE(),$list_no,'Y',$Survey_Society_Cd)";
                                // print_r($insertQuery);
                                $insertData = $db3->RunQueryDataWithError($ULB,$insertQuery,  $userName, $appName, $developmentMode);
        
                                $data =  $insertData;
                            }
                        }  

                    echo json_encode($data); 

                break;

                case 'singleVoter' :
                    $db3 = new DbOperation();
                    $VoterCd = $_POST['VoterCd'];
                    $type = $_POST['type'];
                    if($type == 'voter'){
                        $query = "SELECT Voter_Cd,Voter_Id,List_No,Age,Sex,CONVERT(varchar,BirthDate,23) as BirthDate,FamilyNo,SubLocation_Cd,FullName,Name,MiddleName,Surname,Ac_No,RoomNo,Ward_no,MobileNo,SF,SocietyName,IdCard_No,Sector,Survey_Society_Cd,'voter' as type FROM $DBName..Dw_VotersInfo WHERE Voter_Cd = $VoterCd";
                    }else if($type == 'nonvoter'){
                        $query = "SELECT Voter_Cd,Voter_Id,List_No,Age,Sex,CONVERT(varchar,Birthdate,23) as BirthDate,FamilyNo,Subloc_cd as SubLocation_Cd,Fullname as FullName,Name,Middlename as MiddleName,Surname,Ac_No,Roomno as RoomNo,Ward_No as Ward_no,Mobileno as MobileNo,1 as SF,Societyname as SocietyName, '' as IdCard_No,'' as Sector,Survey_Society_Cd, 'nonvoter' as type FROM $DBName..NewVoterRegistration WHERE Voter_Cd = $VoterCd";
                    }
                    $data = $db3->ExecutveQuerySingleRowSALData($ULB,$query, $userName, $appName, $developmentMode);
                    echo json_encode($data);
                break;

                case 'loockedRoom':
                    $data = array();
                    $db4 = new DbOperation();
                    // echo "<pre>";print_r($_POST);exit;
                    $Ac_No = $_POST['Ac_No'];
                    $floor = $_POST['floor'];
                    $remark = $_POST['remark'];
                    $room_no = $_POST['room_no'];
                    $Ward_no = $_POST['Ward_no'];
                    $Survey_Society_Cd = $_POST['Sublocation_Cd'];
                    $SocietyNew = $_POST['SocietyNew'];

                    if(!empty($Survey_Society_Cd) && ($Survey_Society_Cd != '00') ){
                        $getSocietyQuery = "SELECT * FROM $DBName..SubLocationMaster WHERE  Survey_Society_Cd = $Survey_Society_Cd";
                        $getSociety = $db4->ExecutveQueryMultipleRowSALData($ULB,$getSocietyQuery,  $userName, $appName, $developmentMode);
                        $Sublocation_Cd = $getSociety[0]['SubLocation_Cd'];
                        $SocietyName = $getSociety[0]['SocietyName'];
                        $SocietyNameM = $getSociety[0]['SocietyNameM'];
                    }else if(!empty($Survey_Society_Cd) && ($Survey_Society_Cd == '00') ){
                        $MaxCd ="SELECT (MAX(SubLocation_Cd) + 1) AS SubLocation_Cd FROM $DBName..SubLocationMaster";
                        $getMaxCd = $db3->ExecutveQuerySingleRowSALData($ULB,$MaxCd,  $userName, $appName, $developmentMode);
                        $Subloc_Cd = $getMaxCd['SubLocation_Cd'];
                        $InsertSociety  =  "INSERT INTO $DBName..SubLocationMaster(SubLocation_Cd,SubLocationName,SocietyName,Sector,Ward_No,List_No)
                                            VALUES($Subloc_Cd, N'$SocietyNew', N'$SocietyNew', N'$sector', $Ward_no, $list_no)";
                        $insertNewSocietyData = $db3->RunQueryDataWithError($ULB,$InsertSociety,  $userName, $appName, $developmentMode);
                        if($insertNewSocietyData ){
                            $Sublocation_Cd = $getMaxCd['SubLocation_Cd'];
                            $SocietyName = $SocietyNew;
                            $SocietyNameM = '';
                        }
                    }

                   $isExistQuery = "SELECT * FROM  $DBName..LockRoom WHERE Sublocation_Cd = $Sublocation_Cd AND RoomNo = '$room_no' AND Locked = 1 AND Ac_No = $Ac_No";
                //    echo $isExistQuery; exit;
                   $isExist = $db4->ExecutveQuerySingleRowSALData($ULB,$isExistQuery, $userName, $appName, $developmentMode);

                   if(!empty($isExist)){
                        $LR_Cd = $isExist['LR_Cd'];
                        $updateQuery = "UPDATE $DBName..LockRoom SET  RoomNo = '$room_no', Locked = 1, Ac_No = $Ac_No, UpdatedStatus = 'Y', OrnetUpdateByUser = '$userName', OrnetUpdatedDate = GETDATE()";
                        if(!empty($Ward_no)){
                            $updateQuery .= ", Ward_No = $Ward_no";
                        }
                        if(!empty($floor)){
                            $updateQuery .= ", FloorNo = '$floor'";
                        }
                        if(!empty( $SocietyName)){
                            $updateQuery .= ", SocietyName = '$SocietyName'";
                            $updateQuery .= ", Sublocation_Cd = $Sublocation_Cd";

                        }
                        if(!empty($SocietyNameM)){
                            $updateQuery .= ", SocietyNameM = N'$SocietyNameM'";
                        }

                        if(!empty($remark)){
                            $updateQuery .= ", Remark = '$remark'";
                        }

                        $updateQuery .= "WHERE LR_Cd = $LR_Cd";
                        // echo $updateQuery;exit;
                          
                        $updateData = $db4->RunQueryDataWithError($ULB,$updateQuery, $userName, $appName, $developmentMode);
                        $data =  $updateData;

                   }else{
                        $LR_CdQuery = "SELECT MAX(LR_Cd) as LR_Cd FROM $DBName..LockRoom";
                        $LR_Cd = $db4->ExecutveQueryMultipleRowSALData($ULB,$LR_CdQuery, $userName, $appName, $developmentMode);
                        $MaxCd = $LR_Cd[0]['LR_Cd'];
                        $MaxCd = $MaxCd + 1;
                        $InsertQuery = "INSERT INTO $DBName..LockRoom(LR_Cd,Sublocation_Cd, SocietyName, SocietyNameM, RoomNo, Locked, Ac_No) VALUES($MaxCd,$Sublocation_Cd,'$SocietyName', N'$SocietyNameM', '$room_no', 1, $Ac_No)";
                        $insertData = $db4->RunQueryDataWithError($ULB,$InsertQuery, $userName, $appName, $developmentMode);
                        // echo "<pre>"; print_r($insertData);exit;
                        if($insertData['message'] == "Success"){
                            // $LR_Cd = $insertData["inserted_id"];
                            $LR_CdQuery = "SELECT MAX(LR_Cd) as LR_Cd FROM $DBName..LockRoom";
                            $LR_Cd = $db4->ExecutveQueryMultipleRowSALData($ULB,$LR_CdQuery, $userName, $appName, $developmentMode);
                            $LR_Cd = $LR_Cd[0]['LR_Cd'];
                            $updateQuery = "UPDATE $DBName..LockRoom SET UpdatedStatus = 'Y', OrnetUpdateByUser = '$userName', OrnetUpdatedDate = GETDATE()";
                            if(!empty($Ward_no)){
                                $updateQuery .= ", Ward_No = $Ward_no";
                            }
                            if(!empty($floor)){
                                $updateQuery .= ", FloorNo = '$floor'";
                            }

                            if(!empty($remark)){
                                $updateQuery .= ", Remark = '$remark'";
                            }

                            $updateQuery .= "WHERE LR_Cd = $LR_Cd";
                            $updateData = $db4->RunQueryDataWithError($ULB,$updateQuery, $userName, $appName, $developmentMode);
                            $data =  $updateData;
                        }
                        
                   }

                   echo json_encode($data); 
                break;             ;
    
                default : 
                    echo 'Something Went Wrong';
                break; 
                
            }
        }else{
            echo json_encode(array('statusCode' => 500, 'msg' => 'Task Not Found'));
        }

    }else{
        echo json_encode(array('statusCode' => 500, 'msg' => 'Database Not Found'));
    }

}

?>