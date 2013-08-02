{if (isset($objectArray.$plugin) && $objectArray.$plugin)}
    <div style="clear: both; float: none; width: 100%;">
    <h2>{nlPluginTitle plugin=$plugin}</h2>
    <hr />
    {if $plugin eq 'Newsletter_NewsletterPlugin_NewMembers'}
        <table style="width: 100%; text-align: left;">
            <tr>
              <td>{gt text="Username"}</td>
              <td>{gt text="Register Date"}</td>
            </tr>
            {modavailable modname="Profile" assign="profileAvailable"}
            {foreach from=$objectArray.$plugin item="item" name="loop"}
                <tr>
                    <td>{if $profileAvailable}<div style="font-weight: bold;"><a style="text-decoration: none;" href="{modurl modname="Profile" type="user" func="view" uid=$item.uid lang=$nllang fqurl=true}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></div>{/if}</td>
                    <td>{$item.user_regdate|dateformat}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        {foreach from=$objectArray.$plugin item="item" name="loop"}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {capture assign="nlPicture"}<img src="{$site_url}{$item.nl_picture}" alt="" style="float: left" />{/capture}
                    {if $item.nl_url_title}<a href="{$item.nl_url_title}">{/if}{$nlPicture|nlTreatImg:400}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <h3>{if $item.nl_url_title}<a style="text-decoration: none;" href="{$item.nl_url_title}" title="{$item.nl_title|safehtml}">{/if}{$item.nl_title|safehtml}{if $item.nl_url_title}</a>{/if}</h3>
            {/if}
            {if $item.nl_content}
                <div style="margin: 0 0 5px 0; padding: 0;">
                    {$item.nl_content|nlTreatImg:400|safehtml}
                </div>
            {/if}
            {if $item.nl_url_readmore}
                <div style="margin: 0 0 10px 0; padding: 0;">
                    <a style="text-decoration: none;" href="{$item.nl_url_readmore}">{gt text="read more"} »</a>
                </div>
            {/if}
            {if !$smarty.foreach.loop.last}
                <hr style="border-style:dotted;" />
            {/if}
        {/foreach}
    {/if}
    </div>
{/if}
