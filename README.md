[![Build Status](https://travis-ci.org/GunnarEriksson/chtmltable.svg?branch=master)](https://travis-ci.org/GunnarEriksson/chtmltable) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/?branch=master)

# CHTMLTable
## Introduction
CHTMLTable is an PHP class for generating HTML tables with data from an multidimensional
associative array.

It can to be used with [Anax MVC](https://github.com/mosbth/Anax-MVC), but is not
dependent on it. It can be included in any other project.

## How to install
To install the package, add the row below to your `composer.json` file:

    "require": {
        "guer/chtmltable": "dev-master"
    }

If the Anax-MVC framework is used, copy the `webroot/table-anax-mvc.php` file
and the `webroot/css/html-table.css` file to each folder in the Anax-MVC webroot directory

## How to use
### Get the data to the table
To display values in the table, the HTMLTable uses an multidimensional associative array.
Each key each key represents a column with the corresponding value as the table value.

    $data = [
        0 => ["Column1" => "Table Cell 1", "Column2" => "Table Cell 2", "Column3" => "Table Cell 3",],
        1 => ["Column1" => "Table Cell 4", "Column2" => "Table Cell 5", "Column3" => "Table Cell 6"]
    ];

### Optional elements
#### Table specifications
To set the **CSS id/class** and the **caption** for the table, use the table specification array.

    $tableSpecification = [
        //'id'        => 'test-table',
        //'class'	  => 'test-table',
        'caption'   => 'The table'
    ];

If no CSS id or class is set, the default id is html-table.

#### Column specifications
It is possible to set a number of specifications for a column. To set column specifications,
use the associative column specification array.

    $columnSpecification = [
        $Column1 => [
            'title' => 'Table Header 1',
            'function'	=> function($link) {
             	return '<a href="https://www.google.se">' . $link . '</a>';
        	}
        ],
        $Column2 => [
            'title' => 'Table Header 2',
            'function' 	=> function($isPresent) {
             	return empty($isPresent) ? 'Not present' :  'Present';
        	}
        ],
        'tablefoot1' => [
            'type'      => 'footer',
     		'colspan'    => '2',
     		'value'		 => 'Footer Cell 1',
         ],
         'tablefoot2' => [
            'type'      => 'footer',
      		'function'	=> function() {
              	return '<a href="https://www.google.se">Link</a>';
         	}
          ],
    ];

To set a specification for a column, the name of the key must correspond to the name of
the key in the input array. In this example, the key name Column1 in the column specification
corresponds the key name Column1 in the data array.

The number of columns in the table, corresponds to the number of column specifications in the
column specification array. To exclude values in the input array (data) in the table, exclude
the key name in the column specification array.

If no **title** is set, the CHTMLTable uses the name of the key, in the input array (data), to
set the title of the column.

It is possible to use the **function** for more advanced settings of the column. The
CHTMLTable uses the call_user_func function to get settings from an anonymous function. This
examples shows how to add HTML tags, to all cells in the column, to create a link and a
function to return 'Not present' or 'Present' depending if the value for the cell is
included in the input array (data).

To add a **footer**, use the type tag and set the value to 'footer'. If no type is added, the
setting is regarded to be column setting. If only a simple value should be added to the footer,
use the tag 'value'. Otherwise use the function tag for more advanced settings. For a footer, it
is also possible to set the tag 'colspan' to span the columns in the footer row.

### Create and get the table in HTML
    $table = new \Guer\HTMLTable\CHTMLTable();

    $table = $table->create($tableSpecification, $data, columnSpecification);
    $table->getHTMLTable();

To use a not specified table, where the keys in the $data array is used as headings
for the columns, just exclude the table specification and the column specification.
The number of columns is the number of keys in the first row in the multidimensional
associative array.

    $table = $table->create([], $data, []);
    $table->getHTMLTable();

## License

This software is free software and carries a MIT license.
