<?php
# image details
require('config.php');
require('func.php');

if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	exit();
}

$url = METRO."/me-hlidac.aspx?idc=$id";

if(is_cached($url)){
	$image=load_from_cache($url);
}else{
	$image = array();
	$dom = new DOMDocument();
	@$dom->loadHTML(file_get_contents($url));

	$image['title']=extract_text($dom,"//h1");
	$image['text']=extract_text($dom,"//div[@class='text']");
	$image['authors']=extract_text($dom,"//div[@class='authors']");
	$image['time']=extract_text($dom,"//span[@class='time']");
	$image['img']=extract_atr($dom,"//td[@class='equ-img']/a/img","src");
	$image['prev']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-prev ']","href"));
	$image['next']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-next ']","href"));

	$image=json_encode($image);
	save_to_cache($url,$image);
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
print $image;
