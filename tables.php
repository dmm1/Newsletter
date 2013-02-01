<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright  Newsletter Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Newsletter
 * @subpackage User
 *
 * Please see the CREDITS.txt file distributed with this source code for further
 * information regarding copyright.
 */
 
function Newsletter_tables()
{
    $tables = array();

    $tables['newsletter_users'] = 'newsletter_users';
    $tables['newsletter_users_column'] = array (
        'id'             => 'nlu_id',
        'category_id'    => 'nlu_category_id',
        'uid'            => 'nlu_uid',
        'name'           => 'nlu_name',
        'email'          => 'nlu_email',
        'lang'           => 'nlu_lang',
        'type'           => 'nlu_type',      // text/html/web-html
        'frequency'      => 'nlu_frequency', // weekly/monthly/etc.
        'active'         => 'nlu_active',    // user approval
        'approved'       => 'nlu_approved',
        'last_send_date' => 'nlu_last_send_date',
        'last_send_nlid' => 'nlu_last_send_nlid'
    );
    $tables['newsletter_users_column_def'] = array (
        'id'             => 'I4 NOTNULL AUTO PRIMARY',
        'category_id'    => 'I4 NOTNULL DEFAULT 0',
        'uid'            => 'I4 NOTNULL DEFAULT 0',
        'name'           => "C(64) NOTNULL DEFAULT ''",
        'email'          => "C(128) NOTNULL DEFAULT ''",
        'lang'           => "C(3) NOTNULL DEFAULT ''",
        'type'           => 'I1 NOTNULL DEFAULT 1', // text/html/web-html
        'frequency'      => 'I1 NOTNULL DEFAULT 1', // weekly/monthly/etc.
        'active'         => 'L NOTNULL DEFAULT 0',  // user approval
        'approved'       => 'L NOTNULL DEFAULT 0',
        'last_send_date' => 'T NULL DEFAULT NULL',
        'last_send_nlid' => 'I4 NULL DEFAULT NULL'
    );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['newsletter_users_column'], 'nlu_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['newsletter_users_column_def']);
    // TODO: Indexes


    $tables['newsletter_archives'] = 'newsletter_archives';
    $tables['newsletter_archives_column'] = array (
        'id'        => 'nla_id',
        'date'      => 'nla_date',
        'lang'      => 'nla_lang',
        'n_plugins' => 'nla_n_plugins',
        'n_items'   => 'nla_n_items',
        'html'      => 'nla_html',
        'text'      => 'nla_text'
    );
    $tables['newsletter_archives_column_def'] = array (
        'id'        => 'I4 NOTNULL AUTO PRIMARY',
        'date'      => 'T NOTNULL DEFAULT 1970-01-01 00:00:00',
        'lang'      => "C(3) NOTNULL DEFAULT ''",
        'n_plugins' => 'I2 NOTNULL DEFAULT 0',
        'n_items'   => 'I2 NOTNULL DEFAULT 0',
        'html'      => "X NOTNULL DEFAULT ''",
        'text'      => "X NOTNULL DEFAULT ''"
    );

    ObjectUtil::addStandardFieldsToTableDefinition($tables['newsletter_archives_column'], 'nla_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($tables['newsletter_archives_column_def']);

    return $tables;
}
