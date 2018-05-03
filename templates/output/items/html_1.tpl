{if (isset($objectArray.$plugin) && $objectArray.$plugin)}
    <div style="font-size: 16px; font-weight: bold; color: #812323; margin: 10px 0 5px 0; padding: 0;">{nlPluginTitle plugin=$plugin}</div>
    <img src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
    {if $plugin eq 'Newsletter_NewsletterPlugin_NewMembers'}
        <table style="width: 100%; text-align: left;">
            <tr>
              <td>{gt text="Username"}</td>
              <td>{gt text="Register Date"}</td>
            </tr>
            {modavailable modname="Profile" assign="profileAvailable"}
            {foreach from=$objectArray.$plugin item="item" name="loop"}
                <tr>
                    <td>{if $profileAvailable}<div style="font-weight: bold;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Profile" type="user" func="view" uid=$item.uid lang=$nllang fqurl=true assign='url'}{$url|nlTreatUrl|safetext}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></div>{/if}</td>
                    <td>{$item.user_regdate|dateformat}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        {foreach from=$objectArray.$plugin item="item" name="loop"}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {capture assign="nlPicture"}<img src="{$site_url}{$item.nl_picture}" alt="" style="float: left" />{/capture}
                    {if $item.nl_url_title}<a href="{$item.nl_url_title|nlTreatUrl}">{/if}{$nlPicture|nlTreatImg}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <div style="font-size: 14px; font-weight: bold; color: #813939; margin: 10px 0 5px 0; padding: 0;">
                    {if $item.nl_url_title}<a style="color: #813939; text-decoration: none;" href="{$item.nl_url_title|nlTreatUrl}" title="{$item.nl_title|safehtml}">{/if}{$item.nl_title|safehtml}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_content}
                <div style="font-size: 13px; color: #333333; margin: 0 0 5px 0; padding: 0;">
                    {$item.nl_content|nlTreatContent:$plugin}
                </div>
            {/if}
            {if $item.nl_url_readmore}
                <div style="font-size: 13px; color: #333333; margin: 0 0 10px 0; padding: 0;">
                    <a style="color: #680606; text-decoration: none;" href="{$item.nl_url_readmore|nlTreatUrl}">{gt text="read more"} <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="" width="8" height="8" /></a>
                </div>
            {/if}
            {if !$smarty.foreach.loop.last}
                <img src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />
            {/if}
        {/foreach}
    {/if}
    <div style="clear: both"></div>
{/if}
