<?php
use PHPUnit\Framework\TestCase;
use App\Service\Setup;

class SetupTest extends TestCase
{
	protected $setup;
	public function setUp()
	{
		$this->setup = new Setup();
		$db = database();
		$db->query("SELECT concat('DROP TABLE IF EXISTS ', table_name, ';')
						FROM information_schema.tables
						WHERE table_schema = '" . get('database.database') . "'");
	}
	public function tearDown()
	{
		$db = database();
		/*$db->query("SELECT concat('DROP TABLE IF EXISTS ', table_name, ';')
						FROM information_schema.tables
						WHERE table_schema = '" . get('database.database') . "'");*/
	}
	public function testGetModels()
	{
		$this->assertTrue(0 < count($this->setup->getModels()));
	}
	public function testGetSQL()
	{
		$this->assertTrue(0 < count($this->setup->getSQL()));
	}
	public function testUpload()
	{
		$this->setup->upload();
		$db = database();
		$st = $db->query('SHOW TABLES');
		var_dump($st);
	}
}
?>