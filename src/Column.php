<?php
class Column
{
	protected $name;
	protected $type;
	protected $length;
	protected $attributes;
	protected $options;
	protected $default;
	protected $charset;
	
	public function setName(string $name)
	{
		$this->name = $name;
	}
	public function setType(string $type, $options = null)
	{
		switch (strtolower($type)) {
			case 'integer':
				$type = 'INT';
				$this->setLength($options);
				break;
			case 'string':
				$type = 'VARCHAR';
				$this->setLength($options);
				break;
			case 'int':
			case 'varchar':
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'bigint':
			case 'real':
			case 'double':
			case 'float':
			case 'decimal':
			case 'numeric':
			case 'char':
			case 'tinytext':
			case 'text':
			case 'mediumtest':
			case 'longtext':
			case 'enum':
			case 'set':
				$this->setOptions($options);
			case 'bit':
			case 'time':
			case 'timestamp':
			case 'datetime':
			case 'binary':
			case 'varbinary':
				$this->setLength($options);
			case 'date':
			case 'year':
			case 'tinyblob':
			case 'blob':
			case 'mediumblob':
			case 'longblob':
			case 'json':
				$type = strtoupper($type);
			default:
				return;
		}
		$this->type = $type;
	}
	protected function setLength($options)
	{
		if (is_array($options) and isset($options['length'])) {
			$this->length = $options['length'];
		} elseif (!is_array($options) and is_numeric($options)) {
			$this->length = $options;
		}
	}
	public function addAttribute(string $name)
	{
		switch (strtolower($name)) {
			case 'auto_increment':
			case 'unique':
			case 'unique key':
			case 'key':
			case 'primary':
			case 'primary key':
				$this->attributes[strtoupper($name)] = true;
		}
	}
	public function __get(string $name)
	{
		return $this->$name;
	}
	public function showDefinition()
	{
		$str = ['`' . $this->name . '`', $this->type];
		switch ($this->type) {
			case 'varchar':
				$str []= '(' . $this->size . ')';
				break;
		}
		if ($this->default != null) {
			$str []= 'DEFAULT ' . $this->default;
		}
		if ($this->attributes != null) {
			
		}
	}
}
?>