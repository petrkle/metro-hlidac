<?php
# image details
require('config.php');
require('func.php');

if(isset($_GET['id'])){
	$id = preg_replace('/[^0-9]*/', '', $_GET['id']);
}else{
	exit();
}

$url = METRO."/?idc=$id";

$image = array();
$dom = new DOMDocument();
$html = file_get_contents($url);

$html = iconv('windows-1250', 'UTF-8', html_entity_decode(file_get_contents($url), ENT_COMPAT, 'iso-8859-1'));
$html = preg_replace('/meta charset="windows-1250"/', 'meta http-equiv="Content-Type" content="text/html; charset=utf-8"', $html);
@$dom->loadHTML($html);
$image['title']=extract_atr($dom,"//td[@class='equ-img']/a/img","alt");
$image['text']=extract_text($dom,"//div[@class='text']");
$image['authors']=extract_text($dom,"//div[@class='authors']");
$image['time']=extract_text($dom,"//span[@class='time']");
$image['img']='http:'.extract_atr($dom,"//td[@class='equ-img']/a/img","src");
$image['prev']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-prev ']","href"));
$image['next']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='img-next ']","href"));
$image=json_encode($image);
save_to_cache($url,$image);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
print $image;
