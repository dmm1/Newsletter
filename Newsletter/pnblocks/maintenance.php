<?php

function Newsletter_maintenanceblock_init()
{
    pnSecAddSchema('Newsletter:maintenanceblock:', 'Block title::');
}

function Newsletter_maintenanceblock_info()
{
    return array('text_type' => 'maintenance',
                 'module' => 'Newsletter',
                 'text_type_long' => 'Newsletter Maintenance Block',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

function Newsletter_maintenanceblock_display($blockinfo)
{
    if (!pnSecAuthAction(0, 'Newsletter:maintenanceblock:', "$blockinfo[title]::", ACCESS_READ)) {
        return;
    }

	if (!pnModAvailable('Newsletter')) {
		return;
	}
	
	$today = date('w');	
	$send_day = pnModGetVar('Newsletter','send_day');
	if($send_day == $today){	
		pnModAPIFunc('Newsletter','user','send_newsletters');
		// prune on send day before noon	
		if(date('G')<=12){
			pnModAPIFunc('Newsletter','user','prune_archives');
		}
	}
	
	$blockinfo=array();
    return themesideblock($blockinfo);
}

?>