{if (isset($objectArray.$plugin) && $objectArray.$plugin)}
    <div class="title" style="background-color:#a8defd;padding-left:12px;margin-bottom:-14px;-webkit-border-radius: 4px 4px 0px 0px;border-radius: 4px 4px 0px 0px;"><h2 style="margin-top:12px;color:#fff;">{nlPluginTitle plugin=$plugin}</h2></div>
    <div style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
    {if $plugin eq 'Newsletter_NewsletterPlugin_NewMembers'}
        <table style="width: 100%; text-align: left;">
            <tr>
              <td>{gt text="Username"}</td>
              <td>{gt text="Register Date"}</td>
            </tr>
            {modavailable modname="Profile" assign="profileAvailable"}
            {foreach from=$objectArray.$plugin item="item" name="loop"}
                <tr>
                    <td>{if $profileAvailable}<div style="font-weight: bold;"><a style="text-decoration: none;" href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></div>{/if}</td>
                    <td>{$item.user_regdate|dateformat}</td>
                </tr>
            {/foreach}
        </table>
    {else}
        {foreach from=$objectArray.$plugin item="item" name="loop"}
            {if $item.nl_picture}
                <div style="float: left; margin-right: 4px; margin-bottom: 4px;">
                    {capture assign="nlPicture"}<img src="{$site_url}{$item.nl_picture}" alt="" style="float: left" />{/capture}
                    {if $item.nl_url_title}<a href="{$item.nl_url_title}">{/if}{$nlPicture|nlTreatImg}{if $item.nl_url_title}</a>{/if}
                </div>
            {/if}
            {if $item.nl_title}
                <div style="font-weight:500; font-size: 23px;color:#fff;">
                    <h4 style="font-weight:500; font-size: 23px;color:#fff; text-decoration: none;">
                        {if $item.nl_url_title}<a href="{$item.nl_url_title}" title="{$item.nl_title|safehtml}">{/if}{$item.nl_title|safehtml}{if $item.nl_url_title}</a>
                        {/if}
                    </h4>
                </div>
            {/if}
            {if $item.nl_content}
                <div style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
                    {$item.nl_content|nlTreatContent:$plugin}
                </div>
            {/if}
            {if $item.nl_url_readmore}
                <a style="text-decoration:none;color: #4291bf;background-color: #fff;padding:8px 12px;font-weight:bold;margin-right:10px;text-align:center;cursor:pointer;display: inline-block;-webkit-border-radius: 4px;border-radius: 4px;" href="{$item.nl_url_readmore}">{gt text="read more"} &raquo;</a>
            {/if}
            {if !$smarty.foreach.loop.last}
                <!--nothing-->
            {/if}
        {/foreach}
    {/if}
    </div>
    <div style="clear: both"></div>
{/if}
