<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
*/


function Newsletter_init()
{
    if (!DBUtil::createTable('newsletter_users')  ||
        !DBUtil::createTable('newsletter_archives')) {
        return false;
    }

    pnModSetVar('Newsletter', 'admin_key', substr(md5(time()),-10));
    pnModSetVar('Newsletter', 'allow_anon_registration', '0');
    pnModSetVar('Newsletter', 'allow_frequency_change', '0');
    pnModSetVar('Newsletter', 'archive_expire', '0'); // never
    pnModSetVar('Newsletter', 'auto_approve_registrations', '1');
    pnModSetVar('Newsletter', 'default_frequency', '0');
    pnModSetVar('Newsletter', 'default_type', '1'); //text/html/web
    pnModSetVar('Newsletter', 'enable_multilingual', '0'); 
    pnModSetVar('Newsletter', 'import_active_status', '1');
    pnModSetVar('Newsletter', 'import_approval_status', '1');
    pnModSetVar('Newsletter', 'import_frequency', '0');
    pnModSetVar('Newsletter', 'import_type', '2');
    pnModSetVar('Newsletter', 'itemsperpage', '25');
    pnModSetVar('Newsletter', 'max_send_per_hour', 0);
    pnModSetVar('Newsletter', 'notify_admin', '1');
    pnModSetVar('Newsletter', 'personalize_email', '0');
    pnModSetVar('Newsletter', 'send_day', '5'); 
    pnModSetVar('Newsletter', 'send_per_request', '5');  
    pnModSetVar('Newsletter', 'send_from_address', pnConfigGetVar('adminmail'));

    return true;
}


function Newsletter_upgrade($oldversion) 
{
    switch($oldversion) {
        case '1.0': break;
        case '1.1':
            pnModSetVar('Newsletter', 'personalize_email', 0);
            pnModSetVar('Newsletter', 'admin_key', substr(md5(time()),-10));
            _Newsletter_upgrade_to_20 ();
            break;
        case '1.2':
            pnModSetVar('Newsletter', 'max_send_per_hour', 0);
            pnModSetVar('Newsletter', 'personalize_email', 0);
            pnModSetVar('Newsletter', 'admin_key',substr(md5(time()),-10));
            _Newsletter_upgrade_to_20 ();
            break;
        case '1.5':
            pnModSetVar ('Newsletter', 'max_send_per_hour', 0);
            pnModSetVar ('Newsletter', 'personalize_email', 0);
            pnModSetVar ('Newsletter', 'admin_key',substr(md5(time()),-10));
            _Newsletter_upgrade_to_20 ();
            break;
        case '1.6':
        case '1.61':
        case '1.6.1':
            pnModSetVar ('Newsletter', 'max_send_per_hour', 0);
            pnModSetVar ('Newsletter', 'personalize_email', 0);
            pnModSetVar ('Newsletter', 'admin_key',substr(md5(time()),-10));
            _Newsletter_upgrade_to_20 ();
    }

    return true;
}


function _Newsletter_upgrade_to_20 ()
{
    pnModSetVar ('Newsletter', 'itemsperpage', '25');
    pnModSetVar ('Newsletter', 'enable_multilingual', 0); 

    $pnTables = pnDBGetTables();
    $table    = $pnTables['newsletter_users'];

    $sqls = array();
    $sqls[] = "ALTER TABLE $table CHANGE pn_id nlu_id INT(11) NOT NULL AUTO_INCREMENT";
    $sqls[] = "ALTER TABLE $table CHANGE pn_user_id nlu_uid INT(11) NOT NULL DEFAULT 0";
    $sqls[] = "ALTER TABLE $table CHANGE pn_user_name nlu_name VARCHAR(64) NOT NULL DEFAULT ''";
    $sqls[] = "ALTER TABLE $table CHANGE pn_user_email nlu_email VARCHAR(128) NOT NULL DEFAULT ''";
    $sqls[] = "ALTER TABLE $table CHANGE pn_nl_type nlu_type INT(1) NOT NULL DEFAULT 1";
    $sqls[] = "ALTER TABLE $table CHANGE pn_nl_frequency nlu_frequency INT(2) NOT NULL DEFAULT 0";
    $sqls[] = "ALTER TABLE $table CHANGE pn_active nlu_active INT(1) NOT NULL DEFAULT 1";
    $sqls[] = "ALTER TABLE $table CHANGE pn_approved nlu_approved INT(1) NOT NULL DEFAULT 1";
    $sqls[] = "ALTER TABLE $table CHANGE pn_last_send_date nlu_last_send_date DATETIME NULL DEFAULT NULL";
    $sqls[] = "ALTER TABLE $table DROP pn_join_date";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_category_id INT(11) NOT NULL DEFAULT 0 AFTER nlu_id";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_lang CHAR(3) NOT NULL DEFAULT '' AFTER nlu_email ";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_obj_status CHAR(1) NOT NULL DEFAULT 'A' AFTER nlu_last_send_date";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_cr_date DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00' AFTER nlu_obj_status";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_cr_uid INT(11) NOT NULL DEFAULT 0 AFTER nlu_cr_date";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_lu_date DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00' AFTER nlu_cr_uid";
    $sqls[] = "ALTER TABLE $table ADD COLUMN nlu_lu_uid INT(11) NOT NULL DEFAULT 0 AFTER nlu_lu_date";
    $sqls[] = "DROP INDEX pn_user_id ON $table";
    $sqls[] = "DROP INDEX pn_user_email ON $table";
    $sqls[] = "DROP INDEX pn_approved ON $table";
    $sqls[] = "DROP INDEX pn_last_send_date ON $table";
    $sqls[] = "CREATE INDEX newletter_users_uid ON $table (nlu_uid)";
    $sqls[] = "CREATE INDEX newletter_users_email ON $table (nlu_email)";
    $sqls[] = "CREATE INDEX newletter_users_active ON $table (nlu_active)";
    $sqls[] = "CREATE INDEX newletter_users_approved ON $table (nlu_approved)";
    $sqls[] = "CREATE INDEX newletter_users_last_send_date ON $table (nlu_last_send_date)";
    $sqls[] = "UDPATE $table SET nlu_frequency = 0 WHERE nlu_frequency = 1";
    $sqls[] = "UDPATE $table SET nlu_frequency = 1 WHERE nlu_frequency = 2";
    $sqls[] = "UDPATE $table SET nlu_frequency = 12 WHERE nlu_frequency = 3";

    foreach ($sqls as $sql) {
        DBUtil::executeSQL ($sql, -1, -1, false, true);
    }

    $users = DBUtil::selectObjectArray ('newsletter_users', '', 'id');
    foreach ($users as $user) {
        $tUser = array();
        $tUser['id']             = $user['id'];
        $tUser['last_send_date'] = DateUtil::getDatetime($oldUser['last_send_date']);
        $tUser['cr_date']        = DateUtil::getDatetime();
        $tUser['lang']           = pnConfigGetVar('language');
        DBUtil::updateObject ($tUser, 'newsletter_users');
    }

    // have to do this because apparently we can't drop the primary key
    // so we create a new table, copy the data, drop the original table and then rename the new table
    // ugly but it works
    DBUtil::createTable('newsletter_archivestmp');
    $archives = DBUtil::selectObjectArray ('newsletter_archivesupg', '', 'archive_date');
    foreach ($archives as $archive) {
        $archive['date'] = DateUtil::getDatetime($archive['archive_date']);
        DBUtil::insertObject ($archive, 'newsletter_archivestmp');
    }
    $sqls = array();
    DBUtil::dropTable ('newsletter_archives');
    DBUtil::renameTable ('newsletter_archivestmp', 'newsletter_archives');

    return true;
}


function Newsletter_delete()
{
    if (!DBUtil::dropTable ('newsletter_users')  ||
        !DBUtil::dropTable ('newsletter_archives')) {
        return false;
    }

    pnModDelVar ('Newsletter');

    return true;   
}

