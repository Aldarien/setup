<?php
namespace App\Service;

use Composer\Factory;
use Composer\IO\BufferIO;

class Setup
{
	protected $models;
	protected $creations;

	public function __construct()
	{
		$this->getSpecList();
	}
	public function getSpecList()
	{
		$composer = Factory::create(new BufferIO());
		$it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($composer->getConfig()->get('vendor-dir')));
		foreach ($it as $dir) {
			if ($dir->isDir()) {
				continue;
			}
			if (strpos($dir->getBasename('.' . $dir->getExtension()), 'setup') !== false) {
				\App\Contract\Config::addFile($dir->getRealPath());
			}
		}
		return $this;
	}
    public function getModelList()
    {
		$data = config('setup');
		if ($data != null and isset($data['models'])) {
			foreach ($data['models'] as $model => $info) {
				$entity = new \Entity();
				$entity->setModel($model);
				$entity->setTable($info['table']);
				foreach ($info['columns'] as $col => $col_info) {
					$column = new \Column();
					$column->setName($col)->setType($col_info['type']);
					if (isset($col_info['attributes'])) {
						$column->setAttributes($col_info['attributes']);
					}
					if (isset($col_info['options'])) {
						$column->setOptions($col_info['options']);
					}
					$entity->add($column);
				}
				$this->models []= $entity;
			}
		}
		return $this;
    }
    public function getModels()
    {
    	if ($this->models == null) {
    		$this->getModelList();
    	}
    	return $this->models;
    }
    public function getSQL()
    {
    	if ($this->models == null) {
    		$this->getModelList();
    	}
    	foreach ($this->models as $entity) {
    		$this->creations []= $entity->showDefinition() . ';';
    	}
    	return $this->creations;
    }
    public function upload()
    {
    	if ($this->creations == null) {
    		$this->getSQL();
    	}
    	$db = database();
    	try {
	    	foreach ($this->creations as $create) {
	    		$st = $db->query($create);
	    	}
	    	return true;
    	} catch (\PDOException $e) {
    		var_dump($e->getMessage());
    	}
    	return false;
    }
}
