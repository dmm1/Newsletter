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

        return true;
    }

    public function upgrade($oldversion)
    {
        switch ($oldversion)
        {
            case '2.1.0':
                // do something
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

        return true;
    }
}
