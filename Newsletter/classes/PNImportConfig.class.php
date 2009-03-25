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


class PNImportConfig extends PNObject 
{
    function PNImportConfig ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();
        $this->_objPath = 'import';
        $this->_init ($init, $key, $field);
    }


    function save ()
    {
        foreach ($this->_objData as $k=>$v) {
            pnModSetVar ('Newsletter', $k, $v);
        }

        return true;
    }
}

