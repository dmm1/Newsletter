{gt text='Import Zikula Users' assign='templatetitle'}
{include file='admin/header.tpl'}

{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}

{modgetvar assign="import_type" module="Newsletter" name="import_type" default="1"}
{modgetvar assign="import_frequency" module="Newsletter" name="import_frequency" default="1"}
{modgetvar assign="import_active_status" module="Newsletter" name="import_active_status" default="1"}
{modgetvar assign="import_approval_status" module="Newsletter" name="import_approval_status" default="1"}

{modgetvar assign="admin_key" module="Newsletter" name="admin_key"}

{userloggedin assign="loggedin"}
{secgenauthkey assign="authid" module="Newsletter"}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Newsletter' src='admin.png' alt=''}</div>

    <h2>{$templatetitle}</h2>

    <form class="z-form" name="config" action="{modurl modname='Newsletter' type='admin' func='edit'}" method="post">
        <input type="hidden" name="authid" value="{$authid}" />
        <input type="hidden" name="ot" value="import_config" />
        <input type="hidden" name="otTarget" value="userimport" />

        <fieldset>
            <legend>1. {gt text="Zikula user import configuration"}</legend>
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
        </fieldset>

        <div class="z-formbuttons">
            <input type="submit" name="submit" value="{gt text='Update Configuration'}" />
        </div>

        <fieldset>
            <legend>2. {gt text='Import'}</legend>
            <div class="z-formrow">
                <a href="{modurl modname='Newsletter' type='admin' func='edit' ot='import_users' otTarget='userimport' authKey=$admin_key authid=$authid}" title="{gt text='Import'}">
                    <img src="images/icons/small/button_ok.png" alt="" />
                </a>
            </div>
        </fieldset>
    </form>

</div>
