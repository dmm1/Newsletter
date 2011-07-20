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

class PNUserDelete extends PNUser 
{
    function PNUserDelete($init=null, $key=null, $field=null)
    {
        $this->PNUser($init, $key, $field);
    }

    function save()
    {
        return $this->delete();
    }
}
