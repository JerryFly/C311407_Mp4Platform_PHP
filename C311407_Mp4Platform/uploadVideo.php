<?php header("Access-Control-Allow-Origin: http://192.168.0.5"); ?>
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

	$ajMessage->result = true;
	$ajMessage->fileInfo = new fileInfo();

	$ajMessage->fileInfo->fileName = $getFileName;
	$ajMessage->fileInfo->fileType = $getFileType;
	$ajMessage->fileInfo->fileSize = $getFileSize;
	$ajMessage->fileInfo->fileExtension = $getExtension;
}

$jsonString =  json_encode((array)$ajMessage);
print_r($jsonString);


class ajaxMessage
{
	public $src = "";
	public $result = true;
	public $message = "";
	public $fileInfo = "";
};

class fileInfo{

	public $fileName = "";
	public $fileType = "";
	public $fileSize = "";
	public $fileExtension = "";
};


?>