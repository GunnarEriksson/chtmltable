[![Build Status](https://travis-ci.org/GunnarEriksson/chtmltable.svg?branch=master)](https://travis-ci.org/GunnarEriksson/chtmltable) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GunnarEriksson/chtmltable/?branch=master)

# CHTMLTable
## Introduction
CHTMLTable is an PHP class for generating HTML tables with data from an array of
data objects. An example of an array of data objects, is when fetching data from
an MySQL database with the fetch style PDO::FETCH_OBJ.

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
To display values in the table, the HTMLTable uses an array of data objects.
Each key each key represents a column with the corresponding value as the table value.

    $data = [
        [0] => stdClass Object
            (
                [Column1] => Table Cell 1
                [Column2] => Table Cell 2
                [Column3] => Table Cell 3
                [Column4] => Table Cell 4
                [Column5] => Table Cell 5
            )

        [1] => stdClass Object
            (
                [Column1] => Table Cell 6
                [Column2] => Table Cell 7
                [Column3] => Table Cell 8
                [Column4] => Table Cell 9
                [Column5] => Table Cell 10
            )
    ];

### Optional elements
#### Table specifications
To set the **CSS id/class** and the **caption** for the table, use the table specification array.

    $tableSpecification = [
        'id'        => 'test-table',
        'class'	    => 'test-table',
        'caption'   => 'The table'
    ];

If no CSS id or class is set, the default id is "html-table".

#### Column specifications
It is possible to set a number of specifications for a column. To set column specifications,
use the associative column specification array.

    $columnSpecification = [
        $Column1 => [
        ],
        $Column3 => [
            'title' => 'Table Header 2',
        ],
        $Column2 => [
            'title' => 'Table Header 3',
            'function'	=> function($link) {
             	return '<a href="https://www.google.se">' . $link . '</a>';
        	}
        ],
        $object1 => [
            'title' => 'Table Header 4',
            'function' 	=> function($object) {
             	return htmlentities($object->Column4, null, 'UTF-8');
        	}
        ],
        $Column5 => [
            'title' => 'Table Header 5',
            'function' 	=> function($isPresent) {
             	return empty($isPresent) ? 'Not present' :  'Present';
        	}
        ],
        'tablefoot1' => [
            'type'      => 'footer',
     		'colspan'    => '4',
     		'value'		 => 'Footer Cell 1',
         ],
         'tablefoot2' => [
            'type'      => 'footer',
      		'function'	=> function() {
              	return '<a href="https://www.google.se">Link</a>';
         	}
          ],
    ];

In the column specification it is possible to determine the number of columns in
the table and the settings of the columns. The column specification sets the number
of columns and in which order they are shown in the table. Top to bottom in the
column specification corresponds to left to right in the table.

To get a value from the data object, the key name in the column specification must
correspond to the key name in the data object. For example the key Column1, in the
column specification fetches the value in the data object with the key Column1.

To fetch the whole data object, the name of the key in the column specification must
start with object (case insensitive), for example object, object1 and so on. If two
or more columns wants to fetch the data object, the key name must be unique. You
can not use the name object1 twice or more.
Notice! It is only possible to fetch a data object in combination with a function
defined.

If no **title** is set, the CHTMLTable uses the name of the keys in the first
object as the name of the titles in the table.

It is possible to use the **function** for more advanced settings of the column. The
CHTMLTable uses the call_user_func function to get settings from an anonymous function. This
examples shows how to add HTML tags, to all cells in the column, to create a link and a
function to return 'Not present' or 'Present' depending if the value for the cell is
included in the input array (data). It is also possible to fetch the object with the
key name starting with the name "object". Note! CHTMLTable does NOT use the function
htmlentities() when using a function for the data. The protection against harmful
data must be added in the specified function.

To add a **footer**, use the type tag and set the value to 'footer'. If no type is added, the
setting is regarded to be column setting. If only a simple value should be added to the footer,
use the tag 'value'. Otherwise use the function tag for more advanced settings. For a footer, it
is also possible to set the tag 'colspan' to span the columns in the footer row.

### Create and get the table in HTML
    $table = new \Guer\HTMLTable\CHTMLTable();

    $table = $table->create($tableSpecification, $data, columnSpecification);
    $table->getHTMLTable();

To generate a not specified table, just exclude the table specifications. The data
is presented as it is in number of columns according to the number of keys in the
data objects. The title of the columns are the name of the keys in the data object.
CHTMLTable uses the function htmlentities() to show data in the table cells.

    $table = $table->create([], $data, []);
    $table->getHTMLTable();

## License

This software is free software and carries a MIT license.
