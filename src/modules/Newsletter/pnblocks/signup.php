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


function Newsletter_signupblock_init()
{
    SecurityUtil::registerPermissionSchema('Newsletter:signup:', 'Block title::');
}


function Newsletter_signupblock_info()
{
    return array('text_type'      => 'signup',
                 'module'         => 'Newsletter',
                 'text_type_long' => 'Display a newsletter signup form',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

function Newsletter_signupblock_display($blockinfo)
{
    if (!SecurityUtil::checkPermission*(0, 'Newsletter:signup:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }


    if (!ModUtil::available('Newsletter') || !ModUtil::loadApi('Newsletter', 'user')) {
        return;
    }
	
    $loggedin = UserUtil::isLoggedIn();
    $allow_anon = ModUtil::getVar('Newsletter','allow_anon_registration');
	
    if (!$allow_anon && !$loggedin) {
        return;
    }
	
    if ($loggedin) {
        if (Loader::loadClassFromModule ('Newsletter', 'user')) {
            $object = new PNUser();
            $data   = $object->getUser (UserUtil::getVar('uid'));
            if ($data) {
                return;
	    }
        } else {
            return;
	}
    }
	
    $vars = BlockUtil::varsFromContent($blockinfo['content']);	
    $view = Zikula_View::getInstance('Newsletter', false);

    $view->assign('require_tos', $vars['require_tos']);
    $view->assign('nl_frequency', $vars['nl_frequency']);
    $view->assign('nl_type', $vars['nl_type']);
	
    $blockinfo['content'] = $view->fetch ('newsletter_block_signup_display.html');
    return BlockUtil::themesideblock($blockinfo);
}

function Newsletter_signupblock_modify($blockinfo)
{
    $view = Zikula_View::getInstance('Newsletter', false);
    $vars = BlockUtil::varsFromContent($blockinfo['content']);

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    $view->assign('require_tos', $vars['require_tos']);
    $view->assign('nl_frequency_sel', isset($vars['nl_frequency']) ? $vars['nl_frequency'] : ModUtil::getVar('Newsletter','default_frequency'));
    $view->assign('nl_type_sel', isset($vars['nl_type']) ? $vars['nl_type'] : ModUtil::getVar('Newsletter','default_type'));

     return $view->fetch('newsletter_block_signup_modify.html');
}


function Newsletter_signupblock_update($blockinfo)
{
    $vars = BlockUtil::varsFromContent($blockinfo['content']);
	
    $vars['nl_frequency'] = (int)FormUtil::getPassedValue ('nl_frequency', 1, 'POST');
    $vars['require_tos']  = (int)FormUtil::getPassedValue ('require_tos', 1, 'POST');
    $vars['nl_type']      = (int)FormUtil::getPassedValue ('nl_type', 1, 'POST');
	
    $blockinfo['content'] = BlockUtil::varsToContent($vars);

    $view = Zikula_View::getInstance('Newsletter', false);
    $view->clear_cache('newsletter_block_signup_display.htm');
	
    return $blockinfo;
}

