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
 	
	$dbconn =& pnDBGetConn(true);
	$pntable =& pnDBGetTables();
	$column = &$pntable['links_links_column'];

    $sql = "SELECT $column[lid], 

    			   $column[title],
    			   $column[description],
    		FROM $pntable[links_links]";
  /*   if(isset($category_id)){
    	$sql .= " WHERE $column[cat_id]='".(int)pnVarPrepForStore($category_id)."' ";
    } */

	$sql .= "ORDER BY $column[date] DESC";
				
	if(isset($limit_items) AND $limit_items != ''){
    	$sql .= " LIMIT ".(int)pnVarPrepForStore($limit_items)."";
    } else {
    	$sql .= " LIMIT 5";
	}
	
    $result = $dbconn->Execute($sql);
    
    if($dbconn->ErrorNo()<>0) {
		return $dbconn->ErrorMsg();
    }
		
	if($result->EOF){
    	return;
    }
    
    for (; !$result->EOF; $result->MoveNext()) {
    	list($lid, $cat_id, $title, $description) = $result->fields;
    	$data[] = array('weblink_id'=>$lid,
     					'weblink_category_id'=>$cat_id,
     					'weblink_title'=>$title,
     					'weblink_descrption'=>$description);    
    }

	$result->Close();
	
	if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $data);
    } else {	
		return $data;
	}
}

?>