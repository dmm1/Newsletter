<?php
// ----------------------------------------------------------------------
// Zikula Application Framework
// Copyright (C) 2008 by the Zikula Development Team.
// http://www.zikula.org/
// ----------------------------------------------------------------------

function smarty_function_newsletter_crpcalendar($params, &$smarty)
{

	extract($params);
 	
	if (!pnModAvailable('crpcalendar')) return;	
	if (!pnModAPILoad('crpcalendar', 'user')) return;	
	if(!isset($limit_items) or $limit_items<1) $limit_items = 5;
	if(!isset($limit_text) or $limit_text<1) $limit_text = false;
	
    $dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('crpcalendar');
	$table = $pntable['crpcalendar'];
    $column = &$pntable['crpcalendar_column'];
	$sql = "SELECT $column[eventid],
				   $column[title],
                   $column[event_text],
				   $column[location],
				   $column[start_date]
            FROM $table
            ORDER BY $column[title] DESC
            LIMIT $limit_items";
    $result = $dbconn->Execute($sql);
    
	$data = array();
    for (; !$result->EOF; $result->MoveNext()) {
    	list($eventid, $title, $start_date, $location, $event_text) = $result->fields;
    	if($limit_text and strlen($content)>$limit_text){
    		$content = substr($content,0,$limit_text).'..';
    	}
    	$data[] = array('crpcalendar_eventid'=>$eventid,
						'crpcalendar_title'=>$title,
						'crpcalendar_start_date'=>$start_date,
						'crpcalendar_location'=>$location,
     					'crpcalendar_event_text'=>$event_text);    
    }
    $result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>