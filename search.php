<?php
include('includes/connection.php');
include('includes/functions.php'); 

$value = $_POST['value'];
$query = mysql_query("SELECT ticker FROM _analysis WHERE name LIKE '$value%' || ticker LIKE '$value%'") or die(mysql_error()); ?>

<ul>

<?php

while($run = mysql_fetch_array($query, MYSQL_ASSOC)) {
	$name = $run['ticker'];
	echo "<li><a href='http://localhost/stocks/pages/viewStock.php?ticker=$name'>";
	echo (strtoupper($name));
	echo " - ";
	echo (get_stock_name($name));
	echo "</a></li>";
} ?>

</ul>