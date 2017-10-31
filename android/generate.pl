#!/usr/bin/perl
use strict;
use warnings;
use utf8;
use Template;
use File::Copy;

my $OUT = "app/src/main/assets/www";
my $appversion = `grep versionCode app/build.gradle | sed "s/[^0-9]*//"`;

my $APP = {
	'api' => "https://mh.kle.cz",
	'name' => "Metro hlídač",
	'hlidacURL' => "http://praha.idnes.cz/me-hlidac.aspx",
	'version' => $appversion
};

my @PAGES = ( "index", "img");

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
copy("metro/img/loading.svg","$OUT/loading.svg");
copy("metro/img/home.svg","$OUT/home.svg");
copy("metro/img/reload.svg","$OUT/reload.svg");
copy("metro/jquery-1.12.4.min.js","$OUT/jquery.js");
copy("metro/jquery.touchSwipe-1.6.18.min.js","$OUT/ts.js");
