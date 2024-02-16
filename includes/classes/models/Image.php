<?php

class Image extends Model{
	protected static $table_name = "images";
	
	public function __construct($pdo) {
		parent::__construct($pdo);
		$this->columns = [
			"id" => null,
			"orig_name" => null,
			"sys_name" => null,
			"upload_ts" => null,
			"uploader_id" => null
		];
	}

}