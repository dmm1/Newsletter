<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{gt text="Newsletter"}</title>
{if !isset($site_url)}{assign var='site_url' value=$baseurl}{/if}
<base href="{$site_url}" />
<link rel="stylesheet" type="text/css" href="{$site_url}modules/Newsletter/style/html.css" >
<style>
/* ------------------------------------- 
		GLOBAL 
------------------------------------- */
* { 
	margin:0;
	padding:0;
}
* { font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; }

img { 
	max-width: 100%; 
}
.collapse {
	margin:0;
	padding:0;
}
body {
	-webkit-font-smoothing:antialiased; 
	-webkit-text-size-adjust:none; 
	width: 100%!important; 
	height: 100%;
}

/* ------------------------------------- 
		ELEMENTS 
------------------------------------- */
a { color: #2BA6CB;}
p{font-size:14px;}
.btn {
	text-decoration:none;
	color: #4291bf;
	background-color: #fff;
	padding:8px 12px;
	font-weight:bold;
	margin-right:10px;
	text-align:center;
	cursor:pointer;
	display: inline-block;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	}

p.callout {
	background-color:#ECF8FF;
	margin-bottom: 15px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
}
.callout a {
	font-weight:bold;
	color: #2BA6CB;
}

.title	{
	background-color:#a8defd;
	padding-left:12px;
	margin-bottom:-14px;
	-webkit-border-radius: 4px 4px 0px 0px;
	border-radius: 4px 4px 0px 0px;
}
.title h2{color:#fff;}

table.social {
/* 	padding:15px; */
	background-color: #ebebeb;
	
}
.social .soc-btn {
	padding: 3px 7px;
	font-size:12px;
	margin-bottom:10px;
	text-decoration:none;
	color: #FFF;font-weight:bold;
	display:block;
	text-align:center;
}
a.fb { background-color: #3B5998!important; }
a.tw { background-color: #1daced!important; }
a.gp { background-color: #DB4A39!important; }
a.ms { background-color: #000!important; }

/* ------------------------------------- 
		HEADER 
------------------------------------- */
table.head-wrap { width: 100%;}

.header.container table td.logo { padding: 15px; }
.header.container table td.label { padding: 15px; padding-left:0px;}


/* ------------------------------------- 
		BODY 
------------------------------------- */
table.body-wrap { width: 100%;}


/* ------------------------------------- 
		FOOTER 
------------------------------------- */
table.footer-wrap { width: 100%;	clear:both!important;
}
.footer-wrap .container td.content  p { border-top: 1px solid rgb(215,215,215); padding-top:15px;}
.footer-wrap .container td.content p {
	font-size:10px;
	font-weight: bold;
	
}


/* ------------------------------------- 
		TYPOGRAPHY 
------------------------------------- */
h1,h2,h3,h4,h5,h6 {
font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; line-height: 1.1; margin-bottom:15px; color:#000;
}
h1 small, h2 small, h3 small, h4 small, h5 small, h6 small { font-size: 60%; color: #6f6f6f; line-height: 0; text-transform: none; }

h1 { font-weight:200; font-size: 44px;}
h2 { font-weight:200; font-size: 31px;}
h3 { font-weight:500; font-size: 27px;}
h4 { font-weight:500; font-size: 23px;}
h5 { font-weight:900; font-size: 17px;}
h6 { font-weight:900; font-size: 14px; text-transform: uppercase; color:#444;}

.collapse { margin:0!important;}

p, ul { 
	margin-bottom: 10px; 
	font-weight: normal; 
	font-size:14px; 
	line-height:1.6;
}
p.lead { font-size:17px; }
p.last { margin-bottom:0px;}

ul li {
	margin-left:5px;
	list-style-position: inside;
}

/* --------------------------------------------------- 
		RESPONSIVE
------------------------------------------------------ */

/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
	display:block!important;
	max-width:600px!important;
	margin:0 auto!important; /* makes it centered */
	clear:both!important;
}

/* This should also be a block element, so that it will fill 100% of the .container */
.content {
	padding:15px;
	max-width:600px;
	margin:0 auto;
	display:block; 
	background-color:#ECF8FF;
	-webkit-border-radius: 0px 0px 4px 4px;
	border-radius: 0px 0px 4px 4px;
}

/* Let's make sure tables in the content area are 100% wide */
.content table { width: 100%; }


/* Odds and ends */
.column {
	width: 300px;
	float:left;
}
.column tr td { padding: 15px; }
.column-wrap { 
	padding:0!important; 
	margin:0 auto; 
	max-width:600px!important;
}
.column table { width:100%;}
.social .column {
	width: 280px;
	min-width: 279px;
	float:left;
}

/* Be sure to place a .clear element after each set of columns, just to be safe */
.clear { display: block; clear: both; }


/* ------------------------------------------- 
		Mobile-Tablets
-------------------------------------------- */
@media only screen and (max-width: 600px) {
	
	a[class="btn"] { display:block!important; margin-bottom:10px!important; background-image:none!important; margin-right:0!important;}

	div[class="column"] { width: auto!important; float:none!important;}
	
	table.social div[class="column"] {
		width:auto!important;
	}

}
</style>

</head>
{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang} 
<body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

<!-- HEADER -->
<table class="head-wrap" bgcolor="#a8defd" style="width: 100%;">
	<tr>
		<td></td>
		<td class="header container" align="" bgcolor="#a8defd" style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#a8defd;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
			
			<!-- /content -->
			<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#a8defd;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
				<table bgcolor="#a8defd" >
					<tr>
						<td><h6 class="collapse" style="color:#fff;font-weight:900; font-size: 16px; text-transform: uppercase;"><center>{$objectArray.title}</center></h6></td>
					</tr>
				</table>
			</div><!-- /content -->
			
		</td>
		<td></td>
	</tr>
</table><!-- /HEADER -->

<!-- BODY -->
<table class="body-wrap" bgcolor="">
	<tr>
		<td></td>
		<td class="container" align="" bgcolor="#FFFFFF">
            <br />
			<!-- content -->
			{if (isset($user_name) && $user_name)}
			<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
			    <table bgcolor="">
				    <tr>
					    <td>
						    <p class="callout"><center><strong>{gt text="Hello"} {$user_name}</strong></center></p>
					    </td>
				    </tr>
			    </table>
			</div>
			 {/if}
			<!-- /content -->
			<!-- content -->
			{if (isset($objectArray.Newsletter_NewsletterPlugin_NewsletterMessage) && $objectArray.Newsletter_NewsletterPlugin_NewsletterMessage)}
			<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
			<table bgcolor="">
				<tr>
					<td>
						<p class="callout">{$objectArray.Newsletter_NewsletterPlugin_NewsletterMessage|safehtml}</p>
					</td>
				</tr>
			</table></div>
			{/if}
			
            {assign var='includeFile' value='output/items/html.tpl'}
            {nlActivePlugins assign='plugins'}
            {foreach from=$plugins item='plugin'}
                {if $plugin != 'Newsletter_NewsletterPlugin_NewsletterMessage'}
                    {include file=$includeFile plugin=$plugin}
                {/if}
            {/foreach}
			<div class="content" style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 0px 0px 4px 4px;border-radius: 0px 0px 4px 4px;">
				<table bgcolor="" width="100%">
					<tr>
						<td>
							<table width="100%" bgcolor="" class="social" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;" width="100%">
								<tr>
									<td>
										{if $modvars.Newsletter.contact_facebook or $modvars.Newsletter.contact_twitter or $modvars.Newsletter.contact_google}
										<!--- column 1 -->
										<div class="column" style="width: 280px;min-width: 279px;">
											<table bgcolor="" cellpadding="" align="left">
                                                <tr>
                                                    <td>
												<h5 style="color:#000;">{gt text='Connect with Us'}:</h5>
												<p class="">
													{if $modvars.Newsletter.contact_facebook}<a href="{$modvars.Newsletter.contact_facebook|safetext}" class="soc-btn fb" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#3B5998!important;">Facebook</a> {/if}
													{if $modvars.Newsletter.contact_twitter}<a href="{$modvars.Newsletter.contact_twitter|safetext}" class="soc-btn tw" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#1daced!important;">Twitter</a> {/if}
													{if $modvars.Newsletter.contact_google}<a href="{$modvars.Newsletter.contact_google|safetext}" class="soc-btn gp" style="padding: 3px 7px;font-size:12px;margin-bottom:10px;text-decoration:none;color: #FFF;font-weight:bold;display:block;text-align:center;background-color:#DB4A39!important;">Google+</a> {/if}
												</p>
                                                    </td>
                                                </tr>
                                            </table><!-- /column 1 -->
										</div>
                                        {/if}
										{if $modvars.Newsletter.contact_phone or $modvars.Newsletter.contact_email}
										<!--- remove section if not needed -->
										<div class="column" style="width: 280px;min-width: 279px;">
											<table bgcolor="" cellpadding="" align="right">
                                                <tr>
                                                    <td>				
												<h5 style="color:#000;">{gt text='Contact Information'}:</h5>												
												<p style="color:#000;">{if $modvars.Newsletter.contact_phone}{gt text='Phone'}: <strong>{$modvars.Newsletter.contact_phone|safetext}</strong><br/>{/if}
												{if $modvars.Newsletter.contact_email}{gt text='Email'}: <strong><a href="emailto:me@you.com">{$modvars.Newsletter.contact_email|safetext}</a></strong>{/if}</p>
                                                    </td>
                                                </tr>
                                            </table><!-- /social section -->	
										</div>
										{/if}
										<div class="clear"></div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div><!-- /social & contact -->

		</td>
		<td></td>
	</tr>
</table><!-- /BODY -->

<!-- FOOTER -->
<table class="footer-wrap">
	<tr>
		<td></td>
		<td class="container">
			
				<!-- content -->
				<div class="content">
					<table>
						<tr>
							<td align="center">
								<p>
									<a href="{modurl modname="Newsletter" type="user" func="main" ot="archive" lang=$nllang fqurl=true}"><strong>{gt text="Archive"}</strong></a> |
									<a href="{modurl modname="Newsletter" type="user" func="main" ot="tos" lang=$nllang fqurl=true}"><strong>{gt text="Terms of Service"}</strong></a> |
									<a href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" lang=$nllang fqurl=true}"><strong>{gt text="Unsubscribe"}</strong></a>
								</p>
							</td>
						</tr>
					</table>
				</div><!-- /content -->
				
		</td>
		<td></td>
	</tr>
</table><!-- /FOOTER -->

</body>
</html>
