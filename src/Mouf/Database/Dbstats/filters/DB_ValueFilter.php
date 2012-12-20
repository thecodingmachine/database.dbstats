<?php
namespace Mouf\Database\Dbstats\filters;

/**
 * The filter that filters a column on a specific value.
 *
 * @Component
 */
class DB_ValueFilter extends DB_StatFilter {
	
	/**
	 * The value to filter on.
	 *
	 * @var mixed
	 */
	public $value;
	
	/**
	 * Sets the value to filter on.
	 *
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}
	
	/**
	 * Returns the SQL filter used by the query (for instance: year='2006'). 
	 *
	 * @param Mouf_DBConnection $dbConnection
	 * @return string
	 */
	public function getSqlFilter(Mouf_DBConnection $dbConnection) {
		return $this->columnName." = ".$dbConnection->quoteSmart($this->value);
	}
	
	/**
	 * Default constructor.
	 * 
	 * @param string $columnName The column to filter upon
	 * @param string $value The value to filter
	 */
	public function __construct($columnName=null, $value=null) {
		parent::__construct($columnName);
		$this->value = $value;
	}
}
?>