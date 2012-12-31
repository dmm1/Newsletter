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

    {assign var='i' value=1}
    {foreach from=$objectArray item='plugin'}
    {modgetvar assign="pluginActive" module="Newsletter" name="plugin_`$plugin`" default=0}
    <fieldset>
        <legend>
            {$plugin|safehtml}
            <input type="checkbox" id="enable_{$i}" name="plugin[{$plugin}]" value="1" {if $pluginActive}checked="checked"{/if} />
        </legend>
        <div id="plugin_{$i}">
            {if $plugin neq 'NewsletterMessage'}
            <div class="z-formrow z-nw-numitemsrow">
                <label for="plugin{$i}_numitems">{gt text='Number of items'}</label>
                <div>
                    {modgetvar assign='pluginItems' module='Newsletter' name="plugin_`$plugin`_nItems" default=3}
                    <input id="plugin{$i}_numitems" name="plugin[{$plugin}_nItems]" type="text" value="{$pluginItems}" size="3" maxlength="2" />
                </div>
            </div>
            {/if}
            {if $plugin_parameters.$plugin.number ne 0}
                {include file="plugin_configs/`$plugin`_config.tpl"}
            {/if}
        </div>
    </fieldset>
    {assign var='i' value=$i+1}
    {/foreach}

    {include file='forms/actions.tpl'}
</form>

{adminfooter}
