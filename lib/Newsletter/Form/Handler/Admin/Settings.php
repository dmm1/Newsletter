<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage FormHandler
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

/**
 * @brief Settings FormHandler
 */
class Newsletter_Form_Handler_Admin_Settings extends Zikula_Form_AbstractHandler
{
    /**
     * @brief Setup form.
     *
     * @param Zikula_Form_View $view Current Zikula_Form_View instance.
     */
    public function initialize(Zikula_Form_View $view)
    {
        $this->view->caching = false;
        $this->view
             ->assign('preferences',          $this->getVars())
             ->assign('limitTypeSelector',                   Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorDataNewsletterType(true)))
             ->assign('defaultTypeSelector',                 Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorDataNewsletterType(false)))
             ->assign('defaultFrequencySelector',            Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorDataNewsletterFrequency(false)))
             ->assign('sendDaySelector',                     Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorDataSendDay(false)))
             ->assign('archiveExpireSelector',               Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorDataArchiveExpire()))
             ->assign('hookUserRegistrationDisplaySelector', Newsletter_Util::convertSelectorArrayForFormHandler(
                                                    Newsletter_Util::getSelectorHookUserRegistration()));
    }

    /**
     * @brief Handle form submission.
     * @param Zikula_Form_View $view  Current Zikula_Form_View instance.
     * @param array &$args Arguments.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $url = ModUtil::url('Newsletter', 'admin', 'settings');

        if ($args['commandName'] == 'cancel') {
            return LogUtil::RegisterStatus($this->__('Editing settings canceled'), $url);
        }

        // check for valid form
        if (!$view->isValid())
            return false;

        $prefs = $view->getValues();

        if ($prefs['limit_type'] && $prefs['default_type'] != $prefs['limit_type']) {
            $prefs['default_type'] = $prefs['limit_type'];
            return LogUtil::registerError($this->__('You have selected to limit the type of newsletter subscriptions but have chosen a different default newsletter type. Your default newsletter type has been set to the value you have selected to limit subscriptions to. Please review your settings!'));
        }

        $this->setVar('admin_key',                  $prefs['admin_key'] != ''            ? $prefs['admin_key'] : substr(md5(time()),-10));
        $this->setVar('allow_anon_registration',    $prefs['allow_anon_registration']    ? 1 : 0);
        $this->setVar('allow_frequency_change',     $prefs['allow_frequency_change']     ? 1 : 0);
        $this->setVar('allow_subscription_change',  $prefs['allow_subscription_change']  ? 1 : 0);
        $this->setVar('archive_expire',             $prefs['archive_expire']             ? $prefs['archive_expire'] : 0);
        $this->setVar('archive_controlid',          $prefs['archive_controlid']          ? $prefs['archive_controlid'] : 0);
        $this->setVar('auto_approve_registrations', $prefs['auto_approve_registrations'] ? 1 : 0);
        $this->setVar('default_frequency',          $prefs['default_frequency']          ? $prefs['default_frequency'] : 0);
        $this->setVar('default_type',               $prefs['default_type']               ? $prefs['default_type'] : 1);
        $this->setVar('enable_multilingual',        $prefs['enable_multilingual']        ? 1 : 0);
        $this->setVar('itemsperpage',               $prefs['itemsperpage']               ? $prefs['itemsperpage'] : 25);
        $this->setVar('limit_type',                 $prefs['limit_type']);
        $this->setVar('max_send_per_hour',          $prefs['max_send_per_hour'] >= 0     ? $prefs['max_send_per_hour'] : 0);
        $this->setVar('notify_admin',               $prefs['notify_admin']               ? 1 : 0);
        $this->setVar('require_tos',                $prefs['require_tos']                ? 1 : 0);
        $this->setVar('show_approval_status',       $prefs['show_approval_status']       ? 1 : 0);
        $this->setVar('disable_auto',               $prefs['disable_auto']               ? 1 : 0);
        $this->setVar('activate_archive',           $prefs['activate_archive']           ? 1 : 0);
        $this->setVar('personalize_email',          $prefs['personalize_email']          ? 1 : 0);
        $this->setVar('send_day',                   $prefs['send_day']                   ? $prefs['send_day']           : 5);
        $this->setVar('send_from_address',          $prefs['send_from_address']);
        $this->setVar('newsletter_subject',         $prefs['newsletter_subject']         ? $prefs['newsletter_subject'] : 0);
        $this->setVar('send_per_request',           $prefs['send_per_request'] >= 0      ? $prefs['send_per_request']   : 5);
        $this->setVar('hookuserreg_display',        $prefs['hookuserreg_display']        ? $prefs['hookuserreg_display'] : 'checkboxon');
        $this->setVar('hookuserreg_inform',         $prefs['hookuserreg_inform']);

        //Archive
        $this->setVar('show_archive',   $prefs['show_archive']   ? 1 : 0);
        $this->setVar('show_id',        $prefs['show_id']        ? 1 : 0);
        $this->setVar('show_lang',      $prefs['show_lang']      ? 1 : 0);
        $this->setVar('show_objects',   $prefs['show_objects']   ? 1 : 0);
        $this->setVar('show_plugins',   $prefs['show_plugins']   ? 1 : 0);
        $this->setVar('show_size',      $prefs['show_size']      ? 1 : 0);
        $this->setVar('show_date',      $prefs['show_date']      ? 1 : 0);

        return LogUtil::registerStatus($this->__('Settings saved!'), $url);
    }
}
