<?php

class Tag extends Model{
	protected static $table_name = "tags";
	
	public function __construct($pdo) {
		parent::__construct($pdo);
		$this->columns = [
			"id" => null,
			"post_id" => null,
			"tag" => null
		];
	}

}