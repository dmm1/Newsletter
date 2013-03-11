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

class Newsletter_DBObject_StatisticsArray extends DBObjectArray 
{
    public function getWhere($where='', $sort='', $limitOffset=-1, $limitNumRows=-1, $assocKey=null, $force=false, $distinct=false)
    {
        $data = array();
        $data['users']                     = DBUtil::selectObjectCount ('newsletter_users');
        $data['users_active']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_approved=1');
        $data['users_approved']            = DBUtil::selectObjectCount ('newsletter_users', 'nlu_active=1');
        $data['users_approved_and_active'] = DBUtil::selectObjectCount ('newsletter_users', 'nlu_approved=1 AND nlu_active=1');
        $data['users_registered']          = DBUtil::selectObjectCount ('newsletter_users', 'nlu_uid > 1');
        $data['users_text']                = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 1');
        $data['users_html']                = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 2');
        $data['users_textwithlinks']       = DBUtil::selectObjectCount ('newsletter_users', 'nlu_type = 3');
        $data['users_weekly']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 1');
        $data['users_monthly']             = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 2');
        $data['users_yearly']              = DBUtil::selectObjectCount ('newsletter_users', 'nlu_frequency = 3');
        $data['archives']                  = DBUtil::selectObjectCount ('newsletter_archives');

        $this->_objData = $data;
        return $this->_objData;
    }

    public function getCount($where='', $doJoin=false)
    {
        return count($this->_objData);
    }
}
