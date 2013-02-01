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
    return LogUtil::registerError(__f('Unable to load class [%s]', 'user'));
}

class Newsletter_DBObject_UserDelete extends Newsletter_DBObject_User 
{
    function Newsletter_DBObject_UserDelete($init=null, $key=null, $field=null)
    {
        $this->Newsletter_DBObject_User($init, $key, $field);
    }

    function save()
    {
        return $this->delete();
    }
}
