<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 *
 * @package scribite!
 * @license http://www.gnu.org/copyleft/gpl.html
 *
 * @author sven schomacker, modifier by florian schiessl, modified by dmm for newsletter
 * @version 2.1
 *
 * This plugin checks current version for Newslettermodule and will
 * check if a newer version is available for download.
 */

function smarty_function_versioncheck($params, &$smarty) 
{
    // check module version
    // some code based on work from Axel Guckelsberger - thanks for this inspiration
    $currentversion = pnModGetInfo(pnModGetIDFromName($params['module']));
    $currentversion = trim($currentversion['version']);
    
    // current version           
    $output = $currentversion;
    
    // get newest version number
    require_once('Snoopy.class.php');
    $snoopy = new Snoopy;
    $snoopy->fetchtext("http://www.ffneunkirchen.at/dev/Newsletter/".$params['module'].".txt");

    $newestversion = $snoopy->results;
    $newestversion = trim($newestversion);   
    if (!$newestversion) { 
      // newest version check not possible, so return only current version number
      echo($output." (installation is up to date)");
      return; 
    }  
    
    if ($currentversion != $newestversion) {
	// generate info link if new version is available
      $output .= " (<strong><a id=\"versioncheck\" href=\"javascript:showInfo('http://www.ffneunkirchen.at/dev/Newsletter/newsletter-info.txt')\">Please update! Latest release: ".$newestversion."</a></strong>)";
    }   
    echo($output);
    return; 
}      
