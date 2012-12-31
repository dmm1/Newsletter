{if !$modvars.Newsletter.limit_type}
{newsletter_selector_type assign='types'}
<div class="z-formrow">
    <label for="viewpub_pubtype">{gt text='Default Newsletter Type'}</label>
    {html_options name="nl_type" options=$types selected=$nl_type_sel}
</div>
{else}
<input type="hidden" name="nl_type" value="{$limit_type}" />
{/if}

{newsletter_selector_frequency assign="frequencies"}
<div class="z-formrow">
    <label for="viewpub_orderby">{gt text='Default Frequency'}</label>
    {html_options name="nl_frequency" options=$frequencies selected=$nl_frequency_sel}
</div>
