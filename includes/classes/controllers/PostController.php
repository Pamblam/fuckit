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
		// we can get it by either slug or id
		if(!$this->model_instance->isInDB() && !empty($_GET['slug'])) {
			$this->model_instance = Post::fromColumn($this->pdo, 'slug', $_GET['slug']);
		}

		$return_raw_md = isset($_GET['raw']) && $_GET['raw'] == '1';

		if(false == $this->model_instance){
			$this->response->setError("Post not found", 404)->send();
		}
		$author = User::fromId($this->pdo, $this->model_instance->get('author_id'));
		$edited_by = false;
		if(!empty($this->model_instance->get('editor_id'))) $edited_by = User::fromID($this->pdo, $this->model_instance->get('editor_id'));
		
		if(false !== $author){
			$author = [
				'id' => $author->get('id'),
				'username' => $author->get('username'),
				'display_name' => $author->get('display_name'),
			];
		}

		if(false !== $edited_by){
			$edited_by = [
				'id' => $edited_by->get('id'),
				'username' => $edited_by->get('username'),
				'display_name' => $edited_by->get('display_name'),
			];
		}

		$tags = Tag::allFromColumn($this->pdo, 'post_id', $this->model_instance->get('id'), true);

		$post = $this->model_instance->getColumns();
		if(!$return_raw_md){
			$Parsedown = new Parsedown();
			$post['body'] = $Parsedown->text($post['body']);
		}

		$this->response->setData([
			'author' => $author,
			'edited_by' => $edited_by,
			'post' => $post,
			'tags' => $tags
		]);
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
		$slug_exists = Post::fromColumn($this->pdo, 'slug', $slug) !== false;

		$published = isset($_POST["publish"]) && $_POST["publish"] == 1 ? 1 : 0;
		$this->model_instance->set('create_ts', time());
		$this->model_instance->set('author_id', $user->get('id'));
		$this->model_instance->set('title', $_POST['title']);
		$this->model_instance->set('body', $_POST['body']);
		if(!empty($_POST['summary'])) $this->model_instance->set('summary', $_POST['summary']);
		if(!empty($_POST['graph_img'])) $this->model_instance->set('graph_img', $_POST['graph_img']);
		if(!$slug_exists && !is_numeric($slug)) $this->model_instance->set('slug', $slug);
		$this->model_instance->set('published', $published);
		$this->model_instance->save();

		// if the slug wasn't unique, prepend the id and set it again
		if($slug_exists) $this->model_instance->set('slug', $this->model_instance->get('id')."_".$slug);
		$this->model_instance->save();

		$tag_objs = [];
		$tags = explode(',', $_POST['tags']);
		foreach($tags as $tag){
			$tag = trim(strtolower($tag));
			$tag_obj = Tag::fromColumns($this->pdo, [
				'tag' => $tag,
				'post_id' => $this->model_instance->get('id')
			]);
			$tag_obj->save();
			$tag_objs[] = $tag_obj->getColumns();
		}

		$this->response->setData([
			'Post' => $this->model_instance->getColumns(),
			'Tags' => $tag_objs,
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

		$published = isset($_PATCH["publish"]) && $_PATCH["publish"] == 1 ? 1 : 0;
		$this->model_instance->set('edit_ts', time());
		$this->model_instance->set('editor_id', $user->get('id'));
		$this->model_instance->set('title', $_PATCH['title']);
		$this->model_instance->set('body', $_PATCH['body']);
		if(!empty($_PATCH['summary'])) $this->model_instance->set('summary', $_PATCH['summary']);
		if(!empty($_PATCH['graph_img'])) $this->model_instance->set('graph_img', $_PATCH['graph_img']);
		$this->model_instance->set('published', $published);
		$this->model_instance->save();

		// Delete all existing tags
		$existing_tags = Tag::allFromColumn($this->pdo, 'post_id', $this->model_instance->get('id'));
		foreach($existing_tags as $tag) $tag->delete();

		// Save new tags
		$tag_objs = [];
		$tags = explode(',', $_PATCH['tags']);
		foreach($tags as $tag){
			$tag = trim(strtolower($tag));
			$tag_obj = Tag::fromColumns($this->pdo, [
				'tag' => $tag,
				'post_id' => $this->model_instance->get('id')
			]);
			$tag_obj->save();
			$tag_objs[] = $tag_obj->getColumns();
		}

		$this->response->setData([
			'Post' => $this->model_instance->getColumns(),
			'Tags' => $tag_objs,
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