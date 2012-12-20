<?php
namespace Mouf\Database\Dbstats;

/**
 * A column in the stats table, with its type, and the data its coming from.
 *
 * @Component
 */
class DB_StatColumn {
	
	/**
	 * The name of the column in the stats table.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $columnName;
	
	/**
	 * The source of the data. This can be the name of a column in the source table, or an SQL expression running on the fields of the source table.
	 * IMPORTANT: any column from the original table stated here should start with "[statcol].".
	 * For instance, instead of writing YEAR(mydate), you would write YEAR([statcol].mydate)
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $dataOrigin;
	
	/**
	 * The SQL type of the column. This is used to generate the table dynamically.
	 *
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $type;
	
	/**
	 * Sets the name of the column in the stats table.
	 *
	 * @param string $columnName
	 */
	public function setColumnName($columnName) {
		$this->columnName = $columnName;
	}
	
	/**
	 * Sets the source of the data. This can be the name of a column in the source table, or an SQL expression running on the fields of the source table.
	 * IMPORTANT: any column from the original table stated here should start with "[statcol].".
	 * For instance, instead of writing YEAR(mydate), you would write YEAR([statcol].mydate)
	 *
	 * @param string $columnName
	 */
	public function setDataOrigin($dataOrigin) {
		$this->dataOrigin = $dataOrigin;
	}
	
	/**
	 * The SQL type of the column. This is used to generate the table dynamically.
	 *
	 * @param string $columnName
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Returns the SQL for a data orgin, customized for insert triggers: all columns will be prefixed with "NEW."
	 *
	 * @return string
	 */
	public function getDataOriginForInsertTrigger() {
		return str_ireplace("[statcol].", "NEW.", $this->dataOrigin);
	}

	/**
	 * Returns the SQL for a data orgin, customized for insert triggers: all columns will be prefixed with "NEW."
	 *
	 * @return string
	 */
	public function getDataOriginForUpdateTrigger() {
		return str_ireplace("[statcol].", "OLD.", $this->dataOrigin);
	}
	
	/**
	 * Returns the SQL for a data orgin, customized for group by statements: all columns will not be prefixed at all.
	 *
	 * @return string
	 */
	public function getDataOriginForGroupBy() {
		return str_ireplace("[statcol].", "", $this->dataOrigin);
	}
}
?>