<?php

namespace Database\Seeders;

use App\Models\StorageProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class StorageProviderSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('storage_providers')) {
            $this->command?->warn('جدول storage_providers هنوز ساخته نشده است؛ از این مرحله عبور شد.');
            return;
        }

        StorageProvider::query()->update(['is_default' => false]);

        StorageProvider::updateOrCreate(
            ['name' => 'ذخیره‌سازی محلی'],
            [
                'type' => 'local',
                'config' => ['disk' => 'local'],
                'is_active' => true,
                'is_default' => true,
            ]
        );

        $this->command?->info('Storage Provider محلی به‌عنوان Provider پیش‌فرض فعال شد.');
    }
}
