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

class Newsletter_DBObject_PluginEZCommentsArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginEZCommentsArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('EZComments');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!$this->pluginAvailable()) {
            return array();
        }
        if (empty($lang)) {
            $lang = System::getVar('language_i18n', 'en');
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
        $params['status'] = 0; //Only activated comments (status isn't 'waiting')

        $items = ModUtil::apiFunc('EZComments', 'user', 'getall', $params);

        foreach (array_keys($items) as $k) {
            if ($filtAfterDate && $items[$k]['date'] < $filtAfterDate) {
                // filter by date is given, remove older data
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['subject'];
                $items[$k]['nl_url_title'] = $items[$k]['url'].'&newlang='.$lang;
                $items[$k]['nl_content'] = $items[$k]['comment'];
                $items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
