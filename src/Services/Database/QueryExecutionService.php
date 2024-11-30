<?php

namespace dougkusanagi\LaravelAiChat\Services\Database;

use Illuminate\Support\Facades\DB;

class QueryExecutionService
{
	public function execute(string $query): string
	{
		$queryResult = DB::select($query);
		return json_encode($queryResult);
	}
}
