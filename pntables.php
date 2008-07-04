<?php

function Newsletter_pntables()
{
    $pntable = array();

    $nl_users = pnConfigGetVar('prefix') . '_newsletter_users';
    $pntable['newsletter_users'] = $nl_users;
    $pntable['newsletter_users_column'] = array('id'=>$nl_users.'.pn_id',
    											'user_id'=>$nl_users.'.pn_user_id',
    											'user_name'=>$nl_users.'.pn_user_name',
                                      			'user_email'=>$nl_users.'.pn_user_email',
                                      			'nl_type'=>$nl_users.'.pn_nl_type', // text/html/web-html
                                      			'nl_frequency'=>$nl_users.'.pn_nl_frequency', // weekly/monthly/etc.
                                      			'active'=>$nl_users.'.pn_active', // user approval
                                      			'approved'=>$nl_users.'.pn_approved',
                                      			'last_send_date'=>$nl_users.'.pn_last_send_date',
                                      			'join_date'=>$nl_users.'.pn_join_date');
    
    $nl_archives = pnConfigGetVar('prefix') . '_newsletter_archives';
    $pntable['newsletter_archives'] = $nl_archives;
    $pntable['newsletter_archives_column'] = array('archive_date'=>$nl_archives.'.pn_archive_date',
    											   'archive_text'=>$nl_archives.'.pn_archive_text');
    
    return $pntable;
}
?>