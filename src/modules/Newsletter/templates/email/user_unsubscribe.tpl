<!-- this mailer is sent in html format -->
<div style="border:1px solid #ccc;">
    <h3>{gt text='Your newsletter subscription has been cancelled.'}</h3>
    <p>{gt text="Please feel free to re-subscribe in the future."}</p>
    <p>{gt text='Thank you,<br />%1$s Staff<br /><a href="%2$s">%2$s</a>' tag1=$modvar.ZConfig.sitename tag2=$site_url|safetext}</p>
</div>
