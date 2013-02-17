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

class Newsletter_DBObject_PluginDizkusArray extends Newsletter_DBObject_PluginBaseArray
{
    function Newsletter_DBObject_PluginDizkusArray($init=null, $where='')
    {
        $this->Newsletter_DBObject_PluginBaseArray ();
    }

    // $filtAfterDate is null if is not set, or in format yyyy-mm-dd hh:mm:ss
    function getPluginData($lang=null, $filtAfterDate=null)
    {
        if (!ModUtil::available('Dizkus')) {
            return array();
        }

        ModUtil::dbInfoLoad ('Dizkus');
        $nItems = ModUtil::getVar ('Newsletter', 'plugin_Dizkus_nItems', 1);

        // get all forums the user is allowed to read
        /*$userforums = ModUtil::apiFunc('Dizkus', 'user', 'readuserforums'); 2013-02-17 this function is not working for now, so will replace with the code below */
        $permFilter = array('component_left' => 'Dizkus',
            'component_middle' => '',
            'component_right' => '',
            'instance_left' => 'cat_id',
            'instance_middle' => 'forum_id',
            'instance_right' => '',
            'level' => ACCESS_READ);
        $userforums = DBUtil::selectExpandedObjectArray('dizkus_forums', '', '', 'forum_id', -1, -1, 'forum_id', $permFilter);
        if (!is_array($userforums) || count($userforums)==0) {
            // error or user is not allowed to read any forum at all
            // return empty result set without even doing a db access
            return array();
        }

        // now create a very simple array of forum_ids only. we do not need
        // all the other stuff in the $userforums array entries
        $allowedforums = array();
        foreach (array_keys($userforums) as $k) {
            $allowedforums[] = $userforums[$k]['forum_id'];
        }
        $whereforum = ' forum_id IN (' . DataUtil::formatForStore(implode(',', $allowedforums)) . ') ';

        $items = DBUtil::selectObjectArray('dizkus_topics', $whereforum, 'topic_id DESC', 0, $nItems);

        // filter by date is given, remove older data
        if ($filtAfterDate) {
            foreach (array_keys($items) as $k) {
                if ($items[$k]['topic_time'] < $filtAfterDate) {
                    unset($items[$k]);
                }
            }
        }

        return $items;
    }
}
