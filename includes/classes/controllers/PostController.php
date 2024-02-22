<?php

class PostController extends ModelController{

	public function __construct($pdo, $response, $id=null) {
		parent::__construct($pdo, $response, $id);
	}

	public function generateSlug(){
		if(empty($_GET) || empty($_GET["title"])){
			$this->response->setError("No title provided", 400)->send();
		}
		$slug = Post::generateSlug($_GET["title"]);
		$this->response->setData(['slug' => $slug]);
	}

	public function get(){
		
	}

	public function post(){
		$user = $this->getUser();

		// Validate the request
		if(empty($user)){
			$this->response->setError("Not logged in", 401)->send();
		}

		if(empty($_POST['title'])){
			$this->response->setError("Missing title", 400)->send();
		}

		if(empty($_POST['body'])){
			$this->response->setError("Missing body", 400)->send();
		}

		$slug = Post::generateSlug($_POST["title"]);
		$published = isset($_POST["publish"]) && $_POST["publish"] == 1 ? 1 : 0;
		$this->model_instance->set('create_ts', time());
		$this->model_instance->set('author_id', $user->get('id'));
		$this->model_instance->set('title', $_POST['title']);
		$this->model_instance->set('body', $_POST['body']);
		if(!empty($_POST['summary'])) $this->model_instance->set('summary', $_POST['summary']);
		$this->model_instance->set('slug', $slug);
		if(!empty($_POST['graph_img'])) $this->model_instance->set('graph_img', $_POST['graph_img']);
		$this->model_instance->set('published', $published);
		$this->model_instance->save();

		$this->response->setData([
			'Post' => $this->model_instance->getColumns(),
			'User' => [
				"id" => $user->get('id'),
				"username" => $user->get('username'),
				"display_name" => $user->get('display_name')
			]
		]);
	}

	public function put(){
		
	}

	public function patch(){

		// We gotta do this to parse form data when using PATCH
		$_input = [];
		new Stream($_input);
		$_PATCH = $_input['post'];

		$user = $this->getUser();

		// Validate the request
		if(empty($user)){
			$this->response->setError("Not logged in", 401)->send();
		}

		if(empty($_PATCH['title'])){
			$this->response->setError("Missing title", 400)->send();
		}

		if(empty($_PATCH['body'])){
			$this->response->setError("Missing body", 400)->send();
		}

		$slug = Post::generateSlug($_PATCH["title"]);
		$published = isset($_PATCH["publish"]) && $_PATCH["publish"] == 1 ? 1 : 0;
		$this->model_instance->set('create_ts', time());
		$this->model_instance->set('author_id', $user->get('id'));
		$this->model_instance->set('title', $_PATCH['title']);
		$this->model_instance->set('body', $_PATCH['body']);
		if(!empty($_PATCH['summary'])) $this->model_instance->set('summary', $_PATCH['summary']);
		$this->model_instance->set('slug', $slug);
		if(!empty($_PATCH['graph_img'])) $this->model_instance->set('graph_img', $_PATCH['graph_img']);
		$this->model_instance->set('published', $published);
		$this->model_instance->save();

		$this->response->setData([
			'Post' => $this->model_instance->getColumns(),
			'User' => [
				"id" => $user->get('id'),
				"username" => $user->get('username'),
				"display_name" => $user->get('display_name')
			]
		]);
	}

	public function delete(){
		
	}

}