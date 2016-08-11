<?php
/**
 * Tests to generate specified HTML table from a multidimensional associative
 * array of data and specification arrays.
 *
 * Generates an HTML table fron an anrray of data according to the specifications
 * in the table- and the column specification array.
 *
 * In the table specification it is possible to set the CSS id and/or class for
 * the table and the tables caption.
 *
 * In the column specification it is possible to set the title of the column,
 * the type of the column, colspan and a value if the type is a footer. It is
 * also possible to include functions for a column such as an algorithm or a
 * string of html tags for all columns.
 *
 * @author Gunnar Eriksson <gunnar.nt@spray.se>
 */
include('../../autoloader.php');

$table = new \Guer\HTMLTable\CHTMLTable();

$data = [
	0 => [
		"column1" => "Table Cell 1",
		"column2" => "Table Cell 2",
		"column3" => "Table Cell 3",
		"column4" => "Table Cell 4",
		"column5" => "Table Cell 5",
		"column6" => "Table Cell 6",
	],
	1 => [
		"column1" => "Table Cell 7",
		"column2" => "Table Cell 8",
		"column3" => "Table Cell 9",
		"column4" => "Table Cell 10",
		"column5" => "Table Cell 11",
		"column6" => "Table Cell 12",
	],
	2 => [
		"column1" => "Table Cell 13",
		"column2" => "Table Cell 14",
		"column3" => "Table Cell 15",
		"column4" => "Table Cell 16",
		"column5" => "Table Cell 17",
		"column6" => "Table Cell 18",
	],
];

$tableSpecification = [
	//'id'        => 'test-table',
	//'class'	=> 'test-table',
	'caption'	=> 'The table'
];

$table = $table->create($tableSpecification, $data, [
	'column1' => [
        'title' => 'Table Header 1',
    ],
	'column2' => [
    ],
    'column4' => [
        'title'     => 'Table Header 4',
		'function'	=> function($tableCell4) {
        	return '<a href="https://www.google.se">' . $tableCell4 . '</a>';
   		}
    ],
	'column6' => [
        'title'    	=> 'Table Header 6',
		'function' 	=> function($tableCell6) {
        	return empty($tableCell6) ? 'Not present' :  'Present';
   		}
    ],
	'tablefoot1' => [
        'type'      => 'footer',
		'colspan'  	=> '2',
		'value'		=> 'Footer Cell 1',
    ],
	'tablefoot2' => [
        'type'      => 'footer',
		'colspan'  	=> 2,
		'function'	=> function() {
        	return '<a href="https://www.google.se">Link</a>';
   		}
    ],
]);

?>

<!doctype html>
<meta charset=utf8>
<title>Example on using specified HTML table with CHTMLTable</title>
<link rel="stylesheet" type="text/css" href="../css/html-table.css" />
<!-- Echo out the form -->
<h1>Example on using specified HTML table with CHTMLTable</h1>
<?=$table->getHTMLTable()?>
