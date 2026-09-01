<?php

namespace App\Services\Phone;

class IranPhoneValidator
{
    /**
     * پیش‌شماره‌های قابل قبول موبایل ایران
     */
    private const VALID_PREFIXES = [
        '0901',
        '0902',
        '0903',
        '0904',
        '0905',

        '0910',
        '0911',
        '0912',
        '0913',
        '0914',
        '0915',
        '0916',
        '0917',
        '0918',
        '0919',

        '0920',
        '0921',
        '0922',

        '0930',
        '0933',
        '0935',
        '0936',
        '0937',
        '0938',
        '0939',

        '0940',

        '0990',
        '0991',
        '0992',
        '0993',
        '0994',
        '0995',
        '0996',
        '0997',
        '0998',
        '0999',
    ];

    /**
     * شماره را به فرمت استاندارد 09xxxxxxxxx تبدیل می‌کند.
     */
    public function normalize(string $phone): string
    {
        $phone = trim($phone);

        // اعداد فارسی
        $phone = strtr($phone, [
            '۰' => '0',
            '۱' => '1',
            '۲' => '2',
            '۳' => '3',
            '۴' => '4',
            '۵' => '5',
            '۶' => '6',
            '۷' => '7',
            '۸' => '8',
            '۹' => '9',
        ]);

        // اعداد عربی
        $phone = strtr($phone, [
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
            '٣' => '3',
            '٤' => '4',
            '٥' => '5',
            '٦' => '6',
            '٧' => '7',
            '٨' => '8',
            '٩' => '9',
        ]);

        // حذف فاصله، خط تیره، پرانتز و سایر کاراکترهای اضافی
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // +98xxxxxxxxxx
        if (str_starts_with($phone, '+98')) {
            $phone = '0' . substr($phone, 3);
        }

        // 0098xxxxxxxxxx
        elseif (str_starts_with($phone, '0098')) {
            $phone = '0' . substr($phone, 4);
        }

        // 98xxxxxxxxxx
        elseif (str_starts_with($phone, '98')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }

    /**
     * اعتبارسنجی کامل شماره.
     */
    public function isValid(string $phone): bool
    {
        $phone = $this->normalize($phone);

        // دقیقاً 11 رقم
        if (!preg_match('/^09\d{9}$/', $phone)) {
            return false;
        }

        // پیش‌شماره معتبر
        if (!$this->hasValidPrefix($phone)) {
            return false;
        }

        // رد الگوهای کاملاً ساختگی
        if ($this->looksFake($phone)) {
            return false;
        }

        return true;
    }

    /**
     * بررسی پیش‌شماره.
     */
    private function hasValidPrefix(string $phone): bool
    {
        $prefix = substr($phone, 0, 4);

        return in_array(
            $prefix,
            self::VALID_PREFIXES,
            true
        );
    }

    /**
     * رد شماره‌هایی که به وضوح برای تست/شوخی ساخته شده‌اند.
     */
    private function looksFake(string $phone): bool
    {
        $digits = substr($phone, 4);

        // تمام ارقام یکسان
        if (preg_match('/^(\d)\1{6}$/', $digits)) {
            return true;
        }

        // الگوهای تکراری واضح
        $fakePatterns = [
            '0000000',
            '1111111',
            '2222222',
            '3333333',
            '4444444',
            '5555555',
            '6666666',
            '7777777',
            '8888888',
            '9999999',
        ];

        return in_array(
            $digits,
            $fakePatterns,
            true
        );
    }
}