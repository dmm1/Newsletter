<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage Listener
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

/**
 * Provides a listener (handler) which is loaded on every pageload.
 */
class Newsletter_Listener_AutoSend
{
    /**
     * Sends out the automatic Newsletter.
     *
     * @param Zikula_Event $event The event that triggered this handler.
     *
     * @return void
     */
    public static function pageLoadListener(Zikula_Event $event)
    {
        // Load module, otherwise translation is not working
        ModUtil::load('Newsletter');
        ModUtil::dbInfoLoad('Newsletter');
        $dom = ZLanguage::getModuleDomain('Newsletter');

        $disable_auto = ModUtil::getVar('Newsletter', 'disable_auto', 0);
        if ($disable_auto) {
            return;

        } else {
            $today = date('w');
            $send_day = ModUtil::getVar('Newsletter','send_day');
            if ($send_day == $today && !ModUtil::getVar('Newsletter', 'sendInProgress', false)) {
                ModUtil::setVar('Newsletter', 'sendInProgress', true);
                $class = 'Newsletter_DBObject_NewsletterSend';
                if (!class_exists($class)) {
                    return LogUtil::registerError('Newsletter auto-send:' . __f('Unable to load class [%s]', 'newsletter_send', $dom));
                }

                $enable_multilingual = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
                if ($enable_multilingual) {
                    $_POST['language'] = SessionUtil::getVar('lang'); // hack, to ensure that language can be retrieved from $_POST
                }
                $_POST['authKey'] = ModUtil::getVar('Newsletter', 'admin_key');

                $object = new Newsletter_DBObject_NewsletterSend();
                $object->save();
                ModUtil::setVar('Newsletter', 'sendInProgress', false);
                // prune on send day before noon
                //if (date('G') <= 12) {
                    //if (!Loader::loadClassFromModule('Newsletter', 'archive')) {
                        //return 'Unable to load class [archive]';
                    //}
                    //$object = new PNArchive();
                    //$object->prune();
                //}
            }
        }
    }
}
