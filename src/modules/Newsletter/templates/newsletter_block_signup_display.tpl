<form action="{modurl modname="Newsletter" type="userform" func="edit" ot="user"}" method="post" enctype="application/x-www-form-urlencoded">
  <input type="hidden" name="authid" value="{insert name="generateauthkey" module="Newsletter"}" />
  <input type="hidden" name="nl_frequency" value="{$nl_frequency}" />
  <input type="hidden" name="nl_type" value="{$nl_type}" />
    {if not $loggedin}
      <label for="user_name">{gt text="Your Name"}:</label>
      <input id="user_name" name="user[name]" type="text" size="10" maxlength="64" alt="{gt text="Your Name"}" /><br />
      <label for="user_email">{gt text="Your E-Mail Address"}:</label>
      <input id="user_email" name="user[email]" type="text" size="10" maxlength="128" alt="{gt text="Your E-Mail Address"}" /><br />
    {/if}

	{modgetvar assign="require_tos" module="Newsletter" name="require_tos" default="0"}
	{if ($require_tos)}
      <label for="nl_tos">
	  <a href="index.php?module=Newsletter&amp;func=main&amp;ot=tos" title="Terms of Service">
	  {gt text="Yes, I agree to the terms of service."}
	  </a></label>
      <input type="checkbox" name="user[tos]" value="1" id="nl_tos" />
    {else}
       <input type="hidden" name="user[tos]" value="1" />
    {/if}
    <input type="submit" name="submit" value="{gt text="Subscribe"}" />
</form>
