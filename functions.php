<?php
    
	
	
	if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


	
	
function display_dates($start, $end){
	
	$start_month = date("F",strtotime($start));
	$end_month = date("F",strtotime($end));
	$display_string = ""; 
	
	if($start_month == $end_month){
	$display_string = date("jS",strtotime($start))." - ".date("jS F Y",strtotime($end)); }else{
		
	$display_string = date("jS F",strtotime($start))." - ".date("jS F Y",strtotime($end)); 	
	}
	
	return $display_string;
	
	
}




function CleanStr($str){


$newphrase = trim( $str);

return $newphrase;

}	
function CleanStrxl($str){


$newphrase = trim( strip_tags($str));
$newphrase = mysql_real_escape_string($newphrase);
$newphrase = "'".$newphrase."'";
return $newphrase;

}




function unset_all($array){
	foreach ( $array as $val ) {
		$_SESSION[$val]="";

		unset($_SESSION[$val]);
		
		
	}
	
	
}


function Csqldate($myinput){
    
$sqldate=date('Y-m-d',strtotime($myinput));
return  $sqldate; 
}

function Cbsqldate($myinput){
    
$sqldate=date('d-m-Y',strtotime($myinput));
return  $sqldate; 
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}





?>