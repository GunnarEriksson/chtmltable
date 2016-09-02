<?php
/**
 * Tests to generate a not specified HTML table from a array of objects.
 *
 * Generates an HTML table from an array of objects, where each object contains
 * a number of data.
 * The number of columns corresponds to the number of data items in the object
 * and the number of rows corresponds to the number of objects in the data array.
 *
 * The names of the columns is the name of the keys in the first object.
 *
 * @author Gunnar Eriksson <gunnar.nt@spray.se>
 */
include('../autoloader.php');

// Create data objects.
$row0 = new stdClass();
$row0->column1 = "Table Cell 1";
$row0->column2 = "Table Cell 2";
$row0->column3 = "Table Cell 3";
$row0->column4 = "Table Cell 4";
$row0->column5 = "Table Cell 5";
$row0->column6 = "Table Cell 6";

$row1 = new stdClass();
$row1->column1 = "Table Cell 7";
$row1->column2 = "Table Cell 8";
$row1->column3 = "Table Cell 9";
$row1->column4 = "Table Cell 10";
$row1->column5 = "Table Cell 11";
$row1->column6 = "Table Cell 12";

$row2 = new stdClass();
$row2->column1 = "Table Cell 13";
$row2->column2 = "Table Cell 14";
$row2->column3 = "Table Cell 15";
$row2->column4 = "Table Cell 16";
$row2->column5 = "Table Cell 17";
$row2->column6 = "Table Cell 18";

$data = [
	0 => $row0,
	1 => $row1,
	2 => $row2,
];

// Create a non specified table with table data.
$table = new \Guer\HTMLTable\CHTMLTable();
$table = $table->create([], $data, []);

?>

<!doctype html>
<meta charset=utf8>
<title>Example on using not specified HTML table with CHTMLTable</title>
<link rel="stylesheet" type="text/css" href="css/html-table.css" />
<!-- Echo out the form -->
<h1>Example on using not specified HTML table with CHTMLTable</h1>
<?=$table->getHTMLTable()?>
