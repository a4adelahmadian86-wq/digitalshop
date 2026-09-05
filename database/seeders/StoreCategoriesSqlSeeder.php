<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreCategoriesSqlSeeder extends Seeder
{
    public function run(): void
    {
        $path=base_path('store_categories_mysql.sql');
        if(!is_file($path))throw new \RuntimeException('فایل store_categories_mysql.sql در ریشه پروژه پیدا نشد. فایل دسته‌بندی آماده را در ریشه پروژه قرار دهید و Seeder را دوباره اجرا کنید.');
        $sql=file_get_contents($path);if($sql===false||trim($sql)==='')throw new \RuntimeException('فایل دسته‌بندی خالی یا غیرقابل خواندن است.');
        $sql=preg_replace('/^\s*(SET\s+NAMES.*?;|SET\s+FOREIGN_KEY_CHECKS.*?;|START\s+TRANSACTION\s*;|COMMIT\s*;|ROLLBACK\s*;)/ims','',$sql)??$sql;
        $statements=preg_split('/;\s*(?=INSERT\s+INTO|UPDATE\s+|DELETE\s+|ALTER\s+|CREATE\s+)/i',$sql,-1,PREG_SPLIT_NO_EMPTY);
        DB::transaction(function()use($statements){foreach($statements as $statement){$statement=trim(preg_replace('/^--.*$/m','',$statement));if($statement!=='')DB::unprepared($statement);}});
        $count=DB::table('categories')->count();$this->command?->info('دسته‌بندی‌ها وارد شدند. تعداد فعلی: '.$count);
    }
}
