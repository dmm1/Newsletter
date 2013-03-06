<?php

 /**
 * Smarty modifier decodes html entities
 *
 *
 * Example
 *  {$content|html_entity_decode}
 *
 * 
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_function_newsletter_get_plugin_title($params, Zikula_View $view)
{
    $className = $params['pluginName'];
    $class = new $className();
    return $class->getPluginTitle();
}
