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

class Newsletter_NewsletterPlugin_NewMembers extends Newsletter_AbstractPlugin
{
    public function pluginAvailable()
    {
        return true;
    }

    public function getPluginTitle()
    {
        return $this->__('Newest members');
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    public function getPluginData($lang=null, $filtAfterDate=null)
    {
        $this->setLang($lang);

        ModUtil::dbInfoLoad('Users');
        $tables   = DBUtil::getTables();
        $column   = $tables['users_column'];
        $where    = "$column[uid] > 1";
        $sort     = "$column[user_regdate] DESC";

        $items = DBUtil::selectObjectArray ('users', $where, $sort, 0, $this->nItems);

        foreach (array_keys($items) as $k) {
            if ($filtAfterDate && $items[$k]['approved_date'] < $filtAfterDate) {
                // filter by date is given, remove older data
                unset($items[$k]);
            } else {
                $items[$k]['nl_title'] = $items[$k]['title'];
            }
        }

        return $items;
    }
}
