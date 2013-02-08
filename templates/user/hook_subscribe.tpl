{modgetvar assign="hookuserreg_display" module="Newsletter" name="hookuserreg_display" default="checkboxon"}
{if $hookuserreg_display eq 'nomessage'}
{elseif $hookuserreg_display eq 'infmessage'}
<fieldset>
    <legend>{gt text="Newsletter" domain="module_newsletter"}</legend>
    <div class="z-formrow">
        <label for="newsletter_subscribe">{gt text="Subscribe to Newsletter" domain="module_newsletter"}</label>
        <div class="z-formnote">{gt text="Automated, after registration you can manage easy your subscription status in your profile." domain="module_newsletter"}</div>
    </div>
</fieldset>
{else}
<fieldset>
    <legend>{gt text="Newsletter" domain="module_newsletter"}</legend> 
    <div class="z-formrow">
        <label for="newsletter_subscribe">{gt text="Subscribe to Newsletter" domain="module_newsletter"}</label>
        <input id="newsletter_subscribe" name="newsletter_subscribe" type="checkbox"{if $hookuserreg_display eq 'checkboxon'} checked="checked"{/if} value="1" />
        <p class="z-sub z-formnote">{gt text="You can manage easy your subscription status in your profile." domain="module_newsletter"}</p>
    </div>
</fieldset>
{/if}