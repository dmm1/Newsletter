<?php

/**
 * Smarty function to return the plugin name of a plugin.
 *
 *
 * Example
 *  {nlPluginName plugin='Newsletter_NewsletterPlugin_Clip'}
 *
 * @return string the name of the plugin.
 */
function smarty_function_nlPluginName($params, Zikula_View $view)
{
    $className = $params['plugin'];
    $class = new $className();
    
    $result = $class->getName();
    
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
