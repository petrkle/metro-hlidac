<?php
# image details
require('config.php');
require('func.php');

if(isset($_GET['id'])){
	$id = preg_replace('/[^0-9]*/', '', $_GET['id']);
}else{
	exit();
}

$url = METRO."?idc=$id";

$image = array();
$dom = new DOMDocument();
$html = win2utf(file_get_contents($url));

@$dom->loadHTML($html);
$image['title']=extract_text($dom,"//div[@class='text']/h3");
$image['text']=extract_text($dom,"//div[@class='text']/p");
$image['authors']=extract_text($dom,"//span[@class='autor']");
$image['time']=extract_text($dom,"//span[@class='time']");
$image['img']='http:'.extract_atr($dom,"//td[@class='equ-img']/img","src");
$image['prev']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-prev ']","href"));
$image['next']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-next ']","href"));
$image=json_encode($image);
save_to_cache($url,$image);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
print $image;
