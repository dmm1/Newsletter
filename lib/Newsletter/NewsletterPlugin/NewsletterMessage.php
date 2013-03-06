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

class Newsletter_NewsletterPlugin_NewsletterMessage extends Newsletter_AbstractPlugin
{
    public function getPluginData($lang=null)
    {
        if(!isset($lang))
            $lang = $this->lang;

        $vName = 'message';

        if ($lang != $this->lang) {
            $vName = 'message_' . $lang;
        }

        return ModUtil::getVar ('Newsletter', $vName, '');
    }
}
