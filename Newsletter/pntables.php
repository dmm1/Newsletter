<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2009, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */


function Newsletter_pntables()
{
    $pntables = array();


    $pntables['newsletter_users'] = DBUtil::getLimitedTablename ('newsletter_users'); 
    $columns = array (
    		     'id'		=>	'nlu_id',
		     'category_id'	=>	'nlu_category_id',
		     'uid'		=>	'nlu_uid',
		     'name'		=>	'nlu_name',
		     'email'		=>	'nlu_email',
		     'lang'		=>	'nlu_lang',
		     'type'		=>	'nlu_type', 			// text/html/web-html
		     'frequency'	=>	'nlu_frequency', 		// weekly/monthly/etc.
		     'active'		=>	'nlu_active', 			// user approval
		     'approved'		=>	'nlu_approved',
		     'last_send_date'	=>	'nlu_last_send_date'
        );
    ObjectUtil::addStandardFieldsToTableDefinition ($columns, 'nlu_');
    $pntables['newsletter_users_column'] = $columns;
    $columns_def = array (
    		     'id'         	=> 	'I4 NOTNULL AUTO PRIMARY',
		     'category_id'	=>	'I4 NOTNULL DEFAULT 0',
		     'uid'		=>	'I4 NOTNULL DEFAULT 0',
		     'name'		=>	'C(64) NOTNULL DEFAULT \'\'',
		     'email'		=>	'C(128) NOTNULL DEFAULT \'\'',
		     'lang'		=>	'C(3) NOTNULL DEFAULT \'\'',
		     'type'		=>	'I1 NOTNULL DEFAULT 1', 	// text/html/web-html
		     'frequency'	=>	'I1 NOTNULL DEFAULT 1', 	// weekly/monthly/etc.
		     'active'		=>	'L NOTNULL DEFAULT 0', 		// user approval
		     'approved'		=>	'L NOTNULL DEFAULT 0',
		     'last_send_date'	=>	'T NULL DEFAULT NULL'
        );
    ObjectUtil::addStandardFieldsToTableDataDefinition ($columns_def);
    $pntables['newsletter_users_column_def'] = $columns_def;
    // TODO: Indexes


    $pntables['newsletter_archives'] = DBUtil::getLimitedTablename ('newsletter_archives'); 
    $columns = array (
    		     'id'		=>	'nla_id',
    		     'date'		=>	'nla_date',
    		     'lang'		=>	'nla_lang',
		     'text'		=>	'nla_text'
        );
    ObjectUtil::addStandardFieldsToTableDefinition ($columns, 'nla_');
    $pntables['newsletter_archives_column'] = $columns;
    $columns_def = array (
    		     'id'		=>	'I4 NOTNULL AUTO PRIMARY',
    		     'date'		=>	'T NOTNULL DEFAULT 0',
    		     'lang'		=>	'C(3) NOTNULL DEFAULT \'\'',
    		     'nPlugins'		=>	'I2 NOTNULL DEFAULT 0',
    		     'nItems'		=>	'I2 NOTNULL DEFAULT 0',
		     'text'		=>	'X NOTNULL DEFAULT \'\''
        );
    ObjectUtil::addStandardFieldsToTableDataDefinition ($columns_def);
    $pntables['newsletter_archives_column_def'] = $columns_def;


    // for upgrade to 2.0 only 
    $pntables['newsletter_archivestmp'] = DBUtil::getLimitedTablename ('newsletter_archives_tmp');
    $pntables['newsletter_archivestmp_column'] = $pntables['newsletter_archives_column'];
    $pntables['newsletter_archivestmp_column_def'] = $pntables['newsletter_archives_column_def'];
    $pntables['newsletter_archivesupg'] = $pntables['newsletter_archives'];
    $columns = array (
    		     'archive_date'	=>	'pn_archive_date',
    		     'archive_text'	=>	'pn_archive_text'
        );
    $pntables['newsletter_archivesupg_column'] = $columns;

    return $pntables;
}

