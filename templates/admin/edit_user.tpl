{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{modgetvar assign="enable_multilingual" module="Newsletter" name="enable_multilingual"}
{lang assign="defaultlang"}

{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{gt assign='pageTitle' text='Edit a User / Add a User'}
{pagesetvar name='title' value=$pageTitle}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='user.png' set='icons/small' alt=''}
    <h3>{$pageTitle}</h3>
</div>
<form class="z-form" action="{modurl modname='Newsletter' type='admin' func='save'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />
    <input type="hidden" name="ot" value="user" />
    {if ($user.id)}
    <input type="hidden" name="user[id]" value="{$user.id}" />
    {/if}
    <input type="hidden" name="user[uid]" value="{$user.uid}" />

    <fieldset>
        <legend>{gt text="Newsletter Configuration"}</legend>

        {if ($user.uid)}
        <div class="z-formrow">
            <label for="user_name">{gt text="Username"}</label>
            <span id="user_name">{$user.user_name}</span>
        </div>
        {else}
        <div class="z-formrow">
            <label for="user_name">{gt text="Username"}</label>
            <input id="user_name" name="user[name]" type="text" value="{$user.name}" size="20" maxlength="64" />
        </div>
        {/if}

        <div class="z-formrow">
            <label for="user_email">{gt text="Email"}</label>
            <input id="user_email" name="user[email]" type="text" value="{$user.email}" size="30" maxlength="128" />
        </div>

        {if ($enable_multilingual)}
        <div class="z-formrow">
            <label for="user_lang">{gt text="Language"}</label>
            {html_select_languages id="user_lang" name="user[lang]" installed=true selected=$user.lang|default:$defaultlang}
        </div>
        {/if}

        <div class="z-formrow">
            <label for="user_type">{gt text="Type"}</label>
            <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=$user.type}</select>
        </div>

        {modgetvar module="Newsletter" name="allow_frequency_change" assign="change_allowed"}
        {if ($change_allowed)}
        <div class="z-formrow">
            <label for="user_frequency">{gt text="Frequency"}</label>
            <select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=$user.frequency}</select>
        </div>
        {else}
        <div class="z-informationmsg z-formnote">{gt text="User-based frequency changes have been disabled by the site administrator"}</div>
        {/if}

        <div class="z-formrow">
            <label for="user_approved"><b>{gt text="Approved"}</b></label>
            <input id="user_approved" type="checkbox" name="user[approved]" value="1" {if ($user.approved)}checked="checked"{/if} />
        </div>

        <div class="z-formrow">
            <label for="user_active"><b>{gt text="Active"}</b></label>
            <input id="user_active" type="checkbox" name="user[active]" value="1" {if ($user.active)}checked="checked"{/if} />
        </div>

        {include file="forms/actions.tpl"}
    </fieldset>
</form>

{adminfooter}
