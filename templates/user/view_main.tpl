{include file='user/generic_header.tpl'}

{newsletter_selector_frequency assign='frequency_values' return_keys=true}
{newsletter_selector_frequency assign='frequency_output' return_keys=false}
{newsletter_selector_type assign='type_values' return_keys=true}
{newsletter_selector_type assign='type_output' return_keys=false}

{if !$user}
    {if $modvars.Newsletter.allow_anon_registration or $coredata.logged_in}
    <h3>{gt text='Subscribe'}</h3>

    <form class="z-form" action="{modurl modname='Newsletter' type='user' func='edit' ot='user'}" method="post" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Your Information'}</legend>

            {if !$modvars.Newsletter.auto_approve_registrations}
            <div class="z-informationmsg">{gt text='Subscriptions subject to approval.'}</div>
            {/if}

            <div class="z-formrow">
                <label for="user_name">{gt text='Your Name'}</label>
                <input name="user[name]" type="text" size="20" maxlength="64" />
            </div>

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
    {else}
    <div class="z-warningmsg">
        {gt text="Sorry, but you must be a site member to subscribe to our newsletter."}
    </div>
    {/if}

{else}
    <h3>{gt text='Your Information'}</h3>

    <div class="z-form">
        {if $coredata.logged_in}
        <div class="z-formrow">
            <span class="z-label">{gt text='Username'}:</span>
            <span class="z-formnote"><strong>{usergetvar name='uname'}</strong></span>
        </div>
        {/if}

        <div class="z-formrow">
            <span class="z-label">{gt text='Email'}:</span>
            <span class="z-formnote"><strong>{$user.email}</strong></span>
        </div>

        <div class="z-formrow">
            <span class="z-label">{gt text='Format'}:</span>
            <span class="z-formnote"><strong>
                {switch expr=$user.type}
                    {case expr=1}{gt text='Text'}{/case}
                    {case expr=2}{gt text='HTML'}{/case}
                    {case expr=3}{gt text='Text with Link to Archive'}{/case}
                    {case}{gt text='Invalid'}{/case}
                {/switch}
            </strong></span>
        </div>

        <div class="z-formrow">
            <span class="z-label">{gt text='Frequency'}:</span>
            <span class="z-formnote"><strong>
                {switch expr=$user.frequency}
                    {case expr=0}{gt text='Weekly'}{/case}
                    {case expr=1}{gt text='Monthly'}{/case}
                    {case expr=2}{gt text='Every 2 Months'}{/case}
                    {case expr=3}{gt text='Every 3 Months'}{/case}
                    {case expr=6}{gt text='Every 6 Months'}{/case}
                    {case expr=9}{gt text='Every 9 Months'}{/case}
                    {case expr=12}{gt text='Yearly'}{/case}
                    {case}{gt text='Invalid'}{/case}
                {/switch}
            </strong></span>
        </div>

        {if $modvars.Newsletter.show_approval_status|default:false}
        <div class="z-formrow">
            <span class="z-label">{gt text='Approval Status'}:</span>
            {if $user.approved}
                <span class="z-formnote nl-green">{gt text='Approved'}</span>
            {else}
                <span class="z-formnote nl-red">{gt text='Not Approved'}</span>
            {/if}
        </div>
        {/if}

        <div class="z-formrow">
            <span class="z-label">{gt text='Subscription Status'}:</span>
            {if $user.active}
                <span class="z-formnote nl-green">{gt text='Your Subscription is currently active'}</span>
            {else}
                <span class="z-formnote nl-red">{gt text='Your Subscription is currently inactive'}</span>
            {/if}
        </div>

        <div class="z-formrow">
            <span class="z-label">{gt text='Last newsletter was sent to you on'}:</span>
            {if $user.last_send_date}
                <span class="z-formnote nl-green">{$user.last_send_date}</span>
            {else}
                <span class="z-formnote nl-red">{gt text='Never'}</span>
            {/if}
        </div>
    </div>
{/if}

{include file='user/generic_footer.tpl'}
