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

class Newsletter_Block_Maintenance extends Zikula_Controller_AbstractBlock
{
    public function init()
    {
        SecurityUtil::registerPermissionSchema('Maintenanceblock::', 'Block ID::');
    }

    public function info()
    {
        return array(
            'module'         => 'Newsletter',
            'text_type'      => $this->__('Maintenance'),
            'text_type_long' => $this->__('Newsletter maintenance block'),
            'allow_multiple' => true,
            'form_content'   => false,
            'form_refresh'   => false,
            'show_preview'   => true
        );
    }

    public function display($blockinfo)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Maintenanceblock::', "$blockinfo[bid]::", ACCESS_READ));

        if (!ModUtil::available('Newsletter')) {
            return;
        }

        ModUtil::dbInfoLoad('Newsletter');

        $disable_auto = ModUtil::getVar ('Newsletter', 'disable_auto', 0);
        if ($disable_auto) {
            return;

        } else {
            $today = date('w');
            $send_day = ModUtil::getVar('Newsletter','send_day');
            if ($send_day == $today) {
                if (!Loader::loadClassFromModule('Newsletter', 'newsletter_send')) {
                    return 'Unable to load class [newsletter_send]';
                }

                $enable_multilingual = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
                if ($enable_multilingual) {
                    $_POST['language'] = SessionUtil::getVar('lang'); // hack, to ensure that language can be retrieved from $_POST
                }
                $_POST['authKey'] = ModUtil::getVar('Newsletter', 'admin_key');

                $object = new PNNewsletterSend();
                $object->save();

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

        return;
    }
}
