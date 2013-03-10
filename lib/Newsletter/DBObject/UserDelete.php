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
    return LogUtil::registerError(__f('Unable to load class [%s]', 'user', $dom));
}

class Newsletter_DBObject_UserDelete extends Newsletter_DBObject_User 
{
    public function __construct($init=null, $key=null, $field=null)
    {
        parent::__construct($init, $key, $field);
    }

    public function save()
    {
        return $this->delete();
    }
}
