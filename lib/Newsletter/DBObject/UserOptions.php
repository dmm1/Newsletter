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
    return LogUtil::registerError(__f('Unable to load class [%s]', 'user', $dom));
}

class Newsletter_DBObject_UserOptions extends Newsletter_DBObject_User 
{
    function Newsletter_DBObject_UserOptions ($init=null, $key=null, $field=null)
    {
        $this->Newsletter_DBObject_User($init, $key, $field);
        $this->_objField = 'uid'; 
    }

    function save()
    {
        parent::save();
        LogUtil::registerStatus(__('Your subscription newsletter options have been updated', $dom));
        return true;
    }
}
