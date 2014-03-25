<?php
namespace Mouf\Database\Dbstats;

/**
 * This class represents a dimension to analyse the stats table.
 * A dimension is a column, or a list of columns used to analyse data.
 *
 */
class DB_Dimension {
	
	/**
	 * The list of columns the dimension is made of.
	 *
	 * @var array<DB_StatColumn>
	 */
	private $columns = array();
	
	/**
	 * Sets list of columns the dimension is made of.
	 *
	 * @param array<DB_StatColumn> $columns
	 */
	public function setColumns(array $columns) {
		$this->columns = $columns;
	}
	
	/**
	 * Adds a column to the list of columns the dimension is made of.
	 *
	 * @param DB_StatColumn $column
	 */
	public function addColumn(DB_StatColumn $column) {
		$this->columns[] = $column;
	}
	
	/**
	 * Gets the list of columns the dimension is made of.
	 * @return \Mouf\Database\Dbstats\DB_StatColumn[]
	 */
	public function getColumns(){
		return $this->columns;
	}
}
?>