<?php
namespace App\Contract;

use App\Definition\Contract;

class DB
{
	use Contract;
	
	protected static function newInstance()
	{
		$dsn = 'mysql:host=' . config('databases.mysql.host') . ';dbname=' . config('databases.mysql.database');
		if (config('databases.mysql.port') != null) {
			$dsn .= ';port=' . config('databases.mysql.port');
		}
		return new \PDO($dsn, config('databases.mysql.username'), config('databases.mysql.password'));
	}
	public static function db()
	{
		return self::getInstance();
	}
}
?>