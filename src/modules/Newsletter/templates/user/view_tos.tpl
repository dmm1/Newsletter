{include file="user/header.tpl"} 

<div id="nl-content-container">
  <h2>{gt text="Terms of Service"}</h2> 
  <p>
    {configgetvar name="sitename"}
    {gt text="will not distribute, sell or disclose your personal information to any parties, public or private."}
  </p>
  <p>
    {gt text="Staff is not responsible for maintaining your subscription. You may opt in or out or even suspend your account at your own convenience."}
  </p>
  <p>
    {configgetvar name="sitename"} 
    {gt text="offers no warranties or guarantees for this service and accepts no responsibility or liability for any damages that may incur before, during or after subscription to this service."}
  </p>
  <p style="float:left;">
    <b><a href="javascript:history.back()">{gt text="Back"}</a></b>
  </p>
</div>

{include file="admin/footer.tpl"}
