<?php

function smarty_function_defaultlang($params, Zikula_View $view)
{
    $result = System::getVar('defaultlang', System::getVar('language_i18n', 'en'));

    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}