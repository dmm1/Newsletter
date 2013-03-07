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
    public function getPluginData()
    {
        $vName = 'message';

        if (System::getVar('language_i18n', 'en') != $this->lang) {
            $vName = 'message_' . $this->lang;
        }

        return ModUtil::getVar ('Newsletter', $vName, '');
    }
}
