<?php
function database() {
	$dsn = 'mysql:host=' . get('database.host') . ';dbname=' . get('database.database');
	if (get('database.port') != null) {
		$dsn .= ';port=' . get('database.port');
	}
	return new PDO($dsn, get('database.username'), get('database.password'));
}
function setup() {
	return \App\Contract\Setup::upload();
}
?>