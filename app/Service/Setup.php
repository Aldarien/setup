<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use Stringy\Stringy;

class Setup
{
	protected $directory;
	protected $models;
	protected $migrations;
	
    public function findDirectory()
    {
    	$directory = 'setup';
    	if (config('setup.directory') != null) {
    		$directory = config('setup.directory');
    	}
    	$dir = realpath(root() . '/' . $directory);
    	if($dir) {
    		$this->directory = $dir;
    		return $dir;
    	}
    	throw new \DomainException('Directory ' . $dir . ' not found.');
    }

    protected function readSetups()
    {
    	if ($this->directory == null) {
    		$this->findDirectory();
    	}
    	if ($this->models == null) {
    		$this->models = [];
    	}
    	$files = glob($this->directory . '/*.{php,json,yml}', GLOB_BRACE);
    	foreach ($files as $file) {
			$info = pathinfo($file);
			switch ($info['extension']) {
				case 'php':
					$data = $this->readPhp($file);
					break;
				case 'json':
					$data = $this->readJson($file);
					break;
				case 'yml':
					$data = $this->readYaml($file);
					break;
				default:
					throw new \DomainException('File type incorrect for ' . $info['filename']);
			}
			if (isset($data['models'])) {
				$this->models = array_merge($this->models, $data['models']);
			} else {
				throw new \OutOfBoundsException('models not found in ' . $info['filename']);
			}
		}
		$this->models = array_unique($this->models);
    }

    protected function readPhp($filename)
    {
        return include_once $filename;
    }

    protected function readJson($filename)
    {
    	return json_decode(file_get_contents($filename));
    }

    protected function readYaml($filename)
    {
    	return Yaml::parse(file_get_contents($filename));
    }

    public function findModels()
    {
    	if ($this->models == null) {
    		$this->readSetups();
    	}
    	foreach ($this->models as $i => $model) {
			if (is_object($model)) {
				continue;
			}
			$class = $this->getClassName($model);
			if ($class) {
				$this->models[$i] = $class;
			} else {
				unset($this->models[$i]);
			}
		}
    }
    
    protected function getClassName($model)
    {
    	$classes = get_declared_classes();
    	foreach ($classes as $class) {
    		$info = explode('\\', $class);
    		if ($info[count($info) - 1] == $model) {
    			return $class;
    		}
    	}
    	return '\\' . $model;
    }

    protected function getProperties($model)
    {
		return get_class_vars($model);
    }
    public function getTableName($model)
    {
    	if (property_exists($model, '_table')) {
    		return $model::$_table;
    	}
    	$info = explode('\\', $model);
    	if (!property_exists($model, '_table_use_short_name')) {
    		return '' . Stringy::create(array_pop($info))->underscored();
    	}
    	$output = [];
    	foreach ($info as $part) {
    		$output = '' . Stringy::create($part)->underscored();
    	}
    	return implode('_', $output);
    }

    public function createMigrations()
    {
    	if ($this->models == null) {
    		$this->findModels();
    	}
        foreach ($this->models as $model) {
        	$properties = $this->getProperties($model);
        }
    }
}
