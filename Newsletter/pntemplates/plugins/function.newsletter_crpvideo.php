<?php
// ----------------------------------------------------------------------
// Zikula Application Framework
// Copyright (C) 2008 by the Zikula Development Team.
// http://www.zikula.org/

function smarty_function_newsletter_crpVideo($params, &$smarty)
{

	extract($params);
 	
	if (!pnModAvailable('crpVideo')) return;	
	if (!pnModAPILoad('crpVideo', 'user')) return;	
	if(!isset($limit_items) or $limit_items<1) $limit_items = 5;
	if(!isset($limit_text) or $limit_text<1) $limit_text = false;
	
    $dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('crpVideo');
	$table = $pntable['crpvideos'];
    $column = &$pntable['crpvideos_column'];
	$sql = "SELECT $column[videoid],
				   $column[title],
                   $column[content]
            FROM $table
            ORDER BY $column[title] DESC
            LIMIT $limit_items";
    $result = $dbconn->Execute($sql);
    
	$data = array();
    for (; !$result->EOF; $result->MoveNext()) {
    	list($videoid, $title, $content) = $result->fields;
    	if($limit_text and strlen($content)>$limit_text){
    		$content = substr($content,0,$limit_text).'..';
    	}
    	$data[] = array('crpvideos_videoid'=>$videoid,
						'crpvideos_title'=>$title,
     					'crpvideos_content'=>$content);    
    }
    $result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>