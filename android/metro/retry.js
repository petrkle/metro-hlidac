$('#loading').hide();
$('#'+containerID).append('<div class="error">Chyba načítání <a href="javascript:reload('+id+')" class="button-link">Zkusit znovu</a></div>');
$('#header').html('<a href="index.html" class="home"><h1>[% APP.name %]</h1></a>');
