<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{if !$site_url}{assign var='site_url' value=$baseurl}{/if}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width initial-scale = 1, user-scalable = no" />
<base href="{$site_url}" />
<title>{gt text="Newsletter"}</title>
</head>
{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}
<body style="background: #DDDDDD; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; color: #666; text-align: center; margin: 0; padding: 0;">

<table border="0" cellspacing="0" cellpadding="0" bgcolor="#DDDDDD"  style="width: 100%; background: #DDDDDD;">
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" align='center'  style="width: 100%; padding: 10px;">
				<tr>
					<td>
						<div style="max-width: 600px; margin: 0 auto; overflow: hidden;">
							<table border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff"  style="width: 100%; background-color: #fff; text-align: left; margin: 0 auto; max-width: 1024px; min-width: 320px;">
								<tr>
									<td>
										<table border="0" cellspacing="0" cellpadding="0" height="8" style="width: 100%; background-color: #43A4D0; height: 8px;">
											<tr>
												<td></td>
											</tr>
										</table>

										<table border="0" cellspacing="0" cellpadding="0"  style="margin: 0 0 5px 0; font-size: 1.8em; color: #0088cc; background-color: #EFEFEF; padding: 0; border-bottom: 1px solid #DDD; width: 100%;">
											<tr>
												<td>
													<h2 style="padding: 0px; margin: 5px 20px !important; font-size: 16px !important; line-height: 1; font-weight: normal; color: #464646; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; margin: .4em 0 .3em 0; font-size: 1.8em;">
														<strong>{$objectArray.title}</strong>												</h2>
												</td>
												<!-- Use Username or replace it with 32x32 Logo -->																								
												<td style="text-align: right;">
												<!-- <img border="0" src="{$site_url}modules/Newsletter/images/newsletter_images/logo.png" alt=''  style="margin: 5px 20px 5px 0; vertical-align: middle; vertical-align: middle;"> -->
												{if (isset($user_name) && $user_name)}
												<h2>{gt text="Hello"} {$user_name}</h2>	
												{/if}
												</td> 
												
											</tr>
										</table>

										<table style="width: 100%;"  border="0" cellspacing="0" cellpadding="20" bgcolor="#ffffff">
											<tr>
												<td>
													<table style="width: 100%;"  border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td valign="top">
															
															{if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}
																<div style="padding:15px;max-width:600px;margin:0 auto;display:block;background-color:#ECF8FF;-webkit-border-radius: 6px 6px 6px 6px;border-radius: 6px 6px 6px 6px;">
																	<table bgcolor="">
																		<tr>
																			<td>
																				<p>{$objectArray.NewsletterMessage|safehtml}</p>					</p>
																			</td>
																		</tr>
																	</table>
																</div>
																<div style="margin-top: 1em; max-width: 560px;"></div>
															{/if}
																		<!-- Plugin News Start -->
																		{if (isset($objectArray.News) && $objectArray.News)}
																		<h2>{gt text='News'}</h2>
																		{foreach from=$objectArray.News item="item" name="loop"}
																		<table style="width: 100%;"  border="0" cellspacing="0" cellpadding="0">
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		<tr>
																			{if $modvars.News.picupload_enabled AND $item.pictures gt 0}
																			<td style="width: 60px !important; white-space: nowrap; vertical-align: top;">
																				<a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true} style="text-decoration: none; color: #0088cc; text-decoration: underline; color: #2585B2; display: block; margin-right: 10px;""><!--[*<span></span>*]--><img src="{$site_url}{$modvars.News.picupload_uploaddir}/pic_sid{$item.sid}-0-thumb2.jpg" alt="" /></a>
																			</td>
																			{/if}
																			<td>
																				<h2 style="margin: 0; font-size: 1.6em; color: #555; margin: 0; font-size: 1.6em; color: #555; margin: .4em 0 .3em 0; font-size: 1.8em; font-size: 20px;{if $modvars.News.picupload_enabled AND $item.pictures gt 0}margin-left:8px;{/if}">
																				<a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}" title="{$item.title|safehtml}">{$item.title|safehtml}</a>
																				</h2>
																				
																				<p style="padding:8px;{if $modvars.News.picupload_enabled AND $item.pictures gt 0}margin-left:8px;{/if}">{$item.hometext|trim|safehtml|url_check}</p>
																				<p style="font-size: 14px; line-height: 1.4em; color: #444444; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; margin: 0 0 1em 0; font-size: 14px; padding: 0; color: #666; padding-bottom: 0em; margin-bottom: 0; padding-left: 12px;">
																				<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; width: auto;margin-left:12px;">
																				<tr>
																				<td><a href="{modurl modname="News" type="user" func="display" sid=$item.sid newlang=$nllang fqurl=true}" style="-moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px; border: 1px solid #11729E; text-decoration: none; color: #fff; background-color: #2585B2; padding: 5px 15px; font-size: 16px; line-height: 1.4em; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-weight: normal; margin-left: 0; white-space: nowrap;">
																				{gt text="read more"}</a>
																				</td>																
																																				</tr>
																			</table>
																			</td>
																		</tr>
																		</div>
																		</table>
																		{/foreach}
																		
																		{/if}
																		<!-- Plugin News End -->
																		<!-- Plugin New Members Start -->
																		{if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
																		<h2>{gt text="Welcome New Members"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		
																		<table class="nl-new-members">
																			<tr>
																				<th>{gt text="Username"}</th>
																				<th>{gt text="Register Date"}</th>
																			</tr>
																		{modavailable modname="Profile" assign="profileAvailable"}
																		{foreach from=$objectArray.NewMembers item="item" name="loop"}
																		<tr>
																			<td>{if $profileAvailable}<h3><a href="{modurl modname="Profile" type="user" func="view" uid=$item.uid newlang=$nllang fqurl=true}">{/if}{$item.uname|safehtml}{if $profileAvailable}</a></h3>{/if}</td>
																			<td>{$item.user_regdate}</td>
																		</tr>
																		{/foreach}
																		</table>
																		</div>
																		{/if}
																		<!-- Plugin New Members End -->

																		{if (isset($objectArray.Content) && $objectArray.Content)}
																		<h2>{gt text="New Content Items"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		{foreach from=$objectArray.Content item="item" name="loop"}
																		<h3><a href="{modurl modname="Content" type="user" func="view" pid=$item.id newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach} 
																		</div>
																		{/if}

																		{if (isset($objectArray.Pages) && $objectArray.Pages)}
																		<h2>{gt text="Recently Added Documents"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		{foreach from=$objectArray.Pages item="item" name="loop"}
																		<h3><a href="{modurl modname="Pages" type="user" func="display" pageid=$item.pageid newlang=$nllang fqurl=true}">{$item.title|safehtml}</a></h3>
																		<p>{$item.content|nlTreatContent:'Pages'}</p>
																		<p class="more"><a href="{modurl modname="Pages" type="user" func="display" pageid=$item.pageid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach} 
																		</div>
																		{/if}

																		{if (isset($objectArray.EZComments) && $objectArray.EZComments)}        
																		<h2>{gt text="Latest Comments"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		{foreach from=$objectArray.EZComments item="item" name="loop"}
																		<h3><a href="{$item.url}&newlang={$nllang}">{$item.subject}</a></h3>
																		<p>{$item.comment|nlTreatContent:'EZComments'}</p>
																		<p class="more"><a href="{$item.url}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach}
																		</div>
																		{/if}

																		{if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}        
																		<h2>{gt text="Latest Forum Posts"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		{foreach from=$objectArray.Dizkus item="item" name="loop"}
																		<h3><a href="{modurl modname="Dizkus" type="user" func="viewtopic" topic=$item.topic_id newlang=$nllang fqurl=true}">{$item.topic_title}</a></h3>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach}
																		</div>
																		{/if}

																		{if (isset($objectArray.Clip) && $objectArray.Clip)}
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		<h2>{gt text="Recently Added Publications"}</h2>
																		{foreach from=$objectArray.Clip item="item" name="loop"}
																		<h3><a href="{modurl modname="Clip" type="user" func="viewpub" tid=$item.core_tid pid=$item.core_pid newlang=$nllang fqurl=true}">{$item.core_title|safehtml}</a></h3>
																		<p>{$item.content|nlTreatContent:'Clip'}</p>
																		<p class="more"><a href="{modurl modname="Clip" type="user" func="viewpub" tid=$item.core_tid pid=$item.core_pid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach}
																		</div>
																		{/if}

																		{if (isset($objectArray.Weblinks) && $objectArray.Weblinks)}        
																		<h2>{gt text="Latest web links"}</h2>
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">
																		{foreach from=$objectArray.Weblinks item="item" name="loop"}
																		<h3><a href="{$item.url}">{$item.title}</a></h3>
																		<p>{$item.description|nlTreatContent:'Weblinks'}</p>
																		<p class="more"><a href="{modurl modname="Weblinks" type="user" func="viewlinkdetails" lid=$item.lid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach}
																		</div>
																		{/if}

																		{if (isset($objectArray.Downloads) && $objectArray.Downloads)}
																		<div style="color: #999; font-size:14px; margin-top: 4px; margin-bottom:8px; color: #999; border-bottom: 1px solid #eee; overflow: hidden">					
																		<h2>{gt text="Latest downloads"}</h2>
																		{foreach from=$objectArray.Downloads item="item" name="loop"}
																		<h3><a href="{modurl modname="Downloads" type="user" func="display" lid=$item.lid newlang=$nllang fqurl=true}">{$item.title}</a></h3>
																		<p>{$item.description|nlTreatContent:'Downloads'}</p>
																		<p class="more"><a href="{modurl modname="Downloads" type="user" func="display" lid=$item.lid newlang=$nllang fqurl=true}">{gt text="read more"}</a> <img src="{$site_url}modules/Newsletter/images/newsletter_images/read-more.gif" alt="Header" width="8" height="8" /></p>
																		{if (!$smarty.foreach.loop.last)}<img class="hr" src="{$site_url}modules/Newsletter/images/newsletter_images/hr-small.gif" alt="Newsletter" width="560" height="2" />{/if}
																		{/foreach}
																		</div>
																		{/if}
																
																
																
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
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
			</div>
										<table border="0" cellspacing="0" cellpadding="20" bgcolor="#efefef"  style="width: 100%; background-color: #efefef; text-align: left; border-top: 1px solid #dddddd;">
											<tr>
												<td style="border-top: 1px solid #f3f3f3; color: #888; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; background: #efefef;">
													<p style="font-size: 14px; line-height: 1.4em; color: #444444; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; margin: 0 0 1em 0; font-size: 12px; line-height: 1.4em; margin: 0px 0px 10px 0px;">
														{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe"} <a href="{modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}"><strong>{gt text="here"}</strong></a></p>

													<p style="font-size: 14px; line-height: 1.4em; color: #444444; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; margin: 0 0 1em 0; font-size: 12px; line-height: 1.4em; margin: 0px 0px 0px 0px;">
													 <a href="{modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}"><strong>{gt text="Link to the Newsletter Archive"}</strong></a>														
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<table border="0" cellspacing="0" cellpadding="0" height="3" style="width: 100%; background-color: #43A4D0; height: 3px;">
								<tr>
									<td></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>		

			
			<br />
		</td>
	</tr>
</table>

</body>
</html>
