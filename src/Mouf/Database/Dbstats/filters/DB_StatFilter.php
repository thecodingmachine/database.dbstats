<?php
namespace Mouf\Database\Dbstats\filters;

/**
 * The base class representing a filter used to build a query on a stats table.
 * There are several types of filters:
 * - DB_ValueFilter: Filter on that column (for instance, display only the year 2008)
 * - DB_AllValuesFilter: Do not filter and display everything (display all the years)
 * - DB_SumFilter: Sum up the content of the column (let's display the sum for all the years, this is the default behaviour).
 *
 */
abstract class DB_StatFilter {
	
	/**
	 * The name of the column this filter applies on.
	 *
	 * @var string
	 */
	public $columnName;
	
	/**
	 * Sets the name of the column this filter will act upon.
	 *
	 * @param string $columnName
	 */
	public function setColumnName($columnName) {
		$this->columnName = $columnName;
	}
	
	/**
	 * Default constructor.
	 * 
	 * @param string $columnName The column to filter upon
	 */
	public function __construct($columnName=null) {
		$this->columnName = $columnName;
	}
	
	/**
	 * Returns the SQL filter used by the query.
	 *
	 * @param Mouf_DBConnection $dbConnection
	 * @return string
	 */
	abstract public function getSqlFilter(Mouf_DBConnection $dbConnection);
}
?>