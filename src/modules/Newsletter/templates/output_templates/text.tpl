{usergetlang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}

{$objectArray.title}
===========================
{if (isset($user_name) && $user_name)}
{gt text="Hello"} {$user_name}
{/if}

{if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}
{gt text="Message from the Site Administrators"}
===========================
{$objectArray.NewsletterMessage|html_entity_decode}
{/if}
 
{if (isset($objectArray.AdminMessages) && $objectArray.AdminMessages)}
{gt text="Special Announcements"}
===========================
{foreach from=$objectArray.AdminMessages item="item"}
{$item.title|html_entity_decode}
{$item.content|html_entity_decode}
{/foreach}
{/if} 

{if (isset($objectArray.News) && $objectArray.News)}
{gt text="News"}
===========================
{foreach from=$objectArray.News item="item"}
{$item.title|html_entity_decode}
{$item.hometext|html_entity_decode}
{/foreach}
{/if} 

{if (isset($objectArray.ZWebstore) && $objectArray.ZWebstore)}
{gt text="Recently Added Products"}
===========================
{foreach from=$objectArray.ZWebstore item="item"}
{$item.name|html_entity_decode}
{$item.description|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.CrpVideo) && $objectArray.CrpVideo)}
{gt text="New Videos"}
===========================
{foreach from=$objectArray.CrpVideo item=item}
{$item.crpvideos_title|html_entity_decode}: {$item.crpvideos_content|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.crpCalendar) && $objectArray.crpCalendar)}    
{gt text="New Events"}
===========================
{foreach from=$objectArray.crpCalendar item="item"}
{$item.crpcalendar_title|html_entity_decode}
  {$item.crpcalendar_event_text|html_entity_decode} 
  {$item.crpcalendar_location|html_entity_decode}
  {$item.crpcalendar_start_date|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
{gt text="Welcome New Members"}
===========================
{foreach from=$objectArray.NewMembers item="item"}
{$item.user_name|html_entity_decode}: {$item.register_date}
{/foreach}
{/if}

{if (isset($objectArray.Pagesetter) && $objectArray.Pagesetter)}
{gt text="Recently Added Publications"}
===========================
{foreach from=$objectArray.Pagesetter item="item"}
{$item.title|html_entity_decode}
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
{$item.title|html_entity_decode}: {$item.content|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Reviews) && $objectArray.Reviews)}
{gt text="Recently Added Reviews"}<
===========================
{foreach from=$objectArray.Reviews item="item"}
{$item.title|html_entity_decode}
{$item.text|html_entity_decode}

{/foreach}
{/if}

{if (isset($objectArray.Mediashare) && $objectArray.Mediashare)}
{gt text="Latest Media Items"}
===========================
{foreach from=$objectArray.Mediashare item="item"}
{$item.album.title|html_entity_decode}: {$item.media.title|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Downloads) && $objectArray.Downloads)}
{gt text="Latest Downloads"}
===========================
{foreach from=$objectArray.Downloads item="item"}
{$item.title|html_entity_decode}: {$item.description|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Weblinks) && $objectArray.Weblinks)}        
{gt text="Recently Added Links"}
===========================
{foreach from=$objectArray.Weblinks item="item"}
{$item.title|html_entity_decode}: {$item.description|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Faq) && $objectArray.Faq)}        
{gt text="Recently Added Questions"}
===========================
{foreach from=$objectArray.Faq item="item"}
{$item.question|safehtml|nl_encodetext}
{$item.answer|safehtml|nl_encodetext}

{/foreach}
{/if}

{if (isset($objectArray.Quotes) && $objectArray.Quotes)}
{gt text="Recently Added Quotes"}
===========================
{foreach from=$objectArray.Quotes item="item"}
{$item.quote|html_entity_decode} -- {$item.author|html_entity_decode}
{/foreach}
{/if}
   
{if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}        
{gt text="Latest Forum Posts"}
===========================
{foreach from=$objectArray.Dizkus item="item"}
{$item.topic_title}
{/foreach}
{/if}

{if (isset($objectArray.Pagemaster) && $objectArray.Pagemaster)}        
{gt text="Recently Added Publications"}
===========================
{foreach from=$objectArray.Pagemaster.txt key='tid' item='publist'}
{foreach from=$publist item="item"}
{$item.core_title}
{/foreach}
{/foreach}
{/if}

{if (isset($objectArray.Clip) && $objectArray.Clip)}
{gt text="Recently Added Publications"}
===========================
{foreach from=$objectArray.Clip.txt key='tid' item='publist'}
{foreach from=$publist item="item"}
{$item.core_title}
{/foreach}
{/foreach}
{/if}

{modurl assign="nlurl" modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe"} {gt text="here!"}: {$nlurl}

{modurl assign="nlurlarchive" modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}
{gt text="Link to the Newsletter Archive"}: {$nlurl}
