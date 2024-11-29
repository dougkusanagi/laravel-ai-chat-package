<?php

namespace App\Services\Database;

use Illuminate\Support\Facades\Schema;

class DatabaseSchemaService
{
	private array $ignoreTables = [
		'migrations',
		'cache',
		'cache_locks',
		'password_reset_tokens',
		'telescope_entries',
		'telescope_entries_tags',
		'telescope_monitoring',
		'pulse_values',
		'pulse_entries',
		'pulse_aggregates'
	];

	public function getSchema(): string
	{
		$tables = Schema::getTables();
		$schema = "";

		foreach ($tables as $table) {
			if (in_array($table['name'], $this->ignoreTables)) continue;

			$schema .= $this->getTableSchema($table['name']);
		}

		return $schema;
	}

	private function getTableSchema(string $tableName): string
	{
		$schema = "Table: {$tableName}\n";
		$columns = Schema::getColumnListing($tableName);

		foreach ($columns as $column) {
			$type = Schema::getColumnType($tableName, $column);
			$schema .= "- {$column} ({$type})\n";
		}

		return $schema . "\n";
	}
}
