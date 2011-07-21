<!-- this mailer is sent in html format -->
<div style="border: 1px solid #ccc; padding: 10px;">
    <p>
        {gt text="Hello %s" tag1=$user_name|safetext},<br />
        {gt text="Thank you for your Newsletter Subscription"}
    </p>
    <p>
        {gt text="Your %s Staff" tag1=$modvars.ZConfig.sitename}<br />
    </p>
    <hr />
    <p>
        {gt text='If you didn`t subscribe yourself, please visit <a href="%1$s">%1$s</a> to delete this Subscription' tag1=$site_url|safetext}
    </p>
</div>
