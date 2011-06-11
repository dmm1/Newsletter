
{include file='newsletter_admin_header.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='info.png' set='icons/small' __alt='Statistics'}</div>

    <h3>{gt text='Statistics'}</h3>

    <div class="z-form z-nw-statistics">
        <fieldset>
            <legend>{gt text="Newsletter Module Statistics"}</legend>

            <div class="z-formrow">
                <label for="users"><em>{gt text="Total number of users"}:</em></label>
                <span id="users"><strong>{$objectArray.users}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_active"><em>{gt text="Active subscribers"}:</em></label>
                <span id="users_active"><strong>{$objectArray.users_active}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_approved"><em>{gt text="Approved subscribers"}:</em></label>
                <span id="users_approved"><strong>{$objectArray.users_approved}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_activeapproved"><em>{gt text="Active and approved subscribers"}:</em></label>
                <span id="users_activeapproved"><strong>{$objectArray.users_approved_and_active}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_registered"><em>{gt text="Subscribers which are registered users"}:</em></label>
                <span id="users_registered"><strong>{$objectArray.users_registered}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_html"><em>{gt text="Number of subscribers for HTML format"}:</em></label>
                <span id="users_html"><strong>{$objectArray.users_html}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_text"><em>{gt text="Numer of subscribers for text format"}:</em></label>
                <span id="users_text"><strong>{$objectArray.users_text}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_textwithlinks"><em>{gt text="Number of subscribers for text with links format"}:</em></label>
                <span id="users_textwithlinks"><strong>{$objectArray.users_textwithlinks}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_weekly"><em>{gt text="Number of subscribers for weekly delivery"}:</em></label>
                <span id="users_weekly"><strong>{$objectArray.users_weekly}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_monthly"><em>{gt text="Number of subscribers for monthly delivery"}:</em></label>
                <span id="users_monthly"><strong>{$objectArray.users_monthly}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_yearly"><em>{gt text="Number of subscribers for yearly delivery"}:</em></label>
                <span id="users_yearly"><strong>{$objectArray.users_yearly}</strong></span>
            </div>
            <div class="z-formrow">
                <label for="users_archives"><em>{gt text="Number of archives"}:</em></label>
                <span id="users_archives"><strong>{$objectArray.archives}</strong></span>
            </div>
        </fieldset>
    </div>
</div>
