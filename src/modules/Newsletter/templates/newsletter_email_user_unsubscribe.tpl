<!-- this mailer is sent in html format -->
<div style="border:1px solid #ccc;">
	<h3>{gt text="Your newsletter subscription has been cancelled."}</h3>
	<p>{gt text="Please feel free to re-subscribe in the future."}</p>
	<p>{gt text="Thank you,"}<br />
	{configgetvar name="sitename"} {gt text="Staff"}<br />
        <a href="{$site_url}">{$site_url}<br />
	</p>
</div>
