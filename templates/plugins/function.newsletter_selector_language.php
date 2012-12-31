<?php

function smarty_function_newsletter_selector_language($params, &$smarty) 
{
    $all        = isset($params['all']) ? $params['all'] : false;
    $returnKeys = isset($params['return_keys']) ? $params['return_keys'] : false;

    $array = Newsletter_Util::getSelectorDataLanguage($all);
    if ($returnKeys) {
        $result = array_keys($array);
    } else {
        $result = array_values($array);
    }
    
    if (isset($params['assign'])) {
    	$smarty->assign($params['assign'], $result);
    } else {	
        return $result;
    }
}
