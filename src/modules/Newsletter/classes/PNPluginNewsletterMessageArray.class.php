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


class PNPluginNewsletterMessageArray extends PNPluginBaseArray
{
    function PNPluginNewsletterMessageArray ($init=null, $where='')
    {
        $this->PNPluginBaseArray ();
    }


    function getPluginData ($lang=null)
    {
        $defaultLang = System::getVar ('language');
        $lang = FormUtil::getPassedValue ('language', $defaultLang, 'POST');
        $vName = 'message';
        if ($lang != $defaultLang) {
            $vName = 'message_' . $lang;
        }

        return ModUtil::getVar ('Newsletter', $vName, '');
    }
}

