<?php

/**
 * Smarty function to return the description of a plugin.
 *
 *
 * Example
 *  {nlPluginDescription plugin='Newsletter_NewsletterPlugin_Clip'}
 *
 * @return string the plugin description.
 */
function smarty_function_nlPluginDescription($params, Zikula_View $view)
{
    $className = $params['plugin'];
    $class = new $className();
    
    $result = $class->getDescription();
    
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
