<?php

function mdToHTML($markdown){
	$markdown = preg_replace('/!\[[^\]]*\]\((assets\/[^\)]+)\)/', '![]('.$GLOBALS['config']->base_url.'$1)', $markdown);
	$Parsedown = new Parsedown();
	return $Parsedown->text($markdown);
}