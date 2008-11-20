<?php
function newsletter_admin_modifynewsletter($args)
{

    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }

	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;	
						
	$pnRender->assign(array('newsplugin_checked'=>(pnModGetVar('Newsletter','newsplugin')?'checked="checked" ':''),
							'newmemberplugin_checked'=>(pnModGetVar('Newsletter','newmemberplugin')?'checked="checked" ':''),
							'activenewsplugin_checked'=>(pnModGetVar('Newsletter','activenewsplugin')?'checked="checked" ':''),
							'pagesplugin_checked'=>(pnModGetVar('Newsletter','pagesplugin')?'checked="checked" ':''),
							'crpVideoplugin_checked'=>(pnModGetVar('Newsletter','crpVideoplugin')?'checked="checked" ':''),
							'crpcalendarplugin_checked'=>(pnModGetVar('Newsletter','crpcalendarplugin')?'checked="checked" ':''),
							'mediashareplugin_checked'=>(pnModGetVar('Newsletter','mediashareplugin')?'checked="checked" ':''),
							'adminmessagesplugin_checked'=>(pnModGetVar('Newsletter','adminmessagesplugin')?'checked="checked" ':'')));

							
    return $pnRender->fetch('nl_admin_modifyplugins.htm');
}

function newsletter_admin_updateplugins($args)



{					
list($newsplugin,$newmemberplugin,$pagesplugin,$crpVideoplugin,$activenewsplugin,$crpcalendarplugin, $mediashareplugin,$how_many_news_plugin,
	 $adminmessagesplugin) = pnVarCleanFromInput('newsplugin',
												'newmemberplugin',
												'pagesplugin',
												'crpVideoplugin',
												'crpcalendarplugin',
												'mediashareplugin',
												'activenewsplugin',	
												'how_many_news_plugin',
		 										'adminmessagesplugin');
		 										
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'modifynewsletter'));
        return true;
    }
	
    if($newsplugin == ''){
    	$newsplugin = '0';
    }
	 if($adminmessagesplugin == ''){
    	$adminmessagesplugin = '0';
    }
	
	
	 if($newmemberplugin == ''){
    	$newmemberplugin = '0';
    }
     if($pagesplugin == ''){
    	$pagesplugin = '0';
    }
	 if($crpVideoplugin == ''){
    	$crpVideoplugin = '0';
    }
		 if($crpcalendarplugin == ''){
    	$crpcalendarplugin = '0';
    }
		 if($mediashareplugin == ''){
    	$mediashareplugin = '0';
    }
		 if($activenewsplugin == ''){
    	$activenewsplugin = '0';
    }
  	pnModSetVar('Newsletter','newsplugin',$newsplugin);
	pnModSetVar('Newsletter','crpVideoplugin',$crpVideoplugin);
	pnModSetVar('Newsletter','newmemberplugin',$newmemberplugin);
	pnModSetVar('Newsletter','adminmessagesplugin',$adminmessagesplugin);
	pnModSetVar('Newsletter','how_many_news_plugin',$how_many_news_plugin);
	pnModSetVar('Newsletter','crpcalendarplugin',$crpcalendarplugin);
	pnModSetVar('Newsletter','how_many_news_plugin',$how_many_news_plugin);
	pnModSetVar('Newsletter','mediashareplugin',$mediashareplugin);
	pnModSetVar('Newsletter','activenewsplugin',$activenewsplugin);
	pnModSetVar('Newsletter','pagesplugin',$pagesplugin);
	
pnSessionSetVar('statusmsg', _CONFIG_UPDATE_SUCCESSFUL);
pnRedirect(pnModURL('Newsletter', 'admin', 'modifynewsletter'));
return true;   
}




?>