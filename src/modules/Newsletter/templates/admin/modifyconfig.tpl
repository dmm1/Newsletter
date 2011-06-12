
{include file='admin/header.tpl'}

{newsletter_selector_archive_expire assign="archive_expire_values" return_keys=true}
{newsletter_selector_archive_expire assign="archive_expire_output" return_keys=false}
{newsletter_selector_frequency assign="frequency_values" return_keys=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false}
{newsletter_selector_send_days assign="send_days_values" return_keys=true}
{newsletter_selector_send_days assign="send_days_output" return_keys=false}
{newsletter_selector_type assign="type_values" return_keys=true}
{newsletter_selector_type assign="type_output" return_keys=false}
{newsletter_selector_type assign="type_values2" all=true return_keys=true}
{newsletter_selector_type assign="type_output2" all=true return_keys=false}
{configgetvar assign="multilingual" name="multilingual" default=0}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.png' set='icons/small' __alt='Settings' }</div>

    <h3>{gt text='Settings'}</h3>

    <div class="z-informationmsg nl-round">
        {gt text='It took %1$s seconds to send the last batch of %2$s mails.' tag1=$last_execution_time tag2=$last_execution_count}
    </div>

    <form class="z-form" method="post" name="config" action="{modurl modname='Newsletter' type='admin' func='modifyconfig'}" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="authid" name="authid" value="{insert name='generateauthkey' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Configuration'}</legend>
            <div class="z-formrow">
                <label for="itemsperpage">{gt text='Items per page'}:</label>
                <input id="itemsperpage" name="preferences[itemsperpage]" type="text" value="{$preferences.itemsperpage|default:25}" size="3" maxlength="3" />
            </div>
            <div class="z-formrow">
                <label for="send_from_address">{gt text='Send newsletters from'}:</label>
                <div id="send_from_address">
                    <input name="preferences[send_from_address]" type="text" value="{$preferences.send_from_address}" size="30" maxlength="128" />
                    <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-1', 'BLIND'); return false;">(?)</a>
                    <div id="hint-1" class="z-informationmsg z-formnote nl-round" style="display:none;">
                        {gt text='This is the delivering address of your newsletter e.g. newsletter@yourdomain.com.'}
                    </div>
                </div>
            </div>
            <div class="z-formrow">
                <label for="newsletter_subject">{gt text='Subject'}:</label>
                <div id="newsletter_subject">
                    <input name="preferences[newsletter_subject]" type="text" value="{$preferences.newsletter_subject}" size="40" maxlength="128" />
                    <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-2', 'BLIND'); return false;">(?)</a>
                    <div id="hint-2" class="z-informationmsg z-formnote nl-round" style="display:none;">
                        {gt text='This is the subject of the generated Newsletter.'}
                    </div>
                </div>
            </div>
            <div class="z-formrow">
                <label for="disable_auto">{gt text='Disable automatic sending'}:</label>
                <input id="disable_auto" name="preferences[disable_auto]" type="checkbox" value="1" {if ($preferences.disable_auto)}checked="checked"{/if} />
                <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-3', 'BLIND'); return false;">(?)</a>
                <div id="hint-3" class="z-informationmsg z-formnote nl-round" style="display:none;">
                    {gt text='Newsletters now have to be sent out manually.'}
                </div>
            </div>
            <div class="z-formrow">
                <label for="limit_type">{gt text='Allow subscription of only this type'}:</label>
                <select id="limit_type" name="preferences[limit_type]">{html_options values=$type_values2 output=$type_output2 selected=$preferences.limit_type}</select>
            </div>
            <div class="z-formrow">
                <label for="default_type">{gt text='Default newsletter type'}:</label>
                <select id="default_type" name="preferences[default_type]">{html_options values=$type_values output=$type_output selected=$preferences.default_type}</select>
            </div>
            <div class="z-formrow">
                <label for="default_frequency">{gt text='Default frequency'}:</label>
			
                <select id="default_frequency" name="preferences[default_frequency]" onchange="if(this.options[selectedIndex].value=='0'){document.forms[0].allow_frequency_change.checked=false; document.forms[0].allow_frequency_change.disabled = true; } else { document.forms[0].allow_frequency_change.disabled = false;}">{html_options values=$frequency_values output=$frequency_output selected=$preferences.default_frequency}</select>
		
			</div>
            <div class="z-formrow">
                <label for="send_day">{gt text='Sendday'}:</label>
                <select id="send_day" name="preferences[send_day]">{html_options values=$send_days_values output=$send_days_output selected=$preferences.send_day}</select>
            </div>
            <div class="z-formrow">
                <label for="archive_expire">{gt text='Expire time of archived newsletters'}:</label>
                <div id="archive_expire_values">
                    <select name="preferences[archive_expire]">{html_options values=$archive_expire_values output=$archive_expire_output selected=$preferences.archive_expire}</select>
                </div>
            </div>
            {if ($multilingual)}
            <div class="z-formrow">
                <label for="enable_multilingual">{gt text='Enable multilingual features'}:</label>
                <input id="enable_multilingual" name="preferences[enable_multilingual]" type="checkbox" value="1" {if ($preferences.enable_multilingual)}checked="checked"{/if} />
                <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-4', 'BLIND'); return false;">(?)</a>
                <div id="hint-4" class="z-informationmsg z-formnote nl-round" style="display:none;">
                    {gt text="This enables the sending of multilanguage newsletters."}
                </div>
            </div>
            {/if}
            <div class="z-formrow">
                <label for="notify_admin">{gt text='Receive subscription notices?'}</label>
                <input id="notify_admin" name="preferences[notify_admin]" type="checkbox" value="1" {if ($preferences.notify_admin)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="allow_anon_registration">{gt text='Allow anonymous registrations?'}</label>
                <input id="allow_anon_registration" name="preferences[allow_anon_registration]" type="checkbox" value="1" {if ($preferences.allow_anon_registration)} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_approval_status">{gt text='Show the approvalstatus to the user?'}</label>
                <input id="show_approval_status" name="preferences[show_approval_status]" type="checkbox" value="1" {if ($preferences.show_approval_status)} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="require_tos">{gt text='Require terms of service?'}</label>
                <input id="require_tos" name="preferences[require_tos]" type="checkbox" value="1" {if ($preferences.require_tos)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="auto_approve_registrations">{gt text='Auto-approve registrations?'}</label>
                <input id="auto_approve_registrations" name="preferences[auto_approve_registrations]" type="checkbox" value="1" {if ($preferences.auto_approve_registrations)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="allow_frequency_change">{gt text='Allow frequency changes?'}</label>
                <input id="allow_frequency_changes" name="preferences[allow_frequency_change]" type="checkbox" value="1" {if ($preferences.allow_frequency_change)} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="allow_subscription_change">{gt text='Allow subscription changes?'}</label>
                <input id="allow_subscription_change" name="preferences[allow_subscription_change]" type="checkbox" value="1" {if ($preferences.allow_subscription_change)} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="preferences[personalize_email]">{gt text='Personalize email?'}</label>
                <input id="personalize_email" name="preferences[personalize_email]" type="checkbox" value="1" {if ($preferences.personalize_email)} checked="checked"{/if} />
                <em class="z-formnote">{gt text='Disable to increase performance.'}</em>
            </div>
            <div class="z-formrow">
                <label for="send_per_request">{gt text='Send per request?'}</label>
                <input id="send_per_request" name="preferences[send_per_request]" type="text" value="{$preferences.send_per_request}" style="width:30px;" />
                <em class="z-formnote">{gt text='Number of emails (0 = no restriction).'}</em>
            </div>
            <div class="z-formrow">
                <label for="max_send_per_hour">{gt text='Maximum newsletters to send per hour'}</label>
                <div>
                    <input id="max_send_per_hour" name="preferences[max_send_per_hour]" type="text" value="{$preferences.max_send_per_hour}" style="width:30px;" />
                    <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-5', 'BLIND'); return false;">(?)</a>
                    <em class="z-formnote">{gt text='Number of emails (0 = no restriction).'}</em>
                    <div id="hint-5" class="z-informationmsg z-formnote nl-round" style="display:none;">
                        {gt text="Use this feature to avoid sending restrictions from your hoster!"}
                    </div>
                </div>
            </div>
            <div class="z-formrow">
                <label for="admin_key">{gt text='Adminkey'}</label>
                <input id="admin_key" name="preferences[admin_key]" type="text" value="{$preferences.admin_key}" />
                <em class="z-formnote">{gt text='Used to authenticate cron/batchprocessing.'}</em>
            </div>
        </fieldset>
        {include file='forms/actions.tpl'}
    </form>
</div>
