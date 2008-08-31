<?php

function Newsletter_adminapi_get_all_subscribers($args)
{
    extract($args);

    if (!isset($startnum)) {
        $startnum = 1;
    }
    if (!isset($numitems)) {
        $numitems = -1;
    }

    if ((!isset($startnum)) ||
        (!isset($numitems))) {
        pnSessionSetVar('errormsg','Argument Error');
        return false;
    }

    $items = array();

	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_OVERVIEW)) {
        return $items;
    }
    
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
	
	if (!isset($order_by)) {
        $order_by = " ORDER BY $column[id]";
    } else {
        $order_by = " ORDER BY $column[$order_by]";
    }
    if (isset($order) && $order == 'DESC') {
         $order_by = $order_by." DESC ";
    }  
    
	$sql = "SELECT $column[id],
				   $column[user_id],
				   $column[user_name],
				   $column[user_email],
				   $column[nl_type],
				   $column[nl_frequency],
				   $column[active],
				   $column[approved],
				   $column[last_send_date],
				   $column[join_date]
			FROM $pntable[newsletter_users]";
	$sql .= $order_by;
			
	$result = $dbconn->SelectLimit($sql, $numitems, $startnum-1);
	
	if ($dbconn->ErrorNo() != 0) {
        echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
		exit();
    }
    
    for (; !$result->EOF; $result->MoveNext()) {
    list($id,
         $user_id,
         $user_name,
         $user_email,
         $nl_type,
         $nl_frequency,
         $active,
         $approved,
         $last_send_date,
         $join_date) = $result->fields;
         
    if (pnSecAuthAction(0, 'Newsletter::', "$id::", ACCESS_READ)) {
		$items[] = array('id' => $id,
						 'user_id' => $user_id,
						 'user_name' => $user_name,
						 'user_email' => $user_email,
						 'nl_type' => $nl_type,
						 'nl_frequency' => $nl_frequency,
						 'active' => $active,
						 'approved'=>$approved,
						 'last_send_date' => $last_send_date,
						 'join_date' => $join_date);
       }
    }
 $result->Close();
 
 return $items;
}

function Newsletter_adminapi_count_subscribers($args)
{
    extract($args);

	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_OVERVIEW)) {
        return false;
    }
    
    $dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];

	$sql = "SELECT count($column[id])
			FROM $pntable[newsletter_users]";			
	$result = $dbconn->Execute($sql);

	if ($dbconn->ErrorNo() != 0) {
        echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
		exit();
    }
    
    list($count) = $result->fields;
    
 $result->Close();
 
return $count;
}

function Newsletter_adminapi_remove_subscriber($args)
{
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_DELETE)) {
        return false;
    }
    
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	
 	$sql = "DELETE FROM $pntable[newsletter_users]
 			WHERE $column[id]='".(int)pnVarPrepForStore($id)."'
 			OR $column[user_name]='".pnVarPrepForStore($user_name)."'
 			OR $column[user_email]='".pnVarPrepForStore($user_email)."'";
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
		exit();
    }
    
	if($result){
		return true;
	}
}

function Newsletter_adminapi_change_user_approval($args)
{
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_ADD)) {
        return false;
    }

	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	
 	$sql = "UPDATE $pntable[newsletter_users] 
 			SET $column[approved]='".(int)pnVarPrepForStore($approved)."'
 			WHERE $column[id]='".(int)pnVarPrepForStore($id)."'";
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
		exit();
    }
    
	if($result){
		return true;
	}
}

function Newsletter_adminapi_change_user_status($args)
{
	extract($args);
	
	if (!pnSecAuthAction(0, 'Newsletter::', '::', ACCESS_EDIT)) {
        return false;
    }
    
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_users_column'];
 	
 	$sql = "UPDATE $pntable[newsletter_users] 
 			SET $column[active]='".(int)pnVarPrepForStore($status)."'
 			WHERE $column[id]='".(int)pnVarPrepForStore($id)."'";
	$result = $dbconn->Execute($sql);
	
	if ($dbconn->ErrorNo() != 0) {
        echo "DB Error: ".$dbconn->ErrorNo().": ".$dbconn->ErrorMsg()."<br />";
		exit();
    }
    
	if($result){
		return true;
	}
}

function Newsletter_adminapi_flush_files($args)
{
	extract($args);
	
	$archive_directory = pnModGetVar('Newsletter','archive_directory');
	$files = pnModAPIFunc('Newsletter','user','scandir',array('directory'=>$archive_directory));
	
		$file_count = count($files);
		$hidden = Newsletter_userapi_ignore_files();
		sort($files);
		for($i=0; $i<$file_count; $i++){			
			if(in_array($files[$i],$hidden)) continue;
			@unlink($archive_directory.'/'.$files[$i]);
		}
		
return true;
}

function Newsletter_adminapi_flush_db($args)
{
	extract($args);	
	
	$dbconn  =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();
 	$column = &$pntable['newsletter_archives_column'];	
 	$sql = "DELETE FROM $pntable[newsletter_archives]";
 	$result = $dbconn->Execute($sql);
	
	if($result) return true;
}

function Newsletter_adminapi_get_send_day()
{
	$days = array('1'=>'Monday',
				  '2'=>'Tuesday',
				  '3'=>'Wednesday',
				  '4'=>'Thursday',
				  '5'=>'Friday',
				  '6'=>'Saturday',
				  '0'=>'Sunday');
return $days;
}

function Newsletter_adminapi_get_newsletter_types($args)
{
	$types = array('1'=>'text',
				   '2'=>'html',
				   '3'=>'text w/link to web');
return $types;
}

function Newsletter_adminapi_get_newsletter_frequency()
{
	$frequency = array('1'=>'weekly',
					   '2'=>'monthly',
					   '3'=>'yearly');
	// 0=>manually				   
	//$not_used_yet = array_shift($frequency);
	
return $frequency;
}

function Newsletter_adminapi_get_archive_expire()
{
	$expiry = array('1'=>'1 month',
					'2'=>'2 months',
					'3'=>'3 months',
					'6'=>'6 months',
					'12'=>'1 year');
return $expiry;
}
// read plugin-folder from newsletter and load names into array
   function newsletter_adminapi_getnewsletterPlugins($args)
{
    $path = 'modules/Newsletter/pntemplates/plugins/';
    if (isset($args['path']) && !empty($args['path'])) {
        $path = $args['path'] . '/' . $path;
    }
if (!file_exists(DataUtil::formatForOS($path))) {
die('FEHLER: Verzeichnis ' . $path . ' existiert nicht');
}
    Loader::loadClass('FileUtil');
    $plugins = FileUtil::getFiles($path, false);
    asort($plugins);
	$pluginKeys = array_keys($plugins);
foreach($pluginKeys as $pluginKey) {
    if (!(strpos($plugins[$pluginKey], 'modifier') === false)) {
        unset($plugins[$pluginKey]);
    }
}
    return $plugins;
}


?>