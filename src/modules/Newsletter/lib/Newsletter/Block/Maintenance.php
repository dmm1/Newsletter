<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright 2001-2011, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch), Mateo Tibaquirá Palacios (aka: matheo)
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

class Newsletter_Block_Maintenance extends Zikula_Controller_AbstractBlock
{
	public function init()
	{
	SecurityUtil::registerPermissionSchema('Maintenanceblock::', 'Block ID::');
	}

	public function info()
	{
    return array('text_type' => 'maintenance',
                 'module' => 'Newsletter',
                 'text_type_long' => 'Newsletter Maintenance Block',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
	}

	public function display($blockinfo)
	{
	if (!SecurityUtil::checkPermission('Maintenanceblock::', "$blockinfo[bid]::", ACCESS_READ)) {
            return;
        }

    if (!ModUtil::available('Newsletter')) {
        return;
    }

    ModUtil::dbInfoLoad ('Newsletter');

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }
	
	$disable_auto = ModUtil::getVar ('Newsletter', 'disable_auto', 0);
	if ($disable_auto) {
	
	$blockinfo=array();
    return BlockUtil::themesideblock($blockinfo);
	
	 } else {
	
    $today = date('w');
    $send_day = ModUtil::getVar('Newsletter','send_day');
    if ($send_day == $today) {
        if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_send')) {
            return 'Unable to load class [newsletter_send]';
	}

        $enable_multilingual = ModUtil::getVar ('Newsletter', 'enable_multilingual', 0);
        if ($enable_multilingual) {
            $_POST['language'] = SessionUtil::getVar ('lang'); // hack, to ensure that language can be retrieved from $_POST
	}
        $_POST['authKey'] = ModUtil::getVar ('Newsletter', 'admin_key');

        $object = new PNNewsletterSend ();
        $object->save ();

        // prune on send day before noon
        //if (date('G')<=12) {
            //if (!Loader::loadClassFromModule ('Newsletter', 'archive')) {
                //return 'Unable to load class [archive]';
            //}
            //$object = new PNArchive ();
            //$object->prune ();
        //}
    }
	}
    $blockinfo=array();
	return BlockUtil::themeBlock($blockinfo);
}

}
