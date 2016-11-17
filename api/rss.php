<?php
require('config.php');
require('func.php');

$url = METRO."?strana=1";

$images=get_images($url);

header('Content-Type: application/xml; charset=utf-8');

$rss = '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom"> 
<channel> 
<language>cs</language> 
<title>Metro hlídač</title> 
<atom:link href="http://'.$_SERVER['SERVER_NAME'].'/metro.rss" rel="self" type="application/rss+xml" /> 
<link>http://'.$_SERVER['SERVER_NAME'].'/</link> 
<description>Metro hlídač</description> 
<image> 
<url>http://gidnes.cz/u/loga-n4/metro-cz.png</url> 
<title>Metro hlídač</title> 
<link>http://'.$_SERVER['SERVER_NAME'].'/</link> 
</image>
';

foreach($images['images'] as $img){
	$datum = preg_split('/ /',$img['date']);
	$foo = preg_split('/\./',$datum[0]);
	$datum = date('r',strtotime($foo[1].'/'.$foo[0].'/'.date('Y ').$datum['1'].':00'));
$rss .= '
<item> 
<title>'.$img['date'].'</title> 
<link>'.METRO.'?idc='.$img['link'].'</link> 
<dc:creator>metro.cz</dc:creator> 
<description> 
<![CDATA[
<p> 
<img src="'.$img['image'].'" /> 
</p> 
]]>
</description> 
<pubDate>'.$datum.'</pubDate> 
<guid>'.METRO.'?idc='.$img['link'].'</guid> 
</item>
';
}

$rss .= '</channel></rss>';

print $rss;
if(!is_dir(CACHEDIR)){mkdir(CACHEDIR);};
$fp = fopen(CACHEDIR.'/metro.rss', 'w');
fwrite($fp, $rss);
fclose($fp);
