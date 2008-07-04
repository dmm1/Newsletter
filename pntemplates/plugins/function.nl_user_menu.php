<?php

function smarty_function_nl_user_menu($params, &$smarty) 
{
	unset($params);
    
	$links = '<div style="text-align:center; margin:15px auto 25px auto;">';
	
	$links .= '<a href="index.php?module=Newsletter&amp;func=main" title="main">'._NEWSLETTER.'</a>';
			 
	if (pnSecAuthAction(0, 'Newsletter::Archives', '::', ACCESS_READ)) {
		$links .= '&nbsp;|&nbsp;'
				 .'<a href="index.php?module=Newsletter&amp;func=show_archives" title="view archives">'._VIEWARCHIVES.'</a>';
	}
	
	if(pnUserLoggedIn() and pnModAPIFunc('Newsletter','user','check_user_exists',array('user_email'=>pnUserGetVar('email'),'approved'=>true))>0){		 
		$links .= '&nbsp;|&nbsp;'
			 	 .'<a href="index.php?module=Newsletter&amp;func=manage_newsletter" title="manage newsletter">'._NEWSLETTER_MGMT.'</a>';
	}
	
	$links .= '</div>';
	
	return $links;
}

?>