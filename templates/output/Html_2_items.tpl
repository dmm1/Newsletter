{if (isset($objectArray.$pluginName) && $objectArray.$pluginName)}
    <h1 style="font-size: 1.7em;">{newsletter_get_plugin_title pluginName=$pluginName}</h1>
    <div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
    {if $pluginName eq 'Newsletter_NewsletterPlugin_NewMembers'}
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
                {foreach from=$objectArray.$pluginName item="item"}
                    <tr>
                        <td>
                        {if $profileAvailable}
                            <h3>
                                <a href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">
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
        {foreach from=$objectArray.$pluginName item="item" name="loop"}
{strip}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {capture assign="nlPicture"}<img src="{$site_url}{$item.nl_picture}" alt="" style="float: left" />{/capture}
                    {if $item.nl_url_title}<a href="{$item.nl_url_title}">{/if}{$nlPicture|nlTreatImg}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <h2 style="text-decoration:none;font-size: 1.6em; color: #555; margin: .4em 0 .3em 0;">
                    {if $item.nl_url_title}
                        <a style="text-decoration: none; color: #555" href="{$item.nl_url_title}" title="{$item.nl_title|safehtml}">
                    {/if}
                    {$item.nl_title|safehtml}
                    {if $item.nl_url_title}
                        </a>
                    {/if}
                </h2>
            {/if}
            {if $item.nl_content}
                <p style="padding:8px;">{$item.nl_content|nlTreatContent:$pluginName}</p>
            {/if}
{/strip}
            {if $item.nl_url_readmore}
                
                    <a href="{$item.nl_url_readmore}" style="-moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;text-decoration: none; color: #000; background-color: #ECF8FF; padding: 5px 15px; font-size: 16px; line-height: 1.4em; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: normal; margin-left: 0; white-space: nowrap;">{gt text="read more"}</a>
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
