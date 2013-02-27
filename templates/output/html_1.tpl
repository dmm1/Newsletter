<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>{if !$site_url}{assign var='site_url' value=$baseurl}{/if}
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
            <td style="padding: 0 20px 20px 20px; background-color: #ffffff;">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td valign="top" class="nl-main-class" align="left">

                    {if (isset($user_name) && $user_name)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Hello"} {$user_name}</h2>
                      <br />
                    {/if}

                    {if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}
                      <br />
                      <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$objectArray.NewsletterMessage|safehtml}</div>
                      <br />
                    {/if}

                    {if (isset($objectArray.News) && $objectArray.News)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="News"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.News item="item" name="loop"}
					    {if $modvars.News.picupload_enabled AND $item.pictures gt 0}
						<span style="float:left;margin-right:4px;margin-bottom:4px;"><a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}"><!--[*<span></span>*]--><img src="{$site_url}{$modvars.News.picupload_uploaddir}/pic_sid{$item.sid}-0-thumb2.jpg" alt="" /></a></span>
						{/if}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}" title="{$item.title|safehtml}">{$item.title|safehtml}</a></h3>
						<div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.hometext|nlTreatContent:'News'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Welcome New Members"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      <table style="width: 100%; text-align: left; font:normal 12px arial; ">
                        <tr>
                          <th>{gt text="Username"}</th>
                          <th>{gt text="Register Date"}</th>
                        </tr>
                        {modavailable modname="Profile" assign="profileAvailable"}
                        {foreach from=$objectArray.NewMembers item="item" name="loop"}
                          <tr>
                            <td>{if $profileAvailable}<h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></h3>{/if}</td>
                            <td>{$item.user_regdate}</td>
                          </tr>
                        {/foreach}
                      </table>
                      <br />
                    {/if}

                    {if (isset($objectArray.Content) && $objectArray.Content)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="New Content Items"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Content item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Content" type="user" func="view" pid=$item.id newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.Pages) && $objectArray.Pages)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Recently Added Documents"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Pages item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Pages" type="user" func="display" pageid=$item.pageid newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.content|nlTreatContent:'Pages'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{modurl modname="Pages" type="user" func="display" pageid=$item.pageid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.EZComments) && $objectArray.EZComments)}        
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Latest Comments"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.EZComments item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{$item.url}&newlang={$nllang}">{$item.subject}</a></h3>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.comment|nlTreatContent:'EZComments'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{$item.url}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                         {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}        
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Latest Forum Posts"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Dizkus item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Dizkus" type="user" func="viewtopic" topic=$item.topic_id newlang=$nllang fqurl=true}">{$item.topic_title}</a></h3>
                         {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.Clip) && $objectArray.Clip)}
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Recently Added Publications"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Clip item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Clip" type="user" func="viewpub" tid=$item.core_tid pid=$item.core_pid newlang=$nllang fqurl=true}">{$item.core_title|safehtml}</a></h3>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.content|nlTreatContent:'Clip'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{modurl modname="Clip" type="user" func="viewpub" tid=$item.core_tid pid=$item.core_pid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.Weblinks) && $objectArray.Weblinks)}        
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Latest web links"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Weblinks item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{$item.url}">{$item.title}</a></h3>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.description|nlTreatContent:'Weblinks'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{modurl modname="Weblinks" type="user" func="viewlinkdetails" lid=$item.lid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}

                    {if (isset($objectArray.Downloads) && $objectArray.Downloads)}        
                      <h2 style="font-size: 16px; font-weight: bold; color: #812323; margin: 0; padding: 0;">{gt text="Latest downloads"}</h2>
                      <img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                      {foreach from=$objectArray.Downloads item="item" name="loop"}
                        <h3 style="font-size: 14px; font-weight: bold; color: #813939; margin: 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Downloads" type="user" func="display" lid=$item.lid newlang=$nllang fqurl=true}">{$item.title}</a></h3>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;">{$item.description|nlTreatContent:'Downloads'}</div>
                        <div style="font-size: 13px; color: #333333; margin: 0 0 14px 0; padding: 0;"><a style="color: #680606; text-decoration: none;" href="{modurl modname="Downloads" type="user" func="display" lid=$item.lid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></div>
                        {if (!$smarty.foreach.loop.last)}<img style="margin: 0; padding: 0 0 10px 0;" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                      {/foreach}
                      <br />
                    {/if}
                  </td>
                </tr>
              </table>
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
            <p style="font-size: 12px; color: #333333;padding:4px; text-align: center">
                <strong>{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe"} <a style="color: #680606;" href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}">{gt text="here!"}</a></strong>
                <br /><br />
                <a style="color: #680606;" href="{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}"><strong>{gt text="Link to the Newsletter Archive"}</strong></a>
            </p>
           </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
