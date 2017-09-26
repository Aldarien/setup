<?php
class Entity
{
	protected $table_name;
	protected $model_name;
	protected $columns;
	
	public function setTable(string $table)
	{
		$this->table_name = $table;
		return $this;
	}
	public function setModel(string $name, string $namespace = '')
	{
		$this->model_name = $namespace . '\\' . $name;
		return $this;
	}
	public function add(Column $column)
	{
		$this->columns []= $column;
		return $this;
	}
	public function showDefinition()
	{
		$str = ['CREATE TABLE IF NOT EXISTS `' . $this->table_name . '` ('];
		foreach ($this->columns as $i => $column) {
			if ($i > 0) {
				$str[count($str) -1] .= ',';
			}
			$str []= $column->showDefinition();
		}
		$str []= ')';
		$str []= 'ENGINE `InnoDB`';
		
		return implode(PHP_EOL, $str);
	}
}
?>