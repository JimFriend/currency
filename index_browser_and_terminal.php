<?php

/*
 * Setup variables
 */

// Set currency names and values
$currencies = array( 
				array( "Whole", 100 ),
				array( "Half", 50),
				array( "Quarter", 25 ),
			    array( "Dime", 10 ),
			    array( "Nickel", 5 ),
			    array( "Penny", 1 )
			);

// Set var to hold array of currency values only
$currency_values = get_values( $currencies );

// Set var to hold number of coins for each scenario
$scenarios = array();


// Set total amount of money to make change for
$total_amount = 100;

// Init a global counter variable incremented every time we find a valid currency scenario
$running_total = 0;

// Set style variables
$from_command_line = false; // false assumes browser
$header_background = "#DDE9FF";
$header_text = "";
$footer_background = "#ECECEC";
$footer_text = "";
$odd_row = "#ECF7FF";
$even_row = "#FBFDFF";
$row_text = "";
		   
/*
 * Main
 */

// Header includes names of currencies, 'short' = one letter abbreviation, 'long' = full name 
print_header( get_names( $currencies ), "long" );

// Call recursive function to calculate and display all possible scenarios
display_scenarios( $currency_values, $scenarios, 0, $total_amount );

// Display footer with total scenario count
print_footer( $running_total, count( $currencies ) );

/*
 * Functions
 */
 
function display_scenarios( $coins, $counts, $startIndex, $totalAmount ) {
	if( $startIndex >= count( $coins ) ) {
		global $odd_row;
		global $even_row;
		global $running_total;
		global $from_command_line;
		if( $running_total % 2 ) {
			$background = $odd_row;
		} else {
			$background = $even_row;
		}
	
		if( $from_command_line ) {
			for( $i=0; $i < count( $coins ); $i++ ) {
				echo $counts[$i] . " ";
			}
			echo "\n";
		} else {
			echo "<tr>";
			for( $i=0; $i < count( $coins ); $i++ ) {
				echo "<td style='padding:8px;text-align:right;background:" . $background . ";'>";
				echo $counts[$i];
				echo "</td>";
			}
			echo "</tr>";			
		}
		$running_total++;
		return;
	}

	if( $startIndex == count( $coins ) - 1 ) {
		if( $totalAmount % $coins[$startIndex] == 0 ) {
			$counts[$startIndex] = $totalAmount / $coins[$startIndex];
			display_scenarios( $coins, $counts, $startIndex + 1, 0 );
		}
	} else {
		for( $i=0; $i <= $totalAmount / $coins[$startIndex]; $i++ ) {
			$counts[$startIndex] = $i;
			display_scenarios( $coins, $counts, $startIndex + 1, $totalAmount - $coins[$startIndex] * $i );
		}
	}
}
 
function get_names( $data ) {
	$arrNames = array();
	for( $i=0; $i<count( $data ); $i++) {
		$arrNames[$i] = $data[$i][0];
	}
	return $arrNames;
}

function get_values( $data ) {
	$arrVals = array();
	for( $i=0; $i<count( $data ); $i++) {
		$arrVals[$i] = $data[$i][1];
	}
	return $arrVals;
}

function print_header( $names, $header_type='short' ) {
	global $header_background;
	global $from_command_line;
	
	if( $from_command_line ) {
		echo "\n";
		for( $i=0; $i<count( $names ); $i++ ) {
			echo substr( $names[$i], 0, 1 ) . " ";
		}
		echo "\n";
		
	} else {
		echo "<table cellpadding='0' cellspacing='0' style='border:1px solid #000000;'>";
		echo "<tr>";
		for( $i=0; $i<count( $names ); $i++ ) {
			echo "<td style='padding:8px;font-weight:bold;border-bottom:1px solid #000000;background:" . $header_background . ";'>";
			if( $header_type == 'long' ) {
				echo $names[$i];
			} else {
				echo substr( $names[$i], 0, 1 );
			}
			echo "</td>";
		}
		echo "</tr>";		
	}
}

function print_footer( $total_solutions=0, $num_currencies ) {
	global $footer_background;
	global $from_command_line;
	if( $from_command_line ) {
		echo "\nCount: " . $total_solutions . "\n\n";
	
	} else {
		echo "<tr>";
		echo "<td style='padding:4px;background:" . $footer_background . "' colspan='" . $num_currencies . "'>";
		echo "Count: " . $total_solutions;
		echo "</td>";
		echo "</tr>";
		echo "</table>";		
	}
}

?>