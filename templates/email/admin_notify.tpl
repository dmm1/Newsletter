
<h4>{gt text='New Subscriber Info'}</h4>

<div style="width:50%; border:1px solid #ccc; margin:0px auto;">
    <table>
        <tr>
            <td>{gt text='Username'}:</td>
            <td>{$user_name|safetext}</td>
        </tr>
        <tr>
            <td>{gt text='Email'}:</td>
            <td>{$user_email|safetext}</td>
        </tr>
    </table>
</div>

{if !$modvars.Newsletter.auto_approve_registrations}
<p>
    {gt text='Auto-approve is currently disabled. You will need to login, go to the newsletter admin area and approve this user.'}
</p>
{/if}
