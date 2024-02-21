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
			"summary" => null,
			"slug" => null,
			"graph_img" => null,
			"published" => null
		];
	}

	public static function generateSlug($title){
		$max_chars = 50;
		$slug = preg_replace('/[^a-z0-9 ]/', '', trim(strtolower($title)));
		$words = explode(' ', $slug);
		$slug = '';
		foreach($words as $word){
			if(empty($slug)){
				if(strlen($word) > $max_chars) break;
				$slug = $word;
			}else{
				if(strlen($slug)+1+strlen($word) > $max_chars) break;
				$slug .= "_$word";
			}
		}
		return $slug;
	}

}