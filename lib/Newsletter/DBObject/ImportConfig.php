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

class Newsletter_DBObject_ImportConfig extends DBObject 
{
    public function __construct($init=null, $key=null, $field=null)
    {
        $this->_objPath = 'import';
        $this->_init ($init, $key, $field);
    }

    public function save()
    {
        foreach ($this->_objData as $k=>$v) {
            ModUtil::setVar('Newsletter', $k, $v);
        }

        return true;
    }
}
