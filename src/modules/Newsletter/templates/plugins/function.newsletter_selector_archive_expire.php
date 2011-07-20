<?php

function smarty_function_newsletter_selector_archive_expire($params, &$smarty) 
{
    $returnKeys = isset($params['return_keys']) ? $params['return_keys'] : false;

    $array = Newsletter_Util::getSelectorDataArchiveExpire();
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
