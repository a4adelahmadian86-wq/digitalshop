<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class StoreCategoriesSqlSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('categories')) {
            throw new \RuntimeException('جدول categories وجود ندارد. ابتدا php artisan migrate را اجرا کنید.');
        }

        foreach (['parent_id', 'level', 'sort_order', 'status'] as $column) {
            if (!Schema::hasColumn('categories', $column)) {
                throw new \RuntimeException('ساختار دسته‌بندی کامل نیست. ابتدا php artisan migrate را اجرا کنید. ستون ناقص: '.$column);
            }
        }

        $path = base_path('store_categories_mysql.sql');
        if (!is_file($path)) {
            $response = Http::timeout(30)->get('https://raw.githubusercontent.com/a4adelahmadian86-wq/digitalshop/main/store_categories_mysql.sql');
            if (!$response->successful() || trim($response->body()) === '') {
                throw new \RuntimeException('فایل دسته‌بندی پیدا نشد و دریافت خودکار آن نیز ناموفق بود.');
            }
            file_put_contents($path, $response->body());
        }

        $sql = file_get_contents($path);
        if ($sql === false || trim($sql) === '') {
            throw new \RuntimeException('فایل دسته‌بندی خالی یا غیرقابل خواندن است.');
        }

        $sql = preg_replace('/^\s*(SET\s+NAMES[^;]+;|SET\s+FOREIGN_KEY_CHECKS[^;]+;|START\s+TRANSACTION\s*;|COMMIT\s*;|ROLLBACK\s*;)/im', '', $sql) ?? $sql;
        $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
        $statements = array_values(array_filter(array_map('trim', preg_split('/;\s*/', $sql) ?: [])));

        DB::transaction(function () use ($statements) {
            foreach ($statements as $statement) {
                if ($statement !== '') {
                    DB::unprepared($statement);
                }
            }
        });

        $count = DB::table('categories')->count();
        $this->command?->info('دسته‌بندی‌ها با موفقیت بررسی/وارد شدند. تعداد فعلی: '.$count);
    }
}
