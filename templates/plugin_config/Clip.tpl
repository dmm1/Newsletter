{assign var='pubtypes' value=$plugin_parameters.Newsletter_NewsletterPlugin_Clip.param.PubTypes}
{assign var='pageargs' value=$plugin_parameters.Newsletter_NewsletterPlugin_Clip.param.Args}

{assign var='j' value=1}
{foreach from=$pubtypes key='tid' item='pubtype'}
<hr />
<div class="z-formrow">
    <label for="plugin_{$i}_enable_{$j}">{$pubtype.title|safehtml}</label>
    <input id="plugin_{$i}_enable_{$j}" type="checkbox" name="Newsletter_NewsletterPlugin_Clip_TIDs[{$tid}]" value="1" {if $pubtype.nwactive}checked="checked"{/if} />
</div>
<div id="plugin_{$i}_suboption_{$j}">
    <div class="z-formrow">
        <label for="ClipArgs_{$tid}_template">{gt text='Template'}</label>
        <input type="text" value="{$pageargs.$tid.template|default:''}" name="Newsletter_NewsletterPlugin_Clip_Args[{$tid}][template]" id="ClipArgs_{$tid}_template" maxlength="100" size="30">
        <em class="z-formnote z-sub">{gt text='Only for HTML Newsletter'}</em>
    </div>
    <div class="z-formrow">
        <label for="ClipArgs_{$tid}_itemsperpage">{gt text='Number of publications'}</label>
        <input type="text" value="{$pageargs.$tid.itemsperpage|default:''}" name="Newsletter_NewsletterPlugin_Clip_Args[{$tid}][itemsperpage]" id="ClipArgs_{$tid}_itemsperpage" maxlength="5" size="5">
    </div>
    <div class="z-formrow">
        <label for="ClipArgs_{$tid}_orderby">{gt text='Order by'}</label>
        <input type="text" value="{$pageargs.$tid.orderby|default:''}" name="Newsletter_NewsletterPlugin_Clip_Args[{$tid}][orderby]" id="ClipArgs_{$tid}_orderby" maxlength="255" size="30">
    </div>
    <div class="z-formrow">
        <label for="ClipArgs_{$tid}_filter">{gt text='Filter string'}</label>
        <input type="text" value="{$pageargs.$tid.filter|default:''}" name="Newsletter_NewsletterPlugin_Clip_Args[{$tid}][filter]" id="ClipArgs_{$tid}_filter" maxlength="512" size="30">
    </div>
</div>
{assign var='j' value=$j+1}
{foreachelse}
    <div class="z-warningmsg">{gt text='No publication types found.'}</div>
{/foreach}
