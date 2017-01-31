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
	if($foo->item(0)){
		return trim($foo->item(0)->getAttribute($attr));
	}else{
		return "";
	}
}

function url2fn($url){
	$url = str_replace(METRO,'',$url);
	return preg_replace('/[^a-z0-9]*/','',$url).'.json';
}

function save_to_cache($url,$content){
	if(!is_dir(CACHEDIR)){mkdir(CACHEDIR);};
	$fp = fopen(CACHEDIR.'/'.url2fn($url), 'w');
	fwrite($fp, $content);
	fclose($fp);
}

function extract_userid($link){
	return preg_replace('/.*users\/([0-9]+)\/.*/','\1',$link);
}

function get_images($url){
	$images = array();
	$images['images'] = array();
	$dom = new DOMDocument();
	$html = file_get_contents($url);
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$nodes = $xpath->query('//div[@class="hp-list"]/div[@class="box-in"]/a');

	$images['next']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='ico-right' and contains(@href,'strana=')]","href"));
	$images['prev']=preg_replace('/.*=/','',extract_atr($dom,"//a[@class='ico-left' and contains(@href,'strana=')]","href"));

	foreach ($nodes as $node) {
		$img['link']=preg_replace('/.*=/','',extract_attr_fn(".","href",$node,$xpath));
		$img['date']=extract_text_fn(".//span",$node,$xpath);
		$img['image']='http:'.extract_attr_fn(".//img","src",$node,$xpath);
		$img['alt']=extract_attr_fn(".//img","alt",$node,$xpath);
		array_push($images['images'],$img);
	}
	return $images;
}

function win2utf($html){
	$html = iconv('windows-1250', 'UTF-8', html_entity_decode($html, ENT_COMPAT, 'iso-8859-1'));
	return preg_replace('/meta.*windows-1250"/', 'meta http-equiv="Content-Type" content="text/html; charset=utf-8"', $html);
}
