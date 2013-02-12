{php}
    $defaultLang = System::getVar('language_i18n');
    $alternateLanguages = ZLanguage::getInstalledLanguageNames();
    $defaultLangName = $alternateLanguages[$defaultLang];
    unset($alternateLanguages[$defaultLang]);
    $this->assign('defaultLang', $defaultLangName);
    $this->assign('alternateLanguages', $alternateLanguages);
    $this->assign('coremultilingual', System::getVar('multilingual', 0));
{/php}

{modgetvar module='Newsletter' name='message'  assign='text' default=''}


<div class="z-formrow">
    <label for="NewsletterMessage_text"><strong>{gt text="Your message"} ({$defaultLang}):</strong></label>
    <textarea id="NewsletterMessage_text" name="NewsletterMessage[text]" cols="65" rows="8" >{$text}</textarea>
</div>

{if ($coremultilingual && $alternateLanguages)}
    {foreach from=$alternateLanguages key='lang' item='language'}
    {assign_concat name='fID' 1='NewsletterMessage_text_' 2=$lang}
    {assign_concat name='fName' 1='NewsletterMessage[text_' 2=$lang 3=']'}
    {assign_concat name='vName' 1='text_' 2=$lang}
    {modgetvar assign='text' module='Newsletter' name=$vName default=''}
    <div class="z-formrow">
        <label for="{$fID}"><strong>{gt text='Your message'} ({$language}):</strong></label>
        <textarea id="{$fID}" name="{$fName}" cols="65" rows="8" >{$text}</textarea>
    </div>
    {/foreach}
{/if}


