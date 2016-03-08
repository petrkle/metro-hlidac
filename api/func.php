<?php

function extract_atr($dom,$query,$atr){
	$navrat = "";
	$xpath = new DOMXPath($dom);
	$vysledek = $xpath->query($query);
	foreach ($vysledek as $hodnota) {
		$navrat = trim($hodnota->getAttribute($atr));
	}
	return $navrat;
}

function extract_text($dom,$query){
	$navrat = "";
	$xpath = new DOMXPath($dom);
	$desc = $xpath->query($query);
	foreach ($desc as $popis) {
		$navrat .= trim($popis->nodeValue);
	}
	$remove = array("\n", "\r\n", "\r");
	$navrat = str_replace($remove, ' ', $navrat);
	$navrat = preg_replace('/  /',' ',$navrat);
	$navrat = preg_replace('/^_ /','',$navrat);
	return $navrat;
}

function extract_text_fn($query,$node,$xpath){
	$foo = $xpath->query($query,$node);
	return trim($foo->item(0)->nodeValue);
}

function extract_attr_fn($query,$attr,$node,$xpath){
	$foo = $xpath->query($query,$node);
	return trim($foo->item(0)->getAttribute($attr));
}

function is_cached($url){
	$fn=CACHEDIR.'/'.url2fn($url);
	if(is_readable($fn) and (time()-filemtime($fn) <= CACHETIME)){
		return true;
	}else{
		return false;
	}
}

function url2fn($url){
	$url = str_replace(METRO,'',$url);
	return preg_replace('/[^a-z0-9]*/','',$url);
}

function save_to_cache($url,$content){
	if(!is_dir(CACHEDIR)){mkdir(CACHEDIR);};
	$fp = fopen(CACHEDIR.'/'.url2fn($url), 'w');
	fwrite($fp, $content);
	fclose($fp);
}

function load_from_cache($url){
	return file_get_contents(CACHEDIR.'/'.url2fn($url));
}

function extract_userid($link){
	return preg_replace('/.*users\/([0-9]+)\/.*/','\1',$link);
}

function get_images($url){
	$images = array();
	$images['images'] = array();
	$dom = new DOMDocument();
	$html = file_get_contents($url);
	$html = iconv('windows-1250', 'UTF-8', html_entity_decode(file_get_contents($url), ENT_COMPAT, 'iso-8859-1'));
	$html = preg_replace('/meta charset="windows-1250"/', 'meta http-equiv="Content-Type" content="text/html; charset=utf-8"', $html);
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$nodes = $xpath->query('//div[@class="hp-list"]/div[@class="box-in"]/a');

	$images['next']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='ico-right' and contains(@href,'strana=')]","href"));
	$images['prev']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='ico-left' and contains(@href,'strana=')]","href"));

	foreach ($nodes as $node) {
		$img['link']=preg_replace('/.*=/','',extract_attr_fn(".","href",$node,$xpath));
		$img['date']=extract_text_fn(".//span",$node,$xpath);
		$img['image']='https:'.extract_attr_fn(".//img","src",$node,$xpath);
		$img['alt']=extract_attr_fn(".//img","alt",$node,$xpath);
		array_push($images['images'],$img);
	}
	return $images;
}

