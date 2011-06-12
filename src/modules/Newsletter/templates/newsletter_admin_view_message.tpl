
{include file='newsletter_admin_header.tpl'}

{php}
    $defaultLang = pnConfigGetVar('language_i18n');
    $alternateLanguages = ZLanguage::getInstalledLanguageNames();
    $defaultLangName = $alternateLanguages[$defaultLang];
    unset($alternateLanguages[$defaultLang]);
    $this->assign('defaultLang', $defaultLangName);
    $this->assign('alternateLanguages', $alternateLanguages);
    $this->assign('coremultilingual', pnConfigGetVar('multilingual', 0));
{/php}

{modgetvar module='Newsletter' name='message'  assign='text' default=""}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Newsletter' src='admin.png' alt=''}</div>

    <h3>{gt text="Message"}</h3>
	<br />
    <div class="z-formnote z-warningmsg nl-round">
        <strong>{gt text='Attention'}:</strong> {gt text="Please ensure that you have enabled the NewsletterMessage plugin if you wish to add this message to your newsletter."}
    </div>

    <form class="z-form" method="post" name="config" action="{modurl modname='Newsletter' type='admin' func='edit'}">
        <input type="hidden" id="authid" name="authid" value="{insert name='generateauthkey' module='Newsletter'}" />
        <input type="hidden" name="ot" value="message" />

        <fieldset class="z-linear">
            <div class="z-formrow">
                <label for="message_text"><strong>{gt text="Your message"} ({$defaultLang}):</strong></label>
                <div id="admin_key">
                    <textarea id="message_text" name="message[text]" cols="65" rows="15" >{$text}</textarea>
                </div>
            </div>

            {if ($coremultilingual && $alternateLanguages)}
                {foreach from=$alternateLanguages key="lang" item="language"}
                {assign_concat name="fID" 1="message_text_" 2=$lang}
                {assign_concat name="fName" 1="message[text_" 2=$lang 3="]"}
                {assign_concat name="vName" 1="message_" 2=$lang}
                {modgetvar assign="text" module="Newsletter" name=$vName default=""}
                <div class="z-formrow">
                    <label for="{$fID}"><strong>{gt text="Your message"} ({$language}):</strong></label>
                    <div id="admin_key">
                        <textarea id="{$fID}" name="{$fName}" cols="65" rows="15" >{$text}</textarea>
                    </div>
                </div>
                {/foreach}
            {/if}
        </fieldset>
        {include file='newsletter_inc_form_actions.tpl'}
    </form>
</div>
