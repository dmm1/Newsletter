/*
	thanks to Dustin Diaz
	http://www.dustindiaz.com/check-one-check-all-javascript/
*/
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('subscriber_ids[]');
var sendChecked = document.getElementById('sendChecked');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 ){
		if ( chkAll.checked == true ){
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		} else {
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	} else {
		for ( i=0; i < boxLength; i++ ){
			if ( checks[i].checked == true ){
			allChecked = true;
			continue;
			} else {
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ ){
		if ( checks[j].checked == true )
		totalChecked++;
	}
	
	sendChecked.disabled = (totalChecked<1?true:false);
	sendChecked.value = "Send to ["+totalChecked+"] users";
}