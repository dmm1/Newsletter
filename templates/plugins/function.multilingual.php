<?php

function smarty_function_multilingual($params, Zikula_View $view)
{
    $result = System::getVar('multilingual', false);

    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {	
        return $result;
    }
}