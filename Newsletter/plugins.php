<?php
function newsletter_admin_modifynewsletter($args)
{

   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;	
						
    $pnRender->assign(pnModGetVar('Newsletter'));
							
    return $pnRender->fetch('nl_admin_modifyplugins.htm');
}

function newsletter_admin_updateplugins($args)
{					
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }
	  // Update module variables.
	$newsplugin = (bool) FormUtil :: getPassedValue('newsplugin', false, 'POST');
	pnModSetVar('Newsletter', 'newsplugin', $newsplugin);
	$adminmessagesplugin = (bool) FormUtil :: getPassedValue('adminmessagesplugin', false, 'POST');
	pnModSetVar('Newsletter', 'adminmessagesplugin', $adminmessagesplugin);
	$newmemberplugin = (bool) FormUtil :: getPassedValue('newmemberplugin', false, 'POST');
	pnModSetVar('Newsletter', 'newmemberplugin', $newmemberplugin);
	$pagesplugin = (bool) FormUtil :: getPassedValue('pagesplugin', false, 'POST');
	pnModSetVar('Newsletter', 'pagesplugin', $pagesplugin);
	$crpVideoplugin = (bool) FormUtil :: getPassedValue('crpVideoplugin', false, 'POST');
	pnModSetVar('Newsletter', 'crpVideoplugin', $crpVideoplugin);
	$crpcalendarplugin = (bool) FormUtil :: getPassedValue('crpcalendarplugin', false, 'POST');
	pnModSetVar('Newsletter', 'crpcalendarplugin', $crpcalendarplugin);
	$custom_mailsplugin = (bool) FormUtil :: getPassedValue('custom_mailsplugin', false, 'POST');
	pnModSetVar('Newsletter', 'custom_mailsplugin', $custom_mailsplugin);
	
	
    $how_many_news_plugin = (int)FormUtil::getPassedValue('how_many_news_plugin', 3, 'POST');
    pnModSetVar('Newsletter', 'how_many_news_plugin', $how_many_news_plugin);
	$how_many_weblinks_plugin = (int)FormUtil::getPassedValue('how_many_weblinks_plugin', 3, 'POST');
    pnModSetVar('Newsletter', 'how_many_weblinks_plugin', $how_many_weblinks_plugin);
	
pnSessionSetVar('statusmsg', _CONFIG_UPDATE_SUCCESSFUL);
pnRedirect(pnModURL('Newsletter', 'admin', 'modifynewsletter'));
return true;   
}




?>