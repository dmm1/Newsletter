<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage Installer
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */

class Newsletter_Installer extends Zikula_AbstractInstaller
{
    public function install()
    {
        if (!DBUtil::createTable('newsletter_users') || !DBUtil::createTable('newsletter_archives')) {
            return LogUtil::registerError($this->__('Could not create tables!'));
        }

        $this->setVar('admin_key', substr(md5(time()),-10));
        $this->setVar('allow_anon_registration', '0');
        $this->setVar('allow_frequency_change', '0');
        $this->setVar('allow_subscription_change', '0');
        $this->setVar('archive_expire', '0'); // never
        $this->setVar('archive_controlid', '0');
        $this->setVar('auto_approve_registrations', '1');
        $this->setVar('default_frequency', '0');
        $this->setVar('default_type', '1'); //text/html/web
        $this->setVar('enable_multilingual', '0');
        $this->setVar('import_active_status', '1');
        $this->setVar('import_activelastdays', '0');
        $this->setVar('import_approval_status', '1');
        $this->setVar('import_frequency', '0');
        $this->setVar('import_type', '2');
        $this->setVar('itemsperpage', '25');
        $this->setVar('max_send_per_hour', 0);
        $this->setVar('notify_admin', '1');
        $this->setVar('personalize_email', '0');
        $this->setVar('send_day', '5');
        $this->setVar('send_per_request', '5');
        $this->setVar('send_from_address', System::getVar('adminmail'));
        $this->setVar('hookuserreg_display', 'checkboxon');
        $this->setVar('hookuserreg_inform', '1');

        // Register hooks
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        HookUtil::registerProviderBundles($this->version->getHookProviderBundles());

        // Persistent event handler registration
        EventUtil::registerPersistentModuleHandler('Newsletter', 'user.account.update', array('Newsletter_Listener_UsersUpdate', 'updateAccountListener'));
        EventUtil::registerPersistentModuleHandler('Newsletter', 'user.account.delete', array('Newsletter_Listener_UsersUpdate', 'deleteAccountListener'));
        EventUtil::registerPersistentModuleHandler('Newsletter', 'frontcontroller.predispatch', array('Newsletter_Listener_AutoSend', 'pageLoadListener'));


        return true;
    }

    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
            case '2.1.0':
            case '2.2.0':
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                // drop table prefix
                $prefix = $this->serviceManager['prefix'];
                if ($prefix) {
                    $sqlStatements = array();
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . '_newsletter_users TO `newsletter_users`';
                    $sqlStatements[] = 'RENAME TABLE ' . $prefix . '_newsletter_archives TO `newsletter_archives`';
                    foreach ($sqlStatements as $sql) {
                        $stmt = $connection->prepare($sql);
                        try {
                            $stmt->execute();
                        } catch (Exception $e) {
                        }   
                    }
                }
                // update table structure according to table defenition
                if (!DBUtil::changeTable('newsletter_users') || !DBUtil::changeTable('newsletter_archives')) {
                    return "2.2.0";
                }
                // handle new columns and missing data
                $sqlStatements = array();
                $sqlStatements[] = 'UPDATE `newsletter_archives` SET `nla_lang`="'.ZLanguage::getLanguageCode().'" WHERE `nla_lang`=""';
                $sqlStatements[] = 'UPDATE `newsletter_archives` SET `nla_html`=`nla_text` WHERE `nla_html`=""';
                foreach ($sqlStatements as $sql) {
                    $stmt = $connection->prepare($sql);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                    }   
                }
                // strip tags for text archives
                $archives = DBUtil::selectObjectArray('newsletter_archives');
                foreach (array_keys($archives) as $k) {
                    $pos = strpos($archives[$k]['html'], '<body');
                    if ($pos > 0) {
                        $archives[$k]['text'] = substr($archives[$k]['html'], $pos);
                        //$archives[$k]['text'] = str_replace('<br />', "\n", $archives[$k]['text']);
                        $archives[$k]['text'] = strip_tags($archives[$k]['text']);
                    }
                }
                DBUtil::updateObjectArray($archives, 'newsletter_archives', 'id');

            case '2.2.1':
                // Register hooks
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sqlQueries = array();
                $sqlQueries[] = 'DELETE FROM `hook_area` WHERE `owner`="Newsletter"';
                $sqlQueries[] = 'DELETE FROM `hook_subscriber` WHERE `owner`="Newsletter"';
                $sqlQueries[] = 'DELETE FROM `hook_provider` WHERE `owner`="Newsletter"';
                $sqlQueries[] = 'DELETE FROM `hook_runtime` WHERE `sowner`="Newsletter" OR `powner`="Newsletter"';
                foreach ($sqlQueries as $sql) {
                    $stmt = $connection->prepare($sql);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                    }   
                }
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
                HookUtil::registerProviderBundles($this->version->getHookProviderBundles());

                // Persistent event handler registration
                EventUtil::registerPersistentModuleHandler('Newsletter', 'user.account.update', array('Newsletter_Listener_UsersUpdate', 'updateAccountListener'));
                EventUtil::registerPersistentModuleHandler('Newsletter', 'user.account.delete', array('Newsletter_Listener_UsersUpdate', 'deleteAccountListener'));
                
                $this->delVar('Newsletter');
            case '2.2.2':
                //Delete all Newsletter_Maintenance blocks
                $connection = Doctrine_Manager::getInstance()->getConnection('default');
                $sqlQuery = 'DELETE FROM `blocks` WHERE `bkey`="Maintenance" AND `mid`="' . ModUtil::getIdFromName('Newsletter') . '"';
                $stmt = $connection->prepare($sqlQuery);
                try {
                    $stmt->execute();
                } catch (Exception $e) {
                }

                //Register eventlistener for auto-sending emails.
                EventUtil::registerPersistentModuleHandler('Newsletter', 'frontcontroller.predispatch', array('Newsletter_Listener_AutoSend', 'pageLoadListener'));
            case '2.2.3':
                // future upgrade routines
                break;
        }

        return true;
    }

    public function uninstall()
    {
        if (!DBUtil::dropTable('newsletter_users')  || !DBUtil::dropTable('newsletter_archives')) {
            return LogUtil::registerError($this->__('Could not drop tables!'));
        }

        $this->delVars('Newsletter');

        // Remove hooks
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());
        HookUtil::unregisterProviderBundles($this->version->getHookProviderBundles());

        // Persistent event handler unregistration
        EventUtil::unregisterPersistentModuleHandler('Newsletter', 'user.account.update', array('Newsletter_Listener_UsersUpdate', 'updateAccountListener'));
        EventUtil::unregisterPersistentModuleHandler('Newsletter', 'user.account.delete', array('Newsletter_Listener_UsersUpdate', 'deleteAccountListener'));
        EventUtil::unregisterPersistentModuleHandler('Newsletter', 'frontcontroller.predispatch', array('Newsletter_Listener_AutoSend', 'pageLoadListener'));
        
        return true;
    }
}
