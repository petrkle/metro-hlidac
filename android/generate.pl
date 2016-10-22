#!/usr/bin/perl
use strict;
use warnings;
use utf8;
use Template;
use XML::Simple;
use File::Copy;

my $xml = new XML::Simple;
my $manifest = $xml->XMLin("AndroidManifest.xml");

my $OUT = "assets/www";

my $APP = {
	'api' => "https://kle.cz/metro-hlidac/api",
	'name' => "Metro hlídač",
	'hlidacURL' => "http://praha.idnes.cz/me-hlidac.aspx",
	'version' => $manifest->{'android:versionName'}
};

my @PAGES = ( "index", "img", "about" );

my $t = Template->new({
		INCLUDE_PATH => 'metro',
		ENCODING => 'utf8',
});

foreach my $page (@PAGES){
	$t->process("$page.html",
		{'APP' => $APP},
		"$OUT/$page.html",
		{ binmode => ':utf8' }) or die $t->error;
}

copy("metro/m.css","$OUT/m.css");
copy("metro/img/loading.gif","$OUT/loading.gif");
copy("metro/img/home.png","$OUT/home.png");
copy("metro/jquery-1.12.4.min.js","$OUT/jquery.js");
