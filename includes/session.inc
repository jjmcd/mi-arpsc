<?php
/* $Revision: 2.0 $ */
 
    
#include('config.php');
        
if (isset($SessionSavePath)){
	session_save_path($SessionSavePath);
}
    

function prnMsg($msg,$type='info', $prefix=''){

	echo '<P>' . getMsg($msg, $type, $prefix) . '</P>';

}//prnMsg

function getMsg($msg,$type='info',$prefix=''){
	$Colour='';
	switch($type){
		case 'error':
			$Colour='red';
			$prefix = $prefix ? $prefix : _('ERROR') . ' ' ._('Message Report');
			break;
		case 'warn':
			$Colour='maroon';
			$prefix = $prefix ? $prefix : _('WARNING') . ' ' . _('Message Report');
			break;
		case 'success':
			$Colour='darkgreen';
			$prefix = $prefix ? $prefix : _('SUCCESS') . ' ' . _('Report');
			break;
		case 'info':
		default:
			$prefix = $prefix ? $prefix : _('INFORMATION') . ' ' ._('Message');
			$Colour='navy';
	}
	return '<FONT COLOR="' . $Colour . '"><B>' . $prefix . '</B> : ' .$msg . '</FONT>';
}//getMsg

$cr1="YXJwc2NkYmFkbWlu";
$cr2="Rm9yZFByZWZlY3Q2NA==";
$cr3="YXJwc2NkYnVzZXI=";
$cr4="d2E4ZWZrV2I4cmNy";
$cr5="YXJwc2NkYmFkbWlu";
$cr6="YXJwc2NkYmFkbWluLmRiLjc2MTA2NDAuaG9zdGVkcmVzb3VyY2UuY29t";

?>
