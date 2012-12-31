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

class PNPluginNewsletterMessageArray extends PNPluginBaseArray
{
    function PNPluginNewsletterMessageArray($init=null, $where='')
    {
        $this->PNPluginBaseArray();
    }

    function getPluginData($lang=null)
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
