<?php
/*

Usage: <!--[element_categories]-->
		
Displays all categories as an array

	
	<div class="nl-content-wrapper">
		<h1>Recent topics</h1>
		<!--[element_categories assign="element_cats"]-->
			<!--[section name="element_cats" loop="$element_cats"]-->
				<h3><a href="<!--[$site_url|pnvarprepfordisplay]-->index.php?module=element&amp;func=view_category&amp;category_id=<!--[$element_cats[element_cats].category_id]-->" title="<!--[$element_cats[element_cats].category_name]-->"><!--[$element_cats[element_cats].category_name]--></a></h3>
				<p><!--[$element_cats[element_cats].category_description]--></p>
			<!--[/section]-->
	</div>
			

*/

function smarty_function_element_categories($params, &$smarty)
{
    extract($params);     
	
	if(!pnModAvailable('element') or !pnModAPILoad('element')) return;
	
	$categories = pnModAPIFunc('element','user','get_categories',array('orderby'=>'category_name'));
	
    if($params['assign']){
    	$smarty->assign($params['assign'], $categories);
    	return;
    } else {	
    	return $categories;
    }
}
?>
