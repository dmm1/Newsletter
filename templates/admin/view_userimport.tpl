{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='db_update.png' set='icons/small' alt=''}
    <h3>{gt text='Import / Export'}</h3>
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
        <legend>{gt text="Import registered Zikula users"}</legend>
        <h4>1. {gt text="Import configuration"}</h4>
        <h5 class="z-formnote">{gt text='Defaults for imported users'}</h5>
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
        <h5 class="z-formnote">{gt text='Filter'}</h5>
        <div class="z-formrow">
            <label for="import_activelastdays">{gt text='Import only active users in last n days'}:</label>
            <input id="import_activelastdays" name="import[import_activelastdays]" value="{$import_activelastdays}" />
            <div class="z-sub z-formnote">{gt text='Leave zero or empty for all users'}</div>
        </div>
        <div class="z-buttons z-formbuttons">
            <input type="submit" name="submit" value="{gt text='Update Configuration'}" class="z-bt-ok" />
        </div>

        <h4>2. {gt text='Import registered Zikula users'}</h4>
        <div class="z-formrow z-buttons z-formbuttons">
            <a class="z-bt-ok" href="{modurl modname='Newsletter' type='admin' func='save' ot='ImportUsers' otTarget='userimport' authKey=$admin_key authid=$authid}" title="{gt text='Import'}">
                {gt text='Perform the Import'}
            </a>
            <a class="z-bt-ok" href="{modurl modname='Newsletter' type='admin' func='save' ot='ImportUsers' otTarget='userimport' otTest='1' authKey=$admin_key authid=$authid}" title="{gt text='Just test'}">
                {gt text='Just test'}
            </a>
        </div>
    </fieldset>
</form>

<form class="z-form" action="{modurl modname='Newsletter' type='admin' func='view' ot='import' admin_key=$admin_key}" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>{gt text='Export subscribers'}</legend>
        <div class="z-formrow z-buttons">
            {php}
                $filenameCsv = CacheUtil::getLocalDir() . "/Newsletter/NewsletterUsers.csv";
                $filenameXml = CacheUtil::getLocalDir() . "/Newsletter/NewsletterUsers.xml";
                $this->assign('filenameCsv', $filenameCsv);
                $this->assign('filenameXml', $filenameXml);
            {/php}
            <a href="{modurl modname='Newsletter' type='admin' func='view' ot='export' admin_key=$admin_key format='xml' outputToFile=1}" title="{gt text='Export'}">{img modname='core' src='filesave.png' set='icons/extrasmall'}
                {gt text='Save as xml file at %s' tag1=$filenameXml}
            </a>
            <a href="{modurl modname='Newsletter' type='admin' func='view' ot='export' admin_key=$admin_key format='xml' outputToFile=0}" title="{gt text='Export'}">{img modname='core' src='down.png' set='icons/extrasmall'}
                {gt text='Download as xml file'}
            </a>
            <br />
            <br />
            <a href="{modurl modname='Newsletter' type='admin' func='view' ot='export' admin_key=$admin_key format='csv' outputToFile=1}" title="{gt text='Export'}">{img modname='core' src='filesave.png' set='icons/extrasmall'}
                {gt text='Save as csv file at %s' tag1=$filenameCsv}
            </a>
            <a href="{modurl modname='Newsletter' type='admin' func='view' ot='export' admin_key=$admin_key format='csv' outputToFile=0}" title="{gt text='Export'}">{img modname='core' src='down.png' set='icons/extrasmall'}
                {gt text='Download as csv file'}
            </a>
        </div>
    </fieldset>

    <fieldset>
        <legend>{gt text='Import subscribers'}</legend>
        <div class="z-formrow">
            <label for="import_format">{gt text='Format'}</label>
            <select id="import_format" name="format">
                <option value="xml">xml</option>
                <option value="csv">csv</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="import_delimiter">{gt text='Delimiter'}</label>
            <input id="import_delimiter" name="delimiter" type="text" maxlength="5" value=";">
            <em class="z-formnote z-sub">{gt text='This is only neccassary if you select csv as format.'}</em>
        </div>
        <div class="z-formrow">
            <label for="import_file">{gt text='File'}</label>
            <input id="import_file" name="file" type="file" class="z-form-upload">
        </div>
        <div class="z-buttons z-formbuttons">
            <input type="submit" name="submit" value="{gt text='Import subscribers'}" class="z-bt-ok" />
        </div>
    </fieldset>
</form>
{adminfooter}
