<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{gt text="Newsletter"}</title>
  </head>
  <body style="margin: 0; -webkit-text-size-adjust: none; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; height: 100%; -webkit-font-smoothing: antialiased; width: 100% !important;"><p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">
{if !$site_url}{assign var='site_url' value=$baseurl}{/if}
{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang} 


<table bgcolor="#a8defd" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
		<td align="" bgcolor="#a8defd" style="max-width: 600px !important; clear: both !important; margin: 0 auto; background-color: #a8defd; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; display: block !important;">
			
			
			<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #a8defd; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
				<table bgcolor="#a8defd" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><h6 style="font-size: 14px; font-weight: 900; text-transform: uppercase; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0; color: #fff; padding: 0;"><center style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{$objectArray.title}</center></h6></td>
					</tr></table></div>
			
		</td>
		<td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
	</tr></table><table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
		<td align="" bgcolor="#FFFFFF" style="max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block !important;">
			
                      
                      <br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
			{if (isset($user_name) && $user_name)}
			<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;"><table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
						<p style="-webkit-border-radius: 4px; font-size: 14px; font-weight: normal; margin: 0 0 15px; background-color: #ECF8FF; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.6; padding: 0; border-radius: 4px;"></p><center style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Hello"} {$user_name}</strong></center>
					</td>
				</tr></table></div>
			 {/if}
			
			
			{if (isset($objectArray.NewsletterMessage) & $objectArray.NewsletterMessage)}
			<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;"><table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
						<p style="-webkit-border-radius: 4px; font-size: 14px; font-weight: normal; margin: 0 0 15px; background-color: #ECF8FF; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.6; padding: 0; border-radius: 4px;">{$objectArray.NewsletterMessage|safehtml}</p>					
					</td>
				</tr></table></div>
			{/if}
			
			
			{if (isset($objectArray.News) && $objectArray.News)}
			<div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="News"}</h2></div>
			{foreach from=$objectArray.News item="item" name="loop"}
			<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;"><table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td width="20%" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0 10px 0 0; vertical-align: top;" valign="top"> <a href="{modurl modname=" news="" type="user" func="display" sid="$item.sid" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><img src="{$site_url}images/news_picupload/pic_sid{$item.sid}-0-thumb2.jpg" alt="" style="max-width: 100%; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /></a></td>
					<td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
						<h4 style="font-size: 23px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" news="" type="user" func="display" sid="$item.sid" newlang="$nllang" fqurl="true}&quot;" title="{$item.title|safehtml}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.title|safehtml}</a></h4>
						<p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.hometext|trim|safehtml|url_check}</p>
						<a href="{modurl modname=" news="" type="user" func="display" sid="$item.sid" newlang="$nllang" fqurl="true}&quot;" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>						
					</td>
				</tr></table></div>
			{/foreach}
			{/if}
			
			
			{if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
                <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Welcome New Members"}</h2></div>
                    <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;"><table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><th style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Username"}</th>
                          <th style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Register Date"}</th>
                        </tr>
                        {modavailable modname="Profile" assign="profileAvailable"}
                        {foreach from=$objectArray.NewMembers item="item" name="loop"}
                          <tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{if $profileAvailable}<h5 style="font-size: 17px; font-weight: 900; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" profile="" type="user" func="view" uid="$item.uid" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></h5>{/if}</td>
                            <td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{$item.user_regdate}</td>
                          </tr>
                        {/foreach}
                      </table></div>
                    {/if}

                    {if (isset($objectArray.Content) && $objectArray.Content)}
                      <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="New Content Items"}</h2></div>
                       {foreach from=$objectArray.Content item="item" name="loop"}
                        <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
						<h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" content="" type="user" func="view" pid="$item.id" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.title|safehtml}</a></h3>
                        
						</div>
					  {/foreach}
                      <br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
                    {/if}

                    {if (isset($objectArray.Pages) && $objectArray.Pages)}
                       <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Recently Added Documents"}</h2></div>
                      
                      {foreach from=$objectArray.Pages item="item" name="loop"}
					  <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
                        <h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" pages="" type="user" func="display" pageid="$item.pageid" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.title|safehtml}</a></h3>
                        <p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.content|safehtml|url_check|truncate:400}</p>
                        <a href="{modurl modname=" pages="" type="user" func="display" pageid="$item.pageid" newlang="$nllang" fqurl="true}&quot;" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>
                        
                     </div>
					 {/foreach} 
                    {/if}

                    {if (isset($objectArray.EZComments) && $objectArray.EZComments)}        
                       <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Latest Comments"}</h2></div>
                        {foreach from=$objectArray.EZComments item="item" name="loop"}
					    <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
                        <h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{$item.url}&amp;newlang={$nllang}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.subject}</a></h3>
                        <p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.comment|safehtml|url_check|truncate:400}</p>
                        <a href="{$item.url}" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>
                         
                      </div>
					  {/foreach} <br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
                    {/if}

                    {if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}        
                       <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Latest Forum Posts"}</h2></div>
                      
                      {foreach from=$objectArray.Dizkus item="item" name="loop"}
                        <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
						<h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" dizkus="" type="user" func="viewtopic" topic="$item.topic_id" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.topic_title}</a></h3>
                         
						</div>
					  {/foreach}                    
                    {/if}

                    {if (isset($objectArray.Clip) && $objectArray.Clip)}
                      <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Recently Added Publications"}</h2></div>
                      
                      {foreach from=$objectArray.Clip item="item" name="loop"}
                        <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
						<h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" clip="" type="user" func="viewpub" tid="$item.core_tid" pid="$item.core_pid" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.core_title|safehtml}</a></h3>
                        <p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.content|safehtml|url_check|truncate:400}</p>
                        <a href="{modurl modname=" clip="" type="user" func="viewpub" tid="$item.core_tid" pid="$item.core_pid" newlang="$nllang" fqurl="true}&quot;" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>
                        
						</div>
					 {/foreach}
                      <br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
                    {/if}

                    {if (isset($objectArray.Weblinks) && $objectArray.Weblinks)}        
                      <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Latest web links"}</h2></div>
                      
                      {foreach from=$objectArray.Weblinks item="item" name="loop"}
					  <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
                        <h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{$item.url}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.title}</a></h3>
                        <p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.description|safehtml|url_check|truncate:400}</p>
                        <a href="{modurl modname=" weblinks="" type="user" func="viewlinkdetails" lid="$item.lid" newlang="$nllang" fqurl="true}&quot;" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>
						</div>
					 {/foreach}
                    {/if}

                    {if (isset($objectArray.Downloads) && $objectArray.Downloads)}        
                      <div style="-webkit-border-radius: 4px 4px 0px 0px; margin: 0 0 -14px; background-color: #a8defd; padding: 0 0 0 12px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; border-radius: 4px 4px 0px 0px;color:#fff;"><h2 style="font-size: 31px; font-weight: 200; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 12px 0 15px; color: #fff; padding: 0;">{gt text="Latest downloads"}</h2></div>
                      
                      {foreach from=$objectArray.Downloads item="item" name="loop"}
                        <div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
						<h3 style="font-size: 27px; font-weight: 500; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;"><a href="{modurl modname=" downloads="" type="user" func="display" lid="$item.lid" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">{$item.title}</a></h3>
                        <p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{$item.description|safehtml|url_check|truncate:400}</p>
                        <a href="{modurl modname=" downloads="" type="user" func="display" lid="$item.lid" newlang="$nllang" fqurl="true}&quot;" style="-webkit-border-radius: 4px; font-weight: bold; cursor: pointer; margin: 0 0 10px; background-color: #fff; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 8px 12px; text-decoration: none; color: #4291bf; display: block !important; text-align: center; background-image: none !important;width:140px; border-radius: 4px;">{gt text="read more"} »</a>
                        </div>
                      {/foreach}
                      <br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
                    {/if}
			
			
			<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
				<table bgcolor="" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
							
							
							<table bgcolor="" width="100%" style="margin: 0; background-color: #ebebeb; padding: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
										
										
										<div style="min-width: 279px; margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: auto !important; float: left !important;">
											<table bgcolor="" cellpadding="" align="left" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 15px;">				
												
												<h5 style="font-size: 17px; font-weight: 900; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;">{gt text='Connect with Us'}:</h5>
												<p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;"><a href="#d41d8cd98f00b204e9800998ecf8427e" style="font-size: 12px; font-weight: bold; margin: 0 0 10px; padding: 3px 7px; background-color: #3B5998 !important; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; text-decoration: none; color: #FFF; display: block; text-align: center;">Facebook</a> <a href="#d41d8cd98f00b204e9800998ecf8427e" style="font-size: 12px; font-weight: bold; margin: 0 0 10px; padding: 3px 7px; background-color: #1daced !important; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; text-decoration: none; color: #FFF; display: block; text-align: center;">Twitter</a> <a href="#d41d8cd98f00b204e9800998ecf8427e" style="font-size: 12px; font-weight: bold; margin: 0 0 10px; padding: 3px 7px; background-color: #DB4A39 !important; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; text-decoration: none; color: #FFF; display: block; text-align: center;">Google+</a></p>
						
												
											</td>
										</tr></table></div>
										
										
										<div style="min-width: 279px; margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: auto !important; float: right !important;">
											<table bgcolor="" cellpadding="" align="left" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 15px;">				
																			
												<h5 style="font-size: 17px; font-weight: 900; line-height: 1.1; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; margin: 0 0 15px; color: #000; padding: 0;">{gt text='Contact Information'}:</h5>												
												<p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">{gt text='Phone'}: <strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">+43 000 555</strong><br style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;" />
												{gt text='Email'}: <strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><a href="emailto:me@you.com" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">me@you.com</a></strong></p>
                
											</td>
										</tr></table></div>
										
										<div style="clear: both; margin: 0; padding: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block;"></div>
	
									</td>
								</tr></table></td>
					</tr></table></div>
			

		</td>
		<td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
	</tr></table><table style="clear: both !important; margin: 0; padding: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
		<td style="max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block !important;">
			
				
				<div style="max-width: 600px; -webkit-border-radius: 0px 0px 4px 4px; margin: 0 auto; background-color: #ECF8FF; padding: 15px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block; border-radius: 0px 0px 4px 4px;">
					<table style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0; width: 100%;"><tr style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"><td align="center" style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">
								<p style="font-weight: normal; font-size: 14px; line-height: 1.6; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0 0 10px; padding: 0;">
									<a href="{modurl modname=" newsletter="" type="user" func="main" ot="archive" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Archive"}</strong></a> |
									<a href="{modurl modname=" newsletter="" type="user" func="main" ot="tos" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Terms of Service"}</strong></a> |
									<a href="{modurl modname=" newsletter="" type="user" func="main" ot="unsubscribe" newlang="$nllang" fqurl="true}&quot;" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><strong style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;">{gt text="Unsubscribe"}</strong></a>
								</p>
							</td>
						</tr></table></div>
				
		</td>
		<td style="margin: 0; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; padding: 0;"></td>
	</tr></table></body>
</html>
