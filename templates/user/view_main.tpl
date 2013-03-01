{include file='user/generic_header.tpl'}

{newsletter_selector_frequency assign='frequency_values' return_keys=true}
{newsletter_selector_frequency assign='frequency_output' return_keys=false}
{newsletter_selector_type assign='type_values' return_keys=true}
{newsletter_selector_type assign='type_output' return_keys=false}

{if !$user} {*Current user isn't a newsletter subscriber*}
    {if $modvars.Newsletter.allow_anon_registration || $coredata.logged_in} {*user is allowed to subscribe*}
    {pagesetvar name='title' __value='Subscribe to Newsletter'}
    <h3>{gt text='Subscribe'}</h3>

    <form class="z-form" action="{modurl modname='Newsletter' type='user' func='edit' ot='user'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Your Information'}</legend>

            {if !$modvars.Newsletter.auto_approve_registrations}
              <div class="z-informationmsg">{gt text='Subscriptions are subject to approval.'}</div>
            {/if}

            {if !$coredata.logged_in} {*username will be taken as name automatically*}
              <div class="z-formrow">
                <label for="user_name">{gt text='Your Name'}</label>
                <input name="user[name]" type="text" size="20" maxlength="64" />
              </div>
            {/if}

            <div class="z-formrow">
                <label for="user_email">{gt text='Your E-Mail Address'}</label>
                <input id="user_email" name="user[email]" type="text" size="30" maxlength="128" value="{$coredata.user.email|default:''}" />
            </div>

            {if $modvars.Newsletter.enable_multilingual}
              <div class="z-formrow">
                <label for="user_lang">{gt text='Language'}</label>
                {html_select_languages id="user_lang" name="user[lang]" installed=true selected=$user.lang}
              </div>
            {else}
              <input type="hidden" name="user[lang]" value="{lang}" />
            {/if}

            {if !$modvars.Newsletter.limit_type}
              <div class="z-formrow">
                <label for="user_type">{gt text='Format'}</label>
                <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=1}</select>
              </div>
            {/if}

            {if $modvars.Newsletter.allow_frequency_change}
              <div class="z-formrow">
                <label for="user_frequency">{gt text='Frequency'}</label>
                <select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=1}</select>
              </div>
            {else}
              <input type="hidden" name="user[frequency]" value="{$modvars.Newsletter.default_frequency}" />
            {/if}

            {if $modvars.Newsletter.require_tos|default:0}
              <div class="z-formrow">
                <label for="user_tos">{gt text='Terms of Service'}</label>
                <input type="checkbox" id="user_tos" name="user[tos]" value="1" />
                <a href="{modurl modname='Newsletter' type='user' func='main' ot='tos'}" title="{gt text='Terms of Service'}">
                    {gt text='Yes, I agree to the terms of service.'}
                </a>
              </div>
            {else}
              <input type="hidden" id="user_tos" name="user[tos]" value="1" />
            {/if}

            <div class="z-buttons z-formbuttons">
                {button src='button_ok.png' set='icons/small' id='submit' name='submit' value='submit' alt='' __title='Subscribe' __text='Subscribe'}
            </div>
        </fieldset>
    </form>
    {else} {*user is NOT allowed to subscribe*}
      <div class="z-warningmsg">
        {gt text="Sorry, but you must be a site member to subscribe to our newsletter."}
      </div>
    {/if}
{/if}

{include file='user/generic_footer.tpl'}
