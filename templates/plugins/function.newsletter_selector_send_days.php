<?php

function smarty_function_newsletter_selector_send_days($params, Zikula_View $view) 
{
    $returnKeys = isset($params['return_keys']) ? $params['return_keys'] : false;

    $array = Newsletter_Util::getSelectorDataSendDay();
    if ($returnKeys) {
        $result = array_keys($array);
    } else {
        $result = array_values($array);
    }
    
    if (isset($params['assign'])) {
        $view->assign($params['assign'], $result);
    } else {    
        return $result;
    }
}
