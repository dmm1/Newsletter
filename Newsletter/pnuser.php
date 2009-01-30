<?php

function Newsletter_user_main($args)
{
    // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	$user_email = pnVarCleanFromInput('user_email');
	extract($args);
	    
    $pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;

	if($user_email!=''){
		$pnRender->assign('user_email_val',' value="'.pnVarPrepForDisplay($user_email).'"');
	}
	
	if(pnUserLoggedIn()){
		$subscribed = pnModAPIFunc('Newsletter','user','check_user_exists',array('user_email'=>pnUserGetVar('email')));
	}

    $newsletter_frequency = pnModAPIFunc('Newsletter','user','get_newsletter_frequency');
    $newsletter_types = pnModAPIFunc('Newsletter','admin','get_newsletter_types');
    
    $pnRender->assign(array('type_values'=>array_keys($newsletter_types),
							'type_output'=>array_values($newsletter_types),
							'type_selected'=>pnModGetVar('Newsletter','default_type'),
							'frequency_values'=>array_keys($newsletter_frequency),
							'frequency_output'=>array_values($newsletter_frequency),
							'frequency_selected'=>pnModGetVar('Newsletter','default_frequency')));
    
    $pnRender->assign(array('allow_anon_registration'=>pnModGetVar('Newsletter','allow_anon_registration'),
    						'auto_approval'=>pnModGetVar('Newsletter','auto_approve_registrations'),
    				  		'allow_frequency_change'=>pnModGetVar('Newsletter','allow_frequency_change'),
    				  		'loggedin'=>(pnUserLoggedIn()?'1':'0'),
    				  		'subscribed'=>$subscribed));
	
return $pnRender->fetch('nl_user_main.htm');
}

function Newsletter_user_subscribe($args)
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	list($tos,
		 $user_name,
		 $user_email,
		 $nl_type,
		 $nl_frequency) = pnVarCleanFromInput('tos',
		 									  'user_name',
		 									  'user_email',
		 									  'nl_type',
		 									  'nl_frequency');
	if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
    if($tos < 1){
    	pnSessionSetVar('errormsg', _TERMS_OF_SERVICE_ERROR);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
	$loggedin = pnUserLoggedIn();
	$allow_anon = pnModGetVar('Newsletter','allow_anon_registration');
	$allow_frequency_change = pnModGetVar('Newsletter','allow_frequency_change');

	if(!$allow_frequency_change){
		$nl_frequency = pnModGetVar('Newsletter','default_frequency');
	}
	
	if(!$loggedin and !$allow_anon){
		pnSessionSetVar('errormsg', _ANON_NOT_ALLOWED);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
    if(!$loggedin and ($user_name == '' or $user_email == '')){
    	pnSessionSetVar('errormsg', _MISSING_INFO);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
    if(!$loggedin and !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $user_email)) {
		pnSessionSetVar('errormsg', _INVALID_EMAIL);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
    // check email against users
    if(pnModAPIFunc('Newsletter','user','check_user_exists',array('user_email'=>$user_email))){
    	pnSessionSetVar('errormsg', _USER_EXISTS);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
	if(pnUserLoggedIn()){
		$user_name = pnUserGetVar('uname');
		$user_email = pnUserGetVar('email');
	}
	
	$subscribe = pnModAPIFunc('Newsletter','user','subscribe',array('user_name'=>$user_name,
																	'user_email'=>$user_email,
																	'nl_type'=>$nl_type,
																	'nl_frequency'=>$nl_frequency));
	
	if($subscribe){
		$pnRender =& new pnRender('Newsletter');
		$pnRender->caching = false;
		$pnRender->assign(array('user_name'=>$user_name,
								'user_email'=>$user_email,
								'site_url'=>pnGetCurrentURL()));	
								
		$user_message = $pnRender->fetch('email_user_notify.htm');
		$newsletter_address = pnModGetVar('Newsletter','send_from_address');
		
		if(pnModGetVar('Newsletter','notify_admin')){
			$admin_message = $pnRender->fetch('email_admin_notify.htm');
			pnModAPIFunc('Newsletter','user','pnMail',array('to'=>$newsletter_address,
															'from'=>$newsletter_address,
															'subject'=>_NOTIFY_ADMIN_SUBJECT,
															'message'=>$admin_message,
															'html'=>1));
		}
		
		pnModAPIFunc('Newsletter','user','pnMail',array('to'=>$user_email,
														'from'=>$newsletter_address,
														'subject'=>_NOTIFY_USER_SUBJECT,
														'message'=>$user_message,
														'html'=>1));	
														
		pnSessionSetVar('statusmsg', _SUBSCRIPTION_SUCCESSFUL);
	} else {
		pnSessionSetVar('errormsg', _SUBSCRIPTION_FAILED);
	}

pnRedirect(pnModURL('Newsletter', 'user', 'main'));
return true;
}

function Newsletter_user_unsubscribe($args)
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	$user_email = pnVarCleanFromInput('user_email');
	
	if(pnUserLoggedIn()){
		$user_email = pnUserGetVar('email');
	}
	
	if($user_email == ''){
		pnSessionSetVar('errormsg',_UNSUBSCRIBE_FAILED);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	if(!pnModAPIFunc('Newsletter','user','is_subscribed',array('user_email'=>$user_email))){
		pnSessionSetVar('errormsg',_UNSUBSCRIBE_NO_USER);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	$unsubscribe = pnModAPIFunc('Newsletter','user','unsubscribe',array('user_email'=>$user_email));
	
	if($unsubscribe){
		$pnRender =& new pnRender('Newsletter');
		$message = $pnRender->fetch('email_user_unsubscribe.htm');
		$newsletter_address = pnModGetVar('Newsletter','send_from_address');
		pnModAPIFunc('Newsletter','user','pnMail',array('to'=>$user_email,
														'from'=>$newsletter_address,
														'subject'=>_UNSUBSCRIBE_USER_SUBJECT,
														'message'=>$message,
														'html'=>1));
		pnSessionSetVar('statusmsg',_UNSUBSCRIBE_SUCCESSFUL);
	} else {
		pnSessionSetVar('errormsg',_UNSUBSCRIBE_FAILED);
	}

pnRedirect(pnModURL('Newsletter', 'user', 'main'));
return true;
}

function Newsletter_user_show_archives()
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
    
	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;	
	
	$archive_type = pnModGetVar('Newsletter','archive_type');
	
	if($archive_type == 1){		
		$files = pnModAPIFunc('Newsletter','user','get_all_archives');	
		$file_count = count($files);
		for($i=0; $i<$file_count; $i++){
			$month = date('F',$files[$i]['archive_date']);
			$archives[$month][$i] = array('archive_name'=>$files[$i]['archive_date'],
										  'archive_date'=>date(_ARCHIVE_DATE_FORMAT,$files[$i]['archive_date']));	
		}
	} elseif($archive_type == 2){
		$archive_directory = pnModGetVar('Newsletter','archive_directory');	
		if(function_exists('scandir')){
			$files = scandir($archive_directory,1);
		} else {
			$files = pnModAPIFunc('Newsletter','user','scandir',array('directory'=>$archive_directory));
		}		
		$file_count = count($files);
		$hidden = array('.','..','.DS_Store','index.html');
		sort($files);
		for($i=0; $i<$file_count; $i++){			
			if(in_array($files[$i],$hidden)){
			   continue;
			}
			$file_date = substr($files[$i],0,-5);
			$month = date('F',$file_date);
			$archives[$month][] = array('archive_name'=>$file_date,
										'archive_date'=>date(_ARCHIVE_DATE_FORMAT,$file_date));				
		}
	}
	$pnRender->assign('archives',$archives);	
return $pnRender->fetch('nl_user_show_archives.htm');
}

function Newsletter_user_terms_of_service()
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	
return $pnRender->fetch('nl_user_terms_of_service.htm');
}

function Newsletter_user_manage_newsletter()
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	
	$user_email = pnUserGetVar('email');
	
	if($user_email == ''){
		pnSessionSetVar('errormsg',_NO_USER);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	if(!pnModAPIFunc('Newsletter','user','is_subscribed',array('user_email'=>$user_email))){
		pnSessionSetVar('errormsg',_NO_USER);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	$newsletter_types = pnModAPIFunc('Newsletter','user','get_newsletter_types');
	$newsletter_frequencies = pnModAPIFunc('Newsletter','user','get_newsletter_frequency');
	
	$user_info = pnModAPIFunc('Newsletter','user','get_subscriber_info',array('user_email'=>$user_email));
	
	if($user_info['approved'] == 0 or $user_info['user_id'] == ''){
		pnSessionSetVar('errormsg',_NO_USER);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	if($user_info['user_id'] != pnUserGetVar('uid') and $user_info['active']==1){
		pnSessionSetVar('errormsg',_NO_USER);
		pnRedirect(pnModURL('Newsletter', 'user', 'main'));
		return true;
	}
	
	
	$pnRender->assign('suspend_account_checked',($user_info['active']?'':'checked="checked" '));
	
	$pnRender->assign(array('nl_frequency_values'=>array_keys($newsletter_frequencies),
							'nl_frequency_output'=>array_values($newsletter_frequencies),
							'nl_frequency_selected'=>$user_info['nl_frequency']));
	
	$pnRender->assign(array('nl_type_values'=>array_keys($newsletter_types),
							'nl_type_output'=>array_values($newsletter_types),
							'nl_type_selected'=>$user_info['nl_type']));
	
	$pnRender->assign(array('user_id'=>$user_info['user_id'],
							'user_name'=>$user_info['user_name'],
							'user_email'=>$user_info['user_email'],
							'active'=>$user_info['active']));
	
return $pnRender->fetch('nl_user_manage_newsletter.htm');
}

function Newsletter_user_modify_subscription($args)
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	list($nl_type,
		 $nl_frequency,
		 $suspend_account) = pnVarCleanFromInput('nl_type',
		 									     'nl_frequency',
		 									     'suspend_account');
	
	if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'user', 'main'));
        return true;
    }
    
	$updated = pnModAPIFunc('Newsletter','user','update_account',array('nl_type'=>$nl_type,	
																	   'nl_frequency'=>$nl_frequency,
																	   'active'=>($suspend_account?$active=0:$active=1)));
	if($updated){
		pnSessionSetVar('statusmsg', _SUBSCRIPTION_MODIFY_SUCCESSFUL);
	} else {
		pnSessionSetVar('errormsg', _SUBSCRIPTION_MODIFY_FAILED);
	}

pnRedirect(pnModURL('Newsletter', 'user', 'main'));
return true;
}

function Newsletter_user_view_archive($args){

   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	
	$date = pnVarCleanFromInput('date');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::Archives', '::', ACCESS_READ)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
	$content = '';
	$archive_type = pnModGetVar('Newsletter','archive_type');
	if($archive_type == 1){
		$archive_info = pnModAPIFunc('Newsletter','user','get_archive_by_date',array('date'=>$date));
		if($archive_info){
			$content = $archive_info['archive_text'];
		} 
	} elseif($archive_type == 2){
		$archive_directory = pnModGetVar('Newsletter','archive_directory');
		$archive = $archive_directory.'/'.$date.'.html';		
		if(file_exists(pnVarPrepForOS($archive))){
			$content = file_get_contents($archive);
		} 
	}
	
	if($content == ''){
		pnRedirect(pnModURL('Newsletter', 'user', 'show_archives'));
		return true;
	}

echo $content;
exit;
}

function Newsletter_user_send($args)
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }
	list($admin_key,$scheduled) = pnVarCleanFromInput('admin_key','scheduled');
	extract($args);
	
	if($admin_key!=pnModGetVar('Newsletter','admin_key')) return false;

	$sent = pnModAPIFunc('Newsletter','user','send_newsletters',array('respond'=>true,'scheduled'=>($scheduled+0)));

return $sent;
}
function Newsletter_user_cancel()
{
   // Security check
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;

		if($user_email!=''){
		$pnRender->assign('user_email_val',' value="'.pnVarPrepForDisplay($user_email).'"');
	}
	
	if(pnUserLoggedIn()){
		$subscribed = pnModAPIFunc('Newsletter','user','check_user_exists',array('user_email'=>pnUserGetVar('email')));
	}

    $newsletter_frequency = pnModAPIFunc('Newsletter','user','get_newsletter_frequency');
    $newsletter_types = pnModAPIFunc('Newsletter','admin','get_newsletter_types');
    
    $pnRender->assign(array('type_values'=>array_keys($newsletter_types),
							'type_output'=>array_values($newsletter_types),
							'type_selected'=>pnModGetVar('Newsletter','default_type'),
							'frequency_values'=>array_keys($newsletter_frequency),
							'frequency_output'=>array_values($newsletter_frequency),
							'frequency_selected'=>pnModGetVar('Newsletter','default_frequency')));
    
    $pnRender->assign(array('allow_anon_registration'=>pnModGetVar('Newsletter','allow_anon_registration'),
    						'auto_approval'=>pnModGetVar('Newsletter','auto_approve_registrations'),
    				  		'allow_frequency_change'=>pnModGetVar('Newsletter','allow_frequency_change'),
    				  		'loggedin'=>(pnUserLoggedIn()?'1':'0'),
    				  		'subscribed'=>$subscribed));
							
    return $pnRender->fetch('nl_user_cancel.htm');
}



?>