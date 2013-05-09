{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{pageaddvar name='stylesheet' value='javascript/jquery-ui/themes/base/jquery-ui.css'}
{pageaddvar name='javascript' value='jquery-ui'}

{pageaddvarblock}
<style>
  #sortable {
    list-style-type: none; margin: 0; padding: 0;
  }
  #sortable li {
    margin: 0 3px 3px 3px; padding-left: 1.5em; min-height: 4em;
  }
  #sortable li span {
    position: absolute; margin-left: -1.3em;
  }
</style>
<script>
  jQuery(function() {
    initializeplugins();

    jQuery( "#sortable" ).sortable({items: "li:not(.nl-nosort)", placeholder: "ui-state-highlight"});
    jQuery( "#sortable" ).on( "sortupdate", function( event, ui ) {
        var sortedIDs = jQuery( "#sortable" ).sortable( "toArray" );

        var counter = 100;
        for(var i = 0; i < sortedIDs.length; i++) {
            jQuery('#plugin' + sortedIDs[i].substr(3, 10) + '_nOrder').val(counter);
            counter += 100;
        }
    });
  });

function initializeplugins()
{
    var i = 1;
    var j = 1;

    while ($('plugin_'+i)) {
        // enable checkbox + container
        checkboxswitchdisplaystate('enable_'+i, 'plugin_'+i, true);
        Event.observe('enable_'+i, 'click', function() {
            checkboxswitchdisplaystate(this.id, this.id.replace('enable', 'plugin'), true);
        }, false);
        // plugin suboptions checkboxes
        j = 1;
        while ($('plugin_'+i+'_suboption_'+j)) {
            checkboxswitchdisplaystate('plugin_'+i+'_enable_'+j, 'plugin_'+i+'_suboption_'+j, true);
            Event.observe('plugin_'+i+'_enable_'+j, 'click', function() {
                checkboxswitchdisplaystate(this.id, this.id.replace('enable', 'suboption'), true);
            }, false);
            // next suboption
            j++;
        }
        // next plugin
        i++;
    }
}
</script>
{/pageaddvarblock}

{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='ark.png' set='icons/small' alt=''}
    <h3>{gt text='Manage Plugins'}</h3>
</div>
<form id="nwplugins" class="z-form" action="{modurl modname='Newsletter' type='admin' func='save'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />
    <input type="hidden" name="ot" value="plugin" />

    <fieldset>
        <legend>{gt text='General filter'}</legend>
        <span class="z-informationmsg z-formnote nl-round">
            {gt text="To be effective, particular plugins have to implement."}
        </span>
        <div class="z-formrow">
            {modgetvar assign='plugins_filtlastdays' module='Newsletter' name="plugins_filtlastdays" default=0}
            <label for="plugins_filtlastdays">{gt text="Only items posted in last number of days:"}</label>
            <input id="plugins_filtlastdays" name="plugins_filtlastdays" type="text" value="{$plugins_filtlastdays}" size="6" maxlength="6" />
            <em class="z-sub z-formnote">{gt text='Leave 0 for unlimited.'}</em>
        </div>
        <div class="z-formrow">
            {modgetvar assign='plugins_filtlastarchive' module='Newsletter' name="plugins_filtlastarchive" default=0}
            <label for="plugins_filtlastarchive">{gt text="Only items posted after last newsletter:"}</label>
            <input id="plugins_filtlastarchive" name="plugins_filtlastarchive" type="checkbox" value="1"{if $plugins_filtlastarchive} checked="checked"{/if} />
        </div>
    </fieldset>

<ul id="sortable">
    {nlPluginsWhereModuleIsAvailable assign='pluginsWhereModuleIsAvailable'}
    {assign var='i' value=1}
    {foreach from=$objectArray item='plugin'}
    {if in_array($plugin, $pluginsWhereModuleIsAvailable)}
        {assign var='modAvailable' value=true}
    {else}
        {assign var='modAvailable' value=false}
    {/if}
    <li id="li_{$i}" class="ui-state-default {if $plugin eq 'Newsletter_NewsletterPlugin_NewsletterMessage'}nl-nosort{/if}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
        <h5 {if !$modAvailable}style="text-decoration:line-through;" {/if}>
            <input type="checkbox" id="enable_{$i}" name="plugin[{$plugin}]" value="1" {if !$modAvailable}disabled="disabled" {/if} {if $plugin_settings.$plugin.nActive && $modAvailable}checked="checked"{/if} />
            {nlPluginDisplayName plugin=$plugin assign='displayName'}{$displayName|safehtml}
        </h5>
        {nlPluginDescription plugin=$plugin assign='description'}
        {if !$modAvailable}
            <div class="z-warningmsg">{gt text='Module required for this plugin is not installed.'}</div>
        {elseif !empty($description)}
            <div class="z-informationmsg nl-round">{$description}</div>
        {/if}
        <div id="plugin_{$i}">
            {if $plugin neq 'Newsletter_NewsletterPlugin_NewsletterMessage'}
            <div class="z-formrow z-nw-numitemsrow">
                <label for="plugin{$i}_nItems">{gt text='Number of items'}</label>
                <div>
                    <input id="plugin{$i}_nItems" name="plugin[{$plugin}_nItems]" type="text" value="{$plugin_settings.$plugin.nItems}" size="3" maxlength="2" />
                    &nbsp;{gt text='Treat'}:
                    <select id="plugin{$i}_nTreat" name="plugin[{$plugin}_nTreat]" size="1" >
                        <option value="0"{if $plugin_settings.$plugin.nTreat eq 0} selected="selected"{/if}>{gt text='As is'}</option>
                        <option value="1"{if $plugin_settings.$plugin.nTreat eq 1} selected="selected"{/if}>{gt text='nl2br (from text only)'}</option>
                        <option value="2"{if $plugin_settings.$plugin.nTreat eq 2} selected="selected"{/if}>{gt text='strip_tags (from html)'}</option>
                        <option value="3"{if $plugin_settings.$plugin.nTreat eq 3} selected="selected"{/if}>{gt text='strip_tags but img,a'}</option>
                    </select>
                    &nbsp;{gt text='Truncate'}:
                    <input id="plugin{$i}_nTruncate" name="plugin[{$plugin}_nTruncate]" type="text" value="{if $plugin_settings.$plugin.nTruncate == ''}400{else}{$plugin_settings.$plugin.nTruncate}{/if}" size="8" />
                    &nbsp;{gt text='Order'}:
                    <input id="plugin{$i}_nOrder" name="plugin[{$plugin}_nOrder]" type="text" value="{$plugin_settings.$plugin.nOrder}" size="8" />
                </div>
            </div>
            {/if}
            {if $plugin_parameters.$plugin.number ne 0}
                {nlPluginIncludeConfig plugin=$plugin}
            {/if}
        </div>
    </li>
    {assign var='i' value=$i+1}
    {/foreach}
</ul>
    {include file='forms/actions.tpl'}
</form>
{adminfooter}
