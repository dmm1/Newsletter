<?php

function smarty_function_nl_user_menu($params, &$smarty) 
{
	unset($params);
    
	$links .= '<a href="index.php?module=Newsletter&amp;func=main" title="main">'._HOME.'</a>';
	
	if(pnUserLoggedIn() and pnModAPIFunc('Newsletter','user','check_user_exists',array('user_email'=>pnUserGetVar('email'),'approved'=>true))>0){		 
	$links .= '&nbsp;|&nbsp;'
				 .'<a href="index.php?module=Newsletter&amp;func=show_archives" title="'._VIEWARCHIVES.'">'._VIEWARCHIVES.'</a>';
		

	$links .= '&nbsp;|&nbsp;'
			 	 .'<a href="index.php?module=Newsletter&amp;func=manage_newsletter" title="'._NEWSLETTERADMIN.'">'._NEWSLETTERADMIN.'</a>';
	
	}
	
	;
	
	return $links;
}

?>