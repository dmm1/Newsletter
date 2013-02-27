{lang assign="currLang"}{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
{$objectArray.title}
===============================
{if (isset($user_name) && $user_name)}

{gt text="Hello"} {$user_name}
{/if}
{if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}

{$objectArray.NewsletterMessage|replace:'<br />':"\n"|html_entity_decode}
{/if}
{if (isset($objectArray.News) && $objectArray.News)}

{gt text="News"}
===========================
{foreach from=$objectArray.News item="item"}

{$item.title|html_entity_decode}
{$item.hometext|nlTreatContent:'News':false}
{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}
{/foreach}
{/if}
{if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}

{gt text="Welcome New Members"}
===========================
{foreach from=$objectArray.NewMembers item="item"}

{$item.user_name|html_entity_decode}: {$item.register_date}
{/foreach}
{/if}
{if (isset($objectArray.Content) && $objectArray.Content)}

{gt text="New Content Items"}
===========================
{foreach from=$objectArray.Content item="item"}

{$item.title|html_entity_decode}
{/foreach}
{/if}
{if (isset($objectArray.Pages) && $objectArray.Pages)}

{gt text="Recently Added Documents"}
===========================
{foreach from=$objectArray.Pages item="item"}

{$item.title|html_entity_decode}
{$item.content|nlTreatContent:'Pages':false}
{modurl modname="Pages" type="user" func="display" pageid=$item.pageid newlang=$nllang fqurl=true}
{/foreach}
{/if}
{if (isset($objectArray.EZComments) && $objectArray.EZComments)}

{gt text="Latest comments"}
===========================
{foreach from=$objectArray.EZComments item="item"}

{$item.comment|nlTreatContent:'EZComments':false}
{$item.url}
{/foreach}
{/if}
{if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}

{gt text="Latest Forum Posts"}
===========================
{foreach from=$objectArray.Dizkus item="item"}
{$item.topic_title}
{/foreach}
{/if}
{if (isset($objectArray.Clip) && $objectArray.Clip)}

{gt text="Recently Added Publications"}
===========================
{foreach from=$objectArray.Clip item="item" name="loop"}

{$item.title|html_entity_decode}
{$item.content|nlTreatContent:'Clip':false}
{modurl modname="Clip" type="user" func="viewpub" tid=$item.core_tid pid=$item.core_pid newlang=$nllang fqurl=true}
{/foreach}
{/if}
{if (isset($objectArray.Weblinks) && $objectArray.Weblinks)}

{gt text="Latest web links"}
===========================
{foreach from=$objectArray.Weblinks item="item" name="loop"}

{$item.title|html_entity_decode}
{$item.description|nlTreatContent:'Weblinks':false}
{modurl modname="Weblinks" type="user" func="viewlinkdetails" lid=$item.lid newlang=$nllang fqurl=true}
{/foreach}
{/if}
{if (isset($objectArray.Downloads) && $objectArray.Downloads)}

{gt text="Latest downloads"}
===========================
{foreach from=$objectArray.Downloads item="item" name="loop"}

{$item.title|html_entity_decode}
{$item.description|nlTreatContent:'Downloads':false}
{modurl modname="Downloads" type="user" func="viewlinkdetails" lid=$item.lid newlang=$nllang fqurl=true}
{/foreach}
{/if}

===========================
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}: {modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}

{gt text="Link to the Newsletter Archive"}: {modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}
