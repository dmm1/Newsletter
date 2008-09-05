<?php
/**
 * Zikula Application Framework
 */
/**
 * Return an array of items to show in the your account panel
 *
 * @return   array   array of items, or false on failure
 */
function Newsletter_accountapi_getall($args)
{
    unset($args);

	$items = array(array('url' => pnModURL('Newsletter', 'user', 'main'),
                         'module' => 'Newsletter',
                         'set' => '',
                         'title' => pnML(Newsletter),
                         'icon' => 'admin.gif'));

    // Return the items
    return $items;
}
