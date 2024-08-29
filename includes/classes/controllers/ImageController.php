<?php

class ImageController extends ModelController{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function get(){
		
	}

	public function post(){
		$user = $this->getUser();

		// Validate the request
		if(empty($user)){
			$this->response->setError("Not logged in", 401)->send();
		}

		if(empty($_FILES) || empty($_FILES["img"])){
			$this->response->setError("No file provided", 400)->send();
		}

		if($_FILES['img']['error'] !== UPLOAD_ERR_OK){
			switch ($_FILES['img']['error']) {
				case UPLOAD_ERR_INI_SIZE:
					$message = "The uploaded file exceeds the max file size.";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$message = "The uploaded file was only partially uploaded.";
					break;
				case UPLOAD_ERR_NO_FILE:
					$message = "No file was uploaded.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$message = "Missing a temporary folder.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$message = "Failed to write file to disk.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$message = "File upload stopped by extension.";
					break;
				default:
					$message = "Unknown upload error.";
					break;
			}
			$this->response->setError($message, 400)->send();
		}

		// Set some variables
		$target_dir = APP_ROOT."/public/assets/images/";
		$target_file = $target_dir . basename($_FILES["img"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Make sure it's an image
		$check = getimagesize($_FILES["img"]["tmp_name"]);
		if($check === false) {
			$this->response->setError("Invalid file", 400)->send();
		}

		// Make sure it's not too big
		if ($_FILES["img"]["size"] > $GLOBALS['config']->max_upload_size) {
			$this->response->setData([
				'file_size' => $_FILES["img"]["size"],
				'max_upload_size' => $GLOBALS['config']->max_upload_size
			]);
			$this->response->setError("File too large", 400)->send();
		}

		// Make sure it's a supported format
		if(!in_array($imageFileType, ['png', 'jpg', 'jpeg', 'gif'])){
			$this->response->setError("Unsupported file type", 400)->send();
		}

		// get a unique filename by appending or incremeneting a number to the filename
		while(file_exists($target_file)){
			$basename = basename($target_file);
			$parts = explode(".", $basename);
			$ext = array_pop($parts);
			$basename = implode(".", $parts);
			preg_match('/\d*$/', $basename, $matches);
			if(empty($matches[0])){
				$count = 1;
			}else{
				$basename = substr($basename, 0, -strlen($matches[0]));
				$count = intval($matches[0]);
			}
			$basename .= ($count+1) . ".$ext";
			$target_file = $target_dir . $basename;
		}

		// save the file to the server
		$res = move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
		if(false === $res){
			$this->response->setError("Couldn't move file", 500)->send();
		}

		// Save info to database
		$this->model_instance->set('orig_name', basename($_FILES["img"]["name"]));
		$this->model_instance->set('sys_name', basename($target_file));
		$this->model_instance->set('upload_ts', time());
		$this->model_instance->set('uploader_id', $user->get('id'));
		$res = $this->model_instance->save();
		if(false === $res){
			$this->response->setError("Couldn't save file data.", 500)->send();
		}

		$this->response->setData([
			'path' => 'assets/images/'.basename($target_file),
			'image' => $this->model_instance->getColumns()
		]);
	}

}