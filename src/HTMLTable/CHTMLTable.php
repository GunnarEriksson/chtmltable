<?php
namespace Guer\HTMLTable;

/**
 * Class that generates a table in HTML with table data from an multidimensional
 * associative array.
 *
 * Through a table specification array it is possible to set an CSS id or
 * a class for the table and a caption.
 *
 * Through a column specification it is possible to set the column title, set
 * a colspan for the table cell and footer cell, through type determine if the
 * row of cells belongs to the table body or table foot and through a function
 * include advanced settings for a column.
 *
 * @author Gunnar Eriksson <gunnar.nt@spray.se>
 */
class CHTMLTable
{
    const FOOTER = 'footer';

    private $tableSpec;
    private $tableHead;
    private $tableBody;
    private $tableFoot;

    /**
     * Constructor
     *
     * Creates a table with table head, table body and if specified, a table
     * footer. It is possible to specify the table and the tabel cells settings
     * per column.
     *
     * @param [] $tableSpecs    table settings.
     * @param [] $data          table cell data.
     * @param [] $columnSpecs   table columns cell settings.
     */
    public function __construct($tableSpecs = [], $data = [], $columnSpecs = [])
    {
        $this->create($tableSpecs, $data, $columnSpecs);
    }

    /**
     * Creates a table with cell data.
     *
     * Creates a table with table head, table body with table data and if
     * specified, a table footer. It is possible to specify the table and the
     * tabel cells settings per column.
     *
     * @param  [] $tableSpec    table settings.
     * @param  [] $data         table cell data.
     * @param  [] $columnSpecs  table columns cell settings.
     *
     * @return object           the html table object.
     */
    public function create($tableSpecs = [], $data = [], $columnSpecs = [])
    {
        $this->resetTableTags();
        $this->setTableSpecifications($tableSpecs);

        $this->createTableHead($data, $columnSpecs);
        $this->createTableBody($data, $columnSpecs);
        $this->createTableFooter($columnSpecs);

        return $this;
    }

    /**
     * Helper method to reset main parts of table tags.
     *
     * Sets the table head, table body and table foot tag to null.
     *
     * @return void
     */
    private function resetTableTags()
    {
        $this->tableHead = null;
        $this->tableBody = null;
        $this->tableFoot = null;
    }

    /**
     * Helper method to set the table specifications.
     *
     * Merges the table specifications with the default specifications.
     * Default table CSS id is html-table.
     *
     * @param [] $table     table settings.
     *
     * @return void
     */
    private function setTableSpecifications($tableSpec)
    {
        $defaults = [
            // Always have a id for the form
            'id' => 'html-table',
        ];

        if ($this->isClassPresent($tableSpec)) {
            $tableSpec = $this->removeId($tableSpec);
        }

        $this->tableSpec = array_merge($defaults, $tableSpec);
    }

    /**
     * Helper method to check if a CSS class tag is present
     *
     * Checks if a CSS class tag is present in the table specification.
     *
     * @param  []  $tableSpec the table specification.
     *
     * @return boolean true if class is present in the table specification,
     *                 false otherwise.
     */
    private function isClassPresent($tableSpec)
    {
        return isset($tableSpec['class']) ? true : false;
    }

    /**
     * Helper method to reset the id tag.
     *
     * Sets the CSS id tag to null.
     *
     * @param  [] $tableSpec the table specification.
     *
     * @return [] the table specification without the CSS id tag.
     */
    private function removeId($tableSpec) {
        $tableSpec['id'] = null;

        return $tableSpec;
    }

    /**
     * Helper method to create the table head.
     *
     * Creates the table head. The title of the columns are set according to
     * the table tag in the column specifications. Otherwise, the title is set
     * to the keys name in the table cell data array.
     *
     * @param  [] $data         table cell data.
     * @param  [] $columnSpecs  table columns cell settings.
     *
     * @return void
     */
    private function createTableHead($data, $columnSpecs)
    {
        $this->tableHead = "\n<thead>";
        $this->tableHead .= "\n<tr>";

        if (empty($columnSpecs))
        {
            $this->setColumnTitlesFromData($data);
        } else {
            $this->setColumnTitlesFromColumnSpecifications($columnSpecs);
        }

        $this->tableHead .= "\n</tr>";
        $this->tableHead .= "\n</thead>";
    }

    /**
     * Helper method to set the column titles from the data array.
     *
     * Uses the first row in the table cell data array to set the titles of
     * the columns. The name of the columns are the key name in the associative
     * array containing data for the table.
     *
     * @param  [] $data     table cell data.
     *
     * @return void
     */
    private function setColumnTitlesFromData($data)
    {
        $firstRow = isset($data[0]) ? $data[0] : [];
        foreach ($firstRow as $key => $value) {
            $this->tableHead .= "\n<th>";
            $this->tableHead .= $key;
            $this->tableHead .= "</th>";
        }
    }

    /**
     * Helper method to set the column titles from column specifications.
     *
     * Uses column specifications to set the name of the columns in the table
     * head.
     *
     * @param  [] $columnSpecs    table columns cell settings
     *
     * @return void
     */
    private function setColumnTitlesFromColumnSpecifications($columnSpecs)
    {
        foreach ($columnSpecs as $key => $columnSpec) {
            if (!$this->isTableFooter($columnSpec)) {
                $this->tableHead .= "\n<th>";
                $this->tableHead .= $this->getTitle($key, $columnSpec);
                $this->tableHead .= "</th>";
            }
        }
    }

    /**
     * Helper method to check if the column cell belongs to the footer.
     *
     * Checks the type tag, in the column specification for one column, if the
     * tag is present and set to footer.
     *
     * @param  []  $columnSpec    cell settings for one column.
     *
     * @return boolean true if the cell type belongs to the footer, false otherwise.
     */
    private function isTableFooter($columnSpec)
    {
        $isFooter = false;
        if (isset($columnSpec['type'])) {
            if (strcmp($columnSpec['type'], self::FOOTER) === 0) {
                $isFooter = true;
            }
        }

        return $isFooter;
    }

    /**
     * Helper method to get title from a column specification, if specified.
     *
     * Uses the title tag in the column specification for one column to get
     * the title. If the title tag is not set, the title is the key name in
     * the associative array containing data for the table.
     *
     * @param  [] $key          the name of the key for the table cell data.
     * @param  [] $columnSpec   cell settings for one column.
     *
     * @return []   the name from the title tag in the cell specification.
     *              Otherwise, the table cell data key name.
     */
    private function getTitle($key, $columnSpec)
    {
        return isset($columnSpec['title']) ? $columnSpec['title'] : $key;
    }

    /**
     * Helper method to create the table body with table cell data.
     *
     * Sets the table cell data in the table body.
     *
     * @param  [] $data         table cell data.
     * @param  [] $columnSpecs  table columns cell settings.
     *
     * @return void
     */
    private function createTableBody($data, $columnSpecs)
    {
        $this->tableBody = "\n<tbody>";
        $this->setTableData($data, $columnSpecs);
        $this->tableBody .= "\n</tbody>";
    }

    /**
     * Helper method to set table data in table body.
     *
     * Sets table data according to the column specifications, if it is
     * specified. Otherwise it sets the data as it is stored in the data array.
     *
     * @param  [] $data         table cell data.
     * @param  [] $columnSpecs  table columns cell settings.
     *
     * @return void
     */
    private function setTableData($data, $columnSpecs)
    {
        if (empty($columnSpecs)) {
            $this->setTableDataFromData($data);
        } else {
            $this->setTableDataAsSpecified($data, $columnSpecs);
        }
    }

    /**
     * Helper method to set table data from the data array.
     *
     * Sets table data from the data array.
     *
     * @param  [] $data     table cell data.
     *
     * @return void
     */
    private function setTableDataFromData($data)
    {
        foreach ($data as $row) {
            $this->tableBody .= "\n<tr>";
            foreach ($row as $value) {
                $this->tableBody .= "\n<td>";
                $this->tableBody .= $value;
                $this->tableBody .= "</td>";
            }
            $this->tableBody .= "\n</tr>";
        }
    }

    /**
     * Helper method to set table data according to the column specifications.
     *
     * Sets the table data according to the column specifications, if the cell
     * does not belong to the footer. Adds a colspan tag, if it is specified
     * for the cell in the column.
     *
     * @param  [] $data         table cell data.
     * @param  [] $columnSpecs  table columns cell settings.
     *
     * @return void
     */
    private function setTableDataAsSpecified($data, $columnSpecs)
    {
        foreach ($data as $row) {
            $this->tableBody .= "\n<tr>";
            foreach ($columnSpecs as $key => $columnSpec) {
                if (!$this->isTableFooter($columnSpec)) {
                    $colspan = $this->getColspan($columnSpec);
                    $this->tableBody .= "\n<td{$colspan}>";
                    $this->tableBody .= $this->getValue($row, $key, $columnSpec);
                    $this->tableBody .= "</td>";
                }
            }
            $this->tableBody .= "\n</tr>";
        }
    }

    /**
     * Helper method to get the colspan value, if specified in the column
     * specification for the cell.
     *
     * @param  [] $columnSpec     cell settings for one column.
     *
     * @return int  the colspan value if specified. Otherwise null.
     */
    private function getColspan($columnSpec)
    {
        return isset($columnSpec['colspan']) ? " colspan='{$columnSpec['colspan']}'" : null;
    }

    /**
     * Helper method to get the value for a specific position in one row in
     * the data array.
     *
     * Gets the data from a specific position in one row in the data array.
     * If a function is specified for the cell in the column, the data is
     * runned through the function before it is returned.
     *
     * @param [] $row           one row of in the array of table data.
     * @param string $key       the name of the key in the associative data array.
     * @param [] $columnSpec    cell settings for one column.
     */
    private function getValue($row, $key, $columnSpec)
    {
        if ($this->isFunctionSpecified($columnSpec)) {
            $dataValue = isset($row[$key]) ? $row[$key] : "";
            return $this->getValueThroughFunction($columnSpec, $dataValue);
        } else {
            return isset($row[$key]) ? $row[$key] : "";
        }
    }

    /**
     * Helper method t check if the function tag is specified for the cells in
     * one column.
     *
     * Checks if the function tag is set for the cell in one column.
     *
     * @param  []  $columnSpec    cell settings for one column.
     *
     * @return boolean true if a function is connected to the cell, false otherwise.
     */
    private function isFunctionSpecified($columnSpec)
    {
        return isset($columnSpec['function']) ? true : false;
    }

    /**
     * Helper method to run the value through a function before it is returned.
     *
     * Runs the value through a function, if a function is connected to the cell
     * in the column. If not function is connected to the cell through the
     * column specification, the value is returned as it is.
     *
     * @param [] $columnSpec    cell settings for one column
     * @param mixed $dataValue  the value to run through function, if specified.
     *
     * @return the value.
     */
    private function getValueThroughFunction($columnSpec, $dataValue)
    {
        if (!empty($columnSpec['function'])) {
            return call_user_func($columnSpec['function'], $dataValue);
        } else {
            return $dataValue;
        }
    }

    /**
     * Helper method to create table footer with data.
     *
     * Creates table footer if the cell settings for the column is set to
     * footer in the column specifications.
     * Adds a colspan tag, if it is specified for the cell in the column.
     *
     * @param  [] $columnSpecs    table columns cell settings.
     *
     * @return void
     */
    private function createTableFooter($columnSpecs)
    {
        $isFooterDataAdded = false;

        $this->tableFoot = "\n<tfoot>";
        $this->tableFoot .= "\n<tr>";
        foreach ($columnSpecs as $key => $columnSpec) {
            if ($this->isTableFooter($columnSpec)) {
                $colspan = $this->getColspan($columnSpec);
                $this->tableFoot .= "\n<td{$colspan}>";
                $this->tableFoot .= $this->getFooterData($columnSpec);
                $this->tableFoot .= "</td>";
                $isFooterDataAdded = true;
            }
        }

        if ($isFooterDataAdded) {
            $this->tableFoot .= "\n</tr>";
            $this->tableFoot .= "\n</tfoot>";
        } else {
            $this->tableFoot = null;
        }
    }

    /**
     * Helper method to get table footer data.
     *
     * Gets table footer data from the column specification. Checks if the
     * value should be fetched from a function or from the value tag.
     * If either the function or the value specified, an empty string is
     * returned.
     *
     * @param  [] $columnSpec   cell settings for one column.
     *
     * @return mixed    the cell data value.
     */
    private function getFooterData($columnSpec)
    {
        if ($this->isFunctionSpecified($columnSpec)) {
            return call_user_func($columnSpec['function']);
        } else {
            return isset($columnSpec['value']) ? $columnSpec['value'] : "";
        }
    }

    /**
     * Gets the table.
     *
     * Gets the table with table data.
     *
     * @return html     the table with table data.
     */
    public function getHTMLTable()
    {
        $id = isset($this->tableSpec['id']) ? " id='{$this->tableSpec['id']}'" : null;
        $class = isset($this->tableSpec['class']) ? " class='{$this->tableSpec['class']}'" : null;
        $caption = isset($this->tableSpec['caption']) ? "<caption>{$this->tableSpec['caption']}</caption>" : null;

        $htmlTable = "<table{$id}{$class}>";
        $htmlTable .= $caption;
        $htmlTable .= $this->tableHead;
        $htmlTable .= $this->tableBody;
        $htmlTable .= $this->tableFoot;
        $htmlTable .= "\n</table>";

        return $htmlTable;
    }
}
