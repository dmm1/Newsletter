{$title|safetext}

{gt text="Link to the Newsletter Archive"}:
{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}

{gt text="Thank you for suscribing to our newsletter!"}
{$site_name|safetext}

--------------------------------------
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}
{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}