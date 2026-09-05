<?php

namespace App\Services\Sms;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class KPanelSmsProvider implements SmsProviderInterface
{
    private function apiKey(): string
    {
        $stored=(string)SiteSetting::getValue('sms.kpanel.api_key','');
        if ($stored!=='') {
            try { return Crypt::decryptString($stored); } catch (Throwable $e) {}
        }
        return (string)config('ippanel.api_key','');
    }

    public function sendOtp(string $phone,string $code): bool
    {
        $apiKey=$this->apiKey();
        $baseUrl=rtrim(config('ippanel.base_url','https://edge.ippanel.com/v1/api'),'/');
        $from=config('services.kpanel.from');
        $pattern=config('services.kpanel.pattern');

        if (!$apiKey || !$from || !$pattern) {
            Log::error('KPanel SMS configuration is incomplete.',[
                'has_api_key'=>!empty($apiKey),'has_from'=>!empty($from),'has_pattern'=>!empty($pattern),
            ]);
            throw new RuntimeException('ارسال پیامک در حال حاضر امکان‌پذیر نیست.');
        }

        $url=$baseUrl.'/send';
        $payload=[
            'sending_type'=>'pattern','from_number'=>$from,'code'=>$pattern,
            'recipients'=>[$phone],'params'=>['code'=>$code],
        ];

        try {
            $response=Http::timeout(15)->withHeaders([
                'Content-Type'=>'application/json','Authorization'=>$apiKey,
            ])->post($url,$payload);
        } catch (Throwable $e) {
            Log::error('KPanel SMS HTTP request failed',['exception'=>$e->getMessage(),'url'=>$url]);
            throw new RuntimeException('ارسال پیامک در حال حاضر امکان‌پذیر نیست.');
        }

        if (!$response->successful()) {
            Log::error('KPanel SMS API returned an HTTP error',[
                'http_status'=>$response->status(),'response'=>$response->body(),'url'=>$url,
            ]);
            throw new RuntimeException('ارسال پیامک در حال حاضر امکان‌پذیر نیست.');
        }

        $data=$response->json();
        if (is_array($data) && (($data['meta']['status']??false)===true)) return true;
        if (is_array($data) && (($data['status']??false)===true)) return true;

        Log::error('KPanel SMS API returned an unsuccessful response',[
            'http_status'=>$response->status(),'response'=>$response->body(),'url'=>$url,
        ]);
        throw new RuntimeException('ارسال پیامک در حال حاضر امکان‌پذیر نیست.');
    }
}