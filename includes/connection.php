<?php  
	error_reporting(E_ALL ^ E_DEPRECATED);
	$link = mysql_connect("localhost", "root", "");
	if(!$link) { die ("could not connect to db"); }
	mysql_select_db("stock_app", $link);
?>