<?php

	if(isset($_SESSION['SurveyUtility_ServerIP']) && !empty($_SESSION['SurveyUtility_ServerIP'])){
		$ServerIPInSession = $_SESSION['SurveyUtility_ServerIP'];
	}else{
		$ServerIPInSession = "103.14.99.154";
		$_SESSION['SurveyUtility_ServerIP'] = $ServerIPInSession;
	}

	if($ServerIPInSession == '103.14.99.154'){
		define('DB_USERNAME_USER', 'sa');
		define('DB_PASSWORD_USER', '154@2023SQL#ORNET01');
		define('DB_HOST_USER', '103.14.99.154');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}else if($ServerIPInSession == '52.140.77.2'){
		define('DB_USERNAME_USER', 'sqlvmadmin');
		define('DB_PASSWORD_USER', 'fEMpEALVeRingio123');
		define('DB_HOST_USER', '52.140.77.2');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}else if($ServerIPInSession == '92.204.137.146'){
		define('DB_USERNAME_USER', 'sa');
		define('DB_PASSWORD_USER', '146@2023SQL#ORNET05');
		define('DB_HOST_USER', '92.204.137.146');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}else if($ServerIPInSession == '92.204.145.32'){
		define('DB_USERNAME_USER', 'sa');
		define('DB_PASSWORD_USER', '32@2023SQL#ORNET07');
		define('DB_HOST_USER', '92.204.145.32');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}else if($ServerIPInSession == '103.14.97.58'){
		define('DB_USERNAME_USER', 'sa');
		define('DB_PASSWORD_USER', '58@2023SQL#ORNET03');
		define('DB_HOST_USER', '103.14.97.58');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}else{
		define('DB_USERNAME_USER', 'sa');
		define('DB_PASSWORD_USER', '154@2023SQL#ORNET01');
		define('DB_HOST_USER', '103.14.99.154');
		define('DB_NAME_USER', 'Survey_Entry_Data');
	}

	define('USER_LOGIN_SUCCESS', 101);
	define('USER_LOGIN_FAILED', 102);

	define('USER_INSTALLATION_EXPIRED', 103);
	define('USER_LICENSE_EXPIRED', 104);

	define('UPDATE_SUCCESS', 105);
	define('UPDATE_FAILURE', 106);

	define('USER_STATUS_ACTIVE', 107);
	define('USER_STATUS_NOT_ACTIVE', 108);

	define('DEFAULT_DATE', "1900-01-01 00:00:00.000");



?>