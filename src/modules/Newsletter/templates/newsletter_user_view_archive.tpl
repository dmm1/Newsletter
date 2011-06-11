﻿{include file="newsletter_user_std_header.tpl"}

{gt text="View Archive" assign=pageTitle}
{pagesetvar name="title" value=$pageTitle}

{gt text="View Archive" assign=lblDetail}

<h3>{$pageTitle}</h3>

<fieldset>
  <legend>{gt text="Past Archives"}</legend>
	<ol id="filterlist" class="z-itemlist">
        <li class="z-itemheader z-clearfix">			
			{modgetvar assign="show_id" module="Newsletter" name="show_id" default="0"}
			{if ($show_id)}
			<span class="z-itemcell z-w05">	
			{gt text="ID"}
			</span>
			{/if} 
			{modgetvar assign="show_date" module="Newsletter" name="show_date" default="0"}
			{if ($show_date)}
			<span class="z-itemcell z-w15">			
			{gt text="Date"}
			</span>
			{/if} 
			{modgetvar assign="show_lang" module="Newsletter" name="show_lang" default="0"}
			{if ($show_lang)}
			<span class="z-itemcell z-w15">			
			{gt text="Language"}
			</span>
			{/if}
			{modgetvar assign="show_plugins" module="Newsletter" name="show_plugins" default="0"}
			{if ($show_plugins)} 
			<span class="z-itemcell z-w15">				 
			{gt text="# of Plugins"}
			</span>
			{/if}			
			<span class="z-itemcell z-w15">		  
			{modgetvar assign="show_objects" module="Newsletter" name="show_objects" default="0"}
			{if ($show_objects )}		 
			{gt text="# of Items"}
			</span>
			{/if} 
			<span class="z-itemcell z-w10">		
			{modgetvar assign="show_size" module="Newsletter" name="show_size" default="0"}
			{if ($show_size)}
			{gt text="Length"}	
			{/if}
			</span>
			<span class="z-itemcell z-w10">	
			{gt text="Action"}
			</span>
		<li class="{cycle values='z-odd,z-even'} z-clearfix">
		{foreach from=$objectArray key="key" item="archive"}
			{if ($show_id)}
			<span class="z-itemcell z-w05">        			
			{$archive.id}
			</span>
			{/if} 
			{if ($show_date)}
			<span class="z-itemcell z-w15"> 
			{$archive.date_display}
			</span>
			{/if} 		   
			{if ($show_lang)}
			<span class="z-itemcell z-w15"> 
			{$archive.lang}	
			</span>
			{/if} 
			{if ($show_plugins)}
			<span class="z-itemcell z-w15"> 
			{$archive.n_plugins}	
			</span>
			{/if} 
			{if ($show_objects)}
			<span class="z-itemcell z-w15"> 
			{$archive.n_items}
			</span>
		    {/if} 
			{if ($show_size)}
			<span class="z-itemcell z-w10"> 
			{$archive.text|strlen}
			</span>
			{/if} 
			<span class="z-itemcell z-w10"> 
			<a href="{modurl modname="Newsletter" type="user" func="detail" ot="archive" id=$archive.id}" target="_blank">{img src='demo.png' modname='core' set='icons/extrasmall' alt=$lblDetail altml='false' title=$lblDetail titleml='false'}</a>
			</span>
		</li>
		{/foreach}
	</ol>
      
</fieldset>
{include file="newsletter_std_footer.tpl"}
