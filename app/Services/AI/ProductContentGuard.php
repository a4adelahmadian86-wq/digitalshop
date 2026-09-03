<?php

namespace App\Services\AI;

use Illuminate\Support\Str;

class ProductContentGuard
{
    public function inspect(array $product, string $fileText = ''): array
    {
        $source = trim($fileText);
        $description = trim((string) ($product['description'] ?? ''));
        $short = trim((string) ($product['short_description'] ?? ''));

        $findings = [];
        $status = 'ready_for_review';

        if ($source === '') {
            $findings[] = [
                'severity' => 'warning',
                'code' => 'file_text_unavailable',
                'message' => 'متن قابل تحلیل از فایل در دسترس نیست؛ تأیید محتوای فایل باید توسط مدیر انجام شود.',
            ];
            $status = 'needs_review';
        }

        $claims = [
            'بهترین', 'اولین', 'کامل ترین', 'کامل‌ترین', 'تضمینی',
            'صددرصد', '100%', 'قطعی', 'بدون نقص', 'بی‌نظیر', 'بی نظیر',
            'ارزان‌ترین', 'ارزان ترین', 'پرفروش‌ترین', 'پرفروش ترین',
        ];

        foreach ($claims as $claim) {
            if (Str::contains(mb_strtolower($description . ' ' . $short), mb_strtolower($claim))) {
                $findings[] = [
                    'severity' => 'warning',
                    'code' => 'marketing_claim',
                    'message' => "عبارت «{$claim}» یک ادعای تبلیغاتی است و باید با محتوای واقعی فایل قابل اثبات باشد.",
                ];
            }
        }

        if ($description === '' && $short === '') {
            $findings[] = [
                'severity' => 'warning',
                'code' => 'empty_description',
                'message' => 'توضیحات محصول خالی است و باید بر اساس محتوای واقعی فایل تکمیل شود.',
            ];
        }

        $hasBlocking = collect($findings)->contains(fn ($item) => $item['severity'] === 'blocking');
        if ($hasBlocking) {
            $status = 'blocked';
        }

        return [
            'status' => $status,
            'score' => max(0, 100 - count($findings) * 10),
            'findings' => $findings,
            'evidence' => $source !== '' ? ['file_text_available' => true] : [],
        ];
    }
}
