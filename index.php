<?php

// grab data passed to file
$data = file_get_contents( "php://input" );

if( !empty( $data ) && $data != '' ) {

	// Set total amount of money to make change for
	$total_amount = 100;

	// parse data and build currency map
	$currency_map = build_currencies( $data );
	
	if( !empty( $currency_map ) ) {
		
		// separate currency map into separate vars
		$passed_currency_names = $currency_map[0];
		$passed_currency_values = $currency_map[1];
		
		if( count( $passed_currency_names ) == count( $passed_currency_values ) ) {

			// Init var to hold number of coins for each scenario
			$scenarios = array();
				
			// Init a global counter variable incremented every time we find a valid currency scenario
			$running_total = 0;
			
			print_header( $passed_currency_names );
			
			print_scenarios( $passed_currency_values, $scenarios, 0, $total_amount );
			
			print_footer( $running_total, count( $currencies ) );
			
		} else {
			
			echo "\n# The number of names vs values is different. Please review the argument that you passed in.\n\n";
			
		}
	
		
	} else {
		
		echo "\n# We had an issue pairing currency names with their values. Please review the argument that you passed in.\n\n";
		
	}
	
} else {

	echo "\n# You forgot to give me some currency names and values.\n\n";

}

/*
 * Functions
 */
 
function print_scenarios( $currency_values, $scenarios, $index, $total_amount ) {
	
	// Note: index is used to keep track of which coin is being looped on
	
	// determine whether or not we should continue or display a valid scenario
	if( $index >= count( $currency_values ) ) {
		
		// grab total
		global $running_total;
		
		// display each quantity used in current scenario
		for( $i=0; $i < count( $currency_values ); $i++ ) {
			echo $scenarios[$i] . " ";
		}
		
		echo "\n";
		
		// increment total
		$running_total++;
		
		return;
		
	}

	// determine if we can use current coin
	// continue through scenario by recursively calling self
	if( $index == count( $currency_values ) - 1 ) {
		
		if( $total_amount % $currency_values[$index] == 0 ) {
			$scenarios[$index] = $total_amount / $currency_values[$index];
			print_scenarios( $currency_values, $scenarios, $index + 1, 0 );
		}
		
	} else {
		
		for( $i=0; $i <= $total_amount / $currency_values[$index]; $i++ ) {
			$scenarios[$index] = $i;
			print_scenarios( $currency_values, $scenarios, $index + 1, $total_amount - $currency_values[$index] * $i );
		}
		
	}
	
}

function build_currencies( $data ) {

	// init array to hold currency map
	$arrCurrencies = array();
	
	// create array by splitting input data with comma delimiter, also remove any double quotes
	$arrData = str_replace( '"', '', explode( ",", $data ) );
	
	// validate that we have some data
	if( count( $arrData ) ) {
		
		// init arrays to hold temporary results
		$arrNames = array();
		$arrValues = array();
		
		// loop on array created from data input
		for( $i=0; $i < count( $arrData ); $i++ ) {
			
			// it is assumed that we have a string passed to this func in the following format:
			// "Name,Value,Name,Value,Name,Value"
			// and that there are an equal number of names and values, alternating
			if( $i % 2 ) {
				array_push( $arrValues, $arrData[$i] );
			} else {
				array_push( $arrNames, $arrData[$i] );
			}
			
		}
	
		// add name and value arrays to one array
		$arrCurrencies[0] = $arrNames;
		$arrCurrencies[1] = $arrValues;
			
	}	
	
	return $arrCurrencies;
}
 
function print_header( $currency_names ) {
	echo "\n";
	
	// loop through each currency name
	// displaying only the first letter to keep output clean
	for( $i=0; $i<count( $currency_names ); $i++ ) {
		echo substr( $currency_names[$i], 0, 1 ) . " ";
	}
	
	echo "\n";
}

function print_footer( $total_solutions ) {
	echo "Count: " . $total_solutions . "\n\n";
}

?>