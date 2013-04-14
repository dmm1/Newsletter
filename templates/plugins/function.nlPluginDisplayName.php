<?php

/**
 * Smarty function to return the display name of a plugin.
 *
 *
 * Example
 *  {nlPluginDisplayName plugin='Newsletter_NewsletterPlugin_Clip'}
 *
 * @return string the plugin display name.
 */
function smarty_function_nlPluginDisplayName($params, Zikula_View $view)
{
    $className = $params['plugin'];
    $class = new $className();
    
    $result = $class->getDisplayName();
    
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
