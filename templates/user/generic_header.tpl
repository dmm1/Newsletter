{checkpermission component='Newsletter::' instance='::' level='ACCESS_ADMIN' assign='adminAuth'}

<div class="nl-wrapper">
    {insert name='getstatusmsg'}

    {img src='admin-icon.png' modname='Newsletter' width="50" class="z-floatleft"}
    <h2>{gt text='Newsletter'}</h2>
    <div class="z-clearfix"></div>

    {modulelinks type='user'}

