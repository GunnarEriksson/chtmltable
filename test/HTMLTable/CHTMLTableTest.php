<?php
namespace Guer\HTMLTable;

/**
 * PHP Unit test class for the module HTMLTable, which generates HTML tables.
 */
class CHTMLTableTest extends \PHPUnit_Framework_TestCase
{
    private $data = [
    	0 => ["column1" => "Table Cell 1", "column2" => "Table Cell 2", "column3" => "Table Cell 3"],
    	1 => ["column1" => "Table Cell 4", "column2" => "Table Cell 5", "column3" => "Table Cell 6"]
    ];

    private $defaultHeader = "\n<thead>\n<tr>\n<th>column1</th>\n<th>column2</th>\n<th>column3</th>\n</tr>\n</thead>";
    private $specifiedHeader = "\n<thead>\n<tr>\n<th>Table Header 1</th>\n<th>Table Header 2</th>\n<th>Table Header 3</th>\n</tr>\n</thead>";
    private $defaultBody = "\n<tbody>\n<tr>\n<td>Table Cell 1</td>\n<td>Table Cell 2</td>\n<td>Table Cell 3</td>\n</tr>\n<tr>\n<td>Table Cell 4</td>\n<td>Table Cell 5</td>\n<td>Table Cell 6</td>\n</tr>\n</tbody>";
    private $headColumn1And3 = "\n<thead>\n<tr>\n<th>column1</th>\n<th>column3</th>\n</tr>\n</thead>";
    private $bodyColumn1And3 = "\n<tbody>\n<tr>\n<td>Table Cell 1</td>\n<td>Table Cell 3</td>\n</tr>\n<tr>\n<td>Table Cell 4</td>\n<td>Table Cell 6</td>\n</tr>\n</tbody>";
    private $bodyFunctionColumn3 = "\n<tbody>\n<tr>\n<td>Table Cell 1</td>\n<td>Table Cell 2</td>\n<td>Present</td>\n</tr>\n<tr>\n<td>Table Cell 4</td>\n<td>Table Cell 5</td>\n<td>Present</td>\n</tr>\n</tbody>";
    private $defaultFooter = "\n<tfoot>\n<tr>\n<td>Footer Cell 1</td>\n<td>Footer Cell 2</td>\n<td>Footer Cell 3</td>\n</tr>\n</tfoot>";

    /**
     * Test not specified table.
     *
     * Test to generate a not specified table. The column headings is the
     * key name in the multidimensional associative data array.
     *
     * @return void
     */
    public function testGenerateNotSpecifiedTable()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->defaultHeader . $this->defaultBody . "\n</table>";

        $table = $table->create([], $this->data, []);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with the specified CSS id 'test-table'.
     *
     * @return void
     */
    public function testGenerateTableIdSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='test-table'>" . $this->defaultHeader . $this->defaultBody ."\n</table>";

        $tableSpec = ['id' => 'test-table'];
        $table = $table->create($tableSpec, $this->data, []);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to gnerate a table with the CSS class 'test-table'.
     *
     * @return void
     */
    public function testGenerateTableClassSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table class='test-table'>" . $this->defaultHeader . $this->defaultBody ."\n</table>";

        $tableSpec = ['class' => 'test-table'];
        $table = $table->create($tableSpec, $this->data, []);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with the caption 'The table'.
     *
     * @return void
     */
    public function testGenerateTableCaptionSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'><caption>The table</caption>" . $this->defaultHeader . $this->defaultBody . "\n</table>";

        $tableSpec = ['caption'	=> 'The table'];
        $table = $table->create($tableSpec, $this->data, []);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with column 1 and 3. The column 2 is exluded in
     * the table.
     *
     * @return void
     */
    public function testGenerateTableColumnOneAndThreeSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->headColumn1And3 . $this->bodyColumn1And3 . "\n</table>";
        $columnSpec = ['column1' => [], 'column3' => []];

        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with column headings specified.
     *
     * @return void
     */
    public function testGenerateTableColumnTitlesSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->specifiedHeader . $this->defaultBody ."\n</table>";

        $columnSpec = ['column1' => ['title' => 'Table Header 1'], 'column2' => ['title' => 'Table Header 2'], 'column3' => ['title' => 'Table Header 3']];
        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table where the value from the input array is runned
     * through a specified function for column 3.
     *
     * @return void
     */
    public function testGenerateTableFunctionSpecifiedForColumn3()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->defaultHeader . $this->bodyFunctionColumn3 . "\n</table>";
        $columnSpec = ['column1' => [], 'column2' => [], 'column3' => ['function' => function($value) {return empty($value) ? 'Not present' : 'Present';}]];

        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table where an empty function is specified for column 3.
     * The input value from the data array is shown as it is.
     *
     * @return void
     */
    public function testGenerateTableEmptyFunctionSpecifiedForColumn3()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->defaultHeader . $this->defaultBody . "\n</table>";
        $columnSpec = ['column1' => [], 'column2' => [], 'column3' => ['function' => ""]];

        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with footer with three cells specified. The cells
     * gets the values from the value tag in the column specification array.
     *
     * @return void
     */
    public function testGenerateTableFootersSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->defaultHeader . $this->defaultBody . $this->defaultFooter . "\n</table>";

        $columnSpec = ['column1' => [], 'column2' => [], 'column3' =>[], 'footer1' => ['type' => 'footer', 'value' => 'Footer Cell 1'], 'footer2' => ['type' => 'footer', 'value' => 'Footer Cell 2'], 'footer3' => ['type' => 'footer', 'value' => 'Footer Cell 3']];
        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test to generate a table with a footer with the colspan value of 3. The
     * value in the footer is set with a function.
     *
     * @return void
     */
    public function testGenerateTableFooterColspanSpecified()
    {
        $table = new \Guer\HTMLTable\CHTMLTable();

        $exp = "<table id='html-table'>" . $this->defaultHeader . $this->defaultBody . "\n<tfoot>\n<tr>\n<td colspan='3'>Footer with colspan</td>\n</tr>\n</tfoot>\n</table>";

        $columnSpec = ['column1' => [], 'column2' => [], 'column3' =>[], 'footer1' => ['type' => 'footer', 'colspan' => 3, 'function' => function() {return "Footer with colspan";}]];
        $table = $table->create([], $this->data, $columnSpec);
        $res = $table->getHTMLTable();

        $this->assertEquals($exp, $res);
    }
}
