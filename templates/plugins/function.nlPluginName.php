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
