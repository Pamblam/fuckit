<?php

class ConfigController extends Controller{

	public function __construct($pdo, $response) {
		parent::__construct($pdo, $response);
	}

	public function getThemes(){
		$themes = [];

		$dirs = scandir(APP_ROOT.'/src/themes');
		foreach($dirs as $dir) if(!in_array($dir, ['.','..'])) $themes[] = $dir;

		$this->response->setData([
			'themes' => $themes
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
		if(empty($_POST['desc'])){
			$this->response->setError("Missing description", 400)->send();
		}
		if(empty($_POST['max_upload_size'])){
			$this->response->setError("Missing max filesize", 400)->send();
		}

		$base_url = empty($GLOBALS['config']) || empty($GLOBALS['config']->base_url) ? '/' : $GLOBALS['config']->base_url;
		
		$app_config_obj = json_decode(file_get_contents($GLOBALS['app_config_file']), true);
		$app_config_obj['title'] = $_POST['title'];
		$app_config_obj['desc'] = $_POST['desc'];
		$app_config_obj['ga_tag'] = $_POST['ga_tag'];

		if(!empty($_POST['img'])) $app_config_obj['img'] = $_POST['img'];
		if(!empty($_POST['theme'])) $app_config_obj['theme'] = $_POST['theme'];
		if($app_config_obj['theme'] === 'core') unset($app_config_obj['theme']);

		$res = @file_put_contents($GLOBALS['app_config_file'], json_encode($app_config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
		if(false === $res){
			$this->response->setError("Can't create app config file. Ensure PHP has correct permissions and ownership.", 500)->send();
		}

		$server_config_obj = json_decode(file_get_contents($GLOBALS['server_config_file']), true);
		$server_config_obj['base_url'] = $base_url;
		$server_config_obj['max_upload_size'] = $_POST['max_upload_size'];
		$server_config_obj['node_path'] = $_POST['node_path'];
		$GLOBALS['config']->node_path = $_POST['node_path'];

		$res = @file_put_contents($GLOBALS['server_config_file'], json_encode($server_config_obj, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
		if(false === $res){
			$this->response->setError("Can't create server config file. Ensure PHP has correct permissions and ownership.", 500)->send();
		}

		try{
			self::build();
		}catch(Exception $e){
			$this->response->setError($e->getMessage(), 500)->send();
		}
	}

	public function rebuild(){
		$user = $this->getUser();

		// Validate the request
		if(empty($user)){
			$this->response->setError("Not logged in", 401)->send();
		}

		try{
			self::build();
		}catch(Exception $e){
			$this->response->setError($e->getMessage(), 500)->send();
		}
	}

	private static function build(){
		if(empty($GLOBALS['config']->node_path)){
			throw new Exception("Unable to build: No node path set.");
		}
		$path_parts = explode('/', $GLOBALS['config']->node_path);
		array_pop($path_parts);
		$path = implode("/", $path_parts);
		$envPath = '/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:'.$path;
		$cmd = 'cd ' . escapeshellarg(APP_ROOT) . ' && PATH=' . $envPath . ' npm run build';
		$res = fi_run_cmd($cmd, null, APP_ROOT);
		if($res->exit_status !== 0) throw new Exception($res->stderr." $cmd");
	}
}