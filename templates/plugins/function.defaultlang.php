<?php

function smarty_function_defaultlang($params, Zikula_View $view)
{
    $result = System::getVar('defaultlang', 'eng');

    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {	
        return $result;
    }
}