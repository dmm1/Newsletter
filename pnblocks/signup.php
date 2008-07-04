<?php

@include_once('modules/Newsletter/pnlang/'.pnSessionGetVar('lang').'/user.php');

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
        return false;
    }
	
	if (!pnModAvailable('Newsletter') OR !pnModAPILoad('Newsletter', 'user')) {
		return;
	}
	
	$loggedin = pnUserLoggedIn();
	$allow_anon = pnModGetVar('Newsletter','allow_anon_registration');
	
	if(!$allow_anon and !$loggedin){
		return;
	}
	
	if($loggedin and pnModAPIFunc('Newsletter','user','is_subscribed',
							array('user_id'=>pnUserGetVar('uid')))){
		return;
	}
	
	
    $vars = pnBlockVarsFromContent($blockinfo['content']);	
   	
	$pnRender =& new pnRender('Newsletter');

    $pnRender->assign(array('require_tos'=>$vars['require_tos'],
    						'nl_frequency'=>$vars['nl_frequency'],
    						'nl_type'=>$vars['nl_type'],
    						'loggedin'=>$loggedin));
	
    $blockinfo['content'] = $pnRender->fetch('block_signup_display.htm');

    return themesideblock($blockinfo);
}

function Newsletter_signupblock_modify($blockinfo)
{
    $pnRender =& new pnRender('Newsletter');
	$pnRender->caching = false;
	
	$vars = pnBlockVarsFromContent($blockinfo['content']);

	$types = pnModAPIFunc('Newsletter','user','get_newsletter_types');
	$frequencies = pnModAPIFunc('Newsletter','user','get_newsletter_frequency');

	$pnRender->assign(array('require_tos'=>$vars['require_tos'],
							'nl_types'=>$types,
							'nl_type_sel'=>(isset($vars['nl_type'])?$vars['nl_type']:pnModGetVar('Newsletter','default_type')),
							'nl_frequencies'=>$frequencies,
							'nl_frequency_sel'=>(isset($vars['nl_frequency'])?$vars['nl_frequency']:pnModGetVar('Newsletter','default_frequency'))));
	
	return $pnRender->fetch('block_signup_modify.htm');
}


function Newsletter_signupblock_update($blockinfo)
{
    $vars = pnBlockVarsFromContent($blockinfo['content']);
	
	$vars['require_tos'] = (int)pnVarCleanFromInput('require_tos');
	$vars['nl_type'] = pnVarCleanFromInput('nl_type');
	$vars['nl_frequency'] = pnVarCleanFromInput('nl_frequency');
	
    $blockinfo['content'] = pnBlockVarsToContent($vars);

	$pnRender =& new pnRender('Newsletter');
	$pnRender->clear_cache('block_signup_display.htm');
	
    return $blockinfo;
}

?>