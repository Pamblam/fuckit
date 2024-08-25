<?php

class Model{
	
	protected static $table_name;
	protected $pdo;
	protected $columns;
	protected $in_db = false;
	
	public function isInDB(){
		return $this->in_db;
	}

	public function __construct($pdo) {
		$this->pdo = $pdo;
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	public function set($column, $value){
		if(!array_key_exists($column, $this->columns)) return false;
		$this->columns[$column] = $value;
		return true;
	}
	
	public function get($column){
		if(!array_key_exists($column, $this->columns)) return false;
		return $this->columns[$column];
	}
	
	public function getColumns(){
		return $this->columns;
	}
	
	public function save(){
		if($this->in_db){
			return $this->update();
		}else{
			return $this->create();
		}
	}
	
	public function delete(){
		if(!$this->in_db) return false;
		$success = $this->pdo->prepare("delete from ".static::$table_name." where id = ?")->execute([$this->columns['id']]);
		$this->in_db = false;
		return !!$success;
	}
	
	protected function create(){
		$params = array_values($this->columns);
		$sql = "insert into ".static::$table_name." (".implode(",", array_keys($this->columns)).") values (".implode(", ", array_fill(0, count($this->columns), "?")).")";
		$success = !!$this->pdo->prepare($sql)->execute($params);
		if($success){
			$this->in_db = true;
			if(empty($this->columns['id'])){
				$q = $this->pdo->prepare("select max(id) from ".static::$table_name);
				$q->execute();
				$this->columns['id'] = intval($q->fetchColumn());
			}
		} 
		return $success;
	}
	
	protected function update(){
		$params = [];
		$update_sql = [];
		$sql = "update ".static::$table_name." set ";
		foreach($this->columns as $column=>$value){
			if($column === "id") continue;
			$params[] = $value;
			$update_sql[] = "$column = ?";
		}
		$sql .= implode(", ", $update_sql)." where id = ?";
		$params[] = $this->columns['id'];
		return !!$this->pdo->prepare($sql)->execute($params);
	}
	
	public static function fromID($pdo, $id){
		return self::fromColumn($pdo, 'id', $id);
	}
	
	public static function getLastInserted($pdo){
		$id = $pdo->query("select `id` from `".static::$table_name."` order by `id` desc limit 1")->fetchColumn();
		if($id === false) return false;
		return self::fromID($pdo, $id);
	}
	
	public static function fromColumns($pdo, $columns){
		$classname = get_called_class();
		$instance = new $classname($pdo);
		foreach($columns as $col=>$val){
			$instance->set($col, $val);
		}
		$instance->_load();
		return $instance;
	}
	
	public static function fromColumn($pdo, $column_name, $column_value){
		$classname = get_called_class();
		$instance = new $classname($pdo);
		$q = $pdo->prepare("select * from ".static::$table_name." where `$column_name` = ?");
		$q->execute([$column_value]);
		$res = $q->fetch(PDO::FETCH_ASSOC);
		if(empty($res)) return false;
		foreach($res as $key=>$val){
			$instance->set($key, $val);
		}
		$instance->in_db = true;
		$instance->_load();
		return $instance;
	}
	
	public static function allFromColumn($pdo, $column_name, $column_value, $data_structure_only=false){
		$results = [];
		$classname = get_called_class();
		$q = $pdo->prepare("select * from ".static::$table_name." where `$column_name` = ?");
		$q->execute([$column_value]);
		while($res = $q->fetch(PDO::FETCH_ASSOC)){
			if(empty($res)) continue;
			$instance = new $classname($pdo);
			foreach($res as $key=>$val){
				$instance->set($key, $val);
			}
			$instance->in_db = true;
			$instance->_load();
			if($data_structure_only === true){
				$results[] = $instance->getColumns();
			}else{
				$results[] = $instance;
			}
		}		
		return $results;
	}
	
	public function insertOrUpdate(){
		$q = $this->pdo->prepare("select count(1) from ".static::$table_name." where `id` = ?");
		$q->execute([$this->columns['id']]);
		$this->in_db = intval($q->fetchColumn()) > 0;
		$this->save();
	}
	
	/** called from static constructors **/
	public function _load(){}
}
