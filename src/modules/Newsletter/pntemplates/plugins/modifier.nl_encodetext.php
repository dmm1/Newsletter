<?php

 /**
 * Smarty modifier converts special characters to html encoded entities
 *
 *
 * Example
 *  <!--[$content|nl_cleantext]-->
 *
 * 
 * @author       Devin Hayes
 * @since        2/4/2006
 * @param        array    $string     the contents to transform
 * @return       string   the modified output
 */
function smarty_modifier_nl_encodetext ($string)
{
    return NewsletterUtil::encodeText ($string);
}

