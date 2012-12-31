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

class PNMessage extends DBObject 
{
    function PNMessage($init='P', $key=null, $field=null)
    {
        
        $this->_objPath = 'message';
        $this->_init($init, $key, $field);
    }

    function get($key=0, $field='id', $force=false)
    {
        return array();
    }

    function save()
    {
        $data = $this->_objData;
        ModUtil::setVar('Newsletter', 'message', $data['text']);

        $defaultLang = System::getVar('language');
        $alternateLanguages = ZLanguage::getInstalledLanguageNames();
        unset($alternateLanguages[$defaultLang]);
        foreach ($alternateLanguages as $lang => $v) {
            ModUtil::setVar('Newsletter', "message_$lang", $data["text_$lang"]);
        }
    }
}
