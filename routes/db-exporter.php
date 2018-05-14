<?php

Artisan::command('my:backup {table}', function ($table) {

	$this->info('Backing Up : ' . $table);

	$db = new \App\Services\DatabaseHelper;

	$data = $db->getAsArray($table); // Get all rows from table

	$data = $db->fromAssocToCsv($data, false); // Data from table to be converted into CSV format


	$file = $table . '.csv';

	\Storage::put($file, $data);
	
	$this->info('Backed up as ' . $file);

});

Artisan::command('my:truncate {table}', function ($table) {

	$this->info('Truncating Table : ' . $table);

	$response = $this->ask('Are you sure you want to Truncate? B:Backup First then Truncate, Y:Truncate without Backup, N:Cancel Operation!');

	if($response == "N" || $response == "n" || $response == "")
		return $this->info('Cancelled Operation');


	if($response == "B" || $response == "b") {
		$this->info('Backingup First');
		Artisan::call('my:backup', [
			'table' => $table
		]);
	}

	if($response != "Y" && $response != "y" && $response != "B" && $response != "b")
		return $this->info('Wrong Option. Cancelled!');

	$this->info('Truncating the Table');

	\DB::table($table)->truncate();

	$this->info('Truncated');

});


Artisan::command('my:restore {table}', function ($table) {

	$this->info('Restoring : ' . $table);

	$db = new \App\Services\DatabaseHelper;
	
	$file = $table . '.csv';
	$csv = \Storage::get($file);

	$data = $db->fromCsvToAssoc($csv); // Data from CSV to be converted into ASSOC format for insert

	\DB::table($table)->insert($data);

	$this->info('Restored');
	
});