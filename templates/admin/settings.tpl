{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{pageaddvarblock}
<script type="text/javascript">
    function checkHookInfoMessageCheckbox(element)
    {
        if(element.value == 'infmessage' || element.value == 'nomessage') {
            document.getElementById('hookuserreg_inform').checked = false;
            document.getElementById('hookuserreg_inform').disabled = true;
        }
        else
            document.getElementById('hookuserreg_inform').disabled = false;
    }
</script>
{/pageaddvarblock}

{adminheader}

{multilingual assign="multilingual"}

<div class="z-admin-content-pagetitle">
    {icon type='config' size='small'}
    <h3>{gt text='Settings'}</h3>
</div>

{form cssClass='z-form'}
    {formvalidationsummary}
    <fieldset>
        <legend>{gt text='General settings'}</legend>
        <div class="z-formrow">
            {formlabel for="itemsperpage" __text='Items per page' mandatorysym=true}
            {formintinput id="itemsperpage" text=$preferences.itemsperpage mandatory=true minValue=1 maxValue=100}
        </div>
        <div class="z-formrow">
            {formlabel for="send_from_address" __text='Send newsletters from' mandatorysym=true}
            {formemailinput id="send_from_address" text=$preferences.send_from_address maxLength=128 mandatory=true}
            <em class="z-sub z-formnote">{gt text='This is the delivering address of your newsletter e.g. newsletter@yourdomain.com.'}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="newsletter_subject" __text='Subject'}
            {formtextinput id="newsletter_subject" text=$preferences.newsletter_subject maxLength=128 mandatory=true}
            <em class="z-sub z-formnote">{gt text='This is the subject of the generated Newsletter.'}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="disable_auto" __text='Disable automatic sending'}
            {formcheckbox id="disable_auto" checked=$preferences.disable_auto}
            <em class="z-sub z-formnote">{gt text='Newsletters now have to be sent out manually.'}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="template_html" __text='Html newsletter template' mandatorysym=true}
            {formdropdownlist id="template_html" selectedValue=$preferences.template_html items=$templateHtmlSelector}
        </div>
        <div class="z-formrow">
            {formlabel for="limit_type" __text='Allow subscription of only this type' mandatorysym=true}
            {formdropdownlist id="limit_type" selectedValue=$preferences.limit_type items=$limitTypeSelector}
        </div>
        <div class="z-formrow">
            {formlabel for="default_type" __text='Default newsletter type' mandatorysym=true}
            {formdropdownlist id="default_type" selectedValue=$preferences.default_type items=$defaultTypeSelector}
        </div>
        <div class="z-formrow">
            {formlabel for="default_frequency" __text='Default frequency' mandatorysym=true}
            {formdropdownlist id="default_frequency" selectedValue=$preferences.default_frequency items=$defaultFrequencySelector}
        </div>
        <div class="z-formrow">
            {formlabel for="send_day" __text='Send day' mandatorysym=true}
            {formdropdownlist id="send_day" selectedValue=$preferences.send_day items=$sendDaySelector}
        </div>
        {if ($multilingual)}
            <div class="z-formrow">
                {formlabel for="enable_multilingual" __text='Enable multilingual features'}
                {formcheckbox id="enable_multilingual" checked=$preferences.enable_multilingual}
                <em class="z-sub z-formnote">{gt text='This enables sending of multilingual newsletters.'}</em>
            </div>
        {/if}
        <div class="z-formrow">
            {formlabel for="notify_admin" __text='Receive subscription notices'}
            {formcheckbox id="notify_admin" checked=$preferences.notify_admin}
            <em class="z-sub z-formnote">{gt text="If you enable that, you'll get an email to your specified send-from-adress, if there is a new Newsletter subscription."}</em>
        </div>
    </fieldset>


    <fieldset>
        <legend>{gt text='Contact Information'}</legend>
        <div class="z-informationmsg z-formnote nl-round">
            {gt text="This is shown at the bottom of each newsletter, if respective field is not empty."}
        </div>
        <div class="z-formrow">
            {formlabel for="contact_phone" __text='Phone'}
            {formtextinput id="contact_phone" text=$preferences.contact_phone maxLength=250}
        </div>
        <div class="z-formrow">
            {formlabel for="contact_email" __text='Email'}
            {formtextinput id="contact_email" text=$preferences.contact_email maxLength=250}
        </div>
        <div class="z-formrow">
            {formlabel for="contact_facebook" __text='Facebook'}
            {formtextinput id="contact_facebook" text=$preferences.contact_facebook maxLength=250}
            <em class="z-formnote z-sub">Example: https://www.facebook.com/zikula</em>
        </div>
        <div class="z-formrow">
            {formlabel for="contact_twitter" __text='Twitter'}
            {formtextinput id="contact_twitter" text=$preferences.contact_twitter maxLength=250}
            <em class="z-formnote z-sub">Example: https://twitter.com/TheZikulan</em>
        </div>
        <div class="z-formrow">
            {formlabel for="contact_google" __text='Google+'}
            {formtextinput id="contact_google" text=$preferences.contact_google maxLength=250}
        </div>
    </fieldset>


    <fieldset>
        <legend>{gt text='Subscribing'}</legend>
        <div class="z-formrow">
            {formlabel for="allow_anon_registration" __text='Allow anonymous registrations'}
            {formcheckbox id="allow_anon_registration" checked=$preferences.allow_anon_registration}
        </div>
        <div class="z-formrow">
            {formlabel for="show_approval_status" __text='Show the approvalstatus to the user'}
            {formcheckbox id="show_approval_status" checked=$preferences.show_approval_status}
        </div>
        <div class="z-formrow">
            {formlabel for="require_tos" __text='Require terms of service'}
            {formcheckbox id="require_tos" checked=$preferences.require_tos}
            <em class="z-sub z-formnote"><a href="{modurl modname='Newsletter' type='user' func='main' ot='tos'}" alt="{gt text='Terms of service'}">{gt text='Terms of service'}</a></em>
        </div>
        <div class="z-formrow">
            {formlabel for="auto_approve_registrations" __text='Auto-approve registrations'}
            {formcheckbox id="auto_approve_registrations" checked=$preferences.auto_approve_registrations}
        </div>
        <div class="z-formrow">
            {formlabel for="allow_frequency_change" __text='Allow frequency changes'}
            {formcheckbox id="allow_frequency_change" checked=$preferences.allow_frequency_change}
        </div>
        <div class="z-formrow">
            {formlabel for="allow_subscription_change" __text='Allow subscription changes'}
            {formcheckbox id="allow_subscription_change" checked=$preferences.allow_subscription_change}
        </div>
    </fieldset>


    <fieldset>
        <legend>{gt text='Subscribing in main user registration workflow'}</legend>
        <div class="z-informationmsg z-formnote nl-round">
            {gt text="To enable newsletter subscribing in main user registration procedure, Newsletter provider hook has to be attached to user registration procedure (Module menu, Hooks, Provision, checkbox next to Users Registration management)."}
        </div>
        <div class="z-formrow">
            {formlabel for="hookuserreg_display" __text='Display in user registration form'}
            {formdropdownlist id="hookuserreg_display" selectedValue=$preferences.hookuserreg_display items=$hookUserRegistrationDisplaySelector onchange="checkHookInfoMessageCheckbox(this);"}
        </div>
        {assign var='isChecked' value=$preferences.hookuserreg_inform}
        {assign var='isDisabled' value=false}
        {if $preferences.hookuserreg_display == 'infmessage' || $preferences.hookuserreg_display == 'nomessage'}
            {assign var='isChecked' value=false}
            {assign var='isDisabled' value=true}
        {/if}
        <div class="z-formrow">
            {formlabel for="hookuserreg_inform" __text='Info message to user if subscribed'}
            {formcheckbox id="hookuserreg_inform" checked=$isChecked readOnly=$isDisabled}
            <em class="z-sub z-formnote">{gt text='Log short information about subscription.'}</em>
        </div>
    </fieldset>


    <fieldset>
        <legend>{gt text='Sending'}</legend>
        <div class="z-formrow">
            {formlabel for="preferences[personalize_email]" __text='Personalize email'}
            {formcheckbox id="personalize_email" checked=$preferences.personalize_email}
            <em class="z-sub z-formnote">{gt text='Disable to increase performance.'}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="send_per_request" __text='Send per request' mandatorysym=true}
            {formintinput id="send_per_request" text=$preferences.send_per_request minValue=0 maxValue=1000 mandatory=true}
            <em class="z-sub z-formnote">{gt text='Number of emails (0 = no restriction).'}</em>
        </div>
        <div class="z-formrow">
            {formlabel for="max_send_per_hour" __text='Maximum newsletters to send per hour' mandatorysym=true}
            {formintinput id="max_send_per_hour" text=$preferences.max_send_per_hour minValue=0 maxValue=1000 mandatory=true}
                <em class="z-sub z-formnote">{gt text='Number of emails (0 = no restriction). Use this feature to avoid sending restrictions of your hoster!'} </em>
        </div>
        <div class="z-formrow">
            {formlabel for="admin_key" __text='Admin key'}
            {formtextinput id="admin_key" text=$preferences.admin_key maxLength=128}
            <em class="z-sub z-formnote">{gt text='Used to authenticate cron/batchprocessing. Leave empty to generate a key.'}</em>
        </div>
    </fieldset>


    <fieldset>
        <legend>{gt text='Archive settings'}</legend>
        <div class="z-formrow">
            {formlabel for="archive_expire" __text='Expire time of archived newsletters' mandatorysym=true}
            {formdropdownlist id="archive_expire" selectedValue=$preferences.archive_expire items=$archiveExpireSelector}
        </div>
        <div class="z-formrow">
            {formlabel for="archive_controlid" __text="Control newsletter ID"}
            {formcheckbox id="archive_controlid" checked=$preferences.archive_controlid}
        </div>
        <div class="z-formrow">
            {formlabel for="show_archive" __text="Show archive"}
            {formdropdownlist id="show_archive" selectedValue=$preferences.show_archive items=$showArchiveSelector}
        </div>
        <div class="z-formnote z-informationmsg">{gt text="The following settings define which columns are shown in the archive's table."}</div>
        <div class="z-formrow">
            {formlabel for="show_id" __text="Show newsletter ID"}
            {formcheckbox id="show_id" checked=$preferences.show_id}
        </div>
        <div class="z-formrow">
            {formlabel for="show_date" __text="Show date"}
            {formcheckbox id="show_date" checked=$preferences.show_date}
        </div>
        <div class="z-formrow">
            {formlabel for="show_lang" __text="Show language"}
            {formcheckbox id="show_lang" checked=$preferences.show_lang}
        </div>
        <div class="z-formrow">
            {formlabel for="show_plugins" __text="Show plugins"}
            {formcheckbox id="show_plugins" checked=$preferences.show_plugins}
        </div>
        <div class="z-formrow">
            {formlabel for="show_objects" __text="Show objects"}
            {formcheckbox id="show_objects" checked=$preferences.show_objects}
        </div>
        <div class="z-formrow">
            {formlabel for="show_size" __text="Show size"}
            {formcheckbox id="show_size" checked=$preferences.show_size}
        </div>
    </fieldset>


    <div class="z-buttons z-formbuttons">
        {formbutton commandName='update' __text='Update' class='z-bt-ok'}
        {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
{/form}

{adminfooter}
