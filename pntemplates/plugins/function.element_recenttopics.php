<?php
/*

Usage: <!--[element_recenttopics numitems="10"]-->
		
Displays 10 recent element forum topics

	
	<div class="nl-content-wrapper">
		<h1>Recent topics</h1>
		<!--[element_recenttopics numitems="10" assign="element"]-->
			<!--[section name="element" loop="$element"]-->
				<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?module=element&amp;func=view_topic&amp;topic_id=<!--[$element[element].topic_id]-->" title="<!--[$element[element].topic]-->"><!--[$element[element].topic]--></a></h3>
			<!--[/section]-->
	</div>
			

*/
function smarty_function_element_recenttopics($params, &$smarty)
{
    extract($params);     
	
	if(!pnModAvailable('element') or !pnModAPILoad('element')) return;
	
	if((int)$numitems<1) $numitems = 5;
	
	$recent = pnModAPIFunc('element','user','get_recent_topics',array('numitems'=>$numitems,'orderby'=>'t.last_active','order'=>'DESC'));

    if($params['assign']){
    	$smarty->assign($params['assign'], $recent);
    	return;
    } else {	
    	return $recent;
    }
}
?>
