{$title|html_entity_decode}

{gt text="Link to the Newsletter Archive"}:
{modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}

{gt text="Thank you for suscribing to our newsletter!"}
{$site_name|html_entity_decode}

--------------------------------------
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}
{modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}