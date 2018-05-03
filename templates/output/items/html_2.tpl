{if (isset($objectArray.$plugin) && $objectArray.$plugin)}{strip}
    <h3 style="font-size: 1.4em;margin: 0px;color:#555">{nlPluginTitle plugin=$plugin}</h3>
    <div style="color: #666; font-size:14px; margin-top: 4px; margin-bottom:8px; border-bottom: 1px solid #eee; overflow: hidden">
    {/strip}{if $plugin eq 'Newsletter_NewsletterPlugin_NewMembers'}
{strip}
        <table class="nl-new-members">
            <thead>
                <tr>
                    <th>{gt text="Username"}</th>
                    <th>{gt text="Register Date"}</th>
                </tr>
            </thead>
            <tbody>
                {modavailable modname="Profile" assign="profileAvailable"}
                {foreach from=$objectArray.$plugin item="item"}
                    <tr>
                        <td>
                        {if $profileAvailable}
                            <h3>
                                <a href="{modurl modname="Profile" type="user" func="view" uid=$item.uid lang=$nllang fqurl=true assign='url'}{$url|nlTreatUrl|safetext}">
                        {/if}
                        {$item.uname|safehtml}
                        {if $profileAvailable}
                                </a>
                            </h3>
                        {/if}
                        </td>
                        <td>{$item.user_regdate|dateformat}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
{/strip}
    {else}
        {foreach from=$objectArray.$plugin item="item" name="loop"}
{strip}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {capture assign="nlPicture"}<img src="{$site_url}{$item.nl_picture}" alt="" style="float: left" />{/capture}
                    {if $item.nl_url_title}<a href="{$item.nl_url_title|nlTreatUrl}">{/if}{$nlPicture|nlTreatImg}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <h2 style="text-decoration:none;font-size: 1.0em; color: #555; margin: .4em 0 .3em 0;">
                    {if $item.nl_url_title}
                        <a style="text-decoration: none; color: #555" href="{$item.nl_url_title|nlTreatUrl}" title="{$item.nl_title|safehtml}">
                    {/if}
                    {$item.nl_title|safehtml}
                    {if $item.nl_url_title}
                        </a>
                    {/if}
                </h2>
            {/if}
            {if $item.nl_content}
                <p style="padding:8px;margin:0px">{$item.nl_content|nlTreatContent:$plugin}</p>
            {/if}
{/strip}
            {if $item.nl_url_readmore}
                
                    <a href="{$item.nl_url_readmore|nlTreatUrl}" style="-moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;text-decoration: none; color: #000; background-color: #ECF8FF; padding: 5px 15px; font-size: 16px; line-height: 1.4em; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: normal; margin-left: 0; white-space: nowrap;">{gt text="read more"}</a>
            {/if}
{strip}
            {if !$smarty.foreach.loop.last}
               <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />
            {/if}
{/strip}
        {/foreach}
    {/if}
    </div>
    <div style="clear: both"></div>
{/if}
