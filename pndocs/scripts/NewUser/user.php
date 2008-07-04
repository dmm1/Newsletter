<?php
// File: $Id: user.php,v 1.25 2006/01/07 12:12:14 larsneo Exp $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of this file:
// Purpose of this file:  new user routines
// ----------------------------------------------------------------------
// Added calls to pnVarCleanFromInput since it's more secure than using $var[]. - Skooter

if (!defined('LOADED_AS_MODULE')) {
  die ('You can\'t access this file directly...');
}

$ModName = basename(dirname(__FILE__));

modules_get_language();

function newuser_user_underage()
{
    include 'header.php';

    OpenTable();
    echo '<h1>' . _SORRY . '</h1>';
    echo '<div>'. _MUSTBE . '</div>'
         . _CLICK . '<a href="index.php">' . _HERE . '</a> ' . _RETURN;
    CloseTable();
    include 'footer.php';
}

function newuser_user_check_age($var)
{
    $sitename = pnConfigGetVar('sitename');
    // new by class007
    include 'header.php';
    if (!pnConfigGetVar('reg_allowreg')) {
        echo '<h1>'._REGISTERDISABLED.'</h1>'
            .'<h2>'._REASONS.'</h2>'
            .'<div>'.pnVarPrepForDisplay(pnConfigGetVar('reg_noregreasons')) . '</div>';
        include 'footer.php';
        return false;
    }

    OpenTable();
    echo '<h1>' . _WELCOMETO . ' ' . pnVarPrepForDisplay($sitename).' '._REGISTRATION . '</h1>'
    . '<div>' . _MUSTBE . '</div>'
  . '<div><a href="user.php?op=register&amp;module=NewUser">'._OVER13.'</a></div><br />'
  . '<div>' . _CONSENT . '</div>'
  . '<div><a href="user.php?op=underage&amp;module=NewUser">'._UNDER13.'</a></div>'."\n";

    CloseTable();
    include 'footer.php';
}

function newuser_user_register()
{
    $size = 35;

    include 'header.php';
    // new by class007
    if (!pnConfigGetVar('reg_allowreg')) {
    echo '<strong>' . _REGISTERDISABLED . '</strong><br />' ;

        // show reasons only when entered
        $reg_noregreasons = pnConfigGetVar('reg_noregreasons');
        if (strlen(trim($reg_noregreasons)) > 0) {
        echo '<br /><strong>' . _REASONS . "</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;" . pnVarPrepForDisplay($reg_noregreasons) . '<br />';
        }

        include 'footer.php';
        return false;
    }

  // generate the form authorisation key
  // added to prevent multiple automated signups - markwest
    $authid = pnSecGenAuthKey();

    OpenTable();
  echo '<h1>' . _REGNEWUSER . '</h1>'
    .'<h2>' . _REGISTERNOW . '</h2>'
    ._WEDONTGIVE;
  CloseTable();

  OpenTable();
  echo '<form name="Register" action="user.php" method="post"><div>'."\n"
    .'<table cellpadding="5" cellspacing="0" border="0">'."\n"
    .'<tr>'."\n"
    .'<td style="width:25%" align="right"><strong><label for="uname_new_user">' . _NICKNAME . '</label></strong></td>'."\n"
    .'<td style="width:75%"><input type="text" name="uname" id="uname_new_user" size="'.pnVarPrepForDisplay($size).'" maxlength="25" tabindex="0" /></td>'."\n"
    .'</tr>'."\n";

    // new by class007. echo password area if admin do not want to verify email.
  if (!pnConfigGetVar('reg_verifyemail')) {
    echo '<tr>'."\n"
      .'<td align="right"><strong><label for="pass_new_user">' . _PASSWORD . '</label></strong></td>'."\n"
      .'<td><input type="password" name="pass" id="pass_new_user" size="'.pnVarPrepForDisplay($size).'" maxlength="20" tabindex="0" /></td>'
      .'</tr>'."\n"
      .'<tr>'."\n"
      .'<td align="right"><strong><label for="vpass_new_user">' . _PASSWDAGAIN . '</label></strong></td>'."\n"
      .'<td><input type="password" name="vpass" id="vpass_new_user" size="'.pnVarPrepForDisplay($size).'" maxlength="20" tabindex="0" /></td>'."\n"
      .'</tr>'."\n";
  }

  echo '<tr>'."\n"
    .'<td align="right"><strong><label for="email_new_user">' . _EMAIL . '</label></strong></td>'."\n"
    .'<td><input type="text" name="email" id="email_new_user" size="'.pnVarPrepForDisplay($size).'" maxlength="60" tabindex="0" /></td>'."\n"
    .'</tr>'."\n"
    .'<tr>'."\n"
    .'<td align="right"><strong><label for="vemail_new_user">' . _EMAILAGAIN . '</label></strong></td>'."\n"
    .'<td><input type="text" name="vemail" id="vemail_new_user" size="'.pnVarPrepForDisplay($size).'" maxlength="60" tabindex="0" /></td>'."\n"
    .'</tr>'."\n";

  // edit by class007
  if (pnConfigGetVar('reg_verifyemail')) {
    echo '<tr>'."\n"
      .'<td style="width:75px">&nbsp;</td>'."\n"
      .'<td><strong>' . _PASSWILLSEND . '</strong></td>'."\n"
      .'</tr>'."\n";
  }

  echo '<tr>'."\n"
    .'<td align="right"><strong>' . _OPTION . '</strong></td>'."\n"
    .'<td><input type="checkbox" name="user_viewemail" id="user_viewemail_new_user" value="1" tabindex="0" />'
    .'<label for="user_viewemail_new_user">' . _ALLOWEMAILVIEW . '</label></td>'
    .'</tr>'."\n";

  // Check for legal module
  if (pnModAvailable("legal")) {
    echo '<tr>'."\n"
      .'<td style="width:75px" align="right">&nbsp;</td>'."\n"
      .'<td><input type="checkbox" name="agreetoterms" id="agreetoterms_new_user" value="1" tabindex="0" />'
      .'<label for="agreetoterms_new_user">' . _REGISTRATIONAGREEMENT . ' <a href="'.pnVarPrepForDisplay(pnModURL('legal')).'">'
      . _TERMSOFUSE . '</a> ' . _ANDCONNECTOR . ' <a href="'.pnVarPrepForDisplay(pnModURL('legal', 'user', 'privacy')).'">'
      . _PRIVACYPOLICY . '</a></label></td>'
      .'</tr>'."\n";
  }
  
  if (pnModAvailable('Newsletter')) {
    echo '<tr>'."\n"
      .'<td style="width:75px" align="right">&nbsp;</td>'."\n"
      .'<td><input type="checkbox" name="newsletter_signup" id="newsletter_signup" value="1" tabindex="0" checked="checked" /> '._SIGNUP4NEWSLETTER.'</td>'
      .'</tr>'."\n";
  }

  echo '<tr>'."\n"
    .'<td align="right">&nbsp;</td>'."\n"
    .'<td>'."\n"
    .'<input type="hidden" name="module" value="NewUser" />'."\n"
    .'<input type="hidden" name="op" value="finishnewuser" />'."\n"
    .'<input type="hidden" name="authid" value="' . pnSecGenAuthKey() . '" />'."\n"
    .'<input type="submit" value="' . _NEWUSER . '" />'."\n"
    .'</td>'."\n"
    .'</tr>'."\n";

  echo '<tr>'."\n"
    .'<td>&nbsp;</td>'."\n"
    .'<td>' . _COOKIEWARNING . '</td>'."\n"
    .'</tr>'."\n";

  if (pnConfigGetVar('reg_optitems')) {
    echo '<tr>'
      .'<td>&nbsp;</td>'
      .'<td><strong>' . _ASREGUSER . '</strong><br />'."\n"
      .'<br />- ' . _ASREG1
      .'<br />- ' . _ASREG2
      .'<br />- ' . _ASREG3
      .'<br />- ' . _ASREG4
      .'<br />- ' . _ASREG5
      .'<br />- ' . _ASREG6
      .'<br />- ' . _ASREG7
      .'</td>'
      .'</tr>'."\n";

    echo '<tr>'
      .'<td>&nbsp;</td>'
      .'<td><strong>' . _OPTIONALITEMS . '</strong></td>'
      .'</tr>'."\n";
      // Display optional items to register
    optionalitems();
  }
  echo '</table>'."\n";
  echo '</div></form>'."\n";
  CloseTable();
  include 'footer.php';
}

function userCheck($uname, $email, $agreetoterms)
{
    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    $stop = '';
    
    // check for valid email
    $res = pnVarValidate($email, 'email');
    if ($res == false) {
        $stop = _ERRORINVEMAIL;
    }

    // check for valid uname
    $res2 = pnVarValidate($uname, 'uname');
    if ($res2 == false) {
        $stop = _NICK2LONG;
    }

    // check for some e-mail domains.
    list($foo, $maildomain) = split('@', $email);
    $maildomain = strtolower($maildomain);
    // get the list of banned domains
    $domains = pnConfigGetVar('reg_Illegaldomains');
    // fix any text formatting and convert to an array
    $domains = str_replace(', ', ',', $domains);
    $checkdomains = explode(',', $domains);
    // check if our main domain is amonsgt the banned list
    if (in_array($maildomain, $checkdomains)) {
       $stop = _EMAILINVALIDDOMAIN;
    }
    
    // Check for legal module
    if (pnModAvailable('legal')) {
        // If legal var agreetoterms checkbox not checked, value is 0 and results in error
        if ($agreetoterms == 0) {
            $stop = _ERRORMUSTAGREE;
        }
    }

    // check for valid username
    if (!$uname || stristr($uname,'&') || preg_match("/[[:space:]]/", $uname) || strip_tags($uname) != $uname) {
        $stop = _ERRORINVNICK;
    }

    // check for forbidden names
    $reg_illegalusername = trim(pnConfigGetVar('reg_Illegalusername'));
    if (!empty($reg_illegalusername)) {
        $usernames = explode(" ", $reg_illegalusername);
        $count = count($usernames);
        $pregcondition = "/((";
        for ($i = 0;$i < $count;$i++) {
            if ($i != $count-1) {
                $pregcondition .= $usernames[$i] . ")|(";
            } else {
                $pregcondition .= $usernames[$i] . "))/iAD";
            }
        }
        if (preg_match($pregcondition, $uname)) {
            $stop = _NAMERESERVED;
        }
    }

    // check if user already exists
    $column = &$pntable['users_column'];
    $existinguser =& $dbconn->Execute("SELECT $column[uname] FROM $pntable[users] WHERE $column[uname]='" . pnVarPrepForStore($uname) . "'");
    if (!$existinguser->EOF) {
        $stop = _NICKTAKEN;
    }
    $existinguser->Close();

    // check if email is unique (if wanted)
    if (pnConfigGetVar('reg_uniemail')) {
        $existingemail =& $dbconn->Execute("SELECT $column[email] FROM $pntable[users] WHERE $column[email]='" . pnVarPrepForStore($email) . "'");
        if (!$existingemail->EOF) {
            $stop = _EMAILREGISTERED;
        }
        $existingemail->Close();
    }

    return($stop);
}

function newuser_user_finishnewuser($var)
{
    include 'header.php';

    // confirm the form authorisation key
    // added to prevent multiple automated signups - markwest
    if (!pnSecConfirmAuthKey()) {
      echo _BADAUTHKEY;
      include 'footer.php';
      exit;
    }

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    list($agreetoterms,
    	 $newsletter_signup,
         $uname,
         $email,
         $femail,
         $vemail) = pnVarCleanFromInput('agreetoterms',
         								'newsletter_signup',
                                        'uname',
                                        'email',
                                        'femail',
                                        'vemail');

    $reg_verifyemail = pnConfigGetVar('reg_verifyemail');

    if (!$reg_verifyemail) {
        list($pass, $vpass) = pnVarCleanFromInput('pass', 'vpass');
    }

    $reg_optitems = pnConfigGetVar('reg_optitems');

    if ($reg_optitems) {
        list($name,
             $url,
             $bio,
             $user_avatar,
             $user_icq,
             $user_occ,
             $user_from,
             $user_intrest,
             $user_sig,
             $user_aim,
             $user_yim,
             $user_msnm,
             $user_viewemail,
             $timezoneoffset,
             $dynadata) = pnVarCleanFromInput('name',
                                              'url',
                                              'bio',
                                              'user_avatar',
                                              'user_icq',
                                              'user_occ',
                                              'user_from',
                                              'user_intrest',
                                              'user_sig',
                                              'user_aim',
                                              'user_yim',
                                              'user_msnm',
                                              'user_viewemail',
                                              'timezoneoffset',
                                              'dynadata');
    }

    $adminmail = pnConfigGetVar('adminmail');
    $sitename = pnConfigGetVar('sitename');
    $siteurl = pnGetBaseURL();
    $Default_Theme = pnConfigGetVar('Default_Theme');
    $commentlimit = pnConfigGetVar('commentlimit');
    $storynum = pnConfigGetVar('storyhome');
    if (!$reg_optitems) {
        // if we don't show optional items
        // we should set the timezone based on site settings
        // and clear all other optional items
        $timezoneoffset = pnConfigGetVar('timezone_offset');
        $name = '';
        $femail = '';
        $url = '';
        $bio = '';
        $user_avatar = 'blank.gif';
        $user_icq = '';
        $user_occ = '';
        $user_from = '';
        $user_intrest = '';
        $user_sig = '';
        $user_aim = '';
        $user_yim = '';
        $user_msnm = '';
        $dynadata = '';
    }
    $minpass = pnConfigGetVar('minpass'); //by class007

    $stop = userCheck($uname, $email, $agreetoterms);
    // add vpass and vemail check. by class007
    // TODO: remove it to userCheck() [class007]
    if ((!empty($pass)) && ("$pass" != "$vpass")) {
        $stop = _PASSDIFFERENT;
    } elseif (($pass != "") && (strlen($pass) < $minpass)) {
        $stop = _YOURPASSMUSTBE . " $minpass " . _CHARLONG;
    } elseif (empty($pass) && !$reg_verifyemail) {
        $stop = _PASSWDNEEDED;
    } elseif ("$email" != "$vemail") {
        $stop = _EMAILSDIFF;
    }

    $user_regdate = time();
    if (empty($user_avatar)) $user_avatar = 'blank.gif';
    if (empty($stop)) {
        if ($reg_verifyemail) {
            $makepass = makepass();
            $cryptpass = md5($makepass);
        } else {
            $makepass = $pass; // for welcome email. [class007]
            $cryptpass = md5($pass);
        }

    // some additional checks
    if (!empty($femail)) {
      $femail = preg_replace('/[^a-zA-Z0-9_@.-]/', '', $femail);
    }
    if (!empty($url)) {
      if (!eregi("^http://[\-\.0-9a-z]+", $url)) {
        $url = "http://" . $url;
      }
      $url = preg_replace('/[^a-zA-Z0-9_@.&#?;:\/-]/', '', $url);
    }
    // end of additional checks

        $uid = $dbconn->GenId($pntable['users']);
        $column = &$pntable['users_column'];
        $result =& $dbconn->Execute("INSERT INTO $pntable[users] ($column[name], $column[uname], $column[email],
                           $column[femail], $column[url], $column[user_avatar], $column[user_regdate], $column[user_icq],
                           $column[user_occ], $column[user_from], $column[user_intrest], $column[user_sig],
                           $column[user_viewemail], $column[user_theme], $column[user_aim], $column[user_yim],
                           $column[user_msnm], $column[pass], $column[storynum], $column[umode], $column[uorder],
                           $column[thold], $column[noscore], $column[bio], $column[ublockon], $column[ublock],
                           $column[theme], $column[commentmax], $column[counter], $column[timezone_offset])
                           VALUES ('" . pnVarPrepForStore($name) . "','" . pnVarPrepForStore($uname) . "',
                           '" . pnVarPrepForStore($email) . "','" . pnVarPrepForStore($femail) . "',
                           '" . pnVarPrepForStore($url) . "','" . pnVarPrepForStore($user_avatar) . "',
                           '" . pnVarPrepForStore($user_regdate) . "','" . pnVarPrepForStore($user_icq) . "',
                           '" . pnVarPrepForStore($user_occ) . "','" . pnVarPrepForStore($user_from) . "',
                           '" . pnVarPrepForStore($user_intrest) . "','" . pnVarPrepForStore($user_sig) . "',
                           '" . pnVarPrepForStore($user_viewemail) . "','',
                           '" . pnVarPrepForStore($user_aim) . "','" . pnVarPrepForStore($user_yim) . "',
                           '" . pnVarPrepForStore($user_msnm) . "','" . pnVarPrepForStore($cryptpass) . "',
                           '" . pnVarPrepForStore($storynum) . "','',0,0,0,'" . pnVarPrepForStore($bio) . "',0,'','',
                           '" . pnVarPrepForStore($commentlimit) . "', '0', '" . pnVarPrepForStore($timezoneoffset) . "')");

        if ($dbconn->ErrorNo() <> 0) {
            echo $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg() . '<br />';
            error_log ($dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg() . '<br />');
        } else {
            // get the generated id
            $uid = $dbconn->PO_Insert_ID($pntable['users'], $column['uid']);

            // Fix Bug [ #1347 ] New user registration doesn't save user's dynamic data fields
            if (!empty($dynadata) && is_array($dynadata)) {
                while (list($key, $val) = each($dynadata)) {
                    SaveDynadata($key, $val, $uid);
                }
            }

            // Let any hooks know that we have created a new item
            pnModCallHooks('item', 'create', $uid, 'uid');

            // Add user to group
            $column = &$pntable['groups_column'];
            $result =& $dbconn->Execute("SELECT $column[gid]
                                      FROM $pntable[groups]
                                      WHERE $column[name]='" . pnModGetVar('Groups', 'defaultgroup') . "'");
            if ($dbconn->ErrorNo() <> 0) {
                echo $dbconn->ErrorNo() . _GETGROUP . $dbconn->ErrorMsg() . '<br />';
                error_log ($dbconn->ErrorNo() . _GETGROUP . $dbconn->ErrorMsg() . '<br />');
            } else {
                if (!$result->EOF) {
                    list($gid) = $result->fields;
                    $result->Close();
                    $column = &$pntable['group_membership_column'];
                    $result =& $dbconn->Execute("INSERT INTO $pntable[group_membership] ($column[gid], $column[uid])
                                              VALUES (" . (int)pnVarPrepForStore($gid) . ", " . (int)pnVarPrepForStore($uid) . ")");
                    if ($dbconn->ErrorNo() <> 0) {
                        echo $dbconn->ErrorNo() . _CREATEGROUP . $dbconn->ErrorMsg() . '<br />';
                        error_log ($dbconn->ErrorNo() . _CREATEGROUP . $dbconn->ErrorMsg() . '<br />');
                    }
                }
                $message = _WELCOMETO . " $sitename ($siteurl)!\n\n" . _YOUUSEDEMAIL . " ($email) " . _TOREGISTER . " $sitename. " . _FOLLOWINGMEM . "\n\n" . _UNICKNAME . " $uname\n" . _UPASSWORD . " $makepass";
                $subject = _USERPASS4 . " $uname" . _USERPASS42 . "";
                // send the e-mail
                pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email, 'subject' => $subject, 'body' => $message));
                if (pnConfigGetVar('reg_notifyemail') != "") {
                    $email2 = pnConfigGetVar('reg_notifyemail');
                    $subject2 = _NOTIFYEMAILSUB;
                    $message2 = _NOTIFYEMAILCONT1 . "$uname" . _NOTIFYEMAILCONT2;
                    // send the e-mail
                    pnModAPIFunc('Mailer', 'user', 'sendmessage', array('toaddress' => $email2, 'subject' => $subject2, 'body' => $message2));
                }
                
                // AS GOOD A PLACE AS ANY..
                pnModAPIFunc('Newsletter','user','subscribe',array('user_id'=>$uid,
                													'user_name'=>$uname,
																	'user_email'=>$email,
																	'nl_type'=>_NEWSLETTER_TYPE,
																	'nl_frequency'=>_NEWSLETTER_FREQUENCY));
                
                OpenTable();
                echo '<h2>'._YOUAREREGISTERED.'</h2>';
                CloseTable();
            }
        }
    } else {
        echo '<h2>'.pnVarPrepForDisplay($stop).'</h2>';
    }
    include 'footer.php';
}

function optionalitems() {
     $size = 35;
     $dbconn =& pnDBGetConn(true);
     $pntable =& pnDBGetTables();

     //OpenTable();
     $propertytable = $pntable['user_property'];
     $propertycolumn = &$pntable['user_property_column'];

     $sql = "select " . $propertycolumn['prop_id'] . " AS prop_id, " . $propertycolumn['prop_label'] . " AS prop_label, " . $propertycolumn['prop_dtype'] . " AS prop_dtype, " . $propertycolumn['prop_length'] . " AS prop_length, " . $propertycolumn['prop_weight'] . " AS prop_weight, " . $propertycolumn['prop_validation'] . " AS prop_validation " . "FROM " . $propertytable . " " . "WHERE " . $propertycolumn['prop_weight'] . "!=0 ORDER BY " . $propertycolumn['prop_weight'];

     $result =& $dbconn->Execute($sql);

     $core_fields = array();

    $prop_label_text = "";
     while (!$result->EOF) {
         list($prop_id, $prop_label, $prop_dtype, $prop_length, $prop_weight, $prop_validation) = $result->fields;
         $result->MoveNext();
         // do not display email & fakeemail & password
         if ($prop_label != '_UREALEMAIL' && $prop_label != '_PASSWORD') {

           $prop_label_text = (defined($prop_label) ? constant($prop_label) : $prop_label);

          echo '<tr><td valign="top" align="right">' . pnVarPrepForDisplay($prop_label_text) . ':</td><td valign="top">';
             switch ($prop_dtype) {
                 case _UDCONST_MANDATORY;
                 case _UDCONST_CORE;
                     $core_fields[] = $prop_label;
                     switch ($prop_label) {
                         case '_UREALNAME':
                             echo '<input type="text" name="name" value="' . pnVarPrepForDisplay(pnUserGetVar('name')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="60" />';
                             break;
                         case '_UREALEMAIL':
                             // echo '<input type="text" name="email" value="' . pnVarPrepForDisplay(pnUserGetVar('email')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="60">'
                             // .'&nbsp;'._REQUIRED.'&nbsp;'._EMAILNOTPUBLIC.'</td>';
                             break;
                         case '_UFAKEMAIL':
                             echo '<input type="text" name="femail" value="' . pnVarPrepForDisplay(pnUserGetVar('femail')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="60" />';
                             break;
                         case '_YOURHOMEPAGE':
                             echo '<input type="text" name="url" value="' . pnVarPrepForDisplay(pnUserGetVar('url')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_TIMEZONEOFFSET':
                             $tzoffset = pnConfigGetVar('timezone_offset');
                             global $tzinfo;
                             echo '<select name="timezoneoffset">';
                             foreach ($tzinfo as $tzindex => $tzdata) {
                                 echo "\n".'<option value="'.pnVarPrepForDisplay($tzindex).'"';
                                 if ($tzoffset == $tzindex) {
                                     echo 'selected="selected"';
                                 }
                                 echo '>';
                                 echo pnVarPrepHTMLDisplay($tzdata);
                                 echo '</option>';
                             }
                             echo '</select>';
                             break;
                         case '_YOURAVATAR':
                             $user_avatar = pnUserGetVar('user_avatar');
                             echo '<select name="user_avatar" onchange="showimage()">';
                             $handle = opendir('images/avatar');
                             while ($file = readdir($handle)) {
                                 $filelist[] = $file;
                             }
                             asort($filelist);
                             while (list ($key, $file) = each ($filelist)) {
                                 ereg('.gif|.jpg', $file);
                                 if ($file != '.' && $file != '..' && $file != 'CVS' && $file != 'index.html') {
                                   echo '<option value="'.pnVarPrepForDisplay($file).'"';
                                     if ($file == "blank.gif") {
                                         echo 'selected="selected"';
                                     }
                                     echo '>'.pnVarPrepForDisplay($file).'</option>';
                                 }
                             }
                             echo '</select>&nbsp;&nbsp;<img src="images/avatar/blank.gif" name="avatar" width="32" height="32" alt="" />';
                             break;
                         case '_YICQ':
                             echo '<input type="text" name="user_icq" value="' . pnVarPrepForDisplay(pnUserGetVar('user_icq')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YAIM':
                             echo '<input type="text" name="user_aim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_aim')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YYIM':
                             echo '<input type="text" name="user_yim" value="' . pnVarPrepForDisplay(pnUserGetVar('user_yim')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YMSNM':
                             echo '<input type="text" name="user_msnm" value="' . pnVarPrepForDisplay(pnUserGetVar('user_msnm')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YLOCATION':
                             echo '<input type="text" name="user_from" value="' . pnVarPrepForDisplay(pnUserGetVar('user_from')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YOCCUPATION':
                             echo '<input type="text" name="user_occ" value="' . pnVarPrepForDisplay(pnUserGetVar('user_occ')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_YINTERESTS':
                             echo '<input type="text" name="user_intrest" value="' . pnVarPrepForDisplay(pnUserGetVar('user_intrest')) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                             break;
                         case '_SIGNATURE':
                             echo '<textarea cols="80" rows="10" name="user_sig">' . pnVarPrepForDisplay(pnUserGetVar('user_sig')) . '</textarea><br />'
                                   . _OPTIONAL . '&nbsp;' . _255CHARMAX . '<br />' . _ALLOWEDHTML . '<br />';
                             $AllowableHTML = pnConfigGetVar('AllowableHTML');
                             while (list($key, $access,) = each($AllowableHTML)) {
                                 if ($access > 0) echo " &lt;" . $key . "&gt;";
                             }
                             break;
                         case '_EXTRAINFO':
                             echo '<textarea cols="80" rows="10" name="bio">' . pnVarPrepForDisplay(pnUserGetVar('bio')) . '</textarea>&nbsp;<br />' . _CANKNOWABOUT;
                             break;

                         case '_PASSWORD':
                             break;
                         default:
                             echo "Undefined $prop_id, $prop_label, $prop_dtype, $prop_length, $prop_weight, $prop_validation";
                     }
                     break;

                 case _UDCONST_STRING:
                     if (empty($prop_length)) $prop_length = 30;
                     echo '<input type="text" name="dynadata['.pnVarPrepForDisplay($prop_label).']" value="' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="'.pnVarPrepForDisplay($prop_length).'" />';
                     break;

                 case _UDCONST_TEXT:
                     echo '<textarea wrap="virtual" cols="80" rows="10" name="dynadata['.pnVarPrepForDisplay($prop_label).']">' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '</textarea>';
                     break;

                 case _UDCONST_FLOAT:
                 case _UDCONST_INTEGER:
                     echo '<input type="text" name="dynadata['.pnVarPrepForDisplay($prop_label).']" value="' . pnVarPrepForDisplay(pnUserGetVar($prop_label)) . '" size="'.pnVarPrepForDisplay($size).'" maxlength="100" />';
                     break;
             }
             echo '</td></tr>';
         }
     }
}

// Fix Bug [ #1347 ] New user registration doesn't save user's dynamic data fields
function SaveDynadata($name, $value, $uid)
{

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if (empty($name)) {
        return false;
    }

    if (empty($uid)) {
        return false;
    }

    $propertiestable = $pntable['user_property'];
    $datatable = $pntable['user_data'];
    $propcolumns = &$pntable['user_property_column'];
    $datacolumns = &$pntable['user_data_column'];

    // Confirm that this is a known value
    $query = "SELECT $propcolumns[prop_id],
                     $propcolumns[prop_dtype]
              FROM   $propertiestable
              WHERE  $propcolumns[prop_label] = '" . pnVarPrepForStore($name) ."'";

    $result =& $dbconn->Execute($query);

    if ($result->EOF) {
        return false;
    }

    list ($id, $type) = $result->fields;
    // check for existence of the variable in user data
    $query = "SELECT $datacolumns[uda_id]
              FROM   $datatable
              WHERE  $datacolumns[uda_propid] = '" . (int)pnVarPrepForStore($id) ."'
              AND    $datacolumns[uda_uid]    = '" . (int)pnVarPrepForStore($uid) ."'";
    $result =& $dbconn->Execute($query);

    // jgm - this won't work in databases that care about typing
    // but this should get fixed when we move to the dynamic user
    // variables setup
    // TODO: do some checking with $type to maybe do conditional sql
    if ($result->EOF) {
       // record does not exist
       $query = "INSERT INTO $datatable
                            ($datacolumns[uda_propid],
                             $datacolumns[uda_uid],
                             $datacolumns[uda_value])
                 VALUES     ('".(int)pnVarPrepForStore($id)."',
                             '".(int)pnVarPrepForStore($uid)."',
                             '".pnVarPrepForStore($value)."')";

       $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0) {
            return false;
        }
    } else {
        // existing record
        $query = "UPDATE $datatable
                 SET     $datacolumns[uda_value]  = '" . pnVarPrepForStore($value) . "'
                 WHERE   $datacolumns[uda_propid] = '" . (int)pnVarPrepForStore($id) ."'
                 AND     $datacolumns[uda_uid]    = '" . (int)pnVarPrepForStore($uid) ."'";

        $dbconn->Execute($query);

        if($dbconn->ErrorNo() != 0) {
            return false;
        }
    }

    return true;

}

?>