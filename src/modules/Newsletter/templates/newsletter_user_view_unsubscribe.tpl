{include file="newsletter_user_std_header.tpl"}

{userloggedin assign="loggedin"}

{if ($user || !$loggdin)}
  <h2>{gt text="Unsubscribe"}</h2>
  
  <fieldset>
    <legend>{gt text="Information"}</legend>
    <form class="z-adminform" action="{modurl modname="Newsletter" type="user" func="edit" ot="user_delete"}" method="post" enctype="application/x-www-form-urlencoded" onsubmit="if(this.user_email.value==''){this.user_email.focus(); return false;}">
      <input type="hidden" id="authid" name="authid" value="{insert name="generateauthkey" module="Newsletter"}" />

      <h4>{gt text="Here you can unsubscribe from our Newsletter"}</h4>
      <br />

      {if (!$loggedin)}
         <div class="z-formrow">
          <label for="user_email">{gt text="Your E-Mail Address"}</label>
          <span id="user_email"> <input name="user[email]" type="text" size="30" maxlength="128" /></span>
        </div>
      {/if}

       <div class="z-formrow">
        <input type="submit" name="submit" value="{gt text="Unsubscribe"}" onclick="return confirm('{gt text="Are you sure that you want to unsubscribe?"}');" />
      </div>
    </form>
 </fieldset>
{/if}

{include file="newsletter_std_footer.tpl"}
