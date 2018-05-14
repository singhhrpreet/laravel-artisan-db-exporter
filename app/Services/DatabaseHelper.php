<?php

namespace App\Services;

class DatabaseHelper {

	public function getAsArray($table)
	{
		return \DB::table($table)->get()->toArray();
	}

	public function fromAssocToCsv($array, $as_array = true) {
		
		$body = [];

		if(!isset($array[0]))
			return $body;
		
		$headers = array_keys((Array) $array[0]);

		if(!$as_array) {
			//array values are separated by comma and appended to final string, just like csv
			$body = implode(",", $headers);

			foreach ($array as $index => $row) {
				$body .= "\n" . implode(",", array_values((Array) $row));
			}

		} else {
			//array values are pushed to the final result
			$body[] = $headers;

			foreach ($array as $index => $row) {
				$body[] = array_values((Array) $row);
			}
		}

		return $body;
	}

	public function fromCsvToAssoc($csv) {

		$result = [];

		$rows = explode("\n", $csv);

		$header = array_shift($rows);
		$header = explode(",", $header);

		$body = $rows;
		foreach ($body as $index => $row) {
			$row_as_array = explode(",", $row);

			$result_row = [];
			foreach ($header as $key => $value) {
				if(isset($row_as_array[$key])) {
					$result_row[trim($value)] = trim($row_as_array[$key]) != "" ? trim($row_as_array[$key]) : NULL;	
				} else {
					$result_row[trim($value)] = NULL;
				}
			}

			$result[] = $result_row;
			// var_dump($result);
			// die();
		}

		return $result;

	}
}