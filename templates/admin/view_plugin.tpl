{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='ark.png' set='icons/small' alt=''}
    <h3>{gt text='Manage Plugins'}</h3>
</div>

<script type="text/javascript">

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

Event.observe(window, 'load', function() {
    if ($('nwplugins')) {
        initializeplugins();
    }
}, false);
</script>

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

    {assign var='i' value=1}
    {foreach from=$objectArray item='plugin'}
    {modgetvar assign="pluginActive" module="Newsletter" name="plugin_`$plugin`" default=0}
    <fieldset>
        <legend>
            <input type="checkbox" id="enable_{$i}" name="plugin[{$plugin}]" value="1" {if $pluginActive}checked="checked"{/if} />
            {newsletter_get_plugin_name pluginName=$plugin assign='name'}
            {$name|safehtml}
        </legend>
        <div id="plugin_{$i}">
            {if $plugin neq 'Newsletter_NewsletterPlugin_NewsletterMessage'}
            <div class="z-formrow z-nw-numitemsrow">
                <label for="plugin{$i}_numitems">{gt text='Number of items'}</label>
                <div>
                    {modgetvar assign='pluginItems' module='Newsletter' name="plugin_`$plugin`_nItems" default=3}
                    <input id="plugin{$i}_numitems" name="plugin[{$plugin}_nItems]" type="text" value="{$pluginItems}" size="3" maxlength="2" />
                    {modgetvar assign='pluginSettings' module='Newsletter' name="plugin_`$plugin`_Settings" default=''}
                    {assign var='arrSettings' value=";"|explode:$pluginSettings}
                    &nbsp;Treat:
                    <select id="plugin{$i}_Settings0" name="plugin[{$plugin}_Settings0]" size="1" >
                        <option value="0"{if $arrSettings.0 eq '0'} selected="selected"{/if}>{gt text='As is'}</option>
                        <option value="1"{if $arrSettings.0 eq '1'} selected="selected"{/if}>{gt text='nl2br (from text only)'}</option>
                        <option value="2"{if $arrSettings.0 eq '2'} selected="selected"{/if}>{gt text='strip_tags (from html)'}</option>
                        <option value="3"{if $arrSettings.0 eq '3'} selected="selected"{/if}>{gt text='strip_tags but img,a'}</option>
                    </select>
                    &nbsp;Truncate:
                    <input id="plugin{$i}_Settings1" name="plugin[{$plugin}_Settings1]" type="text" value="{if $arrSettings.1 == ''}400{else}{$arrSettings.1}{/if}" size="10" />
                </div>
            </div>
            {/if}
            {if $plugin_parameters.$plugin.number ne 0}
                {newsletter_get_plugin_name pluginName=$plugin assign='name'}
                {include file="plugin_config/`$name`.tpl"}
            {/if}
        </div>
    </fieldset>
    {assign var='i' value=$i+1}
    {/foreach}

    {include file='forms/actions.tpl'}
</form>

{adminfooter}
