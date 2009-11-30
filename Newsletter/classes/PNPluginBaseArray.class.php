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


class PNPluginBaseArray extends PNObjectArray 
{
    function PNPluginBaseArray ($init=null, $where='')
    {
        $this->PNObjectArray ();
    }


    // to be implenented by derived classes
    function getPluginData ($lang=null)
    {
        exit ('Base class implementation of getPluginData() should not be called ...');
    }

    //EM Start
    // to be derived by derived classes when necessary
    function setPluginParameters ()
    {
    }

    // to be derived by derived classes when necessary
    function getPluginParameters ()
    {
        return array ('number' => 0,
                      'param' => array());
    }
    //EM end
}

