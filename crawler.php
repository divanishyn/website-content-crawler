<?php
set_time_limit(0);

// Maybe add some GET variable to prevent automatic run?
// if( $_GET["go"] === 1 )
if( true ){

	// Include settings
	require_once('settings.php');

	// Database class
	require_once('classes/database.php');

	// Simple_html_dom class for web pages parsing
	require_once('classes/simple_html_dom.php');

	// Array for found data
	// int $category, string $name, string $code, string $year
	$data = array();

	//	Pages with categories to parse
	$pages = array(
		1 => array(
			'http://example.com/category/1'
		),
		2 => array(
			'http://example.com/category/2',
			'http://example.com/category/21'
		)
	);

	// Create database object
	$db = new CustomSQL(DB_HOST, DB_NAME, DB_USER, DB_PASS);

	// Create a DOM object
	$html = new simple_html_dom();

	foreach ( $pages as $category_id => $cat_pages ) {
		foreach ( $cat_pages as $cat_page ) {

			$ch = curl_init( $cat_page );
			$headers = array('Content-type: text/html; charset=utf-8');
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);

			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			// There was an error
			if($httpCode != 200) {
				$error = 'Error loading page ' . $cat_page . '. Error '.$httpCode.'.';
				echo $error . '<br>';
			}

			// There was NO errors
			// LIVE example!
			if($httpCode == 200){
				echo '<small>'.$cat_page . '</small><br>';
				// Load HTML from a URL
				$html = str_get_html($result);
				#$html->load_file('http://adexmart.com/vodafone/3367-vodafone-smart-vf858-android-smartphone-.html');

				// Get table with data
				$records = $html->find('center', 1)->find('center', 0)->find('table', 0)->find('tr');

				// Get each table row
				$records_length = count($records);
				for( $i = 1; $i < $records_length; $i++ ){
					$record_name = $records[$i]->find('td', 0)->plaintext;
					$record_code = $records[$i]->find('td', 1)->plaintext;

					preg_match("/([0-9]{4})-([0-9]{0,4})/", $record_name, $matches);
					$record_year = $matches[0];
					$record_name = str_replace($record_year, "", $record_name);
					// Push new item to array
					// int $category, string $name, string $code, string $year
					if( $record_name !== '' && $record_code !== '' ){
						array_push( $data, array(
								$category_id,
								$record_name,
								$record_year,
								$record_code)
						);
					}
				}
			}

			// Clear memory
			if(is_object($html))$html->clear();
			unset($html);
			unset($result);
			sleep(1);
		}
		//if($category_id==1) break;
	}
	mysql_set_charset("utf8");
	$db->updateSellerProducts($data);
}//if $_GET
?>