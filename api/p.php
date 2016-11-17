<?php
# images list
require('config.php');
require('func.php');

if(isset($_GET['page'])){
	$page = preg_replace('/[^0-9]*/', '', $_GET['page']);
}else{
	$page = 1;
}

$url = METRO."/?strana=$page";

$images=json_encode(get_images($url));
save_to_cache($url,$images);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
print $images;
