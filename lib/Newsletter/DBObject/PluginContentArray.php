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

class Newsletter_DBObject_PluginContentArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginContentArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray();
    }

    function pluginAvailable()
    {
        return ModUtil::available('Content');
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

        ModUtil::dbInfoLoad ('content');
        $table = DBUtil::getTables();
        $column  = $table['content_page_column'];
        $where   = "$column[active] = 1";
        $sort    = "$column[id] DESC";
        $nItems  = ModUtil::getVar ('Newsletter', 'plugin_Content_nItems', 1);

        $items = DBUtil::selectObjectArray ('content_page', $where, $sort, 0, $nItems);

        foreach (array_keys($items) as $k) {
            if ($filtAfterDate && $items[$k]['cr_date'] < $filtAfterDate) {
                // filter by date is given, remove older data
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
                $items[$k]['nl_url_title'] = ModUtil::url('Content', 'user', 'view', array('pid' => $items[$k]['id'], 'newlang' => $lang), null, null, true);
                //$items[$k]['nl_content'] = $items[$k]['???'];
                //$items[$k]['nl_url_readmore'] = $items[$k]['nl_url_title'];
            }
        }

        return $items;
    }
}
