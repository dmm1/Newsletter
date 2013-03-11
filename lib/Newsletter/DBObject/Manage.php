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

class Newsletter_DBObject_Manage extends DBObject 
{
    public function __construct($init=null, $key=null, $field=null)
    {
        $this->_init ($init, $key, $field);
    }

    public function insertPreProcess($data=null)
    {
        return $this->updatePreProcess($data);
    }

    public function updatePreProcess($data=null) 
    {
        if (!$data) {
            $data = $this->_objData;
        }

        $data['uid'] = UserUtil::getVar('uid');

        $this->_objData = $data;
        return $this->_objData;
    }
}
