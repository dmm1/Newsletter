{gt text='Preview your Newsletter' assign='templatetitle'}
{include file='admin/header.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Newsletter' src='admin.png' alt=''}</div>

    <h2>{$templatetitle}</h2>

    <form class="z-form" method="post" name="show_template" action="{modurl modname='Newsletter' type='admin' func='view' ot='show_preview'}" target="_blank" enctype="application/x-www-form-urlencoded">
        <input type="hidden" id="authid" name="authid" value="{insert name='generateauthkey' module='Newsletter'}" />

        <fieldset>
            <legend>{gt text='Preview Templates'}</legend>

            {modgetvar assign="enable_multilingual" module="Newsletter" name="enable_multilingual" default=0}
            {if ($enable_multilingual)}
            <div class="z-formrow">
                <label for="language">{gt text="Language"}:</label>
                {sessiongetvar assign="currLang" name="lang"}
                {configgetvar assign="defaultLang" name="language"}
                {html_select_languages id="user_lang" name="language" installed=true selected=$currLang|default:$defaultlang}
            </div>
            {/if}

            <div class="z-formrow">
                {usergetvar assign="email" name="email"}
                <label for="testsend">{gt text="Mail this preview to the following email address"}:</label>
                <input id="testsend" name="testsend" type="checkbox" value="1" />
                <input id="testsend_email" name="testsend_email" type="text" value="{$email}" size="20" maxlength="64"  />
            </div>

            <div class="z-formrow">
                <label for="format">{gt text="Format"}:</label>
                <div>
                    <select name="format" onchange="this.form.submit();javascript:location.reload(true);" >
                        <option>{gt text="- Choose One -"}</option>
                        <option value="1">{gt text="Text"}</option>
                        <option value="2">{gt text="HTML"}</option>
                        <option value="3">{gt text="Text with Link to Archive"}</option>
                    </select>
                    <a href="#" onclick="Effect.toggle('hint-1','BLIND'); return false;" title="{gt text='Help'}">(?)</a>
                    <div id="hint-1" class="z-informationmsg" style="display:none;">
                        {gt text="You can find the templates in"} /modules/Newsletter/templates/
                        <ul>
                            <li>newsletter_template_html.tpl</li>
                            <li>newsletter_template_text.tpl</li>
                            <li>newsletter_template_text_with_link.tpl</li>
                       </ul>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
