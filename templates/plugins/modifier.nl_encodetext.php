<?php
/**
 * Smarty modifier converts special characters to html encoded entities.
 *
 * Example
 *  {$content|nl_encodetext}
 *
 * @author Devin Hayes
 * @since  2/4/2006
 * @param  array $string The contents to transform.
 * @return string The modified output.
 */
function smarty_modifier_nl_encodetext($string)
{
    return Newsletter_Util::encodeText($string);
}
