<?php

class User extends Model{
	protected static $table_name = "users";
	
	public function __construct($pdo) {
		parent::__construct($pdo);
		$this->columns = [
			"id" => null,
			"username" => null,
			"password" => null,
			"display_name" => null
		];
	}
	
}