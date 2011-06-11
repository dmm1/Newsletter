{include file="newsletter_user_std_header.tpl"}

{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{modgetvar assign="allow_anon_registration" module="Newsletter" name="allow_anon_registration"}
{modgetvar assign="allow_frequency_change" module="Newsletter" name="allow_frequency_change"}
{modgetvar assign="auto_approve_registrations" module="Newsletter" name="auto_approve_registrations"}
{modgetvar assign="enable_multilingual" module="Newsletter" name="enable_multilingual"}
{modgetvar assign="limit_type" module="Newsletter" name="limit_type"}
{modgetvar assign="user_active" module="Newsletter" name="user_active"}
{modgetvar assign="require_tos" module="Newsletter" name="require_tos" default="0"}
{configgetvar assign="defaultlang" name="language" default="eng"}
{userloggedin assign="loggedin"}

<h4>{gt text="Welcome to our Newsletter, here you can manage or delete your Subscription."}</h4>
  
{if (!$user)}

  <h5>{gt text="Subscribe"}</h5>
 
  <fieldset>
    <legend>{gt text="Information"}</legend>

      {if ($allow_anon_registration || $loggedin)}    
          <form class="z-adminform" action="{modurl modname="Newsletter" type="userform" func="edit" ot="user"}" method="post" enctype="application/x-www-form-urlencoded">
            <input type="hidden" id="authid" name="authid" value="{insert name="generateauthkey" module="Newsletter"}" />

            
              <div class="z-formrow">
                <label for="user_name">{gt text="Your Name"}</label>
                <input name="user[name]" type="text" size="20" maxlength="64" />
              </div>

              <div class="z-formrow">
                <label for="user_email">{gt text="Your E-Mail Address"}</label>
                <input id="user_email" name="user[email]" type="text" size="30" maxlength="128" />
              </div>
             

            {if ($enable_multilingual)}
              <div class="z-formrow">
                <label for="user_lang">{gt text="Language"}</label>
                {html_select_languages id="user_lang" name="user[lang]" installed=true selected=$user.lang|default:$defaultlang}
              </div>
            {/if}

            {if (!$limit_type)}
              <div class="z-formrow">
                <label for="user_type">{gt text="Type"}</label>
                <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=1}</select>
              </div>
            {/if}

            {if ($allow_frequency_change)}
              <div class="z-formrow">
                <label for="user_frequency">{gt text="Frequency"}</label>
                <select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=1}</select>
              </div>
            {/if}
			{if ($require_tos)}			
            <div class="z-formrow">
              <label for="user_tos">{gt text="Terms of Service"}</label>
              <input type="checkbox" id="user_tos" name="user[tos]" value="1" /> 
			  <a href="index.php?module=Newsletter&amp;func=main&amp;ot=tos" title="{gt text="Terms of Service"}">
			  {gt text="Yes, I agree to the terms of service."}
			  </a>
            </div>
			{else}
			<div style="display:none;">
			<input type="text" id="user_tos" name="user[tos]" value="1" />
			</div>
			{/if}
			
            {include file="newsletter_inc_form_actions.tpl"}
			
            {if (!$auto_approve_registrations)}
              <div class="z-formrow">
                <label style="color:red">{gt text="* subscriptions subject to approval."}</label>
              </div>
			  
			
            {/if}
			 
      {else}

          <div class="z-formrow">
            {gt text="Sorry, but you must be a site member to subscribe to our newsletter."}
          </div>

      {/if}
  

{elseif ($loggedin)}
  {include file="newsletter_user_inc_info.tpl"}
{/if}

{include file="newsletter_std_footer.tpl"}
