<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>{if !$site_url}{assign var='site_url' value=$baseurl}{/if}
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <base href="{$site_url}" />
    <title>{gt text="Newsletter"}</title>
    <style type="text/css">td.nl-main-class div a { color: #680606; }</style>
</head>
{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
<body style="margin:0; padding:0; background-color: #eeeeee; font-family: Arial;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #eeeeee;">
    <tr>
      <td align="center">
        <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff;">
            <tr>
                <td class="nl-header" align="left" style="background-color: #ECF8FF">
                    <h2 style="padding:10px 0px 10px 20px; font-size:20px; color:#812323;">{$objectArray.title}</h2>
                </td>
            </tr>
            <tr>
                <td class="nl-main-class" align="left" style="padding: 15px; background-color: #ffffff;">
                    {if (isset($user_name) && $user_name)}
                    <div style="font-size: 16px; font-weight: bold; color: #812323; margin: 10px 0 5px 0; padding: 0;">
                        {gt text="Hello"} {$user_name}
                    </div>
                    {/if}

                    {if (isset($objectArray.Newsletter_NewsletterPlugin_NewsletterMessage) && $objectArray.Newsletter_NewsletterPlugin_NewsletterMessage)}
                    <div style="font-size: 13px; color: #333333; margin: 10px 0 5px 0; padding: 0;">
                        {$objectArray.NewsletterMessage|safehtml}
                    </div>
                    {/if}

                    {assign var='includeFile' value='output/Html_1_items.tpl'}
                    {nlActivePlugins assign='plugins'}
                    {foreach from=$plugins item='plugin'}
                        {if $plugin != 'Newsletter_NewsletterPlugin_NewsletterMessage'}
                            {include file=$includeFile plugin=$plugin}
                        {/if}
                    {/foreach}
                </td>
            </tr>
            <tr>
                <td>
                    <!--- social section -->
                    <table style="width: 100%; background-color:#ECF8FF; padding: 3px 7px; text-align:center;">
                        <tr>
                            <td style="width: 40%;">
                                {if $modvars.Newsletter.contact_facebook or $modvars.Newsletter.contact_twitter or $modvars.Newsletter.contact_google}
                                    {if $modvars.Newsletter.contact_facebook}<a href="{$modvars.Newsletter.contact_facebook|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#3B5998;">Facebook</a> {/if}
                                    {if $modvars.Newsletter.contact_twitter}<a href="{$modvars.Newsletter.contact_twitter|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#1daced;">Twitter</a> {/if}
                                    {if $modvars.Newsletter.contact_google}<a href="{$modvars.Newsletter.contact_google|safetext}" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#DB4A39;">Google+</a> {/if}
                                {/if}
                            </td>
                            <td style="width: 60%;">
                                {if $modvars.Newsletter.contact_phone or $modvars.Newsletter.contact_email}
                                    <h5 style="color:#000;">{gt text='Contact Information'}:</h5>												
                                    {if $modvars.Newsletter.contact_phone}{gt text='Phone'}: <strong>{$modvars.Newsletter.contact_phone|safetext}</strong><br/>{/if}
                                    {if $modvars.Newsletter.contact_email}{gt text='Email'}: <strong><a href="emailto:me@you.com">{$modvars.Newsletter.contact_email|safetext}</a></strong>{/if}
                                {/if}
                            </td>
                        </tr>
                    </table>
                    <!-- /social & contact -->
                    <div style="font-family: Arial; font-size: 12px; color: #333333; margin: 5px; text-align: center">
                        <a style="color: #680606; text-decoration: none;" href="{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}"><strong>{gt text="View Archives"}</strong></a> |
                        <a style="color: #680606; text-decoration: none;" href="{modurl modname="Newsletter" type="user" func="main" ot="tos" newlang=$nllang fqurl=true}"><strong>{gt text="Terms of Service"}</strong></a> |
                        <a style="color: #680606; text-decoration: none;" href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}"><strong>{gt text="Unsubscribe"}</strong></a>
                    </div>
                </td>
            </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
