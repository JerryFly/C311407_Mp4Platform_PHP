<?php header("Access-Control-Allow-Origin: http://localhost:3652"); ?>
<?php
/*
 * POST參數
 * MUID
 * VideoID
 * videoFileName
 * */

include_once 'classPostParamter.php';
include_once 'class_AjaxMessage.php';
include_once 'class_fileInfo.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
define('rootPATH', __DIR__);

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
	$postInfo->videoFileName = $_POST["videoFileName"];
	
	$defFolder = "VOD";
	$videoAbsolutePath = rootPATH."/".$defFolder."/".$postInfo->VideoID."/".$postInfo->MUID;
	$ajMessage->message = $videoAbsolutePath;
	//檢查路徑
	if(!file_exists($videoAbsolutePath)){
		mkdir($videoAbsolutePath, 0777, true);
	}
	
	$isOK = move_uploaded_file($getFileTmpPath,$videoAbsolutePath."/".$postInfo->videoFileName);
	$ajMessage->result = true;
}

$jsonString =  json_encode((array)$ajMessage);
print_r($jsonString);
?>