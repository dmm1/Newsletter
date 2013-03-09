{lang assign="currLang"}{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
{$objectArray.title|html_entity_decode}
===============================
{if (isset($user_name) && $user_name)}

{gt text="Hello"} {$user_name|html_entity_decode}
{/if}
{if (isset($objectArray.Newsletter_NewsletterPlugin_NewsletterMessage) && $objectArray.Newsletter_NewsletterPlugin_NewsletterMessage)}

{$objectArray.Newsletter_NewsletterPlugin_NewsletterMessage|strip|replace:'<br />':"\n"|strip_tags|html_entity_decode}
{/if}
{strip}
{assign var='includeFile' value='output/items/text.tpl'}
{nlActivePlugins assign='plugins'}
{foreach from=$plugins item='plugin'}
    {if $plugin != 'Newsletter_NewsletterPlugin_NewsletterMessage'}
        {include file=$includeFile plugin=$plugin}
    {/if}
{/foreach}
{/strip}

===========================
{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}: {modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}

{gt text="Link to the Newsletter Archive"}: {modurl assign='nlUrl' modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}{$nlUrl|html_entity_decode}
