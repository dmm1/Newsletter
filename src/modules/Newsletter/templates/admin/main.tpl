{gt text='Newsletter' assign='templatetitle'}
{include file='admin/header.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Newsletter' src='admin-icon.png' alt='' height='86'}</div>

		<h3>{gt text="Welcome to Newsletter!"}</h3>
	
			<p>
				{gt text="Please report errors or requests features for future versions in the Tracker!"}
			<br /><br />
				<a href="https://github.com/dmm1/Newsletter/wiki" target="_blank"><b>{gt text="Visit our project-page"}</b></a>        
			</p>
	<br />
    <div class="z-warningmsg nl-round">
        <strong>{gt text='Attention'}:</strong><br />
        <a href="index.php?module=Mailer&type=admin">{gt text="Zikula Version 1.3 and up: To ensure html-mails are sent correctly, you have to look that in the Core-Mailer Module (Settings->Mailer) the butten -send mails in html-format- is checked!"}</a>
    </div>
</div>
