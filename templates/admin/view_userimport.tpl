{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='db_update.png' set='icons/small' alt=''}
    <h3>{gt text='Import Zikula Users'}</h3>
</div>

{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}

{modgetvar assign="import_type" module="Newsletter" name="import_type" default="1"}
{modgetvar assign="import_frequency" module="Newsletter" name="import_frequency" default="1"}
{modgetvar assign="import_active_status" module="Newsletter" name="import_active_status" default="1"}
{modgetvar assign="import_approval_status" module="Newsletter" name="import_approval_status" default="1"}
{modgetvar assign="import_activelastdays" module="Newsletter" name="import_activelastdays" default="0"}

{modgetvar assign="admin_key" module="Newsletter" name="admin_key"}

{userloggedin assign="loggedin"}
{insert name='csrftoken' assign="authid"}

<form class="z-form" name="config" action="{modurl modname='Newsletter' type='admin' func='save'}" method="post">
    <input type="hidden" name="authid" value="{$authid}" />
    <input type="hidden" name="ot" value="ImportConfig" />
    <input type="hidden" name="otTarget" value="userimport" />

    <fieldset>
        <legend>1. {gt text="Zikula user import configuration"}</legend>
        <div class="z-formrow">
            <label>{gt text='Defaults for imported users'}</label>
        </div>
        <div class="z-formrow">
            <label for="import_type">{gt text='Type'}:</label>
            <select id="import_type" name="import[import_type]">{html_options values=$type_values output=$type_output selected=$import_type}</select>
        </div>
        <div class="z-formrow">
            <label for="import_frequency">{gt text='Frequency'}:</label>
            <select id="import_frequency" name="import[import_frequency]">{html_options values=$frequency_values output=$frequency_output selected=$import_frequency}</select>
        </div>
        <div class="z-formrow">
            <label for="import_active_status">{gt text='Active'}:</label>
            <select id="import_active_status" name="import[import_active_status]">
                <option value="1"{if $import_active_status eq 1} selected="selected"{/if}>{gt text="Active"}</option>
                <option value="0"{if $import_active_status eq 0} selected="selected"{/if}>{gt text="Inactive"}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="import_approval_status">{gt text='Users approved'}:</label>
            <select id="import_approval_status" name="import[import_approval_status]">
                <option value="1"{if $import_approval_status eq 1} selected="selected"{/if}>{gt text="Approved"}</option>
                <option value="0"{if $import_approval_status eq 0} selected="selected"{/if}>{gt text="Unapproved"}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label>{gt text='Filter'}</label>
        </div>
        <div class="z-formrow">
            <label for="import_activelastdays">{gt text='Import only active users in last n days'}:</label>
            <input id="import_activelastdays" name="import[import_activelastdays]" value="{$import_activelastdays}" />
            <div class="z-sub z-formnote">{gt text='Leave zero or empty for all users'}</div>
        </div>
    </fieldset>

    <div class="z-buttons z-formbuttons">
        <input type="submit" name="submit" value="{gt text='Update Configuration'}" class="z-bt-ok" />
    </div>

    <fieldset>
        <legend>2. {gt text='Import'}</legend>
        <div class="z-formrow z-buttons">
            <a class="z-bt-ok" href="{modurl modname='Newsletter' type='admin' func='save' ot='ImportUsers' otTarget='userimport' authKey=$admin_key authid=$authid}" title="{gt text='Import'}">
                {gt text='Perform the Import'}
            </a>
            <a class="z-bt-ok" href="{modurl modname='Newsletter' type='admin' func='save' ot='ImportUsers' otTarget='userimport' otTest='1' authKey=$admin_key authid=$authid}" title="{gt text='Just test'}">
                {gt text='Just test'}
            </a>
        </div>
    </fieldset>
</form>

{adminfooter}
