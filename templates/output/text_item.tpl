{strip}
{if (isset($objectArray.$pluginName) && $objectArray.$pluginName)}
{"\n"}
{$pluginTitle}{"\n"}
==========================={"\n"}
{if $pluginName eq 'NewMembers'}
{"\n"}
{foreach from=$objectArray.$pluginName item="item" name="loop"}
{$item.uname|html_entity_decode}: {$item.user_regdate|dateformat}{"\n"}
{/foreach}
{else}
{foreach from=$objectArray.$pluginName item="item" name="loop"}
{"\n"}
{if $item.nl_title}{$item.nl_title|html_entity_decode}{"\n"}{/if}
{if $item.nl_content}{$item.nl_content|nlTreatContent:$pluginName:false}{"\n"}{/if}
{if $item.nl_url_readmore}{$item.nl_url_readmore}{"\n"}{else}{if $item.nl_url_title}{$item.nl_url_title}{"\n"}{/if}{/if}
{/foreach}
{/if}
{/if}
{/strip}