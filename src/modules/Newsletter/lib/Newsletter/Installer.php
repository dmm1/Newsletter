<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class Newsletter_Installer extends Zikula_AbstractInstaller
{
	public function install()
	{
    if (!DBUtil::createTable('newsletter_users') || !DBUtil::createTable('newsletter_archives')) {
        return false;
    }

    ModUtil::setVar('Newsletter', 'admin_key', substr(md5(time()),-10));
    ModUtil::setVar('Newsletter', 'allow_anon_registration', '0');
    ModUtil::setVar('Newsletter', 'allow_frequency_change', '0');
    ModUtil::setVar('Newsletter', 'allow_subscription_change', '0');
    ModUtil::setVar('Newsletter', 'archive_expire', '0'); // never
    ModUtil::setVar('Newsletter', 'auto_approve_registrations', '1');
    ModUtil::setVar('Newsletter', 'default_frequency', '0');
    ModUtil::setVar('Newsletter', 'default_type', '1'); //text/html/web
    ModUtil::setVar('Newsletter', 'enable_multilingual', '0'); 
    ModUtil::setVar('Newsletter', 'import_active_status', '1');
    ModUtil::setVar('Newsletter', 'import_approval_status', '1');
    ModUtil::setVar('Newsletter', 'import_frequency', '0');
    ModUtil::setVar('Newsletter', 'import_type', '2');
    ModUtil::setVar('Newsletter', 'itemsperpage', '25');
    ModUtil::setVar('Newsletter', 'max_send_per_hour', 0);
    ModUtil::setVar('Newsletter', 'notify_admin', '1');
    ModUtil::setVar('Newsletter', 'personalize_email', '0');
    ModUtil::setVar('Newsletter', 'send_day', '5'); 
    ModUtil::setVar('Newsletter', 'send_per_request', '5');  
    ModUtil::setVar('Newsletter', 'send_from_address', System::getVar('adminmail'));

    return true;
	}
	
	public function upgrade($oldversion) 
	{
    switch($oldversion)
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

    ModUtil::delVar('Newsletter');

    return true;   
	}

}