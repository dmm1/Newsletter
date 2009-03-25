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
    pnSecAddSchema('Newsletter:signup:', 'Block title::');
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
    if (!pnSecAuthAction(0, 'Newsletter:signup:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }


    if (!pnModAvailable('Newsletter') || !pnModAPILoad('Newsletter', 'user')) {
        return;
    }
	
    $loggedin = pnUserLoggedIn();
    $allow_anon = pnModGetVar('Newsletter','allow_anon_registration');
	
    if (!$allow_anon && !$loggedin) {
        return;
    }
	
    if ($loggedin) {
        if (Loader::loadClassFromModule ('Newsletter', 'user')) {
            $object = new PNUser();
            $data   = $object->getUser (pnUserGetVar('uid'));
            if ($data) {
                return;
	    }
        } else {
            return;
	}
    }
	
    $vars = pnBlockVarsFromContent($blockinfo['content']);	
    $pnRender = pnRender::getInstance('Newsletter', false);

    $pnRender->assign('require_tos', $vars['require_tos']);
    $pnRender->assign('nl_frequency', $vars['nl_frequency']);
    $pnRender->assign('nl_type', $vars['nl_type']);
	
    $blockinfo['content'] = $pnRender->fetch ('newsletter_block_signup_display.html');
    return themesideblock($blockinfo);
}

function Newsletter_signupblock_modify($blockinfo)
{
    $pnRender = pnRender::getInstance('Newsletter', false);
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return 'Unable to load class [newsletter_util]';
    }

    $pnRender->assign('require_tos', $vars['require_tos']);
    $pnRender->assign('nl_frequency_sel', isset($vars['nl_frequency']) ? $vars['nl_frequency'] : pnModGetVar('Newsletter','default_frequency'));
    $pnRender->assign('nl_type_sel', isset($vars['nl_type']) ? $vars['nl_type'] : pnModGetVar('Newsletter','default_type'));

     return $pnRender->fetch('newsletter_block_signup_modify.html');
}


function Newsletter_signupblock_update($blockinfo)
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);
	
    $vars['nl_frequency'] = (int)FormUtil::getPassedValue ('nl_frequency', 1, 'POST');
    $vars['require_tos']  = (int)FormUtil::getPassedValue ('require_tos', 1, 'POST');
    $vars['nl_type']      = (int)FormUtil::getPassedValue ('nl_type', 1, 'POST');
	
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    $pnRender = pnRender::getInstance('Newsletter', false);
    $pnRender->clear_cache('newsletter_block_signup_display.htm');
	
    return $blockinfo;
}

