<?php

function smarty_function_newsletter_admin_messages($params, &$smarty) 
{
	extract($params);
    
    if (!pnModAvailable('Admin_Messages')) {
		return;
	}

    $messages = pnModAPIFunc('Admin_Messages', 'user', 'getactive');

    foreach($messages as $message){
    	// to do: deal with $message['view'];
		$data[] = array('title'=>$message['title'],
						'content'=>$message['content'],
						'date'=>$message['date']);
	}

	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>