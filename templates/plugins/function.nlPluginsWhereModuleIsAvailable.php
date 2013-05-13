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
function smarty_function_nlPluginsWhereModuleIsAvailable($params, Zikula_View $view)
{
    $result = Newsletter_Util::getPluginsWhereModuleIsAvailable();
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
