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

class Newsletter_HookHandlers extends Zikula_Hook_AbstractHandler
{
     /**
     * Display hook for edit views.
     *
     * @param Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiEdit(Zikula_DisplayHook $hook)
    {
        // Input from the hook
        $callermodname = $hook->getCaller();
        $callerobjectid = $hook->getId();

        // Load module, otherwise translation is not working in template
        ModUtil::load('Newsletter');

        // Create output object
        $view = Zikula_View::getInstance('Newsletter', false, null, true);
        //$view->assign('xyz', $xyz);
        $template = 'user/hook_subscribe.tpl';

        $response = new Zikula_Response_DisplayHook('provider.newsletter.ui_hooks.subscribe', $view, $template);
        $hook->setResponse($response);
    }

    /**
     * Process handler for process edit hook type.
     *
     * @param Zikula_ValidationHook $hook
     *
     * @return void
     */
    public function processEdit(Zikula_ProcessHook $hook)
    {
        $dom = ZLanguage::getModuleDomain('Newsletter');

        // Input from the hook
        $callermodname = $hook->getCaller();
        $callerobjectid = $hook->getId();
        // note: default here can not be true, as if checkbox in registration form is unchecked, then post variable is not set
        $newsletter_subscribe = FormUtil::getPassedValue('newsletter_subscribe', false, 'POST');

        // Module variables to use here
        $hookuserreg_display = ModUtil::getVar('Newsletter', 'hookuserreg_display', 'checkboxon');
        $hookuserreg_inform = ModUtil::getVar('Newsletter', 'hookuserreg_inform', '1');

        if ($hookuserreg_display == 'infmessage' || $hookuserreg_display == 'nomessage') {
            // Automated subscription is set in configuration
            $newsletter_subscribe = true;
        }

        if ($newsletter_subscribe) {
            $userdata = DBUtil::selectObjectByID('users', $callerobjectid, 'uid');

            $newUser = array();
            $newUser['uid'] = $callerobjectid;
            if (isset($userdata['realname']) && $userdata['realname']) {
                $newUser['name'] = $userdata['realname'];
            } else {
                $newUser['name'] = $userdata['uname'];
            }
            $newUser['email'] = $userdata['email'];
            $newUser['active'] = 1;
            $newUser['approved'] = 1;

            $newUser['frequency'] = ModUtil::getVar('Newsletter', 'default_frequency', 0);;

            $enableML = ModUtil::getVar('Newsletter', 'enable_multilingual', 0);
            $newUser['lang'] = ZLanguage::getLanguageCode();
            if (!$enableML) {
                $newUser['lang'] = System::getVar('language_i18n', 'en');
            }

            $newUser['type'] = ModUtil::getVar('Newsletter', 'default_type', 0);
            $limitType = ModUtil::getVar('Newsletter', 'limit_type', 0);
            if ($limitType) {
                $newUser['type'] = $limitType;
            }

            // load module, otherwise next insert statement is not working
            ModUtil::load('Newsletter');
            if (DBUtil::insertObject($newUser, 'newsletter_users')) {
                if ($hookuserreg_inform) {
                    LogUtil::registerStatus(__('You have been subscribed to our newsletter. You can manage your subscription from your profile.', $dom));
                }
            }

            return true;
        }
    }
}
