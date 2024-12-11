<?php

	$target_path1 = "";

	$data = array();
	$action = $_POST["action"];
	$ansType = $_POST["ansType"];
	$appName = $_POST["appname"];
	$loginCd = $_POST["User_Id"];


	if (strpos($ansType, 'UserMaster') !== false) {
		$target_path1 = "UserMaster/";
	}else if (strpos($ansType, 'ExecutiveMaster') !== false) {
		$target_path1 = "ExecutiveMaster/";
	}

	if (strpos($action, 'upload') !== false) {
		$temp = explode(".", $_FILES["uploaded_file"]["name"]);
		$target_filename = round(microtime(true)) .'_'. $ansType .'_'. $loginCd . '.' . end($temp);
		//$target_filename = round(microtime(true)) . basename($_FILES['uploaded_file']['name']) ;
		$target_path1 = $target_path1 . $target_filename ;
		if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $target_path1)) {
			//echo "The first file " . basename($_FILES['uploaded_file']['name']) . " has been uploaded.";
			
			$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
	                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
	                $_SERVER['REQUEST_URI'];

	                $file_Name_php = basename($link);
	                $new_link = str_replace($file_Name_php, '', $link);

	                
		 	$data["error"] = false;
	        $data["message"] = "File has been uploaded!!";
	        $data["fileUrl"] = $new_link.$target_path1;
	        $data["Flag"] = "I";
		} else {
			$data["error"] = true;
	        $data["message"] = "Opps!! File has not been uploaded.";
	        $data["fileUrl"] = "";
	        $data["Flag"] = "N";
		}

	}

	echo json_encode($data, 128);

?>