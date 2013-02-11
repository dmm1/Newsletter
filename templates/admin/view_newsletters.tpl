{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='demo.png' set='icons/small' alt=''}
    <h3>{gt text='Manage Newsletters'}</h3>
</div>

<form class="z-form" method="post" name="show_template" action="{modurl modname='Newsletter' type='admin' func='view' ot='ShowPreview'}" target="_blank" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

    <fieldset>
        <legend>{gt text='Preview Templates'}</legend>

        {modgetvar assign="enable_multilingual" module="Newsletter" name="enable_multilingual" default=0}
        {if ($enable_multilingual)}
        <div class="z-formrow">
            <label for="language">{gt text="Language"}:</label>
            {sessiongetvar assign="currLang" name="lang"}
            {defaultlang assign="defaultLang"}
            {html_select_languages id="user_lang" name="language" installed=true selected=$currLang|default:$defaultlang}
        </div>
        {/if}

        <div class="z-formrow">
            {usergetvar assign="email" name="email"}
            <label for="testsend">{gt text="Mail this preview to the following email address"}:</label>
            <div>
                <input id="testsend" name="testsend" type="checkbox" value="1" />
                <input id="testsend_email" name="testsend_email" type="text" value="{$email}" size="20" maxlength="64"  />
            </div>
        </div>

        <div class="z-formrow">
            <label for="format">{gt text='Format'}: <a href="#" onclick="Effect.toggle('hint-1','BLIND'); return false;" title="{gt text='Help'}">(?)</a></label>
            <div>
                {modgetvar module='Newsletter' name='default_type'  assign='default_type' default='1'}
                <select id="format" name="format" size="3" >
                    <option value="1"{if $default_type eq '1'} selected="selected"{/if}>{gt text='Text'}</option>
                    <option value="2"{if $default_type eq '2'} selected="selected"{/if}>{gt text='HTML'}</option>
                    <option value="3"{if $default_type eq '3'} selected="selected"{/if}>{gt text='Text with Link to Archive'}</option>
                </select>
                <div id="hint-1" class="z-informationmsg nl-hint" style="display:none;">
                    {gt text='You can find the templates in %s' tag1='/modules/Newsletter/templates/output'}
                    <ul>
                        <li>html.tpl</li>
                        <li>text.tpl</li>
                        <li>text_with_link.tpl</li>
                   </ul>
                </div>
            </div>
        </div>

        <div class="z-formbuttons z-buttons">
            <input id="submit" class="z-button" type="submit" name="submit" value="{gt text='Preview'}" />
        </div>
    </fieldset>
</form>

<form class="z-form" method="post" name="createNewsletter" action="{modurl modname='Newsletter' type='admin' func='createnewsletter'}" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

    <fieldset>
        <legend>{gt text='Create newsletter in archive'}</legend>
        <div class="z-formrow">
            <label for="Nextid">{gt text='Next Id in archive'}:</label>
            <input id="Nextid" name="Nextid" type="text" value="{$newsletterNextid}" />
            <div class="z-informationmsg z-formnote nl-round">
                {gt text='Max Id in newsletter archive is '}{$newsletterMaxid}. {gt text='Be sure to enter proper value.'}
            </div>
        </div>

        <div class="z-formbuttons z-buttons">
            <input id="submit" class="z-button" type="submit" name="create" value="{gt text='Create new Newsletter'}" />
        </div>
    </fieldset>
</form>

<form class="z-form" method="post" name="sendNewsletter" action="{modurl modname='Newsletter' type='admin' func='sendnewsletter' id=$LastNewsletter.id}" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="authid" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />

    <fieldset class="z-linear">
        <legend>{gt text='Send newsletter to subscribers'}</legend>

        {modgetvar module="Newsletter" name="disable_auto" assign="disable_auto"}
        {modgetvar module="Newsletter" name="send_day" assign="send_day"}
        <div class="z-informationmsg z-formnote nl-round">
            {gt text='Autamated sending:'} {if $disable_auto}{gt text='disabled'}.{else}{gt text='enabled, send day is '}{$arraysenddays[$send_day]}. {gt text='This to occur Maintenance block have to be active.'}{/if}
        </div>
        {if $LastNewsletter.id gt 0}
        <div class="z-informationmsg z-formnote nl-round">
            {gt text='Last newsletter Id:'} {$LastNewsletter.id}, {$LastNewsletter.lang}. {gt text='Sent to subscribers:'} {$newsletterLastidSentCount}. {gt text='All subscribers:'} {$SubscribersCount}.
        </div>
        {/if}
        {modgetvar module="Newsletter" name="enable_multilingual" assign="enable_multilingual"}
        {if $enable_multilingual}
        <div class="z-informationmsg z-formnote nl-round">
            {gt text='Multilingual support: enabled'}. {gt text='Newsletter will send to subscribers selected same language'} ({$LastNewsletter.lang}).
        </div>
        {/if}
        <br />
        <div class="z-formbuttons z-buttons">
            <label for="sendNewsletter">{gt text='Manual sending'}: <a class="z-normal" href="#" onclick="Effect.toggle('hint-2','BLIND'); return false;" title="{gt text='Help'}">(?)</a></label>
            <input id="sendNewsletter" class="z-button" type="submit" name="sendNewsletter" value="{gt text='Send last newsletter to subscribers'}" />
            <a href="{modurl modname='Newsletter' type='admin' func='view' ot='user'}">{gt text='Send to selected subscribers'}</a>
        </div>
        <div id="hint-2" class="z-informationmsg nl-hint" style="display:none;">
            {gt text='You can pres this button several times, newsletter will send once per subscriber.<br />If you notice in the message newsletter is not send to all users, you can press again.'}
        </div>
    </fieldset>
</form>

<form class="z-form" method="post" name="listNewsletters" action="{modurl modname='Newsletter' type='admin' func='newsletters'}" enctype="application/x-www-form-urlencoded">
    <fieldset>
    <legend>{gt text='Last Newsletters in archive'}</legend>
    <ol id="filterlist" class="z-itemlist">
        <li class="z-itemheader z-clearfix">
            <span class="z-itemcell z-w05">
                {gt text='ID'}
            </span>
            <span class="z-itemcell z-w15">
                {gt text='Date'}
            </span>
            <span class="z-itemcell z-w10">
                {gt text='Language'}
            </span>
            <span class="z-itemcell z-w10">
                {gt text='# of Plugins'}
            </span>
            <span class="z-itemcell z-w10">
                {gt text='# of Items'}
            </span>
            <span class="z-itemcell z-w10">
                {gt text='Length'}
            </span>
            <span class="z-itemcell z-w40">
                {gt text="Action"}
            </span>
        </li>
        {foreach from=$objectArray key="key" item="archive"}
        <li class="{cycle values='z-odd,z-even'} z-clearfix">
            <span class="z-itemcell z-w05">
                {$archive.id|safetext}
            </span>
            <span class="z-itemcell z-w15">
                {$archive.date_display}
            </span>
            <span class="z-itemcell z-w10">
                {$archive.lang|safetext}
            </span>
            <span class="z-itemcell z-w10">
                {$archive.n_plugins|safetext}
            </span>
            <span class="z-itemcell z-w10">
                {$archive.n_items|safetext}
            </span>
            <span class="z-itemcell z-w10">
                {$archive.text|strlen}
            </span>
            <span class="z-itemcell z-w05">
                {gt text="View Archive HTML" assign=lblDetail}
                <a href="{modurl modname='Newsletter' type='user' func='detail' ot='archive' format='2' id=$archive.id}" target="_blank">{img src='demo.png' modname='core' set='icons/extrasmall' alt=$lblDetail altml='false' title=$lblDetail titleml='false'}</a>
                {gt text="View Archive Text" assign=lblDetail}
                <a href="{modurl modname='Newsletter' type='user' func='detail' ot='archive' format='1' id=$archive.id}" target="_blank">{img src='kview.png' modname='core' set='icons/extrasmall' alt=$lblDetail altml='false' title=$lblDetail titleml='false'}</a>
            </span>
            <span class="z-itemcell z-w05">
                {gt text="Edit" assign=lblEdit}
                <a href="{modurl modname='Newsletter' type='admin' func='editnewsletter' id=$archive.id authid=$authid}">{img src='xedit.png' modname='core' set='icons/extrasmall' alt=$lblEdit altml='false' title=$lblEdit titleml='false'}</a>
            </span>
            <span class="z-itemcell z-w10">
                {gt text="Delete Archive" assign=lblDelete}
                {gt text="Are you sure? This will delete this item forever." assign=lblConfirm}
                <a href="{modurl modname='Newsletter' type='admin' func='deletenewsletter' id=$archive.id}" onclick="return confirm('{$lblConfirm}')">{img src='button_cancel.png' modname='core' set='icons/extrasmall' alt=$lblDelete altml='false' title=$lblDelete titleml='false'}</a>
            </span>
            {if $key eq 0}
            <span class="z-itemcell z-w20">
                {gt text="Delete/Preserve Id" assign=lblDelPreserve}
                {gt text="Next newsletter Id will not be incremented." assign=lblConfPreserve}
                <a href="{modurl modname='Newsletter' type='admin' func='deletenewsletter' id=$archive.id preserveid=1}" onclick="return confirm('{$lblConfirm} {$lblConfPreserve}')">{img src='button_cancel.png' modname='core' set='icons/extrasmall' alt=$lblDelPreserve altml='false' title=$lblDelPreserve titleml='false'} {$lblDelPreserve}</a>
            </span>
            {/if}
        </li>
        {/foreach}
    </ol>
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar='startnum' shift=1}
    </fieldset>
</form>

{adminfooter}
