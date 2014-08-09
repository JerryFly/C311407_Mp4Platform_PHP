<?php header("Access-Control-Allow-Origin: *"); ?>
<?php
/*
 * POST參數
 * MUID
 * VideoID
 * videoFileName
 * */
include_once 'class_PostParamter.php';
include_once 'class_AjaxMessage.php';
include_once 'class_fileInfo.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

define('rootPATH', __DIR__);
define('webUri','http://203.66.57.153');

$ajMessage = new ajaxMessage();

if ($_FILES["file"]["error"] > 0) {

	$ajMessage->result = false;
	$ajMessage->message = $_FILES["file"]["error"];

} else {

	$getFileName = $_FILES["file"]["name"];
	$getFileType = $_FILES["file"]["type"];
	$getFileSize = $_FILES["file"]["size"];
	$getFileTmpPath = $_FILES["file"]["tmp_name"];

	$splitFileName = explode(".", $getFileName);
	$getExtension = end($splitFileName);

	//回傳JSON取得檔案資訊
	$ajMessage->fileInfo = new fileInfo();

	$ajMessage->fileInfo->fileName = $getFileName;
	$ajMessage->fileInfo->fileType = $getFileType;
	$ajMessage->fileInfo->fileSize = $getFileSize;
	$ajMessage->fileInfo->fileExtension = $getExtension;

	//取得Post參數
	$postInfo = new postParamater ();
	
	$postInfo->MUID = $_POST["MUID"];
	$postInfo->VideoID = $_POST["VideoID"];
	$postInfo->photoFileName = $_POST["photoFileName"];
	$postInfo->photoAbsolutePath = $_POST["photoAbsolutePath"];
	
	//$defFolder = "VOD";
	$photoAbsolutePath = rootPATH."/".$postInfo->photoAbsolutePath;
	$ajMessage->message = $photoAbsolutePath;
	//檢查路徑
	if(!file_exists($photoAbsolutePath)){
		mkdir($photoAbsolutePath, 0777, true);
	}
	
	$isOK = move_uploaded_file($getFileTmpPath,$photoAbsolutePath."/".$postInfo->photoFileName);
	$ajMessage->src = webUri."/".$postInfo->photoAbsolutePath."/".$postInfo->photoFileName;
	$ajMessage->result = true;
}

$jsonString =  json_encode((array)$ajMessage);
print_r($jsonString);
?>