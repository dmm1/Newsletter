<?php

function Newsletter_pntables()
{
    $pntable = array();
	$pntable['newsletter'] = DBUtil::getLimitedTablename('newsletter');
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
									//			'newsletter_activeplugins'			=>	'pn_newsletter_activeplugins',
												'nid'         => 'pn_nid',
    
	$pntable['newsletter_users_column_def'] = array('nid'         => 'I AUTOINCREMENT PRIMARY'),
	 
    $pntable['newsletter_archives'] = pnConfigGetVar('prefix') . '_newsletter_archives');
    $pntable['newsletter_archives_column'] = array('archive_date'	=>	'pn_archive_date',
    											   'archive_text'	=>	'pn_archive_text');
	
 //  newsletter_categoriestable
	$table = $prefix . '_newsletter_categories';
    $pntables['newsletter_categories'] = $table;
    $columns = array (
	    'id'		=>	'nl_id',
	    'category_id'	=>	'nl_category_id',
	    'category2_id'	=>	'nl_category2_id',
	    'category3_id'	=>	'nl_category3_id',
	    'category4_id'	=>	'nl_category4_id',
	    'category5_id'	=>	'nl_category5_id',
	    'status_categorie'		=>	'nl_status_categorie'
        );
    ObjectUtil::addStandardFieldsToTableDefinition ($columns, 'nl_');
    $pntables['newsletter_categories_column'] = $columns;
    $columns_def = array (
            'id' 		=> 	'I4 NOTNULL AUTO PRIMARY',
            'category_id' 	=> 	'I4 NOTNULL DEFAULT 0',
            'category2_id' 	=> 	'I4 NOTNULL DEFAULT 0',
            'category3_id' 	=> 	'I4 NOTNULL DEFAULT 0',
            'category4_id' 	=> 	'I4 NOTNULL DEFAULT 0',
            'category5_id' 	=> 	'I4 NOTNULL DEFAULT 0',
            'status_categorie' 	=> 	'C(1) NOTNULL DEFAULT \'A\''
        );
    ObjectUtil::addStandardFieldsToTableDataDefinition ($columns_def);
    $pntables['newsletter_categories_column_def'] = $columns_def;
    $pntables['newsletter_categories_column_idx'] = array ('categories_category_idx'            => array('category_id', 'category2_id', 'category3_id', 'category4_id', 'category5_id', 'status_categorie'), 
                                                 
        );
												   
 // Enable categorization services
    $pntable['newsletter_db_extra_enable_categorization'] = pnModGetVar('Newsletter', 'enablecategorization');
    $pntable['newsletter_primary_key_column'] = 'id';

    // Return the table information
    return $pntable;
}
?>