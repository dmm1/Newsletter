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
function smarty_function_nlActivePlugins($params, Zikula_View $view)
{
    $result = Newsletter_Util::getActivePlugins();
    if (isset($params['assign'])) {
        $view->assign ($params['assign'], $result);
    } else {    
        return $result;
    }
}
