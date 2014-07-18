{ajaxheader modname='Newsletter' ui=true}
{pagesetvar name='title' __value='View Archive'}

{include file='user/generic_header.tpl'}

<h3>{gt text='View Archive'}</h3>

<ol id="filterlist" class="z-itemlist">
    <li class="z-itemheader z-clearfix">
        {if $modvars.Newsletter.show_id|default:false}
        <span class="z-itemcell z-w05">
            {gt text='ID'}
        </span>
        {/if}
        {if $modvars.Newsletter.show_date|default:false}
        <span class="z-itemcell z-w15">
            {gt text='Date'}
        </span>
        {/if}
        {if $modvars.Newsletter.show_lang|default:false}
        <span class="z-itemcell z-w15">
            {gt text='Language'}
        </span>
        {/if}
        {if $modvars.Newsletter.show_plugins|default:false}
        <span class="z-itemcell z-w15">
            {gt text='# of Plugins'}
        </span>
        {/if}
        {if $modvars.Newsletter.show_objects|default:false}
        <span class="z-itemcell z-w15">
            {gt text='# of Items'}
        </span>
        {/if}
        {if $modvars.Newsletter.show_size|default:false}
        <span class="z-itemcell z-w10">
            {gt text='Length'}
        </span>
        {/if}
        <span class="z-itemcell z-w10">
            {gt text="Action"}
        </span>
    </li>
    {foreach from=$objectArray key="key" item="archive"}
    <li class="{cycle values='z-odd,z-even'} z-clearfix">
        {if $modvars.Newsletter.show_id|default:false}
        <span class="z-itemcell z-w05">
            {$archive.id|safetext}
        </span>
        {/if}
        {if $modvars.Newsletter.show_date|default:false}
        <span class="z-itemcell z-w15">
            {$archive.date_display}
        </span>
        {/if}
        {if $modvars.Newsletter.show_lang|default:false}
        <span class="z-itemcell z-w15">
            {$archive.lang|safetext}
        </span>
        {/if}
        {if $modvars.Newsletter.show_plugins|default:false}
        <span class="z-itemcell z-w15">
            {$archive.n_plugins|safetext}
        </span>
        {/if}
        {if $modvars.Newsletter.show_objects|default:false}
        <span class="z-itemcell z-w15">
            {$archive.n_items|safetext}
        </span>
        {/if}
        {if $modvars.Newsletter.show_size|default:false}
        <span class="z-itemcell z-w10">
            {$archive.text|strlen}
        </span>
        {/if}
        <span class="z-itemcell z-w10">
            <a href="{modurl modname='Newsletter' type='user' func='detail' ot='archive' id=$archive.id}" target="_blank">{img src='demo.png' modname='core' set='icons/extrasmall' __alt='View Archive' __title='View Archive'}</a>
        </span>
    </li>
    {/foreach}
</ol>

{include file='user/generic_footer.tpl'}
