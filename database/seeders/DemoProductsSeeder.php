<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\StorageProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('products') || !Schema::hasTable('product_files')) {
            $this->command?->error('جدول products یا product_files هنوز ساخته نشده است. ابتدا php artisan migrate را اجرا کنید.');
            return;
        }

        $category = Category::query()
            ->where('is_active', true)
            ->when(Schema::hasColumn('categories', 'status'), fn ($q) => $q->where('status', true))
            ->orderBy('level')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->first();

        if (!$category) {
            $this->command?->error('هیچ دسته‌بندی فعالی پیدا نشد. ابتدا دسته‌بندی‌های فروشگاه را وارد کنید.');
            return;
        }

        if (!Schema::hasTable('storage_providers')) {
            $this->command?->error('جدول storage_providers وجود ندارد. ابتدا migrationها را اجرا کنید.');
            return;
        }

        $provider = StorageProvider::query()
            ->where('is_active', true)
            ->where('is_default', true)
            ->first()
            ?: StorageProvider::query()->where('is_active', true)->orderBy('id')->first();

        if (!$provider) {
            $provider = StorageProvider::updateOrCreate(
                ['name' => 'ذخیره‌سازی محلی'],
                [
                    'type' => 'local',
                    'config' => ['disk' => 'local'],
                    'is_active' => true,
                    'is_default' => true,
                ]
            );
        }

        StorageProvider::query()->where('id', '!=', $provider->id)->update(['is_default' => false]);
        $provider->update(['is_active' => true, 'is_default' => true]);

        $items = [
            ['slug' => 'demo-word-project', 'title' => 'فایل آزمایشی ورد — پروژه نمونه', 'file' => 'demo-word-project.pdf', 'price' => 35000],
            ['slug' => 'demo-excel-report', 'title' => 'فایل آزمایشی اکسل — گزارش نمونه', 'file' => 'demo-excel-report.pdf', 'price' => 49000],
            ['slug' => 'demo-web-guide', 'title' => 'فایل آزمایشی طراحی سایت — راهنمای نمونه', 'file' => 'demo-web-guide.pdf', 'price' => 79000],
        ];

        $disk = Storage::disk('local');
        $emptySha = hash('sha256', '');

        foreach ($items as $item) {
            $path = 'demo-products/'.$item['file'];
            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $category->id,
                    'storage_provider_id' => $provider->id,
                    'title' => $item['title'],
                    'short_description' => 'محصول آزمایشی برای بررسی کامل رابط کاربری فروشگاه.',
                    'description' => '<p>محصول آزمایشی برای بررسی صفحه محصول، کارت محصول، سبد خرید و مسیر فایل. این فایل عمداً صفر بایت است و فقط برای تست استفاده می‌شود.</p>',
                    'seo_keywords' => 'آزمایشی, تست محصول, فایل دیجیتال',
                    'meta_title' => $item['title'],
                    'meta_description' => 'محصول آزمایشی فروشگاه فایل برای بررسی صفحه محصول.',
                    'file_format' => '.pdf',
                    'page_count' => 0,
                    'price' => $item['price'],
                    'file_name' => $item['file'],
                    'file_path' => $path,
                    'thumbnail' => 'Images/pdf.png',
                    'is_published' => true,
                    'approval_status' => 'approved',
                ]
            );

            $disk->put($path, '');
            $file = ProductFile::query()->firstOrNew(['product_id' => $product->id]);
            $file->fill([
                'storage_provider_id' => $provider->id,
                'original_name' => $item['file'],
                'stored_name' => $item['file'],
                'storage_path' => $path,
                'mime_type' => 'application/pdf',
                'extension' => 'pdf',
                'size' => 0,
                'sha256' => $emptySha,
                'sort_order' => 1,
            ])->save();

            $this->command?->line('محصول آزمایشی #'.$product->id.' آماده شد: '.$product->title);
        }

        $this->command?->info('۳ محصول آزمایشی با فایل‌های صفر بایت ساخته/بروزرسانی شدند.');
    }
}
