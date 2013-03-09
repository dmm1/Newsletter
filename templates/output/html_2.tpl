<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{if !$site_url}{assign var='site_url' value=$baseurl}{/if}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width initial-scale = 1, user-scalable = no" />
<base href="{$site_url}" />
<title>{gt text="Newsletter"}</title>
</head>
{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
<body style="background: #DDDDDD; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; color: #666; text-align: center; margin: 0; padding: 0;">

<table border="0" cellspacing="0" cellpadding="0" bgcolor="#DDDDDD"  style="width: 100%; background-color: #fff; text-align: left; margin: 0 auto; max-width: 600px; min-width: 320px;">
    <tr>
        <td>
            <div style="width: 100%; background-color: #43A4D0; height: 8px;"></div>

            <table border="0" cellspacing="0" cellpadding="0"  style="margin: 0 0 5px 0; font-size: 1.8em; color: #0088cc; background-color: #EFEFEF; padding: 0; border-bottom: 1px solid #DDD; width: 100%;">
                <tr>
                    <td>
                        <h2 style="padding: 0px; margin: 5px 20px; font-size: 16px; line-height: 1; font-weight: normal; color: #464646; font-family: Arial, sans-serif;">
                            <strong>{$objectArray.title}</strong>
                        </h2>
                    </td>
                    <!-- Use Username or replace it with 32x32 Logo -->
                    <td style="text-align: right;">
                    {if (isset($user_name) && $user_name)}
                        <h2 style="padding: 0px; margin: 5px 20px; font-size: 16px; line-height: 1; font-weight: normal; color: #464646; font-family: Arial, sans-serif;">{gt text="Hello"} {$user_name}</h2>
                    {else}
                        {img modname='Newsletter' src="newsletter_images/logo.png" style="margin: 5px 20px 5px 0; vertical-align: middle; vertical-align: middle;"}
                    {/if}
                    </td>
                </tr>
            </table>

            <table style="width: 100%;"  border="0" cellspacing="0" cellpadding="20" bgcolor="#ffffff">
                <tr>
                    <td>
                        {if (isset($objectArray.Newsletter_NewsletterPlugin_NewsletterMessage) && $objectArray.Newsletter_NewsletterPlugin_NewsletterMessage)}
                            <div style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 6px 6px 6px 6px;border-radius: 6px 6px 6px 6px;">
                                <p>{$objectArray.Newsletter_NewsletterPlugin_NewsletterMessage|safehtml}</p>
                            </div>
                            <div style="margin-top: 1em; max-width: 560px;"></div>
                        {/if}
                        
                        {assign var='includeFile' value='output/items/html_2.tpl'}
                        {nlActivePlugins assign='plugins'}
                        {foreach from=$plugins item='plugin'}
                            {if $plugin != 'Newsletter_NewsletterPlugin_NewsletterMessage'}
                                {include file=$includeFile plugin=$plugin}
                            {/if}
                        {/foreach}
                    </td>
                </tr>
            </table>
            <div style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
                <div style="padding: 3px 3px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center; width: 100%">
                    {if $modvars.Newsletter.contact_facebook or $modvars.Newsletter.contact_twitter or $modvars.Newsletter.contact_google}
                        <h5 style="color:#000;">{gt text='Connect with Us'}:</h5>
                        <p>
                            {if $modvars.Newsletter.contact_facebook}<a href="{$modvars.Newsletter.contact_facebook|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#3B5998!important;">Facebook</a> {/if}
                            {if $modvars.Newsletter.contact_twitter}<a href="{$modvars.Newsletter.contact_twitter|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#1daced!important;">Twitter</a> {/if}
                            {if $modvars.Newsletter.contact_google}<a href="{$modvars.Newsletter.contact_google|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#DB4A39!important;">Google+</a> {/if}
                        </p>
                    {/if}
{strip}
                    {if $modvars.Newsletter.contact_phone or $modvars.Newsletter.contact_email}
                        <h5 style="color:#000;">{gt text='Contact Information'}:</h5>
                        <p style="color:#000;">
                            {if $modvars.Newsletter.contact_phone}{gt text='Phone'}: <strong>{$modvars.Newsletter.contact_phone|safetext}</strong><br/>
                            {/if}
                            {if $modvars.Newsletter.contact_email}{gt text='Email'}: <strong><a href="emailto:{$modvars.Newsletter.contact_email|safetext}">{$modvars.Newsletter.contact_email|safetext}</a></strong>
                            {/if}
                        </p>
                    {/if}
                </div>
            </div>

            <div style="width: 100%; background-color: #efefef; text-align: center; border-top: 1px solid #dddddd; color: #888; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4em; padding-top: 20px">
                <p style="color: #444444; margin: 0px;">
                    {gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe"} <a href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}">
                        <strong>{gt text="here"}</strong>
                    </a>.
                </p>
                <p>
                    <a href="{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}">
                        <strong>{gt text="Link to the Newsletter Archive"}</strong>
                    </a>
                </p>
            </div>
            <div style="width: 100%; background-color: #43A4D0; height: 3px;"></div>
        </td>
    </tr>
</table>

</body>
</html>
{/strip}
