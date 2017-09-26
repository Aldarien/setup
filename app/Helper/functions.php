<?php
function database() {
	return \App\Contract\DB::db();
}
function setup() {
	return \App\Contract\Setup::upload();
}
?>