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


class PNMessage extends PNObject 
{
    function PNMessage ($init=null, $key=null, $field=null)
    {
        $this->PNObject ();
        $this->_objPath = 'message';
    }


    function get ($key=0, $field='id', $force=false)
    {
        return array();
    }


    function save ()
    {
        $data = $this->_objData;
        pnModSetVar ('Newsletter', 'message', $data['text']);

        $defaultLang  = pnConfigGetVar ('language');
        $alternateLanguages = pnInstalledLanguages ();
        unset ($alternateLanguages[$defaultLang]);
        foreach ($alternateLanguages as $k=>$v) {
            $fName = 'text_' . $k;
            $vName = 'message_' . $k;
            pnModSetVar ('Newsletter', $vName, $data[$fName]);
        }
    }
}

