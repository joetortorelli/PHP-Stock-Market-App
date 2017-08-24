<?php
include('includes/connection.php');
include('includes/functions.php');

function master_loop() {
	$link = new mysqli("localhost", "root", "", "stock_app");
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	$master_ticker_file = fopen("ticker_master.txt", "r");
	while(!feof($master_ticker_file)) {
		$company_ticker = fgets($master_ticker_file);
		$company_ticker = trim($company_ticker);

		$next_day_decrease = 0; $next_day_increase = 0; $next_day_no_change = 0; $total = 0;
		$sum_of_increases = 0; $sum_of_decreases = 0;

		$result_id = mysqli_query($link, "SELECT id FROM _info WHERE ticker='$company_ticker'");
		$result_id_row = mysqli_fetch_array($result_id);
		$global_id = $result_id_row['id'];

		//$result */
		$result_data = mysqli_query($link, "SELECT date, percent_change FROM _data WHERE stock_id='$global_id'");

		//$result = mysqli_query($link, "SELECT date, percent_change FROM $company_ticker WHERE percent_change < '0' ORDER BY date ASC");
		if ($result_data) {
			while($row = mysqli_fetch_array($result_data, MYSQLI_ASSOC)){
				$date = $row['date'];
				$percent_change = $row['percent_change'];

				$sql_two = "SELECT date, percent_change FROM _data WHERE stock_id='$global_id' && date > '$date' ORDER BY date ASC LIMIT 1";

				//$sql_two = "SELECT date, percent_change FROM $company_ticker WHERE date > '$date' ORDER BY date ASC LIMIT 1";

				$result_two = mysqli_query($link, $sql_two); 
				$num_of_rows = mysqli_num_rows($result_two);

				if($num_of_rows == 1) {
					$row_two = mysqli_fetch_row($result_two);
					$tom_date = $row_two[0];
					$tom_percent_change = $row_two[1];
					if ($tom_percent_change > 0) {
						$next_day_increase++;
						$sum_of_increases += $tom_percent_change;
						$total++;
					} elseif($tom_percent_change < 0) {
						$next_day_decrease++;
						$sum_of_decreases += $tom_percent_change;
						$total++;
					} else {
						$next_day_no_change++;
						$total++;
					}
				} // while we have a row

			} // end while we have rows loop

		} else { echo 'unable to select query' . $company_ticker; }

		// protect against division by zero
		if ($total != 0 && $next_day_increase != 0) {
			$next_day_increase_percent = ($next_day_increase/$total) * 100;
			$next_day_decrease_percent = ($next_day_decrease/$total) * 100;
			$average_increase_percent = $sum_of_increases/$next_day_increase;
			$average_decrease_percent = $sum_of_decreases/$next_day_increase;
			$company_name = get_stock_name($company_ticker);

			insert_crap_into_table($company_name, $company_ticker, $next_day_increase, $next_day_increase_percent, $average_increase_percent, 
				                   $next_day_decrease, $next_day_decrease_percent, $average_decrease_percent);
		} // end division by zero protection
	} // end while not end of file
} // end master loop

function insert_crap_into_table($company_name, $company_ticker, $next_day_increase, $next_day_increase_percent, $average_increase_percent, 
	                            $next_day_decrease, $next_day_decrease_percent, $average_decrease_percent) {
	$link = new mysqli("localhost", "root", "", "stock_app");
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
	
	$buy_value = $next_day_increase * $average_increase_percent;
	$sell_value = $next_day_decrease * $average_decrease_percent;

	$sql = "SELECT * FROM _analysis WHERE ticker = '$company_ticker'";
	$result = mysqli_query($link, $sql);

	$num_of_rows = mysqli_num_rows($result);

	if($num_of_rows == 1) {
		$sql = "UPDATE _analysis SET ticker='$company_ticker', name='$company_name', next_increase='$next_day_increase', pct_of_next_increase='$next_day_increase_percent', avg_increase_percent='$average_increase_percent', next_decrease='$next_day_decrease_percent', pct_of_next_decrease='$next_day_decrease_percent', avg_decrease_percent='$average_decrease_percent', buy_value='$buy_value', sell_value='$sell_value' WHERE ticker='$company_ticker' ";
		mysqli_query($link, $sql);
	} else {
		$sql = "INSERT INTO _analysis (ticker, name, next_increase, pct_of_next_increase, avg_increase_percent, next_decrease, pct_of_next_decrease, avg_decrease_percent, buy_value, sell_value) VALUES ('$company_ticker', '$company_name', '$next_day_increase', '$next_day_increase_percent', '$average_increase_percent', '$next_day_decrease_percent', '$next_day_decrease_percent', '$average_decrease_percent', '$buy_value', '$sell_value')";
		mysqli_query($link, $sql);
	}
}

master_loop();
?>