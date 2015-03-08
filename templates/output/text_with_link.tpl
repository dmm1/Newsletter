{lang assign="currLang"}{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
{$objectArray.title|html_entity_decode}

{gt text="Link to the Newsletter Archive"}:
{modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="archive" lang=$nllang fqurl=true}{$nlUrl|html_entity_decode}

{gt text="Thank you for suscribing to our newsletter!"}
{$modvars.ZConfig.sitename|html_entity_decode}

--------------------------------------
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}
{modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="unsubscribe" lang=$nllang fqurl=true}{$nlUrl|html_entity_decode}