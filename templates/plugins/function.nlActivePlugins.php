<?php

 /**
 * Smarty function to return all active plugins
 *
 *
 * Example
 *  {nlActivePlugins assign='activePlugins'}
 *
 * @return array   the active plugins
 */
function smarty_function_nlActivePlugins($params, Zikula_View $view)
{
    $result = Newsletter_Util::getActivePlugins();
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
