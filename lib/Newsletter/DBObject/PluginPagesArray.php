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

class Newsletter_DBObject_PluginPagesArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginPagesArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function getPluginData($lang=null)
    {
        if (!ModUtil::available('Pages')) {
            return array();
        }

        $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
        $nItems   = ModUtil::getVar('Newsletter', 'plugin_Pages_nItems', 1);
        $params   = array();
        $params['order']    = 'pageid DESC';
        $params['numitems'] = $nItems;
        $params['startnum'] = 0;
        $params['ignoreml'] = true;

        if ($enableML && $lang) {
            $params['ignoreml'] = false;
            $params['language'] = $lang;
        }

        return ModUtil::apiFunc('Pages', 'user', 'getall', $params);
    }
}

