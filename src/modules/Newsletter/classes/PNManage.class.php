<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */


class PNManage extends PNObject 
{
    function PNManage ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();
        $this->_init ($init, $key, $field);
    }


    function insertPreProcess ($data=null)
    {
        return $this->updatePreProcess ($data);
    }


    function updatePreProcess ($data=null) 
    {
        if (!$data) {
            $data = $this->_objData;
        }

        $data['uid'] = pnUserGetVar ('uid');

        $this->_objData = $data;
        return $this->_objData;
    }
}

