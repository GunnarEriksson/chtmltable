<?php
/**
 * Tests to generate specified HTML table from from a array of objects and
 * specification arrays.
 *
 * Generates an HTML table fron an array of objects, where each object contains
 * a number of data, according to the specifications in the table- and the
 * column specification array.
 *
 * In the table specification it is possible to set the CSS id and/or class for
 * the table and the table caption.
 *
 * In the column specification the arrays order corresponds to the columns order
 * in the table. The name of the key in the column specifiation should
 * correspond to the key in the object, where the data should be fetched from.
 *
 * It is possible to specify a title of the column in the column specification.
 * If no title is specified, the key name in the object of the first row is used.
 * The same name as the key in the column specification.
 *
 * It is possible to define a function for the column. The function could be
 * used to use an algorithm for the table data in the object or add a string
 * of HTML tags for the column. It is also possible to fetch the whole object
 * by naming the key in the column specifiation so it starts with object...
 * This makes it possible to use all the data in the object if it is wanted.
 *
 * The type could be set to footer and colspan could also be used, if wanted.
 *
 * @author Gunnar Eriksson <gunnar.nt@spray.se>
 */
include('../autoloader.php');

// Create data objects.
$row0 = new stdClass();
$row0->column1 = "Table Cell 1";
$row0->column2 = "Table Cell 2";
$row0->column3 = "Table Cell 3";
$row0->column4 = "https://www.google.se";
$row0->column5 = "Table Cell 5";
$row0->column6 = "Table Cell 6";

$row1 = new stdClass();
$row1->column1 = "Table Cell 7";
$row1->column2 = "Table Cell 8";
$row1->column3 = "Table Cell 9";
$row1->column4 = "https://www.google.se";
$row1->column5 = "Table Cell 11";
$row1->column6 = "";

$row2 = new stdClass();
$row2->column1 = "Table Cell 13";
$row2->column2 = "Table Cell 14";
$row2->column3 = "Table Cell 15";
$row2->column4 = "https://www.google.se";
$row2->column5 = "Table Cell 17";
$row2->column6 = "Table Cell 18";

// Create table data, which is an array of data objects.
$data = [
	0 => $row0,
	1 => $row1,
	2 => $row2,
];

// Create table specifiation.
$tableSpecification = [
	//'id'        => 'test-table',
	//'class'	=> 'test-table',
	'caption'	=> 'The table'
];

// Create table with table specification, table data and the column specification.
$table = new \Guer\HTMLTable\CHTMLTable();
$table = $table->create($tableSpecification, $data, [
	'column1' => [
        'title' => 'Table Header 1',
    ],
	'object1' => [
		'title'     => 'Table Header 2',
		'function'	=> function($object) {
			return $object->column2;
		}
    ],
    'column4' => [
        'title'     => 'Table Header 4',
		'function'	=> function($link) {
			return '<a href="'. $link . '">' . "Google" . '</a>';
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
<link rel="stylesheet" type="text/css" href="css/html-table.css" />
<!-- Echo out the form -->
<h1>Example on using specified HTML table with CHTMLTable</h1>
<?=$table->getHTMLTable()?>
