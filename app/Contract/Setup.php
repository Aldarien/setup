<?php
namespace App\Contract;

use App\Service\Setup as SetupService;

class Setup
{
	use Contract;
	
	protected static function newInstance()
	{
		return new SetupService();
	}
	public static function upload()
	{
		$instance = self::getInstance();
		return $instance->upload();
	}
}
?>