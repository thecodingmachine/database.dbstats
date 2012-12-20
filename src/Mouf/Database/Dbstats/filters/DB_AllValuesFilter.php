<?php
namespace Mouf\Database\Dbstats\filters;

require_once 'DB_StatFilter.php';


/**
 * A filter that displays all the content of a column.
 *
 * @Component
 */
class DB_AllValuesFilter extends DB_StatFilter {
	/**
	 * Returns the SQL filter used by the query.
	 *
	 * @param Mouf_DBConnection $dbConnection
	 * @return string
	 */
	public function getSqlFilter(Mouf_DBConnection $dbConnection) {
		return $this->columnName." IS NOT NULL";
	}
}
?>