{strip}
{if (isset($objectArray.$plugin) && $objectArray.$plugin)}
{"\n"}
{nlPluginTitle plugin=$plugin assign='pluginTitle'}
{$pluginTitle|html_entity_decode}{"\n"}
==========================={"\n"}
{if $plugin eq 'Newsletter_NewsletterPlugin_NewMembers'}
{"\n"}
{foreach from=$objectArray.$plugin item="item" name="loop"}
{$item.uname|html_entity_decode}: {$item.user_regdate|dateformat}{"\n"}
{/foreach}
{else}
{foreach from=$objectArray.$plugin item="item" name="loop"}
{"\n"}
{if $item.nl_title}{$item.nl_title|html_entity_decode}{"\n"}{/if}
{if $item.nl_content}{$item.nl_content|nlTreatContent:$plugin:false}{"\n"}{/if}
{if $item.nl_url_readmore}{$item.nl_url_readmore|html_entity_decode}{"\n"}{else}{if $item.nl_url_title}{$item.nl_url_title|html_entity_decode}{"\n"}{/if}{/if}
{/foreach}
{/if}
{/if}
{/strip}
