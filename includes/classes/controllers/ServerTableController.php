<?php

class ServerTableController extends Controller{

	public function __construct($pdo, $response) {
		parent::__construct($pdo, $response);
	}

	public function get(){

		if(empty($_GET['query'])){
			$this->response->setError("Missing query name", 400)->send();
		}

		if(!empty($_GET['page_size']) && !is_numeric($_GET['page_size'])){
			$this->response->setError("Invalid page size", 400)->send();
		}

		if(!empty($_GET['page']) && !is_numeric($_GET['page'])){
			$this->response->setError("Invalid page", 400)->send();
		}

		if(!empty($_GET['order_dir']) && !in_array(strtolower($_GET['order_dir']), ['asc', 'desc'])){
			$this->response->setError("Invalid order direction", 400)->send();
		}

		
		$order_dir = empty($_GET['order_dir']) ? 'desc' : strtolower($_GET['order_dir']);
		$page_size = empty($_GET['page_size']) ? 10 : intval($_GET['page_size']);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$query_params = self::getQuery($_GET['query']);

		if(false === $query_params){
			$this->response->setError("Invalid query name", 400)->send();
		}

		if(!empty($_GET['order_by_col']) && !in_array(strtolower($_GET['order_by_col']), $query_params['cols'])){
			$this->response->setError("Invalid order", 400)->send();
		}

		$order_by = empty($_GET['order_by_col']) ? $query_params['cols'][0] : strtolower($_GET['order_by_col']);
		$base_query = $query_params['sql'];
		
		$record_count = intval($this->pdo->query("select count(1) from ($base_query) `q`")->fetchColumn());
		$total_pages = ceil($record_count / $page_size);

		$offset = ($page - 1) * $page_size;
		$results = $this->pdo->query("select * from ($base_query) `q` order by `q`.`$order_by` $order_dir limit $page_size offset $offset");

		$this->response->setData([
			'results' => $results->fetchAll(PDO::FETCH_ASSOC),
			'total_records' => $record_count,
			'total_pages' => $total_pages,
			'page' => $page,
			'order_dir' => $order_dir,
			'page_size' => $page_size,
			'order_by' => $order_by
		]);
	}

	private static function getQuery($name){
		switch($name){
			case "admin_posts":
				return [
					'cols' => ['id', 'create_ts', 'author_id', 'author_name', 'title', 'slug', 'action'],
					'sql' => "
						select 
							`p`.`id`
							,`p`.`create_ts`
							,`p`.`author_id` 
							,`u`.`display_name` as `author_name` 
							,`p`.`title` 
							,`p`.`slug` 
							,`p`.`published` 
							,null as `action` 
						from 
							`posts` `p` 
							left join 
								`users` `u` on `u`.`id` = `p`.`author_id`"
				];
		}
		return false;
	}

}