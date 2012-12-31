{include file='user/generic_header.tpl'}

{if $user or $modvars.Newsletter.allow_anon_registration and !$coredata.logged_in}
<h3>{gt text="Unsubscribe"}</h3>

<form class="z-form" action="{modurl modname='Newsletter' type='user' func='edit' ot='user_delete'}" method="post" enctype="application/x-www-form-urlencoded" onsubmit="if (this.user_email.value == '') {ldelim} this.user_email.focus(); return false; {rdelim}">
    <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

    <fieldset>
        <legend>{gt text='Confirmation'}</legend>

        <div class="z-warningmsg">{gt text='Are you sure you want to unsubscribe from the newsletter?'}</div>

        {if !$coredata.logged_in}
        <div class="z-formrow">
            <label for="user_email">{gt text="Your E-Mail Address"}</label>
            <input id="user_email" name="user[email]" type="text" size="30" maxlength="128" value="{$user.email|default:''}" />
        </div>
        {/if}

        <div class="z-formbuttons z-buttons">
            <input type="submit" name="submit" value="{gt text='Unsubscribe'}" />
        </div>
    </fieldset>
</form>
{/if}

{include file='user/generic_footer.tpl'}
