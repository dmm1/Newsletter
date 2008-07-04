<?php
// ------------------------------------------------------------------------
//	
//	Imports all registered users into the Newsletter module
//
// - place this file in your web root where index.php and config.php reside
// - configure. default config is active, approved, weekly, text
// - run it in your browser - http://www.sitename.com/newsletter_import.php
// - the end.
// 
// ------------------------------------------------------------------------

	include('includes/pnAPI.php');
	pnInit();
	
	// ------------------------ CONFIGURATION ----------------------- \\
	$nl_type = 2; // TYPE: 1=text | 2=html | 3=text w/ link
	$nl_frequency = 2; // FREQUENCY: 1=weekly | 2=monthly | 3=yearly
	$nl_active = 1; // ACTIVE STATUS: 1=active | 0=suspended
	$approved = 1; // APPROVAL STATUS: 1=approved | 0=unapproved
	// -------------------------------------------------------------- \\
	
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
		
	
	echo $added.' users added.';
	exit;	
		
?>