<?php
/* 

Usage: <!--[newsletter_weblinks limit_items="2"]-->
		- OR - 
	   <!--[newsletter_weblinks limit_items="2" category_id="5"]-->
		
Displays 2 recent Weblinks from category_id 5

	
	<div class="nl-description-wrapper">
		<h1>Recently Added Links</h1>
		<!--[newsletter_weblinks limit_items="5" assign="link"]-->
			<!--[section name="link" loop="$link"]-->
				<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?name=Web_Links&amp;req=visit&amp;lid=<!--[$link[link].weblink_id]-->" title="<!--[$link[link].weblink_title|pnvarprepfordisplay]-->"><!--[$link[link].weblink_title]--></a></h3>
				<div><!--[$link[link].weblink_descrption|pnvarprephtmldisplay]--></div>
			<!--[/section]-->
	</div> */
			
function smarty_function_newsletter_weblinks($params, &$smarty)
{
	extract($params);
 	
	if (!pnModAvailable('Web_Links')) return;	
	if (!pnModAPILoad('Web_Links', 'user')) return;	
	if(!isset($limit_items) or $limit_items<1) $limit_items = 5;
	if(!isset($limit_text) or $limit_text<1) $limit_text = false;
	
    $dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	pnModDBInfoLoad('Web_Links');
	$table = $pntable['links_links'];
    $column = &$pntable['links_links_column'];
	$sql = "SELECT $column[lid],
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
    	$data[] = array('links_links_videoid'=>$lid,
						'links_links_title'=>$title,
     					'links_links_content'=>$content);    
    }
    $result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>