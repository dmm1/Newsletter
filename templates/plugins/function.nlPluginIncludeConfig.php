<?php

/**
 * Smarty function to render the configuration of a plugin.
 *
 *
 * Example
 *  {nlPluginIncludeConfig plugin='Newsletter_NewsletterPlugin_Clip'}
 *
 * @return string the rendered plugin configuration.
 */
function smarty_function_nlPluginIncludeConfig($params, Zikula_View $view)
{
    $className = $params['plugin'];
    $class = new $className();
    
    $module = $class->getModuleWherePlacedIn();
    $pluginName = $class->getName();

    $path = "plugin_config/$pluginName.tpl";

    $extView = Zikula_View::getInstance($module);
    
    $vars = $view->get_template_vars();
    foreach($vars as $name => $value)
    {
        switch (strtolower($name)) {
            case 'zikula_view':
            case 'zikula_core':
            case 'modvars':
            case 'metatags':
            case 'coredata':
            case 'servicemanager':
            case 'eventmanager':
                break;
            default:
                $extView->assign($name, $value);
        }
    }

    return $extView->fetch($path);
}
