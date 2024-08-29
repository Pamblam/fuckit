<?php

class PaginationController extends Controller{

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

		$sql_params = empty($_GET['params']) ? [] : json_decode($_GET['params'], true);
		$query_params = self::getQuery($_GET['query'], $sql_params);

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

		$where_clause = '';
		$params = [];
		if(!empty($_GET['search_term'])){
			foreach($query_params['searchable_cols'] as $idx=>$col){
				$where_clause .= ($idx == 0 ? 'where ' : ' or ') . "lower(`q`.`$col`) like ?";
				$params[] = "%".strtolower($_GET['search_term'])."%";
			}
		}

		$offset = ($page - 1) * $page_size;
		$sql = "select * from ($base_query) `q` $where_clause order by lower(`q`.`$order_by`) $order_dir limit $page_size offset $offset";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		$results = [];
		while($res = $stmt->fetch(PDO::FETCH_ASSOC)){
			if(!empty($res['graph_img']) && strpos($res['graph_img'], 'assets/') === 0){
				$res['graph_img'] = $res['graph_img'] = $GLOBALS['config']->base_url . $res['graph_img'];
			}
			$results[] = $res;
		}

		$this->response->setData([
			'results' => $results,
			'total_records' => $record_count,
			'total_pages' => $total_pages,
			'page' => $page,
			'order_dir' => $order_dir,
			'page_size' => $page_size,
			'order_by' => $order_by
		]);
	}

	private static function getQuery($name, $params){
		switch($name){
			case "all_posts_of_tag":
				return [
					'searchable_cols' => [],
					'cols' => ['id', 'create_ts', 'author_id', 'author_name', 'title', 'slug'],
					'sql' => "
						select 
							`p`.`id`
							,`p`.`create_ts`
							,`p`.`author_id` 
							,`u`.`display_name` as `author_name` 
							,`p`.`graph_img`
							,`p`.`title` 
							,`p`.`summary`
							,`p`.`slug`
						from 
							`posts` `p` 
							left join `users` `u` on `u`.`id` = `p`.`author_id`
							left join (
								select distinct `post_id` from `tags` where `tag` in ('".implode("', '", $params['tags'])."')
							) `q` on `q`.`post_id` = `p`.`id`
						where `p`.`published` = 1 and `q`.`post_id` is not null"
				];

			case "all_posts":
				return [
					'searchable_cols' => ['author_name', 'title', 'slug'],
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
			case "all_posts_summary":
				return [
					'searchable_cols' => ['title', 'summary'],
					'cols' => ['id', 'create_ts', 'author_id', 'author_name', 'graph_img', 'title', 'summary', 'slug'],
					'sql' => "
						select 
							`p`.`id`
							,`p`.`create_ts`
							,`p`.`author_id` 
							,`u`.`display_name` as `author_name` 
							,`p`.`graph_img`
							,`p`.`title`
							,`p`.`summary`
							,`p`.`slug`
						from 
							`posts` `p` 
							left join 
								`users` `u` on `u`.`id` = `p`.`author_id`
						where `p`.`published` = 1"
				];
		}
		return false;
	}

}