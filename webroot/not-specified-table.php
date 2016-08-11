<?php
/**
 * Tests to generate a not specified HTML table from a multidimensional
 * associative array of data.
 *
 * Generates an HTML table fron an anrray of data. Number of kolumns is
 * the number of the keys in the associative array.
 *
 * The names of the columns is the name of the keys in the associative array.
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

$table = $table->create([], $data, []);

?>

<!doctype html>
<meta charset=utf8>
<title>Example on using not specified HTML table with CHTMLTable</title>
<link rel="stylesheet" type="text/css" href="../css/html-table.css" />
<!-- Echo out the form -->
<h1>Example on using not specified HTML table with CHTMLTable</h1>
<?=$table->getHTMLTable()?>
