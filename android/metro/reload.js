function reload(page){
	window.location.hash = page;
	location.reload();
	window.scrollTo(0,0);
	return false;
}
