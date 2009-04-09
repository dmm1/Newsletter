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


function Newsletter_maintenanceblock_init()
{
    pnSecAddSchema('Newsletter:maintenanceblock:', 'Block title::');
}

function Newsletter_maintenanceblock_info()
{
    return array('text_type' => 'maintenance',
                 'module' => 'Newsletter',
                 'text_type_long' => 'Newsletter Maintenance Block',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

function Newsletter_maintenanceblock_display($blockinfo)
{
    if (!pnSecAuthAction(0, 'Newsletter:maintenanceblock:', "$blockinfo[title]::", ACCESS_ADMIN)) {
        return;
    }

    if (!pnModAvailable('Newsletter')) {
        return;
    }

    pnModDBInfoLoad ('Newsletter');

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    $today = date('w');
    $send_day = pnModGetVar('Newsletter','send_day');
    //if ($send_day == $today) {
        if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_send')) {
            return 'Unable to load class [newsletter_send]';
	}

        $enable_multilingual = pnModGetVar ('Newsletter', 'enable_multilingual', 0);
        if ($enable_multilingual) {
            $_POST['language'] = SessionUtil::getVar ('lang'); // hack, to ensure that language can be retrieved from $_POST
	}
        $_POST['authKey'] = pnModGetVar ('Newsletter', 'admin_key');

        $object = new PNNewsletterSend ();
        $object->save ();

        // prune on send day before noon
        if (date('G')<=12) {
            if (!Loader::loadClassFromModule ('Newsletter', 'archive')) {
                return 'Unable to load class [archive]';
            }
            $object = new PNArchive ();
            $object->prune ();
        }
    //}

    $blockinfo=array();
    return themesideblock($blockinfo);
}

