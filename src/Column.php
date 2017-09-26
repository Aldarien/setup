<?php
class Column
{
	protected $name;
	protected $type;
	protected $attributes;
	protected $options;
	
	public function setName(string $name)
	{
		$this->name = $name;
		return $this;
	}
	public function setType(string $type, $options = null)
	{
		switch (strtolower($type)) {
			case 'string':
				$type = 'VARCHAR';
				if ($options != null) {
					$this->setOptions($options);
				}
				break;
			case 'int':
			case 'integer':
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
			case 'bit':
			case 'time':
			case 'timestamp':
			case 'datetime':
			case 'binary':
			case 'varbinary':
				if ($options != null) {
					$this->setOptions($options);
				}
			case 'date':
			case 'year':
			case 'tinyblob':
			case 'blob':
			case 'mediumblob':
			case 'longblob':
			case 'json':
				$type = strtoupper($type);
				break;
			default:
				return $this;
		}
		$this->type = $type;
		
		return $this;
	}
	public function setAttributes(array $attributes)
	{
		foreach ($attributes as $attr => $val) {
			$this->addAttribute($attr, $val);
		}
		return $this;
	}
	public function addAttribute(string $name, bool $value = true)
	{
		switch (strtolower($name)) {
			case 'key':
			case 'primary':
			case 'primary key':
				if (isset($this->attributes['UNIQUE KEY'])) {
					unset($this->attributes['UNIQUE KEY']);
				}
				$this->attributes['PRIMARY KEY'] = $value;
				$this->addAttribute('auto_increment');
				break;
			case 'unique':
			case 'unique key':
				if (isset($this->attributes['PRIMARY KEY'])) {
					break;
				}
				$this->attributes['UNIQUE KEY'] = $value;
				break;
			case 'unsigned':
			case 'binary':
			case 'zerofill':
				if (!$this->validAttribute($name)) {
					break;
				}
			case 'auto_increment':
			case 'null':
				$this->attributes[strtoupper($name)] = $value;
				break;
		}
		$this->sortAttributes();
		return $this;
	}
	protected function validAttribute(string $attribute)
	{
		switch (strtolower($this->type)) {
			case 'date':
			case 'year':
			case 'tinyblob':
			case 'blob':
			case 'mediumblob':
			case 'longblob':
			case 'json':
			case 'bit':
			case 'time':
			case 'timestamp':
			case 'datetime':
			case 'binary':
			case 'varbinary':
			case 'enum':
			case 'set':
				return false;
			case 'char':
			case 'tinytext':
			case 'text':
			case 'mediumtest':
			case 'longtext':
				if (strtolower($attribute) == 'binary') {
					return true;
				}
				return false;
			case 'int':
			case 'integer':
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
				if (strtolower($attribute) == 'binary') {
					return false;
				}
				return true;
		}
	}
	protected function sortAttributes()
	{
		$keys = ['unsigned', 'zerofill', 'binary', 'null', 'auto_increment', 'primary key', 'unique key'];
		$output = [];
		foreach ($keys as $i) {
			$i = strtoupper($i);
			if (isset($this->attributes[$i])) {
				$output[$i] = $this->attributes[$i];
			}
		}
		$this->attributes = $output;
	}
	public function setOptions(array $options)
	{
		foreach ($options as $key => $value) {
			switch (strtolower($key)) {
				case 'length':
				case 'fsp':
				case 'decimals':
				case 'character set':
				case 'collate':
				case 'default':
					$this->options[strtoupper($key)] = $value;
					break;
				default:
					if (is_array($value)) {
						$this->options['LENGTH'] = implode(', ', $value);
					}
					break;
			}
		}
		return $this;
	}
	public function __get(string $name)
	{
		return $this->$name;
	}
	public function isIndex()
	{
		if (isset($this->attributes['PRIMARY']) or isset($this->attributes['KEY']) or isset($this->attributes['PRIMARY KEY'])) {
			return true;
		}
		return false;
	}
	public function getOptions()
	{
		$options = $this->options;
		if ($options == null) {
			return '';
		}
		$str = '';
		if (isset($options['LENGTH']) or isset($options['FSP']) or isset($options['DECIMALS'])) {
			$str .= '(';
			if (isset($options['LENGTH'])) {
				$str .= $options['LENGTH'];
				if (isset($options['DECIMALS'])) {
					$str .= ',' . $options['DECIMALS'];
				}
			} elseif (isset($options['FSP'])) {
				$str .= $options['FSP'];
			}
			$str .= ')';
		}
		
		unset($options['LENGTH']);
		unset($options['DECIMALS']);
		unset($options['FSP']);
		
		foreach ($options as $key => $val) {
			if (is_bool($val) and $val) {
				$str .= ' ' . $key;
				continue;
			}
			$str .= ' ' . $key . ' `' . $val . '`';
		}
		return trim($str);
	}
	public function showDefinition()
	{
		$str = ['`' . $this->name . '`', $this->type];
		if ($this->getOptions()) {
			$str []= $this->getOptions();
		}
		if ($this->attributes != null) {
			foreach ($this->attributes as $attr => $status) {
				if (!$status) {
					$str []= 'NOT';
				}
				$str []= $attr;
			}
		}
		return implode(' ', $str);
	}
}
?>