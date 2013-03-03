{if (isset($objectArray.$pluginName) && $objectArray.$pluginName)}
    <div style="font-size: 16px; font-weight: bold; color: #812323; margin: 10px 0 5px 0; padding: 0;">{$pluginTitle}</div>
    <img src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
    {if $pluginName eq 'NewMembers'}
        <table style="width: 100%; text-align: left;">
            <tr>
              <th>{gt text="Username"}</th>
              <th>{gt text="Register Date"}</th>
            </tr>
            {modavailable modname="Profile" assign="profileAvailable"}
            {foreach from=$objectArray.$pluginName item="item" name="loop"}
                <tr>
                    <td>{if $profileAvailable}<div style="font-size: 14px; font-weight: bold; color: #813939; margin: 10px 0 5px 0; padding: 0;"><a style="color: #813939; text-decoration: none;" href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></div>{/if}</td>
                    <td>{$item.user_regdate}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        {foreach from=$objectArray.$pluginName item="item" name="loop"}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {if $item.nl_url_title}<a href="{$item.nl_url_title}">{/if}<img src="{$site_url}{$item.nl_picture}" alt="" />{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <div style="font-size: 14px; font-weight: bold; color: #813939; margin: 10px 0 5px 0; padding: 0;">
                    {if $item.nl_url_title}<a style="color: #813939; text-decoration: none;" href="{$item.nl_url_title}" title="{$item.nl_title|safehtml}">{/if}{$item.nl_title|safehtml}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_content}
                <div style="font-size: 13px; color: #333333; margin: 0 0 5px 0; padding: 0;">
                    {$item.nl_content|nlTreatContent:$pluginName}
                </div>
            {/if}
            {if $item.nl_url_readmore}
                <div style="font-size: 13px; color: #333333; margin: 0 0 10px 0; padding: 0;">
                    <a style="color: #680606; text-decoration: none;" href="{$item.nl_url_readmore}">{gt text="read more"} <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="" width="8" height="8" /></a>
                </div>
            {/if}
            {if !$smarty.foreach.loop.last}
                <img src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />
            {/if}
        {/foreach}
    {/if}
{/if}
