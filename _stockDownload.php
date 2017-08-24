<?php
include('includes/connection.php');
include('includes/functions.php');

function create_url($ticker) {
	$current_month = date("n");
	$current_day = date("j");
	$current_year = date("Y");

	$start_month = (date("n") - 6);
	$start_day= date("j");
	$start_year = date("Y") ;

	return "http://real-chart.finance.yahoo.com/table.csv?s=$ticker&d=$current_month&e=$current_day&f=$current_year".
		   "&g=d&a=$start_month&b=$start_day&c=$start_year&ignore=.csv";
}

function get_csv_file($url, $output_file) {
	$content = file_get_contents($url);
	$content = str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", $content);
	$content = trim($content);
	file_put_contents($output_file, $content);
}

function file_to_database($txt_file, $ticker) {

	$file = file($txt_file);
	$fileR = array_reverse($file);

	// perform our sql queries
	$sql1 = "SELECT * FROM _info WHERE ticker = '$ticker'";
	$result = mysql_query($sql1);
	$num_rows = mysql_num_rows($result);
	$result = mysql_fetch_array($result);
	$stock_id = $result[0];

	// If we company has never been uploaded
	// into our _info table
	if($num_rows < 1) {

		$ticker_name = get_stock_name($ticker);
		$stock_exchange = get_stock_exchange($ticker);
		
		$stock_info_sql = "INSERT INTO _info (ticker, name, stock_exchange) VALUES ('$ticker', '$ticker_name', '$stock_exchange')";
		mysql_query($stock_info_sql) or die("ERROR3: <br/>" . mysql_error());
		echo "Table Created! (" . $ticker . " - " . $ticker_name . ") <br/><br/>";

		$sql_id_select = "SELECT * FROM _info WHERE ticker = '$ticker'";
		$result = mysql_query($sql_id_select);
		$result = mysql_fetch_array($result);
		$stock_id = $result[0];

		//while (!feof($file)) {
		for ($i = 0; $i <= sizeof($file)-1; $i++) {

			// insert file line into $line var
			$line = $fileR[$i];

			// explode the variable into array
			$pieces = explode(",", $line);

			$date = $pieces[0]; $open = $pieces[1]; $high = $pieces[2];
			$low = $pieces[3]; $close = $pieces[4]; $volume = $pieces[5];

			// calculate our amount and percentage changes
			$amount_change = $close-$open;
			$percent_change = ($amount_change / $open) * 100;
			
			$stock_data_sql = "INSERT INTO _data (stock_id, date, open, high, low, close, volume, amount_change, percent_change) 
			VALUES ('$stock_id', '$date','$open','$high','$low','$close','$volume','$amount_change','$percent_change')";
			mysql_query($stock_data_sql) or die("ERROR4: <br/>" . mysql_error());
			echo "Data Inserted (" . $ticker . " - " . $ticker_name . ") " . $line . "<br/>";
		}

	// If table does exist in _info db
	// we need to select its id and then
	// upload our data into our _data db
	} else {

		$sql_id_select = "SELECT id FROM _info WHERE ticker = '$ticker'";
		$result = mysql_fetch_array(mysql_query($sql_id_select)) or die(mysql_error());

		// ref vars
		$stock_id = $result[0];
		$date = date("Y-m-d", time() - 86400);

		// sql for figuring out whether date in certain stock
		// has already been stored
		$sql_exists = "SELECT * from _data where date = '$date' AND stock_id = '$stock_id'";
		$exists_result = mysql_query($sql_exists) or die("ERROR2: <br/>" . mysql_error());

		// if row exists where date is already inserted
		// dont bother inserting again
		if (mysql_num_rows($exists_result) >= 1)
			echo "Insertion Unnecessary, record exists for " . $ticker . " on " . $date; 
		
		else {
		// if row does not exist we can store 
		// our variables and insert everything!
		//for ($i = 2; $i >= 0; $i--) {
			// insert file line into $line var
			$line = $file[$i];

			// explode the variable into array
			$pieces = explode(",", $line);

			$date = $pieces[0]; $open = $pieces[1]; $high = $pieces[2];
			$low = $pieces[3]; $close = $pieces[4]; $volume = $pieces[5];

			// calculate our amount and percentage changes
			$amount_change = $close-$open;
			$percent_change = ($amount_change / $open) * 100;



			$stock_data_sql = "INSERT INTO _data (stock_id, date, open, high, low, close, volume, amount_change, percent_change) 
			VALUES ('$stock_id', '$date','$open','$high','$low','$close','$volume','$amount_change','$percent_change')";
			mysql_query($stock_data_sql) or die("ERROR4: <br/>" . mysql_error());
			echo "Data Inserted (" . $ticker . ") " . $line . "<br/>";
		}
		//}
	}
}

function main() { 

	$main_ticker_file = fopen("ticker_master.txt", "r");

	while(!feof($main_ticker_file)) {
		$company_ticker = fgets($main_ticker_file);
		$company_ticker = trim($company_ticker);

		$file_url = create_url($company_ticker);
		$company_text_file = "txt_files/".$company_ticker.".txt";
		get_csv_file($file_url, $company_text_file);

		file_to_database($company_text_file, $company_ticker);
	}

}

// roll that shit
main();

?>