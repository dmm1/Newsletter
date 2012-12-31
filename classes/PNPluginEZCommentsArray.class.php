<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class PNPluginEZCommentsArray extends PNPluginBaseArray
{
    function PNPluginEZCommentsArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        if (!ModUtil::available('EZComments')) {
            return array();
        }

        $enableML = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        $nItems   = ModUtil::getVar ('Newsletter', 'plugin_EZComments_nItems', 1);
        $params   = array();
        $params['order']    = 'items DESC';
        $params['numitems'] = $nItems;
        $params['startnum'] = 0;
        $params['ignoreml'] = true;

        if ($enableML && $lang) {
            $params['ignoreml'] = false;
            $params['language'] = $lang;
        }

        return ModUtil::apiFunc('EZComments', 'user', 'getall', $params);
    }
}
