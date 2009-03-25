<?php

 /**
 * Smarty modifier decodes html entities
 *
 *
 * Example
 *  <!--[$content|html_entity_decode]-->
 *
 * 
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_html_entity_decode($data)
{	
    return html_entity_decode($data);
}

