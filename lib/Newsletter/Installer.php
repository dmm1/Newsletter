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
            return false;
        }

        $this->setVar('Newsletter', 'admin_key', substr(md5(time()),-10));
        $this->setVar('Newsletter', 'allow_anon_registration', '0');
        $this->setVar('Newsletter', 'allow_frequency_change', '0');
        $this->setVar('Newsletter', 'allow_subscription_change', '0');
        $this->setVar('Newsletter', 'archive_expire', '0'); // never
        $this->setVar('Newsletter', 'auto_approve_registrations', '1');
        $this->setVar('Newsletter', 'default_frequency', '0');
        $this->setVar('Newsletter', 'default_type', '1'); //text/html/web
        $this->setVar('Newsletter', 'enable_multilingual', '0');
        $this->setVar('Newsletter', 'import_active_status', '1');
        $this->setVar('Newsletter', 'import_activelastdays', '0');
        $this->setVar('Newsletter', 'import_approval_status', '1');
        $this->setVar('Newsletter', 'import_frequency', '0');
        $this->setVar('Newsletter', 'import_type', '2');
        $this->setVar('Newsletter', 'itemsperpage', '25');
        $this->setVar('Newsletter', 'max_send_per_hour', 0);
        $this->setVar('Newsletter', 'notify_admin', '1');
        $this->setVar('Newsletter', 'personalize_email', '0');
        $this->setVar('Newsletter', 'send_day', '5');
        $this->setVar('Newsletter', 'send_per_request', '5');
        $this->setVar('Newsletter', 'send_from_address', System::getVar('adminmail'));

        // Register for hooks subscribing
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

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

                // Register for hook subscribing
                HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());

            case '2.2.1':
                // future upgrade routines
                break;
        }

        return true;
    }

    public function uninstall()
    {
        if (!DBUtil::dropTable('newsletter_users')  || !DBUtil::dropTable('newsletter_archives')) {
            return false;
        }

        $this->delVars('Newsletter');

        // unregister handlers
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

        return true;
    }
}
