{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{ajaxheader modname='Newsletter' filename='newsletter.js' ui=true}
{ajaxheader modname='Newsletter' filename='checkall.js'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='user.png' set='icons/small' alt=''}
    <h3>{gt text='Subscribers'}</h3>
</div>
{*
<script type="text/javascript">
    function disableSendChecked(){
        send2users.sendChecked.disabled = true;
    }
    onload = disableSendChecked;
</script>
*}
{newsletter_selector_active assign="active_values" return_keys=true all=true}
{newsletter_selector_active assign="active_output" return_keys=false all=true}
{newsletter_selector_approved assign="approved_values" return_keys=true all=true}
{newsletter_selector_approved assign="approved_output" return_keys=false all=true}
{newsletter_selector_frequency assign="frequency_values" return_keys=true all=true}
{newsletter_selector_frequency assign="frequency_output" return_keys=false all=true}
{newsletter_selector_language assign="language_values" return_keys=true all=true}
{newsletter_selector_language assign="language_output" return_keys=false all=true}
{newsletter_selector_type assign="type_values" return_keys=true all=true}
{newsletter_selector_type assign="type_output" return_keys=false all=true}

{insert name='csrftoken' assign="authid"}
{modgetvar assign='enable_multilingual' module='Newsletter' name='enable_multilingual' default=0}

{sessiongetvar assign='currPagesize' name='pagesize' default=25 path='/Newsletter'}

<form class="z-form" style="width: 100%;" method="post" action="{modurl modname='Newsletter' type='admin' func='view' ot=$ot}" name="langfilter" enctype="application/x-www-form-urlencoded">
    {gt text="Edit" assign=lblEdit}
    {gt text="Delete" assign=lblDelete}
    {gt text="Are you sure you wish to change the active status for this user?" assign=qActive}
    {gt text="Are you sure you wish to change the approved status for this user?" assign=qApproved}

    <fieldset>
        <legend>{gt text='User search / Add a user'}</legend>

        <div id="liveusersearch" class="z-hide z-formrow">
            <div>
            {gt text='Search'}&nbsp;<input size="30" maxlength="30" name="filter[search]" id="filter_search" value="" />
            {img id='ajax_indicator' style='display: none;' modname='Newsletter' src='ajax.gif' alt=''}

            <div id="filter_search_choices" class="nl_autocomplete_user"></div>
            <img src="images/icons/extrasmall/add_user.png" alt="{gt text='Add a user'}" />&nbsp;<a href="{modurl modname='Newsletter' type='admin' func='edit' ot='user' authid=$authid}">{gt text='Add a user'}</a>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>{gt text="Filter"}</legend>
        <ol id="filterlist" class="z-itemlist">
            <li class="z-itemheader z-clearfix">
                {if ($enable_multilingual)}
                <span class="z-itemcell z-w15">{gt text="Language"}&nbsp;</span>
                {/if}
                <span class="z-itemcell z-w15">{gt text="Type"}&nbsp;</span>
                <span class="z-itemcell z-w25">{gt text="Frequency"}&nbsp;</span>
                <span class="z-itemcell z-w15">{gt text="Active"}&nbsp;</span>
                <span class="z-itemcell z-w15">{gt text="Approved"}&nbsp;</span>
            </li>
            <li class="{cycle values="z-odd,z-even"} z-clearfix">
                {if ($enable_multilingual)}
                <span class="z-itemcell z-w15"><select id="filter_lang" name="filter[lang]" onchange="this.form.submit();">{html_options values=$language_values output=$language_output selected=$filter.lang}</select></span>
                {/if}
                <span class="z-itemcell z-w15"><select id="filter_type" name="filter[type]" onchange="this.form.submit();">{html_options values=$type_values output=$type_output selected=$filter.type}</select></span>
                <span class="z-itemcell z-w25"><select id="filter_frequency" name="filter[frequency]" onchange="this.form.submit();">{html_options values=$frequency_values output=$frequency_output selected=$filter.frequency|default:-1}</select></span>
                <span class="z-itemcell z-w15"><select id="filter_active" name="filter[active]" onchange="this.form.submit();">{html_options values=$active_values output=$active_output selected=$filter.active}</select></span>
                <span class="z-itemcell z-w15"><select id="filter_approved" name="filter[approved]" onchange="this.form.submit();">{html_options values=$approved_values output=$approved_output selected=$filter.approved}</select></span>
            </li>
        </ol>
    </fieldset>
</form>

<form class="z-form" name="send2users" action="{modurl modname='Newsletter' type='admin' func='save'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{$authid}" />
    <input type="hidden" name="ot" value="NewsletterSend" />
    <input type="hidden" name="otTarget" value="user" />
    {if $enable_multilingual}
        <input type="hidden" name="language" value="{$filter.lang}" />
    {else}
        <input type="hidden" name="language" value="{defaultlang}" />
    {/if}

    <fieldset>
        <legend>{gt text="Data"}</legend>
        <div class="z-right">
            {php}
                $psArray = array();
                $psArray[25]   = 25;
                $psArray[50]   = 50;
                $psArray[75]   = 75;
                $psArray[100]  = 100;
                $psArray[250]  = 250;
                $psArray[500]  = 500;
                $psArray[750]  = 750;
                $psArray[1000] = 1000;
                $psArray[-1]   = All;
                $this->assign ('pagesizes', $psArray);
            {/php}
            {gt text='Display number of users per page:'}
            {foreach from=$pagesizes key="amount" item="label"}
                {if $amount eq $currPagesize}
                    {$label}&nbsp;
                {else}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot='user' pagesize=$amount}">{$label}</a>&nbsp;
                {/if}
            {/foreach}
        </div>

        <ol id="datalist" class="z-itemlist">
            <li class="z-itemheader z-clearfix">
                <span class="z-itemcell z-w02">
                    &nbsp;<input type="checkbox" onclick="checkAllFields(1);" id="checkAll" />
                </span>
                <span class="z-itemcell z-w02">
                    {php}$this->_tpl_vars['filter']['sort'] = 'id';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='ID'}</a>
                </span>
                <span class="z-itemcell z-w05">
                    {php}$this->_tpl_vars['filter']['sort'] = 'uid';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='UID'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'name';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Username'}</a>
                </span>
                <span class="z-itemcell z-w15">
                    {php}$this->_tpl_vars['filter']['sort'] = 'email';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Email'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'lang';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Language'}</a>
                </span>
                <span class="z-itemcell z-w05">
                    {php}$this->_tpl_vars['filter']['sort'] = 'type';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Type'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'frequency';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Frequency'}</a>
                </span>
                <span class="z-itemcell z-w05">
                    {php}$this->_tpl_vars['filter']['sort'] = 'active DESC';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Active'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'approved DESC';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Approved'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'last_send_date DESC';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Last sent'}</a>
                </span>
                <span class="z-itemcell z-w10">
                    {php}$this->_tpl_vars['filter']['sort'] = 'cr_date DESC';{/php}
                    <a href="{modurl modname='Newsletter' type='admin' func='view' ot=$ot filter=$filter}">{gt text='Join date'}</a>
                </span>
                <span class="z-itemcell z-w05">
                    {gt text='Action'}
                </span>
            </li>

            {checkpermission assign="adminAuth" component="Newsletter::" instance="::" level="ACCESS_ADMIN"}
            {foreach from=$objectArray item=user}
            <li class="{cycle values='z-odd,z-even'} z-clearfix">
                <span class="z-itemcell z-w02">
                    &nbsp;<input type="checkbox" name="user_array[]" value="{$user.id|safehtml}" onclick="checkAllFields(2);" />
                </span>
                <span class="z-itemcell z-w02">
                    {$user.id}
                </span>
                <span class="z-itemcell z-w05">
                    {if $user.uid gt 1}
                        <a href="{modurl modname='Profile' type='user' func='view' uid=$user.uid}">{$user.uid}</a>
                    {else}
                        {gt text='No'}
                    {/if}
                </span>
                <span class="z-itemcell z-w10">
                    {$user.name|safehtml}
                </span>
                <span class="z-itemcell z-w15">
                    {$user.email|safehtml}
                </span>
                <span class="z-itemcell z-w10">
                    {$user.lang|safehtml}
                </span>
                <span class="z-itemcell z-w05">
                    {if $user.type eq 1}
                        {gt text='Text'}
                    {elseif $user.type eq 2}
                        {gt text='HTML'}
                    {elseif $user.type eq 3}
                        {gt text='Text with link to archive'}
                    {else}
                        {$user.type} (?)
                    {/if}
                </span>
                <span class="z-itemcell z-w10">
                    {if $user.frequency eq 0}
                        {gt text='Weekly'}
                    {elseif $user.frequency eq 1}
                        {gt text="Monthly"}
                    {elseif $user.frequency eq 12}
                        {gt text='Yearly'}
                    {elseif $user.frequency gt 1}
                        {gt text='Every %s months' tag1=$user.frequency}
                    {else}
                        {$user.frequency} (?)
                    {/if}
                </span>
                <span class="z-itemcell z-w05">
                    <a href="{modurl modname='Newsletter' type='admin' func='edit' ot='UserActive' otTarget='user' id=$user.id authid=$authid filter=$filter}" onclick="return confirm('{$qActive}');">
                        {$user.active|yesno}
                    </a>
                </span>
                <span class="z-itemcell z-w10">
                    <a href="{modurl modname='Newsletter' type='admin' func='edit' ot='UserApproved' otTarget='user' id=$user.id authid=$authid filter=$filter}" onclick="return confirm('{$qApproved}');">
                        {$user.approved|yesno}
                    </a>
                </span>
                <span class="z-itemcell z-w10">
                    {if $user.last_send_date}
                        {$user.last_send_date|safehtml}
                    {else}
                        {gt text="Not send yet"}
                    {/if}
                </span>
                <span class="z-itemcell z-w10">
                    {$user.cr_date|safehtml}
                </span>
                <span class="z-itemcell z-w05">
                  {if $adminAuth}
                    <a href="{modurl modname='Newsletter' type='admin' func='edit' ot=$ot id=$user.id authid=$authid}">{img src='xedit.png' modname='core' set='icons/extrasmall' alt=$lblEdit altml='false' title=$lblEdit titleml='false'}</a>
                    <a href="{modurl modname='Newsletter' type='admin' func='delete' ot=$ot id=$user.id authid=$authid}" onclick="return confirm('{$lblDelete}');">{img src='button_cancel.png' modname='core' set='icons/extrasmall' alt=$lblDelete altml='false' title=$lblDelete titleml='false'}</a>
                  {/if}
                </span>
            </li>
            {/foreach}
        </ol>
    </fieldset>

    {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar='startnum' shift=1}

    <fieldset>
        <legend>{gt text='Action'}</legend>
        <div class="z-formrow">
            <label for="updateSendDate">{gt text='Update last sent date?'}</label>
            <input type="checkbox" id="updateSendDate" name="updateSendDate" value="1" />
        </div>
        <div class="z-formrow">
            <label for="sendType">{gt text='Save newsletter in archive?'}</label>
            <select id="sendType" name="sendType">
                <option value="manual" selected="selected">{gt text="Don't save"}</option>
                <option value="manual_archive">{gt text='Save, but if not saved in one week'}</option>
                <option value="manual_archive_nocheck">{gt text='Save without check'}</option>
            </select>
        </div>
        <div class="z-formbuttons z-buttons">
            <input class="z-button" type="submit" value="{gt text='Send to [%s] users' tag1=0}" name="sendChecked" id="sendChecked" />
        </div>
    </fieldset>
</form>

<script type="text/javascript">
Event.observe(window, 'load', function() {
    if ($('liveusersearch')) {
        liveusersearch();
    }
}, false);
</script>

{adminfooter}
