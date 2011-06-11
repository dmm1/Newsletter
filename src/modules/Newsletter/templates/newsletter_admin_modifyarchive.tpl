
{include file='newsletter_admin_header.tpl'}

{newsletter_selector_archive_expire assign='archive_expire_values' return_keys=true}
{newsletter_selector_archive_expire assign='archive_expire_output' return_keys=false}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.gif' set='icons/small' __alt='Archive Settings'}</div>

    <h3>{gt text='Archive Settings'}</h3>

    <form class="z-form" method="post" name="config" action="{modurl modname='Newsletter' type='adminform' func='modifyarchive'}" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="authid" name="authid" value="{insert name='generateauthkey' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Archive Settings'}</legend>
            <div class="z-formrow">
                <label for="create_archive">{gt text='Create archive when sending out the newsletter manual?'}</label>
                <input id="create_archive" name="preferences_archive[create_archive]" type="checkbox" value="1" {if ($preferences_archive.create_archive)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_archive">{gt text="Show archive in the user-menu?"}</label>
                <input id="show_archive" name="preferences_archive[show_archive]" type="checkbox" value="1" {if ($preferences_archive.show_archive)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_id">{gt text="Show newsletter ID?"}</label>
                <input id="show_id" name="preferences_archive[show_id]" type="checkbox" value="1" {if ($preferences_archive.show_id)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_date">{gt text="Show date?"}</label>
                <input id="show_date" name="preferences_archive[show_date]" type="checkbox" value="1" {if ($preferences_archive.show_date)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_lang">{gt text="Show language?"}</label>
                <input id="show_lang" name="preferences_archive[show_lang]" type="checkbox" value="1" {if ($preferences_archive.show_lang)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_plugins">{gt text="Show plugins?"}</label>
                <input id="show_plugins" name="preferences_archive[show_plugins]" type="checkbox" value="1" {if ($preferences_archive.show_plugins)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_objects">{gt text="Show objects?"}</label>
                <input id="show_objects" name="preferences_archive[show_objects]" type="checkbox" value="1" {if ($preferences_archive.show_objects)}checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="show_size">{gt text="Show size?"}</label>
                <input id="show_size" name="preferences_archive[show_size]" type="checkbox" value="1" {if ($preferences_archive.show_size)}checked="checked"{/if} />
            </div>
        </fieldset>
        {include file='newsletter_inc_form_actions.tpl'}
    </form>

    <form class="z-form" action="{modurl modname='Newsletter' type='adminform' func='delete' ot='archive'}" method="post" onsubmit="if(!this.file_archive.checked &amp;&amp; !this.db_archive.checked){alert('{gt text="Please select an archive to flush"}.'); return false;}">
        <input type="hidden" id="authid" name="authid" value="{insert name='generateauthkey' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Prune Archives'}</legend>
            <div class="z-warningmsg">
                <strong>{gt text='Attention'}:</strong> {gt text='The archive cannot be restored.'}
            </div>
            <div class="z-formrow">
                <label for="db_archive">{gt text="Database"}:</label>
                <input id="db_archive" type="checkbox" name="archive[database]" value="1" />
            </div>
        </fieldset>
        {include file='newsletter_inc_form_actions.tpl'}
    </form>
</div>
