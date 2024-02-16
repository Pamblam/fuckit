<?php

class Post extends Model{
	protected static $table_name = "posts";
	
	public function __construct($pdo) {
		parent::__construct($pdo);
		$this->columns = [
			"id" => null,
			"create_ts" => null,
			"edit_ts" => null,
			"author_id" => null,
			"editor_id" => null,
			"title" => null,
			"body" => null,
			"summary" => null
		];
	}

}