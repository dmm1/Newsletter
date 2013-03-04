{lang assign="currLang"}{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
{$objectArray.title|html_entity_decode}
===============================
{if (isset($user_name) && $user_name)}

{gt text="Hello"} {$user_name|html_entity_decode}
{/if}
{if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}

{$objectArray.NewsletterMessage|strip|replace:'<br />':"\n"|strip_tags|html_entity_decode}
{/if}
{strip}
{assign var='includeFile' value='output/text_item.tpl'}

{include file=$includeFile pluginName='News'       __pluginTitle='News'}

{include file=$includeFile pluginName='Content'    __pluginTitle='New Content Items'}

{include file=$includeFile pluginName='Pages'      __pluginTitle='Recently Added Documents'}

{include file=$includeFile pluginName='Clip'       __pluginTitle='Recently Added Publications'}

{include file=$includeFile pluginName='EZComments' __pluginTitle='Latest Comments'}

{include file=$includeFile pluginName='Dizkus'     __pluginTitle='Latest Forum Posts'}

{include file=$includeFile pluginName='Weblinks'   __pluginTitle='Latest web links'}

{include file=$includeFile pluginName='Downloads'  __pluginTitle='Latest downloads'}

{include file=$includeFile pluginName='AddressBook' __pluginTitle='Latest Contacts'}

{include file=$includeFile pluginName='AdvancedPolls' __pluginTitle='Latest Polls'}

{include file=$includeFile pluginName='PostCalendar' __pluginTitle='Latest Events'}

{include file=$includeFile pluginName='NewMembers' __pluginTitle='Welcome New Members'}
{/strip}

===========================
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}: {modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}

{gt text="Link to the Newsletter Archive"}: {modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}
