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

$dom = ZLanguage::getModuleDomain('Newsletter');

if (!class_exists('Newsletter_DBObject_User')) {
    return LogUtil::registerError (__f('Unable to load class [%s]', 'user', $dom));
}

class Newsletter_DBObject_UserActive extends Newsletter_DBObject_User 
{
    function Newsletter_DBObject_UserActive ($init=null, $key=null, $field=null)
    {
        $this->Newsletter_DBObject_User($init, $key, $field);
    }

    function save()
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $id = FormUtil::getPassedValue ('id', null, 'GET');

        $data = $this->get ($id);
        $data['active'] = $data['active'] ? 0 : 1;

        $this->_objData = $data;
        $this->update ();
        return LogUtil::registerStatus (__("The user's approved status has been changed", $dom));
    }
}
