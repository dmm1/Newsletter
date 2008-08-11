<?php

function Newsletter_pntables()
{
    $pntable = array();
	
    $pntable['newsletter_users'] = pnConfigGetVar('prefix') . '_newsletter_users';
    $pntable['newsletter_users_column'] = array('id'					=>	'pn_id',
    											'user_id'				=>	'pn_user_id',
    											'user_name'				=>	'pn_user_name',
                                      			'user_email'			=>	'pn_user_email',
                                      			'nl_type'				=>	'pn_nl_type', // text/html/web-html
                                      			'nl_frequency'			=>	'pn_nl_frequency', // weekly/monthly/etc.
                                      			'active'				=>	'pn_active', // user approval
                                      			'approved'				=>	'pn_approved',
                                      			'last_send_date'		=>	'pn_last_send_date',
                                      			'join_date'				=>	'pn_join_date',
												'category_id'			=>	'pn_category_id',
												'category_name'			=>	'pn_category_name',
												'category_description'	=>	'pn_category_description',
												'info_id'				=>	'pn_info_id',												
												'info_text'				=>	'pn_info_text',
												'info_name'				=>	'pn_info_name',
												'info_senddate'			=>	'pn_info_senddate',
    
    $pntable['newsletter_archives'] = pnConfigGetVar('prefix') . '_newsletter_archives');
    $pntable['newsletter_archives_column'] = array('archive_date'	=>	'pn_archive_date',
    											   'archive_text'	=>	'pn_archive_text');


    // Return the table information
    return $pntable;
}
?>