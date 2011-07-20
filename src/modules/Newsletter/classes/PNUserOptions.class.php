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

if (!Loader::loadClassFromModule('Newsletter', 'user')) {
    return LogUtil::registerError(__('Unable to load class [user] ... ', $dom));
}

class PNUserOptions extends PNUser 
{
    function PNUserOptions ($init=null, $key=null, $field=null)
    {
        $this->PNUser ($init, $key, $field);
        $this->_objField = 'uid'; 
    }

    function save()
    {
        parent::save();
        LogUtil::registerStatus(__('Your subscription newsletter options have been updated', $dom));
        return true;
    }
}
