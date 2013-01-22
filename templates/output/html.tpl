﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={charset}" />
    <base href="{getbaseurl}" />
    <title>{gt text="Newsletter"}</title>
</head>
{if !$site_url}{getbaseurl assign='site_url'}{/if}
<style>
html,body { margin:0px; padding:0px; }

body#nl-body, div#nl-body
{
    font: normal 11px arial;
    color: #222;
    background-color:#eeeeee;
}

.border {border: 2px solid grey;}

#nl-wrapper
{
    margin:0px auto;
    font: normal 12px  arial;
    color: #444;
    width:600px;
}
#nl-wrapper a {
    font-size:12px; 
}
h1#nl-title {
    margin:0px; padding:7px 0px 0px;
    font-size:18px;
    color:#fff;
    display:block;
    text-align:center;
}
h2#nl-title-2 {
    margin:0px; 
    padding:20px 0px 0px;
    font-size:16px;
    color:#fff;
    display:block;
    text-align:center;
}
h4 {
    margin:0px; 
    padding:20px 0px 0px;
    font-size:12px;
    color:#fff;
    display:block;
    text-align:center;
}
#nl-wrapper p {
    padding:0px 5px;
}
.nl-content-wrapper
{
    padding: 5px 5px;
}
.nl-content-wrapper h1,
.nl-content-wrapper h2,
.nl-content-wrapper h3,
.nl-content-wrapper h4,
.nl-content-wrapper h5 { font-size:15px; color:#000; }

.nl-content-wrapper h1 { display:block; border-bottom:1px dashed #000; }

table.nl-new-members,
table.nl-new-subscribers,
.nl-content-wrapper div, 
.nl-content-wrapper p, 
#nl-wrapper p { color: #444; }


#nl-content-container
{
    width: 70%;
    margin: 30px auto;
}
.nl-content-wrapper a, 
#nl-wrapper a
{
    color: #7f0000;
    text-decoration: none;
}

table.nl-new-members, table.nl-new-subscribers { width: 100%; border-collapse:collapse; }
table.nl-new-members th, table.nl-new-subscribers th
{
    border-bottom: 1px solid #000;
    color: #000;
}
.nl-new-members, .nl-new-subscribers td { text-align: left; font:normal 12px arial; }

a img {
    border: none;
}

table.bg1 {
    background-color: #eeeeee;
}

table.bg2 {
    background-color: #ffffff;
}

td.footer {
    background-color: #eeeeee;
    padding: 10px 20px 10px 20px;
}

td.footer p {
    font-family: Arial;
    font-size: 11px;
    font-weight: normal;
    color: #333333;
    margin: 0;
    padding: 0;
}

td.footer p a {
    font-family: Arial;
    font-size: 11px;
    font-weight: normal;
    color: red;
}

td.body {
    padding: 0 20px 20px 20px;
    background-color: #ffffff;
}

td.buttons {
    padding: 20px 0 0 0; 
}

td.nl-main-class h2 {
    font-family: Arial;
    font-size: 16px;
    font-weight: bold;
    color: #812323;
    margin: 0;
    padding: 0;
}

td.nl-main-class h2 a {
    font-family: Arial;
    font-size: 16px;
    font-weight: bold;
    color: #812323;
    text-decoration: none;
    margin: 0;
    padding: 0;
}

td.nl-main-class h3 {
    font-family: Arial;
    font-size: 14px;
    font-weight: bold;
    color: #813939;
    margin: 0;
    padding: 0;
}

td.nl-main-class h3 a {
    font-family: Arial;
    font-size: 14px;
    font-weight: bold;
    color: #813939;
    text-decoration: none;
    margin: 0;
    padding: 0;
}

td.nl-main-class img.hr {
    margin: 0;
    padding: 0 0 10px 0;
}

td.nl-main-class img.hr-small {
    margin: 0;
    padding: 0 0 10px 0;
}

td.nl-main-class p {
    font-family: Arial;
    font-size: 13px;
    font-weight: normal;
    color: #333333;
    margin: 0 0 14px 0;
    padding: 0;
}

td.nl-main-class p a {
    font-family: Arial;
    font-size: 13px;
    font-weight: normal;
    color: #680606;
}

td.nl-main-class p.more a {
    font-family: Arial;
    font-size: 13px;
    font-weight: normal;
    color: #680606;
    text-decoration: none;
}

td.nl-main-class ul {
    font-family: Arial;
    font-size: 13px;
    font-weight: normal;
    color: #333333;
    margin: 0 0 14px 24px;
    padding: 0;
}

td.nl-main-class ul li a {
    font-family: Arial;
    font-size: 13px;
    font-weight: normal;
    color: #680606;
}

td.footer {
    padding: 0 20px 0 20px;
    height: 61px;
    vertical-align: middle;
}

td.footer p {
    font-family: Arial;
    font-size: 12px;
    font-weight: bold;
    color: #ffffff;
    line-height: 16px;
    margin: 0;
    padding: 0;
}

td.footer a {
    font-family: Arial;
    font-size: 12px;
    font-weight: bold;
    color: #680606;
    line-height: 16px;
    margin: 0;
    padding: 0;
}

table.nl-calendar {
    width:100%;
    line-height:1.2em;
    background-color:#fff;
    border-collapse:collapse;
    text-align:left;
    letter-spacing:.1em;
    margin-bottom:1em;
}

table.nl-calendar caption {
    padding-bottom:1em;
    text-align: center;
    font-size: 1.4em;
    font-weight: normal;
    text-transform: uppercase;
    letter-spacing:.6em;
    color: #333;
    background: transparent;
}

table.nl-calendar thead th, table.nl-calendar tfoot th, table.nl-calendar tfoot td {
    border-right: 1px solid #fff;
    padding-left:5px;
    font-variant:small-caps;
    letter-spacing:0.1em;
    color: #fff;
    background: #a0a0a0;
}

table.nl-calendar tfoot th, table.nl-calendar tfoot td {
    border-top: 2pt double #009;
}

table.nl-calendar td, table.nl-calendar th {
    padding:4px;
}

table.nl-calendar tbody tr th, table.nl-calendar tbody tr td {
    border-bottom: 1px dotted #467aa7;
}

table.nl-calendar tbody tr:hover td, table.nl-calendar tbody tr:hover th {
    border-bottom: 1px solid #467aa7;
}

table.nl-calendar tbody td a, table.nl-calendar tbody th a {
    background: transparent;
    text-decoration: none;
    border:none;
}

table.nl-calendar tbody td a:hover, table.nl-calendar tbody th a:hover {
    background: transparent;
}

table.nl-calendar .odd {
    background: #e3e3f4;
}

table.nl-calendar tbody tr:hover {
    color: #333;
    background: #e8eaec;
}

table.nl-calendar tbody tr th:hover {
    background:#fff;
}
</style>

  {lang assign="currLang"}
  {formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
  <body id="nl-body">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bg1">
        <tr>
          <td align="center">
         
            <table width="600" border="0" cellspacing="0" cellpadding="0" class="bg2">
              <tr>
                <td class="nl-header" align="left">
                  <img src="{$site_url}modules/Newsletter/images/newsletter_images/header.gif" width="600" height="101" />                 
                    <h2 id="nl-title-2">{$objectArray.title}</h2>
                 
                </td>
              </tr>
              <tr>
                <td valign="top" class="body">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td valign="top" class="nl-main-class" align="left">
  
                        {if (isset($user_name) && $user_name)}
                          <h2>{gt text="Hello"} {$user_name}</h2>
                          <br />
                        {/if}

                        {if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}
                          <h2>{gt text="Message from the Site Administrators"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          <p>{$objectArray.NewsletterMessage|safehtml}</p>
                          <br />
                        {/if}

                        {if (isset($objectArray.News) && $objectArray.News)}
                          <h2>{gt text="News"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {foreach from=$objectArray.News item="item" name="loop"}
                            <h3><a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}" title="{$item.title|safehtml}"><img src="{$site_url}modules/Newsletter/images/newsletter_images/next.gif" alt="Newsletter" width="12" height="12" />{$item.title|safehtml}</a></h3>
                            <br />
                            <p>{$item.hometext|safehtml|url_check}</p>
                            <p class="more"><a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
                            {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}

                        {if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
                          <h2>{gt text="Welcome New Members"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          <table class="nl-new-members">
                            <tr>
                              <th>{gt text="Username"}</th>
                              <th>{gt text="Register Date"}</th>
                            </tr>
                            {foreach from=$objectArray.NewMembers item="item" name="loop"}
                              <tr>
                                <td><h3><a href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">{$item.uname|safehtml}</a></h3></td>
                                <td>{$item.user_regdate}</td>
                              </tr>
                            {/foreach}
                          </table>
                          <br />
                        {/if}

                        {if (isset($objectArray.Content) && $objectArray.Content)}
                          <h2>{gt text="New Content Items"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {foreach from=$objectArray.Content item="item" name="loop"}
                            <h3><a href="{modurl modname="Content" type="user" func="view" pid=$item.id newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
                            {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}

                        {if (isset($objectArray.Pages) && $objectArray.Pages)}
                          <h2>{gt text="Recently Added Documents"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {foreach from=$objectArray.Pages item="item" name="loop"}
                            <h3><a href="{modurl modname="Pages" type="user" func="display" pid=$item.pageid newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
                            <div>{$item.content|safehtml}</div>
                            {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}
   
                        {if (isset($objectArray.EZComments) && $objectArray.EZComments)}        
                          <h2>{gt text="Latest Comments"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {foreach from=$objectArray.EZComments item="item" name="loop"}
                            <h3><a href="{$item.url}&newlang={$nllang}">{$item.subject}</a></h3>
                            <p>{$item.comment|safehtml|url_check|truncate:400}</p>
                            <p class="more"><a href="{$item.url}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
                             {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}
   
                        {if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}        
                          <h2>{gt text="Latest Forum Posts"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {foreach from=$objectArray.Dizkus item="item" name="loop"}
                            <h3><a href="{modurl modname="Dizkus" type="user" func="viewtopic" topic=$item.topic_id newlang=$nllang fqurl=true}">{$item.topic_title}</a></h3>
                             {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}

                        {if (isset($objectArray.Clip) && $objectArray.Clip)}
                          <h2>{gt text="Recently Added Publications"}</h2>
                          <img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr.gif" alt="Newsletter" width="560" height="3" />
                          {* TXT BASED LIST *}
                          {*
                          {foreach from=$objectArray.Pagemaster.txt key='tid' item="publist" name="loop"}
                            {foreach from=$publist item="item"}
                            <h3><a href="{modurl modname="Pagemaster" type="user" func="viewpub" tid=$tid pid=$item.core_pid newlang=$nllang fqurl=true}">{$item.core_title|safehtml}</a></h3>
                            {/foreach}
                          {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          *}

                          {* HTML OUTPUT LIST *}
                          {foreach from=$objectArray.Clip.htm key='tid' item="output" name="loop"}
                            {$output}
                          {if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
                          {/foreach}
                          <br />
                        {/if}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td valign="middle" align="left" class="footer">
                  <img src="{$site_url}modules/Newsletter/images/newsletter_images/footer-bg.gif" width="600" height="61" />                 
                  <p>
                    {gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe"} <a href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}"><strong>{gt text="here!"}</strong></a>
                  </p>
                  <p align="center">
                    <a href="{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}"><strong>{gt text="Link to the Newsletter Archive"}</strong></a>
                  </p>
                  
               </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>

