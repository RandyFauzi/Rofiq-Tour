<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $checks = [
        'departures' => [
            'valid_seats' => 'capacity > 0 AND available_seats >= 0 AND available_seats <= capacity',
            'valid_duration' => 'duration_days BETWEEN 1 AND 90 AND deposit_amount >= 0',
            'valid_status' => "status IN ('tentative', 'confirmed', 'cancelled')",
        ],
        'departure_rates' => [
            'valid_price' => 'price > 0',
            'valid_room' => "tier IN ('silver', 'platinum') AND room_type IN ('quad', 'triple', 'double')",
        ],
        'hotels' => ['valid_stars' => 'stars BETWEEN 1 AND 5 AND (distance_meters IS NULL OR distance_meters >= 0)'],
        'package_features' => ['valid_type' => "type IN ('included', 'excluded')"],
        'inquiries' => ['valid_travelers' => 'travelers BETWEEN 1 AND 100', 'valid_status' => "status IN ('new', 'contacted', 'closed')"],
        'site_settings' => ['singleton' => 'id = 1'],
    ];

    public function up(): void
    {
        DB::table('departures')->update(['departure_date' => DB::raw('DATE(departure_date)')]);
        foreach ($this->checks as $table => $checks) {
            foreach ($checks as $name => $condition) {
                if (DB::getDriverName() === 'sqlite') {
                    $newCondition = preg_replace('/\\b(capacity|available_seats|duration_days|deposit_amount|status|price|tier|room_type|stars|distance_meters|type|travelers|id)\\b/', 'NEW.$1', $condition);
                    foreach (['INSERT', 'UPDATE'] as $event) {
                        $trigger = $table.'_'.$name.'_'.strtolower($event);
                        DB::unprepared("CREATE TRIGGER IF NOT EXISTS {$trigger} BEFORE {$event} ON {$table} WHEN NOT ({$newCondition}) BEGIN SELECT RAISE(ABORT, 'Travel data constraint violated'); END");
                    }
                } elseif (DB::getDriverName() === 'mysql') {
                    if ($table === 'site_settings') {
                        DB::statement('ALTER TABLE site_settings MODIFY COLUMN id BIGINT UNSIGNED NOT NULL DEFAULT 1');
                    }
                    if (! $this->mysqlCheckExists($table, $name)) {
                        DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$name} CHECK ({$condition})");
                    }
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->checks as $table => $checks) {
            foreach ($checks as $name => $condition) {
                if (DB::getDriverName() === 'sqlite') {
                    foreach (['insert', 'update'] as $event) {
                        DB::unprepared("DROP TRIGGER IF EXISTS {$table}_{$name}_{$event}");
                    }
                } elseif (DB::getDriverName() === 'mysql') {
                    if ($this->mysqlCheckExists($table, $name)) {
                        $version = DB::selectOne('SELECT VERSION() AS version')->version;
                        $clause = str_contains(strtolower($version), 'mariadb') ? 'DROP CONSTRAINT' : 'DROP CHECK';
                        DB::statement("ALTER TABLE {$table} {$clause} {$table}_{$name}");
                    }
                    if ($table === 'site_settings') {
                        DB::statement('ALTER TABLE site_settings MODIFY COLUMN id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
                    }
                }
            }
        }
    }

    private function mysqlCheckExists(string $table, string $name): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $table.'_'.$name)
            ->where('CONSTRAINT_TYPE', 'CHECK')
            ->exists();
    }
};
