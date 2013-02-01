{include file='user/generic_header.tpl'}

{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{newsletter_selector_active assign="active_values" return_keys=true all=true}
{newsletter_selector_active assign="active_output" return_keys=false all=true}

{defaultlang assign="defaultlang"}

<h3>{gt text='Change your subscription settings'}</h3>

<form class="z-form" action="{modurl modname='Newsletter' type='user' func='edit' ot='user_options'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />
    <input type="hidden" name="otTarget" value="options" />

    <fieldset>
        <legend>{gt text='Newsletter Configuration'}</legend>

        {if $modvars.Newsletter.enable_multilingual}
        <div class="z-formrow">
            <label for="user_lang">{gt text='Language'}</label>
            {html_select_languages id='user_lang' name='user[lang]' installed=true selected=$user.lang|default:$defaultlang}
        </div>
        {/if}

        {if !$modvars.Newsletter.limit_type}
        {nocache}
        <div class="z-formrow">
            <label for="user_type">{gt text='Type'}</label>
            <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=$user.type}</select>
        </div>
        {/nocache}
        {/if}

        {if $modvars.Newsletter.allow_frequency_change}
        {nocache}
        <div class="z-formrow">
            <label for="user_frequency">{gt text='Frequency'}</label>
            <select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=$user.frequency}</select>
        </div>
        {/nocache}
        {/if}

        {if $modvars.Newsletter.allow_subscription_change}
        {nocache}
        <div class="z-formrow">
            <label for="user_active">{gt text='Active'}</label>
            <input type="checkbox" id="user_active" name="user[active]" value="1" {if ($user.active)}checked="checked"{/if} />
            <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-1','BLIND'); return false;">(?)</a>
            <div id="hint-1" class="z-informationmsg nl-hint" style="display: none;">{gt text="Here you can activate or suspend yor subscription"}</div>
        </div>
        {/nocache}
        {/if}

        <div class="z-formrow">
            <label for="user_tos">{gt text='View our'}</label>
            <span><a id="user_tos" href="{modurl modname='Newsletter' type='user' func='main' ot='tos'}" title="{gt text="Terms of Service"}">{gt text='Terms of Service'}</a></span>
        </div>

        {include file='forms/actions.tpl'}
  </fieldset>
</form>

{include file='user/generic_footer.tpl'}
