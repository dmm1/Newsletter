
{if isset($ot)}
    {assign var="ot_" value=$ot}
{/if}

{if isset($otTarget) && $otTarget}
    {assign var="ot_" value=$otTarget}
{/if}

{formutil_getpassedvalue assign="type_" name="type" default="user"}
{formutil_getpassedvalue assign="func_" name="func" default="main"}

<div class="z-formbuttons">
    {button src='button_ok.png' set='icons/small' id='submit' name='submit' value='submit' __alt='Update' __title='Update'}
    {if ($type_=='user')}
        {if ($func_ =='main')}
        <a href="{modurl modname="Newsletter" type=$type_ func="main"}">
        {else}
        <a href="{modurl modname="Newsletter" type=$type_ func="view" ot=$ot_}">
        {/if}
    {else}
        {if ($func_ == 'main' || $func_ == 'modifyconfig')}
        <a href="{modurl modname="Newsletter" type=$type_ func="modifyconfig"}">
        {else}
        <a href="{modurl modname="Newsletter" type=$type_ func="view" ot=$ot_}">
        {/if}
    {/if}
    {img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}
    </a>
</div>
