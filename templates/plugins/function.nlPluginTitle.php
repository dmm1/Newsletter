<?php

/**
 * Smarty function to return the title of a plugin.
 *
 *
 * Example
 *  {nlPluginTitle plugin='Newsletter_NewsletterPlugin_Clip'}
 *
 * @return string the title of the plugin.
 */
function smarty_function_nlPluginTitle($params, Zikula_View $view)
{
    $className = $params['plugin'];
    $class = new $className();
    
    $result = $class->getTitle();

    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
