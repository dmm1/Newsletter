{include file="user/header.tpl"}


{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{newsletter_selector_active assign="active_values" return_keys=true all=true}
{newsletter_selector_active assign="active_output" return_keys=false all=true}

{modgetvar assign="enable_multilingual" module="Newsletter" name="enable_multilingual"}
{modgetvar assign="limit_type" module="Newsletter" name="limit_type"}

{configgetvar assign="defaultlang" name="language" default="eng"}
{userloggedin assign="loggedin"}
{secgenauthkey assign="authid" module="Newsletter"}
<h3>{gt text="Change your subscription settings"}</h3>

{include file="user/info.tpl"}

<form class="z-userform" action="{modurl modname="Newsletter" type="user" func="edit" ot="user_options"}" method="post" enctype="application/x-www-form-urlencoded">
  <input type="hidden" name="authid" value="{secgenauthkey module=Newsletter}" />         
  <input type="hidden" name="otTarget" value="options" />

  <fieldset>
    <legend>{gt text="Newsletter Configuration"}</legend>

    {if ($enable_multilingual)}
      <div class="z-formrow">
        <label for="user_lang">{gt text="Language"}</label>
        {html_select_languages id="user_lang" name="user[lang]" installed=true selected=$user.lang|default:$defaultlang}
      </div>
    {/if}

    {if (!$limit_type)}
	{nocache}
      <div class="z-formrow">
        <label for="user_type">{gt text="Type"}</label>
        <select id="user_type" name="user[type]">{html_options values=$type_values output=$type_output selected=$user.type}</select>
      </div>
	{/nocache}
    {/if}

    {modgetvar module="Newsletter" name="allow_frequency_change" assign="change_allowed"}
    {if ($change_allowed)}
	{nocache}
      <div class="z-formrow">
        <label for="user_frequency_1">{gt text="Frequency"}</label>
		<select id="user_frequency" name="user[frequency]">{html_options values=$frequency_values output=$frequency_output selected=$user.frequency}</select>
      </div>
	{/nocache}
    {else}
      <div class="z-formrow">
        <label for="user_frequency_non_allowed">{gt text="Frequency"}:</label>
        <span class="z-informationmsg" id="user_frequency_non_allowed">{gt text="User-based frequency changes have been disabled by the site administrator"}</span>
      
	  </div>
    {/if}
		
	{modgetvar module="Newsletter" name="allow_subscription_change" assign="change_sup_allowed"}
    {if ($change_sup_allowed)}
	{nocache}
    <div class="z-formrow">
      <label for="user_active"><b>{gt text="Active"}</b></label>       
	  <input type="checkbox" id="user_active" name="user[active]" value="1" {if ($user.active)}checked="checked"{/if} />
	  <a href="#" title="{gt text="Help"}" onclick="Effect.toggle('hint-1','BLIND'); return false;">(?)</a>
      <span id="help_subject" style="display:none">{gt text="Help"}</span>
      <div id="hint-1" class="z-informationmsg" style="display:none;">{gt text="Here you can activate or suspend yor subscription"}</div>
	</div>
	{/nocache}
	{else}     
    {/if}
	
    <div class="z-formrow">
      <label for="user_tos">{gt text="Terms of Service"}</label>
      <a id="user_tos" href="{modurl modname="Newsletter" type="user" func="main" ot="tos"}" title="{gt text="Terms of Service"}">{gt text="Terms of Service"}</a>
    </div>

    {include file="forms/actions.tpl"}

  </fieldset>
</form>

{include file="admin/footer.tpl"}
