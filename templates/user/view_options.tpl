{include file='user/generic_header.tpl'}
{pagesetvar name='title' __value='Subscription settings'}

{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{newsletter_selector_active assign="active_values" return_keys=true all=true}
{newsletter_selector_active assign="active_output" return_keys=false all=true}
{defaultlang assign="defaultlang"}

<h3>{gt text='Subscription settings'}</h3>

<form class="z-form" action="{modurl modname='Newsletter' type='user' func='edit' ot='userOptions'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />
    <input type="hidden" name="otTarget" value="options" />

    <fieldset>
        <legend>{gt text='Newsletter Configuration'}</legend>
        {if $coredata.logged_in}
        <div class="z-formrow">
          <span class="z-label">{gt text='Username'}</span>
          <span class="z-formnote"><strong>{usergetvar name='uname'}</strong></span>
        </div>
        {/if}

        <div class="z-formrow">
            <label for="user_email">{gt text='Email'}</label>
            <input type="text" maxlength="30" value="{$user.email}" id="user_email" name="user[email]" />
        </div>

        {if !$modvars.Newsletter.limit_type}
          {nocache}
          <div class="z-formrow">
            <label for="user_type">{gt text='Type'}</label>
            <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=$user.type}</select>
          </div>
          {/nocache}
        {else}
          <div class="z-formrow">
            <span class="z-label">{gt text='Format'}</span>
            <span class="z-formnote"><strong>
                {switch expr=$user.type}
                    {case expr=1}{gt text='Text'}{/case}
                    {case expr=2}{gt text='HTML'}{/case}
                    {case expr=3}{gt text='Text with Link to Archive'}{/case}
                    {case}{gt text='Invalid'}{/case}
                {/switch}
            </strong></span>
          </div>
        {/if}

        {if $modvars.Newsletter.enable_multilingual}
          <div class="z-formrow">
            <label for="user_lang">{gt text='Language'}</label>
            {html_select_languages id='user_lang' name='user[lang]' installed=true selected=$user.lang|default:$defaultlang}
          </div>
        {/if}

        {if $modvars.Newsletter.allow_frequency_change}
          {nocache}
          <div class="z-formrow">
            <label for="user_frequency">{gt text='Frequency'}</label>
            <select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=$user.frequency}</select>
          </div>
          {/nocache}
        {else}
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
        {/if}

        {if $modvars.Newsletter.show_approval_status|default:false}
          <div class="z-formrow">
            <span class="z-label">{gt text='Approval Status'}</span>
            {if $user.approved}
              <span class="z-formnote nl-green">{gt text='Approved'}</span>
            {else}
              <span class="z-formnote nl-red">{gt text='Not Approved'}</span>
            {/if}
          </div>
        {/if}


        {if $modvars.Newsletter.allow_subscription_change}
          {nocache}
          <div class="z-formrow">
            <label for="user_active">{gt text='Subscription active'}</label>
            <input type="checkbox" id="user_active" name="user[active]" value="1" {if ($user.active)}checked="checked"{/if} />
            {if $user.active}
              <em class="z-formnote z-sub nl-green">{gt text='Your Subscription is currently active'}</em>
            {else}
              <em class="z-formnote z-sub nl-red">{gt text='Your Subscription is currently inactive'}</em>
            {/if}
          </div>
          {/nocache}
        {/if}

        <div class="z-formrow">
            <span class="z-label">{gt text='Last newsletter was sent to you on'}</span>
            {if $user.last_send_date}
              <span class="z-formnote nl-green">{$user.last_send_date}</span>
            {else}
              <span class="z-formnote nl-red">{gt text='Never'}</span>
            {/if}
        </div>

        <div class="z-formrow">
            <label for="user_tos">{gt text='View our'}</label>
            <span><a id="user_tos" href="{modurl modname='Newsletter' type='user' func='main' ot='tos'}" title="{gt text="Terms of Service"}">{gt text='Terms of Service'}</a></span>
        </div>

        {include file='forms/actions.tpl'}
  </fieldset>
</form>

{include file='user/generic_footer.tpl'}
