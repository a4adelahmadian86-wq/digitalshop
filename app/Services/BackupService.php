<?php

namespace App\Services;

use App\Services\Storage\StorageManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class BackupService
{
    public function __construct(private StorageManager $storage)
    {
    }

    public function run(): array
    {
        $dir = storage_path('app/backup-work');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $name = 'digitalshop-'.now()->format('Ymd_His').'-'.Str::lower(Str::random(6)).'.sql';
        $file = $dir.DIRECTORY_SEPARATOR.$name;
        file_put_contents($file, $this->dumpDatabase());
        $uploaded = [];

        foreach (['internal', 'external'] as $location) {
            $provider = $this->storage->select('backups', $location, null, (int) filesize($file));
            if (! $provider) {
                continue;
            }
            try {
                $upload = $this->storage->upload(
                    $provider,
                    new UploadedFile($file, $name, 'application/sql', null, true),
                    'backups/'.now()->format('Y/m').'/'.$name
                );
                $uploaded[] = [
                    'location' => $location,
                    'provider_id' => $provider->id,
                    'path' => $upload,
                ];
            } catch (\Throwable $e) {
                report($e);
            }
        }

        @unlink($file);

        return $uploaded;
    }

    protected function dumpDatabase(): string
    {
        $pdo = DB::connection()->getPdo();
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_'.DB::getDatabaseName();
        $sql = "-- DigitalShop database backup\n-- ".now()->toIso8601String()."\nSET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $row) {
            $table = $row->$key ?? array_values((array) $row)[0];
            $quoted = '`'.str_replace('`', '``', $table).'`';
            $create = DB::selectOne('SHOW CREATE TABLE '.$quoted);
            $createSql = $create->{'Create Table'} ?? array_values((array) $create)[1];
            $sql .= "DROP TABLE IF EXISTS {$quoted};\n{$createSql};\n";
            $columns = DB::select('SHOW COLUMNS FROM '.$quoted);
            $names = array_map(fn ($c) => '`'.str_replace('`', '``', $c->Field).'`', $columns);
            $rows = DB::table($table)->get();
            foreach ($rows as $data) {
                $values = [];
                foreach ($names as $i => $column) {
                    $field = $columns[$i]->Field;
                    $value = $data->{$field};
                    $values[] = $value === null ? 'NULL' : $pdo->quote((string) $value);
                }
                $sql .= 'INSERT INTO '.$quoted.' ('.implode(',', $names).') VALUES ('.implode(',', $values).');'."\n";
            }
            $sql .= "\n";
        }

        return $sql."SET FOREIGN_KEY_CHECKS=1;\n";
    }
}
