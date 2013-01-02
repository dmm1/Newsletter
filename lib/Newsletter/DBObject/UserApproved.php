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

if (!class_exists('Newsletter_DBObject_User')) {
    return LogUtil::registerError(__('Unable to load class [user] ... ', $dom));
}

class Newsletter_DBObject_UserApproved extends Newsletter_DBObject_User 
{
    function Newsletter_DBObject_UserApproved($init=null, $key=null, $field=null)
    {
        $this->Newsletter_DBObject_User($init, $key, $field);
    }

    function save()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $id = FormUtil::getPassedValue('id', null, 'GET');

        $data = $this->get($id);
        $data['approved'] = $data['approved'] ? 0 : 1;

        $this->_objData = $data;
        $this->update();
        return LogUtil::registerStatus(__("The user's active status has been changed", $dom));
    }
}
