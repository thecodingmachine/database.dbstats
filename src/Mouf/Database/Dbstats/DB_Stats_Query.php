<?php
namespace Mouf\Database\Dbstats;

use Mouf\Database\Dbstats\filters\DB_ValueFilter;
use Mouf\Database\Dbstats\filters\DB_SumFilter;
use Mouf\Database\Dbstats\filters\DB_AllValuesFilter;

/**
 * A class specialized in querying data stored in a aggregate table created with the DB_Stats class.
 *
 * To build a query, we are starting from the columns of the dimensions.
 * For each column, we have 3 possible choices:
 * - Filter on that column (for instance, display only the year 2008)
 * - Do not filter and display everything (display all the years)
 * - Sum up the content of the column (let's display the sum for all the years).
 * 
 * Please note that it is the responsibility of the developer to query according to the dimensions.
 * For instance, if one dimension is "year->month->day", the developer CANNOT sum up years but filter on the month:
 * You must first apply filters, then sum the latest columns of the dimension if you want.
 * 
 * @Component
 */
class DB_Stats_Query {
	
	/**
	 * The Db_Stats object that represents the table we are going to query.
	 *
	 * @Property
	 * @Compulsory
	 * @var DB_Stats
	 */
	public $dbStats;
	
	/**
	 * The filters applied to all the columns.
	 * If a column is not present in this list, the default operation is to sum up the results.
	 * Therefore, if there are no filters at all, the result will be one line containing the SUM of all the
	 * records on the table.  
	 *
	 * @Property
	 * @Compulsory
	 * @var array<DB_StatFilter>
	 */
	public $filters = array();
	
	/**
	 * Sets the DB_Stats object this object performs query on.
	 *
	 * @param DB_Stats $dbStats
	 */
	public function setDbStats(DB_Stats $dbStats) {
		$this->dbStats = $dbStats;
	}
	
	/**
	 * Adds a filter to the list of filters applied for this query.
	 *
	 * @param DB_StatFilter $filter
	 */
	public function addFilter(DB_StatFilter $filter) {
		$this->filters[] = $filter;
	}
	
	/**
	 * Performs the query, returns the results.
	 *
	 * @return array<array> An array of rows. Each row is an associative array containg the pair columnName/value
	 */
	public function query() {
		// First, let's get a list containing all the columns with all the filters.
		// If there are missing columns, let's create a DB_SumFilter on those automatically.
		
		$columns = $this->dbStats->getFilterColumns();
		
		$filtersByColumn = array();
		foreach ($this->filters as $filter) {
			$filtersByColumn[$filter->columnName] = $filter;
		}
		
		foreach ($columns as $column) {
			if (!isset($filtersByColumn[$column->columnName])) {
				$columnSumFilter = new DB_SumFilter();
				$columnSumFilter->columnName = $column->columnName;
				$filtersByColumn[$column->columnName] = $columnSumFilter; 
			}
		}
		
		// Now, $filtersByColumn contains the name of all the columns as a key, and a filter as the value.
		
		$sql = "SELECT * FROM `".$this->dbStats->statsTable."` WHERE ";
		
		$filterArr = array();
		foreach ($filtersByColumn as $columnName=>$filter) {
			$filterArr[] = $filter->getSqlFilter($this->dbStats->dbConnection);
		}
		
		$sql .= implode(" AND ", $filterArr);
		//echo $sql."\n";
		return $this->dbStats->dbConnection->getAll($sql);
	}
}
?>