{pageaddvar name='stylesheet' value='modules/Newsletter/style/admin_style.css'}
{gt assign='pageTitle' text='Edit a Newsletter'}
{pagesetvar name='title' value=$pageTitle}
{adminheader}

<div class="z-admin-content-pagetitle">
    {img modname='core' src='edit.png' set='icons/small' alt=''}
    <h3>{$pageTitle}</h3>
</div>

<form class="z-form" action="{modurl modname='Newsletter' type='admin' func='savenewsletter'}" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" name="authid" value="{insert name='csrftoken' module='Newsletter'}" />
    <input type="hidden" name="id" value="{$newsletter.id}" />

    <fieldset class="z-linear">
        <legend>{gt text="Newsletter content"}</legend>

        <div class="z-formrow">
            <label for="content_html">{gt text="HTML"}</label>
            <textarea class="noeditor" id="content_html" name="html" cols="65" rows="20" >{$newsletter.html|safetext}</textarea>
        </div>
        <br />
        <div class="z-formrow">
            <label for="content_text">{gt text="Text"}</label>
            <textarea class="noeditor" id="content_text" name="text" cols="65" rows="20" >{$newsletter.text|safetext}</textarea>
        </div>

        {notifydisplayhooks eventname='newsletter.ui_hooks.items.form_edit'}
        <br />
        <div class="z-buttons z-formbuttons">
            {button src='button_ok.png' set='icons/small' id='submit' name='submit' value='submit' __alt='Update' __title='Update' __text='Update'}
            <a href="{modurl modname='Newsletter' type='admin' func='newsletters'}">
            {img modname='core' src='button_cancel.png' set='icons/small' alt='' __title='Cancel'}
            {gt text='Cancel'}
            </a>
        </div>

    </fieldset>
</form>

{adminfooter}
