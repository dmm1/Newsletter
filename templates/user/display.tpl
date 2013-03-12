{assign var='lblTitle' value="What's new in the site"}
{pagesetvar name='title' value=$lblTitle}
{insert name='getstatusmsg'}
<h2>{$lblTitle}</h2>

{assign var='includeFile' value='output/items/'|cat:$modvars.Newsletter.template_html}
{nlActivePlugins assign='plugins'}
{foreach from=$plugins item='plugin'}
    {if $plugin != 'Newsletter_NewsletterPlugin_NewsletterMessage'}
        {include file=$includeFile plugin=$plugin}
    {/if}
{/foreach}
