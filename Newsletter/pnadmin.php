<?php

function Newsletter_admin_main()
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;

    return $pnRender->fetch('nl_admin_main.htm');
}

function Newsletter_admin_settings()
{

    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }

	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	$send_days = pnModAPIFunc('Newsletter','admin','get_send_day');
	$newsletter_types = pnModAPIFunc('Newsletter','admin','get_newsletter_types');
	$newsletter_frequency = pnModAPIFunc('Newsletter','admin','get_newsletter_frequency');
	$archive_expire = pnModAPIFunc('Newsletter','admin','get_archive_expire');
	$archive_type = pnModGetVar('Newsletter','archive_type');
	$archive_directory = pnModGetVar('Newsletter','archive_directory');
	if(is_writable($archive_directory)){
		$archive_directory_warning = '';
	} else {
		$archive_directory_warning = _NOT_WRITABLE;
	}

	switch($archive_type){
		case 1: $pnRender->assign('archive_type_1_checked','checked="checked" '); break;
		case 2: $pnRender->assign('archive_type_2_checked','checked="checked" '); break;
	}
	
	$pnRender->assign(array('send_days_values'=>array_keys($send_days),
							'send_days_output'=>array_values($send_days),
							'send_days_selected'=>pnModGetVar('Newsletter','send_day'),
							'default_type_values'=>array_keys($newsletter_types),
							'default_type_output'=>array_values($newsletter_types),
							'default_type_selected'=>pnModGetVar('Newsletter','default_type'),
							'frequency_values'=>array_keys($newsletter_frequency),
							'frequency_output'=>array_values($newsletter_frequency),
							'frequency_selected'=>pnModGetVar('Newsletter','default_frequency'),
							'archive_expire_values'=>array_keys($archive_expire),
							'archive_expire_output'=>array_values($archive_expire),
							'archive_expire_selected'=>pnModGetVar('Newsletter','archive_expire')));
							
	$pnRender->assign(array('allow_anon_registration_checked'=>(pnModGetVar('Newsletter','allow_anon_registration')?'checked="checked" ':''),
							'auto_approve_registrations_checked'=>(pnModGetVar('Newsletter','auto_approve_registrations')?'checked="checked" ':''),
							'allow_frequency_change_checked'=>(pnModGetVar('Newsletter','allow_frequency_change')?'checked="checked" ':''),
							'personalize_email_checked'=>(pnModGetVar('Newsletter','personalize_email')?'checked="checked" ':''),
							'notify_admin_checked'=>(pnModGetVar('Newsletter','notify_admin')?'checked="checked" ':''),
							'archive_directory_warning'=>$archive_directory_warning,
							'max_send_per_hour'=>pnModGetVar('Newsletter','max_send_per_hour'),
							'last_execution_time'=>(pnModGetVar('Newsletter','end_execution_time')-pnModGetVar('Newsletter','start_execution_time'))));

    return $pnRender->fetch('nl_admin_settings.htm');

}

function Newsletter_admin_config_update($args)
{
	list($send_from_address,
	
		 $archive_directory,
		 $notify_admin,
		 $allow_anon_registration,
		 $auto_approve_registrations,
		 $default_type,
		 $default_frequency,
		 $allow_frequency_change,
		 $send_day,
		 $archive_type,
		 $send_per_request,
		 $personalize_email,
		 $admin_key,
		 $max_send_per_hour,
		 $archive_expire) = pnVarCleanFromInput('send_from_address',
		 										'archive_directory',
		 										'notify_admin',
		 										'allow_anon_registration',
		 										'auto_approve_registrations',
		 										'default_type',
		 										'default_frequency',
		 										'allow_frequency_change',
		 										'send_day',
		 										'archive_type',
		 										'send_per_request',
		 										'personalize_email',
		 										'admin_key',
		 										'max_send_per_hour',
		 										'archive_expire');
		 										
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'settings'));
        return true;
    }

    if(substr($archive_directory,-1)=='/'){
		$archive_directory = substr($archive_directory,0,strlen($archive_directory)-1);
	}
	
	if(!is_writable($archive_directory)){
		$archive_type = '1';
	}
	
    if($default_frequency == ''){
    	$allow_frequency_change = '0';
    }
    
	pnModSetVar('Newsletter','archive_type',$archive_type);
	pnModSetVar('Newsletter','send_from_address',$send_from_address);
	pnModSetVar('Newsletter','archive_directory',$archive_directory);
	pnModSetVar('Newsletter','notify_admin',$notify_admin);
	pnModSetVar('Newsletter','allow_anon_registration',$allow_anon_registration);
  	pnModSetVar('Newsletter','auto_approve_registrations',$auto_approve_registrations);
  	pnModSetVar('Newsletter','default_type',$default_type); 
  	pnModSetVar('Newsletter','default_frequency',$default_frequency);
  	pnModSetVar('Newsletter','allow_frequency_change',$allow_frequency_change);
  	pnModSetVar('Newsletter','send_day',$send_day);	
  	pnModSetVar('Newsletter','archive_expire',$archive_expire); // months
  	pnModSetVar('Newsletter','personalize_email',($personalize_email+0));
  	pnModSetVar('Newsletter','send_per_request',$send_per_request);
	pnModSetVar('Newsletter','import_per_request',$import_per_request);
  	pnModSetVar('Newsletter','admin_key',$admin_key);
  	pnModSetVar('Newsletter','max_send_per_hour',((int)$max_send_per_hour+0));

pnSessionSetVar('statusmsg', _CONFIG_UPDATE_SUCCESSFUL);
pnRedirect(pnModURL('Newsletter', 'admin', 'settings'));
return true;
}

function Newsletter_admin_view_subscribers()
{
	list($startnum,$order_by,$order,$itemsperpage) = pnVarCleanFromInput('startnum','order_by','order','itemsperpage');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
 	
	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	
	if($itemsperpage){
		pnSessionSetVar('newsetter_view_subscribers_ipp',(int)$itemsperpage);
	} else {
		$itemsperpage = pnSessionGetVar('newsetter_view_subscribers_ipp');
	}
	if($itemsperpage<10){
		$itemsperpage = 10; 
	} 
	$pnRender->assign('itemsperpage',$itemsperpage);
    
    $newsletter_types = pnModAPIFunc('Newsletter','admin','get_newsletter_types');
	$newsletter_frequency = pnModAPIFunc('Newsletter','admin','get_newsletter_frequency');
    $subscribers = pnModAPIFunc('Newsletter', 'admin', 'get_all_subscribers', 
    							array('startnum' => $startnum,
                                      'order_by'  => $order_by,
                                	  'order'    => $order,
                                	  'numitems' => $itemsperpage));

	if ($order == 'ASC' or !isset($order)) {
        $order = 'DESC';
    } else {
        $order = 'ASC';
    }
	$pnRender->assign('order', $order);
	// re-build array
	foreach($subscribers as $s){
		$subscription_info[] = array('id' => $s['id'],
						 			 'user_id' => $s['user_id'],
						 			 'user_name' => $s['user_name'],
									 'user_email' => $s['user_email'],
									 'nl_type' => $newsletter_types[$s['nl_type']],
									 'nl_frequency' =>$newsletter_frequency[$s['nl_frequency']],
									 'active_val' =>$s['active'],
									 'active_text'=>($s['active']?$s['active']=_ACTIVE:$s['active']=_INACTIVE),
									 'approved_val'=>$s['approved'],
									 'approved_text'=>($s['approved']?'Approved':'Unapproved'),
									 'last_send_date' => ($s['last_send_date']?date(_SUBSCRIBER_DATE_FORMAT,$s['last_send_date']):'-'),
									 'join_date' => date(_SUBSCRIBER_DATE_FORMAT,$s['join_date']));
	
	}
	
	$pnRender->assign('items', $subscription_info);
   
   $pnRender->assign('pager', array('numitems' => pnModAPIFunc('Newsletter', 'admin', 'count_subscribers'), 'itemsperpage' => $itemsperpage));

return $pnRender->fetch('nl_admin_view_subscribers.htm');	
}

function Newsletter_admin_remove_subscriber()
{
	list($id,
		 $user_name,
		 $user_email) = pnVarCleanFromInput('id',
		 									'user_name', 'user_email');
	extract($args);	 
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }	
	
    if((isset($id) and $id != '') 
    	or (isset($user_name) and $user_name != '') 	
    	or (isset($user_email) and $user_email != '')){
    	
		
    	if (!pnSecConfirmAuthKey()) {
        	pnSessionSetVar('errormsg', _BADAUTHKEY);
        	pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        	return true;
    	}
    	
    	$remove = pnModAPIFunc('Newsletter','admin','remove_subscriber',array('id'=>$id,
    																		  'user_name'=>$user_name,																			  
																			  'user_email'=>$user_email));
								
    	if($remove){
    		pnSessionSetVar('statusmsg', _DELETE_SUCCESSFUL);
    	} else {
    		pnSessionSetVar('errorsmsg', _DELETE_FAILED);
    	}
    }
    
pnRedirect(pnModURL('Newsletter', 'admin', 'delete_user'));
return true;
}


function Newsletter_admin_change_user_approval_status($args)
{
	list($id,$approved) = pnVarCleanFromInput('id','approved');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }
    
    if($id == '' or $approved == ''){
    	pnSessionSetVar('errormsg', _ERROR);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }	
    
    $pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
    
    switch($approved){
    	case 0: $new_approved = '1'; break;
    	case 1: $new_approved = '0'; break;
    }

    if($new_approved == ''){    	
   		pnSessionSetVar('errormsg', _ERROR);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }	
    
    $set = pnModAPIFunc('Newsletter','admin','change_user_approval',array('approved'=>$new_approved,'id'=>$id));
    
    if($set){
    	pnSessionSetVar('statusmsg', _USER_UPDATED);
    } else {
    	pnSessionSetVar('errormsg', _ERROR);
    }
    
pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
return true;
}


function Newsletter_admin_change_user_status($args)
{
	list($id,$status) = pnVarCleanFromInput('id','status');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_EDIT)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }
    
    if($id == '' or $status == ''){
    	pnSessionSetVar('errormsg', _ERROR);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }	
    
    switch($status){
    	case 0: $new_status = '1'; break;
    	case 1: $new_status = '0'; break;
    }

    if($new_status == ''){    	
   		pnSessionSetVar('errormsg', _ERROR);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }	
    
    $set = pnModAPIFunc('Newsletter','admin','change_user_status',array('status'=>$new_status,'id'=>$id));
    
    if($set){
    	pnSessionSetVar('statusmsg', _USER_UPDATED);
    } else {
    	pnSessionSetVar('errormsg', _ERROR);
    }
    
pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
return true;
}

function Newsletter_admin_flush_archives($args)
{
	list($file_archive,$db_archive) = pnVarCleanFromInput('file_archive','db_archive');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_DELETE)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
	if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'flush_archives'));
        return true;
    }
    
    if($file_archive){
    	pnModAPIFunc('Newsletter','admin','flush_files');
    }
	if($db_archive){
		pnModAPIFunc('Newsletter','admin','flush_db');
	}

pnSessionSetVar('statusmsg', 'Archives flushed.');
pnRedirect(pnModURL('Newsletter', 'admin', 'settings'));
return true;
}

function Newsletter_admin_display_template($args){
	$nl_type = pnVarCleanFromInput('nl_type');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter:Archives:', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if($nl_type == ''){
		pnRedirect(pnModURL('Newsletter', 'admin', 'settings'));
		return true;
	}

	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	
	$site_name = pnConfigGetVar('sitename');
	$site_url = pnGetBaseURL();
	$pnRender->assign(array('title'=>$site_name.' Newsletter',
							'site_name'=>$site_name,
							'site_url'=>$site_url,
							'archive_link'=>$site_url,
							'unsubscribe_link'=>$site_url,
							'user_name'=>pnUserGetVar('uname'),
							'show_header'=>'1'));
							
	switch ($nl_type){
		case 1: 
		$content = $pnRender->fetch('newsletter_template_text.htm'); 
		$content = str_replace(array("\n","\r"),'<br />',$content);
		break;
		case 2: $content = $pnRender->fetch('newsletter_template_html.htm'); break;
		case 3: 
		$content = $pnRender->fetch('newsletter_template_text_with_link.htm'); 
		$content = str_replace(array("\n","\r"),'<br />',$content);
		break;
	}
	
echo $content;		
exit;
}

function Newsletter_admin_send2users($args)
{
	list($subscriber_ids,$update_send_dates) = pnVarCleanFromInput('subscriber_ids','update_send_dates');
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }	
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
        return true;
    }
    
    $pnRender =& new pnRender('Newsletter',false);
    $site_name = pnConfigGetVar('sitename');
    $site_url = pnGetBaseURL();
    $send_from_address = pnModGetVar('Newsletter','send_from_address');	
	if($send_from_address == ''){
		$send_from_address = pnConfigGetVar('adminmail');
	}
	
	sort($subscriber_ids);
    $subscr_count = count($subscriber_ids);
    if($subscr_count<1){
    	pnSessionSetVar('errormsg', 'No accounts selected.');	
		pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
		return true;
	}
	$pnRender->assign(array('title'=>$site_name.' Newsletter',
							'site_name'=>$site_name,
							'show_header'=>'1',
							'site_url'=>$site_url));
	$subject = 'Newsletter - '.$site_name;
    for($i=0,$amt_sent=0; $i<$subscr_count; $i++){
    	$subscr_info = pnModAPIFunc('Newsletter','user','get_subscriber_by_id',array('subscriber_id'=>$subscriber_ids[$i]));
    	if($subscr_info){
    		$pnRender->assign(array('user_name'=>$subscr_info['user_name'],
    								'user_email'=>$subscr_info['user_email']));
    		switch($subscr_info['nl_type']){				
				case 1: $message = $pnRender->fetch('newsletter_template_text.htm'); $html = 0;	break;
				case 2: $message = $pnRender->fetch('newsletter_template_html.htm'); $html = 1; break;
				case 3: $message = $pnRender->fetch('newsletter_template_text.htm'); $html = 0; break; // special case: there is no archive to view.
			}						
			$sent = pnModAPIFunc('Newsletter','user','pnMail',array('to'=>$subscr_info['user_email'],
																	'from'=>$send_from_address,
																	'subject'=>$subject,
																	'message'=>$message,
																	'html'=>$html));

    		if($sent){
    			if($update_send_dates) pnModAPIFunc('Newsletter','user','update_last_send_date',array('id'=>$subscriber_ids[$i]));
    			$amt_sent++;
    		}
    	}
    }
    
pnSessionSetVar('statusmsg', $amt_sent.' Newsletter(s) sent successfully.');
pnRedirect(pnModURL('Newsletter', 'admin', 'view_subscribers'));
return true;
}

function Newsletter_admin_preview_template()
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;

    return $pnRender->fetch('nl_admin_preview.htm');
}
function Newsletter_admin_import()
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;

    return $pnRender->fetch('nl_admin_import.htm');
}

function Newsletter_admin_import_update($args)
{
	list($import_per_request,
		 $import_active_status,
	     $import_approval_status,  
		 $import_frequency) = pnVarCleanFromInput('import_per_request',	
													'import_active_status',
													'import_approval_status',
													'import_frequency');	 
										  
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }
    
    if (!pnSecConfirmAuthKey()) {
        pnSessionSetVar('errormsg', _BADAUTHKEY);
        pnRedirect(pnModURL('Newsletter', 'admin', 'import'));
        return true;
    }
    
	pnModSetVar('Newsletter','import_per_request',$import_per_request);
	pnModSetVar('Newsletter','import_active_status',  $import_active_status);
	pnModSetVar('Newsletter','import_approval_status',  $import_approval_status);
	pnModSetVar('Newsletter','import_frequency',  $import_frequency);

pnSessionSetVar('statusmsg', _CONFIG_UPDATE_SUCCESSFUL);
pnRedirect(pnModURL('Newsletter', 'admin', 'import'));
return true;
}

function Newsletter_admin_import_get_users()
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

	$nl_type = pnModGetVar('Newsletter','import_per_request'); 
	$nl_frequency = pnModGetVar('Newsletter','import_frequency'); 
	$nl_active = pnModGetVar('Newsletter','import_active_status'); 
	$approved = pnModGetVar('Newsletter','import_approval_status');	

	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
    pnModDBInfoLoad('Newsletter');
    $user_column = &$pntable['users_column'];
 	$nl_column = &$pntable['newsletter_users_column'];
 	
	$sql = "SELECT $user_column[uid],
 				   $user_column[uname],
 				   $user_column[email]
 			FROM $pntable[users]
 			WHERE $user_column[uid]>'1'";
 		$result = $dbconn->Execute($sql);
 		
 		if ($dbconn->ErrorNo() != 0) {
        	die($dbconn->ErrorMsg());
    	}
    	
    $added = 0;
    $join_date = time();
    for (; !$result->EOF; $result->MoveNext()) {
 		list($user_id,
 			 $user_name,
 			 $user_email) = $result->fields;
 			 
    	$ins = "INSERT IGNORE INTO $pntable[newsletter_users]
    			VALUES ('',
    					'".pnVarPrepForStore($user_id)."',
						'".pnVarPrepForStore($user_name)."',
						'".pnVarPrepForStore($user_email)."',
						'".pnVarPrepForStore($nl_type)."',
						'".pnVarPrepForStore($nl_frequency)."',
						'".pnVarPrepForStore($nl_active)."',
						'".pnVarPrepForStore($approved)."',
						'0',
						'".pnVarPrepForStore($join_date)."')";
		$dbconn->Execute($ins);
		
	$added++;	
	}

pnSessionSetVar('statusmsg', _USERIMPORT_FINISH);
pnRedirect(pnModURL('Newsletter', 'admin', 'import'));
return true;
}
function Newsletter_admin_delete_user()
{
    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;

    return $pnRender->fetch('nl_admin_delete_user.htm');
}
function Newsletter_admin_view_category()
{
	  if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $pnRender =& new pnRender('Newsletter');
    $pnRender->caching = false;
   
    return $pnRender->fetch('nl_admin_category.htm');
}
function newsletter_admin_modifynewsletter($args)
{

    if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_NOACCESS);
    }

	$pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;	
						
	$pnRender->assign(array('newsplugin_checked'=>(pnModGetVar('Newsletter','newsplugin')?'checked="checked" ':''),
							'newmemberplugin_checked'=>(pnModGetVar('Newsletter','newmemberplugin')?'checked="checked" ':''),
							'pagesplugin_checked'=>(pnModGetVar('Newsletter','pagesplugin')?'checked="checked" ':''),
							'adminmessagesplugin_checked'=>(pnModGetVar('Newsletter','adminmessagesplugin')?'checked="checked" ':'')));

    return $pnRender->fetch('nl_admin_modifyplugins.htm');
}

function newsletter_admin_updateplugins($args)
{					
list($newsplugin,$newmemberplugin,$pagesplugin,
	 $adminmessagesplugin) = pnVarCleanFromInput('newsplugin',
												'newmemberplugin',
												'pagesplugin',
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
	
  	pnModSetVar('Newsletter','newsplugin',$newsplugin);
	pnModSetVar('Newsletter','newmemberplugin',$newmemberplugin);
	pnModSetVar('Newsletter','adminmessagesplugin',$adminmessagesplugin);
	pnModSetVar('Newsletter','pagesplugin',$pagesplugin);

pnSessionSetVar('statusmsg', _CONFIG_UPDATE_SUCCESSFUL);
pnRedirect(pnModURL('Newsletter', 'admin', 'modifynewsletter'));
return true;   
}
?>