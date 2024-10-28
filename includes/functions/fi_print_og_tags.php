<?php

function fi_print_og_tags(){
	$meta_tags = [];

	if(empty($GLOBALS['config']) || empty($GLOBALS['pdo'])) return;

	$meta_tags['og:type'] = 'website';
	if(!empty($GLOBALS['config']->img)) $meta_tags['og:image'] = $GLOBALS['config']->img;
	if(!empty($GLOBALS['config']->title)) $meta_tags['og:site_name'] = $GLOBALS['config']->title;
	if(!empty($GLOBALS['config']->desc)) $meta_tags['og:description'] = $GLOBALS['config']->desc;

	$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	$index = strpos($url, $GLOBALS['config']->base_url);
	$relative_url = substr($url, $index + strlen($GLOBALS['config']->base_url));
	$url_parts = explode('/', $relative_url);

	if(count($url_parts) === 2 && $url_parts[0] === 'post'){
		$slugOrId = $url_parts[1];
		if(is_numeric($slugOrId)){
			$post = Post::fromID($GLOBALS['pdo'], $slugOrId);
		}else{
			$post = Post::fromColumn($GLOBALS['pdo'], 'slug', $slugOrId);
		}
		if(!empty($post)){
			$html = fi_md_to_html($post->get('body'));

			$meta_tags['og:type'] = 'article';
			$meta_tags['og:image'] = $post->get('graph_img');
			$meta_tags['og:title'] = $post->get('title');
			$meta_tags['og:description'] = $post->get('summary');

			if(!empty($meta_tags['og:image']) && strpos($meta_tags['og:image'], 'assets/') === 0){
				$meta_tags['og:image'] = fi_get_base_url() . $meta_tags['og:image'];
			}
		}
	}

	if(!empty($meta_tags['og:image'])){

		if(strpos($meta_tags['og:image'], fi_get_base_url()) !== 0){
			$meta_tags['og:image'] = fi_get_base_url() . ltrim($meta_tags['og:image'], '/');
		}

		// convert the image URL to local path to get the image size
		$url_parts = parse_url($meta_tags['og:image']);
		if($url_parts['host'] === $_SERVER['SERVER_ADDR'] || $url_parts['host'] === $_SERVER['HTTP_HOST']){
			$path = '/' . trim($GLOBALS['config']->base_url, '/');
			if(strpos($url_parts['path'], $path) === 0){
				$relative_path = substr($url_parts['path'], strlen($path));
				list($og_img_width, $og_img_height) = getimagesize(APP_ROOT.'/public'.$relative_path);
				if(!empty($og_img_width) && !empty($og_img_height)){
					$meta_tags['og:image:width'] = $og_img_width;
					$meta_tags['og:image:height'] = $og_img_height;
				}
			}
		}else{
			list($og_img_width, $og_img_height) = getimagesize($meta_tags['og:image']);
			if(!empty($og_img_width) && !empty($og_img_height)){
				$meta_tags['og:image:width'] = $og_img_width;
				$meta_tags['og:image:height'] = $og_img_height;
			}
		}
		
	}

	foreach($meta_tags as $property=>$content){
		echo '<meta property="'.$property.'" content="'.addcslashes($content, '"').'" />'."\n";
		if('og:description' === $property){
			echo '<meta name="description" content="'.addcslashes($content, '"').'" />'."\n";
		}

	}
}