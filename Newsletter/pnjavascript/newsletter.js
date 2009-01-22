function showInfo(location) {
	var win = new Window({className: "alphacube",
			      title: "Newsletter Update Browser", 
			      top:100, left:100, width:500, height:400, 
			      url: location,
			      showEffectOptions: {duration:1.5}})
	win.show(); 
}

function liveusersearch()
{
    Element.removeClassName('liveusersearch', 'pn-hide');
    Event.observe('modifyuser', 'click', function() { window.location.href=document.location.entrypoint + "?module=Users&type=admin&func=modify&uname=" + $F('username');}, false);
    Event.observe('deleteuser', 'click', function() { window.location.href=document.location.entrypoint + "?module=Newsletter&type=admin&func=remove_subscriber=" + $F('username');}, false);
    Event.observe('sendmail', 'click', function() { window.location.href=document.location.entrypoint + "?module=Users&type=admin&func=mailusers&uname=" + $F('username');}, false);
    new Ajax.Autocompleter('username', 'username_choices', document.location.pnbaseURL + 'ajax.php?module=Users&func=getusers',
                           {paramName: 'fragment',
                            minChars: 3,
                            afterUpdateElement: function(data){
                                Event.observe('modifyuser', 'click', function() { window.location.href=document.location.entrypoint + "?module=Users&type=admin&func=modify&userid=" + $($(data).value).value;}, false);
                                Event.observe('deleteuser', 'click', function() { window.location.href=document.location.entrypoint + "?module=Newsletter&type=admin&func=remove_subscriber=" + $($(data).value).value;}, false);
                                Event.observe('sendmail', 'click', function() { window.location.href=document.location.entrypoint + "?module=Users&type=admin&func=mailusers&userid=" + $($(data).value).value;}, false);
                                }
                            }
                            );
}

