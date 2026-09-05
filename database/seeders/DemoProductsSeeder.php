<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\StorageProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            ->whereNotNull('id')
            ->orderBy('level')
            ->orderBy('sort_order')
            ->first();

        if (!$category) {
            $this->command?->error('هیچ دسته‌بندی فعالی پیدا نشد. ابتدا دسته‌بندی‌های فروشگاه را وارد کنید.');
            return;
        }

        $provider = StorageProvider::query()
            ->where('is_active', true)
            ->where('is_default', true)
            ->first()
            ?? StorageProvider::query()->where('is_active', true)->orderBy('id')->first();

        if (!$provider) {
            $this->command?->error('هیچ Storage Provider فعالی پیدا نشد.');
            return;
        }

        $items = [
            [
                'slug' => 'demo-word-project',
                'title' => 'فایل آزمایشی ورد — پروژه نمونه',
                'file' => 'demo-word-project.pdf',
                'format' => '.pdf',
                'price' => 35000,
                'description' => 'محصول آزمایشی برای بررسی صفحه محصول، کارت محصول، سبد خرید و جریان دانلود. فایل این محصول عمداً خالی است و فقط برای تست رابط کاربری و مسیر فایل استفاده می‌شود.',
            ],
            [
                'slug' => 'demo-excel-report',
                'title' => 'فایل آزمایشی اکسل — گزارش نمونه',
                'file' => 'demo-excel-report.pdf',
                'format' => '.pdf',
                'price' => 49000,
                'description' => 'محصول آزمایشی دوم برای بررسی قیمت، مشخصات فایل، محصولات مرتبط و بخش پیشنهادها. فایل واقعی نیست و محتوای آن عمداً خالی نگه داشته شده است.',
            ],
            [
                'slug' => 'demo-web-guide',
                'title' => 'فایل آزمایشی طراحی سایت — راهنمای نمونه',
                'file' => 'demo-web-guide.pdf',
                'format' => '.pdf',
                'price' => 79000,
                'description' => 'محصول آزمایشی سوم برای تست صفحه محصول و نمایش سه محصول در کنار هم. این فایل صفر بایت است و فقط وجود فایل و مسیر ذخیره‌سازی را تست می‌کند.',
            ],
        ];

        $disk = Storage::disk('local');
        $emptySha = hash('sha256', '');

        foreach ($items as $item) {
            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $category->id,
                    'storage_provider_id' => $provider->id,
                    'title' => $item['title'],
                    'short_description' => 'محصول آزمایشی برای بررسی کامل رابط کاربری فروشگاه.',
                    'description' => '<p>'.$item['description'].'</p>',
                    'seo_keywords' => 'آزمایشی, تست محصول, فایل دیجیتال',
                    'meta_title' => $item['title'],
                    'meta_description' => 'محصول آزمایشی فروشگاه فایل برای بررسی صفحه محصول.',
                    'file_format' => $item['format'],
                    'page_count' => 0,
                    'price' => $item['price'],
                    'file_name' => $item['file'],
                    'file_path' => 'demo-products/'.$item['file'],
                    'thumbnail' => 'Images/pdf.png',
                    'is_published' => true,
                    'approval_status' => 'approved',
                ]
            );

            $path = 'demo-products/'.$item['file'];
            $disk->put($path, '');

            $existing = ProductFile::query()->where('product_id', $product->id)->first();
            if ($existing) {
                $existing->update([
                    'storage_provider_id' => $provider->id,
                    'original_name' => $item['file'],
                    'stored_name' => $item['file'],
                    'storage_path' => $path,
                    'mime_type' => 'application/pdf',
                    'extension' => 'pdf',
                    'size' => 0,
                    'sha256' => $emptySha,
                    'sort_order' => 1,
                ]);
            } else {
                ProductFile::create([
                    'product_id' => $product->id,
                    'storage_provider_id' => $provider->id,
                    'original_name' => $item['file'],
                    'stored_name' => $item['file'],
                    'storage_path' => $path,
                    'mime_type' => 'application/pdf',
                    'extension' => 'pdf',
                    'size' => 0,
                    'sha256' => $emptySha,
                    'sort_order' => 1,
                ]);
            }

            $this->command?->line('محصول آزمایشی #'.$product->id.' آماده شد: '.$product->title);
        }

        $this->command?->info('۳ محصول آزمایشی با فایل‌های صفر بایت ساخته/بروزرسانی شدند.');
    }
}
