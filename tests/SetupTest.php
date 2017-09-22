<?php
use PHPUnit\Framework\TestCase;

class SetupTest extends TestCase
{
	protected $setup;
	public function setUp()
	{
		mkdir(root() . '/setup');
		$models = ['Test'];
		file_put_contents(root() . '/setup/models.json', json_encode(['models' => $models], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		class Test {
			public $id;
			public static $_table = 'test';
			public $name;
		}
		$this->setup = new Setup();
	}
	public function testGetModelNames()
	{
		$this->assertEquals(['Test'], $this->setup->getModelList());
	}
}
?>