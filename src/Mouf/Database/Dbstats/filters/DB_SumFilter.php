<?php
namespace Mouf\Database\Dbstats\filters;

/**
 * A filter that sum ups all the values of the column (performs a GROUP BY).
 *
 * @Component
 */
class DB_SumFilter extends DB_StatFilter {
	/**
	 * Returns the SQL filter used by the query.
	 *
	 * @param Mouf_DBConnection $dbConnection
	 * @return string
	 */
	public function getSqlFilter(Mouf_DBConnection $dbConnection) {
		return $this->columnName." IS NULL";
	}
}
?>