<?php

namespace App\Services\Storage;

use App\Models\StorageProvider;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;

class StorageManager
{
    public function provider(StorageProvider $provider): StorageProviderInterface
    {
        $config=$provider->config??[];
        return match($provider->type){
            'local'=>new LocalStorageProvider($config['disk']??'local'),
            'api'=>new ApiStorageProvider($config['endpoint']??throw new \RuntimeException('API Storage endpoint تنظیم نشده است.'),$this->apiKey($config),(int)($config['timeout']??120),$config),
            default=>throw new \RuntimeException("Storage provider type [{$provider->type}] پشتیبانی نمی‌شود."),
        };
    }

    protected function apiKey(array $config): string
    {
        if(!empty($config['api_key_encrypted'])){try{return Crypt::decryptString($config['api_key_encrypted']);}catch(\Throwable $e){throw new \RuntimeException('API Key ذخیره‌شده قابل رمزگشایی نیست.');}}
        return (string)($config['api_key']??'');
    }

    public function for(string $scope,string $location='external'): StorageProvider
    {
        $provider=StorageProvider::query()->where('scope',$scope)->where('location',$location)->where('is_active',true)->orderBy('priority')->first();
        if(!$provider) throw new \RuntimeException("برای مسیر {$scope}/{$location} Storage فعال وجود ندارد.");
        return $provider;
    }

    public function defaultProvider(): StorageProvider
    {
        return StorageProvider::query()->where('is_active',true)->where('is_default',true)->orderBy('priority')->firstOrFail();
    }

    public function upload(StorageProvider $provider,UploadedFile $file,string $path): string
    {
        $this->assertLimits($provider,$file);
        return $this->provider($provider)->put($file,$path);
    }

    public function uploadTo(string $scope,UploadedFile $file,string $path,string $location='external'): string
    {
        return $this->upload($this->for($scope,$location),$file,$path);
    }

    protected function assertLimits(StorageProvider $provider,UploadedFile $file): void
    {
        $c=$provider->config??[]; $size=(int)$file->getSize();
        $maxFile=(int)($c['max_file_bytes']??0); $limit=(int)($c['limit_bytes']??0); $used=(int)($c['used_bytes']??0); $maxFiles=(int)($c['max_files']??0);
        if($maxFile>0 && $size>$maxFile) throw new \RuntimeException('حجم فایل از سقف Provider بیشتر است.');
        if($limit>0 && $used+$size>$limit) throw new \RuntimeException('فضای آزاد Provider برای این فایل کافی نیست.');
        if($maxFiles>0 && isset($c['used_files']) && (int)$c['used_files']>=$maxFiles) throw new \RuntimeException('تعداد فایل‌های مجاز Provider تکمیل شده است.');
    }
}
