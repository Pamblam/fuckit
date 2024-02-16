<?php

class Session extends Model{
	protected static $table_name = "sessions";
	
	public function __construct($pdo) {
		parent::__construct($pdo);
		$this->columns = [
			"id" => null,
			"user_id" => null,
			"start_time" => null,
			"user_agent" => null,
			"ip" => null
		];
	}

}