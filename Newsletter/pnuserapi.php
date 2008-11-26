<?php


function Newsletter_userapi_get_subscriber_info($args)
{
	extract($args);

	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	
 	$sql = "SELECT $column[user_id],
 				   $column[user_name],
 				   $column[user_email],
 				   $column[nl_type],
 				   $column[nl_frequency],
 				   $column[active],
 				   $column[approved]
 			FROM $pntable[newsletter_users]
 			WHERE $column[approved]='1'
 			AND $column[user_email]='".pnVarPrepForStore($user_email)."'";
 		$result = $dbconn->Execute($sql);
 		
 		if ($dbconn->ErrorNo() != 0) {
        	die($dbconn->ErrorMsg());
    	}
    	
    	
 		list($user_id,
 			 $user_name,
 			 $user_email,
 			 $nl_type,
 			 $nl_frequency,
 			 $active,
 			 $approved) = $result->fields;
 		
 		$info = array('user_id'=>$user_id,
 					  'user_name'=>$user_name,
 					  'user_email'=>$user_email,
 					  'nl_type'=>$nl_type,
 					  'nl_frequency'=>$nl_frequency,
 					  'active'=>$active,
 					  'approved'=>$approved);

return $info; 		
}

function Newsletter_userapi_get_subscriber_by_id($args)
{
	extract($args);

	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	
 	$sql = "SELECT $column[user_id],
 				   $column[user_name],
 				   $column[user_email],
 				   $column[nl_type],
 				   $column[nl_frequency],
 				   $column[active],
 				   $column[approved]
 			FROM $pntable[newsletter_users]
 			WHERE $column[approved]='1'
 			AND $column[active]='1'
 			AND $column[id]='".pnVarPrepForStore($subscriber_id)."'";
 		$result = $dbconn->Execute($sql);
 		
 		if ($dbconn->ErrorNo() != 0) {
        	die($dbconn->ErrorMsg());
    	}
    	
    	if($result->EOF) return false;
    	
 		list($user_id,
 			 $user_name,
 			 $user_email,
 			 $nl_type,
 			 $nl_frequency,
 			 $active,
 			 $approved) = $result->fields;
 		
 		$info = array('user_id'=>$user_id,
 					  'user_name'=>$user_name,
 					  'user_email'=>$user_email,
 					  'nl_type'=>$nl_type,
 					  'nl_frequency'=>$nl_frequency,
 					  'active'=>$active,
 					  'approved'=>$approved);

return $info; 		
}

function Newsletter_userapi_update_account($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
	$nextid = $dbconn->GenId($pntable['newsletter_users_column']);
	
	
	$sql = "UPDATE $pntable[newsletter_users]
			SET $column[nl_type]='".pnVarPrepForStore($nl_type)."',
				$column[nl_frequency]='".pnVarPrepForStore($nl_frequency)."',
				$column[active]='".pnVarPrepForStore($active)."'
			WHERE $column[user_id]='".pnUserGetVar('uid')."'";
					
	$result = $dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }
	
	if($result){
		return true;
	}
}


function Newsletter_userapi_subscribe($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
	$nextid = $dbconn->GenId($pntable['newsletter_users_column']);
	$time = time();
	$auto_approve = pnModGetVar('Newsletter','auto_approve_registrations');
	($auto_approve?$approved=1:$approved=0);
	$active = 1;
	$user_id = ($user_id!=''?$user_id:pnUserGetVar('uid'));
	
	$sql = "INSERT INTO $pntable[newsletter_users]
			VALUES ('".$nextid."',
					'".pnVarPrepForStore($user_id)."',
					'".pnVarPrepForStore($user_name)."',
					'".pnVarPrepForStore($user_email)."',
					'".pnVarPrepForStore($nl_type)."',
					'".pnVarPrepForStore($nl_frequency)."',
					'".pnVarPrepForStore($active)."',
					'".pnVarPrepForStore($approved)."',
					'0',
					'".pnVarPrepForStore($time)."')";
					
	$result = $dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }
	
	if($result){
		return true;
	}
}

function Newsletter_userapi_unsubscribe($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$nl_column = &$pntable['newsletter_users_column'];	
 	$user_column = &$pntable['users_column'];
 	
 	// hack. should be it's own function
 	if(!pnUserLoggedIn()){
 		$user_id = pnUserGetVar('uid');
 		$check = "SELECT count($user_column[uid])
 				  FROM $pntable[users]
 				  WHERE  $user_column[uid]='".pnVarPrepForStore($user_id)."'
 				  AND $user_column[email]='".pnVarPrepForStore($user_email)."'";
 		$result = $dbconn->Execute($check);
 		list($count) = $result->fields;
 		if($count){
 			pnSessionSetVar('errormsg', _UNSUBSCRIBE_ANON);
 			pnRedirect(pnModURL('Newsletter', 'user', 'main'));
 			return false;
 		}
 	}
 	
 	$sql = "DELETE FROM $pntable[newsletter_users]
 			WHERE $nl_column[user_email]='".pnVarPrepForStore($user_email)."'";
 	$result = $dbconn->Execute($sql);
 	
 	if($result){
 		return true;
 	}
}

function Newsletter_userapi_is_subscribed($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$nl_column = &$pntable['newsletter_users_column'];
	
	$sql = "SELECT COUNT($nl_column[user_id])
			FROM $pntable[newsletter_users]";
	$where = array();
	if((int)$user_id>1){
		$where[] = "$nl_column[user_id]='".(int)$user_id."'";
	}
	if(strlen($user_email)){
		$where[] = "$nl_column[user_email]='".pnVarPrepForStore($user_email)."'";		
	}
	if(count($where)){
		$sql .= " WHERE ".implode(' AND ',$where)."";
	}

	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }    
    
	list($nl_count) = $result->fields;
	$result->Close();

return $nl_count;
}

function Newsletter_userapi_get_recipient_count($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	$last_week = strtotime("-7 days");
	
	$sql = "SELECT COUNT($column[user_id])
			FROM $pntable[newsletter_users]
			WHERE $column[active]='1'
			AND $column[approved]='1'
			AND $column[last_send_date]<'".$last_week."'";			
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }    
	list($count) = $result->fields;
	$result->Close();

return $count;
}

function Newsletter_userapi_get_all_recipients($args)
{
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	$last_week = strtotime("-7 days");
 	
 	$allow_frequency_change = pnModGetVar('Newsletter','allow_frequency_change');
 	$default_frequency = pnModGetVar('Newsletter','default_frequency');
			
	$sql = "SELECT $column[id],
 				   $column[user_name],
 				   $column[user_email],
 				   $column[nl_type],
 				   $column[nl_frequency],
 				   $column[active],
 				   $column[approved],
 				   $column[last_send_date]
 			FROM $pntable[newsletter_users]
 			WHERE $column[active]='1'
			AND $column[approved]='1'
			AND $column[last_send_date]<'".$last_week."'";
 		$result = $dbconn->Execute($sql);
 		
 		if ($dbconn->ErrorNo() != 0) {
        	echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
			exit();
    	}
    
    for (; !$result->EOF; $result->MoveNext()) {
 		list($id,
 			 $user_name,
 			 $user_email,
 			 $nl_type,
 			 $nl_frequency,
 			 $active,
 			 $approved,
 			 $last_send_date) = $result->fields;

 			 if(!$allow_frequency_change){
 			 	$nl_frequency = $default_frequency;
 			 }
 			 
 			 switch($nl_frequency){
 			 	case 1: ($last_send_date<=strtotime('-1 week')?$send_now = true:$send_now = false); break;
 				case 2: ($last_send_date<=strtotime('-1 month')?$send_now = true:$send_now = false); break; 
 				case 3: ($last_send_date<=strtotime('-1 year')?$send_now = true:$send_now = false); break;
 			}

 		$info[] = array('id'=>$id,
 					  'user_name'=>$user_name,
 					  'user_email'=>$user_email,
 					  'nl_type'=>$nl_type,
 					  'nl_frequency'=>$nl_frequency,
 					  'active'=>$active,
 					  'approved'=>$approved,
 					  'last_send_date'=>$last_send_date,
 					  'send_now'=>$send_now);
	}
	
	$result->Close();

return $info; 
}

function Newsletter_userapi_update_last_send_date($args){
	
	extract($args);
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
	$time = mktime(0,0,0,date('m'),date('d'),date('Y'));	
	
	$sql = "UPDATE $pntable[newsletter_users]
			SET $column[last_send_date]='".(int)pnVarPrepForStore($time)."'
			WHERE $column[id]='".(int)pnVarPrepForStore($id)."'";
					
	$result = $dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }
	
	if($result){
		return true;
	}

}

function Newsletter_userapi_insert_archive($args){	
	extract($args);	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_archives_column'];	
	$sql = "INSERT INTO $pntable[newsletter_archives]
			VALUES('".(int)pnVarPrepForStore($archive_time)."','".pnVarPrepForStore($archive_content)."')";					
	$result = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }	
	if($result){
		return true;
	}
}

function Newsletter_userapi_get_recent_archive($args){	
	extract($args);	
	$items = array();
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_archives_column'];	
 	$last_week = mktime(0,0,0,date('m'),date('d')-7,date('y'));
	$sql = "SELECT $column[archive_date], $column[archive_text] 
			FROM $pntable[newsletter_archives]
			WHERE $column[archive_date]>'".$last_week."'";					
	$result = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }	
	if(!$result->EOF){
		list($archive_date,
 			 $archive_text) = $result->fields;
 		$items = array('archive_date'=>$archive_date,
 					   'archive_text'=>$archive_text);
	}
	
return $items;
}

function Newsletter_userapi_get_archive_by_date($args){	
	extract($args);	
	if($date == '') {
		return;
	}
	$items = array();
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_archives_column'];	
	$sql = "SELECT $column[archive_date], $column[archive_text] 
			FROM $pntable[newsletter_archives]
			WHERE $column[archive_date]='".pnVarPrepForStore($date)."'";					
	$result = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }	
	if(!$result->EOF){
		list($archive_date,
 			 $archive_text) = $result->fields;
 		$items = array('archive_date'=>$archive_date,
 					   'archive_text'=>$archive_text);
	}
	$result->close();
	
return $items;
}

function Newsletter_userapi_get_all_archives($args)
{

	extract($args);	
	$items = array();
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_archives_column'];	
	$sql = "SELECT $column[archive_date], $column[archive_text] 
			FROM $pntable[newsletter_archives]
			ORDER BY $column[archive_date]";					
	$result = $dbconn->Execute($sql);
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }	
	if(!$result->EOF){
		for (; !$result->EOF; $result->MoveNext()) {
			list($archive_date,
				 $archive_text) = $result->fields;
			$items[] = array('archive_date'=>$archive_date,
						     'archive_text'=>$archive_text);
		}
	}
	$result->close();
	
return $items;
}

function Newsletter_userapi_prune_archives()
{

	$archive_type = pnModGetVar('Newsletter','archive_type');
	$archive_expire = pnModGetVar('Newsletter','archive_expire');
	$expire_date = strtotime('-'.$archive_expire.' months');

	if($archive_type == 1){	
		$dbconn  =& pnDBGetConn(true);
    	$pntable =& pnDBGetTables();
 		$column = &$pntable['newsletter_archives_column'];	
		$sql = "DELETE FROM $pntable[newsletter_archives]
				WHERE $column[archive_date]<'".$expire_date."'";
		$result = $dbconn->Execute($sql);
	} elseif($archive_type == 2){
		$archive_directory = pnModGetVar('Newsletter','archive_directory');
		$time = time();
		$files = Newsletter_userapi_scandir(array('directory'=>$archive_directory));
		$file_count = count($files);
		
		$hidden = Newsletter_userapi_ignore_files();
		rsort($files);
		for($i=0; $i<$file_count; $i++){		
			$archive_date = substr($files[$i],0,-5);
			if(in_array($files[$i],$hidden) OR 
			   $archive_date > $expire_date){
			   continue;
			}
			if($archive_date < $expire_date){
				unlink($archive_directory.'/'.$files[$i]);
			} 			
		}	
	}
}


function Newsletter_userapi_check_user_exists($args)
{
	extract($args);

	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_OVERVIEW)) {
        return;
    }
	
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$user_column = &$pntable['users_column'];
 	$nl_column = &$pntable['newsletter_users_column'];
    
	$sql = "SELECT COUNT($user_column[uid])
			FROM $pntable[users]
			WHERE $user_column[email]='".pnVarPrepForStore($user_email)."'";			
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }    
	list($user_count) = $result->fields;
	$result->Close();
	
	$sql = "SELECT COUNT($nl_column[user_id])
			FROM $pntable[newsletter_users]
			WHERE $nl_column[user_email]='".pnVarPrepForStore($user_email)."'";	
			
	if(isset($approved)){
		$sql .= " AND $nl_column[approved]='1'";
	}
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }    
	list($nl_count) = $result->fields;
	$result->Close();
	
	$count = ($user_count+$nl_count);
		
	if(pnUserLoggedIn()){
		$count--;
	}
	
	$result->Close();
	
	if($count > 0){
		 return $count;
	}
}

function Newsletter_userapi_get_user_by_email($args)
{
	extract($args);

	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_OVERVIEW)) {
        return;
    }
	
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
    
	$sql = "SELECT $column[id],
				   $column[user_name],
				   $column[nl_type],
				   $column[nl_frequency],
				   $column[active],
				   $column[approved],
				   $column[last_send_date],
				   $column[join_date]
			FROM $pntable[newsletter_users]
			WHERE $column[user_email]='".pnVarPrepForStore($user_email)."'";			
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        die($dbconn->ErrorMsg());
    }    
	list($id,
		 $user_name,
		 $nl_type,
		 $nl_frequency,
		 $active,
		 $approved,
		 $last_send_date,
		 $join_date) = $result->fields;
		 
	$info = array('id'=>$id,
				  'user_name'=>$user_name,
				  'nl_type'=>$nl_type,
				  'nl_frequency'=>$nl_frequency,
				  'active'=>$active,
				  'approved'=>$approved,
				  'last_send_date'=>$last_send_date,
				  'join_date'=>$join_date);
		 
	$result->Close();
	
	return $info;
}


function Newsletter_userapi_get_send_day()
{
pnModLangLoad('Newsletter', 'user');

	$days = array('1'=> pnML('_MONDAY'),
				  '2'=> pnML('_TUESDAY'), 
				  '3'=> pnML('_WEDNESDAY'),
				  '4'=> pnML('_THURSDAY'), 
				  '5'=> pnML('_FRIDAY'), 
				  '6'=> pnML('_SATURDAY'), 
				  '0'=> pnML('_SUNDAY'));
return $days;
}
	
function Newsletter_userapi_get_newsletter_types($args)
{
pnModLangLoad('Newsletter', 'user');

	$types = array('1'=> pnML('_TEXT'),
				   '2'=> pnML('_HTML'),
				   '3'=> pnML('_TEXTWLINK'));
return $types;
}

function Newsletter_userapi_get_newsletter_frequency()
{
pnModLangLoad('Newsletter', 'user');

	$frequency = array('1'=> pnML('_WEEKLY'),  
					   '2'=> pnML('_MONTHLY'),  
					   '3'=> pnML('_YEARLY'));
return $frequency;
}

function Newsletter_userapi_scandir($args)
{
	extract($args);
	
	if(function_exists('scandir')){
		$files = scandir($directory,1);
	} else {
		$dir = opendir($directory);
		while ($file_name = readdir($dir)) {
   			$files[] = $file_name;
		}
	}

return $files;
}

function Newsletter_userapi_pnMail($args)
{
	extract($args);
	
	// pnmail function - the default is missing from_address.. 
    if(empty($to) || !isset($subject)) {
    	return false;
    }
		
	if($from == ''){
    	$from = pnConfigGetVar('adminmail');
    }
    
    $return = false;

    if((pnModAvailable('Mailer')) && (pnModAPILoad('Mailer', 'user'))) {
        $return = pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $to,
        															  'fromaddress'=>$from,
                                                                      'subject' => $subject,
                                                                      'body' => $message,
                                                                      'html' => $html));
   	}

    return $return;
}

function Newsletter_userapi_send_newsletters($args)
{
	extract($args);
	// support for the scheduler mod - cron task
	// $scheduled = 1; // over-rides send_day
	// $send_all = 1; // over-rides send_limit per request
	
	if(isset($scheduled) and $scheduled=='1'){
		$scheduled = true;
	} else {
		$scheduled = false;
	}
	
	if(isset($send_all) and $send_all=='1'){
		$send_all = true;
	} else {
		$send_all = false;
	}
	
	set_time_limit(90);
	ignore_user_abort(true);
		
	if(!$scheduled){
		$send_day = pnModGetVar('Newsletter','send_day');
		$today = date('w',time());
		if($send_day != $today){	
			return;
		}
	}
	
	$max_per_hour = pnModGetVar('Newsletter','max_send_per_hour');
	if($max_per_hour){
		$spam_stuff = pnModGetVar('Newsletter','spam_count');
		$spam_array=explode('-',$spam_stuff);
		// if now minus start of send is more than an hour and more than "x" have been sent, stop the process
		// step two: if the hour is greater than when we started, reset counters (and set spam array, used below)
		if((time()-$spam_array['0'])>=3600 and $spam_array['1']>=$max_per_hour and date('G')==date('G',$spam_array['0'])){
			return 'spam limits encountered';
			exit;
		} else if (date('G')>date('G',$spam_array['0'])){
			pnModSetVar('Newsletter','spam_count',time().'-0');
			$spam_array = array(time(),'0');
		}
	}
	
	$recipient_count = pnModAPIFunc('Newsletter','user','get_recipient_count');
	if($recipient_count == 0){
		return;
	}
	
	pnModSetVar('Newsletter','start_execution_time',(float)array_sum(explode(' ', microtime())));
	
	$pnRender =& new pnRender('Newsletter');
	if(!$personalize){
		$pnRender->caching = true;
	} else {
		$pnRender->caching = false;		
	}
	
	$today = mktime(0,0,0,date('m'),date('d'),date('y'));
	$personalize = pnModGetVar('Newsletter','personalize_email');
	$recipients = pnModAPIFunc('Newsletter','user','get_all_recipients');
	$frequency = pnModAPIFunc('Newsletter','user','get_newsletter_frequency');
	$site_name = pnConfigGetVar('sitename');
	$site_path = pnGetBaseURL();
	$send_per_request = pnModGetVar('Newsletter','send_per_request');
	$send_from_address = pnModGetVar('Newsletter','send_from_address');
	
	if($send_from_address == ''){
		$send_from_address = pnConfigGetVar('adminmail');
	}
	
	$archive_type = pnModGetVar('Newsletter','archive_type');
	
	if($archive_type == 1){
		$archive_info = pnModAPIFunc('Newsletter','user','get_recent_archive');
		if(count($archive_info)){
			$new_archive_time = $archive_info['archive_date'];			
			$matched = true;
		} else {
			$new_archive_time = $today;
			$matched = false;
		}
	} elseif($archive_type == 2){
		$archive_directory = pnModGetVar('Newsletter','archive_directory');
		$new_archive = $archive_directory.'/'.$today.'.html';
		$new_archive_time = $today;
		
		$files = pnModAPIFunc('Newsletter','user','scandir',array('directory'=>$archive_directory));
				
		$file_count = count($files);
		$hidden = Newsletter_userapi_ignore_files();
		sort($files);
		for($i=0; $i<$file_count; $i++){			
			if(in_array($files[$i],$hidden)){
				$file_count--;
			    continue;
			}
			
			$archive_date = date('z',substr($files[$i],0,-5));					
			if((date('z',$today)-$archive_date)<=7){
				$new_archive_time = substr($files[$i],0,-5);
				$new_archive = $files[$i];
				$matched = true;
				$i = $file_count;
			} 			
		}		
	}

	// alows the use of stylesheets .. overridden below for archive
	$pnRender->assign('show_header','1');
	
	if(!$personalize){
		$pnRender->cache_id = $new_archive_time;
	}
	
	
	$pnRender->assign(array('site_url'=>$site_path,
							'site_name'=>$site_name,
							'archive_time'=>$new_archive_time));		
	$newsletters_sent=0;
	for($i=0; $i<$recipient_count; $i++){	
		if($recipients[$i]['send_now']){	
	
			$subject = ucwords(strtolower($frequency[$recipients[$i]['nl_frequency']])).' '._NEWSLETTER.' - '.$site_name;
			$pnRender->assign(array('user_name'=>($personalize?$recipients[$i]['user_name']:_SUBSCRIBER),
									'user_email'=>($personalize?$recipients[$i]['user_email']:''),
									'title'=>$subject));	

			switch($recipients[$i]['nl_type']){				
				case 1: $message = $pnRender->fetch('newsletter_template_text.htm'); $html = 0;	break;
				case 2: $message = $pnRender->fetch('newsletter_template_html.htm'); $html = 1; break;
				case 3: $message = $pnRender->fetch('newsletter_template_text_with_link.htm'); $html = 0; break;
			}
						
			if(!$recipients[$i]['sent']){
				$sent = pnModAPIFunc('Newsletter','user','pnMail',array('to'=>$recipients[$i]['user_email'],
																		'from'=>$send_from_address,
																		'subject'=>$subject,
																		'message'=>$message,
																		'html'=>$html));
				$recipients[$i]['sent'] = true; // weird duplicates.
			}
			if($sent){
				pnModAPIFunc('Newsletter','user','update_last_send_date',array('id'=>$recipients[$i]['id']));
				if($i==$send_per_request or $send_per_request==''){ 	
					$i=$recipient_count;
				}
			}		
		$newsletters_sent++;
		}
	}
	
	if($max_per_hour){
		// track the time and the amount of newsletters set per hour.
		pnModSetVar('Newsletter','spam_count',$spam_array['0'].'-'.($spam_array['1']+$newsletters_sent));
	}
	
	if(!$matched){
	    // headers in archive = bad .. this overrides the first declaration.
		$pnRender->assign(array('show_header'=>'1',
								'site_url'=>$site_path,
								'user_name'=>'',
								'title'=>$site_name.' Newsletter'));
		$html_archive_content = $pnRender->fetch('newsletter_template_html.htm');
		if($archive_type == 1){
			pnModAPIFunc('Newsletter','user','insert_archive',array('archive_time'=>$new_archive_time,
																    'archive_content'=>$html_archive_content));
		} elseif($archive_type == 2){	
			if(function_exists('file_put_contents')){
				file_put_contents($new_archive,$html_archive_content);
			} else {
				$handle = fopen($new_archive, 'wb');
				fwrite($handle, $html_archive_content);
				fclose($handle);
			}
		}
	}
	
	pnModSetVar('Newsletter','end_execution_time',(float)array_sum(explode(' ', microtime())));
	
	if($respond){
		return "$newsletters_sent";
	}
	
return;
}

function Newsletter_userapi_ignore_files()
{
	return array('.','..','.DS_Store','index.html');
}
?>